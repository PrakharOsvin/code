<?php
$order_id = isset( $_GET['order'] ) ? $_GET['order'] : '';
if( !empty( $order ) ){
	$order = get_post( $order_id );
	$order_status = get_post_meta( $order_id, 'order_status', true );
	if( $order->post_author == get_current_user_id() && $order_status == 'paid' ){
		?>
		<h4><strong><?php echo  $order->post_title ?></strong></h4>
		<div class="white-block buyer-block">
			<div class="table-responsive responsive-table">
				<table>
					<tr>
						<th class="offer-title"><?php esc_html_e( 'Name', 'couponxxl' ) ?></th>
						<th class="offer-price"><?php esc_html_e( 'Price', 'couponxxl' ) ?></th>
						<th class="offer-voucher"><?php esc_html_e( 'Voucher', 'couponxxl' ) ?></th>
						<th class="offer-status"><?php esc_html_e( 'Status', 'couponxxl' ) ?></th>
					</tr>		
					<?php
					global $wpdb;
					$order_items = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}order_items AS order_items LEFT JOIN {$wpdb->prefix}vouchers AS vouchers ON order_items.item_id = vouchers.item_id WHERE order_items.order_id = %d", $order_id ) );
					if( !empty( $order_items ) ){
						set_transient( get_current_user_id().'_access_key', 'can_access', 3600 );
						foreach( $order_items as $order_item ){

							?>
							<tr>
								<td class="offer-title"><?php echo  $order_item->offer_title ?></td>
								<td class="offer-price"><?php echo couponxxl_format_price_number( $order_item->price ) ?></td>
								<td class="offer-voucher">
								<div class="voucher-code">
									<?php 
									$can_use = false;
									if( empty( $order_item->status )  ){
										$deal_min_sales = get_post_meta( $order_item->offer_id, 'deal_min_sales', true );
										$deal_sales = get_post_meta( $order_item->offer_id, 'deal_sales', true );
										echo '<strong>'.( $deal_min_sales - $deal_sales ).'</strong> '.esc_html__( ' more sales for active deal', 'couponxxl' );
									}
									else if( $order_item->status == 'deleted' ){
										esc_html_e( 'Offer deleted, you will receive refund', 'couponxxl' );
									}
									else if( $order_item->status == 'refunded' ){
										esc_html_e( 'Refunded', 'couponxxl' );
									}
									else{
										echo  $order_item->voucher_code;
										$can_use = true;
									}
									?>
								</div>
								<?php if( $can_use ): ?>
									<div class="voucher-action">
										<a download href="<?php echo esc_url( add_query_arg( array( 'print_pdf' => 'yes', 'user_id' => get_current_user_id(), 'item_id' => $order_item->item_id, 'voucher_id' => $order_item->voucher_id ) ) ) ?>.pdf"><i class="pline-notes"></i> <?php esc_html_e( 'Print', 'couponxxl' ) ?></a>
										<a href="javascript:;" class="copy-voucher"><i class="pline-windows"></i> <?php esc_html_e( 'Copy', 'couponxxl' ) ?></a>
									</div>
								<?php endif; ?>
								</td>
								<td class="offer-status">
									<?php echo empty( $order_item->voucher_status ) || $order_item->voucher_status == '0' ? '<span class="success">'.esc_html__( 'Not Used', 'couponxxl' ).'</div>' : '<span class="danger">'.esc_html__( 'Used', 'couponxxl' ).'</div>' ?>
								</td>
							</tr>
							<?php
						}
					}
					?>
				</table>
			</div>
		</div>
		<?php
	}
	else{
		wp_redirect( add_query_arg( array( 'subpage' => 'purchases' ), $profile_link ) );
	}
}

?>