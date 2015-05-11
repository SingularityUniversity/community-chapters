<?php
/**
 * Plugin Name: Validate User Email Externally
 *
 * WordPress JSON API client.
 *
 * This class is used to interact with a WordPress site using the WordPress JSON API using the WP_Http class.
 * The client authenticates using basic authentication.
 *
 * @author Marc Gratch
 */

add_filter( 'gform_validation', 'wds_validate_email_address' );

function get_all_users_for_verify() {
    $token = base64_encode('admin' . ':' . 'password');
    $users_emails = array();
    $client = Get_Users_with_JSON::create('http://data.singu.dev', $token);
    $users = $client->get_users();
    foreach ($users as $user){
        $users_emails[] = $user['email'];
    }
    return $users_emails;
}

function wds_validate_email_address( $validation_result ) {
    $form = $validation_result['form'];

    $verified_users = get_all_users_for_verify();

    // loop through form fields
    foreach( $form['fields'] as &$field ) {

        // do we have a date field with “Email” in the label
        if(
            stristr( $field['label'], 'Email' )
            && ( 'email' == $field['type'] )
        ) {
            // check to make sure $_POST data is available for the field
            if( isset( $_POST['input_' . $field['id']] ) ) {
                    $email_address = $_POST['input_'.$field['id']];

                    // Fail validation when email field is null
                    if( '' == $email_address ) {
                        $validation_result['is_valid'] = false;
                        $field['failed_validation'] = true;
                        $field['validation_message'] = 'Your Email Address is required!';
                    }

                // Are they Not Registered Users?
                if(
                    empty( $field['validation_message'] )
                    && !in_array($email_address,$verified_users)
                ) {
                    $validation_result['is_valid'] = false;
                    $field['failed_validation'] = true;
                    $field['validation_message'] = 'SingularityU Global is Currently Restricted to Alumni Members.'."\n";
                    $field['validation_message'] .= "<br>\n";
                    $field['validation_message'] .= 'If you feel you have reached this message in error, please contact our support team.';
                }

            }
        }
    }

    $validation_result['form'] = $form;
    return $validation_result;
}