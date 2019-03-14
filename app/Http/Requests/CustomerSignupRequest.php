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
            'last_name'     => 'required',              
            'city'          => 'required',              
            'country'       => 'required',              
            'mobile_no'     => 'required',              
           // 'gender'        => 'required',              
            'email'         => 'required | email | unique:customers',
           // 'password'      => 'required | min:8 | regex:/^.+@.+$/i',
             'password'      => [
                                'required',
                                'string',
                                'min:8',             // must be at least 10 characters in length
                                'regex:/[a-z]/',      // must contain at least one lowercase letter
                                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                                'regex:/[0-9]/',      // must contain at least one digit
                               // 'regex:/[@$!%*#?&]/', // must contain a special character
                            ],

            //'password'      => 'required|min:6',
            're_password'   => 'required | same:password',
        ];
    }
}
