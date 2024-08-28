<?php
// Enqueue the JavaScript file
function enqueue_api_script() {
    wp_enqueue_script('api-script', get_template_directory_uri() . '/js/api-script.js', array('jquery'), null, true);
    wp_localize_script('api-script', 'apiData', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('api_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_api_script');

// Fetch data from an external API
function fetch_api_data() {
    $response = wp_remote_get('https://api.example.com/data');
    
    if (is_wp_error($response)) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return $data;
}

// Create a shortcode to display API data
function display_api_data_shortcode() {
    $data = fetch_api_data();

    if (empty($data)) {
        return '<p>No data found</p>';
    }

    ob_start();
    ?>
    <div class="api-data">
        <?php foreach ($data as $item) : ?>
            <div class="api-item">
                <h2><?php echo esc_html($item['title']); ?></h2>
                <p><?php echo esc_html($item['description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('api_data', 'display_api_data_shortcode');

// Handle AJAX request to fetch API data
function handle_fetch_api_data() {
    check_ajax_referer('api_nonce', 'nonce');

    $response = wp_remote_get('https://api.example.com/data');
    
    if (is_wp_error($response)) {
        wp_send_json_error('Failed to fetch data');
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    wp_send_json_success($data);
}
add_action('wp_ajax_fetch_api_data', 'handle_fetch_api_data');
add_action('wp_ajax_nopriv_fetch_api_data', 'handle_fetch_api_data');
?>
