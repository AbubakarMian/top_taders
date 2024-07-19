<?php
include('../enums.php');

class ApiSolScan
{
    public $solscan_key = SOLSCAN_KEY;

    function getWalletAdressDetails($walletAddress)
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
        return $response;
    }

    function getAccountTokens($walletAddress)
    {
        $wallet_address_res = $this->getWalletAdressDetails($walletAddress);
        // echo $wallet_address_res;
        $response = new \stdClass();
        $total_sol_amount = $this->calculateWalletTotalSolAmount($wallet_address_res);
        $response->address = $walletAddress;
        $response->token_details = json_decode($wallet_address_res);
        $response->total_tokens = $total_sol_amount['total_token'];
        $response->sol_balance = $total_sol_amount['sol_balance'];
        $response->usd_balance = $this->solToUsd($total_sol_amount['sol_balance']);
        return $response;
    }
    function solToUsd($total_sol)
    {
        $rate = $this->getConversionRates();
        $total_sol = $rate['solana']['usd'] * $total_sol;
        return $total_sol;
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

        $getTransactionalDetails = $this->getTransactionalDetails($response);
        $assosiative_wallets = $this->getAssociativeWallets($response);

        curl_close($curl);
        // $response = $this->addTransferredAmount($response, $walletAddress);
        return [
            'transactional_details' => $getTransactionalDetails,
            'assosiative_wallets' => $assosiative_wallets,
            [
                'addresses' => $assosiative_wallets['wallet_address'],
                'token_details' => $assosiative_wallets['wallet_token_details']
            ]
        ];
    }
    function getTransactionalDetails($transactions)
    {
        $transactions_arr = json_decode($transactions, true);
        // $signautures = array_column($transactions_arr['data'], 'txHash');
        $res['wallet'] = [];
        foreach ($transactions_arr['data'] as $key => $transaction) {
            $transaction_detail = (array) $this->getTransactionDetails($transaction['txHash']); // Ensure it's an array

            if (isset($res['wallet'][$transaction['src']])) {
                $res['wallet'][$transaction['src']] = (array) $res['wallet'][$transaction['src']]; // Ensure it's an array
                $res['wallet'][$transaction['src']]['roi'] += $transaction_detail['roi'];
                $res['wallet'][$transaction['src']]['win_rate'] += $transaction_detail['win_rate'];
                $res['wallet'][$transaction['src']]['profit'] += $transaction_detail['profit'];
            } else {
                $res['wallet'][$transaction['src']] = [
                    'wallet_address' => $transaction['src'],
                    'roi' => $transaction_detail['roi'],
                    'win_rate' => $transaction_detail['win_rate'],
                    'profit' => $transaction_detail['profit'],
                    'total_roi' => 0,
                    'total_win_rate' => 0,
                    'total_profit' => 0,
                    'total_transactions' => 0,
                ];
            }

            // Remove die and debugging to allow code to continue execution
            // die(json_encode((array) $res['wallet'][$transaction['src']])); // Ensure it's an array

            $res['wallet'][$transaction['src']]['total_roi'] = ($res['wallet'][$transaction['src']]['total_roi'] + $transaction_detail['roi']) / 2;
            $res['wallet'][$transaction['src']]['total_win_rate'] = ($res['wallet'][$transaction['src']]['total_win_rate'] + $transaction_detail['win_rate']) / 2;
            $res['wallet'][$transaction['src']]['total_profit'] += $transaction_detail['profit'];
            $res['wallet'][$transaction['src']]['total_transactions']++;
        }
        $total_roi = 0;
        $total_win_rate = 0;
        $total_profit = 0;
        $total_transactions = 0;
        foreach ($res['wallet'][$transaction['src']] as $key => $value) {
            $total_roi += $res['wallet'][$transaction['src']]['total_roi'];
            $total_win_rate += $res['wallet'][$transaction['src']]['total_win_rate'];
            $total_profit += $res['wallet'][$transaction['src']]['total_profit'];
            $total_transactions++;
        }
        $res['aggrigate_result']['roi'] = round($total_roi / $total_transactions, 5);
        $res['aggrigate_result']['win_rate'] = round($total_win_rate / $total_transactions, 5);
        $res['aggrigate_result']['profit'] = number_format(round($total_profit, 5), 5); // Total profit is already summed up

        // die(json_encode($res));
        // die(json_encode($res['wallet'][$transaction['src']]));
        return $res;
    }

    function getAssociativeWallets($transactions)
    {
        $transaction_arr_data = json_decode($transactions, true);
        $transaction_arr = $transaction_arr_data['data'];
        $src_values = array_column($transaction_arr, 'src');
        $dst_values = array_column($transaction_arr, 'dst');
        $merged_values = array_merge($src_values, $dst_values);
        $unique_addresses = array_unique($merged_values);
        $unique_addresses = array_values($unique_addresses);

        foreach ($unique_addresses as $key => $unique_address) {
            $token_details[] = $this->getAccountTokens($unique_address);
        }
        return [
            'wallet_address' => $unique_addresses,
            'wallet_token_details' => $token_details
        ];
    }

    function calculateWalletTotalSolAmount($json_response)
    {
        $tokens = json_decode($json_response, true);
        $total_amount_in_sol = 0;

        foreach ($tokens as $token) {
            $token_amount = $token['tokenAmount']['amount']; // Amount of tokens in smallest unit (e.g., 187258267 for CHIPPY)
            $decimals = $token['tokenAmount']['decimals']; // Decimals for this token (e.g., 6 for CHIPPY)

            $amount_in_sol  = $this->convert_val_to_coin($token_amount, $decimals);
            $total_amount_in_sol  = $total_amount_in_sol + $amount_in_sol;
        }
        return [
            'total_token' => count($tokens),
            'sol_balance' => $total_amount_in_sol
        ];
    }
    function convert_val_to_coin($amount, $decimals)
    {
        // Pad the amount with leading zeros to ensure it's at least $decimals + 1 digits long
        $new_amount = str_pad($amount, $decimals + 1, '0', STR_PAD_LEFT);

        // Calculate the insertion position for the decimal point
        $positionFromEnd = $decimals;
        $length = strlen($new_amount);
        $insertionPosition = $length - $positionFromEnd;

        // Split the string into two parts and insert the decimal point
        $firstPart = substr($new_amount, 0, $insertionPosition);
        $secondPart = substr($new_amount, $insertionPosition);

        // Remove leading zeros except one before the decimal point
        $firstPart = ltrim($firstPart, '0');
        if ($firstPart === '') {
            $firstPart = '0';
        }

        // Limit the digits after the decimal point to 5
        $secondPart = substr($secondPart, 0, 5);

        // Remove trailing zeros after the decimal point
        $secondPart = rtrim($secondPart, '0');
        if ($secondPart === '') {
            return $firstPart; // Return without the decimal point if there are no decimals
        }

        return $firstPart . '.' . $secondPart;
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
        // $response_std = new \stdClass();
        $transaction_details = $this->processTransactionData($response);
        // die(json_encode($response_std));
        $winrate_roi = $this->calculate_Winrate_ROI($response);
        $roi = $winrate_roi['roi'];
        $win_rate = $winrate_roi['win_rate'];
        $profit = $winrate_roi['profit'];
        return [
            'transaction_details' => $transaction_details,
            'roi' => $roi,
            'win_rate' => $win_rate,
            'profit' => $profit,
        ];
    }

    function processTransactionData($response)
    {
        $data = json_decode($response, true);

        $response = [];
        if (isset($data['raydiumTransactions']) && !empty($data['raydiumTransactions'])) {
            foreach ($data['raydiumTransactions'] as $transaction) {
                $swap = $transaction['swap'];
                foreach ($swap['event'] as $event) {
                    $amount = $event['amount'];
                    // if(!isset($event['symbol']))
                    // die(json_encode($event));
                    $tokenSymbol = $event['symbol'] ?? 'SOL';
                    $tokenDecimals = $event['decimals'] ?? 9;
                    $tokenIcon = $event['icon'] ?? '';
                    $amountToUse = isset($event['postAmount']) ? $event['postAmount'] : $event['amount'];
                    // $tokenValue = $amountToUse / pow(10, $tokenDecimals);
                    $tokenValue = $this->convert_val_to_coin($amountToUse, $tokenDecimals);
                    $data = [
                        'token_symbol' => $tokenSymbol,
                        'amount' => $amount,
                        'tokenAmount' => $tokenValue,
                        'tokenIcon' => $tokenIcon,
                    ];
                    $response[] = $data;
                    $tokens[] = strtolower($tokenSymbol);
                }
            }
        } else {
            foreach ($data['solTransfers'] as $transfer) {
                $amount = $transfer['amount'];
                $tokenSymbol = 'SOL';
                $tokenDecimals = 9; // SOL has 9 decimals
                // $tokenValue = $amount / pow(10, $tokenDecimals);
                $tokenValue =  $this->convert_val_to_coin($amount, $tokenDecimals);

                $data = [
                    'token_symbol' => $tokenSymbol,
                    'amount' => $amount,
                    'tokenAmount' => $tokenValue,
                    'tokenIcon' => '', // Add a placeholder or actual icon URL
                ];
                $response[] = $data;
                // $tokens[] = strtolower($tokenSymbol);
            }
        }
        return $response;
    }
    function calculate_Winrate_ROI($response)
    {
        $data = json_decode($response, true);
        $investment = 0;
        $currentValue = 0;
        $profit = 0;
        $wins = 0;
        $totalTrades = 0;
        $decimals = 9;

        foreach ($data['inputAccount'] as $account) {
            if ($account['signer']) {
                $investment += $account['preBalance'];
                $currentValue += $account['postBalance'];
            }

            if ($account['preBalance'] != $account['postBalance']) {

                // $preBalanceSOL = $account['preBalance'] / pow(10, $decimals);
                // $postBalanceSOL = $account['postBalance'] / pow(10, $decimals);
                $preBalanceSOL =  $this->convert_val_to_coin($account['preBalance'], $decimals);
                $postBalanceSOL = $this->convert_val_to_coin($account['postBalance'], $decimals);
                $profit += $postBalanceSOL - $preBalanceSOL;
                $totalTrades++;
                if ($account['postBalance'] > $account['preBalance']) {
                    $wins++;
                }
            }
        }

        $roi = ($currentValue - $investment) / $investment * 100;
        $winRate = ($totalTrades > 0) ? ($wins / $totalTrades * 100) : 0;
        return [
            'roi' => $roi,
            'win_rate' => $winRate,
            'profit' => $profit,
        ];
    }

    function getConversionRates()
    {
        return [
            "raydium" => ["usd" => 1.95],
            "solana" => ["usd" => 159.27]
        ];
        $apiUrl = 'https://api.coingecko.com/api/v3/simple/price?ids=solana,raydium&vs_currencies=usd';
        $response = @file_get_contents($apiUrl);
        if ($response) {
            return json_decode($response, true);
        } else {
            return [
                "raydium" => ["usd" => 1.95],
                "solana" => ["usd" => 159.27]
            ];
        }
    }



    function addTransferredAmount($json, $walletAddress)
    {
        // $json = '[{"slot":275072820,"blockTime":1719841214,"txHash":"3tk6Jb87pRFkH6kToxgo45AM4p7t1qG2ZQWSVu4yhPZNziC6jgYUBXK7mrhC5CJzX7iC8dXYA4y55MaHELWPwGSN","src":"AK1XpdPmHhjvbjRyY1uiY3qoaCPEKFSjwSZuEgKeL2t","decimals":9,"dst":"J98kdKS9sL9CZ5NKczcCQCyHVoPhwZh3evq9XMa6RvFX","lamport":27000,"status":"Success"},{"slot":275072804,"blockTime":1719841206,"txHash":"zQEfgw9Fus4tn5Sh6ceoUgvCakzcgChBQLxZF8T1jenTAzCLWEPzGwcMXWakKbrDtHBrZDFjd1jMwBM7mH9F9QA","src":"AK1XpdPmHhjvbjRyY1uiY3qoaCPEKFSjwSZuEgKeL2t","decimals":9,"dst":"9GmFMR1yQ4pyJfJByUa54xjxMiBM6QEuy9Xmm2AG47Kk","lamport":5039280,"status":"Success"}]';

        $data = json_decode($json, true);

        foreach ($data['data'] as &$transaction) {
            $decimals = $transaction['decimals'];
            $lamports = $transaction['lamport'];
            // $sol = $lamports / pow(10, $decimals);
            $sol  = $this->convert_val_to_coin($lamports, $decimals);
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
