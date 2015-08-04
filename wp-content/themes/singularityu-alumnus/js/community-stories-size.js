/**
 * Feedzy RSS thumbnail resizer.
 *
 */
function feedzy_thumb_resize() {
    var $ = jQuery;
    $(".rss_image").each(function(){
        var el = $(this);
        var default_image = el.find('.default');
        var fetched_image = el.find('.fetched');
        var height = el.width() * (3/4);
        el.height(height);
        default_image.height(height);
        fetched_image.height(height);
    });
}

jQuery(document).ready(function ($) {
    feedzy_thumb_resize();
});

jQuery(window).resize(function( $ ) {
    feedzy_thumb_resize();
});