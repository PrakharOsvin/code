<?php

class CouponXXL_Cart{

	public $storage;
	public $product_ids;
	public $transient;

	public 	function __construct(){

		$this->transient = 'couponxxl_cart'.get_current_user_id();

		if( is_user_logged_in() ){
			$this->storage = 'transient';
		}
		else{
			$this->storage = 'cookie';
		}

		$this->_get_product_ids();

        add_action( 'wp_ajax_update_cart', array( $this, 'update_cart' ) );
        add_action( 'wp_ajax_nopriv_update_cart', array( $this, 'update_cart' ) );

	}

	private function _set_product_ids(){
		if ( $this->storage == 'cookie'  ){
			setcookie( 'couponxxl_cart', maybe_serialize( $this->product_ids ), time()+3600*24, '/' ) ;
		}
		else{
			set_transient( $this->transient, $this->product_ids );
		}
	}

	private function _get_product_ids(){
		if ( isset( $_COOKIE['couponxxl_cart'] )  ){
			$this->product_ids = maybe_unserialize( stripslashes( $_COOKIE['couponxxl_cart'] ) );
			if( $this->storage == 'transient' ){
				setcookie( 'couponxxl_cart', '', 0, '/' ) ;
				$this->_set_product_ids();
			}
		}
		else{
			$this->product_ids = maybe_unserialize( get_transient( $this->transient ) );
		}
	}

	public function get_products_count(){
		$count = 0;
		if( !empty( $this->product_ids ) ){
			foreach( $this->product_ids as $id => $qty ){
				$count += $qty;
			}
		}

		return $count;
	}

	public function is_in_cart( $offer_id ){
		if( !empty( $this->product_ids ) ){
			foreach( $this->product_ids as $id => $qty ){
				if( $id == $offer_id ){
					return true;
				}
			}
			return false;
		}
		else{
			return false;
		}
	}

	public function update_cart(){
		switch( $_POST['cart_action'] ){
			case 'delete':
				$this->delete_product_ids();
				break;
			case 'update':
				$this->update_product_ids();
				break;
		}
		$response['small'] = $this->get_products_count();
		if( !empty( $_POST['cart'] ) ){
			$response['cart'] = $this->cart();
		}
		else{
			$response['added_to_cart'] = couponxxl_added_to_cart();
		}

		$this->_set_product_ids();

		echo json_encode( $response );
		die();
	}

	public function update_product_ids(){
		if( !empty( $_POST['items'] ) ){
			if( !empty( $_POST['cart'] ) ){
				$this->product_ids = array();
				if( !empty( $_POST['items'] ) ){
					foreach( $_POST['items'] as $offer_id ){
						$this->product_ids[ $offer_id] = $_POST['qty_'.$offer_id];
					}
				}
			}
			else{
				$added = false;
				$product_id = array_shift( $_POST['items'] );
				if( !empty( $this->product_ids ) ){
					foreach( $this->product_ids as $id => &$qty ){
						if( $product_id == $id ){
							$qty += 1;
							$added = true;
						}
					}
				}

				if( !$added ){
					$this->product_ids[$product_id] = 1;
				}
			}
		}
	}

	public function delete_product_ids(){
		$product_id = $_POST['product_id'];	
		$this->unset_product( $product_id );
		
	}

	private function unset_product( $product_id ){
		unset( $this->product_ids[$product_id] );
	}

	public function check_products(){
		$html = '';
		if( !empty( $this->product_ids ) ){
			foreach( $this->product_ids as $id => $qty ){
				$deal_items = get_post_meta( $id, 'deal_items', true );
				$deal_sales = get_post_meta( $id, 'deal_sales', true );
				$available = $deal_items - $deal_sales;
				if( $available == 0 ){
					$this->unset_product( $id );
					$html .= '<div class="alert alert-danger">'.esc_html__( 'Product ', 'couponxxl' ).'<strong>'.get_the_title( $id ).'</strong>'.esc_html__( ' is not available and it is removed from your cart.', 'couponxxl' ).'</div>';
				}
				else if( $qty > $available ){
					$this->product_ids[$id] = $available;
					$html .= '<div class="alert alert-info">'.esc_html__( 'Product ', 'couponxxl' ).'<strong>'.get_the_title( $id ).'</strong>'.esc_html__( ' items changed to available ', 'couponxxl' ).'<strong>'.$available.'</strong>'.esc_html__( ' items instead of ', 'couponxl' ).'<strong>'.$qty.'</strong></div>';
				}
			}
			$this->_set_product_ids();
		}

		return $html;
	}

