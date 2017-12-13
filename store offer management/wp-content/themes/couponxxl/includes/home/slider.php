<?php
$big_map_slider_height = couponxxl_get_option( 'big_map_slider_height' );
$offers = new WP_Offers_Query(array(
	'posts_per_page' => -1,
	'offer_in_slider' => true
));

if( $offers->have_posts() ){
	?>
	<section class="home-featured-slider">
	<?php	
	while( $offers->have_posts() ){
		$offers->the_post();
		?>
		<div class="big-slider-item">
			<?php the_post_thumbnail( 'couponxxl-big-slider' ); ?>
			<div class="slider-caption">
				<?php
				$offer_type = couponxxl_get_the_offer_type();
				echo '<i class="pline-clock"></i>'.couponxxl_get_the_expire_time();
				?>
				<h2>
					<a href="<?php the_permalink() ?>">
						<?php the_title() ?>
					</a>
				</h2>
				<?php
				if( $offer_type == 'deal' ){
					couponxxl_deal_meta();
				}
				?>
			</div>
		</div>
		<?php
	}
	?>
	</section>
	<?php
}
?>