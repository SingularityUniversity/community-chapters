<?php
/*
 *
 * Banner Template file
 *
 */

if (is_search()){
    $post_id = get_page_by_path('search');
    if (!empty($post_id)){
        $post_id = $post_id->ID;
        $post_id = absint($post_id);
    }
    else {
        $post_id = get_the_ID();
    }
}
elseif (is_404() ){
    $post_id = get_page_by_path('page-not-found');
    if (!empty($post_id)){
        $post_id = $post_id->ID;
        $post_id = absint($post_id);
    }
    else {
        $post_id = get_the_ID();
    }
}
else {
    $post_id = get_the_ID();
}

$cta_desc = get_post_meta($post_id,'call_to_action_description',true);
$cta_btn_txt = get_post_meta($post_id,'call_to_action_button_text',true);
$cta_link = get_post_meta($post_id,'call_to_action_link',true);
$cta_small_print = get_post_meta($post_id,'call_to_action_small_print',true);
$cta_small_print_link = get_post_meta($post_id,'call_to_action_small_print_link',true);
$alternate_title = get_post_meta($post_id,'alternate_title',true);
$banner_call_to_action_or_title = get_post_meta($post_id,'banner_call_to_action_or_title',true);
$display_title_under_the_banner = get_post_meta($post_id,'display_title_under_the_banner',true);

if(!empty($banner_call_to_action_or_title) && ($banner_call_to_action_or_title == "title" ||$banner_call_to_action_or_title == "alt_title" )){
    $add_class = ' add-title';
}

$banner_cta_text_link_attrs = get_post_meta($post_id, 'banner_cta_text_link_attrs',false);
$banner_cta_small_link_attrs = get_post_meta($post_id, 'banner_cta_small_link_attrs',false);

if (isset($banner_cta_text_link_attrs[0]) && !empty($banner_cta_text_link_attrs[0])){
    $banner_cta_text_link_attrs_nofollow = "rel='nofollow' ";
}
else{
    $banner_cta_text_link_attrs_nofollow = '';
}
if (isset($banner_cta_small_link_attrs[0]) && !empty($banner_cta_small_link_attrs[0])){
    $banner_cta_small_link_attrs_nofollow = "rel='nofollow' ";
}
else{
    $banner_cta_small_link_attrs_nofollow = '';
}
if (isset($banner_cta_text_link_attrs[1]) && !empty($banner_cta_text_link_attrs[1])){
    $banner_cta_text_link_attrs_blank = "target='_blank' ";
}
else{
    $banner_cta_text_link_attrs_blank = '';
}
if (isset($banner_cta_small_link_attrs[1]) && !empty($banner_cta_small_link_attrs[1])){
    $banner_cta_small_link_attrs_blank = "target='_blank' ";
}
else{
    $banner_cta_small_link_attrs_blank = '';
}

?>

<?php if ( (has_post_thumbnail($post_id) || has_post_thumbnail()) && $banner_call_to_action_or_title !== "none"): ?>
    <?php
    $post_thumb_ID = get_post_thumbnail_id($post_id);
    $post_thumb_data = wp_get_attachment_image_src($post_thumb_ID, 'full'. false);
    ?>
    <div id="banner" class="container-fluid">
        <section class="row<?php if(isset($add_class)){ echo $add_class;} ?>" style="background-image: url(<?php echo get_template_directory_uri() . '/css/images/black-overlay.png'; ?>), url(<?php echo $post_thumb_data[0]; ?>)">
            <?php if((!empty($cta_desc) || !empty($cta_btn_txt)||!empty($cta_link) || !empty($cta_small_print) || !empty($cta_small_print_link)) && $banner_call_to_action_or_title == "call_to_action"): ?>
                <div id="callToAction">
                    <?php if(!empty($cta_desc)): ?>
                        <span id="description"><?php echo __($cta_desc,'singularityu-alumnus'); ?></span>
                    <?php endif; ?>
                    <?php if(!empty($cta_link) && !empty($cta_btn_txt)): ?>
                        <span id="calltoActionButton"><a <?php echo $banner_cta_text_link_attrs_blank; echo $banner_cta_text_link_attrs_nofollow; ?> class="button btn" href="<?php echo esc_url($cta_link); ?>"><?php echo __($cta_btn_txt,'singularityu-alumnus'); ?></a></span>
                    <?php endif; ?>
                    <?php if(!empty($cta_small_print_link) && !empty($cta_small_print)): ?>
                        <a <?php echo $banner_cta_small_link_attrs_blank; echo $banner_cta_small_link_attrs_nofollow; ?>class="small link" href="<?php echo esc_url($cta_small_print_link); ?>"><?php echo __($cta_small_print,'singularityu-alumnus'); ?></a>
                    <?php else: ?>
                        <p id="small link"><?php echo __($cta_small_print,'singularityu-alumnus'); ?></p>
                    <?php endif; ?>
                </div>
            <?php elseif(!empty($banner_call_to_action_or_title) && $banner_call_to_action_or_title == "title"): ?>

                <?php if ( is_front_page() ): ?>
                    <header class="entry-header container">
                        <div class="row">
                            <?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
                        </div>
                    </header><!-- .entry-header -->
                <?php elseif(is_search()): ?>
                    <header class="page-header container">
                        <div class="row">
                            <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'singularityu-alumnus' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                        </div>
                    </header><!-- .page-header -->
                <?php else: ?>
                    <header class="entry-header container">
                        <div class="row">
                            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                        </div>
                    </header><!-- .entry-header -->
                <?php endif; ?>

            <?php elseif(!empty($banner_call_to_action_or_title) && $banner_call_to_action_or_title == "alt_title"): ?>

                <?php if ( is_front_page() ) : ?>
                    <header class="entry-header container">
                        <div class="row">
                            <h3 class="entry-title"><?php echo apply_filters( 'the_title', $alternate_title ); ?></h3>
                        </div>
                    </header><!-- .entry-header -->
                <?php else: ?>
                    <header class="entry-header container">
                        <div class="row">
                            <h1 class="entry-title"><?php echo apply_filters( 'the_title', $alternate_title ); ?></h1>
                        </div>
                    </header><!-- .entry-header -->
                <?php endif; ?>

            <?php endif; ?>
        </section>
    </div>
<?php endif; ?>
