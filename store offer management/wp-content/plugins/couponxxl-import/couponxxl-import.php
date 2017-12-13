<?php
/*
Plugin Name: CouponXxL Import
Description: Make sure that you have installed WP All Import in order to use this functionality
Version: 1.1
Author: PowerThemes
*/

include "rapid-addon.php";

$couponxxl_addon = new RapidAddon('CouponXxL Offers', 'couponxxl_addon');
/*
Add fields
*/
$couponxxl_addon->add_title( __( 'Offer Details', 'couponxxl' ) );
$couponxxl_addon->add_text( __( 'All fields for importing offers are located in this section and you do not need to set anything in the Custom Fields section regarding offers. All required fields are marked with *', 'couponxxl' ) );

$couponxxl_addon->add_field( 
	'offer_type', 
	__( 'Offer Type (In database - coupon/deal) *', 'couponxxl' ), 
	'radio',
	array(
		'coupon' => __( 'Coupon', 'couponxxl' ),
		'deal' => __( 'Deal', 'couponxxl' ),
	),
	__( 'If feed is coupons only select Coupon, if it is deals only select Deal, if itis mixed select third option and drag the value which stores type of the offer. Additinally if your feed calls coupon / deal differently you can map it to the values in database by clicking on the Field Options->Mapping.', 'couponxxl' )
);

$couponxxl_addon->add_field( 'offer_start', __( 'Offer Start', 'couponxxl' ), 'text', null, __( 'Value needs to be in UNIX timestamp. Try to use strtotime($time) function here like this [strtotime({my_element[1]})] or you can use your custom function', 'couponxxl' ) );
$couponxxl_addon->add_field( 'offer_expire', __( 'Offer Expire', 'couponxxl' ), 'text', null, __( 'Value needs to be in UNIX timestamp. Try to use strtotime($time) function here like this [strtotime({my_element[1]})] or you can use your custom function', 'couponxxl' ) );

$couponxxl_addon->add_field( 
	'offer_store', 
	__( 'Offer Store *', 'couponxxl' ), 
	'radio',
	array(
		'store_existing' => array(
			__( 'Existing Store', 'couponxxl' ),
			$couponxxl_addon->add_field( 'offer_store_id_name', __( 'Store ID Or Name *', 'couponxxl' ), 'text' )
		),
		'store_new' => array(
			__( 'New Store ( There is a built in check not to duplicate store if exists for multiple offers in the feed - based on store title )', 'couponxxl' ),
			$couponxxl_addon->add_field( 'store_title', __( 'Store Title *', 'couponxxl' ), 'text' ),
			$couponxxl_addon->add_field( 'store_description', __( 'Store Description', 'couponxxl' ), 'text' ),
			$couponxxl_addon->add_field( 'store_link', __( 'Store Link', 'couponxxl' ), 'text' ),
			$couponxxl_addon->add_field( 'store_facebook', __( 'Store Facebook', 'couponxxl' ), 'text' ),
			$couponxxl_addon->add_field( 'store_twitter', __( 'Store Twitter', 'couponxxl' ), 'text' ),
			$couponxxl_addon->add_field( 'store_google', __( 'Store Google', 'couponxxl' ), 'text' ),
			$couponxxl_addon->add_field( 'store_rss', __( 'Store RSS', 'couponxxl' ), 'text' ),
			$couponxxl_addon->add_field( 'store_logo', __( 'Store Logo', 'couponxxl' ), 'image' ),
		)
	)
);

$couponxxl_addon->add_title( __( 'Coupon Data *', 'couponxxl' ) );
$couponxxl_addon->add_text( __( 'Find one representative of the coupons in your feed in order to be able to populate data for the coupons. Inline with this find representative ( if exists ) of the Code, Sale and Printable coupon in order to populate data for each type of coupon.', 'couponxxl' ) );
$couponxxl_addon->add_field( 'coupon_code', __( 'Coupon Code (for Code coupons)', 'couponxxl' ), 'text' );
$couponxxl_addon->add_field( 'coupon_sale', __( 'Coupon Sale Link (for Sale coupons)', 'couponxxl' ), 'text' );
$couponxxl_addon->add_field( 'coupon_image', __( 'Coupon Image (for Printable coupons)', 'couponxxl' ), 'image' );
$couponxxl_addon->add_field( 'coupon_link', __( 'Coupon Affiliate Link', 'couponxxl' ), 'text' );


