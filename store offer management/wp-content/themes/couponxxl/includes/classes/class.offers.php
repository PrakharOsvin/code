<?php
if( !class_exists( 'WP_Offers_Query' ) ){
class WP_Offers_Query extends WP_Query {
	public $args;
	function __construct( $args = array() ) {

		$args = array_merge( array(
			'post_type' => 'offer',
			'all_offers' => false,
			'offer_type' => '',
			'offer_in_slider' => false,
			'orderby' => 'offer_expire',
			'order' => 'ASC'
		), $args);

		$this->args = $args;


		add_filter( 'posts_fields', array( $this, 'posts_fields' ) );
		add_filter( 'posts_join', array( $this, 'posts_join' ) );

		if( !$args['all_offers'] ){
			add_filter( 'posts_where', array( $this, 'posts_where' ) );
		}

		if( $args['orderby'] == 'offer_expire' ){
			add_filter('posts_orderby', array( $this, 'posts_orderby' ));
		}

		if( !empty( $args['offer_type'] ) ){
			add_filter('posts_where', array( $this, 'filter_post_type' ));
		}

		parent::__construct( $args );

		remove_filter( 'posts_fields', array( $this, 'posts_fields' ) );
		remove_filter( 'posts_join', array( $this, 'posts_join' ) );

		if( !$args['all_offers'] ){
			remove_filter( 'posts_where', array( $this, 'posts_where' ) );
		}

		if( $args['orderby'] == 'offer_expire' ){
			remove_filter('posts_orderby', array( $this, 'posts_orderby' ));
		}

		if( !empty( $args['offer_type'] ) ){
			remove_filter('posts_where', array( $this, 'filter_post_type' ));
		}
	}

	function posts_fields( $sql ) {
		global $wpdb;
		return $sql . ", offers.* ";
	}

	function posts_join( $sql ) {
		global $wpdb;
		return $sql . " INNER JOIN {$wpdb->prefix}offers AS offers ON $wpdb->posts.ID = offers.post_id ";
	}

	function posts_where( $sql ) {
		global $wpdb;
		$sql .= $wpdb->prepare( " AND offers.offer_start <= %d AND offers.offer_expire > %d AND offers.offer_has_items = '1' ", current_time( 'timestamp' ), current_time( 'timestamp' ) );
		$theme_usage = couponxxl_get_option( 'theme_usage' );
		if( $theme_usage !== 'all' ){
			$sql .= $wpdb->prepare(" AND offers.offer_type = %s ", $theme_usage );
		}
		if( $this->args['offer_in_slider'] ){
			$sql .=  " AND offers.offer_in_slider = 'yes' ";
		}

		return $sql;
	}

	function posts_orderby(){
		$orderby_statement = ' offers.offer_expire '.$this->args['order'];
		return $orderby_statement;
	}

	function filter_post_type( $sql ){
		global $wpdb;
		$sql .=  $wpdb->prepare( " AND offers.offer_type = %s ", $this->args['offer_type'] );
		return $sql;
	}
}
}

/*
Get offer type
*/
if( !function_exists( 'couponxxl_get_the_offer_type' ) ){
function couponxxl_get_the_offer_type( $post = 0 ){
	$post = get_post( $post );
	$offer_type = isset( $post->offer_type ) ? $post->offer_type : '';
	$id = isset( $post->ID ) ? $post->ID : 0;

	return apply_filters( 'the_offer_type', $offer_type, $id );
}
}

/*
Get HTML of the countdown
*/
if( !function_exists( 'couponxxl_countdown_html' ) ){
function couponxxl_offer_countdown( $offer_expire ){
	?>
	<div class="countdown">
		<i class="pline-clock"></i>
		<?php if ( $offer_expire !== '99999999999') : ?>
		<span class="deal-countdown" data-single="<?php _e( 'Day', 'couponxxl' ) ?>" data-multiple="<?php _e( 'Days', 'couponxxl' ) ?>" data-expire="<?php echo esc_attr( $offer_expire ) ?>" data-current-time="<?php echo esc_attr( current_time( 'timestamp' ) ); ?>"></span>
			<?php else : ?>
			<span>Unlimited</span>
		<?php endif; ?>
	</div>
	<?php
}
}

