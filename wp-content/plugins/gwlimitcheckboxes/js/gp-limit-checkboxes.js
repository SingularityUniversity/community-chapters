
( function( $ ) {

    $.fn.checkboxLimit = function(n) {

        var checkboxes = this;

        this.toggleDisable = function() {

            // if we have reached or exceeded the limit, disable all other checkboxes
            if(this.filter(':checked').length >= n) {
                var unchecked = this.not(':checked');
                unchecked.prop('disabled', true);
            }
            // if we are below the limit, make sure all checkboxes are available
            else {
                this.prop('disabled', false);
            }

        };

        // when form is rendered, toggle disable
        checkboxes.bind('gform_post_render', checkboxes.toggleDisable());

        // when checkbox is clicked, toggle disable
        checkboxes.click(function(event) {

            checkboxes.toggleDisable();

            // if we are equal to or below the limit, the field should be checked
            return checkboxes.filter(':checked').length <= n;
        });

    };

})( jQuery );