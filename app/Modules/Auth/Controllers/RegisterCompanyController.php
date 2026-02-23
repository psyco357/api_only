<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Request\RegisterCompanyRequest;
use App\Modules\Auth\Services\RegisterCompanyService;

class RegisterCompanyController extends Controller
{
    public function __invoke(
        RegisterCompanyRequest $request,
        RegisterCompanyService $service
    ) {
        try {
            $result = $service->handle($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Company registered successfully',
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
