<?php 


namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TestimonialRequest extends FormRequest{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'=>'required|max:200',
            'designation'=>'required|max:200',
            'text'=>'required|max:500|min:5',
            'photo' => 'required|exists:media,id',
            'order' => 'numeric|max:100000000|min:-100000000'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please Give A Name',
            'name.max' => 'Name Can Not Be Longer Than 200 Characters',
            'designation' => 'Designation is Required',
            'photo.required' => 'Please Select A Photo',
            'photo.exists' => 'Invalid Photo',
            'order.max' => 'Order must be between -10^8 and 10^8',
            'order.min' => 'Order must be between -10^8 and 10^8'
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
