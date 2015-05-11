<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package SingularityU Alumnus
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function singularityu_alumnus_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'singularityu_alumnus_body_classes' );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function singularityu_alumnus_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'singularityu-alumnus' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'singularityu_alumnus_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function singularityu_alumnus_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'singularityu_alumnus_render_title' );
endif;

/**
 *
 * Some Gravity Forms Magic to make the submit button responsive on the newsletter page.
 *
 */

add_filter("gform_submit_button_5", "pbc_gf_add_class_to_button_front_end", 10, 2);
function pbc_gf_add_class_to_button_front_end($button, $form){

    preg_match("/class='[\.a-zA-Z_ -]+'/", $button, $classes);
    $classes[0] = substr($classes[0], 0, -1);
    $classes[0] .= ' ';
    $classes[0] .= 'col-xs-10 col-xs-offset-1 col-sm-4 col-md-4';
    $classes[0] .= "'";
    $button_pieces = preg_split(
        "/class='[\.a-zA-Z_ -]+'/",
        $button,
        -1,
        PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
    );
    return $button_pieces[0] . $classes[0] . $button_pieces[1];
}

/**
 * Adding Bootstrap Styles to Gravity Forms
 */
add_filter("gform_field_content", "bootstrap_styles_for_gravityforms_fields", 10, 5);
function bootstrap_styles_for_gravityforms_fields($content, $field, $value, $lead_id, $form_id){

    // Currently only applies to most common field types, but can be expanded.

    if($field["type"] != 'hidden' && $field["type"] != 'list' && $field["type"] != 'multiselect' && $field["type"] != 'checkbox' && $field["type"] != 'fileupload' && $field["type"] != 'date' && $field["type"] != 'html' && $field["type"] != 'address') {
        $content = str_replace('class=\'medium', 'class=\'form-control medium', $content);
    }

    if($field["type"] == 'name' || $field["type"] == 'address') {
        $content = str_replace('<input ', '<input class=\'form-control\' ', $content);
    }

    if($field["type"] == 'textarea') {
        $content = str_replace('class=\'textarea', 'class=\'form-control textarea', $content);
    }

    if($field["type"] == 'checkbox') {
        $content = str_replace('li class=\'', 'li class=\'checkbox ', $content);
        $content = str_replace('<input ', '<input style=\'margin-left:1px;\' ', $content);
    }

    if($field["type"] == 'radio') {
        $content = str_replace('li class=\'', 'li class=\'radio ', $content);
        $content = str_replace('<input ', '<input style=\'margin-left:1px;\' ', $content);
    }


    return $content;
} // End bootstrap_styles_for_gravityforms_fields()

add_filter("gform_submit_button", "bootstrap_styles_for_gravityforms_buttons", 10, 5);
function bootstrap_styles_for_gravityforms_buttons($button, $form){

    $button = str_replace('class=\'gform_button', 'class=\'gform_button btn btn-primary', $button);

    return $button;

} // End bootstrap_styles_for_gravityforms_buttons()