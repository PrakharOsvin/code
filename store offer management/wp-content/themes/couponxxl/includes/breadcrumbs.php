<?php $show_breadcrumbs = couponxxl_get_option( 'show_breadcrumbs' ); 
$breadcrumbs = couponxxl_get_breadcrumbs();
if( !empty( $breadcrumbs ) ):
?>
	<section class="breadcrumb-section <?php echo $show_breadcrumbs == 'yes' ? esc_attr( '' ) : esc_attr( 'breadcrumb-hide' ) ?>">
		<div class="container">
			<?php echo  $breadcrumbs; ?>
		</div>
	</section>
<?php else: ?>
	<section class="breadcrumb-section">
	</section>
<?php endif; ?>