$couponxxl_addon->add_title( __( 'Deal Data', 'couponxxl' ) );
$couponxxl_addon->add_text( __( 'Find one representative of the deals in your feed in order to be able to populate data for the deals. Inline with this find representative of the Webisite Offer and Store Offer to populate deal type.', 'couponxxl' ) );
$couponxxl_addon->add_field( 'deal_items', __( 'Deal Items *', 'couponxxl' ), 'text', null, __( 'Number of items you wish to sell', 'couponxxl' ) );
$couponxxl_addon->add_field( 'deal_item_vouchers', __( 'Deal Vouchers', 'couponxxl' ), 'textarea', null, __( 'List of vouchers which will be used for items, leave empty to generate random ones.', 'couponxxl' ) );
$couponxxl_addon->add_field( 'deal_item_vouchers_separator', __( 'Deal Vouchers Separator (leave it blanjk if separator is new line)', 'couponxxl' ), 'text', null, __( 'If you have one field with all voucehrs you can input here separator which will be used to split them.', 'couponxxl' ) );
$couponxxl_addon->add_field( 'deal_min_sales', __( 'Deal Minimum Sales', 'couponxxl' ), 'text', null, __( 'Number of sales whicih is required in order for deal to become active', 'couponxxl' ) );
$couponxxl_addon->add_field( 'deal_price', __( 'Deal Price (number only) *', 'couponxxl' ), 'text' );
$couponxxl_addon->add_field( 'deal_sale_price', __( 'Deal Sale Price (number only) *', 'couponxxl' ), 'text' );
$couponxxl_addon->add_field( 'deal_voucher_expire', __( 'Deal Voucher Expire Date (date when voucher(s) will expire if not used before)', 'couponxxl' ), 'text', null, __( 'Value needs to be in UNIX timestamp. Try to use strtotime($time) function here like this [strtotime({my_element[1]})] or you can use your custom function', 'couponxxl' ) );
$couponxxl_addon->add_field( 
	'deal_type', 
	__( 'Deal Type (In databse shared/not_shared) *', 'couponxxl' ), 
	'radio',
	array(
		'shared' => __( 'Website Offer ( Owner gets its share from the sale price )', 'couponxxl' ),
		'not_shared' => __( 'Store Offer ( Owner gets its share by custom value from buyer )', 'couponxxl' ),
	),
	__( 'Website Offer means that deal is sold on the site and part for the voucher is taken from sale price. Store offer means that custom value is charged to buyer for the voucher and he pays sale price at the store. If site is being used for the affiliate only ( you just server deal and not handle the selling ) then select Website Offer' )
);

$couponxxl_addon->add_field( 'deal_link', __( 'Deal External Link ( If site is used as affilaiterty site )', 'couponxxl' ), 'text' );

$couponxxl_addon->disable_default_images();
$couponxxl_addon->import_images( 'couponxxl_p_process_images', __( 'CouponXxL Offer Images', 'couponxxl' ) );

/*
Add advanced options for all the meta fields which are not crutial for offersto work
*/

$couponxxl_addon->add_options( null, __( 'Advanced Options', 'couponxxl' ), array(
	$couponxxl_addon->add_field( 
		'offer_in_slider', 
		__( 'Offer In Slider (In databse yes/no) *', 'couponxxl' ), 
		'radio',
		array(
			'yes' => __( 'Yes', 'couponxxl' ),
			'no' => __( 'No', 'couponxxl' ),
		)
	),
	$couponxxl_addon->add_field( 'offer_thumbs_up', __( 'Offer Thumbs Up', 'couponxxl' ), 'text' ),
	$couponxxl_addon->add_field( 'offer_thumbs_down', __( 'Offer Thumbs Down', 'couponxxl' ), 'text' ),
	$couponxxl_addon->add_field( 'offer_thumbs_recommend', __( 'Offer Thumbs Recommend Percentage ( Formula: ( UP/(UP+DOWN)*100 ) )', 'couponxxl' ), 'text' ),
	$couponxxl_addon->add_field( 'offer_clicks', __( 'Offer Clicks', 'couponxxl' ), 'text', null, __( 'Number of visits to offer single page', 'couponxxl' ) ),
	$couponxxl_addon->add_field( 'offer_views', __( 'Offer Views', 'couponxxl' ), 'text', null, __( 'Number of views on offer listing pages', 'couponxxl' ) )
));

/*
Starting import functions and handling of import data
*/
$couponxxl_addon->set_import_function('couponxxl_p_import');

$couponxxl_addon->admin_notice( __( 'CouponXxL recommends that you install WP All Import In order to use import functionality or', 'couponxxl' ).'<a href="%s">'.__( 'ignore', 'couponxxl' ).'</a>' );

