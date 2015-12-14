<?php
/*
* Plugin Name:       	GravityView - Edit DataTables Options
* Plugin URI:        	http://gravityview.co/extensions/datatables/
* Description:       	This plugin is used to override the datatables data source and extend it.
* Version:          	1.0
* Author:            	Katz Web Services, Inc. , Marc Gratch
* Author URI:        	http://www.katzwebservices.com
* License:           	GPLv2 or later
* License URI: 			http://www.gnu.org/licenses/gpl-2.0.html
*/

define( 'GVDT_OPTS_PATH', plugin_dir_path(__FILE__) );
define('GVDT_OPTS_URL', plugin_dir_url(__FILE__));

remove_action('wp_ajax_gv_datatables_data','get_datatables_data');
remove_action('wp_ajax_nopriv_gv_datatables_data','get_datatables_data');
add_filter( 'gravityview_datatables_js_options', 'change_gravityview_datatables_source', 9999, 3 );
add_filter('gravityview_use_cache', '__return_false');

function change_gravityview_datatables_source( $dt_config, $view_id, $post ){

    if (!GravityView_Roles_Capabilities::has_cap('gravityforms_view_entries')){
        return false;
    }

    $return_config = $dt_config;
    $is_responsive = $dt_config['responsive'];
    $returned_data = get_view_data($view_id,$is_responsive);

    if ($returned_data === false){
        return false;
    }

    unset($return_config['ajax']);
    $return_config['serverSide'] = false;
    $return_config['data'] = $returned_data['data'];
    $return_config['rowId'] = 'DT_RowId';
    $return_config['dom'] = 'Bfrtip';
    $return_config['buttons'] = array(
        array( 'text'=> 'Approve', 'className' => 'approve-btn'),
        array( 'text'=> 'Reject', 'className' => 'reject-btn'),
        array( 'text'=> 'Unapprove', 'className' => 'unapprove-btn'),
        array( 'text'=> 'Delete', 'className' => 'delete-btn'),
    );

    $return_config['order'][0][0] = $return_config['order'][0][0] + 1;
    $columns = $return_config['columns'];
    $cb_column = array('name'=>'cb','width'=>10, 'className'=>'cb_col','orderable'=>false);
    $comment_col = array('name'=>'comments','width'=>10, 'className'=>'comment_col','orderable'=>false);
    array_unshift($columns, $cb_column);

    $temp_cols = array();

    if (isset($is_responsive) && $is_responsive == "1" || $is_responsive == true){
        $return_config['order'][0][0] = $return_config['order'][0][0] + 1;
        $blank_col = array('name'=>'spacer','width'=>10, 'orderable'=>false);
        array_unshift($columns, $blank_col);
    }

    for ($i = 0; $i < count($columns); $i++){
        if(isset($returned_data['approval_col']) && $columns[$i]['name'] == "gv_".$returned_data['approval_col']){
            $columns[$i]['className'] = 'approval_status';
        }
        elseif (in_array('gv_date_created',$columns[$i])){
            $second_sort_index = $i;
        }
        $temp_cols[] = $columns[$i];
    }
    $temp_cols[] = $comment_col;
    $return_config['columns'] = $temp_cols;

    if (isset($second_sort_index) && !empty($second_sort_index) && is_numeric($second_sort_index)){
        $return_config['order'][] = array($second_sort_index,'desc');
    }

    return $return_config;
}

