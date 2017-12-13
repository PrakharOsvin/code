<?php

if( !class_exists( 'WP_Orders_Query' ) ){
class WP_Orders_Query extends WP_Query {
	public $args;
	function __construct( $args = array() ) {

		$args = array_merge( array(
			'post_type' => 'ord',
			'search' => '',
			'author' => get_current_user_id()
		), $args);

		$this->args = $args;

		if( !empty( $args['search'] ) ){
			add_filter( 'posts_join', array( $this, 'posts_join' ) );
			add_filter( 'posts_where', array( $this, 'posts_where' ));
		}

		parent::__construct( $args );

		if( !empty( $args['search'] ) ){
			remove_filter( 'posts_join', array( $this, 'posts_join' ) );
			remove_filter( 'posts_where', array( $this, 'posts_where' ));
		}
	}

	function posts_join( $sql ) {
		global $wpdb;
		return $sql . " INNER JOIN {$wpdb->prefix}order_items AS order_items ON $wpdb->posts.ID = order_items.order_id ";
	}

	function posts_where( $sql ) {
		global $wpdb;
		$sql .= $wpdb->prepare( " AND order_items.offer_title LIKE %s ", '%'.$this->args['search'].'%' );
		return $sql;
	}
}
}

?>