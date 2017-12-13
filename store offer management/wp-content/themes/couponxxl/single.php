<?php
/*==================
 SINGLE BLOG POST
==================*/

get_header();
the_post();
get_template_part( 'includes/title' );
?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-<?php echo is_active_sidebar( 'sidebar-blog' ) ? '9' : '12' ?>">
                <div class="white-block">
                    <?php
                    if( has_post_thumbnail() ){
                        ?>
                        <div class="white-block-media">
                            <?php the_post_thumbnail( 'post-thumbnail' ) ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="white-block-content blog-item-content clearfix">

                        <div class="left-single">
                            <div class="blog-title">
                                <div class="month-day">
                                    <?php the_time( 'M Y' ) ?>
                                </div>
                                <div class="day">
                                    <?php the_time( 'd' ) ?>
                                </div>
                            </div>
                        </div>

                        <div class="right-single">
                            <h1><?php the_title(); ?></h1>
                            <p class="blog-meta">
                                <?php esc_html_e( 'by ', 'couponxxl' ); the_author_meta( 'display_name' ); esc_html_e( ' in ', 'couponxxl' ); echo couponxxl_categories_list(); esc_html_e( ' category', 'couponxxl' )?>
                            </p>
                            
                            <?php the_content(); ?>


                            <?php
                            $tags = couponxxl_tags_list();
                            if( !empty( $tags ) ){
                                ?>
                                <div class="blog-tags">
                                    <i class="pline-tag"></i>
                                    <?php echo  $tags; ?>                        
                                </div>
                                <?php
                            }
                            ?>                            
                        </div>


                    </div>
                </div>

                <?php comments_template( '', true ); ?>

            </div>

            <?php  get_sidebar(); ?>

        </div>
    </div>
</section>

<?php
get_footer();
?>