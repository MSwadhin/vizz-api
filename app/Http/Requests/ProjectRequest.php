<?php 


namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectRequest extends FormRequest{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'=>'required|max:200',
            'description'=>'required|max:500',
            'client'=>'max:200',
            'cd'=>'max:200',
            'ft_img' => 'numeric|required|exists:media,id',
            'bg_img' => 'numeric|required|exists:media,id',
            'date' => 'required|date',
            'gallery' => 'array',
            'gallery.*' =>'exists:media,id|numeric',
            'facebook' => 'max:250',
            'twitter' => 'max:250',
            'linkedin' => 'max:250',
            'youtube' => 'max:250',
            'instagram' => 'max:250',
            'cats' => 'array',
            'cats.*' => 'exists:categories,id|numeric',
            'bd_img' => 'required|exists:media,id',
            'bd_title' => 'required|max:200|min:1',
            'meta' => 'max:3000'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please Give A Name',
            'name.max' => 'Name Can Not Be Longer Than 200 Characters',
            'description.required' => 'Please Give A Description',
            'description.max' => 'Description Can Not Be Longer Than 500 Characters',
            'ft_img.required' => 'Please Select A Featured Image',
            'ft_img.exists' => 'Invalid Ft. Photo',
            'bg_img.required' => 'Please Select A Background Image',
            'bg_img.exists' => 'Invalid Bg. Photo'
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
