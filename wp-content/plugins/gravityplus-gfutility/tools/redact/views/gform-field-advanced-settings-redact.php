<?php
/**
 * Field Setting
 */
?>
<li class="gfp_redact_setting field_setting">
	<input type="checkbox" id="field_redact" onclick="SetFieldProperty('redact', this.checked);"/> <label
		for="field_redact"
		class="inline"><?php _e( 'Redact', 'gfp-utility' ) ?> <?php gform_tooltip( 'form_field_redact' ) ?></label>
</li>