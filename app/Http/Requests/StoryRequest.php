<?php 


namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoryRequest extends FormRequest{

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
            'start_year' => 'required|numeric|min:1999|max:2999',
            'end_year' => 'required|numeric|min:1999|max:2999'
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
            'start_year.max' => 'Start Year must be between 1999 and 2999',
            'end_year.max' => 'End Year must be between 1999 and 2999',
            'start_year.min' => 'Start Year must be between 1999 and 2999',
            'end_year.min' => 'End Year must be between 1999 and 2999'
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
