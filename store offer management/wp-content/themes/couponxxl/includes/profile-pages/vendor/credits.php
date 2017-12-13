<div class="credits-block">
	<div class="white-block">
		<div class="white-block-content">
			<h4><?php esc_html_e( 'Credit Packages', 'couponxxl' ) ?></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="white-block">
				<div class="white-block-content">
					<?php
					$deal_submit_price = couponxxl_get_option( 'deal_submit_price' );
					if( empty( $deal_submit_price ) ){
						$deal_submit_price = 0;
					}
					if( $deal_submit_price > 0 ){
						echo '<p><i class="fa fa-hand-o-right"></i> '.esc_html__( 'Deal submission is charged', 'couponxxl' ).' <strong>'.$deal_submit_price.'</strong> '.( $deal_submit_price == '1' ? esc_html__( 'credit', 'couponxxl' ) : esc_html__( 'credits', 'couponxxl' ) ).'</p>';
					}
					else{
						echo '<p><i class="fa fa-hand-o-right"></i> '.esc_html__( 'Deal submission is free of charge', 'couponxxl' ).'</p>';
					}
					?>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="white-block">
				<div class="white-block-content">
					<?php
					$coupon_submit_price = couponxxl_get_option( 'coupon_submit_price' );
					if( empty( $coupon_submit_price ) ){
						$coupon_submit_price = 0;
					}
					if( $coupon_submit_price > 0 ){
						echo '<p><i class="fa fa-tags"></i> '.esc_html__( 'Coupon submission is charged', 'couponxxl' ).' <strong>'.$coupon_submit_price.'</strong> '.( $coupon_submit_price == '1' ? esc_html__( 'credit', 'couponxxl' ) : esc_html__( 'credits', 'couponxxl' ) ).'</p>';
					}
					else{
						echo '<p><i class="fa fa-tags"></i> '.esc_html__( 'Coupon submission is free of charge', 'couponxxl' ).'</p>';
					}
					?>
				</div>
			</div>
		</div>
	</div>

	<form method="post">
	<?php
	$credit_packages = couponxxl_get_option( 'credit_packages' );
	global $couponxxl_cart;
	$checkout_link = $couponxxl_cart->get_checkout_link();
	if( !empty( $credit_packages ) ){
		?>
		<div class="row">
			<?php
			$counter = 0;
			$package_id = 1;
			$credit_packages = explode( "\n", $credit_packages );
			foreach( $credit_packages as $credit_package ){
				if( $counter == 3 ){
					echo '</div><div class="row>';
					$counter = 0;
				}
				$temp = explode( '|', $credit_package );
				$price_basic = couponxxl_format_price_number( trim( $temp[1] ) );
				$price_temp = explode( '.', $price_basic );
				$price = $price_temp[0].'.<span>'.$price_temp[1].'</span>';
				?>
				<div class="col-sm-4">
					<div class="white-block credit-package">
						<div class="white-block-content">
							<div class="package-title">
								<?php echo  $temp[0].' '.( $temp[0] == 1 ? esc_html__( 'credit', 'couponxxl' ) : esc_html__( 'credits', 'couponxxl' ) ).' '.esc_html__( 'for', 'couponxxl' ).' '.$price_basic ?>
							</div>
							<div class="package-value">
								<span><?php echo  $temp[0] ?><span><?php esc_html_e( 'c', 'couponxxl' ) ?></span></span> = <span><?php echo  $price; ?></span>
							</div>
							<a href="<?php echo  $checkout_link == '#' ? esc_attr( 'javascript:;' ) : esc_url( $checkout_link ); ?>" class="purchase-package" <?php echo  $checkout_link == '#' ? 'data-toggle="modal" data-target="#gateways"' : ''; ?> data-package_id="<?php echo esc_attr( $package_id ) ?>">
								<?php esc_html_e( 'Buy Now', 'couponxxl' ); ?>
							</a>
						</div>
					</div>
				</div>
				<?php
				$package_id++;
			}
			?>
		</div>
		<?php
	}
	?>
	</form>
</div>