<?php
/*
    Template Name: Home Page
*/
get_header();
the_post();

$home_page_show_title = couponxxl_get_option( 'home_page_show_title' );
$home_page_show_search = couponxxl_get_option( 'home_page_show_search' );
$extra_body_class =  '';
if( $home_page_show_title == 'no' && $home_page_show_search == 'no' ){
	$extra_body_class =  'home-extra-padding';
}
?>
<section class="home-page-body <?php echo esc_attr( $extra_body_class ) ?>">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php if( $home_page_show_title == 'yes' ): ?>
                    <div class="home-page-title">
                        <h1><?php echo couponxxl_get_option( 'home_page_title' ) ?></h1>
                        <h5>
                        <?php
                            $stores = wp_count_posts( 'store' );
                            $stores = $stores->publish;

                            $locations = wp_count_terms( 'location' );

                            echo str_replace( array( '%stores%', '%locations%' ), array( $stores, $locations ), couponxxl_get_option( 'home_page_subtitle' ) );
                        ?>
                        </h5>
                    </div>
                <?php endif; ?>

                <?php if( $home_page_show_search == 'yes' ): ?>
                    <div class="home-page-search-box clearfix <?php echo $home_page_show_title == 'no' ? esc_attr( 'top-margin' ) : ''; ?>">
                        <?php include( couponxxl_load_path('includes/main-search-values.php') ) ?>
                        <form method="get" action="<?php echo esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ) ) ?>" class="main-search">
                            <div class="col-sm-6">
                                <div class="input-group">
                                  <input type="text" class="form-control top_bar_search" value="<?php echo esc_attr( $term_name ); ?>" placeholder="<?php echo couponxxl_get_option( 'home_page_search_location_placeholder' ) ?>">
                                  <input type="hidden" name="<?php echo esc_attr( $couponxxl_slugs['location'] ) ?>" value="<?php echo esc_attr( $location ) ?>">
                                  <small><?php echo couponxxl_get_option( 'home_page_search_location_desc' ) ?></small>
                                  <div class="search_options"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                  <input type="text" class="form-control top_bar_search" value="<?php echo esc_attr( $store_name ); ?>" placeholder="<?php echo couponxxl_get_option( 'home_page_search_store_placeholder' ) ?>">
                                  <input type="hidden" name="<?php echo esc_attr( $couponxxl_slugs['offer_store'] ) ?>" value="<?php echo esc_attr( $offer_store ) ?>">
                                  <small><?php echo couponxxl_get_option( 'home_page_search_store_desc' ) ?></small>
                                  <div class="search_options"></div>
                                </div>                    
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <div class="page-content clearfix">
            <?php the_content(); ?>
        </div>        
    </div>
</section>
<?php get_footer(); ?>