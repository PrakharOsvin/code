<?php

if( !function_exists( 'couponxxl_deal_location' ) ){
function couponxxl_deal_location() {
	add_meta_box(
		'offer_deal_location',
		esc_html__( 'Deal Locations', 'couponxxl' ),
		'couponxxl_deal_location_callback',
		'offer'
	);
}
add_action( 'add_meta_boxes', 'couponxxl_deal_location' );
}

if( !function_exists( 'couponxxl_deal_precise_locations' ) ){
function couponxxl_deal_precise_locations( $store_id, $saved_markers = array(), $terms = array() ){
	global $wpdb;
	
	$markers = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}store_markers WHERE post_id = %d", $store_id ) );
	$markers = couponxxl_gmap_location_organize( $markers );

	if( !empty( $markers ) ){
		$counter = 0;
		foreach( $markers as $term_slug => $location_group ){
			$term = get_term_by( 'slug', $term_slug, 'location' );
			if( !empty( $term ) ){
				echo '<div class="checkbox-group">';
					echo '<div class="checkbox-wrap checkbox-wrap-parent"><input type="checkbox" name="deal_markers['.esc_attr( $term_slug ).']" id="input_'.esc_attr( $counter ).'" value="'.esc_attr( $term_slug ).'" data-id="'.esc_attr( $term->term_id ).'" class="deal-location-main" '.( in_array( $term_slug, $terms ) ? 'checked="checked"' : '' ).'><label for="input_'.esc_attr( $counter ).'">'.$term->name.'</label></div>';
					if( !empty( $location_group ) ){
						$counter2 = 0;
						foreach( $location_group as $saved_location ){
							echo '<div class="checkbox-wrap checkbox-wrap-child"><input type="checkbox" class="checkbox-child" name="deal_markers['.esc_attr( $term_slug ).'][]" id="input_'.esc_attr( $counter ).'_'.esc_attr( $counter2 ).'" value="'.esc_attr( $saved_location->marker_id ).'" class="'.esc_attr( $term_slug ).'" '.( in_array( $saved_location->marker_id, $saved_markers ) ? 'checked="checked"' : '' ).'><label for="input_'.esc_attr( $counter ).'_'.esc_attr( $counter2 ).'">'.$saved_location->name.'</label></div>';
							$counter2++;
						}
					}
				echo '</div>';
			}
			$counter++;
		}
	}
}
}

if( !function_exists( 'couponxxl_offer_store_location' ) ){
function couponxxl_offer_store_location(){
	couponxxl_deal_precise_locations( $_GET['store_id'] );
	die();
}

add_action('wp_ajax_offer_store_location', 'couponxxl_offer_store_location');
add_action('wp_ajax_nopriv_offer_store_location', 'couponxxl_offer_store_location');
}

if( !function_exists( 'couponxxl_deal_location_callback' ) ){
function couponxxl_deal_location_callback( $post ) {
	global $wpdb;
	$stores = get_posts(array(
		'post_type' => 'store',
		'posts_per_page' => '-1',
		'post_status' => 'publish'
	));
	$store_id = get_post_meta( $post->ID, 'offer_store', true );
	?>
	<label for="offer_store"><?php esc_html_e( 'Offer Store', 'couponxxl' ) ?></label>
	<div class="sm_metabox_description"><?php esc_html_e( 'Select store and its location on which the offer is available. ( For Coupons only offer store select box is active )', 'shopy' ) ?></div>
	<select name="offer_store" id="offer_store" class="form-control">
	<?php
	if( !empty( $stores ) ){
		foreach( $stores as $store ){
			echo '<option value="'.esc_attr( $store->ID ).'" '.( $store->ID == $store_id ? 'selected="selected"' : '' ).'>'.$store->post_title.'</option>';
		}
	}
	?>
	</select>
	<?php
	couponxxl_locations_checkboxes( '', $stores, $post->ID );
}
}

if( !function_exists( 'couponxxl_locations_checkboxes' ) ){
function couponxxl_locations_checkboxes( $store_id = '', $stores = array(), $post_id = 0 ){
	?>
	<div class="store_location">
		<?php
		if( empty( $store_id ) && !empty( $stores ) ){
			$store_id = $stores[0]->ID;
		}
		$saved_markers = array();
		$terms_slugs = array();
		if( !empty( $store_id ) ){
			if( !empty( $post_id ) ){
				$saved_markers = get_post_meta( $post_id, 'deal_markers', true );
				$saved_markers = explode( ',', $saved_markers );
				$terms = get_the_terms( $post_id, 'location' );
				$terms_slugs = array();
				if( !empty( $terms ) ){
					foreach( $terms as $term ) {
					    $terms_slugs[] = $term->slug; 
					}
				}
			}
			couponxxl_deal_precise_locations( $store_id, $saved_markers, $terms_slugs );
		}
		?>
	</div>	
	<?php	
}
}

if( !function_exists( 'couponxxl_deal_location_save' ) ){
function couponxxl_deal_location_save( $post_id ) {
	global $wpdb;

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['post_type'] ) && 'offer' == $_POST['post_type'] && isset( $_POST['offer_store'] ) ) {

		couponxxl_save_deal_marker_locations( $post_id );

		delete_post_meta( $post_id, 'offer_store' );
		add_post_meta( $post_id, 'offer_store', $_POST['offer_store'] );		
	}
  
}
add_action( 'save_post', 'couponxxl_deal_location_save' );
}

if( !function_exists('couponxxl_save_deal_marker_locations') ){
function couponxxl_save_deal_marker_locations( $post_id ){
	if( !empty( $_POST['deal_markers'] ) ){
		$terms = array();
		$markers = array();
		foreach( $_POST['deal_markers'] as $term_slug => $marker_ids ){
			$term = get_term_by( 'slug', $term_slug, 'location' );
			if( !empty( $term ) ){
				$terms[] = $term->term_id;
				$markers[] = join( ',', $marker_ids );
			}
		}

		wp_set_post_terms( $post_id, $terms, 'location', false );
		delete_post_meta( $post_id, 'deal_markers' );
		add_post_meta( $post_id, 'deal_markers', join( ',', $markers ) );		
	}
	else{
		delete_post_meta( $post_id, 'deal_markers' );
	}	
}
}
?>