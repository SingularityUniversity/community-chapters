<?php
/**
 * Add support for the Jetpack Sharing plugin
 *
 * @package GravityView_Sharing
 * @subpackage services
 */

class GravityView_Sharing_Jetpack extends GravityView_Sharing_Service {

	var $_service_name = 'Jetpack';
	var $_plugin_path = 'jetpack/jetpack.php';

	function is_plugin_active() {

		if( !class_exists('Jetpack' ) )  {
			return false;
		}

		if( !Jetpack::is_active() && !Jetpack::is_development_mode() ) {
			return false;
		}

		$jetpack_active_modules = (array)get_option('jetpack_active_modules');

		if ( in_array( 'sharing', $jetpack_active_modules ) || in_array( 'sharedaddy', $jetpack_active_modules ) ) {
		      return true;
		}

		return false;

	}

	/**
	 * @return string|void
	 */
	function output() {

		if( !$this->is_plugin_active() ) {
			return;
		}

		$this->add_permalink_filter();

		$output = sharing_display();

		$this->remove_permalink_filter();

		return $output;

	}

	function frontend_view_hooks() {

		// If the current page has Views
		if( gravityview_get_current_views() ) {

			add_filter( 'sharing_permalink', array( $this, 'filter_sharing_permalink' ), 10, 3 );
			add_filter( 'sharing_title', array( $this, 'modify_sharing_title' ), 10, 2 );

			$sharing_display_priority = 19;
			add_filter( 'the_content', array( $this, 'maybe_add_the_content_filter' ), ( $sharing_display_priority - 1 ) );
			add_filter( 'the_excerpt', array( $this, 'maybe_add_the_content_filter' ), ( $sharing_display_priority - 1 ) );

			add_filter( 'the_content', array( $this, 'remove_the_content_filter' ), ( $sharing_display_priority + 1 ) );
			add_filter( 'the_excerpt', array( $this, 'remove_the_content_filter' ), ( $sharing_display_priority + 1 ) );
		}

		parent::frontend_view_hooks();
	}

	function modify_sharing_title( $post_title, $post_id = 0 ) {
		$return = $post_title;

		if( class_exists('GravityView_frontend') ) {
			$return = gravityview_social_get_title( $post_title, $post_id );
		}

		return $return;
	}

	function maybe_add_the_content_filter( $the_content ) {
		global $post;

		$view_id = GravityView_View_Data::getInstance( )->maybe_get_view_id( $post );

		if( empty( $view_id ) ) {
			return $the_content;
		}

		$this->add_permalink_filter();

		return $the_content;
	}

	/**
	 * Remove the permalink filter
	 * @param string $the_content
	 *
	 * @return string
	 */
	function remove_the_content_filter( $the_content = '' ) {
		$this->remove_permalink_filter();
		return $the_content;
	}

	/**
	 * Modify the redirect URL used for the sharing screens
	 *
	 * @param $permalink
	 * @param $post_id
	 * @param $sharing_source Name of the sharing class triggering the request
	 *
	 * @return string
	 */
	function filter_sharing_permalink( $permalink, $post_id = 0, $sharing_source = '' ) {
		return gravityview_social_get_permalink( $permalink, $post_id );
	}

}

new GravityView_Sharing_Jetpack;