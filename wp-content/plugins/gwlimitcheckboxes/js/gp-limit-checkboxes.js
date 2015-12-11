
( function( $ ) {

    $.fn.checkboxLimit = function( n ) {

        // ignore choices that are disabled by default by filtering only enabled inputs
        var checkboxes = this.filter( ':enabled' );

        checkboxes.toggleDisable = function() {

            // if we have reached or exceeded the limit, disable all other checkboxes
            if( checkboxes.filter( ':checked' ).length >= n ) {
                var unchecked = checkboxes.not( ':checked' );
                unchecked.prop( 'disabled', true );
            }
            // if we are below the limit, make sure all checkboxes are available
            else {
                checkboxes.prop( 'disabled', false );
            }

        };

        // when form is rendered, toggle disable
        checkboxes.bind( 'gform_post_render', function() {
            checkboxes.toggleDisable();
        } );

        // when script is loaded, toggle disable
        checkboxes.toggleDisable();

        // when checkbox is clicked, toggle disable
        checkboxes.click( function() {

            checkboxes.toggleDisable();

            // if we are equal to or below the limit, the field should be checked
            return checkboxes.filter( ':checked' ).length <= n;
        } );

    };

} )( jQuery );