<?php
include('../enums.php');

class SolScan
{
    public $solscan_key = SOLSCAN_KEY;

    
    function getAccountTokens($walletAddress)
    {
        $solscan_key = $this->solscan_key;
        $url = "https://pro-api.solscan.io/v1.0/account/tokens?account=$walletAddress";
        $curl = curl_init();
        $token = "token: $solscan_key";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // $response = $this->addTransferredAmount($response, $walletAddress);
        // return json_decode($response,true);
        return $response;
    }

    function getWalletTransfers($walletAddress, $limit = 10)
    {
        $solscan_key = $this->solscan_key;
        $url = "https://pro-api.solscan.io/v1.0/account/solTransfers?account=$walletAddress&limit=10";
        $curl = curl_init();
        $token = "token: $solscan_key";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = $this->addTransferredAmount($response, $walletAddress);
        return $response;
    }

    function getTransactionDetails($signauture)
    {
        $solscan_key = $this->solscan_key;
        // $url = "https://pro-api.solscan.io/v1.0/account/solTransfers?account=$walletAddress&limit=10";
        $url = "https://pro-api.solscan.io/v1.0/transaction/$signauture";
        $curl = curl_init();
        $token = "token: $solscan_key";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $this->processTransactionData($response);
        return $response;
    }

    function processTransactionData($response)
    {
        $data = json_decode($response, true);

        // Process token balances
        // foreach ($data['tokenBalances'] as $tokenBalance) {
        //     $amount = $tokenBalance['amount']['postAmount']; // Use postAmount for USD calculation
        //     $symbol = $tokenBalance['token']['symbol'];
        //     $decimals = $tokenBalance['token']['decimals'];
        //     $tokenIcon = $tokenBalance['token']['icon'];

        //     // Calculate USD value
        //     $usdValue = $amount / pow(10, $decimals);

        //     // Output the result
        //     echo "{$tokenBalance['token']['name']} $amount $usdValue Image URL: $tokenIcon\n";
        // }

        // echo "<br><br><br>";
        // Process raydiumTransactions
        $response = [];
        foreach ($data['raydiumTransactions'] as $transaction) {
            $swap = $transaction['swap'];
            foreach ($swap['event'] as $event) {
                $amount = $event['amount'];
                $tokenSymbol = $event['symbol'];
                $tokenDecimals = $event['decimals'];
                $tokenIcon = $event['icon'];

                // Calculate USD value based on pre or post amount, depending on availability
                $amountToUse = isset($event['postAmount']) ? $event['postAmount'] : $event['amount'];
                $tokenValue = $amountToUse / pow(10, $tokenDecimals);
                // $usdValue = $amountToUse / pow(10, $tokenDecimals);

                // Output the result
                // echo "$tokenSymbol $amount $usdValue Image URL: $tokenIcon\n";
                $data = [
                    'token_symbol'=>$tokenSymbol,
                    'amount'=>$amount,
                    'tokenAmount'=>$tokenValue,
                    'tokenIcon'=>$tokenIcon,
                ];
                $response[] = $data;
            }
        }
        // print_r(json_encode($response));
        return json_encode($response);
        // $data = json_decode($response, true);
        // foreach ($data['innerInstructions'] as $instruction) {
        //     foreach ($instruction['parsedInstructions'] as $parsedInstruction) {
        //         $amount = $parsedInstruction['params']['amount'];
        //         $symbol = $parsedInstruction['extra']['symbol'];
        //         $decimals = $parsedInstruction['extra']['decimals'];

        //         // Perform currency conversion
        //         $conversionRate = $this->getConversionRate($symbol, 'USD');
        //         $convertedAmount = $conversionRate * $amount;

        //         $decimalAdjustment = $convertedAmount / (pow(10, $decimals));
        //         // Print or format the result as needed
        //         echo "Amount: $amount $symbol\n";
        //         echo "decimalAdjustment to USD: $decimalAdjustment\n";
        //         echo "Converted to USD: $convertedAmount\n";
        //         echo "Image URL: {$parsedInstruction['extra']['icon']}\n";
        //     }
        // }
    }
    // function processTransactionData($jsonData) {
    //     $data = json_decode($jsonData, true);
    //     // $this->displayTransactionsWithConversion($data);
    //     $amount = $data['tokenBalances'][0]['amount']['preAmount'] / pow(10, $data['tokenBalances'][0]['token']['decimals']);
    //     // $convertedAmount = $amount / 1000; // Assuming the conversion rate for the sake of example
    //     // $amountInUSD = number_format($convertedAmount * 0.139218, 6); // Using a placeholder conversion rate
    //     $amountInUSD = number_format($this->getConversionRate($amount, 'USD'), 6); // Using a placeholder conversion rate
    //     $token1 = $data['tokenBalances'][0]['token']['name']; //symbol BullSama
    //     $token2Amount = $data['tokenBalances'][1]['amount']['preAmount'] - $data['tokenBalances'][1]['amount']['postAmount'];
    //     $token2 = $data['tokenBalances'][1]['token']['name'];//symbol  Wrapped SOL
    //      $token2ConvertedAmount = $token2Amount / pow(10, $data['tokenBalances'][1]['token']['decimals']);
    //     // $token2InUSD = number_format($token2ConvertedAmount * 0.005698, 6); // Using a placeholder conversion rate
    //     $amountInUSD = number_format($this->getConversionRate($token2ConvertedAmount, 'USD'), 6); // Using a placeholder conversion rate
    //     $res = [
    //         ''
    //     ];
    //     // $fromAccount = $data['parsedInstruction'][0]['params']['authority'];
    //     // $toAccount = $data['parsedInstruction'][0]['params']['destination'];

