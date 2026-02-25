<?php

namespace App\Modules\Employees\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employees\Services\EmployeeService;
use App\Modules\Employees\Requests\EmployeeRequest;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmployeeController extends Controller
{
    public function index(EmployeeService $service): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        return response()->json([
            'success' => true,
            'data'    => $service->getEmployeesForUser($user),
        ]);
    }

    public function updateEmployeeInfo(EmployeeRequest $request, EmployeeService $service): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (! $user->hasPermission('manage_employees')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update employee information.',
            ], 403);
        }

        $data = $request->validated();
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided to update.',
            ], 422);
        }

        $employee = $service->updateEmployee($user, $data);

        return response()->json([
            'success' => true,
            'message' => 'Employee information updated successfully.',
            'data'    => $employee,
        ]);
    }

    public function store(EmployeeRequest $request, EmployeeService $service): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (! $user->hasPermission('manage_employees')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to create employee information.',
            ], 403);
        }

        $data = $request->validated();
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided to create.',
            ], 422);
        }

        $employee = $service->createEmployee($user, $data);

        return response()->json([
            'success' => true,
            'message' => 'Employee information created successfully.',
            'data'    => $employee,
        ]);
    }
}
