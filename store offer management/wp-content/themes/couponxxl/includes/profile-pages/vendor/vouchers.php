<div class="white-block">
	<div class="white-block-content">
		<form method="post">
			<p><?php esc_html_e( 'In order to check for voucher input it into the field bellow and click on Verify', 'couponxxl' ) ?></p>
	        <div class="input-group">
	            <label for="voucher_code"><?php esc_html_e( 'Voucher Code', 'couponxxl' ); ?> <span class="required">*</span></label>
	            <input type="text" name="voucher_code" id="voucher_code" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input voucher code', 'couponxxl' ); ?>" value="">
	            <i class="pline-magnifier"></i>
	        </div>
			<a href="javascript:;" class="btn submit-form ajax-return">
				<?php esc_html_e( 'Verify', 'couponxxl' ); ?>
			</a>
			<input type="hidden" name="action" value="verify_voucher">
			<input type="hidden" name="vendor_id" value="<?php echo esc_attr( $vendor_id ) ?>">
			<div class="ajax-response"></div>
		</form>
	</div>
</div>