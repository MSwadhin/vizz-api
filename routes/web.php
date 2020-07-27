<?php



$router->get('/', function () use ($router) {
    return "Why You Are Here???";
});



/*
*
* Auth Routes
*/
$router->group(
    ['prefix'=>'auth'],
    function () use ($router){
        $router->post('login','AuthController@login');
        $router->get('logout','AuthController@logout');
    }
);



/*
*
*Media Routes 
*/
$router->group(
    ['prefix' => 'media'],
    function () use ($router){
        $router->get('all','MediaController@index');
        $router->get('show/{id}','MediaController@show');
        // $router->get('show/{id}',[ 'as'=>'show', 'uses'=>'MediaController@show' ]);
        $router->post('upload','MediaController@store');
        $router->post('update/{id}','MediaController@update');
        $router->post('trash/{id}','MediaController@destroy');
        $router->get('gettrash','MediaController@get_trash');
        $router->post('bulkUpload','MediaController@bulkUpload');
        $router->post('clearTrash','MediaController@clearTrash');
        $router->post('bulkRestore/{ids}','MediaController@restore2');
    }
);



/*
*
* Category Routes
*/
$router->group(
    ['prefix'=>'category'],
    function() use ($router){
        $router->get('all','CategoryController@index');
        $router->get('show/{id}','CategoryController@show');
        $router->post('create','CategoryController@store');
        $router->post('update/{id}','CategoryController@update');
        $router->post('trash/{id}','CategoryController@trash');
        $router->post('restore/{id}','CategoryController@restore');
        // $router->post('delete/{id}','CategoryController@destroy');
        $router->get('getTrash','CategoryController@getTrash');
        $router->post('clearTrash','CategoryController@clearTrash');
    }
);

