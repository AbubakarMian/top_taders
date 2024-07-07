<?php
include('../common/sol_scan.php');
$solScan = new SolScan();
$transactionDetails = $solScan->getTransactionDetails($_GET['signature']);
echo $transactionDetails;
return $transactionDetails;
