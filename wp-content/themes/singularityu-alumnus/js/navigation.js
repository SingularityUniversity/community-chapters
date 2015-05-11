jQuery(document).ready(function($){
    $ = jQuery;
    if ($(window).width() < 992){
        var close_toggle = $(".menu-toggle.overlay-close");
        var menu_toggle = $(".menu-toggle #trigger-overlay");
        $(menu_toggle).click(function(e) {
            $("body").css('overflow','hidden');
            $("#site-navigation .sub-menu").hide();
        });
        $(close_toggle).click(function(e) {
            $("body").css('overflow','auto');
        });
        $('#site-navigation li a').click(function(e) {
            var is_href = $(this).attr('href');
            var parent = $(this).parent();
            var submenu = $(this).siblings(".sub-menu");

            if (is_href == "#"){
                e.preventDefault();
                $(submenu).slideToggle('slow');
                $('li').removeClass('active');
                $(parent).addClass('active');
            }
        });
    }
    else {
        $('.search input.search-field').click(function(e) {
            $("#brandingAndMenu nav ul li.search").addClass("active");
        });
        $(document).mouseup(function (event){
            var container = $("#brandingAndMenu nav ul li.search .sub-menu");
            if (container.has(event.target).length === 0){
                $(container).parent().removeClass("active");
            }
        });
    }
});
/*
jQuery(window).resize(function($){
    $ = jQuery;
    if ($(window).width() < 992){
        var close_toggle = $(".menu-toggle.overlay-close");
        var menu_toggle = $(".menu-toggle #trigger-overlay");
        $(menu_toggle).click(function(e) {
            $("body").css('overflow','hidden');
            $("#site-navigation .sub-menu").hide();
        });
        $(close_toggle).click(function(e) {
            $("body").css('overflow','auto');
        });
        $('#site-navigation li a').click(function(e) {
            var is_href = $(this).attr('href');
            var parent = $(this).parent();
            var submenu = $(this).siblings(".sub-menu");

            if (is_href == "#"){
                e.preventDefault();
                $(submenu).slideToggle('slow');
                $('li').removeClass('active');
                $(parent).addClass('active');
            }
        });
    }
});
*/