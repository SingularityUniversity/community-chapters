<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package SingularityU Alumnus
 */

if ( ! function_exists( 'the_posts_navigation' ) ) :
    /**
     * Display navigation to next/previous set of posts when applicable.
     *
     * @todo Remove this function when WordPress 4.3 is released.
     */
    function the_posts_navigation() {
        // Don't print empty markup if there's only one page.
        if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
            return;
        }
        ?>
        <nav class="navigation posts-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php _e( 'Posts navigation', 'singularityu-alumnus' ); ?></h2>
            <div class="nav-links">

                <?php if ( get_next_posts_link() ) : ?>
                    <div class="nav-previous"><?php next_posts_link( __( 'More Results &raquo;', 'singularityu-alumnus' ) ); ?></div>
                <?php endif; ?>

                <?php if ( get_previous_posts_link() ) : ?>
                    <div class="nav-next"><?php previous_posts_link( __( '&laquo; Less Results', 'singularityu-alumnus' ) ); ?></div>
                <?php endif; ?>

            </div><!-- .nav-links -->
        </nav><!-- .navigation -->
    <?php
    }
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
    /**
     * Display navigation to next/previous post when applicable.
     *
     * @todo Remove this function when WordPress 4.3 is released.
     */
    function the_post_navigation() {
        // Don't print empty markup if there's nowhere to navigate.
        $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
        $next     = get_adjacent_post( false, '', false );

        if ( ! $next && ! $previous ) {
            return;
        }
        ?>
        <nav class="navigation post-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php _e( 'Post navigation', 'singularityu-alumnus' ); ?></h2>
            <div class="nav-links">
                <?php
                previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
                next_post_link( '<div class="nav-next">%link</div>', '%title' );
                ?>
            </div><!-- .nav-links -->
        </nav><!-- .navigation -->
    <?php
    }
endif;

