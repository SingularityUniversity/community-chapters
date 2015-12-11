<?php
/**
 * GravityView Extension -- DataTables -- Server side data
 *
 * @package   GravityView
 * @license   GPL2+
 * @author    Katz Web Services, Inc.
 * @link      http://gravityview.co
 * @copyright Copyright 2014, Katz Web Services, Inc.
 *
 * @since 1.0.4
 */


class GV_Extension_DataTables_Data {

	/**
	 * @var bool True: the file is currently being accessed directly by an AJAX request or otherwise. False: Normal WP load.
	 */
	static $is_direct_access = false;

	public function __construct() {

		$this->maybe_bootstrap_wp();

		$this->add_hooks();

		$this->trigger_ajax();
	}

	/**
	 * Trigger the AJAX response
	 * @since 1.3
	 */
	private function trigger_ajax() {

		if( self::$is_direct_access ) {
			$this->get_datatables_data();
		} else {
			// enable ajax
			add_action( 'wp_ajax_gv_datatables_data', array( $this, 'get_datatables_data' ) );
			add_action( 'wp_ajax_nopriv_gv_datatables_data', array( $this, 'get_datatables_data' ) );
		}
	}

	/**
	 * If this file is being accessed directly, then set up WP so we can handle the AJAX request
	 *
	 * @since 1.3
	 */
	function maybe_bootstrap_wp() {

		if ( ! defined( 'ABSPATH' ) ) {

			self::$is_direct_access = true;

			// Prevent loading of all WordPress files so we can manually load them
			define( 'SHORTINIT', true );

			$this->bootstrap_wp_for_direct_access();

			$this->bootstrap_setup_globals();

			$this->bootstrap_gv();
		}
	}

	/**
	 * Create required globals for minimal bootstrap
	 * @since 1.3
	 */
	function bootstrap_setup_globals() {
		global $wp_plugin_paths, $wp_rewrite, $wp_query, $wp, $wp_locale;

		$wp_plugin_paths = array();
		$wp_rewrite = new WP_Rewrite();
		$wp_query = new WP_Query();
		$wp = new WP();
		$wp_locale = new WP_Locale();
	}

	/**
	 * Include Gravity Forms, GravityView, and GravityView Extensions
	 * @since 1.3
	 */
	function bootstrap_gv() {

		$plugins = array(
			'gf' => '/gravityforms/gravityforms.php',
			'gv' => '/gravityview/gravityview.php',
			'gv_extension_advanced_filtering_load' => '/gravityview-advanced-filter/advanced-filter.php',
			'gv_extension_az_entry_filtering_load' => '/gravityview-az-filters/gravityview-az-filters.php',
			'gv_extension_featured_entries_load' => '/gravityview-featured-entries/featured-entries.php',
			'gv_ratings_reviews_loader' => '/gravityview-ratings-reviews/ratings-reviews.php',
			'gv_extension_sharing_load' => '/gravityview-sharing-seo/sharing-seo.php',
		);

		// Load Field files automatically
		foreach ( $plugins as $function_name => $plugin_file ) {
			if( file_exists( WP_PLUGIN_DIR . $plugin_file ) ) {

				require_once( WP_PLUGIN_DIR . $plugin_file );

				switch( $function_name ) {
					case 'gf':
						break;
					case 'gv':
						GravityView_Plugin::getInstance();
						GravityView_Post_Types::init_post_types();
						GravityView_Post_Types::init_rewrite();
						break;
					default:
						if( function_exists( $function_name ) ) {
							$function_name();
						}
				}

			}
		}
	}


