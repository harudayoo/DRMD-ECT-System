<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRespCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add authentication check if needed
    }

    public function rules(): array
    {
        return [
            'responsibilityCode' => [
                'required',
                'string',
                'max:15',
                'regex:/^[A-Za-z0-9-]+$/', // Allow letters, numbers, and hyphens
                Rule::unique('respcodes', 'responsibilityCode')
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'responsibilityCode.required' => 'Responsibility Center Code is required.',
            'responsibilityCode.unique' => 'This Responsibility Center Code already exists.',
            'responsibilityCode.max' => 'Responsibility Center Code must not exceed 15 characters.',
            'responsibilityCode.regex' => 'Responsibility Center Code may only contain letters, numbers, and hyphens.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'responsibilityCode' => trim($this->responsibilityCode),
        ]);
    }
}
