<?php

/**
* Plugin Name: GP Email Users
* Description: Send a quick email to all users who have submitted a specific form.
* Plugin URI: http://gravitywiz.com/
* Version: 1.3.1
* Author: David Smith
* Author URI: http://gravitywiz.com/
* License: GPL2
* Perk: True
*/

/**
* Saftey net for individual perks that are active when core Gravity Perks plugin is inactive.
*/
$gw_perk_file = __FILE__;
if(!require_once(dirname($gw_perk_file) . '/safetynet.php'))
    return;

class GWEmailUsers extends GWPerk {

    public static $version_info;
    public static $email_errors;
    public static $current_emails = array(); // used to prevent duplicates from being sent if 'gpeu_send_to_duplicates' filter is returned false

    function init() {

        // add permissions
        self::add_permissions();
        if(function_exists('members_get_capabilities'))
            add_filter('members_get_capabilities', array('GWEmailUsers', 'members_get_capabilities'));

        add_filter('gform_addon_navigation', array(&$this, 'create_menu'));

        add_action('wp_ajax_gweu_get_form_data', array(&$this, 'ajax_get_form_data'));
        add_action('wp_ajax_gweu_send_email', array(&$this, 'ajax_send_email'));

        $this->enqueue_script(array('handle' => 'gwp-common', 'pages' => 'gwp_email_users'));
        
    }

    public function create_menu($menus) {

        $permission = self::has_access('gravityperks_gwemailusers');
        if($permission)
            $menus[] = array('name' => 'gwp_email_users', 'label' => __('Email Users', 'gravityperks'), 'callback' =>  array(&$this, 'email_users_page'), 'permission' => $permission);

        return $menus;
    }

