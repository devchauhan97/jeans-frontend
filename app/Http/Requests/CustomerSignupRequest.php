<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerSignupRequest extends FormRequest
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
            'first_name'    => 'required',              
            'last_name'    => 'required',              
            'city'    => 'required',              
            'country'    => 'required',              
            'mobile_no'    => 'required',              
            'gender'        => 'required',              
            'email'         => 'required | email|unique:customers',
            'password'      => 'required|min:8|regex:/^.+@.+$/i',
            're_password'   => 'required | same:password',
        ];
    }
}
