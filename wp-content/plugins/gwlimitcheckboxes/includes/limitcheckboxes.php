<?php

class GFLimitCheckboxes { 
    
    private $form_id;
    private $field_limits;

    private static $is_script_output;
    
    function __construct($form_id, $field_limits) {
        
        $this->form_id = $form_id;
        $this->field_limits = $this->set_field_limits($field_limits);
        
        add_filter( "gform_pre_render_$form_id", array( $this, 'pre_render' ), 10, 2 );
        add_filter( "gform_validation_$form_id", array( $this, 'validate' ) );
        
    }
    
    function pre_render( $form, $is_ajax ) {
        
        $script = '';
        $output_script = false;

        // $submisison is set if reloading from Save and Continue; also make sure $_POST is empty to ensure this is a true postback
        $is_postback = isset( GFFormDisplay::$submission[ $form['id'] ] ) && ! empty( $_POST );
        
        foreach($form['fields'] as $field) { 
            
            $field_id = $field['id'];
            $field_limits = $this->get_field_limits($field['id']);
            
            if( !$field_limits                                          // if field limits not provided for this field
                || RGFormsModel::get_input_type($field) != 'checkbox'   // or if this field is not a checkbox
                || ! $field_limits['max']        // or if 'max' is not set for this field
                )
                continue;
            
            $output_script = true;
            $max = $field_limits['max'];
            $selectors = array();
            
            foreach($field_limits['field'] as $checkbox_field) {
                $selectors[] = "#field_{$form['id']}_{$checkbox_field} .gfield_checkbox input:checkbox";
            }
            
            $script .= "jQuery(\"" . implode(', ', $selectors) . "\").checkboxLimit({$max});";
                
        }
        
        
        
        GFFormDisplay::add_init_script($form['id'], 'limit_checkboxes', GFFormDisplay::ON_PAGE_RENDER, $script);



        if( $output_script && ! self::$is_script_output && ( $is_ajax && ! $is_postback || ! $is_ajax ) ):
            ?>
        
            <script type="text/javascript">
            jQuery(document).ready(function($) {        
                $.fn.checkboxLimit = function(n) {
                    
                    var checkboxes = this;
                    
                    this.toggleDisable = function() {
                        
                        // if we have reached or exceeded the limit, disable all other checkboxes
                        if(this.filter(':checked').length >= n) {
                            var unchecked = this.not(':checked');
                            unchecked.prop('disabled', true);
                        } 
                        // if we are below the limit, make sure all checkboxes are available
                        else {
                            this.prop('disabled', false);
                        }
                        
                    }
                    
                    // when form is rendered, toggle disable
                    checkboxes.bind('gform_post_render', checkboxes.toggleDisable());
                    
                    // when checkbox is clicked, toggle disable
                    checkboxes.click(function(event) {
                        
                        checkboxes.toggleDisable();
                        
                        // if we are equal to or below the limit, the field should be checked
                        return checkboxes.filter(':checked').length <= n;
                    });
                    
                }
            });
            </script>
        
            <?php
            self::$is_script_output = true;
        endif;
        
        return $form;
    }
    
    function validate($validation_result) {
        
        $form = $validation_result['form'];
        $checkbox_counts = array();
        
        // loop through and get counts on all checkbox fields (just to keep things simple)
        foreach($form['fields'] as $field) {
            
            if( RGFormsModel::get_input_type($field) != 'checkbox' )
                continue;
            
            $field_id = $field['id'];
            $count = 0;
            
            foreach($_POST as $key => $value) {
                if(strpos($key, "input_{$field['id']}_") !== false)
                    $count++;
            }
            
            $checkbox_counts[$field_id] = $count;
            
        }
        
        // loop through again and actually validate
        foreach($form['fields'] as &$field) {
            
            if(!$this->should_field_be_validated($form, $field))
                continue;
            
            $field_id = $field['id'];
            $field_limits = $this->get_field_limits($field_id);
            
            $min = ! rgblank( $field_limits['min'] ) ? $field_limits['min'] : false;
            $max = ! rgblank( $field_limits['max'] ) ? $field_limits['max'] : false;
            
            $count = 0;
            foreach($field_limits['field'] as $checkbox_field) {
                $count += (int) rgar($checkbox_counts, $checkbox_field);
            }
            
            if( $min !== false && $count < $min) {
                $field['failed_validation'] = true;
                $field['validation_message'] = ! empty( $field['errorMessage'] ) ? $field['errorMessage'] : sprintf( _n('You must select at least %s item.', 'You must select at least %s items.', $min), $min );
                $validation_result['is_valid'] = false;
            }
            else if( $max !== false && $count > $max ) {
                $field['failed_validation'] = true;
                $field['validation_message'] = sprintf( _n('You may only select %s item.', 'You may only select %s items.', $max), $max );
                $validation_result['is_valid'] = false;
            }
            
        }
        
        $validation_result['form'] = $form;
        
        return $validation_result;
    }

    function should_field_be_validated( $form, $field ) {

        if( $field['pageNumber'] != GFFormDisplay::get_source_page( $form['id'] ) )
            return false;

        // if no limits provided for this field
        if( !$this->get_field_limits($field['id']) )
            return false;

        // or if this field is not a checkbox
        if( RGFormsModel::get_input_type($field) != 'checkbox' )
            return false;

        // or if this field is hidden
        if( RGFormsModel::is_field_hidden($form, $field, array()) )
            return false;

        return true;
    }
    
    function get_field_limits($field_id) {

	    // look for field specific limits first
	    $options = rgar( $this->field_limits, $field_id );
	    if( ! empty( $options ) ) {
		    return $options;
	    }

	    // look for spanned limits
        foreach( $this->field_limits as $key => $options ) {
            if( in_array( $field_id, $options['field'] ) ) {
	            return $options;
            }
        }
        
        return false;
    }
    
    function set_field_limits($field_limits) {
        
        foreach($field_limits as $key => &$options) {
            
            if(isset($options['field'])) {
                $ids = is_array($options['field']) ? $options['field'] : array($options['field']);
            } else {
                $ids = array($key);
            }
            
            $options['field'] = $ids;
            
        }
        
        return $field_limits;
    }
    
}