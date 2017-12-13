<?php
/*
    Template Name: All Stores
*/
get_header();
the_post();
get_template_part( 'includes/title' );

global $couponxxl_slugs;

$cur_page        = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$letter          = get_query_var( $couponxxl_slugs['letter'], '' );
$stores_per_page = couponxxl_get_option( 'stores_per_page' );
$this_permalink  = couponxxl_get_permalink_by_tpl( 'page-tpl_all_stores' );
$keyword         = ! empty( $_GET[ $couponxxl_slugs['keyword'] ] ) ? urldecode( $_GET[ $couponxxl_slugs['keyword'] ] ) : '';

$keyword = '';
if ( isset( $_GET[ $couponxxl_slugs['keyword'] ] ) ) {
	$keyword = urldecode( $_GET[ $couponxxl_slugs['keyword'] ] );
}

$args = array(
	'post_type'      => 'store',
	'paged'          => $cur_page,
	'posts_per_page' => $stores_per_page,
	'post_status'    => 'publish',
	'orderby'        => 'title',
	'order'          => 'asc',
	's'              => $keyword,
	'tax_query'      => array(
		'relation' => 'AND'
	),
);
if ( ! empty( $letter ) ) {
	add_filter( 'posts_where', 'couponxxl_filter_stores_by_letter' );
}

$stores = new WP_Query( $args );

if ( ! empty( $letter ) ) {
	remove_filter( 'posts_where', 'couponxxl_filter_stores_by_letter' );
}

$page_links_total = $stores->max_num_pages;
$page_links       = paginate_links( array(
	'prev_next' => true,
	'end_size'  => 2,
	'mid_size'  => 2,
	'total'     => $page_links_total,
	'current'   => $cur_page,
	'prev_text' => '<i class="pline-angle-left"></i> ' . esc_html__( 'Previous', 'couponxxl' ),
	'next_text' => esc_html__( 'Next', 'couponxxl' ) . ' <i class="pline-angle-right"></i>',
	'prev_next' => true,
	'type'      => 'array'
) );
$pagination       = couponxxl_format_pagination( $page_links );

$all_stores_link = couponxxl_get_permalink_by_tpl( 'page-tpl_all_stores' );

?>
	<div class="container">

		<?php
		$content = get_the_content();
		if ( ! empty( $content ) ):
			?>
			<div class="white-block">
				<div class="white-block-content">
					<div class="page-content clearfix">
						<?php echo apply_filters( 'the_content', $content ) ?>
					</div>
				</div>
			</div>
			<?php
		endif;
		?>

		<div class="row">

			<div class="col-md-12">

				<div class="white-block filter-block">
					<div class="white-block-content'">
						<div class="row">
							<div class="col-sm-9">
								<a href="<?php echo esc_url( $all_stores_link ); ?>"
								   class="<?php echo $letter == '' ? esc_attr( 'active' ) : esc_attr( '' ) ?>"><?php esc_html_e( 'ALL', 'couponxxl' ) ?></a>
								<?php couponxxl_get_store_letters( $all_stores_link, $letter ); ?>
							</div>
							<div class="col-sm-3">
								<form role="search" method="get" action="<?php echo esc_url( $all_stores_link ) ?>">
									<input type="text" name="<?php echo esc_attr__( $couponxxl_slugs['keyword'] ) ?>"
									       value="<?php esc_attr__( $couponxxl_slugs['keyword'] ) ?>"
									       class="search-categories"
									       placeholder="<?php _e( 'Search stores', 'couponxxl' ) ?>"/>
									<i class="pline-magnifier"></i>
								</form>
							</div>
						</div>
					</div>
				</div>

				<?php if ( ! empty( $keyword ) ) : ?>
					<div class="row">
						<div class="col-md-12">
							<div class="white-block filter-block">
								<div class="white-block-content"></div>
								<h5>Filter by keyword: <strong><?php echo $keyword ?></strong></h5>
							</div>
						</div>
					</div>
				<?php endif; ?>


				<div class="row">
					<?php if ( ! empty( $keyword ) ) {
						$counter = 0;
						if ( $stores->have_posts() ) {
							while ( $stores->have_posts() ) {
								$stores->the_post();
								if ( $counter == 4 ) {
									$counter = 0;
									echo '</div><div class="row">';
								}
								$counter ++;
								?>
								<div class="col-sm-3">
									<div class="white-block all-stores">
										<div class="embed-responsive embed-responsive-4by3">
											<div class="store-logo">
												<a href="<?php the_permalink() ?>">
													<?php couponxxl_store_logo(); ?>
												</a>
											</div>
										</div>
										<div class="store-name">
											<a href="<?php the_permalink() ?>">
												<?php the_title(); ?>
											</a>
										</div>
									</div>
								</div>
								<?php
							}
						}
					} else {
						$counter = 0;
						if ( $stores->have_posts() ) {
							while ( $stores->have_posts() ) {
								$stores->the_post();
								if ( $counter == 4 ) {
									$counter = 0;
									echo '</div><div class="row">';
								}
								$counter ++;
								?>
								<div class="col-sm-3">
									<div class="white-block all-stores">
										<div class="embed-responsive embed-responsive-4by3">
											<div class="store-logo">
												<a href="<?php the_permalink() ?>">
													<?php couponxxl_store_logo(); ?>
												</a>
											</div>
										</div>
										<div class="store-name">
											<a href="<?php the_permalink() ?>">
												<?php the_title(); ?>
											</a>
										</div>
									</div>
								</div>
								<?php
							}
						}
					}
					?>
				</div>

				<?php
				if ( ! empty( $pagination ) ) {
					?>
					<div class="pagination-wrap">
						<ul class="pagination">
							<?php echo $pagination; ?>
						</ul>
						<div class="pagination-info">
							<strong><?php echo ( $cur_page - 1 ) * $stores_per_page + 1 ?></strong> -
							<strong><?php echo ( $cur_page - 1 ) * $stores_per_page + $stores->post_count ?></strong>
							<?php esc_html_e( 'of', 'couponxxl' ) ?>
							<strong><?php echo $stores->found_posts ?></strong>
							<?php esc_html_e( 'results', 'couponxxl' ) ?>
						</div>
					</div>
					<?php
				}
				?>

			</div>

		</div>
	</div>
<?php
wp_reset_postdata();
get_footer();
?>