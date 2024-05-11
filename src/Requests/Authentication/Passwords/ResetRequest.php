<?php

namespace VanDmade\Cuztomisable\Requests\Authentication\Passwords;

use Illuminate\Foundation\Http\FormRequest;

class ResetRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => '1',
        ];
    }

    public function rules(): array
    {
        return [
            'code' => 'required',
            'password' => 'required',
        ];
    }

}
