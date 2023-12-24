<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'photo' => 'file|mimes:jpeg,jpg,png',
            'item_id' => 'unique:items',
            'item_code' => 'required',
            'item_name' => 'required',
            'categoryName' => 'required',
            'safety_stock' => 'required|integer',
            'received_date' => 'required|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'item_id.unique' => 'The item ID must be unique.',
            'item_code.required' => 'Please enter item code.',
            'item_name.required' => 'Please enter item name.',
            'categoryName.required' => 'Please select category name.',
            'safety_stock.required' => 'Please enter safety stock.',
            'received_date.required' => 'Please enter received date.',
        ];
    }
}
