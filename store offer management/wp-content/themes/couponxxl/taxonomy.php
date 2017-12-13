<?php
get_header();
the_post();
require_once( couponxxl_load_path( 'includes/title.php' ) );

$cur_page = 1;
if( get_query_var( 'paged' ) ){
    $cur_page = get_query_var( 'paged' );
}
else if( get_query_var( 'page' ) ){
    $cur_page = get_query_var( 'page' );
}
$args = array(
	'post_status' => 'publish',
	'paged' => $cur_page,
	'tax_query' => array(
		array(
			'taxonomy' => get_query_var( 'taxonomy' ),
			'field'    => 'slug',
			'terms'    => get_query_var( 'term' ),
		)
	),
);


$offers = new WP_Offers_Query( $args );
$page_links_total =  $offers->max_num_pages;
$pagination_args = array(
    'prev_next' => true,
    'end_size' => 2,
    'prev_text' => '<i class="pline-angle-left"></i> '.esc_html__('Previous', 'couponxxl'),
    'next_text' => esc_html__('Next', 'couponxxl').' <i class="pline-angle-right"></i> ',
    'mid_size' => 2,
    'total' => $page_links_total,
    'current' => $cur_page, 
    'prev_next' => false,
    'type' => 'array'
);
$page_links = paginate_links( $pagination_args );

$pagination = couponxxl_format_pagination( $page_links );  

?>
<section>
    <div class="container">
    	<?php
		if( $offers->have_posts() ){
            $counter = 0;
			?>
			<div class="row">
    			<?php
    			while( $offers->have_posts() ){
    				$offers->the_post();
                    if( $counter == 4 ){
                        echo '</div><div class="row">';
                        $counter = 0;
                    }
                    $counter++;
    				?>
    				<div class="col-sm-3">
    					<?php include( couponxxl_load_path( 'includes/offers/offers.php' ) ); ?>
    				</div>
    				<?php
    			}
                wp_reset_postdata();
    			?>
			</div>

            <?php if( !empty( $pagination ) ): ?>
                <div class="pagination-wrap">
                    <ul class="pagination">
                       <?php echo  $pagination; ?>
                    </ul>
                    <div class="pagination-info">
                        <strong><?php echo ( $cur_page - 1 ) * $offers_per_page + 1 ?></strong> - <strong><?php echo ( $cur_page - 1 ) * $offers_per_page + $offers->post_count ?></strong>
                        <?php esc_html_e( 'of', 'couponxxl' ) ?>
                        <strong><?php echo  $offers->found_posts ?></strong>
                        <?php esc_html_e( 'results', 'couponxxl' ) ?>
                    </div>
                </div>
            <?php endif; ?>
			<?php
		}
        else{
            ?>
            <div class="white-block">
                <div class="white-block-content">
                    <?php echo couponxxl_get_option( 'search_no_offers_message' ); ?>
                </div>
            </div>
            <?php
        }
    	?>
    </div>
</section>
<?php
get_footer();
?>