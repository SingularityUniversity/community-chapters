<?php

/**
 *
 */
class GFPGFU_Toggle_All_Fields_Required {

	public function __construct () {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_init () {
		if ( ( 'gf_edit_forms' == RGForms::get( 'page' ) ) && ( '' == rgget( 'view' ) ) && is_numeric( rgget( 'id' ) ) ) {
			add_filter( 'gform_toolbar_menu', array( $this, 'gform_toolbar_menu' ), 10, 2 );
			add_action( 'gform_editor_js', array( $this, 'gform_editor_js' ) );
			add_filter( 'gform_noconflict_scripts', array( $this, 'gform_noconflict_scripts' ) );
		}
	}

	public function gform_toolbar_menu ( $menu_items, $form_id ) {
		$menu_items[ 'utility' ] = array(
			'label'          => __( 'Utility', 'gfp-utility' ),
			'icon'           => '<i class="fa fa-wrench fa-lg"></i>',
			'title'          => __( 'Use a utility tool', 'gfp-utility' ),
			'url'            => 'javascript:void(0);',
			'menu_class'     => 'gf_form_toolbar_settings gf_form_toolbar_utilities',
			'link_class'     => '',
			'sub_menu_items' => $this->get_toolbar_sub_menu_items( $form_id ),
			'capabilities'   => 'gravityforms_edit_forms',
			'priority'       => 699
		);

		return $menu_items;
	}

	private function get_toolbar_sub_menu_items ( $form_id ) {

		$sub_menu_items = array();
		$tools          = $this->get_tools( $form_id );

		foreach ( $tools as $tool ) {

			$sub_menu_items[ ] = array(
				'url'          => $tool[ 'url' ],
				'label'        => $tool[ 'label' ],
				'menu_class'   => "gfp_gfutil_{$tool['name']}_li",
				'link_class'   => "gfp_gfutil_{$tool['name']}_link",
				'capabilities' => array( 'gravityforms_edit_forms' )
			);

		}

		return $sub_menu_items;
	}

	private function get_tools ( $form_id ) {
		$tools = array(
			'10' => array( 'name' => 'available-utilities', 'label' => __( 'Available Utilities:', 'gfp-utility' ), 'url' => 'javascript:void(0);' ),
			'11' => array( 'name' => 'toggle-all-fields-required', 'label' => __( 'Toggle All Fields Required', 'gfp-utility' ), 'url' => 'javascript:void(0);' )
		);

		$tools = apply_filters( 'gfp_gf_utility_menu', $tools, $form_id );
		ksort( $tools, SORT_NUMERIC );

		return $tools;
	}

	public function gform_editor_js () {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'gfp_gfutil_toggle_all_fields_required', trailingslashit( GFP_GF_UTILITY_URL ) . "tools/toggle-all-fields-required/toggle-all-fields-required{$suffix}.js", array( 'jquery' ) );
		wp_localize_script( 'gfp_gfutil_toggle_all_fields_required', 'gfp_gfutil_toggle_all_fields_required_vars', array( 'set' => __( 'All fields set as required', 'gfp-utility' ), 'unset' => __( 'All fields unset as required', 'gfp-utility' ) ) );
	}

	public static function gform_noconflict_scripts ( $noconflict_scripts ) {
		$noconflict_scripts = array_merge( $noconflict_scripts, array( 'gfp_gfutil_toggle_all_fields_required' ) );

		return $noconflict_scripts;
	}
}

$gfpgfu_toggle_all_fields_required = new GFPGFU_Toggle_All_Fields_Required();