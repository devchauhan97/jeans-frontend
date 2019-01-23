<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StripeRequest extends FormRequest
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
    {//numeric|max:14
        return [
            'card_no'                => 'required|numeric',
            'cc_expiry_month'       => 'required|numeric|max:12|min:1',
            'cc_expiry_year'      => 'required|numeric',
            'cvv_number'         => 'required|numeric',
        ];
    }
}
