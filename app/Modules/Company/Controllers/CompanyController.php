<?php

namespace App\Modules\Company\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Company\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Modules\Company\Requests\CompanyRequest;

class CompanyController extends Controller
{
    public function index(CompanyService $service): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        return response()->json([
            'success' => true,
            'data'    => $service->getCompaniesForUser($user),
        ]);
    }

    public function updateCompanyInfo(CompanyRequest $request, CompanyService $service): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (! $user->hasPermission('manage_company')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update company information.',
            ], 403);
        }

        $data = $request->validated();
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided to update.',
            ], 422);
        }

        $company = $service->updateCompanyInfo($user, $data);

        return response()->json([
            'success' => true,
            'message' => 'Company information updated successfully.',
            'data'    => $company,
        ]);
    }

    public function destroy(CompanyService $service): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (! $user->hasPermission('manage_company')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this company.',
            ], 403);
        }

        $service->deleteCompany($user);

        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully.',
        ]);
    }
}
