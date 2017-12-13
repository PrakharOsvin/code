<?php
$deals = new WP_Offers_Query(array(
	'offer_type' => 'deal',
	'posts_per_page' => '-1',
	'post_status' => 'publish,draft',
	'all_offers' => true,
	'author' => $vendor_id,
));
?>
<div class="white-block">
    <div class="white-block-content">
		<p class="pretable-loading"><?php esc_html_e( 'Loading...', 'couponxxl' ) ?></p>
		<div class="bt-table">
			<div id="toolbar" class="btn-group">
			    <a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'manage-offer-deal', 'offer_type' => 'deal' ), $profile_link ) ) ?>" class="btn btn-default">
			        <i class="fa fa-plus"></i> <?php esc_html_e( 'Add Deal', 'couponxxl' ) ?>
			    </a>
			</div>
			<table data-toggle="table" data-search="true" data-classes="table" data-searchText="<?php _e( 'Search', 'couponxxl' ) ?>">
				<thead>
				    <tr>
				        <th data-field="deal" data-sortable="true">
				        	<?php esc_html_e( 'Deal', 'couponxxl' ); ?>
				        </th>
				        <th data-field="status" data-sortable="true">
				            <?php esc_html_e( 'Status', 'couponxxl' ); ?>
				        </th>
				        <th data-field="category" data-sortable="true">
				            <?php esc_html_e( 'Category', 'couponxxl' ); ?>
				        </th>
				        <th data-field="purchases" data-sortable="true">
				            <?php esc_html_e( 'Purchases', 'couponxxl' ); ?>
				        </th>
				        <th data-field="clicks" data-sortable="true">
				            <?php esc_html_e( 'Clicks', 'couponxxl' ); ?>
				        </th>
				        <th data-field="views" data-sortable="true">
				            <?php esc_html_e( 'Views', 'couponxxl' ); ?>
				        </th>			        
				        <th data-field="crt">
				            <?php esc_html_e( 'CTR', 'couponxxl' ); ?>
				        </th>
				        <th data-field="action">
				            <?php esc_html_e( 'Action', 'couponxxl' ); ?>
				        </th>
				    </tr>
				</thead>
				<?php
				if( $deals->have_posts() ){
					$search_link = couponxxl_get_permalink_by_tpl( 'page-tpl_search' );
					?>
					<tbody>
					<?php
					while( $deals->have_posts() ){
						$deals->the_post() ;

						$expired = false;
						$deal_id = get_the_ID();

						$coupon_expire_date = couponxxl_raw_expire_time();
						if( $coupon_expire_date <= current_time( 'timestamp' ) && $coupon_expire_date !== -1 ){
							$expired = true;
						}

						?>
						<tr class="<?php echo $expired ? esc_attr( 'disabled' ) : esc_attr( '' ) ?>">
							<td class="deal-name-td">
								<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'manage-offer-deal', 'offer_id' => $deal_id ), $profile_link ) ); ?>">
									<?php the_title(); ?>
								</a>
							</td>
							<td>
								<?php
								if( get_post_status( $deal_id ) == 'publish' ){
									if( $expired ){
										esc_html_e( 'Expired', 'couponxxl' );
									}
									else{
										if( !couponxxl_deal_has_items() ){
											esc_html_e( 'Sold out', 'couponxxl' );
										}
										else{
											esc_html_e( 'Live', 'couponxxl' );
										}
									}
									if( $coupon_expire_date == 99999999999 ){
										echo '<div class="min-sales-opacity">'.esc_html__( 'Unlimited', 'couponxxl' ).'</div>';
									}
									else{
										echo '<div class="min-sales-opacity">'.date( 'd/M/Y', $coupon_expire_date ).'<br />'.date( 'H:i', $coupon_expire_date ).'</div>';
									}
								}
								else{
									esc_html_e( 'Pending', 'couponxxl' );
								}
								?>
							</td>
							<td>
								<?php
								$categories = wp_get_post_terms( $deal_id, 'offer_cat' );
								$category = couponxxl_get_deepest_taxonomy( $categories );
								if( !empty( $category ) ){
									echo  $category->name;
								}
								?>
							</td>
							<td>
								<?php
								$deal_sales = get_post_meta( $deal_id, 'deal_sales', true );
								$deal_min_sales = get_post_meta( $deal_id, 'deal_min_sales', true );
								echo !empty( $deal_sales ) ? $deal_sales : '0';
								if( !empty( $deal_min_sales ) ){
									echo '<span class="min-sales-opacity"> '.esc_html__( 'REQ:', 'couponxxl' ).' '.$deal_min_sales.'</div>';
								}
								?>
							</td>
							<td>
								<?php
								$offer_clicks = couponxxl_get_offer_clicks();
								echo  $offer_clicks;
								?>
							</td>
							<td>
								<?php
								$offer_views = couponxxl_get_offer_views( $deal_id );
								echo  $offer_views;
								?>
							</td>						
							<td>
								<?php
									$percentage = couponxxl_ctr_percentage( $offer_clicks, $offer_views );
									echo  $percentage;
								?>
							</td>
							<td>
								<a title="<?php _e( 'Edit Deal', 'couponxxl' ) ?>" href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'manage-offer-deal', 'offer_id' => $deal_id ), $profile_link ) ); ?>">
									<i class="fa fa-pencil"></i>
								</a>
								<a title="<?php _e( 'View Deal', 'couponxxl' ) ?>" href="<?php the_permalink() ?>">
									<i class="fa fa-eye"></i>
								</a>
								<a title="<?php _e( 'Delete Deal', 'couponxxl' ) ?>" class="confirm-action" data-confirm="<?php esc_html_e( 'Are you sure you want to delete this deal?', 'couponxxl' ) ?>" href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'delete-offer', 'offer_id' => $deal_id, 'refferer' => 'deals' ), $profile_link ) ); ?>">
									<i class="fa fa-times"></i>
								</a>
							</td>
						</tr>
						<?php
					}
					?>
					</tbody>
					<?php		
				}
				wp_reset_postdata();
				?>
			</table>
		</div>
	</div>
</div>