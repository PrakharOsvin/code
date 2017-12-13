<?php
if( get_option('users_can_register') ){
	?>
	<ul class="list-unstyled list-inline account-list">
		<li>
			<i class="pline-user"></i>
			<?php
			if( is_user_logged_in() ){
				echo '<a href="'.esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_profile' ) ).'">'.esc_html__( 'My Profile', 'couponxxl' ).'</a> '.esc_html__( 'or', 'couponxxl' ).' <a href="'.esc_url( wp_logout_url( home_url( '/' ) ) ).'">'.esc_html__( 'Log Out', 'couponxxl' ).'</a>';
			}
			else{
				echo '<a href="#login" data-toggle="modal">'.esc_html__( 'Login', 'couponxxl' ).'</a> '.esc_html__( 'or', 'couponxxl' ).' <a href="'.esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_register' ) ).'">'.esc_html__( 'Register', 'couponxxl' ).'</a>';
			}
			?>
		</li>
		<?php 
		$account_type = couponxxl_get_account_type();
		if( empty( $account_type ) || $account_type == 'buyer' ): ?>
			<li class="cart-items">
				<a href="<?php echo esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_cart' ) ) ?>" title="<?php _e( 'View Cart', 'couponxxl' ) ?>">
					<i class="pline-bag"></i>
					<?php
					global $couponxxl_cart;
					$items_count =  $couponxxl_cart->get_products_count();
					?>				
					<span class="count-items <?php echo $items_count == 0 ? esc_attr( 'no-cart-items' ) : esc_attr( '' ) ?>">
					<?php echo  $items_count ?>
					</span>
					<?php esc_html_e( 'My Cart', 'couponxxl' ); ?>
				</a>
			</li>
		<?php endif; ?>
	</ul>
	<?php
}
?>