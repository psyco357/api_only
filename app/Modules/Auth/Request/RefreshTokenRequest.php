<?php

namespace App\Modules\Auth\Request;

use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'refresh_token' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'refresh_token.required' => 'Refresh token Harus diisi',
            'refresh_token.string'   => 'Refresh token harus berupa string',
        ];
    }
}
