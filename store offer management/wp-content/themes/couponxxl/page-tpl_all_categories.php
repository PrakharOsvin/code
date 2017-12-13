<?php
/*
    Template Name: All Categories
*/
get_header();
the_post();
get_template_part( 'includes/title' );

global $couponxxl_slugs;
$category_search = !empty( $_GET['category_search'] ) ? $_GET['category_search'] : '';
$letter = get_query_var( $couponxxl_slugs['letter'] );

$offer_cats = couponxxl_get_organized( 'offer_cat' );
$permalink = couponxxl_get_permalink_by_tpl( 'page-tpl_search' );
$this_permalink = couponxxl_get_permalink_by_tpl( 'page-tpl_all_categories' );


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
                        if( !empty( $offer_cats ) ){
                            foreach( $offer_cats as $offer_cat ){
                                if( !in_array( $offer_cat->name[0], $letters ) ){
                                    $letters[] = $offer_cat->name[0];
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
                            <input type="text" name="category_search" value="<?php echo esc_attr( $category_search ) ?>" class="search-categories" placeholder="<?php _e( 'Search categories', 'couponxxl' ) ?>"/>
                            <i class="pline-magnifier"></i>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="row masonry">
            <?php
            if( !empty( $category_search )){
                $offer_cats = get_terms( 'offer_cat', array('hide_empty' => false, 'name__like' => $category_search ));
                if( !empty( $offer_cats ) ){
                    ?>
                    <div class="col-sm-3 masonry-item">
                        <div class="white-block">
                            <div class="white-block-content">
                                
                                <p class="lead-category"><?php esc_html_e( 'Search Results', 'couponxxl' ) ?></p>
                                
                                <div class="category-children">
                                    <ul class="list-unstyled">
                                        <?php foreach( $offer_cats as $cat ): ?>
                                            <li>
                                                <a href="<?php echo esc_url( couponxxl_append_query_string( $permalink, array( $couponxxl_slugs['offer_cat'] => $cat->slug ) ) ); ?>">
                                                    <?php echo  $cat->name; ?>
                                                    <span class="count"><?php echo  $cat->count; ?></span>
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
                if( !empty( $offer_cats ) ){
                    foreach( $offer_cats as $offer_cat ){
                        $can_display = true;
                        if( !empty( $letter ) && $offer_cat->name[0] !== $letter ){
                            $can_display = false;
                        }
                        else if( !empty( $category_search ) && !stristr( $offer_cat->name, $category_search )){
                            $can_display = false;
                        }

                        if( $can_display ):
                            ?>
                            <div class="col-sm-3 masonry-item">
                                <div class="white-block">
                                    <div class="white-block-content">
                                        <a href="<?php echo esc_url( couponxxl_append_query_string( $permalink, array( $couponxxl_slugs['offer_cat'] => $offer_cat->slug ) ) ); ?>" class="lead-category">
                                            <?php echo  $offer_cat->name; ?>
                                            <span class="count"><?php echo  $offer_cat->count; ?></span>
                                        </a>
                                        <?php if( !empty( $offer_cat->children ) ): ?>
                                            <div class="category-children">
                                                <?php couponxxl_display_tree( $offer_cat, 'offer_cat' ); ?>
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