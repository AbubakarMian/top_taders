function getDefiActivityDetail(signature) {
    $.ajax({
        url: app_url + "/ajax/transaction_details.php",
        method: "GET",
        data: { signature: signature },
        dataType: "json",
        success: function (response) {
            console.log('dddd Response:', response);
            var data = response;
            var defi_transactional_details_tbl_body = $('#defi_transactional_details_tbl_body');
            var color = getRandomColor();
            data.forEach(function (item,key) {
            
                var details_row = `
                <tr style="background-color:${color}">
                    <td>${key === 0 ? 'From' : 'To'}</td>
                    <td>${item.token_symbol}</td>
                    <td>${item.tokenAmount}</td>
                    <td><img class="tbl-img" src="${item.tokenIcon}"></td>
                </tr>
                `;
                defi_transactional_details_tbl_body.append(details_row);
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
    return "Defi activities for " + signature;
}

