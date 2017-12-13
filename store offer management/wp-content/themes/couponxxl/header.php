<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta name="msvalidate.01" content="D6FF511893E061924F653D983EF20703" />
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header>
    <?php
    include( couponxxl_load_path( 'includes/headers/header1.php' ) );
    ?>
</header>
<?php
if( is_front_page() || isset($_GET['home_div']) ){
    $home_divider_section = couponxxl_get_option( 'home_divider_section' );
    if( isset( $_GET['home_div'] ) ){
        $home_divider_section = $_GET['home_div'];
    }
    switch( $home_divider_section ){
    	case 'slider' : include( couponxxl_load_path( 'includes/home/slider.php' ) ); break;
    	case 'map' : include( couponxxl_load_path( 'includes/home/map.php' ) ); break;
    	case 'search' : include( couponxxl_load_path( 'includes/home/search.php' ) ); break;
    }
}

/* Activate hook for the Skrill and iDEAL status return */
if( !empty( $_GET['gateway'] ) && !empty( $_GET['status'] ) ){
    do_action( 'couponxxl_process_verify', $_GET['gateway'] );
}
?>