	/**
	 * Include only the WP files needed
	 *
	 * This brilliant piece of code (cough) is from the dsIDXpress plugin.
	 *
	 * @since 1.3
	 */
	function bootstrap_wp_for_direct_access() {

		/** @define "$bootstrap_dir" "/srv/www/wordpress-default" */
		$bootstrap_dir = dirname( $_SERVER['SCRIPT_FILENAME'] );

		/** @define "$bootstrap_dir" "/srv/www" */
		$document_root = dirname( isset($_SERVER['APPL_PHYSICAL_PATH']) ? $_SERVER['APPL_PHYSICAL_PATH'] : $_SERVER['DOCUMENT_ROOT'] );

		// Loop through folders and keep looking up the directories until you find a directory that has wp-load.php
		while ( ! file_exists( $bootstrap_dir . '/wp-load.php' ) ) {

			$bootstrap_dir = dirname( $bootstrap_dir );

			// The base is no longer part of the path. We're in the weeds.
			// Let's fall back to default relative path to this file from wordpress
			// (wp-content/plugins/gravityview-datatables/includes/)
			if ( false === strpos( $bootstrap_dir, $document_root ) ) {
				$bootstrap_dir = "../../../../..";
				break;
			}
		}

		require( $bootstrap_dir . '/wp-load.php' );

		// Only load what we need.
		if(!function_exists('get_locale')) {
			require_once( ABSPATH . WPINC . '/locale.php' ); // is_rtl()
			require_once( ABSPATH . WPINC . '/class-wp-walker.php' ); // Needed for GF
			require_once( ABSPATH . WPINC . '/plugin.php' );
			require_once( ABSPATH . WPINC . '/load.php' );
			require_once( ABSPATH . WPINC . '/l10n.php' );
			require_once( ABSPATH . WPINC . '/general-template.php' );
			require_once( ABSPATH . WPINC . '/link-template.php' );
			require_once( ABSPATH . WPINC . '/formatting.php' );
			require_once( ABSPATH . WPINC . '/kses.php' );
			require_once( ABSPATH . WPINC . '/pluggable.php' );
			require_once( ABSPATH . WPINC . '/capabilities.php' );
			require_once( ABSPATH . WPINC . '/user.php');
			require_once( ABSPATH . WPINC . '/meta.php' );
			require_once( ABSPATH . WPINC . '/session.php' );
			require_once( ABSPATH . WPINC . '/shortcodes.php' );
			require_once( ABSPATH . WPINC . '/theme.php' );
			require_once( ABSPATH . WPINC . '/template.php' );
			require_once( ABSPATH . WPINC . '/widgets.php' );
			require_once( ABSPATH . WPINC . '/rewrite.php' );
			require_once( ABSPATH . WPINC . '/query.php' );
		}

		// Setup WP_PLUGIN_URL, WP_PLUGIN_DIR, etc.
		if( function_exists( 'wp_plugin_directory_constants' ) ) {
			wp_plugin_directory_constants();
		}

		// USER_COOKIE, AUTH_COOKIE, etc.
		if( function_exists( 'wp_cookie_constants' ) ) {
			wp_cookie_constants();
		}

		// TEMPLATEPATH, STYLESHEETPATH, etc.
		if( function_exists( 'wp_templating_constants' ) ) {
			wp_templating_constants();
		}
	}

	/**
	 * @since 1.3
	 */
	function add_hooks() {

		/**
		 * Don't fetch entries inline when rendering the View, since we're using AJAX requests to do that.
		 *
		 * Only affects GravityView 1.3+
		 *
		 * @since 1.1
		 */
		add_filter( 'gravityview_get_view_entries_table-dt', '__return_false' );

		// add template path
		add_filter( 'gravityview_template_paths', array( $this, 'add_template_path' ) );

		if( !is_admin() ) {
			// Enqueue scripts and styles
			add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts_and_styles' ) );
		}
	}

