jQuery(document).ready(function($) {
    $.ajax({
        url: apiData.ajax_url,
        method: 'POST',
        data: {
            action: 'fetch_api_data',
            nonce: apiData.nonce
        },
        success: function(response) {
            if (response.success) {
                let data = response.data;
                let output = '';
                $.each(data, function(index, item) {
                    output += '<div class="api-item">';
                    output += '<h2>' + item.title + '</h2>';
                    output += '<p>' + item.description + '</p>';
                    output += '</div>';
                });
                $('.api-data').html(output);
            } else {
                $('.api-data').html('<p>Error loading data.</p>');
            }
        }
    });
});
