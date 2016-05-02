<?php
/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 19/01/16
 * Time: 1:00 AM
 */

namespace App;


class SendChatNotification
{

    protected $user;
    protected $byUser;
    protected $msg ;
    protected $message;


    /**
     * SendNotification constructor.
     *
     * @param \App\User    $user
     * @param \App\User    $byUser
     * @param \App\Message $message
     */
    public function __construct(User $user, User $byUser, Message $message)
    {
        $this->user = $user;
        $this->byUser = $byUser;
        $this->msg = "New Msg from " .$byUser->name ;
        $this->message = $message;

    }


    public function sendNotification(){
        $reqIds = $this->user->deviceIds()->lists('registration_id');
        $this->send($reqIds,$this->msg);
    }

    private function send($reqIds, $msg)
    {
        // Replace with the real server API key from Google APIs
        $apiKey = "AIzaSyAchPd-na8tNIfGmbHjrejJfUvM8Rd3_ZY";

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $reqIds,
            'data' => [
                "message" => $this->msg,
                "type" => "newChatMessage",
                "chatMsg"=> json_encode($this->message->toArray())
            ],
        );
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();

        // Set the URL, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields));

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields));

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);
        return  $result;
        //print_r($result);
        //var_dump($result);
    }

}
