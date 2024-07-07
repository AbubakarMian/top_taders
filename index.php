<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Updates Top Trades</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/app.css">
</head>
<!-- GavdQbE7LjmsrG5CvmkkRtwWzjf4Nev34DhjF2SnANpX -->
<body>
    <div class="container">
        <img src="images/top_traders.jpg" alt="Bullsama AI Bot Logo" class="logo">
        <input type="text" id="input_wallet_address" name="input_wallet_address" value="AK1XpdPmHhjvbjRyY1uiY3qoaCPEKFSjwSZuEgKeL2t">
                    <div class="btn btn-primary" onclick="get_complete_details();">Wallet Transfers</div>
        <div class="wizard">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#tab1" class="nav-link active" data-toggle="tab">Transfers</a>
                </li>
                <li class="nav-item">
                    <a href="#tab2" class="nav-link" data-toggle="tab">Defi activities</a>
                </li>
                <li class="nav-item">
                    <a href="#tab3" class="nav-link" data-toggle="tab">Transaction details</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab1">
                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>To</th>
                                <th>From</th>
                                <th>Signature</th>
                                <th>Amount</th>
                                <!-- <th>Defi Activities</th> -->
                            </tr>
                        </thead>
                        <tbody id="wallet_details_tbl_body">
                           
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab2">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>To</th>
                                <th>From</th>
                                <th>Signature</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="defi_activities_tbl_body">
                            <tr>
                                <td>Data 1</td>
                                <td>Data 2</td>
                                <td>Data 3</td>
                            </tr>
                            <!-- More rows as needed -->
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab3">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Symbol</th>
                                <th>Account</th>
                                <th>Icon</th>
                            </tr>
                        </thead>
                        <tbody id="defi_transactional_details_tbl_body">
                            <tr>
                                <td>Data 1</td>
                                <td>Data 2</td>
                                <td>Data 3</td>
                            </tr>
                            <!-- More rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/ajax/get_wallet_transfers.js"></script>
    <script src="js/ajax/defi_activities.js"></script>
    <script src="js/ajax/common.js"></script>
    <script>
        var app_url = "http://localhost/top_traders";
        function get_complete_details() {
            var wallet_address = $('#input_wallet_address').val();
            get_Wallet_transfers_info(wallet_address);
        }
    </script>
</body>

</html>