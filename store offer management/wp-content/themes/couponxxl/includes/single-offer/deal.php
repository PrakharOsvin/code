<section>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
                <?php
                $deal_images = couponxxl_smeta_images( 'deal_images', get_the_ID(), array() ); 
                if( !empty( $deal_images ) || has_post_thumbnail() ){
                    ?>
                    <div class="white-block-media">
                        <ul class="list-unstyled deal-img-slider">
                            <?php
                            $thumbs = '';
                            if( has_post_thumbnail() ){
                                echo '<li>'.get_the_post_thumbnail( get_the_ID(), 'post-thumbnail' ).'</li>';
                            }
                            else{
                                echo '<li><img src="'.esc_url( get_template_directory_uri().'/images/slider-featured-placeholder.png' ).'" alt="" /></li>';
                            }

                            if( !empty( $deal_images ) ){
                                $counter = 0;
                                if( has_post_thumbnail() ){
                                    $thumbs .= '<div><a href="#" data-slide="'.esc_attr( $counter ).'">'.get_the_post_thumbnail( get_the_ID(), 'couponxxl-slider-thumb' ).'</a></div>';
                                    $counter++;
                                }

                                foreach( $deal_images as $image_id ){
                                    echo '<li>'.wp_get_attachment_image( $image_id, 'post-thumbnail' ).'</li>';
                                    $thumbs .= '<div><a href="#" data-slide="'.esc_attr( $counter ).'">'.wp_get_attachment_image( $image_id, 'couponxxl-slider-thumb' ).'</a></div>';
                                }
                            }
                            ?>
                        </ul>
                        <?php if( !empty( $thumbs ) ): ?>
                            <div class="slider-thumbs">
                                <?php echo  $thumbs; ?>
                            </div>                        
                        <?php endif; ?>
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
				<?php
                    $offer_availability =  couponxxl_check_offer();
                    $deal_vouchers = couponxxl_deal_voucher_count();
                    $deal_items = get_post_meta( get_the_ID(), 'deal_items', true );
                    $deal_link = get_post_meta( get_the_ID(), 'deal_link', true );
                ?>
                <div class="widget white-block">
                	<div class="white-block-content">

                		<div class="quantity clearfix">
                			<div class="pull-left">
                				<?php
                				if( !empty( $offer_meta ) ){
                					if( $offer_meta->offer_expire == '99999999999' ){
                						esc_html_e( 'Limited quantity!', 'couponxxl' );
                					}
                					else{
                						esc_html_e( 'Limited time!', 'couponxxl' );
                					}
                				}
                				?>
                			</div>
                            <?php
                            $deal_show_bought = couponxxl_get_option( 'deal_show_bought' );
                            if( $deal_show_bought == 'yes' ):
                            ?>
                    			<div class="pull-right">
                    				<i class="pline-cart"></i>
                    				<?php 
                                    $deal_sales = get_post_meta( get_the_ID(), 'deal_sales', true );
                                    if( empty( $deal_sales ) ){
                                        $deal_sales = 0;
                                    }
                					echo  $deal_sales;
                					esc_html_e( ' bought', 'couponxxl' );
                    				?>
                    			</div>
                            <?php endif; ?>
                		</div>

                		<div class="deal-single-price">
                			<?php echo couponxxl_get_deal_html_price(); ?>
                		</div>

                		<div class="deal-single-add-to-cart">
                			<?php 
                            $deal_link = get_post_meta( get_the_ID(), 'deal_link', true );
                            if( empty( $deal_link ) ){
                                couponxxl_add_to_cart( esc_html__( 'Add To Cart', 'couponxxl' ) );
                            }
                            else{
                                ?>
                                <a href="<?php echo esc_url( add_query_arg( array( 'rd' => get_the_ID() ) ) ) ?>" class="btn" rel="nofollow"><?php esc_html_e( 'Visit Deal', 'couponxxl' ) ?></a>
                                <?php
                            }?>
                		</div>

                        <?php
                        $deal_min_sales = get_post_meta( get_the_ID(), 'deal_min_sales', true );
                        if( !empty( $deal_min_sales ) ){
                            if( $deal_sales < $deal_min_sales ){
                                ?>
                                <div class="groupon-info text-center">
                                    <h5>
                                        <a href="#groupon_explanation" data-toggle="modal">
                                            <?php echo ( $deal_min_sales - $deal_sales ).' '.esc_html__( 'more', 'couponxxl' ).' '.( $deal_min_sales == 1 ? esc_html__( 'sale', 'couponxxl' ) : esc_html__('sales', 'couponxxl') ).' '.esc_html__( 'required', 'couponxxl' ); ?>
                                        </a>
                                    </h5>
                                </div>
                                <?php
                            }
                        }
                        ?>                        

                		<ul class="deal-single-value">
                			<?php
                			$deal_price = get_post_meta( get_the_ID(), 'deal_price', true );
                			$deal_sale_price = get_post_meta( get_the_ID(), 'deal_sale_price', true );
                			?>
							<li>
                                <p><?php esc_html_e( 'Value', 'couponxxl' ) ?></p>
								<?php echo couponxxl_format_price_number( $deal_price ) ?>
							</li>
							<li>
                                <p><?php esc_html_e( 'Discount', 'couponxxl' ) ?></p>
								<?php echo round( 100 - ( $deal_sale_price / $deal_price ) * 100 ).'%'; ?>
							</li>
							<li>
                                <p><?php esc_html_e( 'You save', 'couponxxl' ) ?></p>
								<?php echo couponxxl_format_price_number( $deal_price - $deal_sale_price ) ?>
							</li>
                		</ul>

                		<?php 
                        if( $offer_meta->offer_expire !== '99999999999' && $offer_meta->offer_expire > current_time( 'timestamp' ) ){
                            echo couponxxl_offer_countdown( $offer_meta->offer_expire );
                        }
                        ?>

                        <?php
                        $excerpt = get_the_excerpt();
                        if( !empty( $excerpt ) ):
                        ?>
                            <hr />
                            <div class="deal-in-short">
                                <h5><?php esc_html_e( 'In Short', 'couponxxl' ); ?></h5>
                                <p><?php echo  $excerpt; ?></p>
                            </div>
                        <?php endif; ?>        


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
                        $deal_markers = get_post_meta( get_the_ID(), 'deal_markers', true );
                        if( !empty( $deal_markers ) ):
                        ?>
                            <div id="map-markers"></div>
                            <div class="markers hidden deal-map">
                                <?php couponxxl_deal_single_markers( $deal_markers ) ?>
                            </div>
                        <?php endif; ?>

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
                        'offer_type' => 'deal',
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
                                    <a href="<?php the_permalink() ?>">
                                        <?php the_post_thumbnail( 'couponxxl-staff-box' )?>
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
                                        <?php echo couponxxl_get_deal_html_price(); ?>
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
    </div>
</section>
<?php
    if( has_post_thumbnail() ){
        $image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
    }
    if( !empty( $offer_expire ) && $offer_expire != '99999999999' ){
        $priceValidUntil = date_i18n( 'Y-m-d', $offer_expire );
    }                    
?>
<script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Product",
  "name": "<?php the_title() ?>",
  "image": "<?php echo !empty( $image_data[0] ) ? $image_data[0] : '' ?>",
  "description": "<?php echo  $excerpt; ?>",
  "offers": {
    "@type": "Offer",
    "priceCurrency": "<?php echo esc_attr( couponxxl_get_option( 'main_unit_abbr' ) ) ?>",
    "price": "<?php echo  $deal_sale_price ?>",
    "priceValidUntil": "<?php echo !empty( $priceValidUntil ) ? $priceValidUntil : '' ?>",
    "itemCondition": "http://schema.org/UsedCondition",
    "availability": "http://schema.org/InStock",
    "seller": {
      "@type": "Organization",
      "name": "<?php echo get_the_title( $offer_store ); ?>"
    }
  }
}
</script> 