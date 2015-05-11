
/**
 * GP Reload Form Front-end JS
 */

window.GPReloadForm;

( function( $ ) {

    gwrf = GPReloadForm = function( args ) {

        var self = this;

        self.formId         = args.formId;
        self.spinnerUrl     = args.spinnerUrl;
        self.refreshTime    = args.refreshTime;
        self.refreshTimeout = null;

        self.formWrapper = $( '#gform_wrapper_' + self.formId );
        self.staticElem  = self.formWrapper.parent();
        self.formHtml    = self.staticElem.html(); //$( '<div />' ).append( self.formWrapper.clone() ).html();
        self.spinnerInitialized = false;

        if( self.staticElem.data( 'gwrf' ) ) {
            return self.staticElem.data( 'gwrf' );
        }

        self.init = function() {

            $( document ).bind( 'gform_confirmation_loaded', function( event, formId ) {

                if( formId != self.formId || self.refreshTime <= 0 || self.staticElem.find( '.form_saved_message' ).length > 0 ) {
                    return;
                }

                self.refreshTimeout = setTimeout( function() {
                    self.reloadForm();
                }, self.refreshTime * 1000 );

            } );

            self.staticElem.on( 'click', 'a.gws-reload-form', function( event ) {
                event.preventDefault();
                self.reloadForm();
            } );

            self.staticElem.data( 'gwrf', self );

        };

        self.reloadForm = function() {

            if( self.refreshTimeout ) {
                clearTimeout( self.refreshTimeout );
            }

            self.staticElem.html( self.formHtml );

            window[ 'gf_submitting_' + self.formId ] = false;

            $( document ).trigger( 'gform_post_render', [ self.formId, 0 ] );

            if( window['gformInitDatepicker'] ) {
                gformInitDatepicker();
            }

        };

        self.initSpinner = function(){

            //self.staticElem.on( 'submit', '#gform_' + self.formId, function() {
            //    $( '#gform_submit_button_' + self.formId ).attr( 'disabled', true ).after( '<' + 'img id=\"gform_ajax_spinner_' + self.formId + '\"  class=\"gform_ajax_spinner\" src=\"' + self.spinnerUrl + '\" />' );
            //    self.formWrapper.find( '.gform_previous_button' ).attr( 'disabled', true );
            //    self.formWrapper.find( '.gform_next_button' ).attr( 'disabled', true ).after( '<' + 'img id=\"gform_ajax_spinner_' + self.formId + '\"  class=\"gform_ajax_spinner\" src=\"' + self.spinnerUrl + '\" alt=\"\" />' );
            //});

        };

        self.init();

    };

} )( jQuery );