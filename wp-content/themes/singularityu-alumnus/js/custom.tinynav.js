jQuery(document).ready(function($){

    "use strict";

    var $ = jQuery;

    $("<select />").insertAfter("#secondary .widget_nav_menu:last-child");

    // Populate dropdown with menu items
    $("#secondary .widget_nav_menu a").each(function() {
        var el = $(this);
        var parent = el.parent("li");
        var li_class = parent.attr("class");

        // Create default option "Go to..."
        if (/current-menu-item/i.test(li_class)){
            $("<option />", {
                "selected": "selected",
                "value"   : "",
                "text"    : el.text()
            }).appendTo("#secondary .widget_nav_menu + select");
        }
        else{
            $("<option />", {
                "value"   : el.attr("href"),
                "text"    : el.text()
            }).appendTo("#secondary .widget_nav_menu + select");
        }
    });

    $("#secondary .widget_nav_menu + select").change(function() {
        window.location = $(this).find("option:selected").val();
    });
});