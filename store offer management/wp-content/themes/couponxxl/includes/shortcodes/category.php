<?php
function couponxxl_category_func( $atts, $content ){
	extract( shortcode_atts( array(
		'category' => '',
		'show' => '',
		'items' => '3',
	), $atts ) );

	$content = '';
	$category_term = get_term_by( 'slug', $category, 'offer_cat' );
	if( !empty( $category_term ) ){

		$args = array(
			'post_status' => 'publish',
			'posts_per_page' => $items,
			'tax_query' => array(
				'relation' => 'AND',
				array(
		            'taxonomy' => 'offer_cat',
		            'field' => 'slug',
		            'terms' => explode( ",", $category ),
		            'operator' => 'IN'
				)
			)
		);
		if( !empty( $show ) ){
			$args['offer_type'] = $show;
		}
		$offers = new WP_Offers_Query( $args );

		global $couponxxl_slugs;

		$term_meta = get_option( "taxonomy_".$category_term->term_id );
		$category_image = !empty( $term_meta['category_image'] ) ? $term_meta['category_image'] : '';
		$image_data = wp_get_attachment_image_src( $category_image, 'full' );
		$style = '';
		if( !empty( $image_data[0] ) ){
			$style = 'background-image: url('.esc_url( $image_data[0] ).')';
		}

		$content = '<div class="white-block category-shortcode">
						<a href="'.esc_url( couponxxl_append_query_string( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ), array( $couponxxl_slugs['offer_cat'] => $category_term->slug ) ) ).'">
							<div class="category-info" style="'.$style.'">
								<h2>'.$category_term->name.'</h2>
							</div>
						</a>
						<div class="category-shortcode-slider">';

			ob_start();
			$counter = 0;
			if( $offers->have_posts() ){
				?>
				<div>
				<?php
				while( $offers->have_posts() ){
					$offers->the_post();
					if( $counter == 3 ){
						echo '</div><div>';
						$counter = 0;
					}
					$counter++;
					?>
					<div class="category-item-wrap">
						<div class="category-item-title">
							<?php the_post_thumbnail( 'couponxxl-category-box' ) ?>
							<a href="<?php the_permalink() ?>">
								<h6><?php the_title(); ?></h6>
							</a>
						</div>
						<div class="category-item-meta">
							<?php echo couponxxl_get_deal_html_price() ?>
						</div>
					</div>
					<?php
				}
				?>
				</div>
				<?php
			}
			$content .= ob_get_contents();
			ob_end_clean();

		$content .= '</div></div>';
	}

	return $content;
}

add_shortcode( 'category', 'couponxxl_category_func' );

function couponxxl_category_params(){
	return array(
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Category","couponxxl"),
			"param_name" => "category",
			"value" => couponxxl_get_custom_tax_list( 'offer_cat', 'left' ),
			"description" => esc_html__("Filter offers by category.","couponxxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Filter By Type","couponxxl"),
			"param_name" => "show",
			"value" => array(
				esc_html__( 'All', 'couponxxl' ) => '',
				esc_html__( 'Coupons Only', 'couponxxl' ) => 'coupon',
				esc_html__( 'Deals Only', 'couponxxl' ) => 'deal',
			),
			"description" => esc_html__("Filter offers by type.","couponxxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Items","couponxxl"),
			"param_name" => "items",
			"value" => '',
			"description" => esc_html__("Input number of items you wish to display.","couponxxl")
		),
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => esc_html__("Category", 'couponxxl'),
	   "base" => "category",
	   "category" => esc_html__('Content', 'couponxxl'),
	   "params" => couponxxl_category_params()
	) );
}
?>