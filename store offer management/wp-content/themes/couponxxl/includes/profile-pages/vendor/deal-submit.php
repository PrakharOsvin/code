<h1></h1>
<div>
    <div class="input-group">
        <label for="deal_items"><?php esc_html_e( 'Deal Items', 'couponxxl' ); ?> <span class="required">*</span></label>
        <input type="text" name="deal_items" id="deal_items" value="<?php echo esc_attr( $deal_items ) ?>" class="form-control" data-validation="required" data-error="<?php _e( 'Please input number of deal items you wish to sell', 'couponxxl' ) ?>">
        <p class="description"><?php esc_html_e( 'Input number of deal items or services which will be available for purchase.', 'couponxxl' ); ?></p>    
    </div>
    <div class="input-group">
        <label for="deal_item_vouchers"><?php esc_html_e( 'Deal Item Vouchers', 'couponxxl' ); ?></label>
        <textarea name="deal_item_vouchers" id="deal_item_vouchers" class="form-control" data-validation="length_conditional" data-field_number_val="#deal_items" data-error="<?php _e( 'You need to input number of vouchers same as inputed deal item.', 'couponxxl' ) ?>"><?php echo esc_attr( $deal_item_vouchers ) ?></textarea>
        <p class="description"><?php esc_html_e( 'If you want to serve predefined vouchers instead of random generated ones, input them here one in a row and make sure that you have same amount of these vouchers as the number of items.', 'couponxxl' ); ?></p>    
    </div>
    <div class="input-group">
        <label for="deal_voucher_expire"><?php esc_html_e( 'Deal Voucher Expire Date', 'couponxxl' ); ?> </label>
        <input type="text" name="deal_voucher_expire" id="deal_voucher_expire" value="<?php echo !empty( $deal_voucher_expire ) ? date( 'Y-m-d', $deal_voucher_expire ) : ''; ?>" class="form-control" readonly="readonly" data-min-date="<?php echo date( 'Y/m/d', current_time( 'timestamp' ) + 24*60*60 ); ?>" >
        <p class="description"><?php esc_html_e( 'Set expire date and time for vouchers generated after purchase or leave empty for unlimited last ( How much time voucher is valid after purchase? )', 'couponxxl' ); ?></p>
    </div>
    <div class="input-group">
        <label for="deal_images"><?php esc_html_e( 'Deal Images', 'couponxxl' ); ?> </label>
        <input type="hidden" name="deal_images" id="deal_images" class="form-control">
        <div class="deal-images-wrap">
            <?php
            $deal_images = couponxxl_smeta_images( 'deal_images', get_the_ID(), array() );
            if( !empty( $deal_images ) ){
                foreach( $deal_images as $deal_image ){
                    ?>
                    <div class="deal-image-wrap" data-image_id="<?php echo esc_attr( $deal_image ) ?>">
                        <?php echo wp_get_attachment_image( $deal_image, 'thumbnail' ) ?>
                        <a href="javascript:;" class="remove-deal-image"><i class="fa fa-close"></i></a>
                    </div>
                    <?php
                }
            }
            ?>            
        </div>
        <a href="javascript:;" class="image-upload deal-images"><?php esc_html_e( 'Select deal images', 'couponxxl' ) ?></a>
        <p class="description"><?php esc_html_e( 'Choose images for the deal. Drag and drop to change their order.', 'couponxxl' ); ?></p>
    </div>
    <div class="input-group">
        <label for="deal_link"><?php esc_html_e( 'Deal Affiliate Link', 'couponxxl' ); ?> </label>
        <input type="text" name="deal_link" id="deal_link" value="<?php echo esc_attr( $deal_link ) ?>" class="form-control">
        <p class="description"><?php esc_html_e( 'Input affiliate link for the deal in order to avoid payment over this website.', 'couponxxl' ); ?></p>
    </div>
    <div class="input-group">
        <label for="deal_excerpt"><?php esc_html_e( 'Deal Short Description', 'couponxxl' ); ?> </label>
        <textarea type="text" name="deal_excerpt" id="deal_excerpt" class="form-control"><?php echo  $deal_excerpt ?></textarea>
        <p class="description"><?php esc_html_e( 'Input description which will appear in the deal single page sidebar.', 'couponxxl' ); ?></p>
    </div>    
</div>

