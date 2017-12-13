<div class="white-block">
	<div class="white-block-content">
		<?php
		global $current_user, $wpdb;
		$current_user = wp_get_current_user();
		echo esc_html__( 'Hi', 'couponxxl' ).' <strong>'.( !empty( $current_user->display_name ) ? $current_user->display_name : $current_user->user_login ).'</strong> '.esc_html__( 'here\'s quick overview of your stats.', 'couponxxl' );
		$coupons = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$wpdb->posts} AS posts LEFT JOIN {$wpdb->prefix}offers AS offers ON posts.ID = offers.post_id WHERE posts.post_type = 'offer' AND post_author = %d AND offers.offer_type = 'coupon'", get_current_user_id() ) );
		$deals = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$wpdb->posts} AS posts LEFT JOIN {$wpdb->prefix}offers AS offers ON posts.ID = offers.post_id WHERE posts.post_type = 'offer' AND post_author = %d AND offers.offer_type = 'deal'", get_current_user_id() ) );
		$sales = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(item_id) FROM {$wpdb->prefix}order_items WHERE seller_id = %d", get_current_user_id() ) );
		$earnings = couponxxl_user_earnings(get_current_user_id() );
		?>
		<ul class="list-unstyled dashboard-stats">
			<li>
				<i class="fa fa-tags"></i><?php esc_html_e( 'Coupons:', 'couponxxl' ) ?> <span class="badge"><?php echo  $coupons ?></span>
			</li>
			<li>
				<i class="fa fa-hand-o-right"></i><?php esc_html_e( 'Deals:', 'couponxxl' ) ?> <span class="badge"><?php echo  $deals ?></span>
			</li>
			<li>
				<i class="fa fa-bar-chart"></i><?php esc_html_e( 'Sales:', 'couponxxl' ) ?> <span class="badge"><?php echo  $sales ?></span>
			</li>
			<li class="earnings-due">
				<i class="fa fa-dollar"></i><?php esc_html_e( 'Earnings due:', 'couponxxl' ) ?> <span class="badge"><?php echo couponxxl_format_price_number( 0 + $earnings->not_paid ); ?></span>
			</li>
			<li class="earnings-sent">
				<i class="fa fa-dollar"></i><?php esc_html_e( 'Earnings sent:', 'couponxxl' ) ?> <span class="badge"><?php echo couponxxl_format_price_number( 0 + $earnings->paid ); ?></span>
			</li>
		</ul>
	</div>
</div>