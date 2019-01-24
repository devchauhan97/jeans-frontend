<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingAddressRequest extends FormRequest
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
                'billing_firstname'         =>   'required|max:32',
                'billing_lastname'          =>   'required|max:32',
                'billing_company'           =>   'required|max:250',
                'billing_street'            =>   'required|max:250',
                'billing_countries_id'      =>  $this->request->get('same_billing_address') == 1 ?  '': 'required',
                'billing_zone_id'           =>   $this->request->get('same_billing_address')  == 1 ?   '': 'required',
                'billing_city'              =>   'required',
                'billing_zip'          =>   'required',
            
        ];
    }
    public function messages()
    {

        return [

            'billing_countries_id.required'     => 'The country  field is required.',
            'billing_zip.required'          => 'The state  field is required.',
        ];
    }
}
