<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class DeviceidController extends ApiController
{
    public function registerDeviceId(Request $request)
    {

        if($request->has('registration_id') && $request->has('deviceType')){
            $user = getUserFromToken(\JWTAuth::getToken());
            try{
                $user->deviceIds()->create(['registration_id' => $request->get('registration_id'),'deviceType' => $request->get('deviceType')]);
                return $this->response->array(['registration_id' => $request->get('registration_id'),'deviceType' => $request->get('deviceType')]);
            }catch (QueryException $e){
                //device already registered
            }
        }
    }
}
