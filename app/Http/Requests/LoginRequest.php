<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    /**
     * Login request validation
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-21
     *
     */

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
            //
            'employee_id' => 'required|numeric',
            'password' => 'required',
        ];
    }

    /**
     * Customize the validation message for this request.
     *
     * @author yarzartinshwe
     * @created 2023-6-21
     *
     */
    public function messages()
    {
        return [
            'employee_id.required' => 'Please enter your employee ID.',
            'password.required' => 'Please enter your password.',
        ];
    }
}
