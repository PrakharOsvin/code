<?php
if( isset( $_GET['agent_id'] ) ) {
    $agent = get_user_by( 'id', $_GET['agent_id'] );
    $cxxl_vendor_agent_parent = get_user_meta( $_GET['agent_id'], 'cxxl_vendor_agent_parent', true );
}
if( !empty( $agent ) && get_current_user_id() == $cxxl_vendor_agent_parent ):
$agent = $agent->data;
?>
<div class="white-block edit-agent">
	<div class="white-block-content">
		<form method="post">
			<p><?php esc_html_e( 'Populate fields bellow and click on Edit Agent button. Once you do that email with information will be sent to the agent\'s mail.', 'couponxxl' ) ?></p>
			<div class="row">
				<div class="col-sm-6">
                    <div class="input-group">
                        <label for="password"><?php esc_html_e( 'Password', 'couponxxl' ); ?></label>
                        <input type="password" name="password" id="password" class="form-control" >
                        <i class="pline-lock-locked"></i>
                    </div>
                    <div class="input-group">
                        <label for="agent_email"><?php esc_html_e( 'Vendor Agent Email', 'couponxxl' ); ?> <span class="required">*</span></label>
                        <input type="text" name="agent_email" id="agent_email" class="form-control" data-validation="required|email" data-error="<?php _e( 'Please input agent email', 'couponxxl' ); ?>" value="<?php echo esc_attr( $agent->user_email ); ?>">
                        <i class="pline-envelope"></i>
                    </div>
				</div>
				<div class="col-sm-6">
                    <div class="input-group">
                        <label for="repeat_password"><?php esc_html_e( 'Repeat Password', 'couponxxl' ); ?></label>
                        <input type="password" name="repeat_password" id="repeat_password" class="form-control">
                        <i class="pline-lock-locked"></i>
                    </div>
                    <input type="hidden" name="action" value="edit_agent">
                    <input type="hidden" name="agent_id" value="<?php echo esc_attr( $_GET['agent_id'] ) ?>">
                    <a href="javascript:;" class="btn submit-form ajax-return"><?php esc_html_e( 'Edit Agent', 'couponxxl' ); ?></a>
                    <div class="ajax-response"></div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php endif; ?>