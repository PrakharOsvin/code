<?php
/*
	DEFAULT BLOG LSITING WITH THE MASONRY
*/	
get_header();
global $wp_query;
$cur_page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; //get curent page

$page_links_total =  $wp_query->max_num_pages;
$page_links = paginate_links( 
	array(
		'prev_next' 	=> true,
		'end_size' 		=> 2,
		'mid_size' 		=> 2,
		'total' 		=> $page_links_total,
		'current' 		=> $cur_page,	
		'prev_text'     => '<i class="pline-angle-left"></i> '.esc_html__( 'Previous', 'couponxxl' ),
		'next_text'     => esc_html__( 'Next', 'couponxxl' ).' <i class="pline-angle-right"></i>',
		'prev_next' 	=> true,
		'type' 			=> 'array'
	)
);
$pagination = couponxxl_format_pagination( $page_links );
get_template_part( 'includes/title' );

?>
<section>
	<div class="container">
		<div class="row">
			<div class="col-md-<?php echo is_active_sidebar( 'sidebar-blog' ) ? '9' : '12' ?>">
				<div class="row masonry">
					<?php
					if( have_posts() ){
						while( have_posts() ){
							the_post();
							$has_media = has_post_thumbnail();
							?>
							<div class="col-sm-6 masonry-item">
								<div <?php post_class( 'white-block blog-item '.( $has_media ? 'has-media' : '' ) ) ?>>
									<?php
									if( is_sticky() ){
										?>
										<div class="sticky">
											<i class="fa fa-paperclip"></i>
										</div>
										<?php
									}
									?>

									<?php if( $has_media ): ?>
										<div class="white-block-media">
											<a href="<?php the_permalink() ?>">
												<?php the_post_thumbnail( 'couponxxl-blog-listing' ); ?>
											</a>
										</div>
									<?php endif; ?>

									<div class="white-block-content blog-item-content">
										<a href="<?php the_permalink() ?>">
											<h4 class="blog-title"><?php the_title() ?></h4>
										</a>

										<?php the_excerpt() ?>

										<a href="<?php the_permalink() ?>" class="read-more"><?php esc_html_e( 'Read more', 'couponxxl' ) ?></a>
									</div>


								</div>
							</div>
							<?php
						}
					}
					else{
						?>
						<div class="white-block">
							<?php esc_html_e( 'No posts found.', 'couponxxl' ) ?>
						</div>
						<?php
					}
					?>
				</div>

				<?php
				if( !empty( $pagination ) )	{
					$posts_per_page = get_option('posts_per_page');
					?>
					<div class="pagination-wrap">
						<ul class="pagination">
							<?php echo  $pagination; ?>
						</ul>
                        <div class="pagination-info">
                            <strong><?php echo ( $cur_page - 1 ) * $posts_per_page + 1 ?></strong> - <strong><?php echo ( $cur_page - 1 ) * $posts_per_page + $wp_query->post_count ?></strong>
                            <?php esc_html_e( 'of', 'couponxxl' ) ?>
                            <strong><?php echo  $wp_query->found_posts ?></strong>
                            <?php esc_html_e( 'results', 'couponxxl' ) ?>
                        </div>
					</div>
					<?php
				}
				?>				
			</div>

			<?php get_sidebar(); ?>

		</div>
	</div>
</section>

<?php get_footer(); ?>