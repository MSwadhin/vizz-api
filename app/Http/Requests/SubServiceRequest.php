<?php 


namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubServiceRequest extends FormRequest{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'=>'required|max:200',
            'description'=>'required|max:500',
            'icon' => 'required|numeric|exists:media,id',
            'service_id' => 'required|numeric|exists:services,id'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please Give A Name',
            'title.max' => 'Name Can Not Be Longer Than 200 Characters',
            'description.required' => 'Please Give A Description',
            'description.max' => 'Description Can Not Be Longer Than 500 Characters',
            'icon.required' => 'Please Select A Photo',
            'icon.exists' => 'Invalid Photo',
            'service_id.required' => 'Please Select A Service First',
            'service_id.exists' => 'Invalid Service'
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