$couponxxl_addon->run(
	array(
		"post_types" => array( "offer" )
	)
); 

/*
Set images as deal images if there is more then one and set the first one as featured
*/
$list_of_images = array();
function couponxxl_p_process_images( $post_id, $attachement_id, $attachement_url, $action ){
	global $couponxxl_addon, $list_of_images;
	$post_thumbnail_id = get_post_thumbnail_id( $post_id );
	if( !empty( $post_thumbnail_id ) ){
		if( $post_thumbnail_id !== $attachement_id ){
			$list_of_images[] = $attachement_id;
		}
	}
	else{
		set_post_thumbnail( $post_id, $attachement_id );
	}
	if( !empty( $list_of_images ) ){
		$counter = 0;
		$deal_images_array = array();
		foreach ( $list_of_images as $deal_image ) {
			$deal_images_array['sm-field-'.$counter] = $deal_image;
			$counter++;
		}
		$deal_images = array( serialize( $deal_images_array ) );
		update_post_meta( $post_id, 'deal_images', implode( "", $deal_images ) );
	}
}

function couponxxl_p_import_offer( $post_id, $data ){

	couponxxl_update_post_meta( $data['offer_type'], 'offer_type', $post_id );
	update_post_meta( $post_id, 'offer_store', $data['offer_store'] );

	if( empty( $data['offer_start'] ) ){
		$data['offer_start'] = time();
	}
	couponxxl_update_post_meta( $data['offer_start'], 'offer_start', $post_id );

	if( empty( $data['offer_expire'] ) ){
		$data['offer_expire'] = 99999999999;
	}
	couponxxl_update_post_meta( $data['offer_expire'], 'offer_expire', $post_id );


	/* SAVE COUPON SPECIFIC DATA */
	if( $data['offer_type'] == 'coupon' ){
		update_post_meta( $post_id, 'coupon_type', $data['coupon_type'] );
		switch( $data['coupon_type'] ){
			case 'code' : update_post_meta( $post_id, 'coupon_code', $data['coupon_code'] ); break;
			case 'sale' : update_post_meta( $post_id, 'coupon_sale', $data['coupon_sale'] ); break;
			case 'printable' : update_post_meta( $post_id, 'coupon_image', $data['coupon_image']['attachment_id'] ); break;
		}
		
		update_post_meta( $post_id, 'coupon_link', $data['coupon_link'] );
	}	
	/* SAVE DEAL SPECIFIC DATA */
	else{
		update_post_meta( $post_id, 'deal_link', $data['deal_link'] );
		update_post_meta( $post_id, 'deal_items', $data['deal_items'] );
		if( !empty($data['deal_item_vouchers_separator']) ){
			$vouchers = explode( $data['deal_item_vouchers_separator'], $data['deal_item_vouchers'] );
			$data['deal_item_vouchers'] = implode("\n", $data['deal_item_vouchers']);
		}
		update_post_meta( $post_id, 'deal_item_vouchers', $data['deal_item_vouchers'] );
		update_post_meta( $post_id, 'deal_min_sales', $data['deal_min_sales'] );
		update_post_meta( $post_id, 'deal_voucher_expire', $data['deal_voucher_expire'] );
		update_post_meta( $post_id, 'deal_price', $data['deal_price'] );
		update_post_meta( $post_id, 'deal_sale_price', $data['deal_sale_price'] );
		update_post_meta( $post_id, 'deal_type', $data['deal_type'] );
	}


	couponxxl_update_post_meta( $data['offer_in_slider'], 'offer_in_slider', $post_id );
	update_post_meta( $post_id, 'offer_thumbs_up', $data['offer_thumbs_up'] );
	update_post_meta( $post_id, 'offer_thumbs_down', $data['offer_thumbs_down'] );
	couponxxl_update_post_meta( $data['offer_thumbs_recommend'], 'offer_thumbs_recommend', $post_id );
	couponxxl_update_post_meta( $data['offer_has_items'], 'offer_has_items', '1' );
	couponxxl_update_post_meta( $data['offer_clicks'], 'offer_clicks', $post_id );
	update_post_meta( $post_id, 'offer_views', $data['offer_views'] );
}

function couponxxl_p_check_store_by_id_title( $offer_store ){
	$offer_store_id = false;
	if( !is_numeric( $offer_store ) ){
		$post = get_page_by_title( $offer_store, 'OBJECT', 'store' );
		if( !empty( $post ) ){
			$offer_store_id = $post->ID;
		}
	}
	else{
		$post = get_post( $offer_store );
		if( !empty( $post ) ){
			$offer_store_id = $post->ID;
		}
	}

	return $offer_store_id;
}

