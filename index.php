<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Updates Top Trades</title>
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/index.css">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<!-- GavdQbE7LjmsrG5CvmkkRtwWzjf4Nev34DhjF2SnANpX -->

<body class="body_back">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="logo_area">
                    <img src="images/top_traders.jpg" alt="Bullsama AI Bot Logo" class="logo">

                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-10">
                <div class="search_area">

                    <input type="text" class=" form-control inp" id="input_wallet_address" name="input_wallet_address" value="AK1XpdPmHhjvbjRyY1uiY3qoaCPEKFSjwSZuEgKeL2t">
                </div>
            </div>
            <div class="col-md-2">
                <div class="search_area">

                    <div class="btn btn-primary wall_btn" onclick="get_complete_details();">Wallet Transfers</div>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="wizard">
                    <ul class="nav nav-pills nav-justified">
                        <li class="nav-item">
                            <a href="#tab1" class="nav-link active" data-toggle="tab">Transfers</a>
                        </li>
                        <li class="nav-item">
                            <a href="#tab2" class="nav-link" data-toggle="tab">Defi activities</a>
                        </li>
                        <li class="nav-item">
                            <a href="#tab3" class="nav-link" data-toggle="tab">Transaction</a>
                        </li>
                        <li class="nav-item">
                            <a href="#tab4" class="nav-link" data-toggle="tab">Portfolio</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">

                            <table class="table tbl_style table-striped">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>To</th>
                                        <th>From</th>
                                        <th>Signature</th>
                                        <!-- <th>Amount</th> -->
                                        <!-- <th>Defi Activities</th> -->
                                    </tr>
                                </thead>
                                <tbody id="wallet_details_tbl_body">

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <table class="table tbl_style table-striped">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>To</th>
                                        <th>From</th>
                                        <th>Signature</th>
                                        <!-- <th>Amount</th> -->
                                    </tr>
                                </thead>
                                <tbody id="defi_activities_tbl_body">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <!-- More rows as needed -->
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="tab3">
                            <table class="table tbl_style table-striped">
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
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <!-- More rows as needed -->
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="tab4">
                            <table class="table tbl_style table-striped">
                                <thead>
                                    <tr>
                                        <th>Account</th>
                                        <th>Token</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody id="portfolio_details_tbl_body">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <!-- More rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/ajax/common.js"></script>
    <script src="js/ajax/get_wallet_transfers.js"></script>
    <script src="js/ajax/defi_activities.js"></script>
    <script src="js/ajax/portfolio.js"></script>
    <script>
        // var app_url = "http://localhost/top_traders";
        var protocol = window.location.protocol;
        var hostname = window.location.hostname;
        var port = window.location.port;
        var path = "/top_traders";
        var app_url = protocol + "//" + hostname + (port ? ":" + port : "") + path;


        function get_complete_details() {
            var wallet_address = $('#input_wallet_address').val();
            get_Wallet_transfers_info(wallet_address);
            getPortfolio(wallet_address);
        }
    </script>
</body>

</html>