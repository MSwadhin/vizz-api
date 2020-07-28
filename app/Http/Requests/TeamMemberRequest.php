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
            'name'=>'required|max:200|unique:team_members',
            'designation'=>'required|max:200'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please Give A Name',
            'name.max' => 'Name Can Not Be Longer Than 200 Characters',
            'designation' => 'Team Member Designation is Required'
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
