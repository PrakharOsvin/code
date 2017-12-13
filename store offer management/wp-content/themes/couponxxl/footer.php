<?php get_sidebar('footer'); ?>

<?php
$footer_copyrights = couponxxl_get_option( 'footer_copyrights' );

if( !empty( $footer_copyrights ) ):
?>
	<section class="footer">
		<div class="container">
			<div class="text-center">
				<?php echo wp_kses_post( $footer_copyrights ) ?>
			</div>
		</div>
	</section>
<?php
endif;
?>
<!-- modal -->
<div class="modal fade in" id="showCode" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content showCode-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<div class="coupon_modal_content">
				</div>
			</div>
		</div>
	</div>
</div>
<!-- .modal -->

<?php if( get_page_template_slug() == 'page-tpl_cart.php' || ( !empty( $_GET['subpage'] ) && $_GET['subpage'] == 'credits' ) ): ?>
	<!-- modal -->
	<div class="modal fade in" id="gateways" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<h3><?php esc_html_e( 'Select payment method', 'couponxxl' ); ?></h3>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<?php global $couponxxl_cart; echo  $couponxxl_cart->gateways_select(); ?>
				</div>
			</div>
		</div>
	</div>
	<!-- .modal -->
<?php endif; ?>

<?php if( is_singular('offer') ): ?>
	<!-- modal -->
	<div class="modal fade in" id="offer_store_explanation" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<h3><?php esc_html_e( 'What Is Store Offer?', 'couponxxl' ); ?></h3>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<p class="no-margin"><?php esc_html_e( 'When deal is marked as store offer by its creator, then price you see is price for voucher, once you purchase it you can go to store and redeem your discount.', 'couponxxl' ) ?></p>
				</div>
			</div>
		</div>
	</div>
	<!-- .modal -->
	<!-- modal -->
	<div class="modal fade in" id="groupon_explanation" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<h3><?php esc_html_e( 'Why Sales Are Required?', 'couponxxl' ); ?></h3>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<p><?php esc_html_e( 'This type of deal requires minumum number of sales defined by the deal vendor during the deal submission. Once the number of purchases becomes equal or higher than the defined minimum number of sales, deal is activated and you will receive voucher to reedem your discount.', 'couponxxl' ) ?></p>
					<p class="no-margin"><?php esc_html_e( 'If deal did not acquired defined minimum number of sales and it is expired or deleted, you will receive full refund.', 'couponxxl' ) ?></p>
				</div>
			</div>
		</div>
	</div>
	<!-- .modal -->
<?php endif; ?>


<?php if( !is_user_logged_in() ): ?>
<div class="modal fade in" id="login" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            	<h3><?php esc_html_e( 'Login', 'couponxxl' ) ?></h3>
            	<p><?php esc_html_e( 'All required fields are marked with', 'couponxxl' ) ?> <span class="required">*</span>. <?php esc_html_e( 'If you are not registered, you can ', 'couponxxl' ) ?> <a href="<?php echo esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_register' ) ) ?>"><?php esc_html_e( 'register here', 'couponxxl' ) ?></a> </p>
            	<div class="ajax-response"></div>

                <form method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-group">
                            	<label for="username"><?php esc_html_e( 'Username', 'couponxxl' ); ?> <span class="required">*</span></label>
                                <input type="text" name="username" id="username" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input your username', 'couponxxl' ); ?>">
                                <i class="pline-user"></i>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="input-group">
                            	<label for="password"><?php esc_html_e( 'Password', 'couponxxl' ); ?> <span class="required">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input your password', 'couponxxl' ); ?>">
                                <i class="pline-lock-locked"></i>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="checkbox checkbox-inline">
                                    <input type="checkbox" id="remember_me" name="remember_me">
                                    <label for="remember_me"><?php esc_html_e( 'Remember me', 'couponxxl' ); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-right">
                                <a href="<?php echo couponxxl_get_permalink_by_tpl( 'page-tpl_recover_password' ) ?>" class="recover-modal" data-dismiss="modal"><?php esc_html_e( 'Forgot Password?', 'couponxxl' ) ?></a>
                            </p>
                        </div>
                        <?php wp_nonce_field('login','login_field'); ?>
                    </div>
                    <input type="hidden" name="redirect" value="<?php echo !empty( $_GET['redirect'] ) ? esc_url( urldecode( $_GET['redirect']) ) : '' ?>">
                    <input type="hidden" name="action" value="login">
                    <a href="javascript:;" class="btn submit-form register-form"><?php esc_html_e( 'Log In', 'couponxxl' ) ?></a>
                </form>
                <?php
                if( function_exists( 'sc_render_login_form_social_connect' ) ){
                    sc_render_login_form_social_connect();
                }
                ?>
			</div>
		</div>
	</div>
