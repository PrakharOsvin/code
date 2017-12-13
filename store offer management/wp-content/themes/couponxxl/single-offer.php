<?php
/*==================
 SINGLE DEAL POST
==================*/

the_post();
get_header();

$offer_meta = couponxxl_get_offer_meta( get_the_ID() );
couponxxl_register_click();

include( couponxxl_load_path( 'includes/title/before-page-title.php' ) );
?>

<h1><?php the_title(); ?></h1>

<?php
if( $offer_meta->offer_type == 'deal' ){
	?>
	<section class="recommended-thumbs-section">
		<div class="container">
			<?php echo couponxxl_thumbs_html(); ?>
		</div>
	</section>
	<?php
	include( couponxxl_load_path( 'includes/title/after-page-title.php' ) );

    include( couponxxl_load_path( 'includes/single-offer/deal.php' ) );
}
else{
	include( couponxxl_load_path( 'includes/title/after-page-title.php' ) );

    include( couponxxl_load_path( 'includes/single-offer/coupon.php' ) );
}
?>

<?php get_footer(); ?>