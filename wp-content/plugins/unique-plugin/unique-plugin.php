<<<<<<< HEAD
<?php
/**
 * Plugin Name: SingularityU Global Custom Shortcodes
 * Description: A collection of shortodes necessary to power Singularity University Global Community site.
 * Author: Marc Gratch
 * Version: 1.0
 * License: GPL
 */

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

function get_feedzy_items( $items, $feedURL ){

    $feed_limit = get_option('singu_theme_settings_number_of_community_stories');

    $i = 0;
    $ii = 0;
    if ($feed_limit & 1){
        $force_div = true;
    }
    else {
        $force_div = false;
    }

    foreach ($items as $item){
        if ($ii < $feed_limit):
            $i++;

            //$title = $item->get_title();
            $title = '';
            $link  = $item->get_permalink();
            $image = feedzy_retrieve_image($item);

            $contentMeta = '';
            $contentMeta .= '<small>' . __( 'Posted', 'feedzy_rss_translate' ) . ' ';
            $contentMeta .= __( 'on', 'feedzy_rss_translate') . ' ' . date_i18n( get_option( 'date_format' ), $item->get_date( 'U' ) ) . ' ' . __( 'at', 'feedzy_rss_translate' ) . ' ' . date_i18n( get_option( 'time_format' ), $item->get_date( 'U' ) );
            $contentMeta .= '</small>';

            $description = $item->get_description();
            $description =  wp_trim_words($description, 20 );
            $description = strip_tags($description, "<p><a><strong><em>");

            $content = '';

            if ($i === 1){
                $content .= "<div class='row'>";
            }

            $content .= "<div class='col-sm-6 col-md-6 thumbnail'>";
                if (isset($image) && !empty($image)){
                    $content .= "<a class='rss_image' href='".esc_url($link)."' target='_blank' rel='nofollow'><img src='".esc_url($image)."' /></a>";
                }
                else{
                    if ( function_exists( 'jetpack_the_site_logo' ) ){
                        $content .= "<a class='rss_image' href='".esc_url($link)."' target='_blank' rel='nofollow'><img src='".jetpack_get_site_logo()."' /></a>";
                    }
                    else {
                        $the_image = get_template_directory_uri() . '/css/images/singlularityu-global-logo.png';
                        $content .= "<a class='rss_image' href='".esc_url($link)."' target='_blank' rel='nofollow'><img src='".$the_image."' /></a>";
                    }
                }

                $content .= "<div class='caption'>";
                    //$content .= "<a class='title_link' href='".esc_url($link)."' target='_blank' rel='nofollow'><h4 class='story-title'>{$title}</h4></a>";
                    $content .= "<span class='story-meta'>".$contentMeta."</span>";
                    $content .= "<span class='description'>".$description."</span>";
                $content .= "</div>";
            $content .= "</div>";


            if ($i === 2){
                $content .= "</div>";
                $i = 0;
            }

            echo $content;
        endif;
        $ii++;
    }
    if ($force_div === true){
        echo '</div>';
    }

    echo "<a class='more-link' href='".esc_url($feedURL)."' target='_blank' rel='nofollow'>". __('Read More Stories &raquo;','singularityu-alumnus') ."</a>";
    //return null;
}
add_filter('feedzy_feed_items', 'get_feedzy_items', 9,2);

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
=======
<?php
/**
 * Plugin Name: SingularityU Global Custom Shortcodes
 * Description: A collection of shortodes necessary to power Singularity University Global Community site.
 * Author: Marc Gratch
 * Version: 1.0
 * License: GPL
 */

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

function get_feedzy_items( $items, $feedURL ){

    $feed_limit = get_option('singu_theme_settings_number_of_community_stories');

    $i = 0;
    $ii = 0;
    if ($feed_limit & 1){
        $force_div = true;
    }
    else {
        $force_div = false;
    }

    foreach ($items as $item){
        if ($ii < $feed_limit):
            $i++;

            //$title = $item->get_title();
            $title = '';
            $link  = $item->get_permalink();
            $image = feedzy_retrieve_image($item);

            $contentMeta = '';
            $contentMeta .= '<small>' . __( 'Posted', 'feedzy_rss_translate' ) . ' ';
            $contentMeta .= __( 'on', 'feedzy_rss_translate') . ' ' . date_i18n( get_option( 'date_format' ), $item->get_date( 'U' ) ) . ' ' . __( 'at', 'feedzy_rss_translate' ) . ' ' . date_i18n( get_option( 'time_format' ), $item->get_date( 'U' ) );
            $contentMeta .= '</small>';

            $description = $item->get_description();
            $description =  wp_trim_words($description, 20 );
            $description = strip_tags($description, "<p><a><strong><em>");

            $content = '';

            if ($i === 1){
                $content .= "<div class='row'>";
            }

            $content .= "<div class='col-sm-6 col-md-6 thumbnail'>";
                if (isset($image) && !empty($image)){
                    $content .= "<a class='rss_image' href='".esc_url($link)."' target='_blank' rel='nofollow'><img src='".esc_url($image)."' /></a>";
                }
                else{
                    if ( function_exists( 'jetpack_the_site_logo' ) ){
                        $content .= "<a class='rss_image' href='".esc_url($link)."' target='_blank' rel='nofollow'><img src='".jetpack_get_site_logo()."' /></a>";
                    }
                    else {
                        $the_image = get_template_directory_uri() . '/css/images/singlularityu-global-logo.png';
                        $content .= "<a class='rss_image' href='".esc_url($link)."' target='_blank' rel='nofollow'><img src='".$the_image."' /></a>";
                    }
                }

                $content .= "<div class='caption'>";
                    //$content .= "<a class='title_link' href='".esc_url($link)."' target='_blank' rel='nofollow'><h4 class='story-title'>{$title}</h4></a>";
                    $content .= "<span class='story-meta'>".$contentMeta."</span>";
                    $content .= "<span class='description'>".$description."</span>";
                $content .= "</div>";
            $content .= "</div>";


            if ($i === 2){
                $content .= "</div>";
                $i = 0;
            }

            echo $content;
        endif;
        $ii++;
    }
    if ($force_div === true){
        echo '</div>';
    }

    echo "<a class='more-link' href='".esc_url($feedURL)."' target='_blank' rel='nofollow'>". __('Read More Stories &raquo;','singularityu-alumnus') ."</a>";
    //return null;
}
add_filter('feedzy_feed_items', 'get_feedzy_items', 9,2);

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
>>>>>>> b87696531a7083448558103909819c5947632864
