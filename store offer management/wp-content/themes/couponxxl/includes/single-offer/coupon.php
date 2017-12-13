<section>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
                <?php
                if( has_post_thumbnail() ){
                    ?>
                    <div class="white-block">
                        <div class="white-block-media">
                            <?php the_post_thumbnail( 'post-thumbnail' ); ?>
                        </div>
                    </div>
                    <?php
                }
                ?>

				<div class="white-block">
					<div class="white-block-content">
	            		<div class="clearfix">
			                <?php the_content(); ?>
	            		</div>

	                    <?php
	                    $tags = couponxxl_offer_tags();
	                    if( !empty( $tags ) ):
	                    ?>
	                        <div class="tags-list">
		                        <i class="pline-tag"></i>
		                        <?php echo  $tags; ?>
	                        </div>
	                    <?php
	                    endif;
	                    ?>

	            	</div>
	            </div>

                <?php comments_template( '', true ); ?>

			</div>
			<div class="col-sm-4">
                
                <div class="widget white-block">
                	<div class="white-block-content">

                        <div class="coupon-button">
                            <?php echo couponxxl_coupon_button(); ?>
                        </div>

                        <h5><?php esc_html_e( 'Share', 'couponxxl' ); ?></h5>
                        <ul class="list-unstyled list-inline store-social-networks">
                            <li>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ) ?>" class="share" target="_blank">
                                    <i class="fa fa-facebook-square"></i>
                                </a>
                            </li>
                            <li>
                                <a href="http://twitter.com/intent/tweet?source=<?php echo esc_attr( get_bloginfo('name') ) ?>&amp;text=<?php echo urlencode( get_permalink() ) ?>" class="share" target="_blank">
                                    <i class="fa fa-twitter-square"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://plus.google.com/share?url=<?php echo urlencode( get_permalink() ) ?>" class="share" target="_blank">
                                    <i class="fa fa-google-plus-square"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" class="share mail-share" data-body="<?php the_permalink(); ?>" data-subject="<?php the_title(); ?>">
                                    <i class="fa fa-envelope-o-square"></i>
                                </a>
                            </li>
                        </ul>                    

                        <?php 
                        if( $offer_meta->offer_expire !== '99999999999' && $offer_meta->offer_expire > current_time( 'timestamp' ) ){
                            echo couponxxl_offer_countdown( $offer_meta->offer_expire );
                        }
                        ?>

                        <?php
                        $offer_store = get_post_meta( get_the_ID(), 'offer_store', true );
                        ?>
                        <div class="shop clearfix">
                        	<div class="pull-left">
                            	<a href="<?php echo get_permalink( $offer_store ) ?>">
	                                <?php couponxxl_store_logo( $offer_store ); ?>
                            	</a>
                            </div>
                           <div class="pull-right">
                            	<a href="<?php echo get_permalink( $offer_store ) ?>">
                                    <?php esc_html_e( 'Visit ', 'couponxxl' ) ?>
	                                <?php echo get_the_title( $offer_store ); ?>
                                    <?php esc_html_e( ' store', 'couponxxl' ) ?>
                            	</a>
                           </div>
                        </div>

                	</div>
                </div>
                <?php
                $terms = get_the_terms( get_the_ID(), 'offer_cat' );
                $deal_show_similar = couponxxl_get_option( 'deal_show_similar' );
                if( !empty( $terms ) && $deal_show_similar == 'yes' ):
                    $similar_slugs = array();
                    foreach( $terms as $term ){
                        $similar_slugs[] = $term->slug;
                    }
                    $similar = new WP_Offers_Query(array(
                        'post_status' => 'publish',
                        'posts_per_page' => couponxxl_get_option( 'similar_offers' ),
                        'post__not_in' => array( get_the_ID() ),
                        'offer_type' => 'coupon',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'offer_cat',
                                'field' => 'slug',
                                'terms' => $similar_slugs
                            )
                        )
                    ));  
                    if( $similar->have_posts() ){              
                    ?>  
                    <div class="widget white-block widget_couponxxl_staff_picks">
                        <div class="white-block-content">

                        <div class="widget-title">
                            <h5><?php esc_html_e( 'Similar Offers', 'couponxxl' ) ?></h5>
                        </div>

                        <?php
                            while( $similar->have_posts() ){
                                $similar->the_post();
                                ?>
                                <div class="staff-pick-item">
                                    <a href="<?php the_permalink() ?>" class="coupon-list-wrap">
                                        <?php
                                        $store_id = get_post_meta( get_the_ID(), 'offer_store', true );
                                        couponxxl_store_logo( $store_id );
                                        ?>
                                    </a>
                                    <div class="staff-pick-details">
                                        <h6>
                                            <a href="<?php the_permalink() ?>">
                                                <?php
                                                $title = get_the_title();
                                                if( strlen( $title ) > 30 ){
                                                    $title = substr( $title, 0, 27 ).'...';
                                                }
                                                echo  $title;
                                                ?>
                                            </a>
                                        </h6>
                                    </div>
                                </div>
                                <?php
                            }
                            wp_reset_postdata();
                        ?>
                    </div>
                    <?php
                    }
                    ?>                    

                <?php endif; ?>
			</div>
		</div>
	</div>
</section>