<h1></h1>
<div>
    <div class="input-group">
        <label for="deal_price"><?php esc_html_e( 'Deal Price', 'couponxxl' ); ?> <span class="required">*</span></label>
        <input type="text" name="deal_price" id="deal_price" value="<?php echo esc_attr( $deal_price ) ?>" class="form-control" data-validation="required" data-error="<?php _e( 'Please input real deal price', 'couponxxl' ) ?>">
        <p class="description"><?php esc_html_e( 'Input real price of the deal without currency simbol. If this value is decimal than use . as decimal separator and max two decimal places.', 'couponxxl' ); ?></p>    
    </div>

    <div class="input-group">
        <label for="deal_sale_price"><?php esc_html_e( 'Deal Sale Price', 'couponxxl' ); ?> <span class="required">*</span></label>
        <input type="text" name="deal_sale_price" id="deal_sale_price" value="<?php echo esc_attr( $deal_sale_price ) ?>" class="form-control" data-validation="required" data-error="<?php _e( 'Please input deal sale price', 'couponxxl' ) ?>">
        <p class="description"><?php esc_html_e( 'Input sale price of the deal without currency simbol ( auto updated by the percentage change in the next field ). If this value is decimal than use . as decimal separator and max two decimal places.', 'couponxxl' ); ?></p>
    </div>

    <div class="input-group">
        <label for="deal_min_sales"><?php esc_html_e( 'Deal Minimal Sales', 'couponxxl' ); ?></label>
        <input type="text" name="deal_min_sales" id="deal_min_sales" value="<?php echo esc_attr( $deal_min_sales ) ?>" class="form-control">
        <p class="description"><?php esc_html_e( 'Input minimal number of sales which are needed in order for this deal to be activated.', 'couponxxl' ); ?></p>
    </div>

    <div class="input-group">
        <label for="deal_markers"><?php esc_html_e( 'Deal Marker Locations', 'couponxxl' ); ?> </label>
        <?php couponxxl_locations_checkboxes( $store->ID, array(), $edit ? get_the_ID() : 0 ); ?>
        <p class="description"><?php esc_html_e( 'Select in which store location is this deal available ', 'couponxxl' ); ?></p>
    </div>
    <div class="input-group">
        <label for="deal_type"><?php esc_html_e( 'Deal Type', 'couponxxl' ); ?> <span class="required">*</span></label>
        <select name="deal_type" id="deal_type" class="form-control" data-validation="required" data-error="<?php _e( 'Select deal type', 'couponxxl' ); ?>" data-shared="<?php echo couponxxl_get_option( 'deal_owner_price_shared' ) ?>" data-not_shared="<?php echo couponxxl_get_option( 'deal_owner_price_not_shared' ) ?>" data-unit="<?php echo couponxxl_get_option( 'unit' ) ?>" data-unit_position="<?php echo couponxxl_get_option( 'unit_position' ) ?>">
            <option value=""><?php esc_html_e( '- Select -', 'couponxxl' ) ?></option>
            <option value="shared" <?php echo  $deal_type == 'shared' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Website Offer', 'couponxxl' ) ?></option>
            <option value="not_shared" <?php echo  $deal_type == 'not_shared' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Store Offer', 'couponxxl' ) ?></option>
        </select>
        <div class="alert alert-info shared_info <?php echo $deal_type == 'shared' ? esc_attr( '' ) : esc_attr( 'hidden' ) ?>">
            <p><?php esc_html_e( 'Website Offer:', 'couponxxl' ) ?></p>
            <p><?php echo '<strong>'.esc_html__( 'This type of deal is usually picked when you want to sell discounted products or services.', 'couponxxl' ).'</strong>'; ?></p>
            <p><?php esc_html_e( 'Sell discounted products and services trough website.', 'couponxxl' ) ?></p>
            <p><?php echo esc_html__( 'Deal buyer will be charged ', 'couponxxl').'<strong><span class="charged"></span></strong>'.esc_html__(' for each purchase by site owner.', 'couponxxl' ) ?></p>
        </div>
        <div class="alert alert-info not_shared_info <?php echo $deal_type == 'not_shared' ? esc_attr( '' ) : esc_attr( 'hidden' ) ?>">
            <p><?php esc_html_e( 'Store Offer:', 'couponxxl' ) ?></p>
            <p><?php echo '<strong>'.esc_html__( 'This type of deal is usually picked when you want to sell codes/vouchers for discounted products.', 'couponxxl' ).'</strong>'; ?></p>
            <p><?php esc_html_e( 'Sell discounted products and services trough your store.', 'couponxxl' ) ?></p>
            <p><?php echo esc_html__( 'Deal buyer will be charged ', 'couponxxl').'<strong><span class="charged"></span></strong>'.esc_html__(' for each purchase by site owner.', 'couponxxl' ) ?></p>
        </div>    
        <p class="description"><?php esc_html_e( 'Choose deal type. Choose for more details', 'couponxxl' ); ?></p>
        <?php include( couponxxl_load_path( 'includes/profile-pages/vendor/terms.php' ) ); ?>
    </div>    
    <div class="ajax-response"></div>
</div>