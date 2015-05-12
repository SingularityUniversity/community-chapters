<?php
/**
 * Plugin Name: WP JSON API Connect
 * Plugin URI: https://github.com/WebDevStudios/WDS-WP-JSON-API-Connect
 * Description: This plugin makes it easy to setup the oAuth connection
 * Author: WebDev Studios
 * Version: 1.0
 * License: GPL
 */

if ( ! class_exists( 'WDS_WP_JSON_API_Connect' ) ) {
    require_once('wds-wp-json-api-connect.php');
}

/**
 * Example WDS_WP_JSON_API_Connect usage
 */
function wp_json_api_connect_example_test()
{

    // Consumer credentials
    $consumer = array(
        'consumer_key' => 'ilssBpSIN4jh',
        'consumer_secret' => 'AcsQtVnvdnqYODgKZL4IC93ZtBYjR9wW5bIkhIUgZ0fposJM',
        'json_url' => 'http://data.singu.dev/wp-json/',
    );

    $api = new WDS_WP_JSON_API_Connect($consumer);

    $auth_url = $api->get_authorization_url();

    // Only returns URL if not yet authenticated
    if ($auth_url) {
        echo '<div id="message" class="updated">';
        echo '<p><a href="' . esc_url($auth_url) . '" class="button">Authorize Connection</a></p>';
        echo '</div>';

        // Do not proceed
        return;
    }


    $post_id_to_update = 1;
    //$updated_data = array( 'title' => 'WHat is this? A schedule?' );
    //$response = $api->auth_post_request( 'posts/'. $post_id_to_update, $updated_data );
    $response = $api->auth_request('posts/' . $post_id_to_update, $consumer, 'DELETE');

    if (is_wp_error($response)) {

        echo '<div id="message" class="error">';
        echo wpautop($response->get_error_message());
        echo '</div>';

    } else {

        echo '<div id="message" class="updated">';
        echo '<p><strong>Post DELETED!</strong></p>';
        //echo '<xmp>auth_post_request $response: ' . print_r($response, true) . '</xmp>';
        echo '</div>';
    }
}
add_action( 'all_admin_notices', 'wp_json_api_connect_example_test' );

?>