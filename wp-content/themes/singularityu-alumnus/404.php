<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package SingularityU Alumnus
 */

$post_id = get_page_by_path('page-not-found');
if (!empty($post_id)){
    $post_id = $post_id->ID;
    $post_id = absint($post_id);
}
else {
    $post_id = get_the_ID();
}

$alternate_title = get_post_meta($post_id,'alternate_title',true);
$banner_call_to_action_or_title = get_post_meta($post_id,'banner_call_to_action_or_title',true);
$display_title_under_the_banner = get_post_meta($post_id,'display_title_under_the_banner',true);
$page_custom_menu = get_post_meta($post_id,'page_custom_menu',true);
if (!empty($page_custom_menu)){
    $nav_menu_items = wp_get_nav_menu_items( $page_custom_menu['name'] );
    $nav_items_ID = array();
    foreach($nav_menu_items as $nav_menu_item){
        $nav_items_ID[] = $nav_menu_item->object_id;
    }
}
$cta_desc_below = get_post_meta($post_id,'call_to_action_description_after_content',true);
$cta_btn_txt_below = get_post_meta($post_id,'call_to_action_button_text_below',true);
$cta_link_below = get_post_meta($post_id,'call_to_action_link_below',true);
$cta_small_print_below = get_post_meta($post_id,'call_to_action_small_print_below',true);
$cta_small_print_link_below = get_post_meta($post_id,'call_to_action_small_print_link_below',true);

if(!empty($banner_call_to_action_or_title) && ($banner_call_to_action_or_title == "title" ||$banner_call_to_action_or_title == "alt_title" )){
    $add_class = ' add-title';
}
$column_after_content_2 = get_post_meta($post_id,'column_after_content_2',true);
$column_after_content = get_post_meta($post_id,'column_after_content',true);
$below_cta_text_link_attrs = get_post_meta($post_id, 'below_cta_text_link_attrs',false);
$below_small_text_link_attrs  = get_post_meta($post_id, 'below_small_text_link_attrs',false);
$full_width_section_below_content = get_post_meta($post_id,'full_width_section_below_content',true);

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
$page_slug = basename(get_the_permalink(get_the_ID()));

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <?php get_template_part( 'banner' ); ?>
            <div id="below-fold" class="container-fluid">
                <div class="row">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <?php if (!empty($display_title_under_the_banner) && $display_title_under_the_banner !== "0"): ?>
                                        <?php if((!empty($banner_call_to_action_or_title) && ($banner_call_to_action_or_title == "title" || $banner_call_to_action_or_title == "alt_title"))): ?>
                                            <?php if (!empty($alternate_title) && $banner_call_to_action_or_title !== "alt_title"): ?>
                                                <div id="entry-sub-header">
                                                    <h2 class="entry-subtitle"><?php echo apply_filters('the_title',$alternate_title); ?></h2>
                                                </div>
                                            <?php else: ?>
                                                <div id="entry-sub-header">
                                                    <h2 class="entry-subtitle"><?php echo apply_filters('the_title', get_the_title($post_id)); ?></h2>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif((!empty($banner_call_to_action_or_title) && ($banner_call_to_action_or_title !== "title" || $banner_call_to_action_or_title !== "alt_title"))): ?>
                                            <header class="page-header">
                                                <?php if (!empty($alternate_title)): ?>
                                                    <h1 class="page-title"><?php echo apply_filters('the_title',$alternate_title); ?></h1>
                                                <?php else: ?>
                                                    <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'singularityu-alumnus' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                                                <?php endif; ?>
                                            </header><!-- .page-header -->
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <div class="page-content">
                                        <p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'singularityu-alumnus' ); ?></p>

                                        <?php get_search_form(); ?>

                                    </div><!-- .page-content -->
                                </div>
                                <?php if(isset($full_width_section_below_content) && !empty($full_width_section_below_content)){
                                    $show_full_width = true;
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo $full_width_section_below_content;
                                }
                                else {
                                    $show_full_width = false;
                                }

                                if ($show_full_width == true):

                                ?>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php endif;

                                                    if (
                                                        (!isset($column_after_content) || empty($column_after_content) || $column_after_content == '')
                                                        && (!isset($column_after_content_2) || empty($column_after_content_2) || $column_after_content_2 == '')
                                                        && (!isset($cta_btn_txt_below) || empty($cta_btn_txt_below) || $cta_btn_txt_below == '')
                                                        && (!isset($cta_desc_below) || empty($cta_desc_below) || $cta_desc_below == '')
                                                        && (!isset($cta_small_print_below) || empty($cta_small_print_below) || $cta_small_print_below == '')
                                                    )
                                                    {
                                                        $hide_me = true;
                                                    }
                                                    else{
                                                        $hide_me = false;
                                                    }

                                                    ?>
                                                    <?php if ($hide_me == false): ?>

                                                    <div class="row cols-after-content">
                                                        <?php if(!empty($column_after_content) && isset($column_after_content) && empty($column_after_content_2) || !isset($column_after_content_2)): ?>
                                                            <div class="col-md-12">
                                                                <?php if(!empty($column_after_content) && isset($column_after_content)): ?>
                                                                    <?php echo __($column_after_content,'singularityu-alumnus'); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php elseif (!empty($column_after_content) && isset($column_after_content) && (!empty($column_after_content_2) || isset($column_after_content_2))): ?>
                                                            <div class="col-md-6">
                                                                <?php if(!empty($column_after_content) && isset($column_after_content)): ?>
                                                                    <?php echo __($column_after_content,'singularityu-alumnus'); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <?php echo __($column_after_content_2,'singularityu-alumnus'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="row">
                                                        <div id="callToAction" class="col-md-12">
                                                            <?php if(!empty($cta_desc_below)): ?>
                                                                <span id="before-cta" >
                                            <?php echo __($cta_desc_below,'singularityu-alumnus'); ?>
                                        </span>
                                                            <?php endif; ?>
                                                            <?php if(!empty($cta_link_below) && !empty($cta_btn_txt_below)): ?>
                                                                <span id="calltoActionButton"><a <?php echo $below_cta_text_link_attrs_nofollow; echo $below_cta_text_link_attrs_blank; ?>class="button btn" href="<?php echo esc_url($cta_link_below); ?>"><?php echo __($cta_btn_txt_below,'singularityu-alumnus'); ?></a></span>
                                                            <?php endif; ?>
                                                            <?php if(!empty($cta_small_print_link_below) && !empty($cta_small_print_below)): ?>
                                                                <a <?php echo $below_small_text_link_attrs_blank; echo $below_cta_text_link_attrs_nofollow; ?> class="small link" href="<?php echo esc_url($cta_small_print_link); ?>"><?php echo __($cta_small_print,'singularityu-alumnus'); ?></a>
                                                            <?php else: ?>
                                                                <p id="small link"><?php echo __($cta_small_print_below,'singularityu-alumnus'); ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php //singularityu_alumnus_entry_footer(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php if(isset($full_width_section_below_content) && !empty($full_width_section_below_content)): ?>
                            </div>
                            <?php endif; ?>

                            </section><!-- .error-404 -->
        </main><!-- #main -->
    </div><!-- #primary -->

<?php get_footer(); ?>