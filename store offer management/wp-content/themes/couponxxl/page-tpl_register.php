<?php
/*
    Template Name: Register
*/
if( is_user_logged_in() ){
    wp_redirect( home_url() );
}
get_header();
the_post();
get_template_part( 'includes/title' );

$account = get_query_var( 'account' );
$confirmation_hash = get_query_var( 'confirmation_hash' );
$username = get_query_var( 'username' );

$message = '';
$success = false;

if( !empty( $confirmation_hash ) ){
    $username = esc_sql( $username );
    $user = get_user_by( 'login', $username );
    if( !empty( $user ) ){
        $confirmation_hash = get_user_meta( $user->ID, 'confirmation_hash', true );
        if( !empty( $confirmation_hash ) && $confirmation_hash == $confirmation_hash ){
            update_user_meta( $user->ID, 'user_active_status', 'active' );
            $message = '<div class="alert alert-success">'.esc_html__( 'Thank you for confirming your email. Now you can proceed to login.', 'couponxxl' ).'</div>';
        }
        else{
            $message = '<div class="alert alert-danger">'.esc_html__( 'Wrong confirmation hash.', 'couponxxl' ).'</div>';
        }
    }
    else{
        $message = '<div class="alert alert-danger">'.esc_html__( 'There is no user with that username.', 'couponxxl' ).'</div>';
    }
    $success = true;
}
?>
<?php if( $success ): ?>
<section>
    <div class="container">
        <?php echo  $message; ?>
    </div>
</section>
<?php endif; ?>
<?php if( get_option('users_can_register') ): ?>
<section>
    <div class="container">
        <?php if( empty( $account ) ): ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="white-block">
                        <div class="white-block-content">
                            <div class="register-icon">
                                <i class="pline-bag"></i>
                            </div>
                            <h3><?php esc_html_e( 'I\'m', 'couponxxl' ); ?> <strong><?php esc_html_e( 'Buyer', 'couponxxl' ) ?></strong></h3>
                            <p><?php esc_html_e( 'I would like to purchase deals, I do not wanna submit and sell them.', 'couponxxl' ) ?></p>
                            <a href="<?php echo esc_url( couponxxl_append_query_string( '', array( 'account' => 'buyer' ) ) ) ?>" class="btn"><?php esc_html_e( 'Register as Buyer', 'couponxxl' ) ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="white-block">
                        <div class="white-block-content">
                            <div class="register-icon">
                                <i class="pline-wallet"></i>
                            </div>
                            <h3><?php esc_html_e( 'I\'m', 'couponxxl' ); ?> <strong><?php esc_html_e( 'Vendor', 'couponxxl' ) ?></strong></h3>
                            <p><?php esc_html_e( 'I would like to sell deals and to open store here.', 'couponxxl' ) ?></p>
                            <a href="<?php echo esc_url( couponxxl_append_query_string( '', array( 'account' => 'vendor' ) ) ) ?>" class="btn"><?php esc_html_e( 'Register as Vendor', 'couponxxl' ) ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif( !empty( $account ) ):?>
            <?php
            $content = get_the_content();
            if( !empty( $content ) ):
            ?>
                <div class="clearfix">
                    <?php the_content() ?>
                </div>
            <?php endif; ?>
            <div class="white-block">
                <div class="white-block-content">
                    <form method="post" action="<?php echo esc_url( couponxxl_append_query_string( '', array( 'account' => $account ) ) ) ?>">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="white-block-content">
                                <?php
                                    if( $account == 'vendor' ){
                                        ?>
                                        <div class="register-icon">
                                            <i class="pline-wallet"></i>
                                        </div>
                                        <p><?php esc_html_e( 'I would like to sell deals and to open store here.', 'couponxxl' ) ?></p>
                                        <?php
                                    }
                                    else{
                                        ?>
                                        <div class="register-icon">
                                            <i class="pline-bag"></i>
                                        </div>
                                        <p><?php esc_html_e( 'I would like to purchase deals, I do not wanna submit and sell them.', 'couponxxl' ) ?></p>
                                        <?php
                                    }
                                ?>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <label for="first_name"><?php esc_html_e( 'First Name', 'couponxxl' ); ?> <span class="required">*</span></label>
                                            <input type="text" name="first_name" id="first_name" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input your first name', 'couponxxl' ); ?>">
                                            <i class="pline-user"></i>
                                        </div>
                                        <div class="input-group">
                                            <label for="username"><?php esc_html_e( 'Username', 'couponxxl' ); ?> <span class="required">*</span></label>
                                            <input type="text" name="username" id="username" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input desired username', 'couponxxl' ); ?>">
                                            <i class="pline-user"></i>
                                        </div>
                                        <div class="input-group">
                                            <label for="password"><?php esc_html_e( 'Password', 'couponxxl' ); ?> <span class="required">*</span></label>
                                            <input type="password" name="password" id="password" class="form-control" data-validation="required|match" data-match="repeat_password" data-error="<?php _e( 'Passwords do not match', 'couponxxl' ); ?>">
                                            <i class="pline-lock-locked"></i>
                                        </div>
                                        <?php $register_terms = couponxxl_get_option( 'register_terms');
                                        if( !empty( $register_terms ) ):?>
                                            <div class="input-group">
                                                <div class="checkbox checkbox-inline">
                                                    <input type="checkbox" name="terms" id="terms" data-validation="checked" data-error="<?php _e( 'You must read and accept terms in order to be able to register on site', 'couponxxl' ); ?>">
                                                    <label for="terms"><?php esc_html_e( 'I accept ', 'couponxxl' ); ?> <a href="<?php echo esc_url( $register_terms ) ?>" target="_blank"><?php esc_html_e( 'Terms & Conditions', 'couponxxl' ) ?></a></label>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <label for="last_name"><?php esc_html_e( 'Last Name', 'couponxxl' ); ?> <span class="required">*</span></label>
                                            <input type="text" name="last_name" id="last_name" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input your last name', 'couponxxl' ); ?>">
                                            <i class="pline-user"></i>
                                        </div>                    
                                        <div class="input-group">
                                            <label for="email"><?php esc_html_e( 'Email', 'couponxxl' ); ?> <span class="required">*</span></label>
                                            <input type="text" name="email" id="email" class="form-control" data-validation="required|email"  data-error="<?php _e( 'Email is empty or invalid', 'couponxxl' ); ?>">
                                            <i class="pline-envelope"></i>
                                        </div>
                                        <div class="input-group">
                                            <label for="repeat_password"><?php esc_html_e( 'Repeat Password', 'couponxxl' ); ?> <span class="required">*</span></label>
                                            <input type="password" name="repeat_password" id="repeat_password" class="form-control" data-validation="required|match" data-match="password" data-error="<?php _e( 'Passwords do not match', 'couponxxl' ); ?>">
                                            <i class="pline-lock-locked"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-sm-push-6">
                                        <?php
                                        if( function_exists( 'sc_render_login_form_social_connect' ) ){
                                            sc_render_login_form_social_connect();
                                        }
                                        ?>
                                    </div>
                                    <div class="col-sm-6 col-sm-pull-6">
                                        <input type="checkbox" name="captcha" id="captcha" class="hidden">
                                        <input type="hidden" name="action" value="register">
                                        <input type="hidden" name="vendor" value="<?php echo $account == 'vendor' ? esc_attr( '1' ) : esc_attr( '0' ) ?>">
                                        <?php wp_nonce_field('register','register_field'); ?>
                                        <a href="javascript:;" class="btn submit-form register-form"><?php esc_html_e( 'Register', 'couponxxl' ); ?></a>
                                        <div class="ajax-response"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
<?php get_footer(); ?>