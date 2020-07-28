<?php 


namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FAQRequest extends FormRequest{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ques' => 'required|max:200',
            'ans' => 'required|max:999',
            'order' => 'numeric|max:100000000|min:-100000000'
        ];
    }

    public function messages()
    {
        return [
            'order.max' => 'Order must be between -10^8 and 10^8',
            'order.min' => 'Order must be between -10^8 and 10^8',
            'order.numeric' => 'Order must be a number'
        ];
    }
    

    protected function failedValidation(Validator $validator) 
    { 
        throw new HttpResponseException(response()->json(
            [
                "success"=>false,
                "errors"=> $validator->errors(),
                "message"=>"Invalid Credentials"
        ], 422));
     }
}
