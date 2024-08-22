<?php
include('../common/api_sol_scan.php');

$response = new \stdClass();
$limit = $_GET['limit'] ?? 10;
$page = $_GET['page'] ?? 1;
$wallet_address = $_GET['wallet_address'] ?? '';
$solScan = new ApiSolScan(1,$page,$limit);
$response = new \stdClass();
$response->sol_balance = $solScan->getSolanabalancefromapi( $wallet_address);
$response->usd_balance = $solScan->solToUsd($response->sol_balance);
$response->wallet_address = $wallet_address;

echo json_encode($response);
return;
