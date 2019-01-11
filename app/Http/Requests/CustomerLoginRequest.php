<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerLoginRequest extends FormRequest
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
            'log_email'    => 'required|email',
            'log_password' => 'required',
        ];
    }
    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         if ($this->has('log_password') && !Hash::check($this->old_password, Auth::guard('customer')->user()->password)) {
    //             $validator->errors()->add('old_password', 'Old password not valid');
    //         }
    //     });
    // }
}
