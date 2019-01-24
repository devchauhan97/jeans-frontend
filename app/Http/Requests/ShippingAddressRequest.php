<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingAddressRequest extends FormRequest
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
            'firstname'         =>   'required|max:32',
            'lastname'          =>   'required|max:32',
            'company'           =>   'required|max:250',
            'street'            =>   'required|max:250',
            'countries_id'      =>   'required',
            'zone_id'           =>   'required',
            'city'              =>   'required',
            'postcode'          =>   'required',
            
        ];
    }
    public function messages()
    {
        return [
            'countries_id.required'     => 'The country  field is required.',
            'zone_id.required'          => 'The state  field is required.',
        ];
    }
}
