<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function getChatMsgs()
    {

        $currentUser = getUserFromToken(\JWTAuth::getToken());

        $otherUser = \App\User::findOrFail(\Request::get('otherId'));

        $chatMsgs = $currentUser->ChatMsgs($otherUser);

        return $chatMsgs->toArray();
    }

    public function saveChatMsgs()
    {
        $currentUser = getUserFromToken(\JWTAuth::getToken());

        $otherUser = \App\User::findOrFail(\Request::get('otherId'));

        $msg = new Message();
        $msg->sent_by = $currentUser->id;
        $msg->sent_to = $otherUser->id;
        $msg->message = \Request::get('message');
        $msg->save();

        $sn = new \App\SendChatNotification($otherUser, $currentUser, $msg);
        $sn->sendNotification();

        return $msg->toArray();
    }

    public function saveChatMsgWithImage(Request $request)
    {
        var_dump($request->all());
        $file = $request->file('picture');
        $fileName = time().Str::random(12).".".$file->getClientOriginalExtension();

        if($file->move(public_path()."/chats/images", $fileName)){
            $currentUser = getUserFromToken(\JWTAuth::getToken());
            $otherUser = \App\User::findOrFail($request->get('otherId'));
            $msg = new Message();
            $msg->sent_by = $currentUser->id;
            $msg->sent_to = $otherUser->id;
            $msg->media_path = "/chats/images/".$fileName;

            $msg->save();

            $sn = new \App\SendChatNotification($otherUser, $currentUser, $msg);
            $sn->sendNotification();

            return $msg->toArray();

        }
    }
}
