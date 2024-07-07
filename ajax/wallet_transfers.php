<?php
include('../common/sol_scan.php');
$solScan = new SolScan();
$transfers = $solScan->getWalletTransfers($_GET['wallet_address']);
// $transfers = $solScan->getExportTransactions($_GET['wallet_address']);
echo $transfers;
return $transfers;
