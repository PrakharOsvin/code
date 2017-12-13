<?php
function couponxxl_locations_func( $atts, $content ){
	extract( shortcode_atts( array(
		'locations' => '',
		'show_map' => 'yes',
		'map_image' => ''
	), $atts ) );

	global $couponxxl_slugs;

	$locations = explode( ',', $locations );
	if( !empty( $locations ) ){

		$content = '<div class="white-block locations-shortcode"><div class="locations-shortcode-slider"><div>';

			ob_start();
			$counter = 0;
			foreach( $locations as $location ){
				if( $counter == 7 ){
					echo '</div><div>';
					$counter = 0;
				}
				$counter++;				
				$location_term = get_term_by( 'slug', $location, 'location' );
				?>
				<div class="location-wrap">
					<i class="pline-marker"></i>
					<a href="<?php echo esc_url( couponxxl_append_query_string( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ), array( $couponxxl_slugs['location'] => $location_term->slug ) ) ) ?>">
						<h6><?php echo  $location_term->name; ?></h6>
					</a>
				</div>
				<?php
			}
			$content .= ob_get_contents();
			ob_end_clean();

		$content .= '</div></div>';
		if( $show_map == 'yes' ){
			$image_data = wp_get_attachment_image_src( $map_image, 'full' );
			$style = '';
			if( !empty( $image_data[0] ) ){
				$style = 'background-image: url('.esc_url( $image_data[0] ).')';
			}
			$content .= '<div class="location-map-image" style="'.$style.'"><a href="'.esc_attr( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ) ).'" class="btn">'.esc_html__( 'See Map', 'couponxxl' ).'</a></div>';
		}
		$content .= '</div>';
	}

	return $content;
}

add_shortcode( 'locations', 'couponxxl_locations_func' );

function couponxxl_locations_params(){
	return array(
		array(
			"type" => "multidropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Locations","couponxxl"),
			"param_name" => "locations",
			"value" => couponxxl_get_custom_tax_list( 'location', 'left' ),
			"description" => esc_html__("List of the location.","couponxxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Show Map","couponxxl"),
			"param_name" => "show_map",
			"value" => array(
				esc_html__( 'Yes', 'couponxxl' ) => 'yes',
				esc_html__( 'No', 'couponxxl' ) => 'no'
			),
			"description" => esc_html__("Disaply or hide map from this shortcode.","couponxxl")
		),
		array(
			"type" => "attach_image",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Map Image","couponxxl"),
			"param_name" => "map_image",
			"value" => '',
			"description" => esc_html__("Select map image..","couponxxl")
		),
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => esc_html__("Locations", 'couponxxl'),
	   "base" => "locations",
	   "category" => esc_html__('Content', 'couponxxl'),
	   "params" => couponxxl_locations_params()
	) );
}
?>