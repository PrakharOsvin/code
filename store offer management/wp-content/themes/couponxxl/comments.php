<?php
	/**********************************************************************
	***********************************************************************
	PROPERSHOP COMMENTS
	**********************************************************************/
	
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ( 'Please do not load this page directly. Thanks!' );
	if ( post_password_required() ) {
		return;
	}
?>
<?php if ( comments_open() ) :?>


    <!-- row -->
    <div class="comments">
    	<?php if( have_comments() ): ?>	    
			<h3> <?php esc_html_e( 'Comments', 'couponxxl' )?></h3>
	    	<div class="white-block">

		        <div class="white-block-content">
					
						
						<?php 
						wp_list_comments( array(
							'type' => 'comment',
							'callback' => 'couponxxl_comments',
							'end-callback' => 'couponxxl_end_comments',
							'style' => 'div'
						)); 
						?>

		                <!-- pagination -->
						<?php
							$comment_links = paginate_comments_links( 
								array(
									'echo' => false,
									'type' => 'array',
									'prev_text' => '<i class="fa fa-arrow-left"></i>',
									'next_text' => '<i class="fa fa-arrow-right"></i>'
								) 
							);
							if( !empty( $comment_links ) ):
						?>					
			                <div class="custom-pagination">
			                    <ul class="pagination">
									<?php echo  couponxxl_format_pagination( $comment_links ); ?>
								</ul>
							</div>
						<?php endif; ?>
		                <!-- .pagination -->

		        </div>    		
	    	</div>
    	<?php endif; ?>
    	
    	<h3> <?php esc_html_e( 'Leave Comment', 'couponxxl' )?></h3>

    	<div class="white-block">
    		<div class="white-block-content">
				<?php
					$comments_args = array(
						'label_submit'	=>	esc_html__( 'Leave Comment', 'couponxxl' ),
						'title_reply'	=>	'',
						'fields'		=>	apply_filters( 'comment_form_default_fields', array(
												'author' => '<div class="input-group">
																<label for="author">'.esc_html__( 'Name', 'couponxxl' ).'<span class="required">*</span></label>
                          										<input type="text" class="form-control" name="author" id="author">
                          										<i class="pline-user"></i>
                        									</div>',
												'email'	 => '<div class="input-group">
																<label for="comment-email">'.esc_html__( 'Email', 'couponxxl' ).'<span class="required">*</span></label>
                          										<input type="text" class="form-control" name="email" id="comment-email">
                          										<i class="pline-envelope"></i>
                        									</div>'
											)),
						'comment_field'	=>	'<div class="input-group">
												<label for="comment">'.esc_html__( 'Comment', 'couponxxl' ).'<span class="required">*</span></label>
												<textarea class="form-control" name="comment" id="comment"></textarea>
												<i class="pline-message-cloud"></i>
        									</div>',
						'cancel_reply_link' => esc_html__( 'or cancel reply', 'couponxxl' ),
						'comment_notes_after' => '',
						'comment_notes_before' => ''
					);
					comment_form( $comments_args );	
				?>    			
    		</div>
    	</div>

    </div>
    <!-- .row -->

<?php endif; ?>