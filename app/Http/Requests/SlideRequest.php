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
            'name'=>'required|max:200|unique:slides',
            'media_id'=>'required|integer|exists:media,id',
            'slider_id'=>'required|integer|exists:sliders,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please Give A Name',
            'name.max' => 'Name Can Not Be Longer Than 200 Characters',
            'media_id' => 'No Media Selected',
            'slider_id' => 'No Slider Selected'
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
