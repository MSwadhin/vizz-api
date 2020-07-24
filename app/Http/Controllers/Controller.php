<?php

namespace App\Http\Controllers;

use App\Http\Services\UtilityService;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected $utilityService;

    public function __construct($publicRoutes)
    {
        $this->middleware(
            'auth:api',
            [
                'except'=> $publicRoutes
            ]
        );
        $this->utilityService = new UtilityService;
    }
    
    //
    protected function respondWithToken($token)
    {
        return response()->json([
            'success'=> true,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 600,
        ], 200);
    }
}