	public function get_checkout_link(){
		global $COUPONXXL_GATEWAYS;
		$checkout_link = esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_checkout' ) );
		if( !is_user_logged_in() ){
			$checkout_link = esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_register' ) );
		}
		else if( sizeof( $COUPONXXL_GATEWAYS ) == 1 ){
			$only = array_shift( $COUPONXXL_GATEWAYS );
			$checkout_link = esc_url( add_query_arg( array('gateway' => $only['slug'] ), $checkout_link ) );
		}
		else if( $checkout_link !== 'javascript:;' ){
			$checkout_link = '#';
		}

		return $checkout_link;
	}

	public function cart( $echo = false ){
		$html = '';
		$total = 0;
		$products = 0;

		$html = $this->check_products();

		if( !empty( $this->product_ids ) ){

			$html .= '<div class="table-responsive"><form class="cart-form"><table class="table">';
			$html .= '<tr class="table-title">
				<th></th>
				<th></th>
				<th>'.esc_html__( 'Name', 'couponxxl' ).'</th>
				<th>'.esc_html__( 'Quantity', 'couponxxl' ).'</th>
				<th>'.esc_html__( 'Product Price', 'couponxxl' ).'</th>
				<th>'.esc_html__( 'Subtotal', 'couponxxl' ).'</th>
			</tr>';
			$products = 0;
			foreach( $this->product_ids as $id => $qty ){
				$product_price = couponxxl_get_deal_price( $id );
				$subtotal = $qty * $product_price;
				$total += $subtotal;
				$html .= '
				<tr>
					<td class="item-remove">
						<a href="javascript:;" class="remove-item" data-id="'.esc_attr( $id ).'">
							<i class="fa fa-times"></i>
						</a>
					</td>
					<td class="item-img">
						<a href="'.esc_html( get_permalink( $id ) ).'">
							'.get_the_post_thumbnail( $id, 'thumbnail' ).'
						</a>
					</td>
					<td class="item-name">
						<a href="'.esc_html( get_permalink( $id ) ).'">
							'.get_the_title( $id ).'
						</a>
					</td>
					<td class="item-qty">
						'.couponxxl_quantity_input( $id, $qty ).'
					</td>
					<td class="item-price">
						'.couponxxl_format_price_number( $product_price ).'
					</td>
					<td class="item-subtotal">
						'.couponxxl_format_price_number( $subtotal ).'
					</td>
				</tr>';
				$products++;
			}

			$checkout_link = $this->get_checkout_link();

			$html .= '</table>
			<input type="hidden" name="action" value="update_cart">
			<input type="hidden" name="cart" value="1">
			<input type="hidden" name="cart_action" value="update">
			</form></div>';

			$html .= '
					<div class="cart-total">'.esc_html__( 'Total: ', 'couponxxl' ).'<span>'.couponxxl_format_price_number( $total ).'</span></div>
					<div class="cart-action">
						<a href="javascript:;" class="btn update-cart">'.esc_html__( 'Update Cart', 'couponxxl' ).'</a>
						<a href="'.( $checkout_link == '#' ? 'javascript:;' : $checkout_link ).'" class="btn form-submit" '.( $checkout_link == '#' ? 'data-toggle="modal" data-target="#gateways"' : '' ).'>'.esc_html__( 'Proceed To Checkout', 'couponxxl' ).'</a>
					</div>
			';


		}
		else{
			$html .= '<div class="alert alert-danger">'.esc_html__( 'Your cart is empty.' , 'couponxxl' ).'</div>';
		}

		return $html;

	}

