<?php
include('../common/api_sol_scan.php');
// require_once('../js/ajax/telegram/Telegram.php');
use Telegram\Telegram;

$response = new \stdClass();
$solScan = new ApiSolScan();
$days = $_GET['days'] ?? 30;
$is_spl = $_GET['spl'] ?? 0;
$has_tokens = $_GET['tokens'] ?? 1;
$has_assosiative_wallets = $_GET['assosiative_wallets'] ?? 0;
if($has_tokens){
    $response->all_token_details = $solScan->getAccountTokens($_GET['wallet_address'] , $days);
}
else{
    $response->all_token_details = new \stdClass();
}
if($is_spl){
    $response->all_spltoken_details = $solScan->getSplWalletAdressDetails($_GET['wallet_address'],$days);
}
else{
    $response->all_spltoken_details = [];
}
if($has_assosiative_wallets){
    $response->assosiative_wallets = $solScan->getWalletTransfers($_GET['wallet_address'], $days);
}
else{
    $response->assosiative_wallets = [];
}
// $response->assosiative_wallets_spl = $solScan->getSplTransfers($_GET['wallet_address'], $days);
// echo "getSplTransfers <br/>".json_encode($response->assosiative_wallets_spl);die();
if (isset($response->assosiative_wallets['transactional_details']['aggrigate_result'])) {
    $aggrigate_result = $response->assosiative_wallets['transactional_details']['aggrigate_result'];
    $response->all_token_details->roi = $aggrigate_result['roi'];
    $response->all_token_details->win_rate = $aggrigate_result['win_rate'];
    $response->all_token_details->profit = $aggrigate_result['profit'];
} else {
    $response->all_token_details->roi = 0;
    $response->all_token_details->win_rate = 0;
    $response->all_token_details->profit = 0;
}
echo json_encode($response);
return;
return $transfers;
$transfers_json = json_decode($transfers, true);
$message = 'Unique Tokens : Total ' . count($transfers_json) . " \n ";
foreach ($transfers_json as $key => $transfer) {
    $message .= " 💰Account " . $transfer['tokenAccount'] . " \n ";
    $message .= " 💲Balance " . $transfer['tokenAmount']['uiAmountString'] . ' ' . $transfer['tokenSymbol'] . " \n ";
}
// $apiToken = TELEGRAM_KEY;

//     $telegram = new Telegram();
//     $group_chat_id = GROUP_CHAT_ID;
//     $telegram->send_message($apiToken,$message,$group_chat_id);
// return $transfers;
