<?php
include('../common/api_sol_scan.php');

$response = new \stdClass();
$limit = $_GET['limit'] ?? 10;
$page = $_GET['page'] ?? 1;
$wallet_address = $_GET['wallet_address'] ?? '';
$solScan = new ApiSolScan(1,$page,$limit);
$response = new \stdClass();
$response->transactional_details = $solScan->getLastTransactionsApi( $wallet_address, 1);

echo json_encode($response);
return;
