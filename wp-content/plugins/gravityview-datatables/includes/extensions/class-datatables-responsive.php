<?php

/**
 * Responsive
 * @link https://datatables.net/extensions/responsive/
 */
class GV_Extension_DataTables_Responsive extends GV_DataTables_Extension {

	protected $settings_key = 'responsive';

	/**
	 * Add the `responsive` class to the table to enable the functionality
	 * @param string $classes Existing class attributes
	 * @return  string Possibly modified CSS class
	 */
	function add_html_class( $classes = '' ) {

		if( $this->is_enabled() ) {
			$classes .= ' responsive nowrap';
		}

		return $classes;
	}

	/**
	 * Register the tooltip with Gravity Forms
	 * @param  array  $tooltips Existing tooltips
	 * @return array           Modified tooltips
	 */
	function tooltips( $tooltips = array() ) {

		$tooltips['gv_datatables_responsive'] = array(
			'title' => __('Responsive Tables', 'gv-datatables'),
			'value' => __('Optimize table layout for different screen sizes through the dynamic insertion and removal of columns from the table.', 'gv-datatables')
		);

		return $tooltips;
	}

	/**
	 * Set the default setting
	 * @param  array $settings DataTables settings
	 * @return array           Modified settings
	 */
	function defaults( $settings ) {

		$settings['responsive'] = false;

		return $settings;
	}

	function settings_row( $ds ) {
	?>
		<table class="form-table">
			<caption>Responsive</caption>
			<tr valign="top">
				<td colspan="2">
					<?php
						echo GravityView_Render_Settings::render_field_option( 'datatables_settings[responsive]', array(
								'label' => __( 'Enable Responsive Tables', 'gv-datatables' ),
								'type' => 'checkbox',
								'value' => 1,
								'tooltip' => 'gv_datatables_responsive',
							), $ds['responsive'] );
					?>
				</td>
			</tr>
		</table>
	<?php
	}


	/**
	 * Inject Scripts and Styles if needed
	 */
	function add_scripts( $dt_configs, $views, $post ) {

		$script = false;

		foreach ( $views as $key => $view_data ) {
			if( !$this->is_datatables( $view_data ) || !$this->is_enabled( $view_data['id'] ) ) { continue; }
			$script = true;
		}

		if( !$script ) { return; }

		$path = plugins_url( 'assets/datatables-responsive/', GV_DT_FILE );

		/**
		 * Include Responsive core script (DT plugin)
		 * Use your own DataTables core script by using the `gravityview_dt_responsive_script_src` filter
		 */
		wp_enqueue_script( 'gv-dt-responsive', apply_filters( 'gravityview_dt_responsive_script_src', $path.'js/dataTables.responsive.js' ), array( 'jquery', 'gv-datatables' ), GV_Extension_DataTables::version, true );

		/**
		 * Use your own Responsive stylesheet by using the `gravityview_dt_responsive_style_src` filter
		 */
		wp_enqueue_style( 'gv-dt_responsive_style', apply_filters( 'gravityview_dt_responsive_style_src', $path.'css/dataTables.responsive.css' ), array('gravityview_style_datatables_table'), GV_Extension_DataTables::version );

	}


	/**
	 * Responsive add specific config data based on admin settings
	 */
	function add_config( $dt_config, $view_id, $post  ) {

		if( $this->is_enabled( $view_id ) ) {
			$dt_config['responsive'] = true;
		}

		return $dt_config;
	}

}

new GV_Extension_DataTables_Responsive;