/*
Get offer clicks
*/
if( !function_exists( 'couponxxl_get_offer_clicks' ) ){
function couponxxl_get_offer_clicks( $post = 0 ){
	$post = get_post( $post );
	$offer_clicks = !empty( $post->offer_clicks ) ? $post->offer_clicks : 0;
	return $offer_clicks;
}
}

/*
Get offer views
*/
if( !function_exists( 'couponxxl_get_offer_views' ) ){
function couponxxl_get_offer_views( $post_id = 0 ){
	$offer_views = get_post_meta( $post_id, 'offer_views', true );
	$offer_views = !empty( $offer_views ) ? $offer_views : 0;
	return $offer_views;
}
}

/*
Get offer CTR rate
*/
if( !function_exists( 'couponxxl_ctr_percentage' ) ){
function couponxxl_ctr_percentage( $offer_clicks, $offer_views ){
	if( $offer_clicks > 0 && $offer_views > 0 ){
		$percentage = round( ( $offer_clicks / $offer_views ) * 100, 2 );
	}
	else{
		$percentage = 0;
	}
	esc_html_e( 'CTR: ', 'couponxxl' );
	return $percentage.'%';
}
}

/*
Get raw expire time
*/
if( !function_exists( 'couponxxl_raw_expire_time' ) ){
function couponxxl_raw_expire_time( $post = 0 ){
	$post = get_post( $post );
	return $offer_expire = isset( $post->offer_expire ) ? $post->offer_expire : '';
}
}

/*
Get expire time
*/
if( !function_exists( 'couponxxl_get_the_expire_time' ) ){
function couponxxl_get_the_expire_time( $post = 0 ){
	$post = get_post( $post );

	$remaining = '';
	$offer_expire = isset( $post->offer_expire ) ? $post->offer_expire : '';
	$id = isset( $post->ID ) ? $post->ID : 0;

	if( !empty( $offer_expire ) && $offer_expire !== '99999999999' ){
		$diff = $offer_expire - current_time( 'timestamp' );
		if( $diff > 0 ){

			$secondsInAMinute = 60;
			$secondsInAnHour  = 60 * $secondsInAMinute;
			$secondsInADay    = 24 * $secondsInAnHour;

			/* extract days */
			$days = floor( $diff / $secondsInADay );

			/* extract hours */
			$hourSeconds = $diff % $secondsInADay;
			$hours = floor( $hourSeconds / $secondsInAnHour );

			/* extract minutes */
			$minuteSeconds = $hourSeconds % $secondsInAnHour;
			$minutes = floor( $minuteSeconds / $secondsInAMinute );

			/* extract the remaining seconds */
			$remainingSeconds = $minuteSeconds % $secondsInAMinute;
			$seconds = ceil( $remainingSeconds );

			if( $days > 0 ){
				if( $days == 1 ){
					$remaining = '1 '.esc_html__( 'day', 'couponxxl' );
				}
				else{
					$remaining = $days.' '.esc_html__( 'days', 'couponxxl' );
				}
			}
			else if( $hours > 0 ){
				if( $hours == 1 ){
					$remaining = '1 '.esc_html__( 'hour', 'couponxxl' );
				}
				else{
					$remaining = $hours.' '.esc_html__( 'hours', 'couponxxl' );
				}
			}
			else if( $minutes > 0 ){
				if( $minutes == 1 ){
					$remaining = '1 '.esc_html__( 'minute', 'couponxxl' );
				}
				else{
					$remaining = $minutes.' '.esc_html__( 'minutes', 'couponxxl' );
				}
			}
			else if( $seconds > 0 ){
				if( $seconds == 1 ){
					$remaining = '1 '.esc_html__( 'second', 'couponxxl' );
				}
				else{
					$remaining = $seconds.' '.esc_html__( 'seconds', 'couponxxl' );
				}
			}
		}
		else{
			$remaining = esc_html__( 'Expired', 'couponxxl' );
		}
	}
	else{
		$remaining = esc_html__( 'Unlimited Time', 'couponxxl' );
	}

	return apply_filters( 'the_offer_type', $remaining, $id );
}
}

