<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserLoginRequest;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

use App\User;


class AuthController extends Controller
{

    public function __construct()
    {
        /*
        * Pass public methods [do not neet authentication] to parent constructor;
        */
        parent::__construct(
            ['login']
        );
    }


    public function login(UserLoginRequest $request){
        $creds = $request->only('email','password');
        if( ! $token = Auth::guard('api')->attempt($creds) ){
            $responseMessage = "Invalid email or password !";
            return $this->sendFailure(401,[
                $responseMessage
            ]);
        }
        return $this->respondWithToken($token);
    }

    public function logout(){
        Auth::guard('api')->logout();
        $responseMessage = "Successfully Logged Out";
        return  $this->sendSuccess($responseMessage);
    }


}
