<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package SingularityU Alumnus
 */

/**
 * Add theme support for a Logo
 * see; http://jetpack.me/support/site-logo/
 */

$args = array(
    'header-text' => array(
        'site-title',
        'site-description',
    ),
    'size' => 'medium',
);
add_theme_support( 'site-logo', $args );

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function singularityu_alumnus_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'singularityu_alumnus_jetpack_setup' );
