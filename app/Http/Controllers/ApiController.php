<?php

namespace App\Http\Controllers;

use App\User;
use Dingo\Api\Routing\Helpers;

class ApiController extends \App\Http\Controllers\Controller
{
    use Helpers;


    /**
     * @param $item
     *
     * @return single Item
     */
    public function item($item){
    	return $item->toArray();
    }

    /**
     * @param $collection
     *
     * @return Collection
     */
    public function collection($collection){
    	return $collection;
    }

    public function allUsers()
    {
        $currentUser = getUserFromToken(\JWTAuth::getToken());

        $users =  User::where('id','!=',$currentUser->id)->get();
        
        return $users->toArray();
    }
}
