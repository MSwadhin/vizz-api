<?php

namespace App\Http\Requests;


use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserLoginRequest extends FormRequest{


    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "email"=>"required|email",
            "password"=>"required"
        ];
    }
    /**
     * Custom message for validation
     *
     * @return array
     */
    
    public function messages()
    {
        return [
            'email.required' => 'email field is required',
            'email.email' => 'please enter a valid email',
            'password'=>'required'
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