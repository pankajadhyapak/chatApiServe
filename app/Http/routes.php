<?php

Route::get('/test/{id}', function($id){
    $sn = new \App\SendNotification(\App\User::find($id), "Welcome to SocioDoc");
    return  $sn->sendNotification();
});

Route::get('/t', function(){
	$m = \App\Message::first();

	dd(json_encode($m->toArray()));
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
            	$api->get('users', 'ApiController@allUsers');
                $api->post('/user/registerDeviceId', 'DeviceidController@registerDeviceId');
                $api->get('chatMsgs', 'ApiController@getChatMsgs');
                $api->post('chatMsgs', 'ApiController@saveChatMsgs');
                $api->post("chatMsgs/image", 'ApiController@saveChatMsgWithImage');
        	});
	});
});
