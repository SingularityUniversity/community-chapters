<?php
/**
 *
 */
class GFPGFU_Show_Page_On_Form_List {

	public function run () {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_init() {
if ( ( 'gf_edit_forms' == RGForms::get( 'page' ) ) && ( 1 == count( $_GET ) ) ) {
				$this->show_page_on_form_list();
	add_filter( 'gform_noconflict_scripts', array( $this, 'gform_noconflict_scripts' ) );
}
	}

	public function gform_noconflict_scripts( $scripts ) {
		return array_merge( $scripts, array( 'gp_gf_util_form_list' ) );
	}

	private function show_page_on_form_list() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'gp_gf_util_form_list', trailingslashit( GFP_GF_UTILITY_URL ) . "tools/show-page-on-form-list/form-list{$suffix}.js", array( 'jquery' ) );

		$pages = array();
		$query = new WP_Query( array( 's' => '[gravityform' ) );
		if ( $query->have_posts() ) {
			global $post;
			while( $query->have_posts() ) {
				$query->the_post();
				$shortcode_instances = preg_match_all( "/(\[gravityform)(.*)(id=\")([0-9]*)(\".*)(\])/", $post->post_content, $matches );
				if ( ! empty( $shortcode_instances ) ) {
					foreach ( $matches[4] as $match ) {
						$pages[] = array( 'form_id' => $match, 'name' => $post->post_title, 'edit_url' => get_edit_post_link() );
					}
				}
			}
		}
		$form_list_js_data = array( 'pages'              => $pages );
		wp_localize_script( 'gp_gf_util_form_list', 'gf_util_form_list', $form_list_js_data );
	}
}

$gfpgfu_show_page = new GFPGFU_Show_Page_On_Form_List();
$gfpgfu_show_page->run();