<?php
$store = get_posts(array( 'post_type' => 'store', 'author' => $vendor_id ));
if( !empty( $store ) ):
	$store = array_shift( $store );
	$subpage = isset( $_GET['subpage'] ) ? $_GET['subpage'] : '';
	$search = isset( $_GET['search'] ) ? $_GET['search'] : '';

	/*check if we are returning from purchasing the credits*/
	if( !empty( $_GET['action'] ) && $_GET['action'] == 'gateway-return' && !empty( $_GET['gateway'] ) ){
    	do_action( 'couponxxl_process_response', $_GET['gateway'] );
	}
	?>
	<ul class="list-unstyled list-inline">
		<li class="<?php echo empty( $subpage ) || $subpage == 'coupons' || $subpage == 'manage-offer-coupon' ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'coupons' ), $profile_link ) ) ?>">
				<?php esc_html_e( 'Coupons', 'couponxxl' ); ?>
			</a>
		</li>
		<li class="<?php echo $subpage == 'deals' || $subpage == 'manage-offer-deal' ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'deals' ), $profile_link ) ) ?>">
				<?php esc_html_e( 'Deals', 'couponxxl' ); ?>
			</a>
		</li>
		<li class="<?php echo $subpage == 'vouchers' ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'vouchers' ), $profile_link ) ) ?>">
				<?php esc_html_e( 'Vouchers', 'couponxxl' ); ?>
			</a>
		</li>
	</ul>

	<div class="row">
		<div class="col-sm-4">
			<div class="white-block">
		        <div class="white-block-content">
		            <div class="register-icon">
	                	<a href="javascript:;" class="store-logo" data-store_id="<?php echo esc_attr( $store->ID ) ?>">
		                    <?php couponxxl_store_logo( $store->ID ); ?>
	                	</a>
		            </div>
		            <p>
		            <?php
		            $credits = get_user_meta( $vendor_id, 'cxxl_credits', true );
		            if( empty( $credits ) ){
		            	$credits = 0;
		            }
		            echo esc_html__( 'You have ', 'couponxxl' ).'<strong>'.$credits.'</strong> '.( $credits == 1 ? esc_html__('credit', 'couponxxl') : esc_html__( 'credits', 'couponxxl' ) ).' '.esc_html__( 'left', 'couponxxl' ).', <a href="'.esc_url( add_query_arg( array( 'subpage' => 'credits' ), $profile_link ) ).'">'.esc_html__( 'buy more', 'couponxxl' ).'</a>';
		            ?>
		            </p>
					<a class="btn logout" href="<?php echo esc_url( wp_logout_url( home_url('/') ) ) ?>">
						<?php esc_html_e( 'Log Out', 'couponxxl' ); ?>
					</a>
		        </div>
	        </div>
		</div>
		<div class="col-sm-8">
			<?php
			switch( $subpage ){
				case 'deals' : include( couponxxl_load_path( 'includes/profile-pages/vendor/deals.php' ) ); break;
				case 'coupons' : include( couponxxl_load_path( 'includes/profile-pages/vendor/coupons.php' ) ); break;
				case 'vouchers' : include( couponxxl_load_path( 'includes/profile-pages/vendor/vouchers.php' ) ); break;
				
				case 'manage-offer-coupon' : include( couponxxl_load_path( 'includes/profile-pages/vendor/manage-offer.php' ) ); break;
				case 'manage-offer-deal' : include( couponxxl_load_path( 'includes/profile-pages/vendor/manage-offer.php' ) ); break;
				case 'delete-offer' : include( couponxxl_load_path( 'includes/profile-pages/vendor/delete-offer.php' ) ); break;

				default: include( couponxxl_load_path( 'includes/profile-pages/vendor/coupons.php' ) ); break;
			}
			?>
		</div>
	</div>
<?php  endif; ?>