    public function email_users_page() {

        require_once(GFCommon::get_base_path() . '/notification.php');
        add_action('media_buttons', array('GFNotification', 'media_buttons'), 40);

        $form_id = 0;
        $email_options = array();
        self::$email_errors = array();

        if(rgpost('is_submit')) {

            $form_id = $email_options['form_id'] = rgpost('form_id');
            $form = RGFormsModel::get_form_meta($form_id);

            $email_options['to'] = rgpost('email_to');
            $email_options['from'] = rgpost('email_from');
            $email_options['from_name'] = rgpost('email_from_name');
            $email_options['reply_to'] = rgpost('email_reply_to');
            $email_options['bcc'] = rgpost('email_bcc');
            $email_options['subject'] = rgpost('email_subject');
            $email_options['message'] = rgpost('email_message');

            // check for empty required fields
            foreach(array('to', 'subject', 'message') as $key) {
                if(!rgar($email_options, $key))
                    self::$email_errors[$key] = __('This field is required.');
            }

            // check for valid emails
            foreach(array('reply_to', 'from') as $key) {
                $email = rgar($email_options, $key);
                if($email && !GFCommon::is_valid_email($email))
                    self::$email_errors[$key] = __('Please enter valid emails.');
            }

        }

        $form = $form_id ? RGFormsModel::get_form_meta($form_id) : false;

        ?>

        <style type="text/css">
        .wrap h2 { margin: 0 0 18px; }
        #settings { margin: 18px 0; }
            #settings input.button { margin-left: 130px; }

        .page-content label { display: block; font-weight: bold; width: 120px; float: left; line-height: 26px; }
        .page-content textarea { display: block; width: 75%; height: 200px; }
        .page-content option { padding-right: 10px; }

        .setting { margin: 0 0 10px; border-bottom: 1px dotted #eee; padding: 0 10px 10px; }
            .setting.form-setting { border: 1px solid #eee; border-width: 1px 0; background-color: #f7f7f7; padding: 10px; }
        .setting input { width: 240px; }
            .setting.email_message { border-bottom: 0; }
            .setting select { min-width: 240px !important; }
            .setting .validation_message { color: #900; font-style: italic; margin-left: 120px; }

        #email_message_variable_select { display: block; margin: 0 0 2px; }
        #email_message { margin-left: 120px; }

        .sending { }
        </style>

        <div class="wrap">

            <div id="gravity-edit-icon" class="icon32" style="background-image:url(<?php echo GFCommon::get_base_url(); ?>/images/gravity-notification-icon-32.png);"><br></div>
            <h2><?php _e('Email Users', 'gravityperks'); ?></h2>

            <?php if(rgget('debug')): ?>
                <div class="error"><p>
                    <?php _e('You\'re in debug mode. This allows you to test sending emails without actually sending them.', 'gravityperks'); ?>
                </p></div>
            <?php endif; ?>

            <?php if(rgpost('is_submit') && empty(self::$email_errors)) {
                $emails = self::prepare_emails($email_options);
                ?>

                <div class="updated sending"><p>
                    <span class="count"><span class="current">0</span> / <?php echo count($emails); ?> sent.</span>
                </p></div>

                <script type="text/javascript">

                function sendEmails() {

                    var emails = <?php echo json_encode($emails); ?>;
                    var count = 0;

                    var spinner = new gperk.ajaxSpinner('.sending .count', '<?php echo $this->get_base_url(); ?>/images/ajax-loader.gif');

                    for(i in emails) {
                        jQuery.post(ajaxurl, {
                            email: emails[i],
                            debug: '<?php echo rgget('debug'); ?>',
                            action: 'gweu_send_email'
                        }, function(response){
                            if(response) {
                                count++;
                                jQuery('.sending .current').text(count);
                                if(emails.length == count) {
                                    spinner.destroy();
                                    jQuery('.sending p').html('<strong>All done!</strong>');
                                }
                            }
                        });
                    }

                }

                sendEmails();

                </script>
                <?php
                return;
            } ?>

            <form method="post" id="email_form" name="form_email">

                <input type="hidden" name="is_submit" value="1" />

                <div class="page-content">

                    <div class="setting form-setting">

                        <label for="form_id">Form</label>

                        <select name="form_id" id="form_id" onchange="gweuSelectForm(this);">
                            <option value="">Select a Form</option>
                            <?php
                            $forms = RGFormsModel::get_forms();
                            foreach($forms as $form_obj):
                                $selected = $form_obj->id == $form_id ? 'selected="selected"' : '';
                                ?>

                                <option value="<?php echo $form_obj->id; ?>" <?php echo $selected; ?>><?php echo $form_obj->title; ?></option>

                            <?php endforeach; ?>
                        </select>

                    </div>

                    <div id="notice" class="" style="display:none;"></div>

                    <div id="settings" <?php self::echo_if('style="display:none;"', $form_id, false); ?>>

                        <div class="email_to setting">
                            <?php if($form_id) {
                                echo self::get_email_select_ui($form, 'email_to', 'to', __('Email To', 'gravityperks'), rgar($email_options, 'to'), true);
                            } ?>
                        </div> <!-- / to -->

                        <div class="email_from setting">

                            <label for="email_from">
                                <?php _e('From Email', 'gravityforms'); ?>
                            </label>

                            <input type="text" value="<?php echo rgar($email_options, 'from'); ?>" id="email_from" name="email_from" />

                            <?php if(isset(self::$email_errors['from'])) { ?>
                                <div class="validation_message"><?php _e('Please enter a valid email.', 'gravityperks') ?></div>
                            <?php } ?>

                        </div> <!-- / from email -->

                        <div class="email_from_name setting">

                            <label for="email_from_name">
                                <?php _e('From Name', 'gravityforms'); ?>
                            </label>

                            <input type="text" value="<?php echo rgar($email_options, 'from_name'); ?>" id="email_from_name" name="email_from_name" />

                        </div> <!-- / from email name -->

                        <div class="email_reply_to setting">

                            <label for="email_reply_to">
                                <?php _e('Reply To', 'gravityforms'); ?>
                            </label>

                            <input type="text" value="<?php echo rgar($email_options, 'reply_to'); ?>" id="email_reply_to" name="email_reply_to" />

                            <?php if(isset(self::$email_errors['reply_to'])) { ?>
                                <div class="validation_message"><?php _e('This email is invalid.', 'gravityperks') ?></div>
                            <?php } ?>

                        </div> <!-- / from reply to -->

                        <div class="email_subject setting">

                            <label for="email_subject">
                                <?php _e('Subject', 'gravityforms'); ?> <span class="gfield_required">*</span>
                            </label>

                            <input type="text" value="<?php echo rgar($email_options, 'subject'); ?>" id="email_subject" name="email_subject" />

                            <?php if(isset(self::$email_errors['subject'])) { ?>
                                <div class="validation_message"><?php _e('Please enter a subject for the email.', 'gravityperks') ?></div>
                            <?php } ?>

                        </div> <!-- / from reply to -->

                        <div class="email_bcc setting">
                            <?php if($form_id) {
                                echo self::get_email_select_ui($form, 'email_bcc', 'bcc', __('BCC', 'gravityperks'), rgar($email_options, 'bcc'));
                            } ?>
                        </div> <!-- / from reply to -->

                        <div class="email_message setting">
                            <?php if($form_id) {
                                echo self::get_email_message_ui($form, rgar($email_options, 'message'));
                            } ?>
                        </div> <!-- / message -->

                        <input type="submit" value="Send Email" class="button button-primary" />

                    </div>

                </div>

            </form>

        </div>

        <script type="text/javascript">

        function gweuSelectForm(elem) {

            var formId = jQuery(elem).val();

            if(!formId)
                return;

            jQuery.post(ajaxurl, {
                form_id: formId,
                action: 'gweu_get_form_data'
            }, function(response){

                var response = jQuery.parseJSON(response);

                if(typeof response['error'] != 'undefined') {
                    toggleNotice('<p>' + response['error'] + '</p>');
                    toggleSettings(false);
                } else {
                    toggleNotice(false);
                    toggleSettings(true, response);
                }

            });

        }

        function toggleNotice(message) {

            var notice = jQuery('#notice');

            if(message === false) {
                notice.slideUp();
            } else {
                notice.html(message).removeClass('updated error').addClass('error').slideDown();
            }

        }

        function toggleSettings(open, options) {

            var settings = jQuery('#settings');

            if(typeof open == 'undefined') {
                settings.slideToggle();
            } else if(open) {
                jQuery('.email_message').html(options['message_ui']);
                jQuery('.email_to').html(options['to_ui']);
                jQuery('.email_bcc').html(options['bcc_ui']);
                settings.slideDown();
            } else {
                settings.slideUp();
            }

        }

        function InsertVariable(element_id, callback, variable){
            if(!variable)
                variable = jQuery('#' + element_id + '_variable_select').val();

            var messageElement = jQuery("#" + element_id);

            if(document.selection) {
                // Go the IE way
                messageElement[0].focus();
                document.selection.createRange().text=variable;
            }
            else if(messageElement[0].selectionStart) {
                // Go the Gecko way
                obj = messageElement[0]
                obj.value = obj.value.substr(0, obj.selectionStart) + variable + obj.value.substr(obj.selectionEnd, obj.value.length);
            }
            else {
                messageElement.val(variable + messageElement.val());
            }

            jQuery('#' + element_id + '_variable_select')[0].selectedIndex = 0;


            if(callback && window[callback]){
                window[callback].call(null, element_id, variable);
            }
        }

        </script>

        <?php
    }

    public function ajax_get_form_data() {

        $form_id = rgpost('form_id');
        $form = RGFormsModel::get_form_meta($form_id);

        if(count(GFCommon::get_fields_by_type($form, array('email'))) < 1) {
            echo json_encode(array('error' => __('Oops! This form does not have any email fields.')));
            die();
        }

        $to_ui = self::get_email_select_ui($form, 'email_to', 'to', __('Email To', 'gravityperks'), false, true);
        $message_ui = self::get_email_message_ui($form);
        $bcc_ui = self::get_email_select_ui($form, 'email_bcc', 'bcc', __('BCC', 'gravityperks'));

        echo json_encode(array('form' => $form, 'message_ui' => $message_ui, 'to_ui' => $to_ui, 'bcc_ui' => $bcc_ui));
        die();
    }

    public function get_email_select_ui($form, $id = '', $key = '', $label = '', $value = '', $required = false) {
        ob_start();
        $email_fields = GFCommon::get_fields_by_type($form, array('email'));
        ?>

        <label for="<?php echo $id; ?>">
            <?php echo $label; ?> <?php if($required): ?><span class="gfield_required">*</span><?php endif; ?>
        </label>

        <select name="<?php echo $id; ?>" id="<?php echo $id; ?>">
            <option value=""><?php _e('Select an Email Field', 'gravityperks'); ?></option>
            <?php foreach($email_fields as $email_field):
                $selected = $email_field['id'] == $value ? 'selected="selected"' : '';
                ?>
                <option value="<?php echo $email_field['id']; ?>" <?php echo $selected; ?>><?php echo $email_field['label']; ?></option>
            <?php endforeach; ?>
        </select>

        <?php if(isset(self::$email_errors[$key])) { ?>
            <div class="validation_message"><?php _e('This is a required field.', 'gravityperks') ?></div>
        <?php } ?>

        <?php
        return ob_get_clean();
    }

    public function get_email_message_ui($form, $value = false) {
        ob_start();
        ?>

        <label for="email_message">
            <?php _e('Message', 'gravityforms'); ?> <span class="gfield_required">*</span>
        </label>

        <?php GFCommon::insert_variables($form['fields'], 'email_message', false); ?>

        <textarea name="email_message" id="email_message"><?php echo $value; ?></textarea>

        <?php if(isset(self::$email_errors['message'])) { ?>
            <div class="validation_message"><?php _e('Please enter a message for the email.', 'gravityperks') ?></div>
        <?php } ?>

        <?php
        return ob_get_clean();
    }

    public function prepare_emails($email_options) {

        $form = RGFormsModel::get_form_meta(rgar($email_options, 'form_id'));

        if(!$form)
            return array();

        $leads = RGFormsModel::get_leads($form['id'], 0, 'desc', '', 0, 999);
        $emails = array();

        $send_duplicates = apply_filters( 'gpeu_send_to_duplicates', true, $form, $leads, $email_options );

        foreach($leads as $lead) {

            $options = array();
            foreach($email_options as $key => $option) {
                if(in_array($key, array('to', 'bcc'))) {
                    $options[$key] = rgar($lead, rgar($email_options, $key));
                } else {
                    $options[$key] = GFCommon::replace_variables($option, $form, $lead);
                }
            }

            extract($options);

            if( ! $send_duplicates ) {
                if( in_array( $to, self::$current_emails ) ) {
                    continue;
                } else {
                    self::$current_emails[] = $to;
                }
            }

            $content_type = "text/html";

            $name = empty($from_name) ? $from : $from_name;
            $headers = "From: \"$name\" <$from> \r\n";
            $headers .= GFCommon::is_valid_email($reply_to) ? "Reply-To: $reply_to\r\n" :"";
            $headers .= GFCommon::is_valid_email($bcc) ? "Bcc: $bcc\r\n" :"";
            $headers .= "Content-type: {$content_type}; charset=" . get_option('blog_charset') . "\r\n";

            $emails[] = compact('to', 'subject', 'message', 'headers');

        }

        return $emails;
    }

    public function ajax_send_email() {

        $email = rgpost('email');
        extract($email);

        // if not in debug mode, actually send email
        if(rgpost('debug')) {
            $result = true;
        } else {
            $result = wp_mail($to, $subject, $message, $headers);
        }

        echo $result;
        die();
    }

    public static function add_permissions() {
        global $wp_roles;

        return;

        if(!is_object($wp_roles))
            return;

        $wp_roles->add_cap('administrator', 'gravityperks_gwemailusers');
        $wp_roles->add_cap('administrator', 'gravityperks_gwemailusers');
    }

    public static function members_get_capabilities( $caps ) {
        return array_merge($caps, array('gravityperks_gwemailusers'));
    }

    public static function has_access($required_permission){
        $has_members_plugin = function_exists('members_get_capabilities');
        $has_access = $has_members_plugin ? current_user_can($required_permission) : current_user_can('administrator');

        if($has_access) {
            return $has_members_plugin ? $required_permission : 'administrator';
        } else {
            return false;
        }
    }

    public function documentation() {
        ob_start();
        ?>

# What does it do?

The **Email Users** perk allows you to send an email to users that have filled out a form that contains an email field.


# How does it work?

It pulls all email addresses from a form's lead data then displays default email fields for you to fill out. It makes use of the
native wordpress function <code>wp_mail();</code> so you need to make sure your server has a mailing system setup. 

If **GP Email Users** 
is reporting that everything is ok but your emails are not being sent successfully, the first step contact your hosting provider 
and confirm that there are no issues with your server's mail configuration.

# How do I enable this functionality?

Create a form and add at least one email field within the form. After you have at least one sumbission on this form, you 
can navigate to the [GP Email Users](<?php echo admin_url('admin.php?page=gwp_email_users'); ?>) admin page.  On this page,
select the form that you wish to retrieve the emails from. Additional options will be loaded dynamically.

After you have configured the email you would like to send, press "Send Email". You will be able to watch the progress as
each email is sent.

![Email Users Admin Page](<?php echo $this->get_base_url(); ?>/images/admin-page.png)


# Anything else I need to know?

Nope! That's pretty much it. If you have any questions on this functionality or just want to say how much you love it, make sure you
come back to [GravityWiz.com](<?php echo $this->data['AuthorURI'] ?>) and leave us a comment.

        <?php
        return ob_get_clean();
    }

}

?>