/**
 * Custom js script at post edit screen
 *
 * @package   gravityview-edit-datatables-options
 * @license   GPL2+
 * @author    Marc Gratch
 * @link      http://marcgratch.com
 * @copyright Copyright 2015, Marc Gratch
 *
 * @since 1.0.0
 *
 * globals jQuery, gvDTCGlobals, TableAPI
 */

var TableAPI;

var drawCallback = function(){
    alert('hi');
};

(function( $ ) {

    //Add the new approval dropdown to the DOM
    $('body').append('<ul id="gvdt-dropdown" class="dropdown">' +
        '<li><label><span data-status="0" class="dashicons dashicons-marker"></span> Unapproved</label></li>' +
        '<li><label><span data-status="1" class="dashicons dashicons-yes"></span> Approved</label></li>' +
        '<li><label><span data-status="2" class="dashicons dashicons-no"></span> Rejected</label></li>' +
        '</ul>');

    //init
    var approval_dd = $("#gvdt-dropdown"),
        tables = $('.gv-datatables'),
        aTable,
        lastChecked = null;

    //Prevent child element click from bubbling to parent element
    $(approval_dd).on('click',function(evt){
        evt.preventDefault();
        evt.stopPropagation();
    });

    //clicking outside the DD hides/removes it
    $(document).on('click', function(evt){
        var elem = $("td.approval_status");
        $(elem).removeClass('active');
    });


    self.whichClick = function( e ){
        return $(e.target).text();
    };

    self.getChecked = function(){
        var cb = [];
        $("input:checkbox:checked").each(function(){

            var approval_cell = $(this).parents('td').next();

            var cb_data = {
                lead_id: $(this).val(),
                x: TableAPI.cell( approval_cell ).index().columnVisible,
                y: TableAPI.cell( approval_cell ).index().row
            };

            cb.push(cb_data);
        });
        return cb;
    };

    self.processApproval = function(leads, approved, new_value ){

        console.log(approved);

        //get the data ready for ajax submission
        var data = {
            action: 'gv_bulk_update',
            leads: leads,
            form_id: gvDTCGlobals.form_id,
            approved: approved,
            nonce: gvDTCGlobals.nonce
        };

        //fire ajax
        $.post( gvDTCGlobals.ajaxurl, data)
            .success( function(){
                $.each(leads, function(){
                    if (approved === 'Delete'){
                        TableAPI.row(this.y).remove();
                    }
                    else {
                        TableAPI.cell( this.y, this.x ).data(new_value);
                    }
                });
                TableAPI.draw();
                $("input:checkbox:checked").each(function(){
                   $(this).prop('checked', false);
                });
            })
            .fail( function(){
                console.log('Woh something has gone wrong!')
            });
    };



    //just in case there are multiple tables on a page find each one and then intialize the rest
    $.each(tables, function(index,table){

        //dataTables has completed drawing
        $(table).on( 'init.dt', function () {

            aTable = $(table).dataTable();
            TableAPI = new $.fn.dataTable.Api( aTable);

            $('.dt-buttons').on('click','a', function( e ){
                var approved = self.whichClick( e );
                var leads = self.getChecked();
                var clicked_opt;

                console.log(approved);

                if (approved == "Approve"){
                    clicked_opt = 1;
                    approved = 'Approved';
                }
                else if (approved == "Reject"){
                    clicked_opt = 2;
                    approved = '0';
                }
                else if (approved == "Unapprove"){
                    clicked_opt = 0;
                    approved = '';
                }
                var new_value = "<label data-status='"+clicked_opt+"'><span class='value'>"+clicked_opt+"</span></label>";

                self.processApproval(leads, approved, new_value);
            });

            //using the dataTables API get the clicked cell index
            $(table).on('click','td.approval_status', function(evt){

                //add the approval drop down the cell
                $(this).append(approval_dd).addClass('active');
                evt.stopPropagation();
                return false;

            });

            //select an approval option
            $(approval_dd).on('click','li', function(evt){
                var approval_cell = $(approval_dd).parents('td'),
                    clicked_opt = $(this).find('span').attr("data-status"),
                    new_value = "<label data-status='"+clicked_opt+"'><span class='value'>"+clicked_opt+"</span></label>",
                    approved = '';

                var row_id = $(approval_cell).parents('tr').attr('id'),
                    lead = [{
                        lead_id: row_id.replace('lead-',''),
                        x: TableAPI.cell( approval_cell ).index().columnVisible,
                        y: TableAPI.cell( approval_cell ).index().row
                    }];

                if (clicked_opt == 0){
                    approved = '';
                }
                else if (clicked_opt == 1){
                    approved = 'Approved';
                }
                else if (clicked_opt == 2){
                    approved = 0;
                }

                //get the data ready for ajax submission
                self.processApproval(lead, approved, new_value);


                evt.stopPropagation();
                return false;
            });

            //turn on shift click for bulk row selection
            var handleChecked = function(e) {
                if(lastChecked && e.shiftKey) {

                    var cb = $('input[type="checkbox"]');
                    var i = cb.index(lastChecked);
                    var j = cb.index(e.target);
                    var checkboxes = [];
                    if (j > i) {
                        checkboxes = $('input[type="checkbox"]:gt('+ (i-1) +'):lt('+ (j-i) +')');
                    } else {
                        checkboxes = $('input[type="checkbox"]:gt('+ j +'):lt('+ (i-j) +')');
                    }

                    if (!$(e.target).is(':checked')) {
                        $(checkboxes).removeAttr('checked');
                    } else {
                        $(checkboxes).prop('checked', 'checked');
                    }
                }
                lastChecked = e.target;

                // Other click action code.

            };

            //toggle all rows on/ff
            var clickAllBoxes = function() {
                $(table).find('.headercb').toggle(
                    function() {
                        $('td.cb_col input[type=checkbox]').prop('checked', true);
                    },
                    function() {
                        $('td.cb_col input[type=checkbox]').prop('checked', false);
                    }
                );
            };
            $('td.cb_col input[type=checkbox]').click(handleChecked);
            clickAllBoxes();
        });
    });
} (jQuery) );