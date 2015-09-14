/**
 *
 */
jQuery( document ).ready( function ( jQuery ) {
	fieldSettings['html'] += ', .gfp_redact_setting';
	fieldSettings['text'] += ', .gfp_redact_setting';
	fieldSettings['website'] += ', .gfp_redact_setting';
	fieldSettings['phone'] += ', .gfp_redact_setting';
	fieldSettings['number'] += ', .gfp_redact_setting';
	fieldSettings['date'] += ', .gfp_redact_setting';
	fieldSettings['time'] += ', .gfp_redact_setting';
	fieldSettings['textarea'] += ', .gfp_redact_setting';
	fieldSettings['select'] += ', .gfp_redact_setting';
	fieldSettings['multiselect'] += ', .gfp_redact_setting';
	fieldSettings['checkbox'] += ', .gfp_redact_setting';
	fieldSettings['radio'] += ', .gfp_redact_setting';
	fieldSettings['name'] += ', .gfp_redact_setting';
	fieldSettings['address'] += ', .gfp_redact_setting';
	fieldSettings['email'] += ', .gfp_redact_setting';
	fieldSettings['post_title'] += ', .gfp_redact_setting';
	fieldSettings['post_content'] += ', .gfp_redact_setting';
	fieldSettings['post_excerpt'] += ', .gfp_redact_setting';
	fieldSettings['post_tags'] += ', .gfp_redact_setting';
	fieldSettings['post_category'] += ', .gfp_redact_setting';
	fieldSettings['post_image'] += ', .gfp_redact_setting';
	fieldSettings['list'] += ', .gfp_redact_setting';
	fieldSettings['post_custom_field'] += ', .gfp_redact_setting';


	jQuery( document ).bind( 'gform_load_field_settings', function ( event, field, form ) {
		jQuery( '#field_redact' ).prop( 'checked', field.redact ? true : false );
	} );
} );