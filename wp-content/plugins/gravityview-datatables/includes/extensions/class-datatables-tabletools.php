<?php

class GV_Extension_DataTables_TableTools extends GV_DataTables_Extension {

	protected $settings_key = 'tabletools';

	function defaults( $settings ) {

		$settings['tabletools'] = true;
		$settings['tt_buttons'] = array(
			'copy' => 1,
			'csv' => 1,
			'xls' => 0,
			'pdf' => 0,
			'print' => 1
		);

		return $settings;
	}

	/**
	 * Register the tooltip with Gravity Forms
	 * @param  array  $tooltips Existing tooltips
	 * @return array           Modified tooltips
	 */
	function tooltips( $tooltips = array() ) {

		$tooltips['gv_datatables_tabletools'] = array(
			'title' => __('TableTools', 'gv-datatables'),
			'value' => __('TableTools adds buttons that allow users to print or export the current results.', 'gv-datatables')
		);

		return $tooltips;
	}

	function settings_row( $ds ) {

		$tt_buttons_labels = GV_Extension_DataTables_TableTools::tabletools_button_labels();

		?>
		<table class="form-table">
			<caption>TableTools</caption>
			<tr valign="top">
				<td colspan="2">
					<?php
						echo GravityView_Render_Settings::render_field_option( 'datatables_settings[tabletools]', array(
								'label' => __( 'Enable TableTools', 'gv-datatables' ),
								'type' => 'checkbox',
								'value' => 1,
								'tooltip' => 'gv_datatables_tabletools',
							), $ds['tabletools'] );
					?>
				</td>
			</tr>
			<tr valign="top" id="gv_dt_tt_buttons">
				<td colspan="2">

				<h3><?php esc_html_e( 'Display Buttons', 'gv-datatables' ); ?></h3>
				</p>
					<ul class="ul-square">
				<?php
					foreach( $ds['tt_buttons'] as $b_key => $b_value ) {
						if( empty( $tt_buttons_labels[ $b_key ] )) { continue; }

						echo '<li>'.GravityView_Render_Settings::render_field_option(
							'datatables_settings[tt_buttons]['. $b_key .']',
							array(
								'label' => $tt_buttons_labels[ $b_key ],
								'type' => 'checkbox',
								'value' => 1
							),
							$ds['tt_buttons'][ $b_key ]
						).'</li>';
					}
					?>
					</ul>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Returns the TableTools buttons' labels
	 * @return array
	 */
	public static function tabletools_button_labels() {
		return array(
			'select_all' => __( 'Select All', 'gv-datatables' ),
			'select_none' => __( 'Deselect All', 'gv-datatables' ),
			'copy' => __( 'Copy', 'gv-datatables' ),
			'csv' => 'CSV',
			'xls' => 'XLS',
			'pdf' => 'PDF',
			'print' => __( 'Print', 'gv-datatables' )
		);
	}

	/** TableTools */

	/**
	 * Inject TableTools Scripts and Styles if needed
	 */
	function add_scripts( $dt_configs, $views, $post ) {

		$script = false;

		foreach ( $views as $key => $view_data ) {
			if( !$this->is_datatables( $view_data ) || !$this->is_enabled( $view_data['id'] ) ) { continue; }
			$script = true;
		}

		if( !$script ) { return; }

		$path = plugins_url( 'assets/datatables-tabletools/', GV_DT_FILE );

		/**
		 * Include TableTools core script (DT plugin)
		 * Use your own DataTables core script by using the `gravityview_datatables_script_src` filter
		 */
		wp_enqueue_script( 'gv-dt-tabletools', apply_filters( 'gravityview_dt_tabletools_script_src', $path.'js/dataTables.tableTools.js' ), array( 'jquery', 'gv-datatables' ), GV_Extension_DataTables::version, true );

		/**
		 * Use your own TableTools stylesheet by using the `gravityview_dt_tabletools_style_src` filter
		 */
		wp_enqueue_style( 'gv-dt_tabletools_style', apply_filters( 'gravityview_dt_tabletools_style_src', $path.'css/dataTables.tableTools.css' ), array('gravityview_style_datatables_table'), GV_Extension_DataTables::version, 'all' );

	}


	/**
	 * TableTools add specific config data based on admin settings
	 */
	function add_config( $dt_config, $view_id, $post  ) {

		if( !$this->is_enabled( $view_id ) ) { return $dt_config; }

		// init TableTools
		$dt_config['dom'] = empty( $dt_config['dom'] ) ? 'T<"clear">lfrtip' : 'T<"clear">'. $dt_config['dom'];
		$dt_config['tableTools']['sSwfPath'] = plugins_url( 'assets/datatables-tabletools/swf/copy_csv_xls_pdf.swf', GV_DT_FILE );

		// row selection mode option
		//$dt_config['tableTools']['sRowSelect'] = empty( $settings['tt_row_selection'] ) ? 'none' : $settings['tt_row_selection'];
		$dt_config['tableTools']['sRowSelect'] = apply_filters( 'gravityview_dt_tabletools_rowselect', 'none', $dt_config, $view_id, $post );

		// View DataTables settings
		$buttons = $this->get_setting( $view_id, 'tt_buttons');

		// display buttons
		if( !empty( $buttons ) && is_array( $buttons ) ) {

			//fetch buttons' labels
			$button_labels = self::tabletools_button_labels();

			//calculate who's in
			$buttons = array_keys( $buttons, 1 );

			if( !empty( $buttons ) ) {
				foreach( $buttons as $button ) {
					$dt_config['tableTools']['aButtons'][] = array(
						'sExtends' => $button,
						'sButtonText' => $button_labels[ $button ],
					);
				}
			}

		}

		do_action( 'gravityview_log_debug', '[tabletools_add_config] Inserting TableTools config. Data: ', $dt_config );

		return $dt_config;
	}

}

new GV_Extension_DataTables_TableTools;
