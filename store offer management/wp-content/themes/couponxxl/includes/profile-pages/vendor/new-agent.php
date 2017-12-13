<div class="white-block">
	<div class="white-block-content">
		<form method="post">
			<p><?php esc_html_e( 'Populate fields bellow and click on Add Agent button. Once you are done email with notification will be sent to the agent.', 'couponxxl' ) ?></p>
			<div class="row">
				<div class="col-sm-6">
                    <div class="input-group">
                        <label for="agent_username"><?php esc_html_e( 'Vendor Agent Username', 'couponxxl' ); ?> <span class="required">*</span></label>
                        <input type="text" name="agent_username" id="agent_username" class="form-control" data-validation="required" data-error="<?php _e( 'Please input agent name', 'couponxxl' ); ?>">
                        <i class="pline-user"></i>
                    </div>
                    <div class="input-group">
                        <label for="password"><?php esc_html_e( 'Password', 'couponxxl' ); ?> <span class="required">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" data-validation="required|match" data-match="repeat_password" data-error="<?php _e( 'Passwords do not match', 'couponxxl' ); ?>">
                        <i class="pline-lock-locked"></i>
                    </div>
                    <input type="hidden" name="action" value="add_agent">
                    <a href="javascript:;" class="btn submit-form ajax-return"><?php esc_html_e( 'Add Agent', 'couponxxl' ); ?></a>
                    <div class="ajax-response"></div>
				</div>
				<div class="col-sm-6">
                    <div class="input-group">
                        <label for="agent_email"><?php esc_html_e( 'Vendor Agent Email', 'couponxxl' ); ?> <span class="required">*</span></label>
                        <input type="text" name="agent_email" id="agent_email" class="form-control" data-validation="required|email" data-error="<?php _e( 'Please input agent email', 'couponxxl' ); ?>">
                        <i class="pline-envelope"></i>
                    </div>
                    <div class="input-group">
                        <label for="repeat_password"><?php esc_html_e( 'Repeat Password', 'couponxxl' ); ?> <span class="required">*</span></label>
                        <input type="password" name="repeat_password" id="repeat_password" class="form-control" data-validation="required|match" data-match="password" data-error="<?php _e( 'Passwords do not match', 'couponxxl' ); ?>">
                        <i class="pline-lock-locked"></i>
                    </div>
				</div>
			</div>
		</form>
	</div>
</div>