<?php

namespace Telegram;


class Telegram
{
    public function send_message($apiToken,$message, $group_chat_id)
    {
        $message = preg_replace('/\<[\/]{0,1}div[^\>]*\>/i', '', $message);

        $data = [
            'chat_id' => '@'.$group_chat_id, //'@update_contracts_token129',
            'text' => $message,
        ];
        $response = file_get_contents("http://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) . "&parse_mode=html");

        print_r($response);
    }
     
}
