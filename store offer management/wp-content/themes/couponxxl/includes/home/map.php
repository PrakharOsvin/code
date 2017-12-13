<?php
$big_map_slider_height = couponxxl_get_option( 'big_map_slider_height' );
?>
<section class="big-map-holder">
    <div id="map-markers" class="big-map" style="height: <?php echo esc_attr( $big_map_slider_height ); ?>;"></div>
</section>
<div class="markers hidden">
<?php

$marker_deals_array = array();
$marker_deals = new WP_Offers_Query(array(
	'offer_type' => 'deal',
	'posts_per_page' => -1
));

if( $marker_deals->have_posts() ){
	while( $marker_deals->have_posts() ){
		$marker_deals->the_post();
        $marker_deals_array[] = array(
            'title' => get_the_title(),
            'permalink' => get_permalink(),
            'ID' => get_the_ID(),
            'image' => get_the_post_thumbnail( get_the_ID(), 'couponxxl-popup-box' ),
            'deal_markers' => get_post_meta( get_the_ID(), 'deal_markers', true ),
            'offer_price' => couponxxl_get_deal_meta( get_the_ID(), false )
        );
	}
}
couponxxl_generate_markers( $marker_deals_array ); 
wp_reset_postdata();
?>
</div>