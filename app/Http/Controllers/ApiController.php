<?php

namespace App\Http\Controllers;

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
}
