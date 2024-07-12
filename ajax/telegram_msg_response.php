<?php
include('../common/sol_scan.php');
require_once('../js/ajax/telegram/Telegram.php');
use Telegram\Telegram;

$solScan = new SolScan();
$action = $_GET['action'];
if(in_array($action,['unique_tokens','wallet_info']) ){
    $transfers = $solScan->getAccountTokens($_GET['wallet_address']);
    // echo $transfers;

    $transfers_json = json_decode($transfers,true);
    $unique_token_message = 'Unique Tokens : Total '.count($transfers_json)." \n ";
    $wallet_info_message = "";
    $message = '';
    foreach ($transfers_json as $key => $transfer) {
        $unique_token_message .= " 💰Account ".$transfer['tokenAccount']." \n ";
        $unique_token_message .= " 💲Balance ".$transfer['tokenAmount']['uiAmountString'].' '.$transfer['tokenSymbol']." \n ";
        $wallet_info_message .= " 💰Account ".$transfer['tokenAccount']." \n ";
        $wallet_info_message .= " 💲Balance ".$transfer['tokenAmount']['uiAmountString'].' '.$transfer['tokenSymbol']." \n ";
        
    }
    if($action == 'unique_tokens'){
        $message = $unique_token_message;
    }
    else{
        $message = $wallet_info_message;
    }
        $res = [
            'status'=>true,
            'response'=>[
                'message'=>$message,
                'data'=>$transfers_json
            ],
            'error'=>[]
        ];
        echo json_encode($res);
        die();
}

if($action==='defi_activities' ){
    $transfers = $solScan->getAccountTokens($_GET['wallet_address']);
    // echo $transfers;

    $transfers_json = json_decode($transfers,true);
    $message = "Defi Activities \n";
    $empty_msg = " sorry no defi activities here";
    foreach ($transfers_json as $key => $transfer) {
        if(!in_array($transfer['tokenName'],[
            "Pump fun",
            "Moon shot",
            "Raydium",
            "Júpiter",
        ])){
            continue;
        }
        $empty_msg = "";

        $message .= " 💰Account ".$transfer['tokenAccount']." \n ";
        $message .= " 💲Balance ".$transfer['tokenAmount']['uiAmountString'].' '.$transfer['tokenSymbol']." \n ";
        
    }
    $message .=$empty_msg; 
        $res = [
            'status'=>true,
            'response'=>[
                'message'=>$message,
                'data'=>$transfers_json
            ],
            'error'=>[]
        ];
        echo json_encode($res);
        die();
}

