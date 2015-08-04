<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 7/23/2015
 * Time: 9:56 AM
 */

/***************************************************************
 * SECURITY : Exit if accessed directly
 ***************************************************************/
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access not allowed!' );
}


/***************************************************************
 * Main shortcode function
 ***************************************************************/
function feedzy_rss_su( $atts, $content = '' ) {

    global $feedzyStyle;
    $feedzyStyle = true;
    $count = 0;
    $i = 0;

    //Load SimplePie if not already
    if ( !class_exists( 'SimplePie' ) ){
        require_once( ABSPATH . WPINC . '/class-feed.php' );
    }

    //Retrieve & extract shorcode parameters
    extract( shortcode_atts( array(
        "feeds" => '', 			//comma separated feeds url
        "max" => '5', 			//number of feeds items (0 for unlimited)
        "feed_title" => 'yes', 	//display feed title yes/no
        "target" => '_blank', 	//_blank, _self
        "title" => '', 			//strip title after X char
        "meta" => 'yes', 		//yes, no
        "summary" => 'yes', 	//strip title
        "summarylength" => '', 	//strip summary after X char
        "thumb" => 'yes', 		//yes, no, auto
        "default" => '', 		//default thumb URL if no image found (only if thumb is set to yes or auto)
        "size" => '', 			//thumbs pixel size
        "keywords_title" => '' 	//only display item if title contains specific keywords (comma-separated list/case sensitive)
    ), $atts ) );

    if ( !empty( $feeds ) ) {
        $feeds = rtrim( $feeds, ',' );
        $feedURL = explode( ',', $feeds );

        if ( count( $feedURL ) === 1 ) {
            $feedURL = $feedURL[0];
        }

    }

    if ( $max == '0' ) {
        $max = '999';
    } else if ( empty( $max ) || !ctype_digit( $max ) ) {
        $max = '5';
    }

    if ( empty( $size ) || !ctype_digit( $size ) ){
        $size = '150';
    }
    $sizes = array( 'width' => $size, 'height' => $size );
    $sizes = apply_filters( 'feedzy_thumb_sizes', $sizes, $feedURL );

    if ( !empty( $title ) && !ctype_digit( $title ) ){
        $title = '';
    }

    if ( !empty($keywords_title)){
        $keywords_title = rtrim( $keywords_title, ',' );
        $keywords_title = array_map( 'trim', explode( ',', $keywords_title ) );
    }

    if ( !empty( $summarylength ) && !ctype_digit( $summarylength ) ){
        $summarylength = '';
    }

    if ( !empty( $default ) ) {
        $default = $default;

    } else {
        $default = get_template_directory_uri() . '/css/images/singlularityu-global-logo.png';
    }

    //Load SimplePie Instance
    $feed = new SimplePie();
    $feed -> set_feed_url( $feedURL );
    $feed -> enable_cache( true );
    $feed -> enable_order_by_date( true );
    $feed -> set_cache_class( 'WP_Feed_Cache' );
    $feed -> set_file_class( 'WP_SimplePie_File' );
    $feed -> set_cache_duration( apply_filters( 'wp_feed_cache_transient_lifetime', 7200, $feedURL ) );
    do_action_ref_array( 'wp_feed_options', array( $feed, $feedURL ) );
    $feed -> strip_comments( true );
    $feed -> strip_htmltags( array() );
    $feed -> init();
    $feed -> handle_content_type();

    if ($feed->error()) {

        $content .= '<div id="message" class="error"><p>' . __('Sorry, this feed is currently unavailable or does not exists anymore.', 'feedzy_rss_translate') . '</p></div>';

    }

    $content .= '<div class="feedzy-rss">';

    if ($feed_title == 'yes') {

        $content .= '<div class="rss_header">';
        $content .= '<h2><a href="' . $feed->get_permalink() . '" class="rss_title">' . html_entity_decode( $feed->get_title() ) . '</a> <span class="rss_description"> ' . $feed->get_description() . '</span></h2>';
        $content .= '</div>';

    }

    //$content .= '<ul>';

    //Loop through RSS feed
    $items = apply_filters( 'feedzy_feed_items', $feed->get_items(), $feedURL );
    foreach ( (array) $items as $item ) {
        $raw_description = $item->get_description();
        $link  = $item->get_permalink();
        $image = feedzy_retrieve_image($item);
        $i++;

        $continue = apply_filters( 'feedzy_item_keyword', true, $keywords_title, $item, $feedURL );

        if ( $continue == true ) {

            //Count items
            if ( $count >= $max ){
                break;
            }
            $count++;

            //Fetch image thumbnail
            if ( $thumb == 'yes' || $thumb == 'auto' ) {
                $thethumbnail = feedzy_retrieve_image( $item );
            }


            //$itemAttr = apply_filters( 'feedzy_item_attributes', $itemAttr = '', $sizes, $item, $feedURL );

            //Build element DOM
            if ($i === 1){
                $content .= "<div class='row'>";
            }

            $content .= '<div class="col-xs-6 col-sm-6 col-md-6 thumbnail rss_item">';


            /*


            if ( $thumb == 'yes' || $thumb == 'auto' ) {

                $contentThumb = '';

                if ( ( ! empty( $thethumbnail ) && $thumb == 'auto' ) || $thumb == 'yes' ){


                    if ( !empty( $thethumbnail )) {

                        $contentThumb .= "<a class='rss_image' href='".esc_url($link)."' target='_blank' rel='nofollow'><img src='".esc_url($image)."' /></a>";

                    } else if ( empty( $thethumbnail ) && $thumb == 'yes' ) {
                        if(strpos($raw_description,'iframe')){
                            $iframe = explode("</iframe>",$raw_description);
                            preg_match('/src="([^"]+)"/', $iframe[0], $match);
                            $url = $match[1];
                            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
                            //var_dump($matches);
                            $contentThumb .= do_shortcode("[video src='http://youtu.be/$matches[1]']");
                            //echo $tag[0]."</iframe>";
                        }
                        else{
                            if ( function_exists( 'jetpack_the_site_logo' ) ){
                                $contentThumb .= '<span class="default" style="width:100%; height:' . $sizes['height'] . 'px; background-image:url(' . jetpack_get_site_logo() . ');" alt="' . $item->get_title() . '"></span/>';
                            }
                            else {
                                $the_image = get_template_directory_uri() . '/css/images/singlularityu-global-logo.png';
                                $contentThumb .= '<span class="default" style="width:100%; height:' . $sizes['height'] . 'px; background-image:url(' . $the_image . ');" alt="' . $item->get_title() . '"></span/>';
                            }
                        }
                    }

                    //$contentThumb .= '</div>';

                }

                //Filter: feedzy_thumb_output
                $content .= apply_filters( 'feedzy_thumb_output', $contentThumb, $feedURL );

            }

            */


            if ( $thumb == 'yes' || $thumb == 'auto' ) {

                $contentThumb = '';

                if ( ( ! empty( $thethumbnail ) && $thumb == 'auto' ) || $thumb == 'yes' ){

                    $contentThumb .= '<div class="rss_image" style="width:100%; height:' . $sizes['height'] . 'px;">';
                    $contentThumb .= '<a href="' . $item->get_permalink() . '" target="' . $target . '" title="' . $item->get_title() . '" >';
                    if ( !empty( $thethumbnail )) {
                        $thethumbnail = feedzy_image_encode( $thethumbnail );
                        $contentThumb .= '<span class="default" style="width:100%; height:' . $sizes['height'] . 'px; background-image:  url(' . $default . ');" alt="' . $item->get_title() . '"></span/>';
                        $contentThumb .= '<span class="fetched" style="width:100%; height:' . $sizes['height'] . 'px; background-image:  url(' . $thethumbnail . ');" alt="' . $item->get_title() . '"></span/>';
                    } else if ( empty( $thethumbnail ) && $thumb == 'yes' ) {
                        if(strpos($raw_description,'iframe')){
                            $iframe = explode("</iframe>",$raw_description);
                            preg_match('/src="([^"]+)"/', $iframe[0], $match);
                            $url = $match[1];
                            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
                            $thethumbnail = "http://img.youtube.com/vi/$matches[1]/hqdefault.jpg";
                            //var_dump($matches);
                            $contentThumb .= '<span class="default" style="width:100%; height:' . $sizes['height'] . 'px; background-image:  url(' . $default . ');" alt="' . $item->get_title() . '"></span/>';
                            $contentThumb .= '<span class="fetched" style="width:100%; height:' . $sizes['height'] . 'px; background-image:  url(' . $thethumbnail . ');" alt="' . $item->get_title() . '"></span/>';
                        }
                        else{
                            if ( function_exists( 'jetpack_the_site_logo' ) ){
                                $contentThumb .= '<a href="' . $item->get_permalink() . '" target="' . $target . '" title="' . $item->get_title() . '" >';
                                $contentThumb .= '<span class="default" style="width:100%; height:' . $sizes['height'] . 'px; background-image:url(' . jetpack_get_site_logo() . ');" alt="' . $item->get_title() . '"></span/>';
                            }
                            else {
                                $contentThumb .= '<a href="' . $item->get_permalink() . '" target="' . $target . '" title="' . $item->get_title() . '" >';
                                $the_image = get_template_directory_uri() . '/css/images/singlularityu-global-logo.png';
                                $contentThumb .= '<span class="default" style="width:100%; height:' . $sizes['height'] . 'px; background-image:url(' . $the_image . ');" alt="' . $item->get_title() . '"></span/>';
                            }
                        }
                    }
                    $contentThumb .= '</a>';
                    $contentThumb .= '</div>';

                }

                //Filter: feedzy_thumb_output
                $content .= apply_filters( 'feedzy_thumb_output', $contentThumb, $feedURL );

            }

            /*

            $contentTitle = '';
            $contentTitle .= '<span class="title"><a href="' . $item->get_permalink() . '" target="' . $target . '">';

            if ( is_numeric( $title ) && strlen( $item->get_title() ) > $title ) {

                $contentTitle .= preg_replace( '/\s+?(\S+)?$/', '', substr( $item->get_title(), 0, $title ) ) . '...';

            } else {

                $contentTitle .= $item->get_title();

            }

            $contentTitle .= '</a></span>';

            */

            //Filter: feedzy_title_output
            //$content .= apply_filters( 'feedzy_title_output', $contentTitle, $feedURL );

            $content .= '<div class="rss_content">';


            //Define Meta args
            $metaArgs = array(
                'author' => false,
                'date' => true,
                'date_format' => get_option( 'date_format' ),
                'time_format' => get_option( 'time_format' )
            );

            //Filter: feedzy_meta_args
            $metaArgs = apply_filters( 'feedzy_meta_args', $metaArgs, $feedURL );

            if ( $meta == 'yes' && ( $metaArgs[ 'author' ] || $metaArgs[ 'date' ] ) ) {

                $contentMeta = '<div class="caption">';
                $contentMeta .= '<div class="story-meta"><small>' . __( 'Posted', 'feedzy_rss_translate' ) . ' ';

                if ( $item->get_author() && $metaArgs[ 'author' ] ) {

                    $author = $item->get_author();
                    if ( !$authorName = $author->get_name() ){
                        $authorName = $author->get_email();
                    }

                    if( $authorName ){
                        $domain = parse_url( $item->get_permalink() );
                        $contentMeta .= __( 'by', 'feedzy_rss_translate' ) . ' <a href="http://' . $domain[ 'host' ] . '" target="' . $target . '" title="' . $domain[ 'host' ] . '" >' . $authorName . '</a> ';
                    }

                }

                if ( $metaArgs[ 'date' ] ) {
                    $contentMeta .= __( 'on', 'feedzy_rss_translate') . ' ' . date_i18n( $metaArgs[ 'date_format' ], $item->get_date( 'U' ) ) . ' ' . __( 'at', 'feedzy_rss_translate' ) . ' ' . date_i18n( $metaArgs[ 'time_format' ], $item->get_date( 'U' ) );
                }

                $contentMeta .= '</small></div>';

                //Filter: feedzy_meta_output
                $content .= apply_filters( 'feedzy_meta_output', $contentMeta, $feedURL );

            }
            if ( $summary == 'yes' ) {


                $contentSummary = '';

                //Filter: feedzy_summary_input
                $description = $item->get_description();
                //$description = apply_filters( 'feedzy_summary_input', $description, $item->get_content(), $feedURL );
                $contentSummary_allow = strip_tags($description, "<p><a><strong><em>");

                if ( is_numeric( $summarylength ) && strlen( $contentSummary_allow ) > $summarylength )  {
                    $contentSummary .= '<div class="description">';
                    $contentSummary .= "<p>".wp_trim_words($contentSummary_allow, $summarylength )."</p>";
                    $contentSummary .= '</div>';
                } elseif (strlen( $contentSummary_allow ) == 0) {
                    $contentSummary .= "";
                } else {
                    $contentSummary .= '<div class="description">';
                    $contentSummary .= "<p>".$contentSummary_allow."</p>";
                    $contentSummary .= '</div>';
                }

                //Filter: feedzy_summary_output
                $content .= apply_filters( 'feedzy_summary_output', $contentSummary, $item->get_permalink(), $feedURL );

            }
            $content .= '</div></div>';
            $content .= '</div>';
            if ($i === 2){
                $content .= "</div>";
                $i = 0;
            }


        } //endContinue

    } //endforeach

    $content .= '</div>';

    $content .= "<a class='more-link' href='".esc_url(str_replace('rss','',$feedURL))."' target='_blank' rel='nofollow'>". __('Read More Stories &raquo;','singularityu-alumnus') ."</a>";

    return apply_filters( 'feedzy_global_output', $content, $feedURL );

}//end of feedzy_rss
add_shortcode( 'feedzy-rss-su', 'feedzy_rss_su' );

function bweb_feedzy_thumb_aspect_ratio( $sizes, $feedURL ) {
    $sizes = array(
        'width' => $sizes['width'] * (4/3),
        'height' => $sizes['height']
    );
    return $sizes;
}
add_filter( 'feedzy_thumb_sizes', 'bweb_feedzy_thumb_aspect_ratio', 10, 2 );