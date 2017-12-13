<?php
/*
    Template Name: Cart
*/
get_header();
the_post();
get_template_part( 'includes/title' );

global $couponxxl_cart;
?>
<section>
    <div class="container">
    	<div class="ajax-cart-wrap">
        	<?php echo  $couponxxl_cart->cart(); ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>