<?php

abstract class GV_DataTables_Extension {

	protected $settings_key = '';

	function __construct() {

		add_action( 'gravityview_datatables_scripts_styles', array( $this, 'add_scripts' ), 10, 3 );
		add_filter( 'gravityview_datatables_js_options', array( $this, 'add_config' ), 10, 3 );

		add_filter( 'gravityview_datatables_table_class', array( $this, 'add_html_class') );

		add_action( 'gravityview_datatables_settings_row', array( $this, 'settings_row' ) );

		add_filter( 'gravityview_dt_default_settings', array( $this, 'defaults') );

		add_filter( 'gravityview_tooltips', array( $this, 'tooltips' ) );
	}

	/**
	 * Add the `responsive` class to the table to enable the functionality
	 * @param string $classes Existing class attributes
	 * @return  string Possibly modified CSS class
	 */
	function add_html_class( $classes = '' ) {

		return $classes;
	}

	/**
	 * Register the tooltip with Gravity Forms
	 * @param  array  $tooltips Existing tooltips
	 * @return array           Modified tooltips
	 */
	function tooltips( $tooltips = array() ) {

		return $tooltips;
	}

	/**
	 * Set the default setting
	 * @param  array $settings DataTables settings
	 * @return array           Modified settings
	 */
	function defaults( $settings ) {

		return $settings;

	}

	/**
	 * Get the DataTables settings
	 * @param  int|null $view_id View ID. If empty, uses `$gravityview_view->ID`
	 * @return [type]          [description]
	 */
	function get_settings( $view_id = NULL ) {
		global $gravityview_view;

		if( is_null( $view_id ) ) {
			$view_id = $gravityview_view->view_id;
		}

		$settings = get_post_meta( $view_id, '_gravityview_datatables_settings', true );

		return $settings;
	}

	/**
	 * Get a specific DataTables setting
	 * @param  int|null $view_id View ID. If empty, uses `$gravityview_view->ID`
	 * @param string $key Setting key to fetch
	 * @param mxied $default Default value to return if setting doesn't exist
	 * @return mixed|false          Setting, if exists; returns `$default` parameter if not exists
	 */
	function get_setting( $view_id = NULL, $key = '', $default = false ) {

		$settings = $this->get_settings( $view_id );

		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
	}

	/**
	 * Is responsive
	 * @return boolean [description]
	 */
	function is_enabled( $view_id = NULL ) {

		$settings = $this->get_settings( $view_id );

		if( empty( $settings ) ) { return false; }

		foreach ( (array)$this->settings_key as $value ) {

			if( !empty( $settings[ $value ] ) ) {
				return true;
			}

		}

		return false;

	}

	/**
	 * Check if the view is a DataTable View
	 * @param  [type]  $view_data [description]
	 * @return boolean            [description]
	 */
	function is_datatables( $view_data ) {

		if( !empty( $view_data['template_id'] ) && 'datatables_table' === $view_data['template_id'] ) {
			return true;
		}

		return false;

	}


	/**
	 * Inject Scripts and Styles if needed
	 */
	function add_scripts( $dt_configs, $views, $post ) {

		$script = false;

		foreach ( $views as $key => $view_data ) {
			if( !$this->is_datatables( $view_data ) || !$this->is_enabled( $view_data['id'] ) ) { continue; }
			$script = true;
		}

		if( !$script ) { return; }

	}

	/**
	 * Add Javascript specific config data based on admin settings
	 */
	function add_config( $dt_config, $view_id, $post  ) {

		return $dt_config;
	}

}
