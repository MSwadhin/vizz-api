<?php 


namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TeamMemberRequest extends FormRequest{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'=>'required|max:200',
            'designation'=>'required|max:200',
            'media_id' => 'required|exists:media,id',
            'order' => 'numeric|max:100000000|min:-100000000'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please Give A Name',
            'name.max' => 'Name Can Not Be Longer Than 200 Characters',
            'designation' => 'Team Member Designation is Required',
            'media_id.required' => 'Please Select A Photo',
            'media_id.exists' => 'Invalid Photo',
            'order.max' => 'Order must be between -10^8 and 10^8',
            'order.min' => 'Order must be between -10^8 and 10^8'
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
