<?php
$locations = get_nav_menu_locations();
$has_nav = isset( $locations[ 'top-navigation' ] ) ? true : false;
$enable_sticky = couponxxl_get_option( 'enable_sticky' );
?>
<div class="navigation" data-enable_sticky="<?php echo $enable_sticky == 'yes' ? esc_attr( 'yes' ) : esc_attr( 'no' ) ?>">
    <div class="container">
        <div class="navbar navbar-default" role="navigation">

            <a href="javascript:;" class="menu-trigger">
                <i class="fa fa-bars"></i>
            </a>

            <div class="small-device-wrapper">

                <div class="small-device-action">
                    <?php  include( couponxxl_load_path( 'includes/headers/account.php' ) )?>
                </div>

                <h5 class="small-device-title"><?php  esc_html_e( 'Navigation', 'couponxxl' )?></h5>

                <?php
                if ( $has_nav ) {
                    wp_nav_menu( array(
                        'theme_location'    => 'top-navigation',
                        'menu_class'        => 'nav navbar-nav clearfix',
                        'container'         => false,
                        'echo'              => true,
                        'items_wrap'        => '<ul class="%2$s">%3$s</ul>',
                        'depth'             => 10,
                        'walker'            => new couponxxl_walker,
                    ) );
                }
                ?>
            </div>
        </div>
    </div>    
</div>