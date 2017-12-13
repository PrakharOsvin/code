<?php if( $show_deals_map == 'yes' ): ?>
    <div class="search-map hidden">
        <a href="javascript:;" class="hide-map">
            <?php echo '<i class="pline-arrow-left"></i> '.esc_html__( 'Hide', 'couponxxl' ); ?>
        </a>
        <div id="map-markers"></div>
    </div>
<?php endif; ?>