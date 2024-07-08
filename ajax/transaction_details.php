<?php
include('../common/sol_scan.php');
require_once('../js/ajax/telegram/Telegram.php');

use Telegram\Telegram;

$solScan = new SolScan();
$transactionDetails = $solScan->getTransactionDetails($_GET['signature']);
echo $transactionDetails;

$transactionDetails_json = json_decode($transactionDetails, true);
$message = '';
foreach ($transactionDetails_json as $key => $transfer_detail) {
    if ($key % 2 === 0) {
        $message .= " 💲Spent " . $transfer_detail['tokenAmount'] . ' ' . $transfer_detail['token_symbol'] . " \n ";
    } else {
        $message .= " 💰Bought " . $transfer_detail['tokenAmount'] . ' ' . $transfer_detail['token_symbol'];
    }
}
$apiToken = TELEGRAM_KEY;
$telegram = new Telegram();
$group_chat_id = GROUP_CHAT_ID;
$telegram->send_message($apiToken, $message, $group_chat_id);

return $transactionDetails;
