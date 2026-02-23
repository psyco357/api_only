<?php

namespace App\Modules\Auth\Services;

use App\Helpers\DeviceDetector;
use App\Models\RefreshToken;
use App\Models\UserSession;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RefreshTokenService
{
    public function handle(array $data, $request): array
    {
        return DB::transaction(function () use ($data, $request) {

            $tokenHash = hash('sha256', $data['refresh_token']);

            /** @var RefreshToken|null $oldToken */
            $oldToken = RefreshToken::where('token_hash', $tokenHash)->first();

            if (!$oldToken) {
                throw new \Exception('Invalid refresh token');
            }

            if ($oldToken->isRevoked()) {
                throw new \Exception('Refresh token has been revoked');
            }

            if ($oldToken->isExpired()) {
                throw new \Exception('Refresh token has expired');
            }

            $user = $oldToken->user;

            if (!$user || !$user->is_active) {
                throw new \Exception('Account inactive or not found');
            }

            // 1️⃣ Revoke old refresh token
            $oldToken->update(['revoked_at' => now()]);

            // 2️⃣ Issue new refresh token (rotation)
            $newPlain = Str::random(64);

            $newRefreshToken = RefreshToken::create([
                'user_id'    => $user->id,
                'company_id' => $user->company_id,
                'token_hash' => hash('sha256', $newPlain),
                'expired_at' => now()->addDays(7),
            ]);

            // 3️⃣ Update session: point to new refresh token & refresh last activity
            $session = UserSession::where('refresh_token_id', $oldToken->id)->firstOrFail();

            $session->update([
                'refresh_token_id' => $newRefreshToken->id,
                'device_name'      => DeviceDetector::detect($request->userAgent()),
                'ip_address'       => $request->ip(),
                'user_agent'       => $request->userAgent(),
                'last_activity_at' => now(),
            ]);

            // 4️⃣ Issue new JWT — preserve session_id claim
            $accessToken = JWTAuth::claims(['session_id' => $session->id])->fromUser($user);

            return [
                'access_token'  => $accessToken,
                'refresh_token' => $newPlain,
                'token_type'    => 'Bearer',
                'expires_in'    => JWTAuth::factory()->getTTL() * 60,
            ];
        });
    }
}
