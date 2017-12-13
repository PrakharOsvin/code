<?php
global $current_user;
$user_id = $vendor_id;
$store = get_posts(array( 'post_type' => 'store', 'author' => $user_id ));
$user_offers_args = array(
	'post_type'      => 'offer',
	'post_status'    => 'publish,draft',
	'posts_per_page' => - 1,
	'author'         => $current_user->ID,
);
$user_offers = new WP_Query( $user_offers_args );
$offer_ids  = wp_list_pluck( $user_offers->posts, 'ID' );
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
		<li class="<?php echo empty( $subpage ) ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( $profile_link ) ?>">
				<?php esc_html_e( 'Dashboard', 'couponxxl' ); ?>
			</a>
		</li>
		<li class="<?php echo $subpage == 'profile' ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'profile' ), $profile_link ) ) ?>">
				<?php esc_html_e( 'Profile', 'couponxxl' ); ?>
			</a>
		</li>
		<li class="<?php echo $subpage == 'store' ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'store' ), $profile_link ) ) ?>">
				<?php esc_html_e( 'Store', 'couponxxl' ); ?>
			</a>
		</li>
		<li class="<?php echo $subpage == 'coupons' || $subpage == 'manage-offer-coupon' ? esc_attr( 'active' ) : '' ?>">
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
		<li class="<?php echo $subpage == 'agents' ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'agents' ), $profile_link ) ) ?>">
				<?php esc_html_e( 'Agents', 'couponxxl' ); ?>
			</a>
		</li>
		<li class="<?php echo $subpage == 'credits' ? esc_attr( 'active' ) : '' ?>">
			<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'credits' ), $profile_link ) ) ?>">
				<?php esc_html_e( 'Credits', 'couponxxl' ); ?>
			</a>
		</li>
	</ul>

	<div class="row">
		<div class="col-sm-4">
			<div class="white-block">
		        <div class="white-block-content">
		            <div class="register-icon">
	                	<a href="javascript:;" class="store-logo" data-store_id="<?php echo esc_attr( $store->ID ) ?>">
		                    <?php
		                    if( has_post_thumbnail( $store->ID ) ){
		                    	couponxxl_store_logo( $store->ID );
		                    }
		                    else{
		                    	esc_html_e( 'Upload Store Logo', 'couponxxl' );
		                    }
		                    ?>
	                	</a>
		            </div>
		            <p>
		            <?php
		            $credits = get_user_meta( $user_id, 'cxxl_credits', true );
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
				case 'profile' : include( couponxxl_load_path( 'includes/profile-pages/vendor/profile.php' ) ); break;
				case 'store' : include( couponxxl_load_path( 'includes/profile-pages/vendor/store.php' ) ); break;
				case 'coupons' : include( couponxxl_load_path( 'includes/profile-pages/vendor/coupons.php' ) ); break;
				case 'deals' : include( couponxxl_load_path( 'includes/profile-pages/vendor/deals.php' ) ); break;
				case 'vouchers' : include( couponxxl_load_path( 'includes/profile-pages/vendor/vouchers.php' ) ); break;
				case 'agents' : include( couponxxl_load_path( 'includes/profile-pages/vendor/agents.php' ) ); break;
				case 'credits' : include( couponxxl_load_path( 'includes/profile-pages/vendor/credits.php' ) ); break;

				case 'manage-offer-coupon' : include( couponxxl_load_path( 'includes/profile-pages/vendor/manage-offer.php' ) ); break;
				case 'manage-offer-deal' : include( couponxxl_load_path( 'includes/profile-pages/vendor/manage-offer.php' ) ); break;
				case 'delete-offer' : include( couponxxl_load_path( 'includes/profile-pages/vendor/delete-offer.php' ) ); break;

				case 'delete-agent' : include( couponxxl_load_path( 'includes/profile-pages/vendor/delete-agent.php' ) ); break;
				case 'edit-agent' : include( couponxxl_load_path( 'includes/profile-pages/vendor/edit-agent.php' ) ); break;
				case 'new-agent' : include( couponxxl_load_path( 'includes/profile-pages/vendor/new-agent.php' ) ); break;
				default: include( couponxxl_load_path( 'includes/profile-pages/vendor/dashboard.php' ) ); break;
			}
			?>
		</div>
	</div>
<?php  endif; ?>