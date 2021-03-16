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
            'title'=>'required|max:1000',
            'description'=>'required|max:1500',
            'icon' => 'required|numeric|exists:media,id',
            'service_id' => 'required|numeric|exists:services,id'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please Give A Title',
            'title.max' => 'Title Can Not Be Longer Than 1000 Characters',
            'description.required' => 'Please Give A Description',
            'description.max' => 'Description Can Not Be Longer Than 1500 Characters',
            'icon.required' => 'Please Select an Icon',
            'icon.exists' => 'Invalid Icon',
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