	public function gateways_select(){
		global $COUPONXXL_GATEWAYS;
		$gateway_html = '<ul class="list-unstyled">';
		if( !empty( $COUPONXXL_GATEWAYS ) ){
			foreach( $COUPONXXL_GATEWAYS as $gateway ){
				$checkout_link = esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_checkout' ) );
				if( !is_user_logged_in() ){
					$checkout_link = esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_register' ) );
				}				
				$gateway_html .= '<a href="'.esc_url( add_query_arg( array( 'gateway' => $gateway['slug'] ), $checkout_link ) ).'"><img src="'.get_template_directory_uri().'/images/'.esc_attr( $gateway['slug'] ).'.png" /></a>';
			}
		}
		$gateway_html .= '</ul>';

		return $gateway_html;
	}

	public function initiate_payment( $order_key = '' ){
		global $wpdb;
		if( empty( $order_key ) ){

			echo  $this->check_products();

			if( !empty( $this->product_ids ) ){

				$user_id = get_current_user_id();
				$transient_data = maybe_unserialize( get_transient( $this->transient ) );

				$order_id = wp_insert_post(array(
					'post_title' => esc_html__( '#', 'couponxxl' ).' '.uniqid( true ),
					'post_author' => $user_id,
					'post_type' => 'ord',
					'post_status' => 'publish'
				));

				wp_update_post(array(
					'ID' => $order_id,
					'post_title' => esc_html__( '#', 'couponxxl' ).' '.$order_id
				));

				$amount = 0;
				$order_owner_share = 0;

				foreach( $this->product_ids as $id => $qty ){
					$offer = get_post( $id );
					$product_price = couponxxl_get_deal_price( $id );
					$subtotal = $qty * $product_price;
					$amount += $subtotal;

					$owner_share_product = couponxxl_calculate_owner_part( $id );
					$owner_share = $qty * $owner_share_product;

					$order_owner_share += $owner_share;

					$seller_share_product = couponxxl_calculate_seller_part( $id, $owner_share_product );
					$seller_share = $qty * $seller_share_product;

					$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}order_items VALUES ( '', %d, %d, %d, %d, %d, %s, %f, %f, %d, '' )", $order_id, $product_price, $qty, $offer->post_author, $user_id, $offer->post_title, $owner_share, $seller_share, $offer->ID ) );
				}

				$order_key = 'cxxl_order_'.$order_id;
				$transient_value = array(
					'purchase' => 'order',
					'buyer_id' => $user_id,
					'order_id' => $order_id,
					'title' => $offer->post_title,
					'amount' => $amount,
					'gateway' => $_GET['gateway']
				);
				set_transient( $order_key, $transient_value );

				update_post_meta( $order_id, 'order_total', $amount );
				update_post_meta( $order_id, 'order_owner_share', $order_owner_share );
				update_post_meta( $order_id, 'order_buyer_id', $user_id );
				update_post_meta( $order_id, 'order_status', 'not_paid' );
				update_post_meta( $order_id, 'order_gateway', $_GET['gateway'] );

				delete_transient( $this->transient );
				wp_redirect( add_query_arg( array( 'order_key' => $order_key ) ) );
			}
			else{
				echo '<div class="alert alert-danger">'.esc_html__( 'Your cart is empty.' , 'couponxxl' ).'</div>';
			}
		}
		else{
			$order_data = maybe_unserialize( get_transient( $order_key ) );
			$main_unit_abbr = couponxxl_get_option( 'main_unit_abbr' );
			$desc = esc_html__( 'Payment for the order of deals.', 'couponxxl' );
			$permalink = add_query_arg( array( 'action' => 'gateway-return' ), couponxxl_get_permalink_by_tpl( 'page-tpl_checkout' ) );

			echo '<div class="alert alert-success">'.esc_html__( 'Your items are ready for payment.' , 'couponxxl' ).'</div>';
			do_action( 'couponxxl_generate_payments', $order_data['amount'], $order_data['title'], $desc, $main_unit_abbr, $permalink, $order_key, $order_data['gateway'] );
		}
	}

}

$couponxxl_cart = new CouponXXL_Cart();

?>