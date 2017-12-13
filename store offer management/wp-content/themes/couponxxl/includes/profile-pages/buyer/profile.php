<div class="white-block buyer-block">
    <div class="white-block-content">
        <form method="post">
            <div class="row">
                <div class="col-sm-4">
                    <div class="white-block-content">
                        <div class="register-icon">
                            <a href="javascript:;" class="user-avatar">
                                <?php echo get_avatar( $current_user->ID ); ?>
                            </a>
                        </div>
                        <p>
                            <?php 
                            $purchases = couponxxl_user_purchases( get_current_user_id(), 'deals' );
                            esc_html_e( 'Hi ', 'couponxxl' ); echo  $current_user->display_name ?>, <?php esc_html_e( 'you have purchased ', 'couponxxl' ); ?><strong><?php echo  $purchases ?></strong> <?php $purchases == 1 ? esc_html_e( ' deal', 'couponxxl' ) : esc_html_e( ' deals', 'couponxxl' ) ?>, <a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'purchases' ), $profile_link ) ) ?>"><?php esc_html_e( ' see purchases', 'couponxxl' ) ?></a>
                        </p>
                    </div>
                </div>
                <div class="col-sm-8">
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?php wp_nonce_field('update_profile','update_profile_field'); ?>
                            <a href="javascript:;" class="btn submit-form register-form"><?php esc_html_e( 'Save Profile', 'couponxxl' ); ?></a>
                            <div class="ajax-response"></div>
                            <input type="hidden" value="update_profile" name="action">                    
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>