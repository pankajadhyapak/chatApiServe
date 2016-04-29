<?php

Route::get('/test/{id}', function($id){
    $sn = new \App\SendNotification(\App\User::find($id), "Welcome to SocioDoc");
    return  $sn->sendNotification();
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

	$api->group([
		'namespace' => 'App\Http\Controllers',
		'middleware' => 'cors'], function ($api) {

			// Login route
        	$api->post('login', 'Auth\AuthController@authenticate');
        	$api->post('register', 'Auth\AuthController@register');

        	//Auth Routes
        	$api->group( [ 'middleware' => 'jwt.auth' ], function ($api) {
            	$api->get('me', 'Auth\AuthController@me');
                $api->post('/user/registerDeviceId', 'DeviceidController@registerDeviceId');
        	});
	});
});
