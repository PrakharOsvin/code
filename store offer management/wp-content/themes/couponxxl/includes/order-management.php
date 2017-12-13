<?php

if ( ! function_exists( 'couponxxl_order_managemenet' ) ) {
	function couponxxl_order_managemenet() {
		add_meta_box( 'order_management', esc_html__( 'Order Details', 'couponxxl' ), 'couponxxl_order_management_callback', 'ord' );
	}

	add_action( 'add_meta_boxes', 'couponxxl_order_managemenet' );
}

if ( ! function_exists( 'couponxxl_update_item_status' ) ) {
	function couponxxl_update_item_status( $item_id, $status ) {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}order_items SET status = %s WHERE item_id = %d", $status, $item_id ) );
	}
}


if ( ! function_exists( 'couponxxl_process_order' ) ) {
	function couponxxl_process_order( $order_ids = array() ) {
		global $wpdb, $COUPONXXL_GATEWAYS;
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}order_items AS order_items LEFT JOIN {$wpdb->prefix}offers AS offers ON order_items.offer_id = offers.post_id SET order_items.status = 'deleted' WHERE offers.offer_type = 'deal' AND offers.offer_expire <= %d AND order_items.status = ''", current_time( 'timestamp' ) ) );
		if ( ! empty( $_POST['order_id'] ) ) {
			$order_items_payouts = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}order_items AS order_items WHERE order_items.status = 'payout' AND order_items.order_id = %d", $_POST['order_id'] ) );
			$order_items_refunds = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}order_items AS order_items WHERE order_items.status = 'deleted' AND order_items.order_id = %d", $_POST['order_id'] ) );
		} else if ( ! empty( $order_ids ) ) {
			$order_items_payouts = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}order_items AS order_items WHERE order_items.status = 'payout' AND order_id IN (" . esc_sql( join( ',', $order_ids ) ) . ")" );
			$order_items_refunds = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}order_items AS order_items WHERE order_items.status = 'deleted' AND order_id IN (" . esc_sql( join( ',', $order_ids ) ) . ")" );
		} else {
			$order_items_payouts = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}order_items AS order_items WHERE order_items.status = 'payout'" );
			$order_items_refunds = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}order_items AS order_items WHERE order_items.status = 'deleted'" );
		}

		if ( ! empty( $order_items_payouts ) ) {
			foreach ( $order_items_payouts as $order_items_payout ) {
				$seller_payout_method = get_user_meta( $order_items_payout->seller_id, 'seller_payout_method', true );

				$sellers  = array();
				$messages = '';

				if ( ! empty( $seller_payout_method ) ) {
					if ( empty( $sellers[ $seller_payout_method ][ $order_items_payout->seller_id ] ) ) {
						$sellers[ $seller_payout_method ][ $order_items_payout->seller_id ] = array(
							'amount'                => 0,
							'order_item_ids'        => array(),
							'user'                  => get_the_author_meta( 'login', $order_items_payout->seller_id ),
							'seller_payout_account' => get_user_meta( $order_items_payout->seller_id, 'seller_payout_account', true )
						);
					}
					$sellers[ $seller_payout_method ][ $order_items_payout->seller_id ]['amount'] += $order_items_payout->seller_share;
					$sellers[ $seller_payout_method ][ $order_items_payout->seller_id ]['order_item_ids'][] = $order_items_payout->item_id;
				} else {
					$messages .= couponxxl_wrap_message( get_the_author_meta( 'login', $order_items_payout->seller_id ) . ' - ' . esc_html__( 'User did not set payout option', 'couponxxl' ), 'error' );
				}

				ob_start();
				do_action( 'couponxxl_process_payout', $sellers );
				$messages .= ob_get_contents();
				ob_end_clean();
				set_transient( 'couponxxl_process_payout', $messages );

			}
		}

		if ( ! empty( $order_items_refunds ) ) {
			$buyers         = array();
			$messages       = '';
			$order_gateways = array();
			foreach ( $order_items_refunds as $order_items_refund ) {
				if ( empty( $order_gateways[ $order_items_refund->order_id ] ) ) {
					$order_gateways[ $order_items_refund->order_id ] = get_post_meta( $order_items_refund->order_id, 'order_gateway', true );
				}
				if ( ! empty( $COUPONXXL_GATEWAYS[ $order_gateways[ $order_items_refund->order_id ] ] ) && ! empty( $COUPONXXL_GATEWAYS[ $order_gateways[ $order_items_refund->order_id ] ]['has_refund'] ) ) {
					if ( empty( $buyers[ $order_gateways[ $order_items_refund->order_id ] ][ $order_items_refund->buyer_id ] ) ) {
						$buyers[ $order_gateways[ $order_items_refund->order_id ] ][ $order_items_refund->buyer_id ] = array(
							'amount'              => 0,
							'order_item_ids'      => array(),
							'user'                => get_the_author_meta( 'login', $order_items_refund->buyer_id ),
							'transaction_details' => get_post_meta( $order_items_refund->order_id ),
							'order_id'            => $order_items_refund->order_id
						);
					}

					$buyers[ $order_gateways[ $order_items_refund->order_id ] ][ $order_items_refund->buyer_id ]['amount'] += $order_items_refund->seller_share + $order_items_refund->owner_share;
					$buyers[ $order_gateways[ $order_items_refund->order_id ] ][ $order_items_refund->buyer_id ]['order_item_ids'][] = $order_items_refund->item_id;
				} else {
					$messages .= couponxxl_wrap_message( get_the_author_meta( 'login', $order_items_refund->buyer_id ) . ' - ' . esc_html__( 'Order gateway does not support API refund', 'couponxxl' ) . '. <a href="' . esc_url( get_edit_post_link( $order_items_refund->order_id ) ) . '" target="_blank">' . esc_html__( 'Visit order', 'couponxxl' ) . '</a>', 'error' );
					set_transient( 'couponxxl_process_refund', $messages );
				}
			}

			ob_start();
			do_action( 'couponxxl_process_refund', $buyers );
			$messages .= ob_get_contents();
			ob_end_clean();
		}
	}

	add_action( 'wp_ajax_process_order', 'couponxxl_process_order' );
}

