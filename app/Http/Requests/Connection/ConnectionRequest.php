<?php namespace App\Http\Requests\Connection;

use Illuminate\Foundation\Http\FormRequest;

class  ConnectionRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
        return [
            'con_name' => 'required',
            'con_type' 	=> 'required',

            // WORD PRESS
            // 'api_url' => 'required_if:con_type,wordpress'
        ];
	}

	public function messages()
	{
		return [
			'con_name.required' => 'Enter the connection name for the connection.',
			'con_type.required' => 'Please select a connection type.',
			// - Wordpress
			'api_url.required'  => 'Enter your API URL. This is normally.... {{domain}}/api ** find this out and update it**'
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

}
