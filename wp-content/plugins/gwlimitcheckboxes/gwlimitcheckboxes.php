<?php
/**
* Plugin Name: GP Limit Checkboxes
* Description: Limit how many checkboxes can be checked.
* Plugin URI: http://gravitywiz.com/
* Version: 1.1.3
* Author: David Smith
* Author URI: http://gravitywiz.com/
* License: GPL2
* Perk: True
*/

/**
* Saftey net for individual perks that are active when core Gravity Perks plugin is inactive.
*/
$gw_perk_file = __FILE__;
if( !require_once(dirname($gw_perk_file) . '/safetynet.php') )
    return;

class GWLimitCheckboxes extends GWPerk {

	public $version = '1.1.3';

    protected $min_wp_version            = '1.7';
    protected $min_gravity_perks_version = '1.2.8.12';
    
	public function init() {
        
		$this->enqueue_field_settings();

		$this->add_tooltip("{$this->key('enable')}", '<h6>'. __('Limit Checkboxes Amount', 'gravityperks') .'</h6>' . __('Limit how many checkboxes can be checked for this field.', 'gravityperks'));
		$this->add_tooltip("{$this->key('span_multiple_fields')}", '<h6>'. __('Span Multiple Fields', 'gravityperks') .'</h6>' . __('Apply this limit as an accumlative limit across multiple fields. For example, spanning a limit of "2" across two fields would allow you to select two checkboxes in either field or one checkbox in each field.', 'gravityperks'));

		add_action( 'wp_print_scripts', array( $this, 'enqueue_admin_scripts' ) );

		add_filter( 'gform_pre_render', array( $this, 'init_limit_checkbox_class' ), 1 );
		add_filter( 'gform_validation', array( $this, 'init_limit_checkbox_class' ), 1 );

		add_filter( 'gform_enqueue_scripts', array( $this, 'enqueue_form_scripts' ) );

	}

	public function init_limit_checkbox_class( $form_or_validation ) {
		
		$fields_array = array();

		if( rgar( $form_or_validation, 'form' ) ) {
            $form = $form_or_validation['form'];
        } else {
            $form = $form_or_validation;
        }
		
		foreach ($form['fields'] as $field) {
            
			if( !$this->is_limit_checkbox_field( $field ) )
				continue;

			$minimum_limit = rgar( $field, $this->key('minimum_limit') );
			$maximum_limit = rgar( $field, $this->key('maximum_limit') );
			$span_limit_fields = rgar( $field, $this->key('span_limit_fields') );

			if( gwar( $field, $this->key('span_multiple_fields') ) && ! empty( $span_limit_fields ) ) {
				array_push( $span_limit_fields, $field['id'] );
				$fields_array[$field['id']] = array( 'field' => $span_limit_fields, 'min' => $minimum_limit, 'max' => $maximum_limit );
			} else {
				$fields_array[$field['id']] = array( 'min' => $minimum_limit, 'max' => $maximum_limit );
			}
            
		}

        if( !empty( $fields_array ) ) {
            require_once( plugin_dir_path(__FILE__) . 'includes/limitcheckboxes.php' );
            new GFLimitCheckboxes( $form['id'], $fields_array );
        }
        
		

		return $form_or_validation;
	}

    /**
    * Check if this field is a checkbox field AND that the limit checkbox setting is enabled.
    * 
    */
    public function is_limit_checkbox_field( $field ) {
        return GFFormsModel::get_input_type( $field ) == 'checkbox' && rgar( $field, $this->key('enable') );
    }
    
