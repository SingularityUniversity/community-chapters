/**
 * Custom js script loaded on Views frontend to set DataTables
 *
 * @package   GravityView
 * @license   GPL2+
 * @author    Katz Web Services, Inc.
 * @link      http://gravityview.co
 * @copyright Copyright 2014, Katz Web Services, Inc.
 *
 * @since 1.0.0
 */


(function( $ ) {



var gvDataTables = {

	init: function() {

		if( typeof $.fn.dataTable.TableTools !== 'undefined' ) {
			// - TableTools -
			// Change the default behaviour of cell content manipulation when exporting
			$.fn.dataTable.TableTools.buttons.copy.fnCellRender = gvDataTables.customfnCellRender;
			$.fn.dataTable.TableTools.buttons.csv.fnCellRender = gvDataTables.customfnCellRender;
			$.fn.dataTable.TableTools.buttons.xls.fnCellRender = gvDataTables.customfnCellRender;
			$.fn.dataTable.TableTools.buttons.pdf.fnCellRender = gvDataTables.customfnCellRender;
		}


		$('.gv-datatables').each( function( i, e ) {

			var table =  $(this).DataTable( gvDTglobals[ i ] );

			// init FixedHeader
			if( i < gvDTFixedHeaderColumns.length && gvDTFixedHeaderColumns[ i ].fixedheader.toString() === '1' ) {
				new $.fn.dataTable.FixedHeader( table );
			}
			// init FixedColumns
			if(  i < gvDTFixedHeaderColumns.length && gvDTFixedHeaderColumns[ i ].fixedcolumns.toString() === '1' ) {
				new $.fn.dataTable.FixedColumns( table );
			}


		});

	},

	/**
	 * Manipulate how TableTools format data imported from html
	 *
	 * @param sValue  (string) The value from the cell's data
	 * @param iColumn (int) - The column number being read
	 * @param nTr  (node) - The TR element for the row
	 * @param iDataIndex (int) - The internal DataTables cache index for the row (based on fnGetPosition)
	 * @returns {*}
	 */
	customfnCellRender: function ( sValue, iColumn, nTr, iDataIndex ) {

		var newValue = sValue;

		// Don't process if empty
		if( newValue.length === 0 ) {
			return newValue;
		}

		// standard behavior @see tabletools.js:_fnGetDataTablesData
		newValue = newValue.replace(/\n/g, " "); // Replace new lines with spaces

		/**
		 * Changed to jQuery in 1.2.2 to make it more consistent. Regex not always to be trusted!
		 */
		newValue = $('<span>'+newValue+'</span>') // Wrap in span to allow for $() closure
			.find('li').after('; ').end() // Separate <li></li> with ;
			.find('img').replaceWith(function() {
				return $(this).attr('alt'); // Replace <img> tags with the image's alt tag
			}).end()
			.find('br').replaceWith(' ').end() // Replace <br> with space
			.find('.map-it-link').remove().end() // Remove "Map It" link
			.text(); // Strip all tags

		return newValue;

    }


};



$(document).ready( function() {
	gvDataTables.init();
});


}(jQuery));