if ( ! function_exists( 'couponxxl_order_management_callback' ) ) {
	function couponxxl_order_management_callback( $post ) {
		global $wpdb;
		$order_items = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}order_items WHERE order_id = %d", $post->ID ) );
		echo '<ul class="order-list">';
		if ( ! empty( $order_items ) ) {
			foreach ( $order_items as $order_item ) {
				$vouchers_html = '';
				$vouchers      = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}vouchers WHERE item_id = %d", $order_item->item_id ) );
				if ( ! empty( $vouchers ) ) {
					foreach ( $vouchers as $voucher ) {
						$vouchers_html .= couponxxl_voucher_check( $voucher );
					}
				}
				if ( ! empty( $vouchers_html ) ) {
					$vouchers_html = esc_html__( 'Vouchers:', 'couponxxl' ) . $vouchers_html;
				}

				switch ( $order_item->status ) {
					case 'refunded' :
						$status = '<span class="status refunded">' . esc_html__( 'Buyer was refunded', 'couponxxl' ) . '</span>';
						break;
					case 'deleted' :
						$status = '<span class="status deleted">' . esc_html__( 'Buyer will receive refund', 'couponxxl' ) . ' <a href="javascript:;" class="manual-refund button" title="' . esc_attr__( 'Mark as refunded', 'couponxxl' ) . '" data-id="' . esc_attr( $order_item->item_id ) . '"><i class="fa fa-mail-forward"></i></a></span>';
						break;

					case 'sent' :
						$status = '<span class="status sent">' . esc_html__( 'Seller share was sent', 'couponxxl' ) . '</span>';
						break;
					case 'payout' :
						$status = '<span class="status payout">' . esc_html__( 'Seller share can be sent', 'couponxxl' ) . '</span>';
						break;

					default :
						$status = '<span class="status live">' . esc_html__( 'Waiting for deal to become active', 'couponxxl' ) . '</span>';
						break;
				}


				echo '
			<li>
				<span class="order-left">
					' . $order_item->offer_title . '
					<div class="order-item-vouchers">
						' . $vouchers_html . '					
					</div>
				</span>

				<span class="order-right">
					' . $order_item->qty . ' x ' . couponxxl_format_price_number( $order_item->price ) . ' 
					(<span class="order-item-split">' . couponxxl_format_price_number( $order_item->qty * $order_item->price ) . '</span>)
					' . $status . '
				</span>
				<div class="clearfix"></div>
			</li>';
			}
			echo '
		<li class="order-total clearfix">
			<span class="order-left">
				' . esc_html__( 'Total:', 'couponxxl' ) . '
			</span>

			<span class="order-right">
				' . couponxxl_format_price_number( get_post_meta( $post->ID, 'order_total', true ) ) . ' 
			</span>
			<div class="clearfix"></div>
		</li>
		';

			$order_status    = get_post_meta( $post->ID, 'order_status', true );
			$order_processed = get_post_meta( $post->ID, 'order_processed', true );
			if ( $order_processed !== '100%' ) {
				$order_processed = couponxxl_order_percentage_complete( $post->ID );
			}

			if ( $order_status == 'not_paid' ) {
				echo esc_html__( 'Order is not yet paid.', 'couponxxl' );
			} else if ( $order_status == 'pending_payment' ) {
				echo esc_html__( 'Order is pending ( for banks its needs to be cleared manually ).', 'couponxxl' );
			} else if ( $order_processed == '100%' ) {
				echo esc_html__( 'Order is completelly processed.', 'couponxxl' );
			} else {
				echo '<a href="javascript:;" class="process-payment button" data-id="' . esc_attr( $post->ID ) . '">' . esc_html__( 'Process Order', 'couponxxl' ) . ' (' . $order_processed . ')</a>';
			}
		} else {
			echo '<li>' . esc_html__( 'Order is empty', 'couponxxl' ) . '</li>';
		}
		echo '</ul>'
		?>
		<?php
	}
}

