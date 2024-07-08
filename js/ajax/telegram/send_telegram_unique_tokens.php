<?php

require_once('Telegram.php');

// use Database\Database;
use Telegram\Telegram;

            

// $database = new Database();
    
    $message ="hello i am here heeeeee";
    // $message .=  "\xE2\x9C\x8F Name: ".$token_details_name."\n";
    // $message .=  "\xE2\x9C\x92 Token:<code>".$contract."</code>\n";
    // $message .=  "\xF0\x9F\x94\x90 Liquidity : Locked\n";
    // $message .=  "\xF0\x9F\x92\xB2 Liquidity :".$liquidity."\n";
    // $message .=  "\xE2\x9C\x85 Has Social Links \n";
    // $message .=  "\xF0\x9F\x92\xB5 MKT Cap: ".$mkt_cap."\n";
    // $twitter_message = "🤖🚀 New Stable Token Detected on $chain 🚀🤖 🔖 \\n Name: ".$token_details_name."\\n 📋 Contract: ".$contract."\\n🔐 Liquidity: Locked \\n💲 Total Liquidity: $liquidity \\n✅Has Social Links \\n💵 MKT Cap :$mkt_cap \\n";

    // $link = "\xF0\x9F\x94\x97";
    // $url = "";
    // $wave1solana = 'wave1solana';
    // $wave1etherium = 'wave1etherium';
    // $message .= $link."<a href=\"https://tokensniffer.com/token/".$contract."\">Tokensniffer</a>\n";
    // // https://t.me/+xfpbu_IVWQNiZGM0
    // https://t.me/+8Ozn7ytcjUFiN2Rk

    https://t.me/top_trader_notifications_001

    $apiToken = "7447580521:AAHe8yjHmOdE3pw7fW7zTnuN-EKRDddpVNE";
    // $apiToken = "6411141649:AAHWHj-Bpm6ChtDXQOP83qx3EwWIF3DKly4";

    $telegram = new Telegram();
    $group_chat_id = 'top_trader_notifications_001';
    $telegram->send_message($apiToken,$message,$group_chat_id);
 