/**
 *
 */
jQuery( document ).ready( function ( jQuery ) {
	jQuery( '.gfp_gfutil_toggle-all-fields-required_link' ).on( 'click', gfp_gfutil_toggle_all_fields_required );
} );

function gfp_gfutil_toggle_all_fields_required( event ) {
	event.preventDefault();
	if ( false === form['fields'][0]['isRequired'] ) {
		jQuery.each( form['fields'], gfp_gfutil_set_field_required );
		alert( gfp_gfutil_toggle_all_fields_required_vars.set );
	}
	else {
		jQuery.each( form['fields'], gfp_gfutil_unset_field_required );
		alert( gfp_gfutil_toggle_all_fields_required_vars.unset );
	}
}

function gfp_gfutil_set_field_required( index, value ) {
	value['isRequired'] = true;
}

function gfp_gfutil_unset_field_required( index, value ) {
	value['isRequired'] = false;
}