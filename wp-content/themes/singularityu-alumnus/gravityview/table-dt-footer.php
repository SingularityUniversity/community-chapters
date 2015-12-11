<?php
$post_id = $this->view_id;
$gv_dt_settings = get_post_meta($post_id, '_gravityview_datatables_settings',true);
$is_reponsive = $gv_dt_settings['responsive'];

?>
	<tfoot>
		<tr>
			<?php
			if( !empty( $this->fields['directory_table-columns'] ) ) {
				if (isset($is_reponsive) && $is_reponsive == "1" || $is_reponsive == true){
					echo '<th class="spacer" scope="col"></th>';
				}
				echo '<th scope="col"><input type="checkbox" class="headercb"></th>';
				foreach( $this->fields['directory_table-columns'] as $field ) {
					echo '<th class="'. gv_class( $field ) .'" scope="col">' . esc_html( gv_label( $field ) ) . '</th>';
				}
				echo '<th class="comments_col" scope="col">Notes</th>';
			}
			?>
		</tr>
		<?php gravityview_footer(); ?>
	</tfoot>
</table>
</div>
<?php gravityview_after(); ?>
