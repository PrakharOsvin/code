<div class="white-block offer-box coupon-box<?php echo $offer->offer_expire < current_time( 'timestamp' ) ? ' expired' : ''; ?>">
	<div class="white-block-media">
		<div class="embed-responsive embed-responsive-16by9">
<a href="<?php the_permalink() ?>">
    <?php
    if( !has_post_thumbnail() ) {
        $store_id = get_post_meta( get_the_ID(), 'offer_store', true );
        couponxxl_store_logo( $store_id );
    } else {
        the_post_thumbnail();
    }    
    ?>
</a>
		</div>
	</div>

	<div class="white-block-content">

		<div class="top-meta">
			<i class="pline-clock"></i>
			<?php echo couponxxl_get_the_expire_time(); ?>
		</div>

		<h6>
			<a href="<?php the_permalink() ?>">
				<?php
				$title = get_the_title();
				if( strlen( $title > 60 ) ){
					$title = substr( $title, 0, 60 ).'...';
				}

				echo  $title;
				?>
			</a>
		</h6>

		<?php echo couponxxl_coupon_button(); ?>

	</div>
</div>