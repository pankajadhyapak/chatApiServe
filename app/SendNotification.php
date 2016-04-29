<?php
/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 19/01/16
 * Time: 1:00 AM
 */

namespace App;


class SendNotification
{

    protected $user;
    protected $msg;
    protected $type;
    /**
     * SendNotification constructor.
     */
    public function __construct(User $user, $message, $type=null)
    {
        $this->user = $user;
        $this->msg = $message;
        $this->type = $type;
    }


    public function sendNotification(){
        $reqIds = $this->user->deviceIds()->lists('registration_id');
        $this->send($reqIds,$this->msg);
    }

    private function send($reqIds, $msg)
    {
        // Replace with the real server API key from Google APIs
        $apiKey = "AIzaSyAchPd-na8tNIfGmbHjrejJfUvM8Rd3_ZY";


        // Message to be sent
        $message = $msg;

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

		if($this->type == null){
			$this->type = "normal";
		}
        $fields = array(
            'registration_ids' => $reqIds,
            'data' => array( "message" => $this->msg, "type"=> $this->type),
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