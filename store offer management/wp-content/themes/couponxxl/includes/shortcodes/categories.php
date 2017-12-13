<?php
function couponxxl_categories_func( $atts, $content ){
	global $couponxxl_slugs;
	extract( shortcode_atts( array(
		'categories' => '',
	), $atts ) );

	$html = '';

	$categories = explode( ',', $categories );
	if( !empty( $categories ) ){
		$html .= '<ul class="list-unstyled shortcode-categories">';
		foreach( $categories as $category ){
			$term = get_term_by( 'slug', $category, 'offer_cat' );
			if( !empty( $term ) ){
				$term_meta = get_option( "taxonomy_".$term->term_id );
				$promo_text = !empty( $term_meta['promo_text'] ) ? '<div class="label">'.$term_meta['promo_text'].'</div>' : '';
				$html .= '<li><a href="'.esc_attr( couponxxl_append_query_string( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ), array( $couponxxl_slugs['offer_cat'] => $term->slug ) ) ).'">'.$term->name.' '.$promo_text.'</a></li>';
			}
		}
		$html .= '</ul>';
	} 

	return $html;
}

add_shortcode( 'categories', 'couponxxl_categories_func' );

function couponxxl_categories_params(){
	return array(
		array(
			"type" => "multidropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Categories","couponxxl"),
			"param_name" => "categories",
			"value" => couponxxl_get_custom_tax_list( 'offer_cat', 'left' ),
			"description" => esc_html__("Select whichi categories to dispay.","couponxxl")
		),
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => esc_html__("Categories", 'couponxxl'),
	   "base" => "categories",
	   "category" => esc_html__('Content', 'couponxxl'),
	   "params" => couponxxl_categories_params()
	) );
}
?>