if( !function_exists( 'couponxxl_quantity_input' ) ){
function couponxxl_quantity_input( $post_id = '', $value = '' ){
	if( empty( $post_id ) ){
		$post_id = get_the_ID();
	}

	$deal_items = get_post_meta( $post_id, 'deal_items', true );
	$deal_sales = get_post_meta( $post_id, 'deal_sales', true );
	$deal_items = !empty( $deal_items ) ? $deal_items : 0;

	return '
	<input type="number" min="1" max="'.esc_attr( $deal_items - $deal_sales ).'" name="qty_'.( esc_attr( $post_id ) ).'" value="'.esc_attr( $value ).'"/>
	<input type="hidden" value="'.esc_attr( $post_id ).'" name="items[]" />';
}
}

/*
Calculate owner share
*/
if( !function_exists( 'claculate_owner' ) ){
function couponxxl_calculate_owner_part( $post_id = '' ){
	if( empty( $post_id ) ){
		$post_id = get_the_ID();
	}

	$owned_part = '';

	$deal_sale_price = get_post_meta( $post_id, 'deal_sale_price', true );
	$deal_type = get_post_meta( $post_id, 'deal_type', true );

	if( $deal_type = 'shared' ){
		$deal_owner_price = couponxxl_get_option( 'deal_owner_price_shared' );
	}
	else{
		$deal_owner_price = couponxxl_get_option( 'deal_owner_price_not_shared' );
	}

	if( stristr( $deal_owner_price, '%' ) ){
		$deal_owner_price = str_replace( '%', '', $deal_owner_price );
		$deal_owner_price = round( ($deal_owner_price / 100) * $deal_sale_price, 2 );
	}

	return $deal_owner_price;
}
}

/* 
Calculate part of the seller
*/
if( !function_exists( 'couponxxl_calculate_seller_part' ) ){
function couponxxl_calculate_seller_part( $post_id, $owner_share ){
	$deal_sale_price = get_post_meta( $post_id, 'deal_sale_price', true );
	$deal_type = get_post_meta( $post_id, 'deal_type', true );

	$deal_seller_share = 0;
	if( $deal_type = 'shared' ){
		$deal_seller_share = $deal_sale_price - $owner_share;
	}

	return $deal_seller_share;
}
}

/*
Calculate get amount of the deal
*/
if( !function_exists( 'couponxxl_get_deal_price' ) ){
function couponxxl_get_deal_price( $post_id = '' ){
	if( empty( $post_id ) ){
		$post_id = get_the_ID();
	}

	$deal_type = get_post_meta( $post_id, 'deal_type', true );
	$deal_sale_price = get_post_meta( $post_id, 'deal_sale_price', true );

	if( $deal_type == 'shared' ){
		return $deal_sale_price;
	}
	else{
		return couponxxl_calculate_owner_part( $post_id );
	}
}
}

/*
Get ptice of the dres,
*/
if( !function_exists( 'couponxxl_get_deal_html_price' ) ){
function couponxxl_get_deal_html_price( $post_id = '' ){
	if( empty( $post_id ) ){
		$post_id = get_the_ID();
	}

	$deal_type = get_post_meta( $post_id, 'deal_type', true );
	$deal_price = get_post_meta( $post_id, 'deal_price', true );
	$deal_sale_price = get_post_meta( $post_id, 'deal_sale_price', true );

	$html = '<h3 class="price">'.couponxxl_format_price_number( $deal_sale_price ).' <span class="price-sale">'.couponxxl_format_price_number( $deal_price ).'</span></h3>';

	if( $deal_type == 'not_shared' && is_singular( 'offer' ) ){
		$owned_part = couponxxl_calculate_owner_part( $post_id );
		$html .= '<h3 class="price"><span class="price-sale store-explanation"><a href="#offer_store_explanation" data-toggle="modal">'.esc_html__( 'In store', 'couponxxl' ).'</a> '.esc_html__( 'offer', 'couponxxl' ).' - '.couponxxl_format_price_number( $owned_part ).'</span></h3>';
	}

	return $html;
}
}


/*
Add to cart HTML
*/
if( !function_exists( 'couponxxl_add_to_cart' ) ){
function couponxxl_add_to_cart( $text, $post_id = '' ){
	$account_type = couponxxl_get_account_type();
	if( empty( $account_type ) || $account_type == 'buyer' ){
		if( empty( $post_id ) ){
			$post_id = get_the_ID();
		}
		$deal_link = get_post_meta( $post_id, 'deal_link', true );
		if( !is_single() && !empty( $deal_link ) ){
			return;
		}
		?>
		<a href="javascript:;" class="btn add-to-cart" data-id="<?php echo esc_attr( $post_id ) ?>" title="<?php _e('Add To Cart', 'couponxxl'); ?>"><?php echo  $text ?></a>
		<?php
	}
}
}

