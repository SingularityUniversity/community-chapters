<?php
/**
 * SingularityU Alumnus functions and definitions
 *
 * @package SingularityU Alumnus
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'singularityu_alumnus_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function singularityu_alumnus_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on SingularityU Alumnus, use a find and replace
	 * to change 'singularityu-alumnus' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'singularityu-alumnus', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'singularityu-alumnus' ),
        'header' => __( 'Header', 'singularityu-alumnus' ),
        'footer' => __( 'Footer', 'singularityu-alumnus' ),
    ) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'singularityu_alumnus_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // singularityu_alumnus_setup
add_action( 'after_setup_theme', 'singularityu_alumnus_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function singularityu_alumnus_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Site Announce Widget', 'singularityu-alumnus' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	) );
    register_sidebar( array(
        'name'          => __( 'Footer Call to Action', 'singularityu-alumnus' ),
        'id'            => 'sidebar-2',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Home Sidebar', 'singularityu-alumnus' ),
        'id'            => 'sidebar-3',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer 1', 'singularityu-alumnus' ),
        'id'            => 'sidebar-4',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'footer 2', 'singularityu-alumnus' ),
        'id'            => 'sidebar-5',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer 3', 'singularityu-alumnus' ),
        'id'            => 'sidebar-6',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer 4', 'singularityu-alumnus' ),
        'id'            => 'sidebar-7',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Very Bottom Footer', 'singularityu-alumnus' ),
        'id'            => 'sidebar-8',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'singularityu-alumnus' ),
        'id'            => 'sidebar-9',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Salon Sidebar', 'singularityu-alumnus' ),
        'id'            => 'sidebar-10',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Chapter Sidebar', 'singularityu-alumnus' ),
        'id'            => 'sidebar-11',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Summit Sidebar', 'singularityu-alumnus' ),
        'id'            => 'sidebar-12',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Event Sidebar', 'singularityu-alumnus' ),
        'id'            => 'sidebar-13',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'singularityu_alumnus_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function singularityu_alumnus_scripts() {
	wp_enqueue_style( 'singularityu-alumnus-style', get_stylesheet_uri() );

	wp_enqueue_script( 'singularityu-alumnus-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '20120206', true );

    wp_enqueue_script( 'custom-tiny-nav', get_template_directory_uri() . '/js/custom.tinynav.js', array('jquery'), '', true );

	wp_enqueue_script( 'singularityu-alumnus-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

    wp_enqueue_style( 'singularityu-alumnus-style-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css' );

    wp_enqueue_style( 'singularityu-alumnus-fonts', get_template_directory_uri() . '/fonts/stylesheet.css',array('singularityu-alumnus-style'),null,'all' );

    wp_enqueue_script( 'classie', get_template_directory_uri() . '/js/classie.js', array('modernizr'), '', true );

    wp_enqueue_script( 'menu-huger-overlay', get_template_directory_uri() . '/js/demo1.js', array('modernizr','classie'), '', true );

    wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.custom.js', array(), '', false );

    if (is_front_page()){
        wp_enqueue_script( 'community-stories-size', get_template_directory_uri() . '/js/community-stories-size.js', array('wp-mediaelement'), '', true );
    }

    //wp_enqueue_style( 'singularityu-alumnus-style-bootstrap-theme', get_template_directory_uri() . '/css/bootstrap-theme.min.css' );

    //wp_enqueue_style( 'singularityu-alumnus-style-custom', get_template_directory_uri() . '/css/styles.css' );

    //wp_enqueue_style( 'singularityu-alumnus-style-animate', get_template_directory_uri() . '/css/animate.css' );


    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'singularityu_alumnus_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Description Nav Walker Class
 */
require get_template_directory() . '/inc/nav-edits.php';

function maintenace_mode() {
    if ( !current_user_can( 'edit_themes' ) || !is_user_logged_in() ) {
        die('Maintenance.');
    }
}

add_action( 'after_setup_theme', 'baw_theme_setup' );
function baw_theme_setup() {
    add_image_size( 'menu-thumb', 360, 300, true ); // (cropped)
}
//add_action('get_header', 'maintenace_mode');


/*
// Append the Gravity Forms ID to the end of the filter so that the validation only runs on this form. In this example I'm doing it on Form #2
add_filter('gform_validation_12', 'custom_validation');
function custom_validation($validation_result) {
    $form = $validation_result["form"];
    //We need to loop through all the form fields to check if the value matches and of the default values
    foreach ($form['fields'] as $field) {

        var_dump($this);
        var_dump($field);

            //The field ID can be found by hovering over the field in the backend of WordPress
            if ($field["id"] == "13") {
                $field["failed_validation"] = true;
                $field["validation_message"] = "Test Error.";
            }
    }
    //Assign modified $form object back to the validation result
    $validation_result["form"] = $form;
    return $validation_result;
}

*/
add_filter( 'wp_feed_cache_transient_lifetime', 'bweb_feedzy_cache_duration', 10, 2 );
function bweb_feedzy_cache_duration( $feedCacheDuration, $feedURL ) {
    if( 'http://singularityuglobal.tumblr.com/tagged/highlight' == $feedURL )
        return 60*1; //5 minutes

    return $feedCacheDuration;
}


add_filter( 'gform_notification_events', 'gw_add_manual_notification_event' );
function gw_add_manual_notification_event( $events ) {
    $events['su_approval'] = __( 'Send Approval' );
    return $events;
}

//add_action('gform_post_update_entry', 'su_send_approval');
add_action('gravityview/approve_entries/approved','su_send_approval');
function su_send_approval($entry_id){


    $entry = RGFormsModel::get_lead($entry_id);
    $event = 'su_approval';
    $form_id = $entry['form_id'];
    $form = GFAPI::get_form( $form_id );
    $notification = GFCommon::get_notifications_to_send( $event, $form, $entry );

    $notifications_to_send[] = $notification[0]['id'];

    GFCommon::send_notifications( $notifications_to_send, $form, $entry, true, $event );
    //gw_activate_by_entry_id($entry_id);

}

function gw_activate_by_entry_id( $entry_id ) {

    if( ! class_exists( 'GFUser' ) ) {
        return new WP_Error( 'plugin_required', __( 'Gravity Forms User Registration plugin is required.' ) );
    }

    if( ! class_exists( 'GFUserSignups' ) ) {
        require_once( GFUser::get_base_path() . '/includes/signups.php' );
    }

    $activation_key = GFUserSignups::get_lead_activation_key( $entry_id );
    if( ! $activation_key ) {
        return new WP_Error( 'no_activation_key', __( 'This entry does not have an activation key.' ), compact( 'entry_id' ) );
    }

    $result = GFUserSignups::activate_signup( $activation_key );

    return $result;
}

/*
class GWUnrequire {

    var $_args = null;

    public function __construct( $args = array() ) {

        $this->_args = wp_parse_args( $args, array(
            'admins_only' => true,
            'require_query_param' => true
        ) );

        add_filter( 'gform_pre_validation', array( $this, 'unrequire_fields' ) );

    }

    function unrequire_fields( $form ) {

        if( $this->_args['admins_only'] && ! current_user_can( 'activate_plugins' ) )
            return $form;

        if( $this->_args['require_query_param'] && ! isset( $_GET['gwunrequire'] ) )
            return $form;

        foreach( $form['fields'] as &$field ) {
            $field['isRequired'] = false;
        }

        return $form;
    }

}

new GWUnrequire( array(
    'admins_only' => false,
    'require_query_param' => false
) );

*/