/*
Add extra columns on the orders listing
*/
if ( ! function_exists( 'couponxxl_custom_order_columns' ) ) {
	add_filter( 'manage_edit-ord_columns', 'couponxxl_custom_order_columns' );
	function couponxxl_custom_order_columns( $columns ) {
		$columns = array_slice( $columns, 0, count( $columns ) - 1, true ) + array(
				"order_owner_share" => esc_html__( 'Owner Share', 'coupon' ),
				"order_total"       => esc_html__( 'Total', 'coupon' ),
				"order_buyer"       => esc_html__( 'Buyer', 'coupon' ),
				"order_status"      => esc_html__( 'Status', 'coupon' ),
			) + array_slice( $columns, count( $columns ) - 1, count( $columns ) - 1, true );

		return $columns;
	}
}

/*
Populate custom columns on the order listing
*/
if ( ! function_exists( 'couponxxl_custom_order_columns_populate' ) ) {
	add_action( 'manage_ord_posts_custom_column', 'couponxxl_custom_order_columns_populate', 10, 2 );
	function couponxxl_custom_order_columns_populate( $column, $post_id ) {
		switch ( $column ) {
			case 'order_owner_share' :
				echo couponxxl_format_price_number( get_post_meta( $post_id, 'order_owner_share', true ) );
				break;
			case 'order_total' :
				echo couponxxl_format_price_number( get_post_meta( $post_id, 'order_total', true ) );
				break;
			case 'order_buyer' :
				$order_buyer = get_post_meta( $post_id, 'order_buyer_id', true );
				$user_data   = get_userdata( $order_buyer );
				if ( $user_data ) {
					if ( ! empty( $user_data->user_nicename ) ) {
						echo $user_data->user_nicename;
					} else {
						echo $user_data->user_login;
					}
				}
				break;
			case 'order_status':
				$order_status    = get_post_meta( $post_id, 'order_status', true );
				$order_processed = get_post_meta( $post_id, 'order_processed', true );
				if ( $order_processed !== '100%' ) {
					$order_processed = couponxxl_order_percentage_complete( $post_id );
				}
				if ( $order_status == 'not_paid' || $order_status == 'pending_payment' ) {
					esc_html_e( 'Not Paid', 'couponxxl' );
				} else if ( $order_processed == '100%' ) {
					esc_html_e( 'Order Processed', 'couponxxl' );
				} else {
					echo '<a href="javascript:;" class="button button-primary button-large process-payment" data-id="' . esc_attr( $post_id ) . '">' . esc_html__( 'Process Order', 'couponxxl' ) . ' ( ' . $order_processed . ' )</a>';
				}
				break;
		}
	}
}

