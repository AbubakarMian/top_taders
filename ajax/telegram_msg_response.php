<?php
include('../common/sol_scan.php');
require_once('../js/ajax/telegram/Telegram.php');
use Telegram\Telegram;

$solScan = new SolScan();
$action = $_GET['action'];
if($action == 'unique_tokens'){
    $transfers = $solScan->getAccountTokens($_GET['wallet_address']);
    // echo $transfers;

    $transfers_json = json_decode($transfers,true);
    $message = 'Unique Tokens : Total '.count($transfers_json)." \n ";
    foreach ($transfers_json as $key => $transfer) {
        $message .= " 💰Account ".$transfer['tokenAccount']." \n ";
        $message .= " 💲Balance ".$transfer['tokenAmount']['uiAmountString'].' '.$transfer['tokenSymbol']." \n ";
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

