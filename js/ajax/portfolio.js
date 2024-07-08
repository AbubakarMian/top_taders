function getPortfolio(wallet_address) {
    $.ajax({
        url: app_url + "/ajax/account_tokens.php",
        method: "GET",
        data: { wallet_address: wallet_address },
        dataType: "json",
        success: function (response) {
            console.log('getDefiActivityDetail Response:', response);
            var portfolio_details_tbl_body = $('#portfolio_details_tbl_body');
            portfolio_details_tbl_body.empty();
            var data = response;
            data.forEach(function (item,key) {
                var truncatetokenAccount = truncateString(item.tokenAccount, 33);
                var details_row = `
                <tr>
                    <td title="${item.tokenAccount}">
                        <span class="copy-text" data-text="${item.tokenAccount}">${truncatetokenAccount}</span>
                        <button class="btn btn-sm btn-outline-primary copy-btn">Copy</button>
                    </td>
                    <td>${item.tokenName}</td>
                    <td>${item.tokenAmount.uiAmountString}</td>
                </tr>
                `;
                portfolio_details_tbl_body.append(details_row);
            });

            
            $('.copy-btn').on('click', function() {
                var textToCopy = $(this).siblings('.copy-text').data('text');
                copyToClipboard(textToCopy);
                // alert('Copied to clipboard: ' + textToCopy);
            });
        },
        error: function (xhr, status, error) {
            console.log('Error:', error);
        }
    });
    return "Portfolio ";
}



