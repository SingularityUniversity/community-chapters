<?php

class GWTermsofServiceField extends GWField {

	function __construct($args = array()) {

		$default_choice_label = __('I agree to the Terms of Service', 'gravityperks');

		$args = array_merge( $args, array(
			'type' => 'tos',
			'name' => __('Terms of Service', 'gravityperks'),
			'button' => array('group' => 'advanced_fields'),
			'field_settings' => array('label_setting', 'description_setting', 'admin_label_setting', 'size_setting', 'default_value_textarea_setting', 'error_message_setting', 'css_class_setting', 'visibility_setting', 'tos_setting', 'choices_setting' ),
			'default_field_values' => array(
				'inputType' => 'checkbox',
				'size' => 'large',
				'choices' => array(
					array(
						'text' => $default_choice_label,
						'value' => $default_choice_label
					)
				),
				'tosHTMLEnabled' => true
			)
		) );

		parent::__construct($args);

		add_action( 'gform_field_standard_settings_25', array( $this, 'advanced_settings_html' ) );
		add_action( 'gform_multilingual_field_keys',    array( $this, 'add_wpml_field_keys' ) );
		add_filter( 'gform_merge_tag_filter',           array( $this, 'include_terms_in_merge_tags' ), 10, 4 );

	}

	function input_html( $field, $value, $lead_id, $form_id ) {

		$perk = $this->perk;
		$terms_container = $this->get_terms_container_tag( $field );
		$disabled = $this->is_form_editor() || self::doing_ajax( 'rg_add_field' ) ? "disabled='disabled'" : '';

		if( $this->is_entry_detail() ) {

			$terms = '';

		} else {

			$classes = array( rgar( $field, 'cssClass' ), $field['size'] );
			$terms = $this->get_terms( $field );

			if( ! $this->is_html_enabled( $field ) ) {

				$tabindex = GFCommon::get_tabindex();
				$classes[] = 'textarea';
				$readonly = "readonly='readonly'";

				$terms = sprintf(
					"<div class='ginput_container'><textarea $disabled $readonly $tabindex id='gw_terms_%d' class='%s' rows='10' cols='50'>%s</$terms_container></div>",
					$field['id'], esc_attr( implode( ' ', $classes ) ), $terms
				);

			} else {

				if( ! is_admin() ) {
					//array_push( $classes, 'gfield', 'gsection', 'gf_scroll_text' );
				} else {
					$terms = sprintf( '<p>%s</p>', __( 'Terms will appear here when you preview the form.', 'gravityperks' ) );
					$terms .= '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis lorem sed justo aliquam maximus at ac lectus. Phasellus feugiat dictum tellus a consequat. Vivamus ultricies elementum auctor. Quisque ac molestie elit. Praesent quis pulvinar diam. Donec lorem tortor, vulputate et erat eu, convallis sodales est. Maecenas a urna sagittis, pretium urna ac, dignissim ligula. Morbi nec nibh ac ante interdum vulputate quis sed est. Vivamus nec sagittis nulla.</p><p>Donec aliquet vulputate hendrerit. Curabitur mattis, tellus quis iaculis consectetur, massa quam accumsan nibh, sit amet vestibulum ligula eros ut orci. Sed gravida accumsan quam, ac viverra lacus venenatis id. Phasellus laoreet id ante vitae luctus. Praesent sed leo lectus. Integer vel eros auctor, vestibulum mauris et, sollicitudin ligula. Praesent faucibus dictum purus, at vestibulum nibh sollicitudin ullamcorper. Mauris nibh est, placerat non odio eget, imperdiet tempus est. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aenean facilisis nibh at urna efficitur faucibus. Praesent lacinia erat ipsum, a pharetra tellus rhoncus ut. Suspendisse sollicitudin vel ligula ac egestas. Ut porttitor a enim id condimentum. In dolor nulla, consequat sit amet finibus vitae, fermentum sed sem.</p>';
				}

				$disable_auto_formatting = gf_apply_filters( 'gptos_disable_auto_formatting', array( $form_id, sprintf( "%s_%s", $form_id, $field['id'] ) ), false, $field, $form_id, $lead_id, $value );
				if( ! $disable_auto_formatting ) {
					$terms = wpautop( $terms );
				}

				$disable_css = gf_apply_filters( 'gptos_disable_css', $form_id, false, $field, $form_id, $lead_id, $value );
				if( ! $disable_css ) {
					ob_start();
					?>

						<style type="text/css">

							/* Frontend Styles */
							.gptos_terms_container { height: 11.250em; width: 97.5%; background-color: #fff; overflow: auto; border: 1px solid #ccc; }
								.gptos_terms_container.small { width: 25%; }
								.gptos_terms_container.medium { width: 47.5%; }
								.gptos_terms_container.large { /* default width */ }
								.left_label .gptos_terms_container,
								.right_label .gptos_terms_container { margin-left: 30% !important; width: auto !important; }
							.gform_wrapper .gptos_terms_container > div { margin: 1rem !important; }
							.gform_wrapper .gptos_terms_container ul,
							.gform_wrapper .gptos_terms_container ol { margin: 0 0 1rem 1.5rem !important; }
							.gform_wrapper .gptos_terms_container ul li { list-style: disc !important; }
							.gform_wrapper .gptos_terms_container ol li { list-style: decimal !important; }
							.gform_wrapper .gptos_terms_container p { margin: 0 0 1rem; }
							.gform_wrapper .gptos_terms_container *:last-child { margin-bottom: 0; }

							/* Admin Styles */
							#gform_fields .gptos_terms_container { background-color: rgba( 255, 255, 255, 0.5 ); border-color: rgba( 222, 222, 222, 0.75 ); }
							#gform_fields .gptos_terms_container > div { margin: 1rem !important; }
							#gform_fields .gptos_terms_container p { margin: 0 0 1rem; }
							#gform_fields .gptos_terms_container *:last-child { margin-bottom: 0; }

						</style>

					<?php
					$style_block = ob_get_clean();
				}

				array_push( $classes, 'gptos_terms_container', 'gwtos_terms_container' );

				$terms = sprintf(
					"<div id='gw_terms_%d' class='%s'>" .
						"<div class='gptos_the_terms'>%s</div>" .
					"</div>",
					$field['id'], esc_attr( implode( ' ', array_filter( $classes ) ) ), $terms
				);

				if( isset( $style_block ) ) {
					$terms .= $style_block;
				}

			}

		}

		if( ! is_admin() ) {
			$disabled = rgar( $field, $perk->key( 'require_scroll' ) ) && ! rgpost( sprintf( 'input_%s_1', $field['id'] ) ) ? "disabled='disabled'" : '';
		}

		$input = sprintf(
			"<div class='ginput_container' style='margin-top:12px;'><ul class='gfield_checkbox' id='%s'>%s</ul></div>",
			sprintf( 'input_%s_%s', $form_id, $field['id'] ),
			is_a( $field, 'GF_Field_Checkbox' ) ? $field->get_checkbox_choices( $value, $disabled, $form_id ) : GFCommon::get_checkbox_choices( $field, $value, $disabled )
		);

		return $terms . $input;
	}

	function editor_js(){
		$perk = $this->perk;
		?>

		<style type="text/css">
			.gwtos-hide { }
			.gwtos-hide .gf_insert_field_choice,
			.gwtos-hide .gf_delete_field_choice,
			.gwtos-hide #gfield_settings_choices_container + input.button,
			.gwtos-hide input#field_choice_values_enabled,
			.gwtos-hide input#field_choice_values_enabled + label,
			.gwtos-hide .field-choice-handle
			{
				display: none !important;
			}
		</style>

		<script type='text/javascript'>

			var gwtos = {
				modifyChoiceEditor: function() {
					jQuery( 'li.choices_setting' ).addClass( 'gwtos-hide' );
				},
				resetChoiceEditor: function() {
					jQuery( 'li.choices_setting' ).removeClass( 'gwtos-hide' );
				}
			};

			jQuery(document).bind("gform_load_field_settings", function(event, field, form) {

				if(field.type == '<?php echo $this->type; ?>') {
					gwtos.modifyChoiceEditor();
				} else {
					gwtos.resetChoiceEditor();
				}

				jQuery("#<?php echo $perk->key('require_scroll'); ?>").attr("checked", field['<?php echo $perk->key('require_scroll'); ?>'] == true);
				jQuery("#<?php echo $perk->key('terms'); ?>").val(field['<?php echo $perk->key('terms'); ?>']);

			});

			jQuery(document).ready(function($){

				$('#<?php echo $perk->key('terms'); ?>').keyup(function(){
					var field = GetSelectedField();
					$('#gw_terms_' + field['id']).val($(this).val());
				});

			});

		</script>

	<?php
	}

	function advanced_settings_html($form_id) {
		$perk = $this->perk;
		?>

		<li class="tos_setting field_setting">

			<label for="<?php echo $perk->key('terms'); ?>">
				<?php _e('The Terms', 'gravityperks'); ?>
				<?php gform_tooltip($perk->key('terms')); ?>
			</label>
			<textarea id="<?php echo $perk->key('terms'); ?>" onkeyup="SetFieldProperty('<?php echo $perk->key('terms'); ?>', this.value);" class="fieldwidth-3 fieldheight-2"></textarea>

			<div class="clear" style="margin:0 0 5px;"></div>

			<input type="checkbox" id="<?php echo $perk->key('require_scroll'); ?>" onclick="SetFieldProperty('<?php echo $perk->key('require_scroll'); ?>', this.checked);" />
			<label for="<?php echo $perk->key('require_scroll'); ?>" class="inline">
				<?php _e('Require Full Scroll', 'gravityperks'); ?>
				<?php gform_tooltip($perk->key('require_scroll')); ?>
			</label>

		</li>

	<?php
	}

	function add_init_script( $form, $is_ajax ) {

		$script = '';

		foreach( $form['fields'] as $field ) {

			if( ! $this->is_this_field_type( $field ) || ! rgar( $field, $this->perk->key( 'require_scroll' ) ) ) {
				continue;
			}

			$script .= 'gwTosScroll( jQuery( "#gw_terms_' . $field['id'] . '"), ' . $field['id'] . ', ' . $form['id'] . ' ); jQuery("#gw_terms_' . $field['id'] . '").scroll(function(){ gwTosScroll(this, ' . $field['id'] . ', ' . $form['id'] . ' ); });';

		}

		if( $script ) {

			// include generic function once
			$script = '' .
				'function gwTosScroll( elem, fieldId, formId ) {' .
					'var textarea        = jQuery( elem ),' .
			        '    isFullScroll    = textarea.scrollTop() + textarea.height() >= textarea[0].scrollHeight - 20;' .
			        'if( textarea.is( ":visible" ) && isFullScroll ) {' .
			        '    jQuery( "input#choice_" + formId + "_" + fieldId + "_1" ).prop( "disabled", false );' .
					'}' .
				'}' . $script;

			$script_event = $this->has_conditional_logic( $form ) ? GFFormDisplay::ON_CONDITIONAL_LOGIC : GFFormDisplay::ON_PAGE_RENDER;
			GFFormDisplay::add_init_script( $form['id'], $this->perk->key('init_script'), $script_event, $script );

		}

	}

	function field_default_values_js() {
		?>

		<script type="text/javascript">

			function SetDefaultValues_<?php echo $this->type; ?>(field) {

				var defaultFieldValues = <?php echo json_encode($this->default_field_values); ?>;
				for(var key in defaultFieldValues) {
					field[key] = defaultFieldValues[key];
				}

				field.inputs = [];
				var skip = 0;

				// populate 'inputs' property for checkboxes
				for(var i = 0; i < field.choices.length; i++) {

					// skipping ids that are multiple of ten to avoid conflicts with other fields (i.e. 5.1 and 5.10)
					if((i + 1 + skip) % 10 == 0)
						skip++;

					var field_number = field.id + '.' + (i + 1 + skip);
					field.inputs.push(new Input(field_number, field.choices[i].text));

				}

				return field;
			}

		</script>

	<?php
	}

	function get_terms_container_tag( $field ) {
		$form_id = rgar( $field, 'formId' ) ? rgar( $field, 'formId' ) : rgget( 'id' );
		return apply_filters( 'gptos_terms_container_tag_' . $form_id, apply_filters( 'gptos_terms_container_tag', 'textarea', $field ), $field );
	}

	function is_html_enabled( $field ) {
		return $this->get_terms_container_tag( $field ) == 'div' || rgar( $field, 'tosHTMLEnabled' );
	}

	function has_conditional_logic( $form ) {

		// has_conditional_logic is changed to public with GF 1.8.5.18, see if the version of GF running has the public version of this method
		$func = array( 'GFFormDisplay', 'has_conditional_logic' );
		if( is_callable( $func ) ) {
			$has_conditional_logic = call_user_func( $func, $form );
		} else {
			$has_conditional_logic = $this->has_conditional_logic_legwork( $form );
			$has_conditional_logic = apply_filters( 'gform_has_conditional_logic', $has_conditional_logic, $form );
		}

		return $has_conditional_logic;
	}

	function has_conditional_logic_legwork( $form ) {

		if( empty( $form ) )
			return false;

		if( isset( $form['button']['conditionalLogic'] ) )
			return true;

		if( is_array( rgar( $form, 'fields' ) ) ) {
			foreach( $form['fields'] as $field ) {
				if( ! empty( $field['conditionalLogic'] ) ) {
					return true;
				}
				else if( isset( $field['nextButton'] ) && ! empty( $field['nextButton']['conditionalLogic'] ) ) {
					return true;
				}
			}
		}

		return false;
	}

	function add_wpml_field_keys( $keys ) {

		array_push( $keys, $this->perk->key( 'terms' ) );

		return $keys;
	}

	function include_terms_in_merge_tags( $value, $merge_tag, $options, $field ) {

		if( $field['type'] != 'tos' ) {
			return $value;
		}

		$options = explode( ',', $options );
		if( ! in_array( 'include_terms', $options ) ) {
			return $value;
		}

		if( $merge_tag != 'all_fields' ) {
			$value = '<ul><li>' . $value . '</li></ul>';
		}

		$value = wpautop( $this->get_terms( $field ) ) . $value;

		return $value;

	}

	function get_terms( $field ) {

		$terms = do_shortcode( rgar( $field, $this->perk->key( 'terms' ) ) );

		return $terms;
	}

}