<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
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
        $userId = Auth::id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($userId)
                    ->whereNull('deleted_at')
            ],
            'phone' => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('users', 'phone')
                    ->ignore($userId)
                    ->whereNull('deleted_at')
            ],
            'image' => 'nullable|image|max:2048|dimensions:max_width=500,max_height=500',
            'password' => 'nullable|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'image.dimensions' => 'The image must not exceed 500x500 pixels.',
            'image.max' => 'The image size must not be greater than 2MB.',
        ];
    }
}
