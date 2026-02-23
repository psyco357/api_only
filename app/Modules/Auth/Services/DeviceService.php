<?php

namespace App\Modules\Auth\Services;

use App\Models\UserSession;
use Illuminate\Support\Collection;

class DeviceService
{
    private function sessionQuery()
    {
        return UserSession::with('refreshToken')
            ->whereHas('refreshToken', fn($q) => $q->whereNull('revoked_at')->where('expired_at', '>', now()));
    }

    private function format(UserSession $session, bool $isCurrent = false): array
    {
        return [
            'session_id'       => $session->id,
            'device_name'      => $session->device_name,
            'ip_address'       => $session->ip_address,
            'last_activity_at' => $session->last_activity_at?->toDateTimeString(),
            'created_at'       => $session->created_at?->toDateTimeString(),
            'is_current'       => $isCurrent,
        ];
    }

    public function getDevices(string $userId, string $currentSessionId): Collection
    {
        return $this->sessionQuery()
            ->where('user_id', $userId)
            ->orderByDesc('last_activity_at')
            ->get()
            ->map(fn(UserSession $s) => $this->format($s, $s->id === $currentSessionId));
    }

    public function getDevice(string $sessionId, string $userId): array
    {
        $session = $this->sessionQuery()
            ->where('id', $sessionId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return $this->format($session, true);
    }
}