/*
Calculate percentage of comlpeted order by processing
*/
if ( ! function_exists( 'couponxxl_order_percentage_complete' ) ) {
	function couponxxl_order_percentage_complete( $order_id ) {
		global $wpdb;
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT COUNT(item_id) AS count_all, 
			SUM( CASE WHEN status = 'refunded' OR status = 'sent' THEN 1 ELSE 0 END ) AS count_processed 
			FROM {$wpdb->prefix}order_items 
			WHERE order_id = %d", $order_id ) );

		if ( ! empty( $results[0] ) && ! empty( $results[0]->count_all ) ) {
			$percentage = round( ( $results[0]->count_processed / $results[0]->count_all ) * 100 ) . '%';
		} else {
			$percentage = '0%';
		}

		update_post_meta( $order_id, 'order_processed', $percentage );

		return $percentage;
	}
}

/*
Display messages from the order process if there are any
*/
if ( ! function_exists( 'custom_order_notices' ) ) {
	function custom_order_notices() {
		echo get_transient( 'couponxxl_process_payout' );
		delete_transient( 'couponxxl_process_payout' );

		echo get_transient( 'couponxxl_process_refund' );
		delete_transient( 'couponxxl_process_refund' );
	}

	add_action( 'admin_notices', 'custom_order_notices' );
}

/*
Process manual refund
*/
if ( ! function_exists( 'couponxxl_process_manual_refund' ) ) {
	function couponxxl_process_manual_refund() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}order_items set status = 'refunded' WHERE item_id = %d", $_POST['item_id'] ) );
		die();
	}

	add_action( 'wp_ajax_manual_refund', 'couponxxl_process_manual_refund' );
}

/*
Set items as refunded on refund process complete
*/
if ( ! function_exists( 'couponxxl_process_refund' ) ) {
	function couponxxl_process_refund( $data ) {
		global $wpdb;
		foreach ( $data as $order_id => $order_data ) {
			$wpdb->query( "UPDATE {$wpdb->prefix}order_items SET status = 'refunded' WHERE item_id IN ( " . esc_sql( join( ',', $order_data['ids'] ) ) . " )" );
			$order_total = get_post_meta( $order_id, 'order_total', true );
			update_post_meta( $order_id, 'order_total', $order_total - $order_data['amount'] );
		}
	}
}

/*
On post delete update order items
*/
if ( ! function_exists( 'couponxxl_update_order_items' ) ) {
	function couponxxl_update_order_items( $post_id ) {
		global $wpdb, $poost_type;
		if ( $poost_type == 'offer' ) {
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}order_items AS order_items LEFT JOIN {$wpdb->prefix}offers AS offers ON order_items.offer_id = offers.offer_id SET order_items.status = 'deleted' WHERE offers.post_id = %d AND order_items.status = ''", $post_id ) );
		}
	}

	add_action( 'delete_post', 'couponxxl_update_order_items', 10 );
}

