<?php if( $show_deals_map == 'yes' ): ?>
    <div class="markers hidden">
        <?php couponxxl_generate_markers( $marker_deals ); ?>
    </div>
<?php endif; ?>