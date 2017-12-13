<div class="white-block">
    <div class="white-block-content">
        <form method="post">

            <?php
            $message = get_transient( 'couponxxl_account_connect_message' );
            if( !empty( $message ) ){
                echo  $message;
                delete_transient( 'couponxxl_account_connect_message' );
            }
            ?>

            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group">
                        <label for="first_name"><?php esc_html_e( 'First Name', 'couponxxl' ); ?> <span class="required">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input your first name', 'couponxxl' ); ?>" value="<?php echo esc_attr( $current_user->user_firstname ) ?>">
                        <i class="pline-user"></i>
                        <p class="description"><?php esc_html_e( 'Input your first name.', 'couponxxl' ); ?></p>
                    </div>
                    <div class="input-group">
                        <label for="password"><?php esc_html_e( 'Password', 'couponxxl' ); ?> <span class="required">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" data-validation="match" data-match="repeat_password" data-error="<?php _e( 'Passwords do not match', 'couponxxl' ); ?>" placeholder="******">
                        <i class="pline-lock-locked"></i>
                        <p class="description"><?php esc_html_e( 'Input your password.', 'couponxxl' ); ?></p>
                    </div>
                    <div class="input-group">
                        <label for="email"><?php esc_html_e( 'Email', 'couponxxl' ); ?> <span class="required">*</span></label>
                        <input type="text" name="email" id="email" class="form-control" data-validation="required|email"  data-error="<?php _e( 'Email is empty or invalid', 'couponxxl' ); ?>" value="<?php echo esc_attr( $current_user->user_email ) ?>">
                        <i class="pline-envelope"></i>
                        <p class="description"><?php esc_html_e( 'Input your email.', 'couponxxl' ); ?></p>
                    </div>                    
                    <?php wp_nonce_field('update_profile','update_profile_field'); ?>
                    <a href="javascript:;" class="btn submit-form register-form"><?php esc_html_e( 'Save Profile', 'couponxxl' ); ?></a>
                    <div class="ajax-response"></div>
                    <input type="hidden" value="update_profile" name="action">
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <label for="last_name"><?php esc_html_e( 'Last Name', 'couponxxl' ); ?> <span class="required">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input your last name', 'couponxxl' ); ?>" value="<?php echo esc_attr( $current_user->user_lastname ) ?>">
                        <i class="pline-user"></i>
                        <p class="description"><?php esc_html_e( 'Input your last name.', 'couponxxl' ); ?></p>
                    </div>
                    <div class="input-group">
                        <label for="repeat_password"><?php esc_html_e( 'Repeat Password', 'couponxxl' ); ?> <span class="required">*</span></label>
                        <input type="password" name="repeat_password" id="repeat_password" class="form-control" data-validation="match" data-match="password" data-error="<?php _e( 'Passwords do not match', 'couponxxl' ); ?>" placeholder="******">
                        <i class="pline-lock-locked"></i>
                        <p class="description"><?php esc_html_e( 'Input password again.', 'couponxxl' ); ?></p>
                    </div>
                    <div class="input-group">
                        <label for="seller_payout_method"><?php esc_html_e( 'Withdrawal Method', 'couponxxl' ); ?></label>
                        <select name="seller_payout_method">
                            <option value=""><?php esc_html_e( 'Select', 'couponxxl' ) ?></option>
                            <?php
                            $seller_payout_method = get_user_meta( get_current_user_id(), 'seller_payout_method', true );
                            echo  $seller_payout_method;
                            global $COUPONXXL_GATEWAYS;
                            if( !empty( $COUPONXXL_GATEWAYS ) ){
                                foreach( $COUPONXXL_GATEWAYS as $gateway ){
                                    if( $gateway['has_payout'] ){
                                        echo '<option value="'.$gateway['slug'].'" '.( $gateway['slug'] == $seller_payout_method ? 'selected="selected"' : '' ).'>'.$gateway['name'].'</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                        <i class="pline-lock-locked"></i>
                        <p class="description"><?php esc_html_e( 'Select your withdrawal method.', 'couponxxl' ); ?></p>

                        <div class="payout-fields">
                            <?php 
                            if( !empty( $COUPONXXL_GATEWAYS ) ){
                                $seller_payout_account = get_user_meta( get_current_user_id(), 'seller_payout_account', true );
                                foreach( $COUPONXXL_GATEWAYS as $gateway ){
                                    if( $gateway['has_payout'] ){
                                        do_action( 'couponxxl_payout_fields_'.$gateway['slug'], $seller_payout_account, $seller_payout_method ); 
                                    }
                                }
                            }                            
                            
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>