var link_btn = (function ($) {
    'use strict';
    var _link_sideload = false; //used to track whether or not the link dialogue actually existed on this page, ie was wp_editor invoked.

    /* PRIVATE METHODS
    -------------------------------------------------------------- */
    //add event listeners
    function _init() {
        $('body').on('click keypress keydown keyup', "[for='slides-link-btn']", function (event) {
            event.preventDefault();
            event.stopPropagation();

            var link_val_container = $(event.target).parents("td").find('input');

            _addLinkListeners(link_val_container);
            _link_sideload = false;

            if (typeof wpActiveEditor != 'undefined') {
                wpLink.open();
                wpLink.textarea = $(link_val_container);
            } else {
                window.wpActiveEditor = true;
                _link_sideload = true;
                wpLink.open();
                wpLink.textarea = $(link_val_container);
            }
            return false;
        });

    }

    /* LINK EDITOR EVENT HACKS
    -------------------------------------------------------------- */
    function _addLinkListeners(link_val_container) {
        $('body').on('click mouseup mousedown keypress keydown keyup', '#wp-link-submit', function (event) {
            var linkAtts = wpLink.getAttrs(),
                linktext = $("#wp-link-text").val(),
                linkTarget = linkAtts.target,
                linkTarget = ( linkTarget === '' ) ? 'target="_self"' : 'target="_blank"',
                builtHTML = '<a href="'+linkAtts.href+'" '+linkTarget+' >'+linktext+'</a>';

            link_val_container.val(builtHTML);
            _removeLinkListeners();
            return false;
        });

        $('body').on('click mouseup mousedown keypress keydown keyup', '#wp-link-cancel', function (event) {
            _removeLinkListeners();
            return false;
        });
    }

    function _removeLinkListeners() {
        if (_link_sideload) {
            if (typeof wpActiveEditor != 'undefined') {
                wpActiveEditor = undefined;
            }
        }

        wpLink.close();
        wpLink.textarea = $(this).find('input.add-link'); //focus on document

        $('body').off('click mouseup mousedown keypress keydown keyup', '#wp-link-submit');
        $('body').off('click mouseup mousedown keypress keydown keyup', '#wp-link-cancel');
    }

    /* PUBLIC ACCESSOR METHODS
    -------------------------------------------------------------- */
    return {
        init: _init
    };

})(jQuery);


    // Initialise
    jQuery(document).ready(function ($) {
        'use strict';
        var insertButton = "<label class='rs-label' for='slides-link-btn'><i class='mce-ico mce-i-link rs-custom-link-btn'></i></label>";
        jQuery("input.add-link").after(insertButton);

        link_btn.init();
    });
