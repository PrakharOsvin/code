<?php

/*
Calculate and update thumbnails
*/
if( !function_exists( 'couponxxl_thumb_rate' ) ){
function couponxxl_thumb_rate(){
	$offer_id = !empty( $_GET['offer_id'] ) ? $_GET['offer_id'] : '';
	$value = !empty( $_GET['value'] ) ? $_GET['value'] : '';
	$can_thumbs = couponxxl_can_thumbs( $offer_id );
	if( !empty( $offer_id ) && $can_thumbs === true ){
		$offer_thumbs_up = get_post_meta( $offer_id, 'offer_thumbs_up', true );
		$offer_thumbs_down = get_post_meta( $offer_id, 'offer_thumbs_down', true );
		if( $value == 'up' ){
			$offer_thumbs_up++;
			update_post_meta( $offer_id, 'offer_thumbs_up', $offer_thumbs_up );
		}
		else{
			$offer_thumbs_down++;
			update_post_meta( $offer_id, 'offer_thumbs_down', $offer_thumbs_down );
		}

		$offer_thumbs_recommend = 0;
		if( $offer_thumbs_up + $offer_thumbs_down > 0 ){
			$offer_thumbs_recommend = round( ( $offer_thumbs_up / ( $offer_thumbs_up + $offer_thumbs_down ) ) * 100 );
		}

		couponxxl_update_post_meta( $offer_thumbs_recommend, 'offer_thumbs_recommend', $offer_id );

		update_post_meta( $offer_id, 'offer_thumbs_ip', $_SERVER['REMOTE_ADDR'] );

		echo couponxxl_thumbs_html( $offer_id ).'<span>'.esc_html__( 'Thank you for your feedback', 'couponxxl' ).'</span>';
		die();
	}
}  
add_action('wp_ajax_thumb_rate', 'couponxxl_thumb_rate');
add_action('wp_ajax_nopriv_thumb_rate', 'couponxxl_thumb_rate');
}

/*
Calcualte average of the thumbs
*/
if( !function_exists( 'couponxxl_can_thumbs' ) ){
function couponxxl_can_thumbs( $offer_id ){
	global $wpdb;
	$offer = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}offers WHERE post_id = %d", $offer_id ) );
	if( !empty( $offer[0] ) ){
		$offer = $offer[0];
		if( !is_user_logged_in() ){
			return esc_html__( 'Only loggeed in users can rate.', 'couponxxl' );
		}
		else{
			$rated = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_value = %s", $offer_id, $_SERVER['REMOTE_ADDR'] ) );			
			if( !empty( $rated ) ){
				return esc_html__( 'You have already rated this offer.', 'couponxxl' );
			}
			else if( $offer->offer_type == 'deal' ){
				$bought = $wpdb->get_var( $wpdb->prepare( "SELECT item_id FROM {$wpdb->prefix}order_items WHERE offer_id = %d AND buyer_id = %s", $offer_id, get_current_user_id() ) );			
				if( !empty( $bought ) ){
					return true;	
				}
				else{
					return esc_html__( 'You did not bought this offer.', 'couponxxl' );			
				}
			}
			else{
				return true;
			}
		}
	}
	else{
		return esc_html__( 'Offer does not exists.', 'couponxxl' );
	}
}
}

/*
Get thumb value
*/
if( !function_exists( 'couponxxl_thumbs_html' ) ){
function couponxxl_thumbs_html( $offer_id = '' ){
	if( empty( $offer_id ) ){
		$offer_id = get_the_ID();
	}

	$offer_thumbs_up = get_post_meta( $offer_id, 'offer_thumbs_up', true );
	$offer_thumbs_down = get_post_meta( $offer_id, 'offer_thumbs_down', true );
	$offer_thumbs_recommend = 0;
	if( $offer_thumbs_up + $offer_thumbs_down > 0 ){
		$offer_thumbs_recommend = round( ( $offer_thumbs_up / ( $offer_thumbs_up + $offer_thumbs_down ) ) * 100 );
	}

	$can_thumbs = couponxxl_can_thumbs( $offer_id );
	if( $can_thumbs === true ){
		$class = 'can-rate';
		$error = '';
	}
	else{
		$class = 'cant-rate';
		$error = $can_thumbs;
	}

	$html = '<span class="thumb-recommended">'.$offer_thumbs_recommend.'%</span> '.esc_html__( 'of', 'couponxxl' ).' <strong>'.($offer_thumbs_up + $offer_thumbs_down).'</strong> '.esc_html__( 'customers recommended', 'couponxxl' );
	$html .= '<div class="thumb-actions">
				<a href="javascript:;" class="thumb-rate '.esc_attr( $class ).'" data-offer_id="'.esc_attr( $offer_id ).'" data-error="'.esc_attr( $error ).'" data-value="up"><i class="fa fa-thumbs-o-up"></i></a>
				<a href="javascript:;" class="thumb-rate '.esc_attr( $class ).'" data-offer_id="'.esc_attr( $offer_id ).'" data-error="'.esc_attr( $error ).'" data-value="down"><i class="fa fa-thumbs-o-down"></i></a>
			  </div>';

	return '<div class="thumbs-wrap">'.$html.'</div>';
}
}
?>