function get_view_data($view_id, $is_responsive){
    global $gravityview_view;

    $view_data        = gravityview_get_current_view_data($view_id);
    $gravityview_view = new GravityView_View( $view_data );

    // Prevent error output
    ob_start();

    // Prevent emails from being encrypted
    add_filter('gravityview_email_prevent_encrypt', '__return_true' );

    // include some frontend logic
    if( class_exists('GravityView_Plugin') && !class_exists('GravityView_View') ) {
        GravityView_Plugin::getInstance()->frontend_actions();
    }

    // create the view object based on the post_id
    $GravityView_View_Data = GravityView_View_Data::getInstance( (int)$gravityview_view->post_id );

    // get the view data
    $view_data = $GravityView_View_Data->get_view( $view_id );
    $view_data['atts']['id'] = $view_id;

    $atts = $view_data['atts'];

    // Paging/offset
    $atts['offset'] = isset( $gravityview_view->paging['offset'] ) ? intval( $gravityview_view->paging['offset'] ) : 0;
    $page_size = $gravityview_view->getAtts('page_size');

    // prepare to get entries
    $atts = wp_parse_args( $atts, GravityView_View_Data::get_default_args() );

    // check if someone requested the full filtered data (eg. TableTools print button)
    if( $atts['page_size'] == '-1' ) {
        $mode = 'all';
        $atts['page_size'] = PHP_INT_MAX;
    } else {
        // regular mode - get view entries
        $mode = 'page';
    }

    $view_data['atts'] = $atts;

    $gravityview_view = new GravityView_View( $view_data );

    $paging = array(
        'offset'    => $atts['offset'],
        'page_size' => $page_size
    );

    GravityView_View::getInstance()->setPaging( $paging );


    if( class_exists( 'GravityView_Cache' ) ) {

        // We need to fetch the search criteria and pass it to the Cache so that the search is used when generating the cache transient key.
        $search_criteria = GravityView_frontend::get_search_criteria( $atts, $view_data['form_id'] );

        // make sure to allow late filter ( used on Advanced Filter extension )
        $criteria = apply_filters( 'gravityview_search_criteria', array( 'search_criteria' => $search_criteria ), $view_data['form_id'], $view_id );

        $atts['search_criteria'] = $criteria['search_criteria'];

        // Cache key should also depend on the View assigned fields
        $atts['directory_table-columns'] = !empty(  $view_data['fields']['directory_table-columns'] ) ? $view_data['fields']['directory_table-columns'] : array();

        // cache depends on user session
        if( !is_user_logged_in() ) {
            return '';
        }

        /**
         * @see wp_get_session_token()
         */
        $cookie = wp_parse_auth_cookie( '', 'logged_in' );
        $token = ! empty( $cookie['token'] ) ? $cookie['token'] : '';

        $user_session =  get_current_user_id() . '_' . $token;

        $atts['user_session'] = $user_session;

        $Cache = new GravityView_Cache( $view_data['form_id'], $atts );

        if( $output = $Cache->get() ) {

            do_action( 'gravityview_log_debug', '[DataTables] Cached output found; using cache with key '.$Cache->get_key() );

        }
    }

    if (!isset($output) || empty($output)){
        $view_entries = GravityView_frontend::get_view_entries( $atts, $view_data['form_id'] );

        // build output data
        $data = array();
        $data['form_id'] = $view_data['form_id'];
        if( isset($view_entries['count']) && $view_entries['count'] !== '0' && $view_entries['count'] !== 0 ) {

            set_time_limit(500);

            // For each entry
            foreach( $view_entries['entries'] as $entry ) {

                $entry_notes = array();

                $temp = array();

                $temp['DT_RowId'] = 'lead-'.$entry['id'];

                if ($is_responsive == "1" || $is_responsive == true){
                    $temp[] = '';
                }

                $temp[] = "<input type=\"checkbox\" name=\"lead[]\" value=\"{$entry['id']}\">";


                $notes = RGFormsModel::get_lead_notes( $entry['id'] );
                if (!empty($notes)){
                    $date = '';
                    $date_array = '';
                    $noteVal = '';
                    foreach ($notes as $note){
                        if ($note->note_type !== 'gravityview' && $note->value !== 'Approved the Entry for GravityView' && $note->value !== "Disapproved the Entry for GravityView" ) {
                            $entry_notes[] = $note;
                        } elseif ($note->note_type === 'gravityview' || $note->value === 'Approved the Entry for GravityView' || $note->value === "Disapproved the Entry for GravityView" ){
                            $dateTime = $note->date_created;
                            $index = strpos($dateTime, ' ');
                            $dateStr = substr($dateTime,0,$index);
                            $dateClass = DateTime::createFromFormat('Y-m-d',$dateStr);
                            $date_array[] = $dateClass->format('Y/m/d');
                        }
                    }
                    if (is_array($entry_notes)){
                        $last_note = end($entry_notes);
                    } else {
                        $last_note = '';
                    }
                    if ($last_note){
                        $noteVal = '';
                        $user_email = '';
                        $user_name = '';
                        $date_created = '';
                        foreach ($last_note as $key => $val){
                            $a = $key;
                            switch ($key){
                                case 'value':
                                    $noteText = $val;
                                    break;
                                case 'user_name':
                                    $user_name = $val;
                                    break;
                                case 'user_email':
                                    $user_email = $val;
                                    break;
                                case 'date_created':
                                    $date_created = $val;
                                    break;
                            }
                        }
                        $noteVal = $noteText . "<br>By: <a class='user_email' href='mailto:{$user_email}'>{$user_name}</a> <span class='sep'> | </span> <span class='date_create'>{$date_created}</span>";
                    } else {
                        $noteVal = '';
                    }
                    if (is_array($date_array)){
                        $date = end($date_array);
                    } else {
                        $date = '';
                    }
                } else {
                    $noteVal = "";
                    $date = "";
                }

                // Loop through each column and set the value of the column to the field value
                if( !empty(  $view_data['fields']['directory_table-columns'] ) ) {
                    foreach( $view_data['fields']['directory_table-columns'] as $field_settings ) {
                        if ($field_settings['label'] == 'Last Approval Status Change' || $field_settings['label'] == 'Last Approval Status Change'){
                            $temp[] = $date;
                        }
                        elseif ($field_settings['label'] == 'Approved' || $field_settings['label'] == 'Approved <small>(Approved? (Admin-only))</small>'){
                            $data['approval_col'] = $field_settings['id'];
                            $current_status = gform_get_meta($entry['id'],'is_approved');
                            if($current_status === "0"){
                                $current_status = "<label data-status='2'><span class='value'>2</span></label>";
                            }
                            elseif (!isset($current_status) || empty($current_status) || $current_status === false){
                                $current_status = "<label data-status='0'><span class='value'>0</span></label>";
                            }
                            else {
                                $current_status = "<label data-status='1'><span class='value'>1</span></label>";
                            }

                            $temp[] = $current_status;
                        }
                        else {
                            $temp[] = GravityView_API::field_value( $entry, $field_settings );
                        }
                    }
                    $temp[] = $noteVal;
                }

                // Then add the item to the output dataset
                $data['data'][] = $temp;

            }

        }
        else {
            return false;
        }

        do_action( 'gravityview_log_debug', '[DataTables] Ajax request answer', $output );

        //$json = json_encode( $data );

        if( class_exists( 'GravityView_Cache' ) ) {

            do_action( 'gravityview_log_debug', '[DataTables] Setting cache' );

            // Cache results
            $Cache->set( $data, 'datatables_output' );

        }

        // End prevent error output
        ob_end_clean();

        $output = $data ;

    }
    return $output;
}

