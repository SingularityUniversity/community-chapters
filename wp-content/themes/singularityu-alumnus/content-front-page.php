<?php
/**
 * The template used for displaying home page content in front-page.php
 *
 * @package SingularityU Alumnus
 */
$alternate_title = get_post_meta(get_the_ID(),'alternate_title',true);
$banner_call_to_action_or_title = get_post_meta(get_the_ID(),'banner_call_to_action_or_title',true);
$display_title_under_the_banner = get_post_meta(get_the_ID(),'display_title_under_the_banner',true);
$page_custom_menu = get_option('singu_theme_settings_page_custom_menu');
$page_custom_menu_title = get_option('singu_theme_settings_page_custom_menu_title');
$page_custom_menu_description = get_option('singu_theme_settings_page_custom_menu_description');
$home_page_caruousel = get_option('singu_theme_settings_home_page_caruousel');
$home_page_map_title = get_option('singu_theme_settings_home_page_map_title');
$home_page_map_description = get_option('singu_theme_settings_home_page_map_description');
$home_page_map = get_option('singu_theme_settings_home_page_map');
if (!empty($page_custom_menu)){
    $nav_menu_items = wp_get_nav_menu_items( $page_custom_menu[0] );
    $nav_items_ID = array();
    foreach($nav_menu_items as $nav_menu_item){
        $nav_items_ID[] = $nav_menu_item->object_id;
    }
}

$singu_theme_settings_rss_feed = get_option('singu_theme_settings_rss_feed');
$singu_theme_settings_rss_feed =  esc_url($singu_theme_settings_rss_feed);
$singu_theme_settings_number_of_community_stories = get_option('singu_theme_settings_number_of_community_stories');

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
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
        <?php if (get_the_content() !== ''): ?>
            <div class="entry-content row">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($page_custom_menu_title) && !empty($page_custom_menu_title)): ?>
            <div id="entry-sub-header" class="row">
                <h3 class="entry-subtitle getInvolvedLinks"><?php echo apply_filters('the_title',$page_custom_menu_title); ?></h3>
            </div>
        <?php endif; ?>
        <?php if (isset($page_custom_menu_description) && !empty($page_custom_menu_description)): ?>
            <div class="sub-entry-content row">
                <?php echo apply_filters('the_content', $page_custom_menu_description); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($page_custom_menu) && !empty($page_custom_menu)): ?>
            <section id="getInvolvedLinks" class="row">
                <?php
                foreach($nav_items_ID as $nav_item_ID):

                    $the_image = get_post_meta($nav_item_ID,'menu_image',true);
                    $add_class = '';

                    if (!isset($the_image) || empty($the_image)){
                        if (has_post_thumbnail($nav_item_ID)){
                            $the_image_ID = get_post_thumbnail_id($nav_item_ID);
                            $the_image = wp_get_attachment_image_src($the_image_ID, 'menu-thumb', false);
                            $the_image = $the_image[0];
                        }
                        elseif (jetpack_has_site_logo()) {
                            $the_image = jetpack_get_site_logo($show = 'url');
                            $add_class = 'class="crop_me" ';
                        }
                        else {
                            $the_image = get_template_directory_uri() . '/css/images/singlularityu-global-logo.png';
                            $add_class = 'class="crop_me" ';
                        }
                    }
                    else {
                        $the_image = wp_get_attachment_image_src($the_image['ID'],'full',false);
                        $the_image = $the_image[0];
                    }

                    $post_data = get_post($nav_item_ID);
                    $post_content = $post_data->post_excerpt;
                    ?>
                    <?php $the_slug = basename(get_the_permalink($nav_item_ID)); ?>
                    <div class="col-sm-4 col-md-4">
                        <a <?php echo $add_class; ?>href="<?php echo get_the_permalink($nav_item_ID); ?>"><img id="<?php esc_html_e($the_slug); ?>" src="<?php echo esc_url($the_image); ?>" /><h4 class="the-title"><?php echo apply_filters('the_title',get_the_title($nav_item_ID)); ?></h4></a>
                        <span class="the-excerpt"><?php echo apply_filters('the_excerpt',$post_content); ?></span>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
        <?php if(isset($home_page_caruousel) && !empty($home_page_caruousel)): ?>
            <section id="getInvolvedLinks" class="row">
                <?php echo do_shortcode($home_page_caruousel); ?>
            </section>
        <?php endif; ?>
        <?php if (isset($home_page_map_title) && !empty($home_page_map_title)): ?>
            <div id="entry-sub-header" class="row">
                <h3 class="entry-subtitle map-section"><?php echo apply_filters('the_title',$home_page_map_title); ?></h3>
            </div>
        <?php endif; ?>
        <?php if (isset($home_page_map_description) && !empty($home_page_map_description)): ?>
            <div class="sub-entry-content map-section row">
                <?php echo apply_filters('the_content', $home_page_map_description); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($home_page_map) && !empty($home_page_map)): ?>
            <div class="sub-entry-content-map row">
                <?php echo do_shortcode($home_page_map); ?>
            </div>
        <?php endif; ?>

        <section class="row">
            <div id="communityStories" class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-0">
                <header class="section-header row">
                    <h3 class="sectiion-title">Community Stories</h3>
                </header>
                <?php //@todo move the below shortcode to a pods item ?>
                <?php echo do_shortcode('[feedzy-rss-su feeds="'.$singu_theme_settings_rss_feed.'" max="4" feed_title="no" target="_blank" meta="yes" summary="yes" summarylength="20" thumb="yes" size="270" ]'); ?>
            </div>
            <div id="sidebar" class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-offset-0 col-md-4" >
                <?php get_sidebar('home'); ?>
            </div>
        </section>

        <?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>
    </div>
</article><!-- #post-## -->