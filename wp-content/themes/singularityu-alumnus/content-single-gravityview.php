<?php
/**
 * The template used for displaying page content in page.php with no sidebar
 *
 * @package SingularityU Alumnus
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div id="below-fold" class="container-fluid">
        <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row">
                            <header class="entry-header">
                                <?php the_title( '<h3>', '</h3>' ); ?>
                            </header><!-- .entry-header -->
                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div><!-- .entry-content -->
                </div>
            </div>
        </div>
    </div>
</article><!-- #post-## -->
