<div class="small-device-categories">

    <a href="javascript:;" class="categories-trigger">
        <i class="fa fa-bars"></i>
    </a>

    <div class="small-device-wrapper">
        <h5 class="small-device-title"><?php  esc_html_e( 'Categories', 'couponxxl' )?></h5>

        <?php
        $offer_cat = get_terms( 'offer_cat', array( 'parent' => 0 ) );
        global $couponxxl_slugs;
        if( !empty( $offer_cat ) && !is_wp_error( $offer_cat ) ){
            ?>
            <ul class="list-unstyled">
                <?php
                foreach( $offer_cat as $cat ){
                    if( !empty( $cat ) && !is_wp_error( $cat ) ){
                        ?>
                        <li>
                            <a href="<?php echo esc_url( couponxxl_append_query_string( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ), array( $couponxxl_slugs['offer_cat'] => $cat->slug ) ) ) ?>">
                                <?php echo  $cat->name ?>
                            </a>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
            <?php
        }
        ?>
    </div>
</div>