	public function field_settings_ui(){
		?>
			<style type="text/css">
				.gwp-option { margin: 0 0 10px; }
				#gws_field_tab .gwp-option label { margin: 0 !important; }
				#gws_field_tab .gwp-option input[type="text"] { margin-right: 100px; }
				.gws-child-settings { border-left: 2px solid #eee; padding: 15px; margin-left: 5px; margin-top: 5px; }
				/*.gwp-option .asmSelect { max-width: 100%; }*/
				/*.gwp-option .asmList { margin: 10px 0 0 !important; }*/
				/*.gwp-option li.asmListItem {*/
					/*background-color: #f7f7f7;*/
					/*border: 1px solid #eee !important;*/
					/*padding: 6px 10px !important;*/
					/*border-radius: 4px;*/
					/*margin-left: 0 !important;*/
				/*}*/
				/*.gwp-option a.asmListItemRemove {*/
					/*float: right;*/
				/*}*/
			</style>

			<li class="<?php echo $this->key('setting'); ?> gwp_field_setting field_setting">
				<input type="checkbox" id="<?php echo $this->key('enable'); ?>" onclick="gperk.toggleSettings('<?php echo $this->key('enable'); ?>', '<?php echo $this->key('settings'); ?>');" value="1">
				<label for="<?php echo $this->key('enable'); ?>" class="inline">
					<?php _e('Limit how many checkboxes can be checked', 'gravityperks'); ?>
					<?php gform_tooltip($this->key('enable')); ?>
				</label>

				<div id="<?php echo $this->key('settings'); ?>" class="gws-child-settings" style="display: none;">
					<div class="gwp-option">
                        <div>
						    <label for="<?php echo $this->key('minimum_limit'); ?>" class="inline" style="width:100px;">
							    <?php _e('Minimum Limit: ', 'gravityperks'); ?>
						    </label>
						    <input type="text" id="<?php echo $this->key('minimum_limit'); ?>" onchange="SetFieldProperty('<?php echo $this->key('minimum_limit'); ?>', jQuery(this).val());" style="width:60px;">
                        </div>
                        <div>
						    <label for="<?php echo $this->key('maximum_limit'); ?>" class="inline" style="width:100px;">
							    <?php _e('Maximum Limit: ', 'gravityperks'); ?>
						    </label>
						    <input type="text" id="<?php echo $this->key('maximum_limit'); ?>" onchange="SetFieldProperty('<?php echo $this->key('maximum_limit'); ?>', jQuery(this).val());" style="width:60px;">
                        </div>
					</div>

					<div class="gwp-option">
						<input type="checkbox" id="<?php echo $this->key('span_multiple_fields'); ?>" onclick="gperk.toggleSettings('<?php echo $this->key('span_multiple_fields'); ?>', '<?php echo $this->key('multiple_fields_settings'); ?>')" value="1">
						<label for="<?php echo $this->key('span_multiple_fields'); ?>" class="inline">
							<?php _e('Span Limit Across Multiple Checkbox Fields', 'gravityperks'); ?>
							<?php gform_tooltip($this->key('span_multiple_fields')); ?>
						</label>

						<div id="<?php echo $this->key('multiple_fields_settings'); ?>" class="gws-child-settings" style="display: none;"></div>
					</div>
				</div>
			</li>
		<?php
	}

	public function field_settings_js(){
		?>
			<script>
				(function($) {
					$(document).bind('gform_load_field_settings', function(e, field, form) {
						// We only want to allow checkbox variant field types.
						if (field.type != 'checkbox' && field.inputType != 'checkbox') {
							$('.<?php echo $this->key('setting'); ?>').hide();
							return;
						} else {
							$('.<?php echo $this->key('setting'); ?>').show();
						}

						var checkboxFields = getCheckboxFields(form, field);

						gperk.toggleSettings("<?php echo $this->key('enable'); ?>", "<?php echo $this->key('settings'); ?>", field["<?php echo $this->key('enable'); ?>"]);
						gperk.toggleSettings("<?php echo $this->key('span_multiple_fields') ?>", "<?php echo $this->key('multiple_fields_settings'); ?>", field["<?php echo $this->key('span_multiple_fields'); ?>"]);

						$("#<?php echo $this->key('minimum_limit'); ?>").val(field["<?php echo $this->key('minimum_limit'); ?>"]);
						$("#<?php echo $this->key('maximum_limit'); ?>").val(field["<?php echo $this->key('maximum_limit'); ?>"]);
                        
						setSelectOptions(checkboxFields, field);

						if ($("#field_"+field.id+" .asmContainer").length > 0)
							return;

						// All setTimeouts are set as I was hitting an issue where the fieldSettings dom object
						// was not added before the select fields were being dynamically set.
						setTimeout(function() {
							jQuery("#field_"+field.id+" .<?php echo $this->slug; ?>_"+field.id).asmSelect({
								addItemTarget: 'bottom',
								animate: true,
								highlight: true,
								sortable: true
							});
						}, 10);
					});

					// Loop through the currently set fields and grab all field's that are some form of checkbox variant,
					// unless the current field is being viewed.
					function getCheckboxFields(form, currentField) {
						var checkboxFields = new Object();

						$.each(form.fields, function(fieldIndex, field) {
							if (currentField.id != field.id && (field.type == 'checkbox' || field.inputType == 'checkbox'))
								checkboxFields[field.id] = field.label;
						});

						return checkboxFields;
					}

					function setSelectOptions(checkboxFields, field) {
						var option, html;
                        
                        var selectContainer = $('#<?php echo $this->key('multiple_fields_settings'); ?>');
                        
                        html = '<select class="<?php echo $this->slug; ?>_'+field.id+'" id="<?php echo $this->key('span_limit_fields'); ?>" multiple="multiple" title="Select a Field" onchange="SetFieldProperty(\'<?php echo $this->key('span_limit_fields') ?>\', jQuery(this).val());">';

						$.each(checkboxFields, function(fieldId, fieldLabel) {
                            
                            var spanLimitFields = field['<?php echo $this->key('span_limit_fields'); ?>'],
                            	selected = isFieldSelected( fieldId, spanLimitFields ) ? 'selected="selected"' : '';
                            
                            // add default label for unlabeled fields
                            if( ! fieldLabel )
								fieldLabel = '(unlabeled) ID: ' + fieldId;
                            	
                            html += "<option id='field-id-" + fieldId + "' value='" + fieldId + "'" + selected + ">" + truncateRuleText( fieldLabel, 40 ) + "</option>";
							
						});
                        
                        html += '</select>';
                        
                        selectContainer.html( html );
                        $(".<?php echo $this->slug; ?>_" + field.id).val(field["<?php echo $this->key('span_limit_fields'); ?>"]);
					}
                    
                    function isFieldSelected( fieldId, spanLimitFields ) {
                        var field = GetFieldById( fieldId );
                        return $.inArray( fieldId, spanLimitFields ) != -1;
                    }

					function truncateRuleText( text, length ) {

						if( ! text || text.length <= length ) {
							return text;
						}

						var halfLength = length / 2;

						return text.substr( 0, halfLength ) + '...' + text.substr( text.length -( halfLength - 1 ), halfLength );

					}
                    
				})(jQuery);
			</script>
		<?php
	}

	public function enqueue_admin_scripts(){
        
        if( !is_admin() || rgget('page') != 'gf_edit_forms' || !rgget('id') || rgget('view') )
            return;
        
		wp_enqueue_style( 'asmSelectCss', $this->get_base_url() . '/css/jquery.asmselect.css');
		$this->register_noconflict_styles( 'asmSelectCss' );

		wp_enqueue_script( 'asmSelect', $this->get_base_url() . '/js/jquery.asmselect.js' );
		$this->register_noconflict_script( 'asmSelect' );

	}

	public function enqueue_form_scripts( $form ) {
		if( $this->is_applicable_form( $form ) ) {
			wp_enqueue_script( 'gp-limit-checkboxes', $this->get_base_url() . '/js/gp-limit-checkboxes.js', array( 'jquery' ), $this->version );
		}
	}

	public function is_applicable_form( $form ) {
		foreach( $form['fields'] as $field ) {
			if( $this->is_limit_checkbox_field( $field ) ) {
				return true;
			}
		}
		return false;
	}
    public function documentation() {
        return array( 
            'type' => 'url', 
            'value' => 'http://gravitywiz.com/documentation/gp-limit-checkboxes/' 
            );
    }
    
}