    // }
    // function displayTransactionsWithConversion($transactions) {

    //     foreach ($transactions as $transaction) {
    //         echo "<strong>{$transaction['operation']}</strong><br>";

    //         // Display transaction details
    //         if (isset($transaction['from_address'])) {
    //             echo "From: {$transaction['from_address']}<br>";
    //         }
    //         if (isset($transaction['to_address'])) {
    //             echo "To: {$transaction['to_address']}<br>";
    //         }
    //         echo "Amount: {$transaction['amount']}<br>";
    //         echo "Amount (USD): {$transaction['amount_usd']}<br>";
    //         echo "Token Name: {$transaction['token_name']}<br>";

    //         // Fetch and display conversion rate if conversion currency is set
    //         if (isset($transaction['conversion_currency'])) {
    //             $fromCurrency = strtolower($transaction['token_name']); // Assuming token name is lowercase
    //             $toCurrency = $transaction['conversion_currency'];

    //             $conversionRate = $this->getConversionRate($fromCurrency, $toCurrency);

    //             if ($conversionRate !== null) {
    //                 $convertedAmount = $transaction['amount'] * $conversionRate;
    //                 echo "Converted Amount ({$toCurrency}): {$convertedAmount}<br>";
    //             } else {
    //                 echo "Conversion rate not available.<br>";
    //             }
    //         }

    //         echo "<br>";
    //     }
    // }

    function getConversionRate($fromCurrency, $toCurrency)
    {

        $currencies = [
            [
                'from' => 'USD',
                'to' => 'SOL',
                'value' => 0.003
            ],
            [
                'from' => 'SOL',
                'to' => 'USD',
                'value' => 333.33 // Inverse of 0.003
            ],
            [
                'from' => 'USD',
                'to' => 'BSAMA',
                'value' => 0.304799
            ],
            [
                'from' => 'BSAMA',
                'to' => 'USD',
                'value' => 3.2796 // Inverse of 0.304799
            ],
        ];

        foreach ($currencies as $currency) {
            if (strtolower($currency['from']) === strtolower($fromCurrency) && strtolower($currency['to']) === strtolower($toCurrency)) {
                return $currency['value'];
            }
        }

        return 1;
        // Base and target currencies
        // $base_currency = 'BULSAMA';  // Replace with your currency code
        // $target_currency = 'USD';    // Replace with the currency you want to convert to

        // // Build the API URL
        // $url = "$endpoint?base=$base_currency&symbols=$target_currency";

        // // Fetch data from API
        // $response = file_get_contents($url);

        // // Decode JSON response
        // $data = json_decode($response, true);

        // // Check if conversion rate data is available
        // if (isset($data['rates'][$target_currency])) {
        //     $conversion_rate = $data['rates'][$target_currency];
        //     // Example conversion (replace with your actual conversion logic)
        //     $amount_in_bulsama = 100;  // Replace with the amount you want to convert
        //     $amount_in_usd = $amount_in_bulsama * $conversion_rate;

        //     echo "$amount_in_bulsama BULSAMA is approximately $amount_in_usd USD.";
        // } else {
        //     echo "Conversion rate not available.";
        // }
        return 1;
    }


    function addTransferredAmount($json, $walletAddress)
    {
        // $json = '[{"slot":275072820,"blockTime":1719841214,"txHash":"3tk6Jb87pRFkH6kToxgo45AM4p7t1qG2ZQWSVu4yhPZNziC6jgYUBXK7mrhC5CJzX7iC8dXYA4y55MaHELWPwGSN","src":"AK1XpdPmHhjvbjRyY1uiY3qoaCPEKFSjwSZuEgKeL2t","decimals":9,"dst":"J98kdKS9sL9CZ5NKczcCQCyHVoPhwZh3evq9XMa6RvFX","lamport":27000,"status":"Success"},{"slot":275072804,"blockTime":1719841206,"txHash":"zQEfgw9Fus4tn5Sh6ceoUgvCakzcgChBQLxZF8T1jenTAzCLWEPzGwcMXWakKbrDtHBrZDFjd1jMwBM7mH9F9QA","src":"AK1XpdPmHhjvbjRyY1uiY3qoaCPEKFSjwSZuEgKeL2t","decimals":9,"dst":"9GmFMR1yQ4pyJfJByUa54xjxMiBM6QEuy9Xmm2AG47Kk","lamport":5039280,"status":"Success"}]';

        $data = json_decode($json, true);

        foreach ($data['data'] as &$transaction) {
            $decimals = $transaction['decimals'];
            $lamports = $transaction['lamport'];
            $sol = $lamports / pow(10, $decimals);
            $transaction_amount = number_format($sol, 9, '.', '');
            if ($transaction['dst'] !== $walletAddress) {
                $transaction_amount = '-' . $transaction_amount;
            }
            // $transaction['blockTime'] = date('Y-m-d H:i:s', $transaction['blockTime']);
            $transaction['transferred_amount_sol'] = rtrim($transaction_amount, '0');
        }
        $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
        return $updatedJson;
    }
}
