<?php
/**
 * Plugin Name: SingularityU Global Custom Shortcodes
 * Description: A collection of shortodes necessary to power Singularity University Global Community site.
 * Author: Marc Gratch
 * Version: 1.0
 * License: GPL
 */

define( 'SU_PATH', plugin_dir_path(__FILE__) );
define('SU_URL', plugin_dir_url(__FILE__));
function load_feedzy_edit(){
    if (function_exists('feedzy_rss')){
        require SU_PATH . '/community-stories.php';
    }
}
add_action('plugins_loaded','load_feedzy_edit');

function mk_ser_shortcode( $args, $content, $tag ) {

    $ret = get_search_form(false);

    return $ret;

}

add_shortcode( 'wpbsearch', 'mk_ser_shortcode' );

function mg_get_gravatar( $args, $content, $tag ) {

    $current_user_id = get_current_user_id();
    $profile_gravatar = get_avatar($current_user_id, 20);

    return $profile_gravatar;

}

add_shortcode( 'toolbar-gravatar', 'mg_get_gravatar' );

add_shortcode('the-year', 'year_shortcode');
function year_shortcode() {
    $year = date('Y');
    return $year;
}

add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
    add_post_type_support( 'page', 'excerpt' );
}

add_filter('wp_nav_menu_items','do_shortcode');

//add_filter('gravityview/approve_entries/hide-if-no-connections', '__return_true');

if( ! function_exists('tagpages_register_taxonomy') ){
    function tagpages_register_taxonomy()
    {
        register_taxonomy_for_object_type('post_tag', 'page');
    }
    add_action('admin_init', 'tagpages_register_taxonomy');
}

/**
 * Display all post_types on the tags archive page. This forces WordPress to
 * show tagged Pages together with tagged Posts. Thanks to Page Tagger by
 * Ramesh Nair: http://wordpress.org/extend/plugins/page-tagger/
 */
if( ! function_exists('tagpages_display_tagged_pages_archive') ){
    function tagpages_display_tagged_pages_archive(&$query)
    {
        if ( $query->is_archive && $query->is_tag ) {
            $q = &$query->query_vars;
            $q['post_type'] = 'any';
        }
    }
    add_action('pre_get_posts', 'tagpages_display_tagged_pages_archive');
}

function mg_rcp_hijack_login_url() {
    global $rcp_options;

    if( isset( $rcp_options['login_redirect'] ) ) {
        $login_url = get_the_permalink( $rcp_options['login_redirect'] );
    }
    return $login_url;
}


function mg_login_logout( $args, $content, $tag ) {

    if (is_user_logged_in()){
        $href = wp_logout_url( get_the_permalink() );
    }
    else{

        $redirect = get_the_permalink();
        $login_url = mg_rcp_hijack_login_url();

        if ( !empty($redirect) )
            $login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);

        $href = $login_url;

    }

    return $href;

}

//add_shortcode( 'login-logout-button', 'mg_login_logout' );

function add_login_redirect_filter( $vars ){
    $vars[] = "redirect_to";
    return $vars;
}
//add_filter( 'query_vars', 'add_login_redirect_filter' );

function block_dashboard() {
    $file = basename($_SERVER['PHP_SELF']);
    if (is_user_logged_in() && is_admin() && !current_user_can('edit_posts') && $file != 'admin-ajax.php'){
        wp_redirect( home_url() );
        exit();
    }
}

add_action('init', 'block_dashboard');

function filter_admin_bar_for_user() {

    if(is_user_logged_in()) {

        if(current_user_can('manage_options'))
            return;
    }

    add_filter('show_admin_bar', '__return_false');
}
add_action('init','filter_admin_bar_for_user');
