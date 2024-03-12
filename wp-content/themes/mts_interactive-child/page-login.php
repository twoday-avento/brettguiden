<?php
get_header(); ?>
<!-- section -->
<div id="page">
    <div class="sidebar-with-content">
        <div class="article">
            <section class="aa_loginForm">
                <?php
                global $user_login;
                // In case of a login error.
                if (isset($_GET['login']) && $_GET['login'] == 'failed') : ?>
                    <div class="aa_error">
                        <p><?php _e('FAILED: Try again!'); ?></p>
                    </div>
                    <?php
                endif;
                // If user is already logged in.
                if (is_user_logged_in()) : ?>
                    <div class="aa_logout">
                        <?php
                        _e('Hello');
                        echo ' '.$user_login;
                        ?>
                        </br>
                        <?php _e('You are already logged in.'); ?>
                    </div>
                    <a id="wp-submit" href="<?php echo get_permalink('4884') ?>" title="Logout">
                        <?php _e('Logut'); ?>
                    </a>
                    <?php
                // If user is not logged in.
                else:

                    // Login form arguments.
                    $args = array(
                        'echo' => true,
                        'redirect' => home_url('/kritikker/'),
                        'form_id' => 'loginform',
                        'label_username' => __('Username'),
                        'label_password' => __('Password'),
                        'label_remember' => __('Remember Me'),
                        'label_log_in' => __('Log In'),
                        'id_username' => 'user_login',
                        'id_password' => 'user_pass',
                        'id_remember' => 'rememberme',
                        'id_submit' => 'wp-submit',
                        'remember' => true,
                        'value_username' => NULL,
                        'value_remember' => true
                    );

                    // Calling the login form.
                    wp_login_form($args);
                endif;
                ?>
            </section>
        </div>
    </div>
    <!-- /section -->
    <?php get_sidebar(); ?></div>
<?php get_footer(); ?>