function add_GVDT_styles(){
    global $gravityview_view;
    wp_enqueue_style('dt-btn-styles','https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css',array(),null,'all');
    wp_enqueue_style('custom-gvdt-styles',GVDT_OPTS_URL.'/assets/css/custom-gvdt-styles.css',array(),null,'all');
    wp_register_script('custom-gvdt-script',GVDT_OPTS_URL.'/assets/js/custom-gvdt-script.js',array(),null,true);
    wp_register_script('datatables-buttons','https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js',array('custom-gvdt-script'),null,true);

    $post_id = get_the_ID();
    $post_type = get_post_type();

    if($post_type == 'gravityview' || has_shortcode(get_post_field('post_content',$post_id),'gravityview')){
        $form_id  = get_post_meta($post_id,'_gravityview_form_id',true);
        $view_data        = gravityview_get_current_view_data($post_id);
        $gravityview_view = new GravityView_View( $view_data );

        $id = $gravityview_view;
    }
    if (isset($gravityview_view) && !empty($gravityview_view) && $gravityview_view !== null){
        wp_localize_script( 'custom-gvdt-script', 'gvDTCGlobals', array(
            'nonce' => wp_create_nonce( 'gravityview_ajaxgfentries'),
            'form_id' => $gravityview_view->getFormId(),
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ) );
    }
    wp_enqueue_script('custom-gvdt-script');
    wp_enqueue_script('datatables-buttons');
}
add_action( 'wp_enqueue_scripts', 'add_GVDT_styles',99999 );

function front_end_approval_value($output = '',$entry,$field_settings,$field_data){
    if (array_key_exists('gravityview_approved',$field_data['field'])){
        if($output == "0"){
            $output = "<label data-status='2'><span class='value'>2</span></label>";
        }
        elseif (!isset($output) || empty($output)){
            $output = "<label data-status='0'><span class='value'>0</span></label>";
        }
        else {
            $output = "<label data-status='1'><span class='value'>1</span></label>";
        }
            $everything = array($output,$entry,$field_settings,$field_data);
    }
    return $output;
}
//add_filter('gravityview_field_entry_value','front_end_approval_value',10,4);



