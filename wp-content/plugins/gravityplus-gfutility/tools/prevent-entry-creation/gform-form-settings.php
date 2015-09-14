<?php
	/**
	 *
	 */
?>

<tr>
	<th>
		<?php _e( 'Prevent entry creation', 'gfp-gf-utility' ) ?>
	</th>
	<td>
		<input type="checkbox" id="gform_prevent_entry_creation" name="form_prevent_entry_creation"
					 value="1" <?php checked( rgar( $form, 'preventEntryCreation' ), '1', true ) ?> />
		<label
			for="gform_prevent_entry_creation"><?php _e( 'Prevent entries from being created for this form', 'gfp-gf-utility' ) ?></label>
	</td>
</tr>