if ( ! function_exists( 'singularityu_alumnus_posted_on' ) ) :
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function singularityu_alumnus_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf( $time_string,
            esc_attr( get_the_date( 'c' ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( 'c' ) ),
            esc_html( get_the_modified_date() )
        );

        $posted_on = sprintf(
            _x( 'Posted on %s', 'post date', 'singularityu-alumnus' ),
            '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
        );

        $byline = sprintf(
            _x( 'by %s', 'post author', 'singularityu-alumnus' ),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

    }
endif;

if ( ! function_exists( 'singularityu_alumnus_entry_footer' ) ) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function singularityu_alumnus_entry_footer() {
        // Hide category and tag text for pages.
        if ( 'post' == get_post_type() ) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list( __( ', ', 'singularityu-alumnus' ) );
            if ( $categories_list && singularityu_alumnus_categorized_blog() ) {
                printf( '<span class="cat-links">' . __( 'Posted in %1$s', 'singularityu-alumnus' ) . '</span>', $categories_list );
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list( '', __( ', ', 'singularityu-alumnus' ) );
            if ( $tags_list ) {
                printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'singularityu-alumnus' ) . '</span>', $tags_list );
            }
        }

        if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link">';
            comments_popup_link( __( 'Leave a comment', 'singularityu-alumnus' ), __( '1 Comment', 'singularityu-alumnus' ), __( '% Comments', 'singularityu-alumnus' ) );
            echo '</span>';
        }

        edit_post_link( __( 'Edit', 'singularityu-alumnus' ), '<span class="edit-link">', '</span>' );
    }
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
    /**
     * Shim for `the_archive_title()`.
     *
     * Display the archive title based on the queried object.
     *
     * @todo Remove this function when WordPress 4.3 is released.
     *
     * @param string $before Optional. Content to prepend to the title. Default empty.
     * @param string $after  Optional. Content to append to the title. Default empty.
     */
    function the_archive_title( $before = '', $after = '' ) {
        if ( is_category() ) {
            $title = sprintf( __( 'Category: %s', 'singularityu-alumnus' ), single_cat_title( '', false ) );
        } elseif ( is_tag() ) {
            $title = sprintf( __( 'Tag: %s', 'singularityu-alumnus' ), single_tag_title( '', false ) );
        } elseif ( is_author() ) {
            $title = sprintf( __( 'Author: %s', 'singularityu-alumnus' ), '<span class="vcard">' . get_the_author() . '</span>' );
        } elseif ( is_year() ) {
            $title = sprintf( __( 'Year: %s', 'singularityu-alumnus' ), get_the_date( _x( 'Y', 'yearly archives date format', 'singularityu-alumnus' ) ) );
        } elseif ( is_month() ) {
            $title = sprintf( __( 'Month: %s', 'singularityu-alumnus' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'singularityu-alumnus' ) ) );
        } elseif ( is_day() ) {
            $title = sprintf( __( 'Day: %s', 'singularityu-alumnus' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'singularityu-alumnus' ) ) );
        } elseif ( is_tax( 'post_format' ) ) {
            if ( is_tax( 'post_format', 'post-format-aside' ) ) {
                $title = _x( 'Asides', 'post format archive title', 'singularityu-alumnus' );
            } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
                $title = _x( 'Galleries', 'post format archive title', 'singularityu-alumnus' );
            } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
                $title = _x( 'Images', 'post format archive title', 'singularityu-alumnus' );
            } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
                $title = _x( 'Videos', 'post format archive title', 'singularityu-alumnus' );
            } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
                $title = _x( 'Quotes', 'post format archive title', 'singularityu-alumnus' );
            } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
                $title = _x( 'Links', 'post format archive title', 'singularityu-alumnus' );
            } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
                $title = _x( 'Statuses', 'post format archive title', 'singularityu-alumnus' );
            } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
                $title = _x( 'Audio', 'post format archive title', 'singularityu-alumnus' );
            } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
                $title = _x( 'Chats', 'post format archive title', 'singularityu-alumnus' );
            }
        } elseif ( is_post_type_archive() ) {
            $title = sprintf( __( 'Archives: %s', 'singularityu-alumnus' ), post_type_archive_title( '', false ) );
        } elseif ( is_tax() ) {
            $tax = get_taxonomy( get_queried_object()->taxonomy );
            /* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
            $title = sprintf( __( '%1$s: %2$s', 'singularityu-alumnus' ), $tax->labels->singular_name, single_term_title( '', false ) );
        } else {
            $title = __( 'Archives', 'singularityu-alumnus' );
        }

        /**
         * Filter the archive title.
         *
         * @param string $title Archive title to be displayed.
         */
        $title = apply_filters( 'get_the_archive_title', $title );

        if ( ! empty( $title ) ) {
            echo $before . $title . $after;
        }
    }
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
    /**
     * Shim for `the_archive_description()`.
     *
     * Display category, tag, or term description.
     *
     * @todo Remove this function when WordPress 4.3 is released.
     *
     * @param string $before Optional. Content to prepend to the description. Default empty.
     * @param string $after  Optional. Content to append to the description. Default empty.
     */
    function the_archive_description( $before = '', $after = '' ) {
        $description = apply_filters( 'get_the_archive_description', term_description() );

        if ( ! empty( $description ) ) {
            /**
             * Filter the archive description.
             *
             * @see term_description()
             *
             * @param string $description Archive description to be displayed.
             */
            echo $before . $description . $after;
        }
    }
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function singularityu_alumnus_categorized_blog() {
    if ( false === ( $all_the_cool_cats = get_transient( 'singularityu_alumnus_categories' ) ) ) {
        // Create an array of all the categories that are attached to posts.
        $all_the_cool_cats = get_categories( array(
            'fields'     => 'ids',
            'hide_empty' => 1,

            // We only need to know if there is more than one category.
            'number'     => 2,
        ) );

        // Count the number of categories that are attached to the posts.
        $all_the_cool_cats = count( $all_the_cool_cats );

        set_transient( 'singularityu_alumnus_categories', $all_the_cool_cats );
    }

    if ( $all_the_cool_cats > 1 ) {
        // This blog has more than 1 category so singularityu_alumnus_categorized_blog should return true.
        return true;
    } else {
        // This blog has only 1 category so singularityu_alumnus_categorized_blog should return false.
        return false;
    }
}

/**
 * Flush out the transients used in singularityu_alumnus_categorized_blog.
 */
function singularityu_alumnus_category_transient_flusher() {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient( 'singularityu_alumnus_categories' );
}
add_action( 'edit_category', 'singularityu_alumnus_category_transient_flusher' );
add_action( 'save_post',     'singularityu_alumnus_category_transient_flusher' );

/*
 * Has Subpage
 */

function has_subpage() {
    global $post;
    if($post->post_parent){
        $children = wp_list_pages("title_li=&child_of=".$post->post_parent."&echo=0");
    } else {
        $children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
    } if ($children) {
        return true;
    } else {
        return false;
    }
}

/**
 * Get next/prev page based on siblings
 */

