<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package SingularityU Alumnus
 */

//Returns Array of Term Names for "my_taxonomy"

if (taxonomy_exists('classification')){
    $term_list = wp_get_post_terms($post->ID, 'classification', array("fields" => "names"));
}

if (empty($term_list) || !isset($term_list)){
    $term_list = array();
}

if (in_array('Start a Salon',$term_list)  || is_page_template('salon-page.php')){
    if( ! is_active_sidebar( 'sidebar-10' ) ) {
        return;
    }
    else{
        $sidebar = 'sidebar-10';
    }
}
elseif (in_array('Start a Chapter',$term_list) || is_page_template('chapter-page.php')){
    if( ! is_active_sidebar( 'sidebar-11' ) ) {
        return;
    }
    else{
        $sidebar = 'sidebar-11';
    }
}
elseif (in_array('Organize a Summit',$term_list) || is_page_template('summit-page.php')) {
    if (!is_active_sidebar('sidebar-12')) {
        return;
    } else {
        $sidebar = 'sidebar-12';
    }
}
elseif (in_array('Organize a G.I.C.',$term_list) || is_page_template('event-page.php')) {
    if (!is_active_sidebar('sidebar-13')) {
        return;
    } else {
        $sidebar = 'sidebar-13';
    }
}
elseif( ! is_active_sidebar( 'sidebar-9' ) ) {
	return;
}
else{
    $sidebar = 'sidebar-9';
}
?>

<div id="secondary" class="widget-area" role="complementary">
	<?php dynamic_sidebar( $sidebar ); ?>
</div><!-- #secondary -->
