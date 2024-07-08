<?php
include('../common/sol_scan.php');
require_once('../js/ajax/telegram/Telegram.php');
use Telegram\Telegram;

$solScan = new SolScan();
$transfers = $solScan->getAccountTokens($_GET['wallet_address']);
// $transfers = $solScan->getExportTransactions($_GET['wallet_address']);
echo $transfers;

$transfers_json = json_decode($transfers,true);
$message = 'Unique Tokens : Total '.count($transfers_json)." \n ";
foreach ($transfers_json as $key => $transfer) {
    $message .= " 💰Account ".$transfer['tokenAccount']." \n ";
    $message .= " 💲Balance ".$transfer['tokenAmount']['uiAmountString'].' '.$transfer['tokenSymbol']." \n ";
}
// die($message);
// $apiToken = "7447580521:AAHe8yjHmOdE3pw7fW7zTnuN-EKRDddpVNE";
$apiToken = TELEGRAM_KEY;

    $telegram = new Telegram();
    // $group_chat_id = 'top_trader_notifications_001';
    $group_chat_id = GROUP_CHAT_ID;
    $telegram->send_message($apiToken,$message,$group_chat_id);
return $transfers;
