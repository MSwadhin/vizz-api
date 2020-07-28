<?php 


namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRequest extends FormRequest{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'file'=>'required|mimes:doc,docx,pdf,txt,jpg,png,jpeg,ai,ps,svg|max:20600',
            'name'=>'required|max:200'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please Give A Name ',
            'name.max'=>'Category Name Must be Between 1-200 Characters'
        ];
    }
    

    protected function failedValidation(Validator $validator) 
    { 
        throw new HttpResponseException(response()->json(
            [
                "success"=>false,
                "errors"=> $validator->errors(),
                "message"=>"Invalid Data"
        ], 422));
     }
}
