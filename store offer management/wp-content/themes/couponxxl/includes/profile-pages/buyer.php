<?php
$subpage = isset( $_GET['subpage'] ) ? $_GET['subpage'] : '';
$order = isset( $_GET['order'] ) ? $_GET['order'] : '';
$search = isset( $_GET['search'] ) ? $_GET['search'] : '';
?>

<ul class="list-unstyled list-inline buyer-nav">
	<li class="<?php echo empty( $subpage ) ? esc_attr( 'active' ) : '' ?>">
		<a href="<?php echo esc_url( $profile_link ) ?>">
			<?php esc_html_e( 'My Profile', 'couponxxl' ); ?>
		</a>
	</li>
	<li class="<?php echo $subpage == 'purchases' ? esc_attr( 'active' ) : '' ?>">
		<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'purchases' ), $profile_link ) ) ?>">
			<?php esc_html_e( 'My Purchases', 'couponxxl' ); ?>
		</a>
	</li>
	<li class="logout">
		<a href="<?php echo esc_url( wp_logout_url( home_url('/') ) ) ?>">
			<?php esc_html_e( 'Log Out', 'couponxxl' ); ?>
		</a>
	</li>
</ul>

<?php

if( $subpage == 'purchases' ){
	if( !empty( $order ) ){
		include( couponxxl_load_path( 'includes/profile-pages/buyer/view-order.php' ) );
	}
	else{
		include( couponxxl_load_path( 'includes/profile-pages/buyer/list-orders.php' ) );
	}

}
else{
	include( couponxxl_load_path( 'includes/profile-pages/buyer/profile.php' ) );
}
?>