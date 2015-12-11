<?php

/**
 *
 * Enable the Scroller extension for DataTables
 *
 * @link https://datatables.net/extensions/scroller/
 */
class GV_Extension_DataTables_Scroller extends GV_DataTables_Extension {

	protected $settings_key = 'scroller';

	function defaults( $settings ) {

		$settings['scroller'] = false;
		$settings['scrolly'] = 500;

		return $settings;
	}

	/**
	 * Register the tooltip with Gravity Forms
	 * @param  array  $tooltips Existing tooltips
	 * @return array           Modified tooltips
	 */
	function tooltips( $tooltips = array() ) {

		$tooltips['gv_datatables_scroller'] = array(
			'title' => __('Scroller', 'gv-datatables'),
			'value' => __('Allow large datasets to be drawn on screen in one continuous page. The aim of Scroller for DataTables is to make rendering large data sets fast.

				Note: Scroller will not work well if your View has columns of varying height.', 'gv-datatables')
		);

		return $tooltips;
	}

	function settings_row( $ds ) {
	?>
		<table class="form-table">
			<caption>Scroller</caption>
			<tr valign="top">
				<td colspan="2">
					<?php
						echo GravityView_Render_Settings::render_field_option( 'datatables_settings[scroller]', array(
								'label' => __( 'Enable Scroller', 'gv-datatables' ),
								'type' => 'checkbox',
								'value' => 1,
								'tooltip' => 'gv_datatables_scroller',
							), $ds['scroller'] );
					?>
				</td>
			</tr>
			<tr valign="top">
				<td scope="row">
					<label for="gravityview_dt_scrollerheight"><?php esc_html_e( 'Table Height', 'gv-datatables'); ?></label>
				</td>
				<td>
					<input name="datatables_settings[scrolly]" id="gravityview_dt_scrollerheight" type="number" step="1" min="50" value="<?php empty( $ds['scrolly'] ) ? print 500 : print $ds['scrolly']; ?>" class="small-text">
				</td>
			</tr>
		</table>
	<?php
	}

	/**
	 * Inject Scroller Scripts and Styles if needed
	 */
	function add_scripts( $dt_configs, $views, $post ) {

		$script = false;

		foreach ( $views as $key => $view_data ) {
			if( !$this->is_datatables( $view_data ) || !$this->is_enabled( $view_data['id'] ) ) { continue; }
			$script = true;
		}

		if( !$script ) { return; }

		$script_debug = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

		$path = plugins_url( 'assets/datatables-scroller/', GV_DT_FILE );

		/**
		 * Include Scroller core script (DT plugin)
		 * Use your own DataTables core script by using the `gravityview_dt_scroller_script_src` filter
		 */
		wp_enqueue_script( 'gv-dt-scroller', apply_filters( 'gravityview_dt_scroller_script_src', $path.'js/dataTables.scroller'.$script_debug.'.js' ), array( 'jquery', 'gv-datatables' ), GV_Extension_DataTables::version, true );

		/**
		 * Use your own Scroller stylesheet by using the `gravityview_dt_scroller_style_src` filter
		 */
		wp_enqueue_style( 'gv-dt_scroller_style', apply_filters( 'gravityview_dt_scroller_style_src', $path.'css/dataTables.scroller.css' ), array('gravityview_style_datatables_table'), GV_Extension_DataTables::version, 'all' );

	}

	/**
	 * Scroller add specific config data based on admin settings
	 *
	 */
	function add_config( $dt_config, $view_id, $post  ) {

		if( !$this->is_enabled( $view_id ) ) { return $dt_config; }

		// init Scroller
		$dt_config['dom'] = empty( $dt_config['dom'] ) ? 'frtiS' : $dt_config['dom'].'S';

		// set table height
		$scrolly = $this->get_setting( $view_id, 'scrolly', 500 );

		// Use passed value, if already set
		$scrolly = empty( $dt_config['scrollY'] ) ? $scrolly : $dt_config['scrollY'];

		// Get rid of existing pixel definition, to make sure it's not double-set
		$scrolly = str_replace('px', '', $scrolly);

		// Finally set the scrollY parameter
		$dt_config['scrollY'] = $scrolly.'px';

		// Only render the DOM items you need to for speed
		//$dt_config['deferRender'] = true;

		do_action( 'gravityview_log_debug', '[scroller_add_config] Inserting Scroller config. Data:', $dt_config );

		return $dt_config;
	}

}

new GV_Extension_DataTables_Scroller;
