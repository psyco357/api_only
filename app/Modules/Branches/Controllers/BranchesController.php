<?php

namespace App\Modules\Branches\Controllers;

use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
// use App\Modules\Company\Services\CompanyService;
use App\Modules\Branches\Services\BranchesService;
use App\Modules\Branches\Requests\BranchesRequest;

class BranchesController extends Controller
{
    public function index(BranchesService $branches): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or user not found.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data'    => $branches->getBranchesForCompany($user->company_id),
        ]);
    }

    public function store(BranchesRequest $request, BranchesService $branches): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or user not found.',
            ], 401);
        }

        $data = $request->validated();

        $branch = $branches->createBranch($user->company_id, $data);

        return response()->json([
            'success' => true,
            'data'    => $branch,
        ]);
    }
}
