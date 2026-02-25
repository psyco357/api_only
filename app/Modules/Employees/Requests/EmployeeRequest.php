<?php

namespace App\Modules\Employees\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id'   => 'sometimes|exists:companies,id',
            'branch_id'    => 'sometimes|exists:branches,id',
            'user_id'      => 'sometimes|exists:users,id',
            'department_id' => 'sometimes|exists:departments,id',
            'position_id'     => 'sometimes|exists:positions,id',
            'manager_id'     => 'sometimes|exists:employees,id',
            'employee_number' => 'sometimes|string|max:50',
            'salary'          => 'sometimes|numeric',
            'hire_date'       => 'sometimes|date',
            'employee_status'   => 'sometimes|in:permanent,contract,internship',
            'termination_date'   => 'sometimes|date|after_or_equal:hire_date',
            'reason_termination'   => 'sometimes|nullable|string',

            // Personal Information
            'full_name'     => 'sometimes|string|max:255',
            'phone_number'      => 'sometimes|string|max:255',
            'address'          => 'sometimes|string|max:255',
            'gender'           => 'sometimes|in:male,female,other',
            'birth_date'    => 'sometimes|date',
            'profile_picture' => 'sometimes|nullable|string',
            'is_active'       => 'sometimes|boolean',
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.string'   => 'First name must be a string.',
            'first_name.max'      => 'First name may not be greater than 255 characters.',

            'last_name.required' => 'Last name is required.',
            'last_name.string'   => 'Last name must be a string.',
            'last_name.max'      => 'Last name may not be greater than 255 characters.',

            'email.required' => 'Email is required.',
            'email.email'    => 'Email format is invalid.',
            'email.unique'   => 'Email is already registered.',

            'phone_number.string' => 'Phone number must be a string.',
            'phone_number.max'    => 'Phone number may not be greater than 20 characters.',

            'company_id.exists'   => 'Selected company does not exist.',
            'branch_id.exists'    => 'Selected branch does not exist.',
            'user_id.exists'      => 'Selected user does not exist.',
            'department_id.exists' => 'Selected department does not exist.',
            'position_id.exists'     => 'Selected position does not exist.',
            'manager_id.exists'     => 'Selected manager does not exist.',
            'employee_status.in'   => 'Employee status must be one of: permanent, contract, internship.',
            'termination_date.after_or_equal' => 'Termination date must be after or equal to hire date.',

        ];
    }

    /**
     * Custom attribute names.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'full_name' => 'nama lengkap',
            'hire_date' => 'tanggal masuk',
        ];
    }
}
