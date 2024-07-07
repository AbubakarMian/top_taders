function get_Wallet_transfers_info(wallet_address) {
    $.ajax({
        url: app_url + "/ajax/wallet_transfers.php",
        method: "GET",
        data: { wallet_address: wallet_address },
        dataType: "json",
        success: function (response) {
            console.log('Response:', response);
            var data = response.data;
            var wallet_details_tbl_body = $('#wallet_details_tbl_body');
            var defi_activities_tbl_body = $('#defi_activities_tbl_body');
            var defi_transactional_details_tbl_body = $('#defi_transactional_details_tbl_body');
            wallet_details_tbl_body.empty();
            defi_activities_tbl_body.empty();
            defi_transactional_details_tbl_body.empty();
            
            data.forEach(function (item) {

                var truncatedDst = truncateString(item.dst, 10);
                var truncatedSrc = truncateString(item.src, 10);
                var truncatedTxHash = truncateString(item.txHash, 10);

                var row = '<tr>' +
                    '<td>' + new Date(item.blockTime * 1000).toLocaleString() + '</td>' +
                    '<td title="' + item.dst + '">' +
                    '<span class="copy-text" data-text="' + item.dst + '">' + truncatedDst + '</span>' +
                    '<button class="btn btn-sm btn-outline-primary copy-btn">Copy</button>' +
                    '</td>' +
                    '<td title="' + item.src + '">' +
                    '<span class="copy-text" data-text="' + item.src + '">' + truncatedSrc + '</span>' +
                    '<button class="btn btn-sm btn-outline-primary copy-btn">Copy</button>' +
                    '</td>' +
                    '<td title="' + item.txHash + '">' +
                    '<span class="copy-text" data-text="' + item.txHash + '">' + truncatedTxHash + '</span>' +
                    '<button class="btn btn-sm btn-outline-primary copy-btn">Copy</button>' +
                    '</td>' +
                    '<td>' + item.lamport + '</td>' +
                    // `<td><div class="btn btn-primary defi-activities" onclick="getDefiActivities('` + item.src + `')">
                    //         Load</div></td>` +
                    '</tr>';

                    wallet_details_tbl_body.append(row);
                    console.log('amount ',item.transferred_amount_sol);
                    console.log('amount check ',(item.transferred_amount_sol > 0.9));
                    if(item.lamport > 1000000){
                        defi_activities_tbl_body.append(row);
                        getDefiActivityDetail(item.txHash);
                    }
            });
            $('.copy-btn').on('click', function() {
                var textToCopy = $(this).siblings('.copy-text').data('text');
                copyToClipboard(textToCopy);
            });
        },
        error: function (xhr, status, error) {
            console.log('Error:', error);
        }
    });
}