	/**
	 * Verify AJAX request nonce
	 */
	function check_ajax_nonce() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'gravityview_datatables_data' ) ) {
			do_action( 'gravityview_log_debug', '[DataTables] AJAX request - NONCE check failed' );
			exit( false );
		}
	}

	/**
	 * Get AJAX ready by defining AJAX constants and sending proper headers.
	 * @since  1.1
	 * @param  string  $content_type Type of content to be set in header.
	 * @param  boolean $cache        Do you want to cache the results?
	 */
	static function do_ajax_headers($content_type = 'text/plain', $cache = false) {

		// If it's already been defined, that means we don't need to do it again.
		if(defined('GV_AJAX_IS_SETUP')) { return; } else { define('GV_AJAX_IS_SETUP', true); }

		if(!defined('DOING_AJAX')) { define('DOING_AJAX', true); }

		// Fix errors thrown by NextGen Gallery
		if(!defined('NGG_SKIP_LOAD_SCRIPTS')) { define('NGG_SKIP_LOAD_SCRIPTS', TRUE); }

		// Prevent some theoretical random stuff from happening
		if(!defined('IFRAME_REQUEST')) { define('IFRAME_REQUEST', true); }

		// Get rid of previously set headers
		if( function_exists('header_remove') ) {
			header_remove();
		}

		// Setting the content type actually introduces 200ms of latency for some reason.
		// Give us the option to say no.
		if( !empty( $content_type )) {
			@header('Content-Type: '.$content_type.'; charset=UTF-8');
		}

		// @see send_nosniff_header()
		@header( 'X-Content-Type-Options: nosniff' );
		@header('Accept-Encoding: gzip, deflate');

		if($cache) {
			@header('Cache-Control: public, store, post-check=10000000, pre-check=100000;');
			@header('Expires: Thu, 15 Apr 2030 20:00:00 GMT;');
			@header('Vary: Accept-Encoding');
			@header("Last-Modified: " . gmdate("D, d M Y H:i:s", strtotime('-2 months')) . " GMT");

		} else {

			if ( ! defined( 'DONOTCACHEPAGE' ) ) {
				define( "DONOTCACHEPAGE", "true" );
			}

			@nocache_headers();
		}

		@header('HTTP/1.1 200 OK', true, 200);
		@header('X-Robots-Tag:noindex;');
	}

	/**
	 * main AJAX logic to retrieve DataTables data
	 */
	function get_datatables_data() {
		global $gravityview_view;

		if( empty( $_POST ) ) {
			return;
		}

		// Prevent error output
		ob_start();

		// Send correct headers
		$this->do_ajax_headers('application/javascript');

		$this->check_ajax_nonce();

		if( empty( $_POST['view_id'] ) ) {
			do_action( 'gravityview_log_debug', '[DataTables] AJAX request - View ID check failed');
			exit( false );
		}

		// Prevent emails from being encrypted
		add_filter('gravityview_email_prevent_encrypt', '__return_true' );

		do_action( 'gravityview_log_debug', '[DataTables] AJAX Request ($_POST)', $_POST );

		// include some frontend logic
		if( class_exists('GravityView_Plugin') && !class_exists('GravityView_View') ) {
			GravityView_Plugin::getInstance()->frontend_actions();
		}

		// Pass $_GET variables to the View functions, since they're relied on heavily
		// for searching and filtering, for example the A-Z widget
		$_GET = json_decode( stripslashes( $_POST['getData'] ), true );

		$view_id = intval( $_POST['view_id'] );

		// create the view object based on the post_id
		$GravityView_View_Data = GravityView_View_Data::getInstance( (int)$_POST['post_id'] );

		// get the view data
		$view_data = $GravityView_View_Data->get_view( $view_id );
		$view_data['atts']['id'] = $view_id;

		$atts = $view_data['atts'];

		// check for order/sorting
		if( isset( $_POST['order'][0]['column'] ) ) {
			$order_index = $_POST['order'][0]['column'];
			if( !empty( $_POST['columns'][ $order_index ]['name'] ) ) {
				// remove prefix 'gv_'
				$atts['sort_field'] = substr( $_POST['columns'][ $order_index ]['name'], 3 );
				$atts['sort_direction'] = !empty( $_POST['order'][0]['dir'] ) ? strtoupper( $_POST['order'][0]['dir'] ) : 'ASC';
			}
		}

		// check for search
		if( !empty( $_POST['search']['value'] ) ) {
			$atts['search_value'] = esc_attr( stripslashes_deep( $_POST['search']['value'] ) );
		}

		// Paging/offset
		$atts['page_size'] = isset( $_POST['length'] ) ? intval( $_POST['length'] ) : '';
		$atts['offset'] = isset( $_POST['start'] ) ? intval( $_POST['start'] ) : 0;

		// prepare to get entries
		$atts = wp_parse_args( $atts, GravityView_View_Data::get_default_args() );

		// check if someone requested the full filtered data (eg. TableTools print button)
		if( $atts['page_size'] == '-1' ) {
			$mode = 'all';
			$atts['page_size'] = PHP_INT_MAX;
		} else {
			// regular mode - get view entries
			$mode = 'page';
		}

		$view_data['atts'] = $atts;

		$gravityview_view = new GravityView_View( $view_data );


		if( class_exists( 'GravityView_Cache' ) ) {

			// We need to fetch the search criteria and pass it to the Cache so that the search is used when generating the cache transient key.
			$search_criteria = GravityView_frontend::get_search_criteria( $atts, $view_data['form_id'] );

			// make sure to allow late filter ( used on Advanced Filter extension )
			$criteria = apply_filters( 'gravityview_search_criteria', array( 'search_criteria' => $search_criteria ), $view_data['form_id'], $_POST['view_id'] );

			$atts['search_criteria'] = $criteria['search_criteria'];

			// Cache key should also depend on the View assigned fields
			$atts['directory_table-columns'] = !empty(  $view_data['fields']['directory_table-columns'] ) ? $view_data['fields']['directory_table-columns'] : array();

			// cache depends on user session
			$atts['user_session'] = $this->get_user_session();

			$Cache = new GravityView_Cache( $view_data['form_id'], $atts );

			if( $output = $Cache->get() ) {

				do_action( 'gravityview_log_debug', '[DataTables] Cached output found; using cache with key '.$Cache->get_key() );

				// update DRAW (mr DataTables is very sensitive!)
				$temp = json_decode( $output, true );
				$temp['draw'] = intval( $_POST['draw'] );
				$output = json_encode($temp);

				exit( $output );
			}
		}

		$view_entries = GravityView_frontend::get_view_entries( $atts, $view_data['form_id'] );

		$data = $this->get_output_data( $view_entries, $view_data );

		// wrap all
		$output = array(
			'draw' => intval( $_POST['draw'] ),
			'recordsTotal' => intval( $view_entries['count'] ),
			'recordsFiltered' => intval( $view_entries['count'] ),
			'data' => $data,
		);

		do_action( 'gravityview_log_debug', '[DataTables] Ajax request answer', $output );

		$json = json_encode( $output );

		if( class_exists( 'GravityView_Cache' ) ) {

			do_action( 'gravityview_log_debug', '[DataTables] Setting cache' );

			// Cache results
			$Cache->set( $json, 'datatables_output' );

		}

		// End prevent error output
		ob_end_clean();

		exit( $json );
	}

	/**
	 * Get the array of entry data
	 *
	 * @since 1.3
	 *
	 * @param array $view_entries Array of entries for the current search
	 * @param array $view_data Data information returned from GravityView_View_Data::get_view()
	 *
	 * @return array
	 */
	function get_output_data( $view_entries, $view_data ) {

		// build output data
		$data = array();
		if( $view_entries['count'] !== 0 ) {

			// For each entry
			foreach( $view_entries['entries'] as $entry ) {
				$temp = array();

				// Loop through each column and set the value of the column to the field value
				if( !empty(  $view_data['fields']['directory_table-columns'] ) ) {
					foreach( $view_data['fields']['directory_table-columns'] as $field_settings ) {
						$temp[] = GravityView_API::field_value( $entry, $field_settings );
					}
				}

				// Then add the item to the output dataset
				$data[] = $temp;
			}

		}

		return $data;
	}

	/**
	 * Get column width as a % from the field setting
	 *
	 * @since 1.3
	 *
	 * @param array $field_setting Array of settings for the field
	 *
	 * @return string|null If not empty, string in "{number}%" format. Otherwise, null.
	 */
	private function get_column_width( $field_setting ) {
		$width = NULL;

		if( !empty( $field_setting['width'] ) ) {
			$width = absint( $field_setting['width'] );
			$width = $width > 100 ? 100 : $width.'%';
		}

		return $width;
	}

	/**
	 * Calculates the user ID and Session Token to be used when calculating the Cache Key
	 * @return string
	 */
	function get_user_session() {

		if( !is_user_logged_in() ) {
			return '';
		}

		/**
		 * @see wp_get_session_token()
		 */
		$cookie = wp_parse_auth_cookie( '', 'logged_in' );
		$token = ! empty( $cookie['token'] ) ? $cookie['token'] : '';

		return get_current_user_id() . '_' . $token;

	}



	/**
	 * Include this extension templates path
	 * @param array $file_paths List of template paths ordered
	 */
	function add_template_path( $file_paths ) {

		// Index 100 is the default GravityView template path.
		$file_paths[101] = GV_DT_DIR . 'templates/';

		return $file_paths;
	}

	/**
	 * Generate the values for the page length menu
	 *
	 * @filter  gravityview_datatables_lengthmenu Modify the values shown in the page length menu. Key is the # of results, value is the label for the results.
	 * @param  array $view_data View data array from GravityView_View_Data
	 * @return array            2D array formatted for DataTables
	 */
	function get_length_menu( $view_data ) {

		// Create the array of values for the drop-down page menu
		$values = array(
			(int)$view_data['atts']['page_size'] => $view_data['atts']['page_size'],
			10 => 10,
			25 => 25,
			50 => 50
		);

		// no duplicate values
		$values = array_unique( $values );

		// Sort by the # of results per page
		ksort( $values );

		// Add the "All" option after the rest of them have been sorted by value
		$values[-1] = _x('All', 'Menu label to show all results in DataTables template.', 'gv-datatables' );

		$values = apply_filters( 'gravityview_datatables_lengthmenu', $values, $view_data );

		/**
		 * Prepare a 2D array for the dropdown.
		 * @link https://datatables.net/examples/advanced_init/length_menu.html
		 */
		$lengthMenu = array(
			array_keys( $values ),
			array_values( $values )
		);

		return $lengthMenu;
	}

	/**
	 * Get the data for the language parameter used by DataTables
	 *
	 * @since 1.2.3
	 * @return array Array of strings, as used by the DataTables extension's `language` setting
	 */
	function get_language() {

		$translations = $this->get_translations();

		$locale = get_locale();

		/**
		 * Change the locale used to fetch translations
		 * @since 1.2.3
		 */
		$locale = apply_filters( 'gravityview/datatables/config/locale', $locale, $translations );

		// If a translation exists
		if( isset( $translations[ $locale ] ) ) {


			ob_start();

			// Get the JSON file
			include GV_DT_DIR.'assets/js/translations/'.$translations[ $locale ].'.json';

			$json_string = ob_get_clean();

			// If it exists
			if( !empty( $json_string ) ) {

				// Parse it into an array
				$json_array = json_decode( $json_string, true );

				// If that worked, use the array as the base
				if( !empty( $json_array ) ) {
					$language = $json_array;
				}

			}

		} else {

			// Otherwise, load default English text with filters.
			$language = array(
				'processing' => apply_filters( 'gravityview_datatables_loading_text', __( 'Loading data&hellip;', 'gv-datatables' ) ),
				'emptyTable' => __( 'No entries match your request.', 'gv-datatables' )
			);

		}

		/**
		 * Override language settings
		 *
		 * @since 1.2.2
		 *
		 * {@link https://github.com/DataTables/Plugins/blob/master/i18n/English.lang}
		 */
		$language = apply_filters( 'gravityview/datatables/config/language', $language, $translations, $locale );

		return $language;
	}

	/**
	 * Match the DataTables translation file to the WordPress locale setting
	 *
	 * @since 1.2.3
	 *
	 * @return array Key is the WordPress locale string; Value is the name of the file in assets/js/translations/ without .json
	 */
	private function get_translations() {
		$translations = array(
			'af' => 'Afrikaans',
			'sq' => 'Albanian',
			'ar' => 'Arabic',
			'hy' => 'Armenian',
			'az'  => 'Azerbaijan',
			'bn_BD' => 'Bangla',
			'eu' => 'Basque',
			'bel' => 'Belarusian',
			'bg_BG' => 'Bulgarian',
			'ca' => 'Catalan',
			'zh_CN' => 'Chinese',
			'hr' => 'Croatian',
			'cs_CZ' => 'Czech',
			'da_DK' => 'Danish',
			'nl_NL' => 'Dutch',
			#'en_US' => 'English',
			'et' => 'Estonian',
			'fi' => 'Finnish',
			'fr_FR' => 'French',
			'gl_ES' => 'Galician',
			'ka_GE' => 'Georgian',
			'de_DE' => 'German',
			'el' => 'Greek',
			'gu' => 'Gujarati',
			'he_IL' => 'Hebrew',
			'hi_IN' => 'Hindi',
			'hu_HU' => 'Hungarian',
			'is_IS' => 'Icelandic',
			'id_ID' => 'Indonesian',
			'ga' => 'Irish',
			'it_IT' => 'Italian',
			'ja' => 'Japanese',
			'ko_KR' => 'Korean',
			'ky_KY' => 'Kyrgyz',
			'lv' => 'Latvian',
			'lt_LT' => 'Lithuanian',
			'mk_MK' => 'Macedonian',
			'ms_MY' => 'Malay',
			'mm' => 'Mongolian',
			'nb_NO' => 'Norwegian',
			'fa_IR' => 'Persian',
			'pl_PL' => 'Polish',
			'pt_BR' => 'Portuguese-Brasil',
			'pt_PT' => 'Portuguese',
			'ro_RO' => 'Romanian',
			'ru_RU' => 'Russian',
			'sr_RS' => 'Serbian',
			'si_LK' => 'Sinhala',
			'sk_SK' => 'Slovak',
			'sl_SI' => 'Slovenian',
			'es_ES' => 'Spanish',
			'sw' => 'Swahili',
			'sv_SE' => 'Swedish',
			'ta_IN' => 'Tamil',
			'th' => 'Thai',
			'tr_TR' => 'Turkish',
			'uk' => 'Ukranian',
			'ur' => 'Urdu',
			'uz_UZ' => 'Uzbek',
			'vi' => 'Vietnamese',
		);

		return $translations;
	}

	/**
	 * Get the url to the AJAX endpoint in use
	 *
	 * @return string If direct access, it's this file. otherwise, admin-ajax.php
	 */
	function get_ajax_url() {

		/**
		 * Do you want to bypass admin-ajax.php and access this file directly?
		 *
		 * This method is more prone to errors and may be less secure. Use carefully.
		 *
		 * @since 1.3
		 *
		 * @param boolean $use_direct_access Default false
		 */
		$use_direct_access = apply_filters( 'gravityview/datatables/direct-ajax', false );

		$ajax_url = $use_direct_access ? plugin_dir_url( __FILE__ ) . 'class-datatables-data.php' : admin_url( 'admin-ajax.php' );

		return $ajax_url;
	}

	/**
	 * Enqueue Scripts and Styles for DataTable View Type
	 *
	 * @filter gravityview_datatables_loading_text Modify the text shown while the DataTable is loading
	 */
	function add_scripts_and_styles() {
		//global $gravityview_view;

		$post = get_post();

		if( !is_a( $post, 'WP_Post' ) ) {
			do_action( 'gravityview_log_debug', 'GV_Extension_DataTables_Data[add_scripts_and_styles] not a post...leaving', $post );
			return;
		}

		// Get all the views on the current post/page/view
		$views = gravityview_get_current_views();
		do_action( 'gravityview_log_debug', 'GV_Extension_DataTables_Data[add_scripts_and_styles] Get current views. Found:', $views );

		// Check whether there's a view with a datatables template
		$is_datatables = false;
		$dt_configs = array();

		foreach ( $views as $key => $view_data ) {
			if( empty( $view_data['template_id'] ) || 'datatables_table' !== $view_data['template_id'] ) {
				continue;
			}

			$is_datatables = true;

			$ajax_settings = array(
				'action' => 'gv_datatables_data',
				'view_id' => $view_data['id'],
				'post_id' => $post->ID,
				'nonce' => wp_create_nonce( 'gravityview_datatables_data' ),
				'getData' => json_encode( (array)$_GET ), // Pass URL args to $_POST request
			);

			// Prepare DataTables init config
			$dt_config =  array(
				'processing' => true,
				'deferRender' => true, // Improves performance https://datatables.net/reference/option/deferRender
				'serverSide' => true,
				'retrieve'	 => true, // Only initialize each table once
				'stateSave'	 => true, // On refresh (and on single entry view, then clicking "go back"), save the page you were on.
				'stateDuration' => -1, // Only save the state for the session. Use to time in seconds (like the DAY_IN_SECONDS WordPress constant) if you want to modify.
				'lengthMenu'	=> $this->get_length_menu( $view_data ), // Dropdown pagination length menu
				'language' => $this->get_language(),
				'ajax' => array(
					'url' => $this->get_ajax_url(),
					'type' => 'POST',
					'data' => $ajax_settings
				),
			);

			// page size, if defined
			if( !empty( $view_data['atts']['page_size'] ) && is_numeric( $view_data['atts']['page_size'] ) ) {
				$dt_config['pageLength'] = intval( $view_data['atts']['page_size'] );
			}


			/**
			 * Set the columns to be displayed
			 *
			 * @link https://datatables.net/reference/option/columns
			 */
			$columns = array();
			if( !empty( $view_data['fields']['directory_table-columns'] ) ) {

				foreach( $view_data['fields']['directory_table-columns'] as $field ) {
					$columns[] = array(
						'name' => 'gv_' . $field['id'],
						'width' => $this->get_column_width( $field ),
						// TODO: Add searchable limits here to only make Search Bar fields searchable, if widget exists. https://datatables.net/reference/option/columns.searchable
					);
				}

				$dt_config['columns'] = $columns;
			}

			// set default order
			if( !empty( $view_data['atts']['sort_field'] ) ) {
				foreach ( $columns as $key => $column ) {
					if( $column['name'] === 'gv_'. $view_data['atts']['sort_field'] ) {
						$dir = !empty( $view_data['atts']['sort_direction'] ) ? $view_data['atts']['sort_direction'] : 'asc';
						$dt_config['order'] = array( array( $key, strtolower( $dir ) ) );
					}
				}
			}

			// filter init DataTables options
			$dt_config = apply_filters( 'gravityview_datatables_js_options', $dt_config, $view_data['id'], $post );

			$dt_configs[] = $dt_config;

		} // major FOREACH just to test


		// is the View requested a Datatables view ?
		if( empty( $is_datatables ) ) {
			do_action( 'gravityview_log_debug', 'GV_Extension_DataTables_Data[add_scripts_and_styles] DataTables view not requested.');
			return;
		}


		do_action('gravityview_log_debug', 'GV_Extension_DataTables_Data[add_scripts_and_styles] DataTables configuration: ', $dt_configs );

		$script_debug = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

		$path = plugins_url( 'assets/datatables/media/', GV_DT_FILE );

		/**
		 * Include DataTables core script
		 * Use your own DataTables core script by using the `gravityview_datatables_script_src` filter
		 */
		wp_enqueue_script( 'gv-datatables', apply_filters( 'gravityview_datatables_script_src', $path.'js/jquery.dataTables'.$script_debug.'.js' ), array( 'jquery' ), GV_Extension_DataTables::version, true );

		/**
		 * Use your own DataTables stylesheet by using the `gravityview_datatables_style_src` filter
		 */
		wp_enqueue_style( 'gravityview_style_datatables_table' );

		/**
		 * Register the featured entries script so that if active, the Featured Entries extension can use it.
		 */
		wp_register_style( 'gv-datatables-featured-entries', plugins_url( 'assets/css/featured-entries.css', GV_DT_FILE ), array('gravityview_style_datatables_table'), GV_Extension_DataTables::version, 'all' );

		// include DataTables custom script
		wp_enqueue_script( 'gv-datatables-cfg', plugins_url( 'assets/js/datatables-views'.$script_debug.'.js', GV_DT_FILE ), array( 'gv-datatables' ), GV_Extension_DataTables::version, true );

		wp_localize_script( 'gv-datatables-cfg', 'gvDTglobals', $dt_configs );

		// Extend datatables by including other scripts and styles
		do_action( 'gravityview_datatables_scripts_styles', $dt_configs, $views, $post );


	} // end add_scripts_and_styles

} // end class

new GV_Extension_DataTables_Data;