</div>

<div class="modal fade in" id="recover" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">

	        	<h3><?php esc_html_e( 'Forgot Password', 'couponxxl' ) ?></h3>
	        	<p><?php esc_html_e( 'Please provide your email address with whih you have registered on the site.', 'couponxxl' ) ?></p>
	        	<div class="ajax-response"></div>
	        	
                <form method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-group">
                            	<label for="email"><?php esc_html_e( 'Email', 'couponxxl' ); ?> <span class="required">*</span></label>
                                <input type="text" name="email" id="email" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input your email', 'couponxxl' ); ?>">
                                <i class="pline-envelope"></i>
                            </div>
                        </div>
                        <input type="hidden" name="action" value="recover">
                        <?php wp_nonce_field('recover','recover_field'); ?>
                    </div>
                    <a href="javascript:;" class="btn submit-form register-form"><?php esc_html_e( 'Retrieve Password', 'couponxxl' ) ?></a>
                </form>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php
if( get_page_template_slug() == 'page-tpl_search.php' ):
include( couponxxl_load_path( 'includes/search-args.php' ) );
?>
<!-- modal -->
<div class="modal fade in" id="locations" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<div class="row modal-filter-wrap location">
					<?php
					$locations = couponxxl_get_organized( 'location' );
					$search_show_count = couponxxl_get_option( 'search_show_count' );

					if( !empty( $locations) ){
						foreach( $locations as $parent_location ){
							$count = '';
							if( $search_show_count = 'yes' ){
								$count = couponxxl_custom_term_count( $parent_location, 'location' );
							}
							echo '<div class="col-sm-4">';
								couponxxl_modal_locations( array( $parent_location ), $location, $search_show_count );
							echo '</div>';
						}
					}
					?>
				</div>
				<a href="javascript:;" class="modal-filter btn" data-filter="location" data-dismiss="modal"><?php esc_html_e( 'Filter', 'couponxxl' ) ?></a>
			</div>
		</div>
	</div>
</div>

<!-- modal -->
<div class="modal fade in" id="stores" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<div class="row modal-filter-wrap offer_store">
					<?php
					$stores = get_posts(array(
						'post_type' => 'store',
						'posts_per_page' => '-1',
						'post_status' => 'publish'
					));

					if( !empty( $stores) ){
						echo '<ul class="list-unstyled">';
						foreach( $stores as $store ){
							$count = '0';
							if( $search_show_count = 'yes' ){
								$count = couponxxl_custom_store_count( $store->ID );
							}
		                    echo '
		                    <li class="'.( in_array( $store->ID, $offer_store ) ? 'active current' : '' ).'">
		                        <input type="checkbox" name="'.$couponxxl_slugs['offer_store'].'[]" id="m_offer_store_'.esc_attr( $store->ID ).'" value="'.esc_attr( $store->ID ).'" '.( in_array( $store->ID, $offer_store ) ? 'checked="checked"' : '' ).'>
		                        <label for="m_offer_store_'.esc_attr( $store->ID ).'">'.$store->post_title.' <span class="count">( '.$count.' )</span></label>
		                    </li>';
						}
						echo '</ul>';
					}
					?>
				</div>
				<a href="javascript:;" class="modal-filter btn" data-filter="offer_store" data-dismiss="modal"><?php esc_html_e( 'Filter', 'couponxxl' ) ?></a>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php
wp_footer();
?>
</body>
</html>