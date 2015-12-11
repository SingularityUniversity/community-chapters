<?php
/**
 * The template for displaying all single posts.
 *
 * @package SingularityU Alumnus
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

            <?php get_template_part( 'content', 'single-gravityview' ); ?>

        <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
