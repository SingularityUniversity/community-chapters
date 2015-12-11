<?php
/*
* Plugin Name: GravityView - Use Alternate DataTables Sources
* Description: Use CDNJS.com sources for the DataTables library
* Version: 1.0
* Author: Katz Web Services, Inc.
* Author URI: https://gravityview.co
*/

class Replace_GV_DataTables_Scripts {

    public static function init() {
        new Replace_GV_DataTables_Scripts;
    }

    function __construct() {

        add_filter('gravityview_datatables_script_src', array( $this, 'datatables_js') );
        add_filter('gravityview_datatables_style_src', array( $this, 'datatables_css') );

        add_filter('gravityview_dt_tabletools_script_src', array( $this, 'tabletools_js' ) );
        add_filter('gravityview_dt_tabletools_style_src', array( $this, 'tabletools_css' ) );

    }

    function datatables_js() {
        if (WP_DEBUG == true){
            return '//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/js/jquery.dataTables.js';
        }
        else{
            return '//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/js/jquery.dataTables.min.js';
        }
    }

    function datatables_css() {
        return '//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/css/jquery.dataTables.min.css';
    }

    function tabletools_js() {
        return '//cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.5/js/TableTools.min.js';
    }

    function tabletools_css() {
        return '//cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.5/css/TableTools.css';
    }
}

add_action( 'init', 'Replace_GV_DataTables_Scripts::init' );