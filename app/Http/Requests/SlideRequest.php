<?php 


namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SlideRequest extends FormRequest{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'=>'required|max:200',
            'media_id'=>'required|integer|exists:media,id',
            'slider_id'=>'required|integer|exists:sliders,id',
            'order' => 'numeric|max:100000000|min:-100000000',
            'text' => 'max:500'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please Give A Name',
            'name.max' => 'Name Can Not Be Longer Than 200 Characters',
            'media_id' => 'No Media Selected',
            'slider_id' => 'No Slider Selected',
            'order.max' => 'Order must be between -10^8 and 10^8',
            'order.min' => 'Order must be between -10^8 and 10^8',
            'text.max' => 'Slider Text Can Not Be Longer Than 500 Characters',
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
