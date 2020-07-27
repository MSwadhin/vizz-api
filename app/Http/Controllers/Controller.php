<?php

namespace App\Http\Controllers;

use App\Http\Services\UtilityService;
use App\Http\Services\ResponseService;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected $utilityService;
    protected $responseService;

    public function __construct($publicRoutes)
    {
        $this->middleware(
            'auth:api',
            [
                'except'=> $publicRoutes
            ]
        );
        $this->utilityService = new UtilityService;
        $this->responseService = new ResponseService;
    }
    
    //
    protected function respondWithToken($token)
    {
        return response()->json([
            'success'=> true,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL(),
        ], 200);
    }


    protected function sendSuccess($data=false){
        if( $data ){
            return $this->responseService->successWithData("Success",$data);
        }
        return $this->responseService->success("Success");
    }

    protected function sendFailure($statusCode,$data=false){
        if( !$data ){
            return $this->responseService->fail("Request Failed",$statusCode);
        }
        return $this->responseService->failWithData("Request Failed",$statusCode,$data);
    }
}