/*
get Added to cart HTMl
*/
if( !function_exists( 'couponxxl_added_to_cart' ) ){
function couponxxl_added_to_cart(){
	return '<a href="'.couponxxl_get_permalink_by_tpl( 'page-tpl_cart' ).'" class="btn view-cart" title="'.esc_attr__( 'View Cart', 'couponxxl').'"><i class="pline-eye"></i></a>';
}
}

/* 
Create price and add to cart for the deal
*/
if( !function_exists( 'couponxxl_deal_meta' ) ){
function couponxxl_deal_meta( $post_id = '', $show_cart = true ){
	if( empty( $post_id ) ){
		$post_id = get_the_ID();
	}
	?>
	<div class="clearfix deal-meta">
		<div class="pull-left">
			<?php echo couponxxl_get_deal_html_price( $post_id ); ?>
		</div>
		<?php
		$account_type = couponxxl_get_account_type();
		if( ( empty( $account_type ) || $account_type == 'buyer' ) && $show_cart ): ?>
			<div class="pull-right">
				<?php couponxxl_add_to_cart( '<i class="pline-cart"></i>', $post_id ); ?>
			</div>
		<?php endif;?>
	</div>
	<?php

}
}

/*
Get couponnxxl_meta
*/
if( !function_exists( 'couponxxl_get_deal_meta' ) ){
function couponxxl_get_deal_meta( $post_id = '', $show_cart = true ){
	if( empty( $post_id ) ){
		$post_id = get_the_ID();
	}
	ob_start();
	couponxxl_deal_meta( $post_id, $show_cart );
	$data = ob_get_contents();
	ob_end_clean();

	return $data;
}
}


/*
Get row from the custom table for offer single
*/
if( !function_exists( 'couponxxl_get_offer_meta' ) ){
function couponxxl_get_offer_meta( $post_id ){
	global $wpdb;
	$meta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}offers WHERE post_id = %d", $post_id ) );
	if( !empty( $meta ) ){
		return $meta[0];
	}
	else{
		return '';
	}
}
}

/*
Count offers per category and store them in cache
*/

if( !function_exists( 'couponxxl_category_count' ) ){
$cxxl_category_count = array();
function couponxxl_category_count( $cat_id ){
	global $cxxl_category_count;
	$cxxl_category_count = maybe_unserialize( get_transient( 'cxxl_category_count' ) );
	if( empty( $cxxl_category_count ) ){
		global $wpdb;
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT terms.term_taxonomy_id AS term_id, count(*) as offers_count FROM {$wpdb->term_relationships} AS terms LEFT JOIN {$wpdb->prefix}offers AS offers ON terms.object_id = offers.post_id WHERE offers.offer_expire > %d AND offers.offer_start <= %d GROUP BY terms.term_taxonomy_id", current_time( 'timestamp' ), current_time( 'timestamp' ) ) );
		if( !empty( $results ) ){
			foreach( $results as $res ){
				$cxxl_category_count[$res->term_id] = $res->offers_count;
			}

			set_transient( 'cxxl_category_count', $cxxl_category_count, 900 );
		}
	}

	if( !empty( $cxxl_category_count[$cat_id] ) ){
		return $cxxl_category_count[$cat_id];
	}
	else{
		return 0;
	}

}
}

