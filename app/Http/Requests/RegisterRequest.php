<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed',Password::default()],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.mixedCase' => 'Password must contain both uppercase and lowercase letters.',
            'password.uncompromised' => 'This password has been compromised in a data breach.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one symbol.',
        ];
    }
}
