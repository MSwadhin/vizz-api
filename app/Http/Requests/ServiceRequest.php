<?php 


namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceRequest extends FormRequest{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'=>'required|max:200',
            'description'=>'required|max:500',
            'ft_img' => 'required|exists:media,id',
            'button_link'=>'max:500',
            'order'=>'numeric|max:100000000|min:-100000000'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please Give A Name',
            'title.max' => 'Name Can Not Be Longer Than 200 Characters',
            'description.required' => 'Please Give A Description',
            'description.max' => 'Description Can Not Be Longer Than 500 Characters',
            'ft_img.required' => 'Please Select A Photo',
            'ft_img.exists' => 'Invalid Photo',
            'order.max' => 'Start Year must be between 1999 and 2999',
            'order.max' => 'End Year must be between 1999 and 2999'
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
