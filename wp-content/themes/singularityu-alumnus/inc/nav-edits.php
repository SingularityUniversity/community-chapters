<?php
/**
 *
 * This file adds the description to the navigation menus.
 *
 */


class Menu_With_Description extends Walker_Nav_Menu {
    static protected $className;
    function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ){

        self::$className = null;

        //$my_menu = wp_get_nav_menu_object( 'Main Navigation' );

        //var_dump($my_menu->count);


        global $wp_query;

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $class_names = $value = '';

        $classes = empty( $object->classes ) ? array() : (array) $object->classes;

        self::$className = $classes;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $object ) );

        $class_names = ' class="' . esc_attr( $class_names ) . '"';

        $output .= $indent . '<li id="menu-item-'. $object->ID . '"' . $value . $class_names .'>';

        if ((!isset($object->description) || empty($object->description) || $object->description == ' ') && $depth >= 1){
            $cetner_class = 'class="center"';
        }
        else {
            $cetner_class = '';
        }

        $attributes = ! empty( $object->attr_title ) ? ' title="' . esc_attr( $object->attr_title ) .'"' : '';
        $attributes .= ! empty( $object->target ) ? ' target="' . esc_attr( $object->target ) .'"' : '';
        $attributes .= ! empty( $object->xfn ) ? ' rel="' . esc_attr( $object->xfn ) .'"' : '';
        if (strpos($object->url, 'login-logout-button')){
            $object->url = do_shortcode("[login-logout-button]");
        }
        $attributes .= ! empty( $object->url ) ? ' href="' . esc_attr( $object->url ) .'"' : '';

        $object_output = $args->before;
        $object_output .= '<a'. $attributes . $cetner_class . '>';
        $object_output .= $args->link_before . apply_filters( 'the_title', $object->title, $object->ID ) . $args->link_after;

        if ($args->theme_location === "primary" && $depth >= 1){
            $object_output .= '<span class="sub">' . $object->description . '</span>';
        }
        $object_output .= '</a>';
        $object_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $object_output, $object, $depth, $args );
    }
    function start_lvl(&$output, $depth = 0, $args = array(), $current_object_id = 0) {
        $indent = str_repeat("\t", $depth);
        if ( in_array('participate',self::$className)){
            $output .= "\n$indent<ul class=\"sub-menu container"."\">\n";
        }
        else{
            $output .= "\n$indent<ul class=\"sub-menu"."\">\n";
        }
    }
}

remove_filter('nav_menu_description', 'strip_tags');

/*
add_filter( 'wp_setup_nav_menu_item', 'cus_wp_setup_nav_menu_item' );
function cus_wp_setup_nav_menu_item($menu_item) {
    $menu_item->description = apply_filters( 'nav_menu_description',  $menu_item->post_content );
    return $menu_item;
}

*/