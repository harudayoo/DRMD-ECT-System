<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrsBursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orsBurs' => [
                'required',
                'string',
                'max:11',
                'regex:/^[A-Za-z0-9-\/]+$/',
                Rule::unique('orsburs', 'orsBursNumber')
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'orsBurs.required' => 'ORS/BURS Number is required.',
            'orsBurs.string' => 'ORS/BURS Number must be a string.',
            'orsBurs.unique' => 'This ORS/BURS Number already exists.',
            'orsBurs.max' => 'ORS/BURS Number must not exceed 11 characters.',
            'orsBurs.regex' => 'ORS/BURS Number may only contain letters, numbers, hyphens, and forward slashes.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Only trim if the value is a string
        if (is_string($this->orsBurs)) {
            $this->merge([
                'orsBurs' => trim($this->orsBurs)
            ]);
        }
    }
}
