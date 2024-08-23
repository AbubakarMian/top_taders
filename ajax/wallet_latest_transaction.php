<?php
include('../common/api_sol_scan.php');

$response = new \stdClass();
$limit = $_GET['limit'] ?? 10;
$page = $_GET['page'] ?? 1;
$wallet_address = $_GET['wallet_address'] ?? '';
$offset = $_GET['offset'] ?? 0;
$uniq_hash = $_GET['uniq_hash'] ?? '';
$solScan = new ApiSolScan(1,$page,$limit);
$response = new \stdClass();
$response->transactional_details = $solScan->getLastTransactionsApi( $wallet_address, 50,$uniq_hash);

echo json_encode($response);
return;
