<?php


namespace App\Handlers;



class SMSHandler
{
    public static function sendSMS($receiver, $message){

        $url = "http://66.45.237.70/api.php";
        $data= array(
            'username'=>config('sms.sms_username'),
            'password'=>config('sms.sms_password'),
            'number'=>$receiver,
            'message'=>$message
        );

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("|",$smsresult);
        $sendstatus = $p[0];

        if($sendstatus != 1101){ //success response code from http://login.bulksmsbd.com/apidocs.php
            throw new \Exception("Failed to send sms");
        }
    }
}
