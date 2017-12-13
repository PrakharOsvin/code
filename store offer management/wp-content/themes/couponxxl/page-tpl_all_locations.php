<?php
/*
    Template Name: All Locations
*/
get_header();
the_post();
get_template_part( 'includes/title' );

global $couponxxl_slugs;
$location_search = !empty( $_GET['location_search'] ) ? $_GET['location_search'] : '';
$letter = get_query_var( $couponxxl_slugs['letter'], '' );

$locations = couponxxl_get_organized( 'location' );
$permalink = couponxxl_get_permalink_by_tpl( 'page-tpl_search' );
$this_permalink = couponxxl_get_permalink_by_tpl('page-tpl_all_locations');

?>

<section class="contact-page">
    <div class="container">

        <?php 
        $content = get_the_content();
        if( !empty( $content ) ):
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

        <div class="white-block filter-block">
            <div class="white-block-content'">
                <div class="row">
                    <div class="col-sm-9">
                        <a href="<?php echo esc_url( $this_permalink ) ?>" class="<?php echo empty( $letter ) ? esc_attr( 'active' ) : esc_attr( '' ) ?>">
                            <?php esc_html_e( 'ALL', 'couponxxl' ) ?>
                        </a>
                        <?php
                        $letters = array();
                        if( !empty( $locations ) ){
                            foreach( $locations as $location ) {
                                if( !in_array( $location->name[0], $letters ) ){
                                    $letters[] = $location->name[0];
                                }
                            }
                        }
                        if( !empty( $letters ) ){
                            sort( $letters );
                            foreach( $letters as $letter_item ){
                                ?>
                                <a href="<?php echo esc_url( couponxxl_append_query_string( $this_permalink, array( $couponxxl_slugs['letter'] => $letter_item ) ) ) ?>" class="<?php echo $letter == $letter_item ? esc_attr('active') : esc_attr('') ?>">
                                    <?php echo strtoupper( $letter_item ) ?>
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="col-sm-3">
                        <form method="get" action="<?php echo esc_url( $this_permalink ) ?>">
                            <input type="text" name="location_search" value="<?php echo esc_attr( $location_search ) ?>" class="search-categories" placeholder="<?php _e( 'Search locations', 'couponxxl' ) ?>"/>
                            <i class="pline-magnifier"></i>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="row masonry">
            <?php
            if( !empty( $location_search )){
                $locations = get_terms( 'location', array('hide_empty' => false, 'name__like' => $location_search ));
                if( !empty( $locations ) ){
                    ?>
                    <div class="col-sm-3 masonry-item">
                        <div class="white-block">
                            <div class="white-block-content">
                                
                                <p class="lead-category"><?php esc_html_e( 'Search Results', 'couponxxl' ) ?></p>
                                
                                <div class="category-children">
                                    <ul class="list-unstyled">
                                        <?php foreach( $locations as $location ): ?>
                                            <li>
                                                <a href="<?php echo esc_url( couponxxl_append_query_string( $permalink, array( $couponxxl_slugs['location'] => $location->slug ) ) ); ?>">
                                                    <?php echo  $location->name; ?>
                                                    <span class="count"><?php echo  $location->count; ?></span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            else{
                if( !empty( $locations ) ){
                    foreach( $locations as $location ){
                        $can_display = true;
                        if( !empty( $letter ) && $location->name[0] !== $letter ){
                            $can_display = false;
                        }
                        else if( !empty( $location_search ) && !stristr( $location->name, $location_search )){
                            $can_display = false;
                        }

                        if( $can_display ):
                            ?>
                            <div class="col-sm-3 masonry-item">
                                <div class="white-block">
                                    <div class="white-block-content">
                                        <a href="<?php echo esc_url( couponxxl_append_query_string( $permalink, array( $couponxxl_slugs['location'] => $location->slug ) ) ); ?>" class="lead-category">
                                            <?php echo  $location->name; ?>
                                            <span class="count"><?php echo  $location->count; ?></span>
                                        </a>
                                        <?php if( !empty( $location->children ) ): ?>
                                            <div class="category-children">
                                                <?php couponxxl_display_tree( $location, 'location' ); ?>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endif;
                    }
                }
            }
            ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>