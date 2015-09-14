jQuery( document ).ready( function () {
	var page_column_header = '<th scope="col" id="gf_util_page" class="manage-column column-title">Page</th>';
	var form_list_table = jQuery( '#forms_form' ).find( 'table' );
	form_list_table.find( 'thead' ).find( 'tr' ).find( 'th' ).filter( ':last' ).after( page_column_header );
	form_list_table.find( 'tfoot' ).find( 'tr' ).find( 'th' ).filter( ':last' ).after( page_column_header );
	var forms_list = form_list_table.find( 'tbody' ).find( 'tr' );
	jQuery.each( forms_list, function(){
		var form_id = jQuery( this ).find( 'td.column-id' ).html();
		var on_page = false;
		var page_links = '';
		jQuery.each( gf_util_form_list.pages, function() {
			if ( this['form_id'] == form_id ) {
				on_page = true;
				page_links += '<a class="row_title" title="' + this['name'] + '" href="' + this['edit_url'] + '">' + this['name'] + '</a>';
			}
		} );
		if ( true === on_page ) {
			jQuery( this ).find( 'td' ).filter( ':last' ).after( '<td>' + page_links + '</td>' );
		}
		else {
			jQuery( this ).find( 'td' ).filter( ':last' ).after( '<td></td>' );
		}
	});
} );