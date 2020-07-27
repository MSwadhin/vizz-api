<?php
 namespace App\Http\Services;

 use Illuminate\Http\Exceptions\HttpResponseException;
 use Illuminate\Support\Facades\Hash;



 class ResponseService
 {
 

    public function success($responseMessage)
    {
        throw new HttpResponseException(response()->json(
            [
                "success"=>true,
                "message"=>$responseMessage
            ], 
            200
        ));      
    }

    public function successWithData($responseMessage,$data)
    {
        throw new HttpResponseException(response()->json(
            [
                "success"=>true,
                "message"=>$responseMessage,
                "data"=>$data
            ], 
            200
        ));      
    } 


    public function fail($responseMessage,$statusCode)
    {
        throw new HttpResponseException(response()->json(
            [
                "success"=>false,
                "message"=>$responseMessage
            ], 
            $statusCode
        ));      
    }

    public function failWithData($responseMessage,$statusCode,$data)
    {
        throw new HttpResponseException(response()->json(
            [
                "success"=>false,
                "message"=>$responseMessage,
                "errors"=>$data
            ], 
            $statusCode
        ));      
    }


    public function hash_password($password)
    {
        return Hash::make($password);
    }
    



 }