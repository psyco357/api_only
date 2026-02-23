<?php

namespace App\Modules\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'name_company'          => 'sometimes|string|max:255',
            'email'                 => 'sometimes|email|max:255',
            'phone_number'          => 'sometimes|nullable|string|max:50',
            'logo'                  => 'sometimes|nullable|string',
            'address'               => 'sometimes|nullable|string',
            'subscription_plan'     => 'sometimes|string|max:50',
            'subscription_end_date' => 'sometimes|date',
            'is_active'             => 'sometimes|boolean',
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
            'name_company.required' => 'Company name is required.',
            'name_company.string'   => 'Company name must be a string.',
            'name_company.max'      => 'Company name may not be greater than 255 characters.',

            'email.required' => 'Email is required.',
            'email.email'    => 'Email format is invalid.',
            'email.unique'   => 'Email is already registered.',

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
            'company_name' => 'Company Name',
            'email'        => 'Email Address',
            'phone_number' => 'Phone Number',
        ];
    }
}
