<?php global $rcp_login_form_args; ?>
<?php $is_there_content = get_the_content(); ?>
<div id="loginForm">
    <div id="loginWrapper" class="row">
        <?php if( ! is_user_logged_in() ) : ?>

            <?php if (!empty($is_there_content)): ?>

                <div class="col-xs-12 col-xs-offset-0 col-sm-7 col-sm-offset-0 col-md-7 col-md-offset-0">
                    <?php the_content(); ?>
                </div>

                <div id="theLoginForm" class="col-xs-12 col-xs-offset-0 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1">

            <?php else: ?>

                <div id="theLoginForm" class="col-xs-12 col-xs-offset-0 col-sm-12 col-sm-offset-0 col-md-12 col-md-offset-0">

            <?php endif; ?>
                    <?php rcp_show_error_messages( 'login' ); ?>

                    <h3><?php echo __('Log In Using Email','singularityu-alumnus'); ?></h3>

                    <form id="rcp_login_form"  class="rcp_form form-horizontal" method="POST" action="<?php echo esc_url( rcp_get_current_url() ); ?>">
                    <fieldset class="rcp_login_data">

                        <div class="form-group">
                            <div class="input-group margin-bottom-sm col-md-12">
                                <input name="rcp_user_login" class="required form-control" type="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group col-md-12">
                                <input name="rcp_user_pass" type="password" class="form-control required" id="rcp_user_pass"  placeholder="Enter Password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div for="rcp_user_remember" class="checkbox">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="rcp_user_remember" id="rcp_user_remember" value="1" /> <?php _e( 'Remember Me?', 'rcp' ); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="rcp_lost_password"><a href="<?php echo esc_url( wp_lostpassword_url( rcp_get_current_url() ) ); ?>"><?php _e( 'Lost your password?', 'rcp' ); ?></a></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group col-md-12">
                                <input class="btn" type="hidden" name="rcp_action" value="login"/>
                                <input type="hidden" name="rcp_redirect" value="<?php echo esc_url(get_query_var('redirect_to')); ?>" />
                                <input type="hidden" name="rcp_login_nonce" value="<?php echo wp_create_nonce( 'rcp-login-nonce' ); ?>"/>
                                <input class="btn" id="rcp_login_submit" type="submit" value="Login"/>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        <?php else : ?>
            <div class="jumbotron">
                <h1><?php _e( "You are already logged in.", 'singularityu-alumnus' ); ?></h1>
                <p><?php _e( "We hate to see you go, but if it's time to leave:", 'singularityu-alumnus' ); ?></p>
                <p<span class="rcp_logged_in"><a class="btn" href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Logout', 'rcp' ); ?></a></span></p>
            </div>
        <?php endif; ?>
    </div>
</div>