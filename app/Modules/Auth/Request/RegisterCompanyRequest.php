<?php

namespace App\Modules\Auth\Request;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCompanyRequest extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
            'phone_number' => 'nullable|string|max:20',
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
            'company_name.required' => 'Company name is required.',
            'company_name.string'   => 'Company name must be a string.',
            'company_name.max'      => 'Company name may not be greater than 255 characters.',

            'email.required' => 'Email is required.',
            'email.email'    => 'Email format is invalid.',
            'email.unique'   => 'Email is already registered.',

            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 6 characters.',

            'phone_number.string' => 'Phone number must be a string.',
            'phone_number.max'    => 'Phone number may not be greater than 20 characters.',
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
            'company_name' => 'company name',
            'phone_number' => 'phone number',
        ];
    }
}
