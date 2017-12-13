<?php 
$theme_usage = couponxxl_get_option( 'theme_usage' );
if( $show_deals_map == 'yes' && $offer_type !== 'coupon' && ( $theme_usage == 'all' || $theme_usage == 'deal' ) ): ?>
    <div class="show-map">
        <a href="javascript:;" class="show-map">
            <div class="trigger-text">
                <?php echo esc_html__( 'Show', 'couponxxl' ).' <i class="pline-arrow-right"></i>'; ?>
            </div>
            <?php
            $map_trigger_img = couponxxl_get_option( 'map_trigger_img' );
            if( !empty( $map_trigger_img['url'] ) ){
                echo '<img src="'.esc_url( $map_trigger_img['url'] ).'" height="'.esc_attr( $map_trigger_img['height'] ).'" width="'.esc_attr( $map_trigger_img['width'] ).'" alt="">';
            }
            ?>
        </a>
    </div>
<?php endif; ?>