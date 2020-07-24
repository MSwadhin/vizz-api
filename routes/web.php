<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return "Why You Are Here???";
});






$router->group(
    ['prefix'=>'auth'],
    function () use ($router){
        $router->post('login','AuthController@login');
        $router->get('logout','AuthController@logout');
    }
);



/*
    Media Controller 
*/
// $router->resourc

$router->group(
    ['prefix' => 'media'],
    function () use ($router){
        $router->get('all','MediaController@index');
        $router->get('show','MediaController@show');
        $router->post('add','MediaController@store');
        $router->post('upadte','MediaController@update');
    }
);