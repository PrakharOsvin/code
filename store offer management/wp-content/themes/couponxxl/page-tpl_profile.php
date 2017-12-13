<?php
/*
    Template Name: My Profile
*/

if( !is_user_logged_in() ){
    wp_redirect( home_url() );
}

get_header();
the_post();
get_template_part( 'includes/title' );
global $current_user;
$current_user = wp_get_current_user();

$profile_link = couponxxl_get_permalink_by_tpl( 'page-tpl_profile' );
?>
<section>
    <div class="container">
        <?php
        $vendor_id = couponxxl_get_vendor_id();
        switch( couponxxl_get_account_type() ){
            case 'vendor' : include( couponxxl_load_path( 'includes/profile-pages/vendor.php' ) ); break;
            case 'agent' : include( couponxxl_load_path( 'includes/profile-pages/vendor_agent.php' ) ); break;
            default : include( couponxxl_load_path( 'includes/profile-pages/buyer.php' ) ); break;
        }
        ?>
    </div>
</section>
<?php
get_footer();
?>