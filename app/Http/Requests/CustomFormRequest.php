<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\FormStatus;
use App\Enums\FormFieldType;

class CustomFormRequest extends FormRequest
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
            'label' => 'required|string|max:255',
            'status' => 'required|in:' . implode(',', array_column(FormStatus::cases(), 'value')),
            'name' => 'required|array|min:1',
            'name.*' => 'required|string|max:255',
            'type' => 'required|array',
            'type.*' => 'required|in:' . implode(',', array_column(FormFieldType::cases(), 'value')),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'At least one form field name is required.',
            'name.*.required' => 'Each form field name is required.',
        ];
    }
}
