<?php

/**
 * Redact field entry information
 *
 * Supports html, text, website, phone, number, date, time, textarea, select, multiselect, checkbox, radio, name, address,
 * email, post_title, post_content, post_category, post_tags, post_excerpt, post_image, post_custom_field, list
 *
 * The value will not be available for any functions that need to take place during the form processing after the entry is saved
 *
 * Generously sponsored by Joseph Sellers of woodworkingmasterclasses.com
 */
class GFPGFU_Redact {

	public function __construct () {
		$this->add_field_option();
		$this->redact();
	}

	public function add_field_option () {
		if ( is_admin() && 'gf_edit_forms' == RGForms::get( 'page' ) ) {
			add_action( 'gform_field_advanced_settings', array( $this, 'gform_field_advanced_settings' ), 10, 2 );
			add_filter( 'gform_tooltips', array( $this, 'gform_tooltips' ) );
			add_action( 'gform_editor_js', array( $this, 'gform_editor_js' ) );
			add_filter( 'gform_noconflict_scripts', array( $this, 'gform_noconflict_scripts' ) );
		}
	}

	public function gform_field_advanced_settings ( $position, $form_id ) {
		if ( 450 == $position ) {
			require_once( trailingslashit( GFP_GF_UTILITY_PATH ) . 'tools/redact/views/gform-field-advanced-settings-redact.php' );
		}
	}

	public static function gform_tooltips ( $tooltips ) {
		$redact_tooltips = array(
			'form_field_redact'     => '<h6>' . __( 'Redact', 'gfp-utility' ) . '</h6>' . __( 'Check this box if you do *not* want the information the user places in this field to be saved to the entry. The information will be unrecoverable and will not be available to any functions that use entry values to perform actions while the form is submitting.', 'gfp-utility' ),
		);

		return array_merge( $tooltips, $redact_tooltips );
	}

	public function gform_editor_js () {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'gfp_utility_redact', trailingslashit( GFP_GF_UTILITY_URL ) . "tools/redact/js/form-editor-redact-setting{$suffix}.js", array( 'gform_form_editor' ), GFP_GF_Utility::get_version() );
}

	public static function gform_noconflict_scripts ( $noconflict_scripts ) {
		$noconflict_scripts = array_merge( $noconflict_scripts, array( 'gfp_utility_redact' ) );

		return $noconflict_scripts;
	}

	public function redact () {
		add_action( 'gform_save_field_value', array( $this, 'gform_save_field_value' ), 100, 5 );
	}

	public function gform_save_field_value ( $value, $lead, $field, $form, $input_id ) {
		if ( ! empty( $field['redact'] ) ) {
			$value = '';
		}

		return $value;
	}
}

$gfpgfu_redact = new GFPGFU_Redact();