/*
Canel the order and delete transient and order
*/
if ( ! function_exists( 'couponxxl_cancel_order' ) ) {
	function couponxxl_cancel_order( $transient, $transient_data ) {
		wp_delete_post( $transient_data['order_id'], true );
		delete_transient( $transient );
	}
}

/*
Process adding credits and managin vouchers
*/
if ( ! function_exists( 'couponxxl_process_payment_details' ) ) {
	function couponxxl_process_payment_details( $transient, $transient_value = '', $transaction_details = array(), $status = 'paid' ) {
		global $couponxxl_cart, $wpdb;
		if ( $transient_value['purchase'] == 'credits' ) {
			$cxxl_credits = $transient_value['credits'] + get_user_meta( $transient_value['buyer_id'], 'cxxl_credits', true );

			update_user_meta( $transient_value['buyer_id'], 'cxxl_credits', $cxxl_credits );

			$message = '<div class="alert alert-success clearfix">' . esc_html__( 'Your credits were successfully added.', 'couponxxl' ) . '</div>';
		} else {
			delete_transient( $couponxxl_cart->transient );
			$order_id = $transient_value['order_id'];
			update_post_meta( $order_id, 'order_status', $status );
			if ( ! empty( $transaction_details ) ) {
				update_post_meta( $order_id, 'transaction_details', $transaction_details );
			}

			couponxxl_process_order_payment( $order_id );

			$order_mailed = get_post_meta( $order_id, 'order_mailed', true );
			if ( $order_mailed !== '1' ) {
				$items = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}offers AS offers LEFT JOIN {$wpdb->prefix}order_items AS order_items ON order_items.offer_id = offers.post_id WHERE order_items.order_id = %d", $order_id ) );
				couponxxl_inform_admin_and_buyer( $items, $order_id );
				update_post_meta( $order_id, 'order_mailed', 1 );
			}
			$message = '<div class="alert alert-success clearfix">' . esc_html__( 'Your order has been processed.', 'couponxxl' ) . '</div>';
		}
		delete_transient( $transient );

		return $message;
	}
}

/*
Create vouchers for purchases once the deal has becaome active
*/
if ( ! function_exists( 'couponxxl_generate_vouchers_for_active_deal' ) ) {
	function couponxxl_generate_vouchers_for_active_deal( $offer_id ) {
		global $wpdb;
		$items = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}order_items WHERE offer_id = %d AND status = ''", $offer_id ) );
		if ( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				for ( $i = 0; $i < $item->qty; $i ++ ) {
					couponxxl_create_insert_voucher( $item->item_id, $offer_id );
				}
				couponxxl_update_order_status( $item->item_id, 'payout' );
			}
		}

	}
}

/*
Create and insert voucher
*/
if ( ! function_exists( 'couponxxl_create_insert_voucher' ) ) {
	function couponxxl_create_insert_voucher( $item_id, $offer_id ) {
		global $wpdb;

		$voucher_code = couponxxl_generate_voucher( 5, $offer_id );
		$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}vouchers VALUES ( '', %d, '0', %s )", $item_id, $voucher_code ) );
	}
}


/*
Set order it for refund since it is payed but there is no more vouchers left
*/
if ( ! function_exists( 'couponxxl_update_order_status' ) ) {
	function couponxxl_update_order_status( $item_id, $status ) {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}order_items SET status = %s WHERE item_id = %d", $status, $item_id ) );
	}
}