function couponxxl_p_import_store( $data ){
	$offer_store_id = false;
	if( $data['offer_store'] == 'store_existing' ){
		$offer_store = couponxxl_p_check_store_by_id_title( $data['offer_store_id_name'] );
		if( $offer_store ) {
			$offer_store_id = $offer_store;
		}		
	}
	else if( $data['offer_store'] == 'store_new' ){
		if( !empty( $data['store_title'] ) ){
			$offer_store = couponxxl_p_check_store_by_id_title( $data['store_title'] );
			if( !$offer_store ){
				$offer_store_id = wp_insert_post(array(
					'post_type' => 'store',
					'post_status' => 'publish',
					'post_title' => $data['store_title'],
					'post_content' => $data['store_description'],
				));
				if( !empty( $data['store_logo']['attachment_id'] ) ){
					set_post_thumbnail( $offer_store_id, $data['store_logo']['attachment_id'] );
				}
				update_post_meta( $offer_store_id, 'store_link', $data['store_link'] );
				update_post_meta( $offer_store_id, 'store_facebook', $data['store_facebook'] );
				update_post_meta( $offer_store_id, 'store_twitter', $data['store_twitter'] );
				update_post_meta( $offer_store_id, 'store_google', $data['store_google'] );
				update_post_meta( $offer_store_id, 'store_rss', $data['store_rss'] );
			}
			else{
				$offer_store_id = $offer_store;
			}
		}
		else{
			$couponxxl_addon->log( __( 'Store name is required for creating store, skipping this offer', 'couponxxl' ) );
		}
	}

	return $offer_store_id;
}

function couponxxl_p_import($post_id, $data, $import_options) {
	global $couponxxl_addon, $list_of_images;
	$list_of_images = array();

	if ( $couponxxl_addon->can_update_meta( 'offer_type', $import_options ) ) {
		if( !empty( $data['offer_type'] ) ){
			if( !empty( $data['offer_store'] ) ){

				$offer_store_id = couponxxl_p_import_store( $data );

				if( !empty( $offer_store_id ) ){
					$data['offer_store'] = $offer_store_id;
					if( $data['offer_type'] == 'coupon' ){
						$coupon_check_pass = false;

						if( !empty( $data['coupon_code'] ) ){
							$coupon_check_pass = true;
							$data['coupon_type'] = 'code';
						}
						else if( !empty( $data['coupon_sale'] ) ){
							$coupon_check_pass = true;
							$data['coupon_type'] = 'sale';
						}
						else if( !empty( $data['coupon_image']['attachment_id'] ) ){
							$coupon_check_pass = true;
							$data['coupon_type'] = 'printable';
						}
						if( $coupon_check_pass ){
							couponxxl_p_import_offer( $post_id, $data );
						}
						else{
							$couponxxl_addon->log( __( 'Coupon has no any data so type is undefined, skipping this offer', 'couponxxl' ) );
						}
					}
					else if( $data['offer_type'] == 'deal' ){
						if( !empty( $data['deal_items'] ) ){
							if( !empty( $data['deal_price'] ) ){
								if( !empty( $data['deal_sale_price'] ) ){
									if( !empty( $data['deal_type'] ) ){
										couponxxl_p_import_offer( $post_id, $data );
									}
									else{
										$couponxxl_addon->log( __( 'Missing deal type, skipping this offer', 'couponxxl' ) );
									}
								}
								else{
									$couponxxl_addon->log( __( 'Missing deal sale price, skipping this offer', 'couponxxl' ) );
								}
							}
							else{
								$couponxxl_addon->log( __( 'Missing deal price, skipping this offer', 'couponxxl' ) );
							}
						}
						else{
							$couponxxl_addon->log( __( 'Missing deal number of items, skipping this offer', 'couponxxl' ) );
						}
					}
					else{
						$couponxxl_addon->log( __( 'Unrecognized offer type, skipping this offer', 'couponxxl' ) );
					}					
				}
				else{
					$couponxxl_addon->log( __( 'Store with provided ID / name does not exists, skipping this offer', 'couponxxl' ) );
				}
			}
			else{
				$couponxxl_addon->log( __( 'Missing offer store, skipping this offer', 'couponxxl' ) );
			}
		}
		else{
			$couponxxl_addon->log( __( 'Missing offer type, skipping this offer', 'couponxxl' ) );
		}
		couponxxl_update_post_meta( $data['offer_type'], 'offer_type', $post_id );
		update_post_meta( $post_id, 'offer_type', $data['offer_type'] );
	}
}
?>