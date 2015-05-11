<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package SingularityU Alumnus
 */
$is_active_sidebar_2 = is_active_sidebar( 'sidebar-2' );
$is_active_sidebar_4 = is_active_sidebar( 'sidebar-4' );
$is_active_sidebar_5 = is_active_sidebar( 'sidebar-5' );
$is_active_sidebar_6 = is_active_sidebar( 'sidebar-6' );
$is_active_sidebar_7 = is_active_sidebar( 'sidebar-7' );
$is_active_sidebar_8 = is_active_sidebar( 'sidebar-8' );

?>

</div><!-- #content -->

<footer id="colophon" class="site-footer footer container-fluid" role="contentinfo">
    <?php if ($is_active_sidebar_2 === TRUE): ?>
        <div id="promobox" class="row">
            <div class="container">
                <?php dynamic_sidebar( 'sidebar-2' ); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="container">
        <div id="footerCols" class="row">
            <div id="footer-left" class="col col-md-3 col-sm-3">
                <div  id="singu-global">
                    <?php
                    if ($is_active_sidebar_4 === TRUE){
                        dynamic_sidebar( 'sidebar-4' );
                    }
                    ?>
                </div>
            </div>
            <div id="footer-middle-left" class="col col-md-3 col-sm-3">
                <div  id="sing-u">
                    <?php
                    if ($is_active_sidebar_5 === TRUE){
                        dynamic_sidebar( 'sidebar-5' );
                    }
                    ?>
                </div>
            </div>
            <div id="footer-middle-right" class="col col-md-3 col-sm-3">
                <div id="get-inspired">
                    <?php
                    if ($is_active_sidebar_6 === TRUE){
                        dynamic_sidebar( 'sidebar-6' );
                    }
                    ?>
                </div>
            </div>
            <div id="footer-right" class="col col-md-3 col-sm-3">
                <div  class="contact-info">
                    <?php
                    if ($is_active_sidebar_7 === TRUE){
                        dynamic_sidebar( 'sidebar-7' );
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="very-bottom">
        <div class="container">
            <div class="col-md-6">
                <?php
                if ($is_active_sidebar_8 === TRUE){
                    dynamic_sidebar( 'sidebar-8' );
                }
                ?>
            </div>
            <div class="col-md-6">
                <?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => 'div', 'container_class' => 'footer-nav', 'menu_id' => 'nav', 'fallback_cb' => '', 'menu_class' => 'nav navbar-nav navbar-right' ) ); ?>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
