<?php

namespace App\Modules\Employees\Services;

use App\Models\Employee;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    public function getEmployeesForUser(User $user)
    {
        return Employee::where('company_id', $user->company_id)->get();
    }

    public function createEmployee(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'username' => $data['username'] ?? 'Employee',
                'email' => $data['email'] ?? null,
                'password' => bcrypt('defaultpassword'),
                'company_id' => $data['company_id'] ?? null,
            ]);

            $employee = Employee::create([
                'user_id' => $user->id,
                'company_id' => $data['company_id'] ?? null,
                'branch_id' => $data['branch_id'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'position_id' => $data['position_id'] ?? null,
                'manager_id' => $data['manager_id'] ?? null,
                'employee_number' => $data['employee_number'] ?? null,
                'salary' => $data['salary'] ?? null,
                'hire_date' => $data['hire_date'] ?? null,
                'employee_status' => $data['employee_status'] ?? null,
                'termination_date' => $data['termination_date'] ?? null,
                'reason_termination' => $data['reason_termination'] ?? null,
            ]);

            $profile = Profile::create([
                'employee_id' => $employee->id,
                'full_name' => $data['full_name'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'address' => $data['address'] ?? null,
                'gender' => $data['gender'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'profile_picture' => $data['profile_picture'] ?? null,
            ]);

            return $employee;
        });
    }

    public function updateEmployee(Employee $employee, array $data)
    {
        $employee->update($data);
        return $employee;
    }

    public function deleteEmployee(Employee $employee): void
    {
        $employee->delete();
    }

    public function updateProfile(Profile $profile, array $data)
    {
        $profile->update($data);
        return $profile;
    }

    public function updateUser(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }
}
