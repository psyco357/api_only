<?php

namespace App\Modules\Branches\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_branch' => 'required|string|max:255',
            'address'     => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
        ];
    }

    public function messages()
    {
        return [
            'name_branch.required' => 'Branch name is required.',
            'name_branch.string'   => 'Branch name must be a string.',
            'name_branch.max'      => 'Branch name may not be greater than 255 characters.',
            'address.string'       => 'Address must be a string.',
            'phone_number.string'  => 'Phone number must be a string.',
            'phone_number.max'     => 'Phone number may not be greater than 20 characters.',
        ];
    }
}
