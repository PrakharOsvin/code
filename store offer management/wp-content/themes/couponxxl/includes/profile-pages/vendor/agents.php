<?php
$agents = new WP_User_Query(array(
	'posts_per_page' => '-1',
	'meta_query' => array(
		array(
			'key' => 'cxxl_vendor_agent_parent',
			'value' => get_current_user_id(),
			'compare' => '='
		)
	)
));

$agents = $agents->get_results();
?>
<div class="white-block">
    <div class="white-block-content">
		<p class="pretable-loading"><?php esc_html_e( 'Loading...', 'couponxxl' ) ?></p>
		<div class="bt-table">
			<div id="toolbar" class="btn-group">
			    <a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'new-agent' ), $profile_link ) ) ?>" class="btn btn-default">
			        <i class="fa fa-plus"></i> <?php esc_html_e( 'Add Agent', 'couponxxl' ) ?>
			    </a>
			</div>
			<table data-toggle="table" data-search="true" data-classes="table" data-searchText="<?php _e( 'Search', 'couponxxl' ) ?>">
				<thead>
				    <tr>
				        <th data-field="username" data-sortable="true">
				        	<?php esc_html_e( 'Username', 'couponxxl' ); ?>
				        </th>
				        <th data-field="email" data-sortable="true">
				            <?php esc_html_e( 'Email', 'couponxxl' ); ?>
				        </th>
				        <th data-field="action" data-sortable="true">
				            <?php esc_html_e( 'Action', 'couponxxl' ); ?>
				        </th>
				    </tr>
				</thead>
				<?php
				if ( !empty( $agents ) ) {
					foreach( $agents as $agent ){
						?>
					    <tr>
							<td class="username">
								<?php echo  $agent->user_login ?>
							</td>
							<td class="email">
								<?php echo  $agent->user_email ?>
							</td>
							<td class="action">
								<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'edit-agent', 'agent_id' => $agent->ID ), $profile_link ) ) ?>" title="<?php _e( 'Edit agent', 'couponxxl' ) ?>">
									<i class="fa fa-cog"></i>
								</a>
								<a href="<?php echo esc_url( add_query_arg( array( 'subpage' => 'delete-agent', 'agent_id' => $agent->ID ), $profile_link ) ) ?>" title="<?php _e( 'Delete agent', 'couponxxl' ) ?>" class="confirm-action" data-confirm="<?php _e( 'Are you sure you want to delete this agent?', 'couponxxl' ) ?>">
									<i class="fa fa-times"></i>
								</a>
							</td>
					    </tr>
						<?php
					}
					?>
					</tbody>
					<?php		
				}
				wp_reset_postdata();
				?>
			</table>
		</div>
	</div>
</div>