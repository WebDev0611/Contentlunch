<?php

namespace App\Http\Requests\AccountSettings;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class AccountSettingsRequest extends Request
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Full name is required.',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format'
        ];
    }
}
