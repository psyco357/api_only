<?php

namespace App\Modules\Auth\Services;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterCompanyService
{
    public function handle(array $data)
    {
        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | 1️⃣ Create Company
            |--------------------------------------------------------------------------
            */
            $company = Company::create([
                'company_code'      => strtoupper(Str::random(6)),
                'name_company'      => $data['company_name'],
                'email'             => $data['email'],
                'phone_number'      => $data['phone_number'] ?? null,
                'subscription_plan' => 'basic',
                'is_active'         => true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 2️⃣ Create Default Roles
            |--------------------------------------------------------------------------
            */
            $ownerRole = Role::create([
                'company_id' => $company->id,
                'slug'       => 'owner',
                'name'       => 'Owner',
                'is_system'  => true,
            ]);

            $adminRole = Role::create([
                'company_id' => $company->id,
                'slug'       => 'admin',
                'name'       => 'Admin',
                'is_system'  => true,
            ]);

            $employeeRole = Role::create([
                'company_id' => $company->id,
                'slug'       => 'employee',
                'name'       => 'Employee',
                'is_system'  => true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 3️⃣ Create Default Permissions
            |--------------------------------------------------------------------------
            */
            $permissions = [
                ['slug' => 'manage_company',   'name' => 'Manage Company'],
                ['slug' => 'manage_users',     'name' => 'Manage Users'],
                ['slug' => 'manage_roles',     'name' => 'Manage Roles'],
                ['slug' => 'manage_attendance', 'name' => 'Manage Attendance'],
                ['slug' => 'manage_leave',     'name' => 'Manage Leave'],
                ['slug' => 'view_dashboard',   'name' => 'View Dashboard'],
                ['slug' => 'clock_in',         'name' => 'Clock In'],
                ['slug' => 'clock_out',        'name' => 'Clock Out'],
            ];

            $createdPermissions = [];

            foreach ($permissions as $perm) {
                $createdPermissions[$perm['slug']] = Permission::create([
                    'company_id' => $company->id,
                    'slug'       => $perm['slug'],
                    'name'       => $perm['name'],
                    'is_system'  => true,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 4️⃣ Assign Permissions Per Role
            |--------------------------------------------------------------------------
            */

            // OWNER → all permissions
            foreach ($createdPermissions as $permission) {
                $ownerRole->permissions()->attach($permission->id, [
                    'company_id' => $company->id
                ]);
            }

            // ADMIN → limited
            $adminPermissions = [
                'manage_users',
                'manage_attendance',
                'manage_leave',
                'view_dashboard',
            ];

            foreach ($adminPermissions as $slug) {
                $adminRole->permissions()->attach(
                    $createdPermissions[$slug]->id,
                    ['company_id' => $company->id]
                );
            }

            // EMPLOYEE → minimal
            $employeePermissions = [
                'view_dashboard',
                'clock_in',
                'clock_out',
            ];

            foreach ($employeePermissions as $slug) {
                $employeeRole->permissions()->attach(
                    $createdPermissions[$slug]->id,
                    ['company_id' => $company->id]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 5️⃣ Create Owner User
            |--------------------------------------------------------------------------
            */
            $user = User::create([
                'company_id'    => $company->id,
                'username'      => 'owner',
                'email'         => $data['email'],
                'password'      => $data['password'], // auto hashed via cast
                'registered_at' => now(),
                'is_active'     => true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 6️⃣ Assign Owner Role
            |--------------------------------------------------------------------------
            */
            $user->roles()->attach($ownerRole->id, [
                'company_id' => $company->id
            ]);

            return [
                'company' => $company,
                'user'    => $user,
            ];
        });
    }
}
