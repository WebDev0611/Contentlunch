<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class  emailInviteRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{

               /* $file       = $this->file('csv_import');
                $mime       = $file->getClientMimeType();
                echo $mime;die;*/
		return [
		            'emails' 		=> 'required',
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
