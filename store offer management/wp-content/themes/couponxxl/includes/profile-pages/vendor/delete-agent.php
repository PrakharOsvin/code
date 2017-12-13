<?php
if( isset( $_GET['agent_id'] ) ) {
    $agent = get_user_by( 'id', $_GET['agent_id'] );
    $cxxl_vendor_agent_parent = get_user_meta( $_GET['agent_id'], 'cxxl_vendor_agent_parent', true );
}
if( !empty( $agent ) && get_current_user_id() == $cxxl_vendor_agent_parent ){
	wp_delete_user( $_GET['agent_id'] );
	wp_redirect( add_query_arg( array( 'subpage' => 'agents' ), couponxxl_get_permalink_by_tpl( 'page-tpl_profile' ) ) );
}
?>