<?php
/*
	Template Name: Search Page
*/
get_header();
the_post();
require_once( couponxxl_load_path( 'includes/title.php' ) );
$permalink = couponxxl_get_permalink_by_tpl( 'page-tpl_search' );

global $couponxxl_slugs;
include( couponxxl_load_path( 'includes/search-args.php' ) );

$offers_per_page = couponxxl_get_option( 'offers_per_page' );
$show_deals_map = couponxxl_get_option( 'show_deals_map' );

?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-3 ajax-sidebar">
                <?php include( couponxxl_load_path( 'includes/search-sidebar.php' ) ) ?>
            </div>
            <div class="col-md-9 ajax-results">

                <?php 

                include( couponxxl_load_path( 'includes/search-map/map-holder.php' ) );

                $show_search_slider = couponxxl_get_option( 'show_search_slider' );
                if( $show_search_slider == 'yes' ){
                    include( couponxxl_load_path( 'includes/featured-slider.php' ) );
                }
                ?>

            	<?php
                    $cur_page = 1;
                    if( get_query_var( 'paged' ) ){
                        $cur_page = get_query_var( 'paged' );
                    }
                    else if( get_query_var( 'page' ) ){
                        $cur_page = get_query_var( 'page' );
                    }
            		$args = array(
            			'post_status' => 'publish',
            			'posts_per_page' => $offers_per_page,
            			'post_type'	=> 'offer',
            			'paged' => $cur_page,
            			'tax_query' => array(
            				'relation' => 'AND'
            			),
                        'meta_query' => array(
                            'relation' => 'AND'
                        ),
            		);

                    $args['orderby'] = couponxxl_get_option( 'search_order_by' );
                    $args['order'] = couponxxl_get_option( 'search_order' );

                    if( !empty( $offer_sort ) ){
                        $temp = explode( '-', $offer_sort );
                        if( $temp[0] == 'date' ){
                            $args['orderby'] = $temp[0];
                            $args['order'] = $temp[1];
                        }
                        else{
                            if( $temp[0] == 'rate' ){
                                $temp[0] = 'couponxxl_average_rate';
                            }
                            $args['meta_key'] = $temp[0];
                            $args['order'] = $temp[1];
                        }
                    }

            		if( !empty( $offer_type ) ){
            			$args['offer_type'] = $offer_type;
            		}

                    if( !empty( $offer_store ) ){
                        $args['meta_query'][] = array(
                            'key' => 'offer_store',
                            'value' => $offer_store,
                            'compare' => 'IN',
                        );
                    }

            		if( !empty( $offer_cat ) ){
            			$args['tax_query'][] = array(
            				'taxonomy' => 'offer_cat',
            				'field'	=> 'slug',
            				'terms' => $offer_cat,
            			);
            		}

            		if( !empty( $offer_tag ) ){
            			$args['tax_query'][] = array(
            				'taxonomy' => 'offer_tag',
            				'field'	=> 'slug',
            				'terms' => $offer_tag,
            			);
            		}
            		if( !empty( $location ) ){
            			$args['tax_query'][] = array(
            				'taxonomy' => 'location',
            				'field'	=> 'slug',
            				'terms' => $location,
            			);
            		}

                    if( !empty( $keyword ) ){
                        $args['s'] = urldecode( $keyword );
                    }

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

                    $marker_deals = array();
            		if( $offers->have_posts() ){
                        $counter = 0;
            			?>
            			<div class="row">
	            			<?php
	            			while( $offers->have_posts() ){
	            				$offers->the_post();
                                if( $counter == 3 ){
                                    echo '</div><div class="row">';
                                    $counter = 0;
                                }
                                $counter++;
                                if( $show_deals_map == 'yes' ){
                                    $marker_deals[] = array(
                                        'title' => get_the_title(),
                                        'permalink' => get_permalink(),
                                        'ID' => get_the_ID(),
                                        'image' => get_the_post_thumbnail( get_the_ID(), 'couponxxl-popup-box' ),
                                        'deal_markers' => get_post_meta( get_the_ID(), 'deal_markers', true ),
                                        'offer_price' => couponxxl_get_deal_meta( get_the_ID(), false )
                                    );
                                }
	            				?>
	            				<div class="col-sm-4">
	            					<?php include( couponxxl_load_path( 'includes/offers/offers.php' ) ); ?>
	            				</div>
	            				<?php
	            			}
                            wp_reset_postdata();
	            			?>
            			</div>

                        <?php include( couponxxl_load_path( 'includes/search-map/map-markers-holder.php' ) ); ?>

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
        </div>
    </div>
</section>
<?php
get_footer();
?>