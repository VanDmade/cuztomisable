<?php

namespace VanDmade\Cuztomisable\Requests\Authentication\Passwords;

use Illuminate\Foundation\Http\FormRequest;

class ForgotRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => '1',
            'email' => '2',
            'in' => '3',
        ];
    }

    public function prepareForValidation(): void
    {
        $email = config('cuztomisable.account.passwords.reset_with.email', false);
        $phone = config('cuztomisable.account.passwords.reset_with.phone', false);
        $type = $phone && strpos($this->input('username'), '@') == false ? 'phone' : ($email ? 'email' : 'username');
        $username = $this->input('username');
        if ($type == 'phone') {
            foreach (['/', '_', '-', '(', ')', ' '] as $i => $key) {
                $username = str_replace($key, '', $username);
            }
        }
        $this->merge([
            'type' => $type,
            'username' => $username,
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:email,phone,username',
            'username' => 'required'.($this->input('type') == 'email' ? '|email' : ''),
        ];
    }

}
