<?php

namespace App\Http\Requests\WriterAccess;

use App\Http\Requests\Request;

class CreateOrderRequest extends Request
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
            'due_date' => 'required|date|after:tomorrow',
            'writer_access_asset_type' => 'required',
            'writer_access_word_count' => 'required',
            'writer_access_writer_level' => 'required',
            'writer_access_order_count' => 'required|numeric|min:1',
        ];
    }
}
