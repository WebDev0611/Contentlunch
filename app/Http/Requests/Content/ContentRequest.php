<?php namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class  ContentRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'content_type'		=> 'required',
		            'author' 		=> 'required',
		            'due_date' 		=> 'required',
		            'title' 			=> 'required',
		            'connections' 		=> 'required',
		            'content' 		=> 'required',
		            // -- WORD PRESS 
		          //  'api_key' 		=> 'required_if:con_type,wordpress',
		           // 'api_url' 		=> 'required_if:con_type,wordpress'
		];
	}

	public function messages()
	{
		return [
			'con_name.required' => 'Enter the connection name for the connection.',
			'con_type.required' => 'Please select a connection type.',
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
