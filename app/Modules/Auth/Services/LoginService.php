<?php

namespace App\Modules\Auth\Services;

use App\Helpers\DeviceDetector;
use App\Models\Company;
use App\Models\RefreshToken;
use App\Models\User;
use App\Models\UserSession;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoginService
{
    public function handle(array $data, $request)
    {
        return DB::transaction(function () use ($data, $request) {

            $user = User::where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw new \Exception('Invalid credentials');
            }

            /** @var Company|null $company */
            $company = Company::where('id', $user->company_id)
                ->where('is_active', true)
                ->first();

            if (! $company) {
                throw new \Exception('Company not found or inactive');
            }

            if (!$user->is_active) {
                throw new \Exception('Account inactive');
            }

            // 0️⃣ Replace previous token/session only for this device (support multi-device)
            $deviceName = DeviceDetector::detect($request->userAgent());

            // Find existing sessions for this user, company, and device
            $previousSessions = UserSession::where('user_id', $user->id)
                ->where('company_id', $user->company_id)
                ->where('device_name', $deviceName)
                ->get();

            if ($previousSessions->isNotEmpty()) {
                $refreshTokenIds = $previousSessions->pluck('refresh_token_id')->filter()->all();

                // Delete sessions for this device
                UserSession::whereIn('id', $previousSessions->pluck('id'))->delete();

                // Delete linked refresh tokens for this device (so data tidak numpuk)
                if (!empty($refreshTokenIds)) {
                    RefreshToken::whereIn('id', $refreshTokenIds)->delete();
                }
            }

            // 1️⃣ Create Refresh Token
            $refreshPlain = Str::random(64);

            $refreshToken = RefreshToken::create([
                'user_id'    => $user->id,
                'company_id' => $user->company_id,
                'token_hash' => hash('sha256', $refreshPlain),
                'expired_at' => now()->addDays(7),
            ]);

            // 2️⃣ Create Session (FK refresh_token_id requires token to exist first)
            $session = UserSession::create([
                'user_id'          => $user->id,
                'company_id'       => $user->company_id,
                'refresh_token_id' => $refreshToken->id,
				'device_name'      => $deviceName,
                'ip_address'       => $request->ip(),
                'user_agent'       => $request->userAgent(),
                'last_activity_at' => now(),
            ]);

            // 3️⃣ Generate JWT — embed session_id so logout needs no body
            $accessToken = JWTAuth::claims(['session_id' => $session->id])->fromUser($user);

            $user->update([
                'last_login_at' => now(),
            ]);

            return [
                'access_token'  => $accessToken,
                'refresh_token' => $refreshPlain,
                'token_type'    => 'Bearer',
                'expires_in'    => JWTAuth::factory()->getTTL() * 60,
            ];
        });
    }
}
