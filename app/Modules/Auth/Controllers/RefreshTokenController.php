<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Request\RefreshTokenRequest;
use App\Modules\Auth\Services\RefreshTokenService;

class RefreshTokenController extends Controller
{
    public function __invoke(RefreshTokenRequest $request, RefreshTokenService $service)
    {
        try {
            $result = $service->handle($request->validated(), $request);

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully',
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }
}
