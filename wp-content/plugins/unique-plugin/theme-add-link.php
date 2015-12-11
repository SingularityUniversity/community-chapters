<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 12/9/2015
 * Time: 10:26 AM
 */

function lwi_curPageURL() {
    $pageURL = 'http';
    if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function tinymce_title_js() {
    global $wp_admin_bar;
    if (strstr(lwi_curPageURL(), 'themes.php?page=pods-settings-singu_theme_settings')) {
        //$settings = array( 'media_buttons' => false );
        echo '<div id="contain_none" style="display:none;"><div id="none"></div></div>';
        wp_editor($content=null, 'none', $settings=null);
        ?>
        <style>
            #wp-none-wrap { display: none !important; }
            i.mce-ico.mce-i-link.rs-custom-link-btn { position: absolute; bottom: -10px; left: 5px; }
            label.rs-label { position: relative; }
            a#update-slider { margin: 3px 0px 0px 0px; float: right; }
            div#slider-actions { overflow: hidden; }
            #update-progress { right: 110px; display: block; position: absolute; bottom: 8px; font-size: 12px; color: green; }
        </style>
        <?php
        wp_register_script( 'admin_functions', SU_URL .'assets/js/admin_functions.js',array('jquery'),'1.0',true);
        wp_enqueue_script( 'admin_functions' );
    }
}
add_action( 'admin_enqueue_scripts', 'tinymce_title_js');
