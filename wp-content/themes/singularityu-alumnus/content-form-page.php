<?php
/**
 * The template used for displaying page content in page.php with no sidebar
 *
 * @package SingularityU Alumnus
 */
$alternate_title = get_post_meta(get_the_ID(),'alternate_title',true);
$banner_call_to_action_or_title = get_post_meta(get_the_ID(),'banner_call_to_action_or_title',true);
$display_title_under_the_banner = get_post_meta(get_the_ID(),'display_title_under_the_banner',true);

/*
 * @TODO Remove options meta boxes from pages that do no support them.
 */

/*
if (!empty($page_custom_menu)){
    $nav_menu_items = wp_get_nav_menu_items( $page_custom_menu['name'] );
    $nav_items_ID = array();
    foreach($nav_menu_items as $nav_menu_item){
        $nav_items_ID[] = $nav_menu_item->object_id;
    }
}

$page_custom_menu = get_post_meta(get_the_ID(),'page_custom_menu',true);
$cta_desc_below = get_post_meta(get_the_ID(),'call_to_action_description_after_content',true);
$cta_btn_txt_below = get_post_meta(get_the_ID(),'call_to_action_button_text_below',true);
$cta_link_below = get_post_meta(get_the_ID(),'call_to_action_link_below',true);
$cta_small_print_below = get_post_meta(get_the_ID(),'call_to_action_small_print_below',true);
$cta_small_print_link_below = get_post_meta(get_the_ID(),'call_to_action_small_print_link_below',true);

$column_after_content_2 = get_post_meta(get_the_ID(),'column_after_content_2',true);
$column_after_content = get_post_meta(get_the_ID(),'column_after_content',true);

$full_width_section_below_content = get_post_meta(get_the_ID(),'full_width_section_below_content',true);


if (isset($below_cta_text_link_attrs[0]) && !empty($below_cta_text_link_attrs[0])){
    $below_cta_text_link_attrs_nofollow = "rel='nofollow' ";
}
else{
    $below_cta_text_link_attrs_nofollow = '';
}
if (isset($below_small_text_link_attrs[0]) && !empty($below_small_text_link_attrs[0])){
    $below_small_text_link_attrs_nofollow = "rel='nofollow' ";
}
else{
    $below_small_text_link_attrs_nofollow = '';
}
if (isset($below_cta_text_link_attrs[1]) && !empty($below_cta_text_link_attrs[1])){
    $below_cta_text_link_attrs_blank = "target='_blank' ";
}
else{
    $below_cta_text_link_attrs_blank = '';
}
if (isset($below_small_text_link_attrs[1]) && !empty($below_small_text_link_attrs[1])){
    $below_small_text_link_attrs_blank = "target='_blank' ";
}
else{
    $below_small_text_link_attrs_blank = '';
}

*/

$page_slug = basename(get_the_permalink(get_the_ID()));

?>

<article id="post-<?php the_ID(); ?>" <?php post_class($page_slug); ?>>
    <?php get_template_part( 'banner' ); ?>
    <div id="below-fold" class="container">
        <?php if (!empty($display_title_under_the_banner) && $display_title_under_the_banner !== "0"): ?>
            <?php if((!empty($banner_call_to_action_or_title) && ($banner_call_to_action_or_title == "title" || $banner_call_to_action_or_title == "alt_title"))): ?>
                <?php if (!empty($alternate_title) && $banner_call_to_action_or_title !== "alt_title"): ?>
                    <div id="entry-sub-header" class="row">
                        <h4 class="entry-subtitle"><?php echo apply_filters('the_title',$alternate_title); ?></h4>
                    </div>
                <?php else: ?>
                    <div id="entry-sub-header" class="row">
                        <?php the_title( '<h4 class="entry-subtitle">', '</h4>' ); ?>
                    </div>
                <?php endif; ?>
            <?php elseif((!empty($banner_call_to_action_or_title) && ($banner_call_to_action_or_title !== "title" || $banner_call_to_action_or_title !== "alt_title"))): ?>
                <header class="entry-header row">
                    <?php if (!empty($alternate_title)): ?>
                        <h3 class="entry-title"><?php echo apply_filters('the_title',$alternate_title); ?></h3>
                    <?php else: ?>
                        <?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
                    <?php endif; ?>
                </header><!-- .entry-header -->
            <?php endif; ?>
        <?php endif; ?>
        <div class="entry-content row">
            <?php the_content(); ?>
        </div>
        <?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>
    </div>
</article><!-- #post-## -->
