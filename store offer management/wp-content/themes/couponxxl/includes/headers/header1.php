<?php 
$site_logo = couponxxl_get_option( 'site_logo' );
if( function_exists('sm_init') || !empty( $site_logo['url'] ) ): 
?>
<div class="container">
	<div class="top-bar clearfix">
	    <div class="pull-left">
	        <?php 
				if( !empty( $site_logo['url'] ) ){
					echo '<a class="logo"  href="'.home_url('/').'">';
						echo '<img src="'.esc_url( $site_logo['url'] ).'" height="'.esc_attr( $site_logo['height'] ).'" width="'.esc_attr( $site_logo['width'] ).'" alt="">';
					echo '</a>';
				}
	        ?>
	        <?php  include( couponxxl_load_path( 'includes/headers/search.php' ) )?>
	    </div>
	    <div class="pull-right">
	        <?php  include( couponxxl_load_path( 'includes/headers/account.php' ) )?>
	    </div>
	</div>
</div>
<?php endif; ?>

<?php  include( couponxxl_load_path( 'includes/headers/navigation.php' ) )?>

<?php  include( couponxxl_load_path( 'includes/headers/categories.php' ) )?>