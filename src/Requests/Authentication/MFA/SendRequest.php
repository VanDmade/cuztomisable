<?php

namespace VanDmade\Cuztomisable\Requests\Authentication\MFA;

use Illuminate\Foundation\Http\FormRequest;

class SendRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => '1',
            'in' => '4',
        ];
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:email,phone',
        ];
    }

}
