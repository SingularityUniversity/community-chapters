<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package SingularityU Alumnus
 */


?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <!--[if lt IE 9]>
    <script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
    <![endif]-->
    <script>(function(){document.documentElement.className='js'})();</script>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
    <a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'singularityu-alumnus' ); ?></a>

    <header id="masthead" class="site-header" role="banner">
        <div class="container-fluid">
            <div id="site-announce" class="navbar navbar-default navbar-static-top row">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-4">
                            <?php if (is_active_sidebar('sidebar-1')): ?>
                                <div class="top-container-widget">
                                    <?php dynamic_sidebar( 'sidebar-1' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-8">
                            <nav id="social-navigation" class="navbar" role="navigation">
                                <div class="navbar-right">
                                    <?php
                                    /*
                                    if (!is_user_logged_in()){
                                        wp_nav_menu( array( 'theme_location' => 'header', 'container' => NULL, 'container_class' => '', 'menu_id' => 'nav', 'menu_class' => 'nav navbar-nav', 'fallback_cb' => '', "walker" => new Menu_With_Description ) );
                                    }
                                    else {
                                        wp_nav_menu( array( 'menu' => 'Header Menu - Logged In', 'container' => NULL, 'container_class' => '', 'menu_id' => 'nav', 'menu_class' => 'nav navbar-nav', 'fallback_cb' => '', "walker" => new Menu_With_Description ) );
                                    }
                                    */

                                    wp_nav_menu( array( 'theme_location' => 'header', 'container' => NULL, 'container_class' => '', 'menu_id' => 'nav', 'menu_class' => 'nav navbar-nav', 'fallback_cb' => '', "walker" => new Menu_With_Description ) );

                                    ?>
                                </div>
                            </nav><!-- #site-navigation -->
                        </div>
                    </div>
                </div>
            </div>
            <div id="brandingAndMenu" class="navbar navbar-default navbar-static-top row">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <div class="site-branding">
                                <?php if ( is_front_page() ) : ?>
                                    <h1 class="site-title">
                                        <?php if ( function_exists( 'jetpack_the_site_logo' ) ){
                                            jetpack_the_site_logo();
                                        }
                                        else {
                                            $the_image = get_template_directory_uri() . '/css/images/singlularityu-global-logo.png';
                                            echo '<img width="300" height="56" src="'.$the_image.'" class="site-logo attachment-medium" alt="SingularityU Global Logo" data-size="medium" itemprop="logo">';
                                        } ?>
                                        <span class="text-hide"><?php bloginfo( 'name' ); ?></span>
                                    </h1>
                                <?php elseif(is_archive()||is_category()|| is_tax()): ?>
                                    <h3 class="site-title">
                                        <?php if ( function_exists( 'jetpack_the_site_logo' ) ){
                                            jetpack_the_site_logo();
                                        }
                                        else {
                                            $the_image = get_template_directory_uri() . '/css/images/singlularityu-global-logo.png';
                                            echo '<img width="300" height="56" src="'.$the_image.'" class="site-logo attachment-medium" alt="SingularityU Global Logo" data-size="medium" itemprop="logo">';
                                        } ?>
                                        <span class="text-hide"><?php bloginfo( 'name' ); ?></span>
                                    </h3>
                                <?php elseif (is_single() ||is_page()) : ?>
                                    <h4 class="site-title">
                                        <?php if ( function_exists( 'jetpack_the_site_logo' ) ){
                                            jetpack_the_site_logo();
                                        }
                                        else {
                                            $the_image = get_template_directory_uri() . '/css/images/singlularityu-global-logo.png';
                                            echo '<img width="300" height="56" src="'.$the_image.'" class="site-logo attachment-medium" alt="SingularityU Global Logo" data-size="medium" itemprop="logo">';
                                        } ?>
                                        <span class="text-hide"><?php bloginfo( 'name' ); ?></span>
                                    </h4>
                                <?php else: ?>
                                    <p class="site-title">
                                        <?php if ( function_exists( 'jetpack_the_site_logo' ) ){
                                            jetpack_the_site_logo();
                                        }
                                        else {
                                            $the_image = get_template_directory_uri() . '/css/images/singlularityu-global-logo.png';
                                            echo '<img width="300" height="56" src="'.$the_image.'" class="site-logo attachment-medium" alt="SingularityU Global Logo" data-size="medium" itemprop="logo">';
                                        } ?>
                                        <span class="text-hide"><?php bloginfo( 'name' ); ?></span>
                                    </p>
                                <?php endif;
                                $description = get_bloginfo( 'description', 'display' );
                                if ( (is_front_page() && is_home()) && ($description || is_customize_preview()) ) : ?>
                                    <h2 class="site-description hide-text"><?php echo $description; ?></h2>
                                <?php elseif($description || is_customize_preview()): ?>
                                    <p class="site-description hide-text"><?php echo $description; ?></p>
                                <?php endif; ?>
                            </div><!-- .site-branding -->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-8">
                            <span class="menu-toggle"><button class="btn" id="trigger-overlay" type="button"><i class="fa fa-bars"></i><?php echo __(' Menu','singularityu-alumnus'); ?></button></span>
                            <div class="overlay overlay-hugeinc">
                                <button type="button" class="menu-toggle overlay-close"><i class="fa fa-close"></i></button>
                                <nav id="site-navigation" class="main-navigation navbar" role="navigation">
                                    <div class="navbar-right">
                                        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => NULL, 'container_class' => '', 'menu_id' => 'nav', 'menu_class' => 'nav navbar-nav', 'fallback_cb' => '', 'walker' => new Menu_With_Description ) ); ?>
                                    </div>
                                </nav><!-- #site-navigation -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header><!-- #masthead -->

    <div id="content" class="site-content">