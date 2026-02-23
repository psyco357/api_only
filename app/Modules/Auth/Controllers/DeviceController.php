<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Services\DeviceService;
use App\Modules\Auth\Services\LogoutService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeviceController extends Controller
{
    // GET /v1/auth/devices
    public function index(DeviceService $service): JsonResponse
    {
        $token     = JWTAuth::parseToken();
        $user      = $token->authenticate();
        $sessionId = (string) ($token->getPayload()->get('session_id') ?? '');

        return response()->json([
            'success' => true,
            'data'    => $service->getDevices($user->id, $sessionId),
        ]);
    }

    // GET /v1/auth/devices/current  — device yang sedang login
    public function current(DeviceService $service): JsonResponse
    {
        try {
            $token     = JWTAuth::parseToken();
            $user      = $token->authenticate();
            $sessionId = $token->getPayload()->get('session_id')
                ?? throw new \Exception('session_id claim not found in token.');

            return response()->json([
                'success' => true,
                'data'    => $service->getDevice($sessionId, $user->id),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Active session not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    // POST /v1/auth/logout  — session_id dibaca otomatis dari JWT claims
    public function logout(LogoutService $service): JsonResponse
    {
        try {
            $token   = JWTAuth::parseToken();
            $user    = $token->authenticate();
            $payload = $token->getPayload();

            $sessionId = $payload->get('session_id')
                ?? throw new \Exception('session_id claim not found in token.');

            $service->logoutDevice($sessionId, $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Device logged out successfully.',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found or does not belong to you.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    // DELETE /v1/auth/devices/{sessionId}  — logout device lain dari list
    public function logoutDevice(string $sessionId, LogoutService $service): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $service->logoutDevice($sessionId, $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Device logged out successfully.',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found or does not belong to you.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // POST /v1/auth/logout-all
    public function logoutAll(LogoutService $service): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $service->logoutAll($user->id);

            return response()->json([
                'success' => true,
                'message' => 'All devices logged out successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
