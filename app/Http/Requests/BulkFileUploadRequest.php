<?php 


namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BulkFileUploadRequest extends FormRequest{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'file'=>'required|mimes:doc,docx,pdf,txt,jpg,png,jpeg,ai,ps,svg|max:20600'
        ];
    }

    public function messages()
    {
        return [
            // 'file.required' => 'Please Select A File',
            // 'file.mimes' => 'Please select any valid file type [eg:image/doc/pdf/txt]',
            // 'file.max'=>'File size can not be greater than 20MB'
        ];
    }
    

    protected function failedValidation(Validator $validator) 
    { 
        throw new HttpResponseException(response()->json(
            [
                "success"=>false,
                "errors"=> $validator->errors(),
                "message"=>"Invalid Request"
        ], 422));
     }
}
