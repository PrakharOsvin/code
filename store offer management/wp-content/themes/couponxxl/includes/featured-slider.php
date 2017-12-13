<?php

$args = array(
	'post_status' => 'publish',
	'post_type' => 'offer',
	'posts_per_page' => -1,
);
global $offers;
if( !empty( $offers ) ){
	$args['post__in'] = $offers;
}
else{
	$args['offer_in_slider'] = true;
	if( !empty( $offer_type ) ){
		$args['offer_type'] = $offer_type;
	}

	if( !empty( $offer_store ) ){
	    $args['meta_query'][] = array(
	        'key' => 'offer_store',
	        'value' => $offer_store,
	        'compare' => 'IN',
	    );
	}

	if( !empty( $offer_cat ) ){
		$args['tax_query'][] = array(
			'taxonomy' => 'offer_cat',
			'field'	=> 'slug',
			'terms' => $offer_cat,
		);
	}

	if( !empty( $offer_tag ) ){
		$args['tax_query'][] = array(
			'taxonomy' => 'offer_tag',
			'field'	=> 'slug',
			'terms' => $offer_tag,
		);
	}
	if( !empty( $location ) ){
		$args['tax_query'][] = array(
			'taxonomy' => 'location',
			'field'	=> 'slug',
			'terms' => $location,
		);
	}
	if( !empty( $keyword ) ){
	    $args['s'] = urldecode( $keyword );
	}	
}

$offers = new WP_Offers_Query( $args );
if( $offers->have_posts() ){
	?>
	<div class="featured-slider-wrap">
		
		<div class="featured-slider-loader">
			<div class="featured-slider-loader-holder">
				<i class="fa fa-spin fa-spinner"></i>
			</div>
		</div>
		
		<ul class="list-unstyled featured-slider" data-slider_auto_rotate="<?php echo esc_attr( couponxxl_get_option( 'slider_auto_rotate' ) ) ?>" data-slider_speed="<?php echo esc_attr( couponxxl_get_option( 'slider_speed' ) ) ?>">
			<?php
			while( $offers->have_posts() ){
				$offers->the_post();
				$expire_time = couponxxl_raw_expire_time();
				?>
				<li>
					<?php
					if( has_post_thumbnail() ){
						the_post_thumbnail( 'couponxxl-featured-slider', array('class' => 'img-responsive') );
					}
					?>
					<div class="white-block">
						<?php couponxxl_offer_countdown( $expire_time ) ?>
						<a href="<?php the_permalink() ?>">
							<h2><?php the_title(); ?></h2>
						</a>
						<?php
						if( couponxxl_get_the_offer_type() == 'deal' ){
							couponxxl_deal_meta();
						}
						?>
					</div>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
	<?php
}
wp_reset_postdata();

?>