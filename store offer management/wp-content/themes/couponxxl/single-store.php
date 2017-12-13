<?php
/*==================
 SINGLE BLOG POST
==================*/

get_header();
the_post();
get_template_part( 'includes/title' );
$post_id = get_the_ID();

$show_deals_map = couponxxl_get_option( 'show_deals_map' );

$offer_type = get_query_var( $couponxxl_slugs['offer_type'] );

$theme_usage = couponxxl_get_option( 'theme_usage' );

if ( $theme_usage != 'all' ) {
	$offer_type = $theme_usage;
}

$store_link = get_post_meta( $post_id, 'store_link', true );
?>

	<section>
		<div class="container">
			<div class="row">
				<div class="col-md-3">

					<?php include( couponxxl_load_path( 'includes/search-map/map-trigger.php' ) ); ?>

					<div class="widget white-block">
						<?php if ( has_post_thumbnail() ): ?>
							<div class="shop-logo">
								<?php if ( ! empty( $store_link ) ): ?>
								<a href="<?php echo esc_url( add_query_arg( array( 'rs' => get_the_ID() ), get_permalink() ) ) ?>"
								   rel="nofollow" target="_blank">
									<?php endif; ?>
									<?php the_post_thumbnail( 'full', array( 'class' => 'img-responsive' ) ); ?>
									<?php if ( ! empty( $store_link ) ): ?>
								</a>
							<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php
						if ( ! empty( $store_link ) ) {
							echo '<a href="' . esc_url( $store_link ) . '" class="visit-store-link btn">' . esc_html__( 'Visit store website', 'couponxxl' ) . '</a>';
						}
						?>

						<ul class="list-unstyled list-inline store-social-networks">
							<?php
							$store_facebook = get_post_meta( $post_id, 'store_facebook', true );
							if ( ! empty( $store_facebook ) ) {
								?>
								<li>
									<a href="<?php echo esc_url( $store_facebook ) ?>" target="_blank" class="share">
										<i class="fa fa-facebook-square"></i>
									</a>
								</li>
								<?php
							}
							$store_twitter = get_post_meta( $post_id, 'store_twitter', true );
							if ( ! empty( $store_twitter ) ) {
								?>
								<li>
									<a href="<?php echo esc_url( $store_twitter ) ?>" target="_blank" class="share">
										<i class="fa fa-twitter-square"></i>
									</a>
								</li>
								<?php
							}
							$store_google = get_post_meta( $post_id, 'store_google', true );
							if ( ! empty( $store_google ) ) {
								?>
								<li>
									<a href="<?php echo esc_url( $store_google ) ?>" target="_blank" class="share">
										<i class="fa fa-google-plus-square"></i>
									</a>
								</li>
								<?php
							}
							$store_instagram = get_post_meta( $post_id, 'store_instagram', true );
							if ( ! empty( $store_instagram ) ) {
								?>
								<li>
									<a href="<?php echo esc_url( $store_instagram ) ?>" target="_blank" class="share">
										<i class="fa fa-instagram"></i>
									</a>
								</li>
								<?php
							}
							$store_rss = get_post_meta( $post_id, 'store_rss', true );
							if ( ! empty( $store_rss ) ) {
								?>
								<li>
									<a href="<?php echo esc_url( $store_rss ) ?>" target="_blank" class="share">
										<i class="fa fa-rss-square"></i>
									</a>
								</li>
								<?php
							}
							?>
						</ul>

						<?php if ( $theme_usage == 'all' ): ?>
							<div class="widget-title">
								<h5><?php esc_html_e( 'Deals or coupons?', 'couponxxl' ) ?></h5>
							</div>
							<div class="white-block-content">
								<?php
								$deals = new WP_Offers_Query( array(
									'offer_type' => 'deal',
									'meta_query' => array(
										array(
											'key'     => 'offer_store',
											'value'   => $post_id,
											'compare' => '='
										)
									)
								) );
								$deals = $deals->post_count;
								wp_reset_postdata();

								$coupons = new WP_Offers_Query( array(
									'offer_type' => 'coupon',
									'meta_query' => array(
										array(
											'key'     => 'offer_store',
											'value'   => $post_id,
											'compare' => '='
										)
									)
								) );
								$coupons = $coupons->post_count;
								wp_reset_postdata();

								?>

								<form class="store-type-filter">
									<ul class="list-unstyled">
										<li>
											<input type="radio" value="<?php the_permalink() ?>"
											       name="offer_type"
											       id="offer_type_all" <?php echo empty( $offer_type ) ? 'checked="checked"' : '' ?>>
											<label for="offer_type_all">
												<?php esc_html_e( 'All', 'couponxxl' ) ?>
												<span class="count">(<?php echo $coupons + $deals; ?>)</span>
											</label>
										</li>
										<li>
											<input type="radio"
											       name="offer_type"
											       value="<?php echo esc_url( add_query_arg( array( $couponxxl_slugs['offer_type'] => 'deal' ), the_permalink() ) ) ?>"
											       id="offer_type_deal" <?php echo $offer_type == 'deal' ? 'checked="checked"' : '' ?>>
											<label for="offer_type_deal">
												<?php esc_html_e( 'Deals only', 'couponxxl' ) ?>
												<span class="count">(<?php echo $deals; ?>)</span>
											</label>
										</li>
										<li>
											<input type="radio"
											       name="offer_type"
											       value="<?php echo esc_url( add_query_arg( array( $couponxxl_slugs['offer_type'] => 'coupon' ), the_permalink() ) ) ?>"
											       id="offer_type_coupon" <?php echo $offer_type == 'coupon' ? 'checked="checked"' : '' ?>>
											<label for="offer_type_coupon">
												<?php esc_html_e( 'Coupons only', 'couponxxl' ) ?>
												<span class="count">(<?php echo $coupons; ?>)</span>
											</label>
										</li>
									</ul>
								</form>
							</div>
						<?php endif; ?>

						<div class="widget-title">
							<h5><?php esc_html_e( 'About Store', 'couponxxl' ) ?></h5>
						</div>
						<div class="white-block-content">
							<?php the_content(); ?>
						</div>


					</div>
				</div>

				<div class="col-md-9">

					<?php include( couponxxl_load_path( 'includes/search-map/map-holder.php' ) ); ?>

					<?php
					$cur_page = 1;
					if ( get_query_var( 'paged' ) ) {
						$cur_page = get_query_var( 'paged' );
					} else if ( get_query_var( 'page' ) ) {
						$cur_page = get_query_var( 'page' );
					}
					$offers_per_page = couponxxl_get_option( 'offers_per_page' );
					$show_expired    = couponxxl_get_option( 'stores_show_expired' );
					$order           = couponxxl_get_option( 'store_offers_order' );

					$args = array(
						'all_offers'     => $show_expired == 'yes' ? true : false,
						'posts_per_page' => $offers_per_page,
						'paged'          => $cur_page,
						'order'          => $order,
						'meta_query'     => array(
							array(
								'key'     => 'offer_store',
								'value'   => $post_id,
								'compare' => '='
							)
						)
					);

					if ( ! empty( $offer_type ) ) {
						$args['offer_type'] = $offer_type;
					}

					$offers = new WP_Offers_Query( $args );

					$page_links_total = $offers->max_num_pages;
					$pagination_args  = array(
						'end_size'  => 2,
						'mid_size'  => 2,
						'total'     => $page_links_total,
						'current'   => $cur_page,
						'prev_next' => true,
						'prev_text' => '<i class="pline-angle-left"></i> ' . esc_html__( 'Previous', 'couponxxl' ),
						'next_text' => esc_html__( 'Next', 'couponxxl' ) . ' <i class="pline-angle-right"></i> ',
						'type'      => 'array'
					);

					$page_links = paginate_links( $pagination_args );

					$pagination = couponxxl_format_pagination( $page_links );


					if ( $offers->have_posts() ) {
						$counter = 0;
						?>
						<div class="row">
							<?php
							while ( $offers->have_posts() ) {
								$offers->the_post();
								if ( $counter == 3 ) {
									echo '</div><div class="row">';
									$counter = 0;
								}
								$counter ++;
								if ( $show_deals_map == 'yes' ) {
									$marker_deals[] = array(
										'title'        => get_the_title(),
										'permalink'    => get_permalink(),
										'ID'           => get_the_ID(),
										'image'        => get_the_post_thumbnail( get_the_ID(), 'couponxxl-popup-box' ),
										'deal_markers' => get_post_meta( get_the_ID(), 'deal_markers', true ),
										'offer_price'  => couponxxl_get_deal_meta( get_the_ID(), false )
									);
								}
								?>
								<div class="col-sm-4">
									<?php include( couponxxl_load_path( 'includes/offers/offers.php' ) ); ?>
								</div>
								<?php
							}
							?>
						</div>
						<?php if ( ! empty( $pagination ) ): ?>
							<div class="pagination-wrap">
								<ul class="pagination">
									<?php echo $pagination; ?>
								</ul>
								<div class="pagination-info">
									<strong><?php echo ( $cur_page - 1 ) * $offers_per_page + 1 ?></strong> -
									<strong><?php echo ( $cur_page - 1 ) * $offers_per_page + $offers->post_count ?></strong>
									<?php esc_html_e( 'of', 'couponxxl' ) ?>
									<strong><?php echo $offers->found_posts ?></strong>
									<?php esc_html_e( 'results', 'couponxxl' ) ?>
								</div>
							</div>
						<?php endif; ?>
						<?php
					} else {
						?>
						<div class="white-block">
							<div class="white-block-content">
								<?php echo couponxxl_get_option( 'store_no_offers_message' ); ?>
							</div>
						</div>
						<?php
					}
					wp_reset_postdata();
					?>
					<?php include( couponxxl_load_path( 'includes/search-map/map-markers-holder.php' ) ); ?>
				</div>
			</div>
		</div>
	</section>
<?php get_footer(); ?>