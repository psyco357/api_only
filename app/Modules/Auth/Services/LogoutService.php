<?php

namespace App\Modules\Auth\Services;

use App\Models\UserSession;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutService
{
    /**
     * Logout a specific device by session_id.
     * Only the owner can logout their own session.
     */
    public function logoutDevice(string $sessionId, string $userId): void
    {
        DB::transaction(function () use ($sessionId, $userId) {
            $session = UserSession::where('id', $sessionId)
                ->where('user_id', $userId)
                ->firstOrFail();

            // Revoke the associated refresh token
            $session->refreshToken?->update(['revoked_at' => now()]);

            $session->delete();
        });
    }

    /**
     * Logout all devices for the given user, and invalidate the current JWT.
     */
    public function logoutAll(string $userId): void
    {
        DB::transaction(function () use ($userId) {
            // Revoke all refresh tokens
            $sessionIds = UserSession::where('user_id', $userId)->pluck('refresh_token_id');

            \App\Models\RefreshToken::whereIn('id', $sessionIds)
                ->whereNull('revoked_at')
                ->update(['revoked_at' => now()]);

            // Delete all sessions
            UserSession::where('user_id', $userId)->delete();
        });

        // Invalidate current JWT token
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (\Throwable) {
            // token may already be invalid, ignore
        }
    }
}
