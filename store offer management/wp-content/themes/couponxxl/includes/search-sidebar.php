<?php  include( couponxxl_load_path( 'includes/search-map/map-trigger.php' ) ); ?>
<div class="widget white-block widget_couponxxl_filter">

    <div class="overlay"></div>

    <form action="<?php echo esc_url( $permalink ) ?>" class="advanced-search">

    <?php
    if( !empty( $_GET ) ){
        ?>
        <a href="javascript:;" class="alert alert-danger reset-search">
            <?php esc_html_e( 'Reset Search', 'couponxxl' ) ?>
            <i class="fa fa-times"></i>
        </a>
        <?php
    }
    ?>

    <div class="category-filter">
        <?php
        $permalink = couponxxl_get_permalink_by_tpl( 'page-tpl_search' );
        $search_page_category_filter_title = couponxxl_get_option( 'search_page_category_filter_title' );
        if( !empty( $search_page_category_filter_title ) ):
        ?>
            <div class="widget-title">
                <h4>
                    <?php echo  $search_page_category_filter_title; ?>
                </h4>
            </div>
        <?php endif; ?>
        <?php

        global $search_show_count;
        $search_show_count = couponxxl_get_option( 'search_show_count' );
        $start_parent = 0;
        $ancestors = array();
        $selected = '';

        if( !empty( $offer_cat ) ){
            $offer_cat_term = get_term_by( 'slug', $offer_cat, 'offer_cat' );
            if( !empty( $offer_cat_term ) ){
                $selected = $offer_cat_term;
                $ancestors = get_ancestors( $offer_cat_term->term_id, 'offer_cat' );
                array_unshift( $ancestors, $offer_cat_term->term_id );
                $start_parent = 0;
            }
            
        }

        echo '<input type="hidden" name="'.$couponxxl_slugs['offer_cat'].'" value="'.esc_attr( $offer_cat ).'" class="offer_cat">';
        couponxxl_list_search_sidebar_cats( $ancestors, $start_parent, $selected, $search_show_count, $permalink );

        ?>
    </div>

    <?php if( $offer_type == 'deal' ): ?>
        <div class="location-filter">
            <?php
            $search_page_location_filter_title = couponxxl_get_option( 'search_page_location_filter_title' );
            if( !empty( $search_page_location_filter_title ) ):
            ?>
                <div class="widget-title">
                    <h4>
                        <?php echo  $search_page_location_filter_title ?>
                    </h4>
                </div>
            <?php endif; ?>
            <ul class="list-unstyled">
                <?php
                $search_visible_locations_count = couponxxl_get_option( 'search_visible_locations_count' );
                $all_parent_locations = get_terms( 'location', array( 'parent' => 0, 'number' => $search_visible_locations_count ) );
                if( !empty( $all_parent_locations ) ){
                    foreach( $all_parent_locations as $parent_location ){
                        $count = '';
                        if( $search_show_count == 'yes' ){
                            $count = couponxxl_custom_term_count( $parent_location, 'location' );
                        }

                        echo '
                        <li class="'.( in_array( $parent_location->slug, $location ) ? 'active current' : '' ).'">
                            <input type="checkbox" name="'.$couponxxl_slugs['location'].'[]" id="location_'.esc_attr( $parent_location->slug ).'" value="'.esc_attr( $parent_location->slug ).'" '.( in_array( $parent_location->slug, $location ) ? 'checked="checked"' : '' ).'>
                            <label for="location_'.esc_attr( $parent_location->slug ).'">'.$parent_location->name.' <span class="count">('.$count.')</span></label>
                        </li>';
                    }
                }
                ?>
            </ul>
            <?php
                if( $search_visible_locations_count ){
                    echo '<div class="show-all"><a href="#locations" data-toggle="modal">'.esc_html__( 'See More', 'couponxxl' ).'</a></div>';
                }
            ?>
            <div class="filter-location-modal hidden"></div>
        </div>  
    <?php  endif; ?>

    <div class="store-filter">
        <?php
        $search_page_store_filter_title = couponxxl_get_option( 'search_page_store_filter_title' );
        if( !empty( $search_page_store_filter_title ) ):
        ?>
            <div class="widget-title">
                <h4>
                    <?php echo  $search_page_store_filter_title ?>
                </h4>
            </div>
        <?php endif; ?>
        <ul class="list-unstyled">
            <?php
            $search_visible_stores_count = couponxxl_get_option( 'search_visible_stores_count' );

            $all_stores = get_posts(array( 
                'post_type' => 'store',
                'posts_per_page' => $search_visible_stores_count,
                'post_status' => 'publish'
            ));
            if( !empty( $all_stores ) ){
                foreach( $all_stores as $store ){
                    $count = '';
                    if( $search_show_count == 'yes' ){
                        $count = couponxxl_custom_store_count( $store->ID );
                    }
                    echo '
                    <li class="'.( in_array( $store->ID, $offer_store ) ? 'active current' : '' ).'">
                        <input type="checkbox" name="'.$couponxxl_slugs['offer_store'].'[]" id="offer_store_'.esc_attr( $store->ID ).'" value="'.esc_attr( $store->ID ).'" '.( in_array( $store->ID, $offer_store ) ? 'checked="checked"' : '' ).'>
                        <label for="offer_store_'.esc_attr( $store->ID ).'">'.$store->post_title.' <span class="count">('.$count.')</span></label>
                    </li>';
                }
            }
            ?>
        </ul>
        <?php
            if( $search_visible_stores_count ){
                echo '<div class="show-all"><a href="#stores" data-toggle="modal">'.esc_html__( 'See More', 'couponxxl' ).'</a></div>';
            }
        ?>
        <div class="filter-offer_store-modal hidden"></div>
    </div> 

    <?php
    $theme_usage = couponxxl_get_option( 'theme_usage' );
    if( $theme_usage == 'all' ):
    ?>
        <div class="offer-type-filter">
            <?php
            $search_page_offer_type_filter_title = couponxxl_get_option( 'search_page_offer_type_filter_title' );
            if( !empty( $search_page_offer_type_filter_title ) ):
            ?>
                <div class="widget-title">
                    <h4>
                        <?php echo  $search_page_offer_type_filter_title ?>
                    </h4>
                </div>
            <?php endif; ?>
            <ul class="list-unstyled">
                <li>
                    <input type="radio" name="<?php echo esc_attr( $couponxxl_slugs['offer_type'] ); ?>" id="all" value="" <?php echo empty( $offer_type ) ? 'checked="checked"' : '' ?>>
                    <label for="all"><?php esc_html_e( 'All', 'couponxxl' ) ?></label>
                </li>
                <li>
                    <input type="radio" name="<?php echo esc_attr( $couponxxl_slugs['offer_type'] ); ?>" id="deal" value="deal" <?php echo  $offer_type == 'deal' ? 'checked="checked"' : '' ?>>
                    <label for="deal"><?php esc_html_e( 'Deals Only', 'couponxxl' ) ?></label>
                </li>
                <li>
                    <input type="radio" name="<?php echo esc_attr( $couponxxl_slugs['offer_type'] ); ?>" id="coupon" value="coupon" <?php echo  $offer_type == 'coupon' ? 'checked="checked"' : '' ?>>
                    <label for="coupon"><?php esc_html_e( 'Coupons Only', 'couponxxl' ) ?></label>
                </li>
            </ul>
        </div>
    <?php endif; ?>

    </form>

</div>
<?php 
    if ( is_active_sidebar( 'sidebar-search' ) ){
        dynamic_sidebar( 'sidebar-search' );
    }
?>