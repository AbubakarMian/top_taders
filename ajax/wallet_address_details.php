<?php
include('../common/api_sol_scan.php');
// require_once('../js/ajax/telegram/Telegram.php');
use Telegram\Telegram;

$response = new \stdClass();
$solScan = new ApiSolScan();
$response->all_token_details = $solScan->getAccountTokens($_GET['wallet_address']);
$response->assosiative_wallets = $solScan->getWalletTransfers($_GET['wallet_address']);
$aggrigate_result = $response->assosiative_wallets['transactional_details']['aggrigate_result'];
$response->all_token_details->roi = $aggrigate_result['roi'];
$response->all_token_details->win_rate = $aggrigate_result['win_rate'];
$response->all_token_details->profit = $aggrigate_result['profit'];
echo json_encode($response);
return;
return $transfers;
$transfers_json = json_decode($transfers,true);
$message = 'Unique Tokens : Total '.count($transfers_json)." \n ";
foreach ($transfers_json as $key => $transfer) {
    $message .= " 💰Account ".$transfer['tokenAccount']." \n ";
    $message .= " 💲Balance ".$transfer['tokenAmount']['uiAmountString'].' '.$transfer['tokenSymbol']." \n ";
}
// $apiToken = TELEGRAM_KEY;

//     $telegram = new Telegram();
//     $group_chat_id = GROUP_CHAT_ID;
//     $telegram->send_message($apiToken,$message,$group_chat_id);
// return $transfers;
