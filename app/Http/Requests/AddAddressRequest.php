<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddAddressRequest extends FormRequest
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
            
            'entry_firstname'               =>   'required|max:32',
            //'entry_lastname'                =>   'required',
            'entry_street_address'          =>   'required|max:250',
            //'entry_suburb'                  =>   'required',
            'entry_postcode'                =>   'required|max:10',
            'entry_city'                    =>   'required',
            //'entry_state'                   =>   'required',
            'entry_country_id'              =>   'required',
            'entry_zone_id'                 =>   'required',
            //'customers_id'                  =>   'required',
            //'entry_gender'                  =>   'required',
            //'entry_company'                 =>   'required'
        ];
    }
}