/*
Process creating vouchers and checking if deal is active
*/
if ( ! function_exists( 'couponxxl_process_order_payment' ) ) {
	function couponxxl_process_order_payment( $order_id ) {
		global $wpdb;
		$order_status = get_post_meta( $order_id, 'order_status', true );
		$order_mailed = get_post_meta( $order_id, 'order_mailed', true );
		if ( $order_status != 'not_paid' ) {
			$generate_vouchers = false;
			$deal_is_active    = false;
			$items             = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}offers AS offers LEFT JOIN {$wpdb->prefix}order_items AS order_items ON order_items.offer_id = offers.post_id WHERE order_items.order_id = %d AND order_items.status = ''", $order_id ) );
			if ( ! empty( $items ) ) {
				foreach ( $items as $item ) {
					$deal_meta               = get_post_meta( $item->offer_id );
					$deal_meta['deal_sales'] = ! empty( $deal_meta['deal_sales'] ) ? $deal_meta['deal_sales'] : array( 0 );

					$new_sales_num = $deal_meta['deal_sales'][0] + $item->qty;

					if ( $new_sales_num <= $deal_meta['deal_items'][0] ) {
						if ( ! empty( $deal_meta['deal_min_sales'] ) ) {
							if ( $deal_meta['deal_min_sales'][0] <= $new_sales_num ) {
								$generate_vouchers = true;
							}
							if ( ! empty( $deal_meta['deal_min_sales'] ) && $deal_meta['deal_sales'][0] < $deal_meta['deal_min_sales'][0] && ( $new_sales_num >= $deal_meta['deal_min_sales'][0] ) ) {
								$deal_is_active = true;
							}
						} else {
							$generate_vouchers = true;
						}

						update_post_meta( $item->offer_id, 'deal_sales', $new_sales_num );
						if ( $new_sales_num == $deal_meta['deal_items'][0] ) {
							couponxxl_update_post_meta( '0', 'offer_has_items', $item->offer_id );
						}

						if ( $generate_vouchers ) {
							for ( $i = 0; $i < $item->qty; $i ++ ) {
								couponxxl_create_insert_voucher( $item->item_id, $deal_meta );
							}
							couponxxl_update_order_status( $item->item_id, 'payout' );
						}

						if ( $deal_is_active ) {
							$deal_meta['deal_sales'][0] = $new_sales_num;
							couponxxl_generate_vouchers_for_active_deal( $item->offer_id );
						}
					} else {
						couponxxl_update_order_status( $item->item_id, 'deleted' );
					}
				}

			}
		}
	}
}


