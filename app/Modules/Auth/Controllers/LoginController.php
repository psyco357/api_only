<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Request\LoginRequest;
use App\Modules\Auth\Services\LoginService;

class LoginController extends Controller
{
    public function login(LoginRequest $request, LoginService $service)
    {
        // try {
        $result = $service->handle($request->validated(), $request);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data'    => $result,
        ]);
        // } catch (\Exception $e) {

        //     return response()->json([
        //         'success' => false,
        //         'message' => $e->getMessage()
        //     ], 401);
        // }
    }
}
