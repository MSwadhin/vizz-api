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
            'title'=>'required|max:200|min:4',
            'description'=>'required|max:1500|min:4',
            'ft_img' => 'required|exists:media,id',
            'button_link'=>'max:500',
            'order'=>'numeric|max:100000000|min:-100000000'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please Give A Service Name',
            'title.max' => 'Service Name Can Not Be Longer Than 200 Characters',
            'description.required' => 'Please Give A Description',
            'description.max' => 'Description Can Not Be Longer Than 1500 Characters',
            'ft_img.required' => 'Please Select A Featured Image',
            'ft_img.exists' => 'Invalid Featured Image',
            'order.number' => 'Order must be an integer',
            'order.max' => 'Order must be between -100000000 and 100000000',
            'order.max' => 'Order must be between -100000000 and 100000000',
            'button_link' => 'Button Link Must Can Not Be Longer Than 500 Characters'
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