function add_button_gf_entries($menu_items = array(), $id = NULL){

    $query_args = array();

    $connected_views = gravityview_get_connected_views( $id );

    if( empty( $connected_views ) ) {
        return $menu_items;
    }

    $count = count($connected_views);

    for ($i = 0; $i < $count; $i++){
        $elem = $menu_items['gravityview']['sub_menu_items'][$i];
        $parsed_url = parse_url($elem['url']);
        parse_str($parsed_url['query'], $query_args[]);
        $is_admin_view = get_post_meta($query_args[$i]['post'], 'is_admin_view', true);
        $sub_menu_items[] = $elem;
        if(isset($is_admin_view) && $is_admin_view == 'admin_view'){
            $label = empty( get_the_title($query_args[$i]['post']) ) ? sprintf( __('No Title (View #%d)', 'gravityview' ), $query_args[$i]['post'] ) : "Advanced: ".get_the_title($query_args[$i]['post']);
            $sub_menu_items[] = array(
                'label' =>  $label,
                'url' => get_permalink($query_args[$i]['post']),
                'link_class' => 'advanced_view',
                'target' => '_blank',
            );
        }
    }

    $menu_items['gravityview']['sub_menu_items'] = $sub_menu_items;

    return $menu_items;
}
add_filter( 'gform_toolbar_menu', 'add_button_gf_entries', 99, 2 );

function gv_bulk_update(){
    $approved = $_POST['approved'];
    $form_id = $_POST['form_id'];

    if ($approved !== 'Delete'){
        foreach ($_POST['leads'] as $entry){
            $entry_id = $entry['lead_id'];
            $_POST['entry_id'] = $entry_id;
            if( empty( $_POST['entry_id'] ) || empty( $_POST['form_id'] ) ) {

                do_action( 'gravityview_log_error', __METHOD__ . ' entry_id or form_id are empty.', $_POST );

                $result = false;
            }

            else if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'gravityview_ajaxgfentries' ) ) {

                do_action( 'gravityview_log_error', __METHOD__ . ' Security check failed.', $_POST );

                $result = false;
            }

            else if( ! GVCommon::has_cap( 'gravityview_moderate_entries', $_POST['entry_id'] ) ) {

                do_action( 'gravityview_log_error', __METHOD__ . ' User does not have the `gravityview_moderate_entries` capability.' );

                $result = false;
            }

            else {

                $result = GravityView_Admin_ApproveEntries::update_approved( $_POST['entry_id'], $_POST['approved'], $_POST['form_id'] );

                if( is_wp_error( $result ) ) {
                    /** @var WP_Error $result */
                    do_action( 'gravityview_log_error', __METHOD__ .' Error updating approval: ' . $result->get_error_message() );
                    $result = false;
                }

            }
        }
        exit ($result);
    }
    else {
        foreach ($_POST['leads'] as $entry){
            $entry_id = $entry['lead_id'];
            $entry = gravityview_get_entry($entry_id);
            $GravityView_Delete_Entry = new GravityView_Delete_Entry;
            $_GET['delete'] = wp_create_nonce( $GravityView_Delete_Entry->get_nonce_key($entry_id) );
            $_GET['entry_id'] = $entry_id;
            if ($GravityView_Delete_Entry->user_can_delete_entry($entry) === true){
                GFAPI::update_entry_property( $entry_id, 'status', 'trash' );
            }
        }
    }
}
add_action('wp_ajax_gv_bulk_update', 'gv_bulk_update');

function update_aproval_field( $form ) {


    foreach( $form['fields'] as &$field )  {

        //NOTE: replace 3 with your checkbox field id
        $field_id = 12;
        if ( $field->id != $field_id ) {
            continue;
        }

        $args = array(
            'post_type' => 'mg_task',
            'posts_per_page' => -1,
            'status' => 'published'
        );

        $posts = get_posts( $args );

        $input_id = 1;
        foreach( $posts as $post ) {

            //skipping index that are multiples of 10 (multiples of 10 create problems as the input IDs)
            if ( $input_id % 10 == 0 ) {
                $input_id++;
            }

            $choices[] = array( 'text' => $post->post_title, 'value' => $post->post_title );
            $inputs[] = array( 'label' => $post->post_title, 'id' => "{$field_id}.{$input_id}" );

            $input_id++;
        }

        $field->choices = $choices;
        $field->inputs = $inputs;

    }

    return $form;
}
