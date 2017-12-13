<?php 
$terms = couponxxl_get_option( 'terms' ); 
if( !empty( $terms ) ):
?>
    <div class="input-group">
        <label for="offer_expire"><?php esc_html_e( 'Terms & Condition', 'couponxxl' ); ?> <span class="required">*</span></label>
        <div class="terms_conditions">
        	<?php echo apply_filters( 'the_content', $terms ); ?>
        </div>
        <div class="checkbox checkbox-inline">
        	<input type="checkbox" name="terms" id="terms" data-validation="checked" data-error="<?php _e( 'You must read and accept terms in order to be able to submit your offer', 'couponxxl' ); ?>">
        	<label for="terms"><?php esc_html_e( 'I have read and agreed with the terms and conditions.', 'couponxxl' ); ?></label>
        </div>
    </div>
<?php endif; ?>