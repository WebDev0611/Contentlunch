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
			'con_name'		=> 'required',
		            'connection_type' 	=> 'required',
		            // -- WORD PRESS 
		            'api_key' 		=> 'required_if:connection_type,wordpress',
		            'api_url' 		=> 'required_if:connection_type,wordpress'
		];
	}

	public function messages()
	{
		return [
			'con_name.required' => 'Enter the connection name for the connection.',
			'connection_type.required' => 'Please select a connection type.',
			// - Wordpress
			'api_key.required' => 'Enter your wordpress API key. You can find this key in Settings -> API.',
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
