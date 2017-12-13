<div class="wrap">

	<h2><?php esc_html_e( 'Import / Export Custom Data From Theme ( Extended data of offers, orders, order items, vouchers, store markers )', 'couponxxl' ) ?> </h2>


	<p><?php esc_html_e( 'Click button bellow to get JSON export of your created fields which you can later import back using form bellow', 'couponxxl' ) ?></p>
	<?php $permalink = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
	<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'export' ), $permalink ) ) ?>" class="button"><?php esc_html_e( 'Export', 'couponxxl' ) ?></a>
	<?php
	if( isset( $_GET['action'] ) && $_GET['action'] == 'export' ){
		couponxxl_export_cd_values();
	}
	?>

	<br /><br />
	<hr />

	<p><?php esc_html_e( 'Paste JSON of your custom data and click on import button', 'couponxxl' ) ?></p>
	<?php couponxxl_import_cd_values() ?>
	<form method="post" action="<?php echo esc_url( add_query_arg( array( 'action' => 'cd_import' ), $permalink ) ) ?>">
		<textarea name="cxxl_custom_data" class="cd-import"></textarea>
		<input type="submit" class="button" value="<?php esc_attr_e( 'Import', 'couponxxl' ) ?>">
	</form>

</div>