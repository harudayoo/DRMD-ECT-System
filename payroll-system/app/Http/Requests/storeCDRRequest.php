<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeCDRRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cdrName' => ['required', 'string', 'min:3', 'max:35'],
            'payrollID' => ['required', 'exists:payrolls,payrollID'],
        ];
    }
}
