<?php 


namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostRequest extends FormRequest{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'=>'required|max:250',
            'author'=>'required|max:250',
            'text'=>'required',
            'tags'=>'array',
            'tags.*' =>'exists:tags,id'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please Give A Title',
            'title.max' => 'Title Can Not Be Longer Than 250 Characters',
            'author.required' => 'Please Give An Author',
            'author.max' => 'Author Name Can Not Be Longer Than 250 Characters',
            'text' => 'Empty Post Can Not Be Added'
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
