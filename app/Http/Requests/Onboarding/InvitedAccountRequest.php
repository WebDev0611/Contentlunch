<?php

namespace App\Http\Requests\Onboarding;

use App\Http\Requests\Request;

class InvitedAccountRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'It appears this email is already taken. <a href="/login">Log in</a> and try again.',
        ];
    }
}