/*
Send mail to admin and buyer
*/
if ( ! function_exists( 'couponxxl_inform_admin_and_buyer' ) ) {
	function couponxxl_inform_admin_and_buyer( $items, $order_id ) {
		global $COUPONXXL_GATEWAYS;

		$items_list   = '';
		$vendors_data = array();

		if ( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				$item_data = '
			<tr class="order_item">
			<td class="td" style="text-align: left; vertical-align: middle; border: 1px solid #eee; word-wrap: break-word; color: #737373; padding: 12px">
				' . $item->offer_title . '<br><small></small>
			</td>
			<td class="td" style="text-align: left; vertical-align: middle; border: 1px solid #eee; color: #737373; padding: 12px">
				' . $item->qty . '
			</td>
			<td class="td" style="text-align: left; vertical-align: middle; border: 1px solid #eee; color: #737373; padding: 12px">
				<span class="amount">' . couponxxl_format_price_number( $item->price ) . '</span>
			</td>
			</tr>';

				$items_list .= $item_data;

				$vendor = get_userdata( $item->seller_id );
				if ( empty( $vendors_data[ $vendor->user_email ] ) ) {
					$vendors_data[ $vendor->user_email ] = '';
				}
				$vendors_data[ $vendor->user_email ] .= $item_data;
			}
		}

		$order_gateway = get_post_meta( $order_id, 'order_gateway', true );
		$gateway       = ! empty( $COUPONXXL_GATEWAYS[ $order_gateway ] ) ? $COUPONXXL_GATEWAYS[ $order_gateway ]['name'] : $order_gateway;
		$total         = couponxxl_format_price_number( get_post_meta( $order_id, 'order_total', true ) );

		/* Buyer Specific */
		$email_title    = esc_html__( 'Thank you for your order', 'couponxxl' );
		$email_subtitle = esc_html__( 'Your order has been received and is now being processed. Your order details are shown below for your reference:', 'couponxxl' );
		ob_start();
		include( couponxxl_load_path( 'includes/email-tpl/email-order.php' ) );
		$message = ob_get_contents();
		ob_end_clean();

		$order_buyer_id = get_post_meta( $order_id, 'order_buyer_id', true );
		$buyer_data     = get_userdata( $order_buyer_id );
		$to             = $buyer_data->user_email;
		$subject        = esc_html__( 'Your ', 'couponxxl' ) . get_bloginfo( 'name', 'display' ) . esc_html__( ' order receipt', 'couponxxl' );
		couponxxl_send_mail( $to, $subject, $message );

		/* Admin Specific */
		$email_title    = esc_html__( 'New customer order', 'couponxxl' );
		$email_subtitle = esc_html__( 'You have received an order from ', 'couponxxl' ) . $buyer_data->display_name . ' ' . esc_html__( 'The order is as follows:', 'couponxxl' );
		ob_start();
		include( couponxxl_load_path( 'includes/email-tpl/email-order.php' ) );
		$message = ob_get_contents();
		ob_end_clean();

		$to      = couponxxl_get_option( 'new_offer_email' );
		$subject = '[' . get_bloginfo( 'name', 'display' ) . '] ' . esc_html__( 'New customer order ', 'couponxxl' ) . '(' . $order_id . ')';
		couponxxl_send_mail( $to, $subject, $message );

		/* Send mail to vendor about their purchase products  */
		foreach ( $vendors_data as $email => $vendor_data ) {
			$email_title    = esc_html__( 'New sales', 'couponxxl' );
			$email_subtitle = esc_html__( 'There is a new sale of your items. List of sold items is as follows:', 'couponxxl' );
			ob_start();
			include( couponxxl_load_path( 'includes/email-tpl/email-order.php' ) );
			$message = ob_get_contents();
			ob_end_clean();

			$subject = '[' . get_bloginfo( 'name', 'display' ) . '] ' . esc_html__( 'New sales ', 'couponxxl' ) . '(' . $order_id . ')';
			couponxxl_send_mail( $email, $subject, $message );
		}
	}
}

/*
On order save check if it needs vouchers to be generaetd
*/
if ( ! function_exists( 'couponxxl_check_order_on_save' ) ) {
	function couponxxl_check_order_on_save( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( 'ord' != $post->post_type ) {
			return;
		}

		couponxxl_process_order_payment( $post_id );
	}

	add_action( 'save_post', 'couponxxl_check_order_on_save', 20, 2 );
}