/* Offers shortcode listing */
if( !function_exists( 'couponxxl_offer_listing_shortcode' ) ){
function couponxxl_offer_listing_shortcode( $atts ){
	extract( shortcode_atts( array(
		'categories' => '',
		'locations' => '',
		'stores' => '',
		'number' => '3',
		'items_per_row' => '3',
		'orderby' => 'offer_expire',
		'order' => 'ASC',
		'items' => '',
		'offer_type' => 'coupon'
	), $atts ) );

	$items = explode( ",", $items );
	if( !empty( $categories ) || !empty( $locations ) || !empty( $stores ) || !empty( $number ) ){
	    $args = array(
	        'post_type' => 'offer',
	        'post_status' => 'publish',
	        'posts_per_page' => $number,
	        'offer_type' => $offer_type,
	        'orderby' => $orderby,
	        'order' => $order,
	        'tax_query' => array(
	            'relation' => 'AND'
	        ),
	        'meta_query' => array(
	            'relation' => 'AND'
	        ),
	    );
	    if( !empty( $categories ) ){
	        $args['tax_query'][] = array(
	            'taxonomy' => 'offer_cat',
	            'field' => 'slug',
	            'terms' => explode( ",", $categories ),
	            'operator' => 'IN'
	        );
	    }
	    if( !empty( $locations ) ){
	        $args['tax_query'][] = array(
	            'taxonomy' => 'location',
	            'field' => 'slug',
	            'terms' => explode( ",", $locations ),
	            'operator' => 'IN'
	        );
	    }
	    if( !empty( $stores ) ){
	        $args['meta_query'][] = array(
	            'key' => 'offer_store',
	            'value' => explode( ",", $stores ),
	            'compare' => 'IN',
	        );
	    }
	}

	else if( !empty( $items ) ){
	    $args = array(
	        'post_type' => 'offer',
	        'post_status' => 'publish',
	        'offer_type' => $offer_type,
	        'posts_per_page' => '-1',
	        'post__in' => $items,
	        'orderby' => $orderby,
	        'order' => $order,
	    );
	}

	$coupons = new WP_Offers_Query( $args );
	$counter = 0;
	if( $coupons->have_posts() ){
	    ?>
	    <div class="row">
	    <?php
	    while( $coupons->have_posts() ){
	        if( $counter == $items_per_row ){
	            echo '</div><div class="row">';
	            $counter = 0;
	        }
	        $counter++;
	        $coupons->the_post();
	        ?>
	        <div class="col-sm-<?php echo esc_attr( 12 / $items_per_row ) ?>">
	            <?php include( couponxxl_load_path( 'includes/offers/offers.php' ) ); ?>
	        </div>
	        <?php
	    }
	    ?>
	    </div>
	    <?php
	    wp_reset_postdata();
	}
}
}

/*Create markers*/
if( !function_exists( 'couponxxl_generate_markers' ) ){
function couponxxl_generate_markers( $deals ){
	$markers = array();
	if( !empty( $deals ) ){
		foreach( $deals as $deal ){
			$windowHTML = '
			<div class="deal-info-wrap">
				<a href="'.esc_url( $deal['permalink'] ).'" target="_blank">
					'.$deal['image'].'
				</a>
				<div class="deal-info">
					<h6>
						<a href="'.esc_url( $deal['permalink'] ).'" target="_blank">
							'.$deal['title'].'
						</a>
					</h6>
					'.$deal['offer_price'].'
				</div>
			</div>';

			$locations = array();


			$markers[] = array(
				'windowHTML' => $windowHTML,
				'locations' => couponxxl_generate_markers_map_locations( $deal['deal_markers'] )
			);
		}
	}

	echo json_encode( $markers, JSON_HEX_QUOT | JSON_HEX_TAG );
}
}

/*
Generate array for the markers
*/
if( !function_exists('couponxxl_generate_markers_map_locations') ){
function couponxxl_generate_markers_map_locations( $deal_markers ){
	global $wpdb;
	$locations = array();
	if( !empty( $deal_markers ) ){
		$locs = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}store_markers WHERE marker_id IN ( ".esc_sql( $deal_markers )." ) " );
		if( !empty( $locs ) ){
			foreach( $locs as $loc ){
				$locations[] = array(
					"latitude" => $loc->latitude,
					"longitude" => $loc->longitude
				);
			}
		}
	}

	return $locations;
}
}

/*Generate markers for the deal single*/
if( !function_exists('couponxxl_deal_single_markers') ){
function couponxxl_deal_single_markers( $deal_markers ){
	$markers = array(
		array(
			'windowHTML' => '',
			'locations' => couponxxl_generate_markers_map_locations( $deal_markers )
		)
	);

	echo json_encode( $markers, JSON_HEX_QUOT | JSON_HEX_TAG );
}
}

/*
Get deal sales status
*/
if( !function_exists( 'couponxxl_deal_has_items' ) ){
function couponxxl_deal_has_items( $post = 0 ){
	$post = get_post( $post );
	return $post->offer_has_items == '1' ? true : false;
}
}
?>