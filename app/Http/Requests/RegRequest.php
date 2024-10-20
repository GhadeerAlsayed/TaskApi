<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'full_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
