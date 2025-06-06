<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "fullname"=> 'required|string|max:255',
            "email"=> 'required|email|unique:users,email',
            "password" => 'required|confirmed|min:8',
            'invite_token' => 'nullable|string|exists:invites,token',
        ];
    }
}
