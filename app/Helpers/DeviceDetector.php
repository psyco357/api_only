<?php

namespace App\Helpers;

class DeviceDetector
{
    public static function detect(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown Device';
        }

        $ua = $userAgent;

        // ─── Bot / API clients ────────────────────────────────
        if (str_contains($ua, 'PostmanRuntime')) return 'Postman';
        if (str_contains($ua, 'insomnia'))       return 'Insomnia';
        if (str_contains($ua, 'curl'))           return 'cURL';

        // ─── OS Detection ─────────────────────────────────────
        $os = 'Unknown OS';
        if (preg_match('/Windows NT ([\d.]+)/', $ua, $m)) {
            $os = match (true) {
                $m[1] >= '10.0' => 'Windows 10/11',
                $m[1] === '6.3' => 'Windows 8.1',
                $m[1] === '6.2' => 'Windows 8',
                $m[1] === '6.1' => 'Windows 7',
                default         => 'Windows',
            };
        } elseif (preg_match('/iPhone.*OS ([\d_]+)/', $ua, $m)) {
            $os = 'iPhone iOS ' . str_replace('_', '.', $m[1]);
        } elseif (preg_match('/iPad.*OS ([\d_]+)/', $ua, $m)) {
            $os = 'iPad iOS ' . str_replace('_', '.', $m[1]);
        } elseif (preg_match('/Android ([\d.]+)/', $ua, $m)) {
            $os = 'Android ' . $m[1];
        } elseif (str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS X')) {
            $os = 'macOS';
        } elseif (str_contains($ua, 'Linux')) {
            $os = 'Linux';
        }

        // ─── Browser Detection ────────────────────────────────
        $browser = 'Unknown Browser';
        if (preg_match('/Edg\/([\d.]+)/', $ua, $m)) {
            $browser = 'Edge ' . $m[1];
        } elseif (preg_match('/OPR\/([\d.]+)/', $ua, $m)) {
            $browser = 'Opera ' . $m[1];
        } elseif (preg_match('/Chrome\/([\d.]+)/', $ua, $m)) {
            $browser = 'Chrome ' . $m[1];
        } elseif (preg_match('/Firefox\/([\d.]+)/', $ua, $m)) {
            $browser = 'Firefox ' . $m[1];
        } elseif (preg_match('/Version\/([\d.]+).*Safari/', $ua, $m)) {
            $browser = 'Safari ' . $m[1];
        }

        return "{$browser} on {$os}";
    }
}