/*
Generate pdf of the order item
*/
if ( ! function_exists( 'couponxxl_generate_pdf' ) ) {
	function couponxxl_generate_pdf() {
		if ( isset( $_GET['print_pdf'] ) ) {
			$item_id    = $_GET['item_id'];
			$voucher_id = $_GET['voucher_id'];
			$user_id    = $_GET['user_id'];
			$access_key = get_transient( $user_id . '_access_key' );
			if ( ! empty( $item_id ) && ! empty( $access_key ) ) {
				global $wpdb;
				$vouchers = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}order_items AS items LEFT JOIN {$wpdb->prefix}vouchers AS vouchers ON items.item_id = vouchers.item_id WHERE items.item_id = %d AND ( items.status = 'payout' OR items.status = 'sent' ) AND voucher_id = %d", $item_id, $voucher_id ) );
				if ( ! empty( $vouchers ) ) {
					remove_filter('the_title', 'wptexturize');
					$offer_id    = $vouchers[0]->offer_id;
					$offer_store = get_post_meta( $offer_id, 'offer_store', true );
					$site_logo = couponxxl_get_option( 'site_logo' );
					$user      = get_userdata( $user_id );
					$offer_description = wp_strip_all_tags( get_post_field( 'post_content', $offer_id ) );
					$offer_description = preg_replace("/&nbsp;/", "", $offer_description);
					$image_file_name = 'barcode.png';
					$pdf             = new Couponxxl_FPDF();

					$pdf->AddPage();
					$x = $pdf->GetX();
					$y = $pdf->GetY();
					$pdf->SetFont( 'Arial', 'B', 12 );
					$pdf->MultiCell( 156, 24, get_the_title( $offer_id ), 0 );
					$pdf->SetFont( 'Arial', 'B', 8 );
					$pdf->SetTextColor( 60, 60, 60 );
					$pdf->Ln( 22 );
					$pdf->MultiCell( 156, 4, iconv("UTF-8", "CP1250//TRANSLIT", $offer_description), 0, 'L' );
					$pdf->SetXY( $x + 156, $y );
					$offer_store_img = wp_get_attachment_image_src( get_post_thumbnail_id( $offer_store ), 'couponxxl-shop-logo' );
					if ( ! empty( $offer_store_img[0] ) ) {
						$pdf->MultiCell( 53.5, 4, $pdf->Image( $offer_store_img[0], $pdf->GetX(), 9.5, 32 ), 0 );
					}

					if ( ! empty( $site_logo ) ) {
						$pdf->MultiCell( 53.5, 4, $pdf->Image( $site_logo['url'], $pdf->GetX(), 7.5, 35 ), 0 );
					}

					$pdf->SetFont( 'Arial', 'B', 9 );
					$pdf->Ln( 10 );
					$pdf->SetTextColor( 144, 144, 144 );
					$pdf->Cell( 156, 1, esc_html__( 'Purchased:', 'couponxxl' ) . ' ' . get_the_time( 'F j, Y', $offer_id ), 0, 0, 'L' );
					$pdf->Ln( 4 );
					$pdf->Cell( 156, 3, esc_html__( 'Store:', 'couponxxl' ) . ' ' . get_the_title( $offer_store ), 0, 0, 'L' );
					$pdf->Ln( 5 );
					$pdf->Cell( 156, 3, esc_html__( 'Customer:', 'couponxxl' ) . ' ' . $user->first_name . ' ' . $user->last_name, 0, 0, 'L' );
					$deal_voucher_expire = get_post_meta( $offer_id, 'deal_voucher_expire', true );
					if ( ! empty( $deal_voucher_expire ) ) {
						$pdf->Ln( 4 );
						$pdf->Cell( 156, 5, esc_html__( 'Expires:', 'couponxxl' ) . ' ' . date( 'F j, Y', $deal_voucher_expire ), 0, 0, 'L' );
					}
					$pdf->Ln( 0 );

					$pdf->SetFont( 'Arial', 'B', 12 );
					$pdf->Ln( 5 );

					$pdf->SetFont( 'Arial', 'B', 10 );
					$pdf->SetTextColor( 0, 0, 0 );
					$pdf->Cell( 156, 5.4, esc_html__( 'Voucher:', 'couponxxl' ), 0, 0, 'L' );
					$pdf->SetTextColor( 144, 144, 144 );
					$pdf->SetFont( 'Arial', 'B', 12 );

					foreach ( $vouchers as $voucher ) {
						$pdf->Ln( 5 );
						$pdf->Cell( 156, 5.4, $voucher->voucher_code, 0, 0, 'L' );
						couponxxl_barcode( $voucher->voucher_code, $image_file_name );
						$pdf->Cell( 53.5, 0, $pdf->Image( $image_file_name, $pdf->GetX(), $pdf->GetY(), 33.78 ), 0, 1, 'L' );
					}

					$pdf->Output();
				}
			}
		}
	}

	add_action( 'after_setup_theme', 'couponxxl_generate_pdf' );
}

?>