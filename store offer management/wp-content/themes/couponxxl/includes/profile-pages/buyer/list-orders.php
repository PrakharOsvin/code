<?php

$paged = 1;
if( get_query_var( 'paged' ) ){
	$paged = get_query_var( 'paged' );
}
else if( get_query_var( 'page' ) ){
	$paged = get_query_var( 'page' );
}


$orders = new WP_Orders_Query(array(
	'posts_per_page' => '-1',
	'post_type' => 'ord',
	'search' => $search,
	'paged' => $paged
));

$page_links_total =  $orders->max_num_pages;
$page_links = paginate_links( 
	array(
		'prev_next' 	=> true,
		'end_size' 		=> 2,
		'mid_size' 		=> 2,
		'total' 		=> $page_links_total,
		'current' 		=> $paged,	
		'prev_text'     => '<i class="pline-angle-left"></i> '.esc_html__( 'Previous', 'couponxxl' ),
		'next_text'     => esc_html__( 'Next', 'couponxxl' ).' <i class="pline-angle-right"></i>',
		'prev_next' 	=> true,
		'type' 			=> 'array'
	)
);
$pagination = couponxxl_format_pagination( $page_links );


?>
<div class="white-block buyer-block">
	<div class="responsive-table table-responsive">
		<table>
			<tr>
				<th>
					<?php esc_html_e( 'Order Number', 'couponxxl' ) ?>
				</th>
				<th>
					<?php esc_html_e( 'Order Date', 'couponxxl' ) ?>
				</th>
				<th>
					<?php esc_html_e( 'Order Price', 'couponxxl' ) ?>
				</th>
				<th>
					<form action="<?php esc_url( $profile_link ) ?>">
						<input type="text" placeholder="<?php _e( 'Search orders', 'couponxxl' ) ?>" value="<?php echo esc_attr( $search ) ?>" class="form-control" name="search">
						<input type="hidden" name="subpage" value="purchases">
						<i class="pline-magnifier"></i>
					</form>
				</th>
			</tr>
			<?php
			if( $orders->have_posts() ){
				while( $orders->have_posts() ){
					$orders->the_post();
					$order_status = get_post_meta( get_the_ID(), 'order_status', true );
					if( $order_status !== 'not_paid' ):
					?>
					<tr>
						<td><?php the_title(); ?></td>
						<td><?php the_time( 'F j, Y' ); echo esc_html__( ' at ', 'couponxxl' ); the_time( 'H:i' ) ?></td>
						<td><?php echo couponxxl_format_price_number( get_post_meta( get_the_ID(), 'order_total', true ) ); ?></td>
						<td>
							<?php if( $order_status == 'paid' ): ?>
								<a href="<?php echo esc_url( add_query_arg( array( 'order' => get_the_ID() ) ) ) ?>" class="btn">
									<?php esc_html_e( 'See Details', 'couponxxl' ); ?>
								</a>
							<?php else: ?>
								<div class="text-right">
									<?php esc_html_e( 'Pending payment', 'couponxxl' ) ?>
								</div>
							<?php endif; ?>
						</td>
					</tr>
					<?php
					else:
							wp_delete_post( get_the_ID(), true );
					endif;
				}
			}

			?>
		</table>
	</div>
</div>

<?php wp_reset_postdata(); ?>