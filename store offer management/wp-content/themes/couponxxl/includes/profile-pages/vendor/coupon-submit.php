<div class="input-group">
    <label for="coupon_excerpt"><?php esc_html_e( 'Coupon Excerpt', 'couponxxl' ); ?></label>
    <textarea name="coupon_excerpt" id="coupon_excerpt" class="form-control"><?php echo  $coupon_excerpt ?></textarea>
    <p class="description"><?php esc_html_e( 'Input coupon excerpt.', 'couponxxl' ); ?></p>
</div>


<div class="input-group">
    <label for="coupon_type"><?php esc_html_e( 'Coupon Type', 'couponxxl' ); ?> <span class="required">*</span></label>
    <select name="coupon_type" id="coupon_type" class="form-control" data-validation="required" data-error="<?php _e( 'Select coupon type', 'couponxxl' ); ?>">
    	<option value=""><?php esc_html_e( '- Select -', 'couponxxl' ) ?></option>
    	<option value="code" <?php echo  $coupon_type == 'code' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Code', 'couponxxl' ) ?></option>
    	<option value="sale" <?php echo  $coupon_type == 'sale' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Sale', 'couponxxl' ) ?></option>
    	<option value="printable" <?php echo  $coupon_type == 'printable' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Printable', 'couponxxl' ) ?></option>
    </select>
    <p class="description"><?php esc_html_e( 'Choose type of the coupon.', 'couponxxl' ); ?></p>
</div>

<div class="input-group group_code_type group_code <?php echo $coupon_type == 'code' ? esc_attr( '' ) : esc_attr( 'hidden' ); ?>">
    <label for="coupon_code"><?php esc_html_e( 'Coupon Code Value', 'couponxxl' ); ?> <span class="required">*</span></label>
    <input type="text" name="coupon_code" id="coupon_code" value="<?php echo esc_attr( $coupon_code ) ?>" class="form-control" data-validation="required" data-error="<?php _e( 'Please input coupon code for the coupon', 'couponxxl' ); ?>">
    <p class="description"><?php esc_html_e( 'Input coupon code.', 'couponxxl' ); ?></p>
</div>

<div class="input-group group_code_type group_sale <?php echo $coupon_type == 'sale' ? esc_attr( '' ) : esc_attr( 'hidden' ); ?>">
    <label for="coupon_sale"><?php esc_html_e( 'Coupon Sale Link', 'couponxxl' ); ?> <span class="required">*</span></label>
    <input type="text" name="coupon_sale" id="coupon_sale" value="<?php echo esc_attr( $coupon_sale ) ?>" class="form-control" data-validation="required" data-error="<?php _e( 'Please input coupon sale link for the coupon', 'couponxxl' ); ?>">
    <p class="description"><?php esc_html_e( 'Input sale link.', 'couponxxl' ); ?></p>
</div>

<div class="input-group group_code_type group_printable <?php echo $coupon_type == 'printable' ? esc_attr( '' ) : esc_attr( 'hidden' ); ?>">
    <label for="coupon_image"><?php esc_html_e( 'Printable Coupon Image', 'couponxxl' ); ?> <span class="required">*</span></label>
    <input type="hidden" name="coupon_image" id="coupon_image" value="<?php echo esc_attr( $coupon_image ) ?>" data-validation="required" data-error="<?php _e( 'Please select image for the printable coupon', 'couponxxl' ); ?>">
    <div class="upload-image-wrap coupon-image-wrap">
        <?php
        if( !empty( $coupon_image ) ){
            echo wp_get_attachment_image( $coupon_image, 'thumbnail' );
        }?>
    </div>
    <a href="javascript:;" class="image-upload coupon-image"><?php esc_html_e( 'Select coupon image', 'couponxxl' ) ?></a>    
    <p class="description"><?php esc_html_e( 'Upload printable coupon image.', 'couponxxl' ); ?></p>
</div>

<div class="input-group">
    <label for="coupon_link"><?php esc_html_e( 'Affiliate Link', 'couponxxl' ); ?></label>
    <input type="text" name="coupon_link" id="coupon_link" value="<?php echo esc_attr( $coupon_link ); ?>" class="form-control">
    <p class="description"><?php esc_html_e( 'Input affiliate link which will be opened once the coupon is clicked.', 'couponxxl' ); ?></p>
</div>

<div class="ajax-response"></div>

<?php include( couponxxl_load_path( 'includes/profile-pages/vendor/terms.php' ) ); ?>