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
        $router->post('delete/{id}','CategoryController@destroy');
        $router->get('getTrash','CategoryController@getTrash');
        $router->post('clearTrash','CategoryController@clearTrash');
    }
);



/**
 * 
 * Slider Routes
 */
$router->group(
    ['prefix'=>'slider'],
    function() use ($router){
        $router->get('all','SliderController@index');
        $router->get('show/{id}','SliderController@show');
        $router->post('create','SliderController@store');
        $router->post('update/{id}','SliderController@update');
        $router->post('trash/{id}','SliderController@trash');
        $router->post('restore/{id}','SliderController@restore');
        $router->post('delete/{id}','SliderController@destroy');
        $router->get('getTrash','SliderController@getTrash');
        $router->post('clearTrash','SliderController@clearTrash');
    }
);

/**
 * 
 * Slide Routes
 */
$router->group(
    ['prefix'=>'slide'],
    function() use ($router){
        $router->get('all','SlideController@index');
        $router->get('show/{id}','SlideController@show');
        $router->post('create','SlideController@store');
        $router->post('update/{id}','SlideController@update');
        $router->post('delete/{id}','SlideController@destroy');
    }
);


/**
 * 
 * Team Routes
 */
$router->group(
    ['prefix'=>'team'],
    function() use ($router){
        $router->get('all','TeamMemberController@index');
        $router->get('show/{id}','TeamMemberController@show');
        $router->post('create','TeamMemberController@store');
        $router->post('update/{id}','TeamMemberController@update');
        $router->post('trash/{id}','TeamMemberController@destroy');
    }
);


/**
 * 
 * Client Routes
 */
$router->group(
    ['prefix'=>'client'],
    function() use($router){
        $router->get('all','ClientController@index');
        $router->get('show/{id}','ClientController@show');
        $router->post('create','ClientController@store');
        $router->post('trash/{id}','ClientController@destroy');
    }
);


/**
 * 
 * FAQ Routes
 */
$router->group(
    ['prefix'=>'faq'],
    function() use ($router){
        $router->get('all','FAQController@index');
        $router->get('show/{id}','FAQController@show');
        $router->post('create','FAQController@store');
        $router->post('update/{id}','FAQController@update');
        $router->post('trash/{id}','FAQController@destroy');
    }
);


/**
 * 
 * Story Routes
 */
$router->group(
    ['prefix'=>'story'],
    function() use ($router){
        $router->get('all','StoryController@index');
        $router->get('show/{id}','StoryController@show');
        $router->post('create','StoryController@store');
        $router->post('update/{id}','StoryController@update');
        $router->post('trash/{id}','StoryController@destroy');
    }
);