function get_prev_page(){
    $stored_values = array();
    if ('page' == get_post_type()){
        $post_parent = wp_get_post_parent_id(get_the_ID());
        $current_page_id = get_queried_object_id();
        if (isset($post_parent) && $post_parent > 0){

            /*
             * Start Next Loop
             */

            $args = array(
                'post_parent' => $post_parent,
                'post_type' => 'page',
                'orderby' => 'menu_order',
                'order'    => 'ASC',
                'posts_per_page' => -1
            );

            $prev_query = new WP_Query($args);

            while ( $prev_query->have_posts() ) : $prev_query->the_post();
                $stored_values[$prev_query->post->menu_order] = get_the_ID();
            endwhile;

            if (current($stored_values) === $current_page_id){
                prev($stored_values);
                if (current($stored_values) !== false){
                    $prev_page_permalink = get_the_permalink(current($stored_values));

                    $link = "<div class='nav-prev'>";
                    $link .= "<a class='more-link' ";
                    $link .= "href='$prev_page_permalink' >";
                    $link .= '&laquo;';
                    $link .= __( ' Previous: ', 'singularityu-alumnus' );
                    $link .= get_the_title(current($stored_values));
                    $link .= "</a></div>";

                    echo $link;
                }
                else{
                    $prev_page_permalink = get_the_permalink($post_parent);
                    $link = "<div class='nav-prev'>";
                    $link .= "<a class='more-link' ";
                    $link .= "href='$prev_page_permalink' >";
                    $link .= '&laquo;';
                    $link .= __( ' Previous: ', 'singularityu-alumnus' );
                    $link .= get_the_title($post_parent);
                    $link .= "</a></div>";

                    echo $link;
                }
            }
            else {
                while (current($stored_values) != $current_page_id){
                    next($stored_values);
                }

                prev($stored_values);

                $prev_page_permalink = get_the_permalink(current($stored_values));

                if (current($stored_values) !== false){
                    $link = "<div class='nav-prev'>";
                    $link .= "<a class='more-link' ";
                    $link .= "href='$prev_page_permalink' >";
                    $link .= '&laquo;';
                    $link .= __( ' Previous: ', 'singularityu-alumnus' );
                    $link .= get_the_title(current($stored_values));
                    $link .= "</a></div>";

                    echo $link;
                }
            }
        }
    }
}

function get_next_page(){
    $stored_values = array();
    if ('page' == get_post_type()){
        $post_parent = wp_get_post_parent_id(get_the_ID());
        $current_page_id = get_queried_object_id();
        if (isset($post_parent) && $post_parent > 0){

            /*
             * Start Next Loop
             */

            $args = array(
                'post_parent' => $post_parent,
                'post_type' => 'page',
                'orderby' => 'menu_order',
                'order'    => 'ASC',
                'posts_per_page' => -1
            );

            $next_query = new WP_Query($args);

            while ( $next_query->have_posts() ) : $next_query->the_post();
                $stored_values[$next_query->post->menu_order] = get_the_ID();
            endwhile;

            if (current($stored_values) === $current_page_id){
                next($stored_values);
                if (current($stored_values) !== false){
                    $next_page_permalink = get_the_permalink(current($stored_values));

                    $link = "<div class='nav-next'>";
                    $link .= "<a class='more-link' ";
                    $link .= "href='$next_page_permalink' >";
                    $link .= __( 'Next: ', 'singularityu-alumnus' );
                    $link .= get_the_title(current($stored_values));
                    $link .= ' &raquo;';
                    $link .= "</a></div>";

                    echo $link;
                }
            }
            else {
                while (current($stored_values) != $current_page_id){
                    next($stored_values);
                }

                next($stored_values);

                $next_page_permalink = get_the_permalink(current($stored_values));

                if (current($stored_values) !== false){
                    $link = "<div class='nav-next'>";
                    $link .= "<a class='more-link' ";
                    $link .= "href='$next_page_permalink' >";
                    $link .= __( 'Next: ', 'singularityu-alumnus' );
                    $link .= get_the_title(current($stored_values));
                    $link .= ' &raquo;';
                    $link .= "</a></div>";

                    echo $link;
                }
            }
        }
        elseif (has_subpage() === true) {

            $post_parent = get_the_ID();

            $args = array(
                'post_parent' => $post_parent,
                'post_type'   => 'page',
                'posts_per_page' => 1,
                'post_status' => 'publish',
                'orderby'     => 'menu_order',
                'order'       => 'ASC'
            );

            $children = get_children($args);

            foreach ($children as $child){
                $first_child_link = $child->ID;
            }
            $next_page_permalink = get_the_permalink($first_child_link);
            $link = "<div class='nav-next'>";
            $link .= "<a class='more-link' ";
            $link .= "href='$next_page_permalink' >";
            $link .= __( 'Next: ', 'singularityu-alumnus' );
            $link .= get_the_title($first_child_link);
            $link .= ' &raquo;';
            $link .= "</a></div>";

            echo $link;

        }
    }
}