<?php

namespace App\Modules\Auth\Request;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_id' => 'required|uuid|exists:user_sessions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required' => 'Session ID harus diisi.',
            'session_id.uuid'     => 'Session ID harus berupa UUID.',
            'session_id.exists'   => 'Session tidak ditemukan.',
        ];
    }
}
