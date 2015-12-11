<?php

$post_id = $this->view_id;
$gv_dt_settings = get_post_meta($post_id, '_gravityview_datatables_settings',true);
$is_reponsive = $gv_dt_settings['responsive'];
gravityview_before(); ?>

<div id="gv-datatables-<?php echo $this->view_id; ?>" class="gv-datatables-container gv-container">
<table class="gv-datatables <?php echo esc_attr( apply_filters('gravityview_datatables_table_class', 'display dataTable') ); ?>">
	<thead>
		<?php gravityview_header(); ?>



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
	</thead>

	<?php
	?>
