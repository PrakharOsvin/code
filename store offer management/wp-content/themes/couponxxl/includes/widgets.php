<?php
/******************************************************** 
COUPONXXL Categories Widget
********************************************************/
class Couponxxl_Categories extends WP_Widget {
	public function __construct() {
		parent::__construct('couponxxl_categories', esc_html__('CouponXXL Categories','couponxxl'), array('description' =>esc_html__('CouponXXL Categories Widget','couponxxl') ));
	}
	public function widget( $args, $instance ) {
		extract($args);
		global $couponxxl_slugs;
		$title = empty( $instance['title'] ) ? '' : $instance['title'];
		$icons = empty( $instance['icons'] ) ? '' : $instance['icons'];
		$categories = empty( $instance['categories'] ) ? array() : $instance['categories'];

		echo  $before_widget.$before_title.$title.$after_title;
			$args = array(
				'hide_empty' => false
			);
			if( empty( $categories ) ){
				$args['parent'] = 0;
				$offer_cats = get_terms( 'offer_cat', $args );
			}
			else{
				foreach( $categories as $category ){
					$offer_cats[] = get_term_by( 'slug', $category, 'offer_cat' );
				}

				usort( $offer_cats, "couponxxl_organized_sort_name_asc" );
			}
			?>

			<?php
			if( !empty( $offer_cats ) ){
				?>
				<ul class="list-unstyled offer-cat-filter">
					<?php
					foreach( $offer_cats as $offer_cat_item ){
						?>
						<li>
							<a href="<?php echo esc_url( couponxxl_append_query_string( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ), array( $couponxxl_slugs['offer_cat'] => $offer_cat_item->slug ) ) ) ?>">
								<?php echo  $offer_cat_item->name;  ?>
							</a>
							<div class="count">
								(<?php echo couponxxl_category_count( $offer_cat_item->term_id ); ?>)
							</div>
							<?php
								$term_meta = get_option( "taxonomy_".$offer_cat_item->term_id );
								echo !empty( $term_meta['promo_text'] ) ? '<span class="label">'.$term_meta['promo_text'].'</span>' : '';
							?>
						</li>
						<?php
					}
					?>
					<li>
						<a href="<?php echo esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_all_categories' ) ); ?>">
							<?php esc_html_e( 'View All', 'couponxxl' ); ?>
						</a>
					</li>
				</ul>
				<?php
			}
		echo  $after_widget;
	}
 	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'categories' => array() ) );
		
		$title = esc_attr( $instance['title'] );
		$categories = (array)$instance['categories'];

		echo '<p><label for="'.esc_attr($this->get_field_id('title')).'">'.esc_html__( 'Title:', 'couponxxl' ).'</label>';
		echo '<input type="text" class="widefat" id="'.esc_attr($this->get_field_id('title')).'"  name="'.esc_attr($this->get_field_name('title')).'" value="'.esc_attr( $title ).'"></p>';

		echo '<p><label for="'.esc_attr($this->get_field_id('categories')).'">'.esc_html__( 'Categories:', 'couponxxl' ).'</label>';
		echo '<select style="min-height: 200px" style="min-height: 200px" id="'.esc_attr($this->get_field_id('categories')).'" name="'.esc_attr($this->get_field_name('categories')).'[]" class="widefat" multiple>';
			$terms = couponxxl_get_organized( 'offer_cat' );
			if( !empty( $terms ) ){
				foreach( $terms as $key => $term ){
					couponxxl_display_indent_select_tree( $term, $categories, 0 );
				}
			}
		echo '</select>';	
		
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['categories'] = $new_instance['categories'];
		return $instance;	
	}	
}


/********************************************************
COUPONXXL Popular Stores
********************************************************/
class Couponxxl_Popular_Stores extends WP_Widget {
	public function __construct() {
		parent::__construct('couponxxl_popular_stores', esc_html__('CouponXXL Sidebar Popular Stores','couponxxl'), array('description' =>esc_html__('CouponXXL Popular Stores Widget For Sidebar Only','couponxxl') ));
	}
	public function widget( $args, $instance ) {
		extract($args);
		$title = empty( $instance['title'] ) ? '' : $instance['title'];
		$count = empty( $instance['count'] ) ? 6 : $instance['count'];
		$popular_stores = couponxxl_popular_stores( $count );

		echo  $before_widget.$before_title.$title.$after_title.'<div class="white-block-content">';
		if( !empty( $popular_stores ) ){
			echo '<ul class="list-unstyled list-inline">';
			foreach( $popular_stores as $store ){
				?>
				<li>
					<a href="<?php echo get_permalink( $store->ID ) ?>">
						<?php echo couponxxl_store_logo( $store->ID, true, 'couponxxl-shop-logo-widget' ); ?>
					</a>
				</li>
				<?php
			}
			echo '</ul>';
		}
		echo '</div>'.$after_widget;	
	}
 	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => '' ) );
		
		$title = esc_attr( $instance['title'] );
		$count = esc_attr( $instance['count'] );

		echo '<p><label for="'.esc_attr($this->get_field_id('title')).'">'.esc_html__( 'Title:', 'couponxxl' ).'</label>';
		echo '<input type="text" class="widefat" id="'.esc_attr($this->get_field_id('title')).'"  name="'.esc_attr($this->get_field_name('title')).'" value="'.esc_attr( $title ).'"></p>';		

		echo '<p><label for="'.esc_attr($this->get_field_id('count')).'">'.esc_html__( 'Count:', 'couponxxl' ).'</label>';
		echo '<input type="text" class="widefat" id="'.esc_attr($this->get_field_id('count')).'"  name="'.esc_attr($this->get_field_name('count')).'" value="'.esc_attr( $count ).'"></p>';				
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = strip_tags( $new_instance['count'] );
		return $instance;	
	}	
}

/******************************************************** 
CouponXXL Subscribe
********************************************************/
class CouponXXL_Subscribe extends WP_Widget {	
	public function __construct() {
		parent::__construct('couponxxl_subscribe', esc_html__('CouponXXL Subscribe','couponxxl'), array('description' =>esc_html__("CouponXXL Subscribe Widget","couponxxl") ));
	}
	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? esc_html__('Subscribe','couponxxl') : $instance['title'], $instance, $this->id_base);
		
		echo  $before_widget.
				$before_title.$title.$after_title.'
				<form>
					<div class="form-group">
						<input name="email" class="form-control" placeholder="'.esc_attr__( 'Your email', 'couponxxl' ).'" type="text">
						<input type="hidden" name="action" value="subscribe">
						<i class="pline-envelope"></i>
					</div>
					<a href="javascript:;" class="subscribe btn submit-form ajax-return">'.esc_html__( 'Subscribe', 'couponxxl' ).'</a>
					<div class="ajax-response"></div>
				</form>'.$after_widget;
	}
 	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		
		$title = esc_attr( $instance['title'] );
		
		echo '<p><label for="'.esc_attr($this->get_field_id('title')).'">'.esc_html__('Title:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('title')).'"  name="'.esc_attr($this->get_field_name('title')).'" type="text" value="'.esc_attr( $title ).'" /></p>';	
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;	
	}	
}

/******************************************************** 
CouponXXL Staff picks
********************************************************/
class CouponXXL_Staff_Picks extends WP_Widget {	
	public function __construct() {
		parent::__construct('couponxxl_staff_picks', esc_html__('CouponXXL Staff Picks','couponxxl'), array('description' =>esc_html__("CouponXXL Staff Picks Widget","couponxxl") ));
	}
	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? esc_html__('Subscribe','couponxxl') : $instance['title'], $instance, $this->id_base);
		$offers = empty( $instance['offers'] ) ? '' : $instance['offers'];

		if( !empty( $offers ) ){
			$offers = explode( ',', $offers );
			$offers = new WP_Offers_Query(array(
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'post__in' => $offers
			));

			echo  $before_widget.$before_title.$title.$after_title;

			if( $offers->have_posts() ){
				while( $offers->have_posts() ){
					$offers->the_post();
					$offer_type = couponxxl_get_the_offer_type();
					?>
					<div class="staff-pick-item">
						<a href="<?php the_permalink() ?>" class="<?php echo $offer_type == 'coupon' ? esc_attr( 'coupon-list-wrap' ) : esc_attr( '' ) ?>">
							<?php
							if( $offer_type == 'deal' ){
								the_post_thumbnail( 'couponxxl-staff-box' );
							}
							else{
                                $store_id = get_post_meta( get_the_ID(), 'offer_store', true );
                                couponxxl_store_logo( $store_id );
							}
							?>
						</a>
						<div class="staff-pick-details">
							<h6>
								<a href="<?php the_permalink() ?>">
									<?php
									$title = get_the_title();
									if( strlen( $title ) > 25 ){
										$title = substr( $title, 0, 25 ).'...';
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
			}

			echo  $after_widget;

		}
	}
 	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'offers' => '') );
		
		$title = esc_attr( $instance['title'] );
		$offers = esc_attr( $instance['offers'] );
		
		echo '<p><label for="'.esc_attr($this->get_field_id('title')).'">'.esc_html__('Title:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('title')).'"  name="'.esc_attr($this->get_field_name('title')).'" type="text" value="'.esc_attr( $title ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('offers')).'">'.esc_html__('Offers (comma separated list of IDs):','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('offers')).'"  name="'.esc_attr($this->get_field_name('offers')).'" type="text" value="'.esc_attr( $offers ).'" /></p>';	
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['offers'] = strip_tags($new_instance['offers']);
		return $instance;	
	}	
}

/******************************************************** 
CouponXXL Useful Links
********************************************************/
class CouponXXL_Useful_Links extends WP_Widget {	
	public function __construct() {
		parent::__construct('couponxxl_useful_links', esc_html__('CouponXXL Useful Links','couponxxl'), array('description' =>esc_html__("CouponXXL Useful Links Widget","couponxxl") ));
	}
	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? esc_html__('Subscribe','couponxxl') : $instance['title'], $instance, $this->id_base);
		$links = empty( $instance['links'] ) ? '' : $instance['links'];

		if( !empty( $links ) ){
			$links = explode( "\n", $links );
			echo  $before_widget.$before_title.$title.$after_title;
			echo '<ul class="list-unstyled">';
			foreach( $links as $link ){
				echo '<li>'.$link.'</li>';
			}
			echo '</ul>';
			echo  $after_widget;
		}
	}
 	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'links' => '') );
		
		$title = esc_attr( $instance['title'] );
		$links = esc_attr( $instance['links'] );
		
		echo '<p><label for="'.esc_attr($this->get_field_id('title')).'">'.esc_html__('Title:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('title')).'"  name="'.esc_attr($this->get_field_name('title')).'" type="text" value="'.esc_attr( $title ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('links')).'">'.esc_html__('Links (separated with line break - enter):','couponxxl').'</label>';
		echo '<textarea class="widefat" id="'.esc_attr($this->get_field_id('links')).'"  name="'.esc_attr($this->get_field_name('links')).'">'.$links.'</textarea></p>';	
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['links'] = $new_instance['links'];
		return $instance;	
	}	
}

/******************************************************** 
CouponXXL Useful Links
********************************************************/
class CouponXXL_Follow_Us extends WP_Widget {	
	public function __construct() {
		parent::__construct('couponxxl_follow_us', esc_html__('CouponXXL Follow Us','couponxxl'), array('description' =>esc_html__("CouponXXL Follow Us Widget","couponxxl") ));
	}
	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? esc_html__('Subscribe','couponxxl') : $instance['title'], $instance, $this->id_base);
		$links = empty( $instance['links'] ) ? '' : $instance['links'];
		$facebook = empty( $instance['facebook'] ) ? '' : $instance['facebook'];
		$twitter = empty( $instance['twitter'] ) ? '' : $instance['twitter'];
		$google = empty( $instance['google'] ) ? '' : $instance['google'];
		$rss = empty( $instance['rss'] ) ? '' : $instance['rss'];

		echo  $before_widget.$before_title.$title.$after_title;
		echo '<ul class="list-unstyled list-inline store-social-networks">';
		if( !empty( $facebook ) ){
			echo '<li><a href="'.esc_url( $facebook ).'"><i class="fa fa-facebook-square"></i></a></li>';
		}
		if( !empty( $twitter ) ){
			echo '<li><a href="'.esc_url( $twitter ).'"><i class="fa fa-twitter-square"></i></a></li>';
		}
		if( !empty( $google ) ){
			echo '<li><a href="'.esc_url( $google ).'"><i class="fa fa-google-plus-square"></i></a></li>';
		}
		if( !empty( $rss ) ){
			echo '<li><a href="'.esc_url( $rss ).'"><i class="fa fa-rss-square"></i></a></li>';
		}
		echo '</ul>';
		echo  $after_widget;
	}
 	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'facebook' => '', 'twitter' => '', 'google'  => '', 'rss' => '') );
		
		$title = esc_attr( $instance['title'] );
		$facebook = esc_attr( $instance['facebook'] );
		$twitter = esc_attr( $instance['twitter'] );
		$google = esc_attr( $instance['google'] );
		$rss = esc_attr( $instance['rss'] );
		
		echo '<p><label for="'.esc_attr($this->get_field_id('title')).'">'.esc_html__('Title:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('title')).'"  name="'.esc_attr($this->get_field_name('title')).'" type="text" value="'.esc_attr( $title ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('facebook')).'">'.esc_html__('Facebook:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('facebook')).'"  name="'.esc_attr($this->get_field_name('facebook')).'" type="text" value="'.esc_attr( $facebook ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('twitter')).'">'.esc_html__('Twitter:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('twitter')).'"  name="'.esc_attr($this->get_field_name('twitter')).'" type="text" value="'.esc_attr( $twitter ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('google')).'">'.esc_html__('Google:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('google')).'"  name="'.esc_attr($this->get_field_name('google')).'" type="text" value="'.esc_attr( $google ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('rss')).'">'.esc_html__('RSS:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('rss')).'"  name="'.esc_attr($this->get_field_name('rss')).'" type="text" value="'.esc_attr( $rss ).'" /></p>';	
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['facebook'] = strip_tags($new_instance['facebook']);
		$instance['twitter'] = strip_tags($new_instance['twitter']);
		$instance['google'] = strip_tags($new_instance['google']);
		$instance['rss'] = strip_tags($new_instance['rss']);
		return $instance;	
	}	
}

/******************************************************** 
CouponXXL Payments
********************************************************/
class CouponXXL_Payments extends WP_Widget {	
	public function __construct() {
		parent::__construct('couponxxl_payments', esc_html__('CouponXXL Payments','couponxxl'), array('description' =>esc_html__("CouponXXL Payments Widget","couponxxl") ));
	}
	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base);
		$stripe = empty( $instance['stripe'] ) ? '' : $instance['stripe'];
		$ideal = empty( $instance['ideal'] ) ? '' : $instance['ideal'];
		$skrill = empty( $instance['skrill'] ) ? '' : $instance['skrill'];
		$payumoney = empty( $instance['payumoney'] ) ? '' : $instance['payumoney'];
		$paypal = empty( $instance['paypal'] ) ? '' : $instance['paypal'];
		$bank = empty( $instance['bank'] ) ? '' : $instance['bank'];

		echo  $before_widget;
		if( !empty( $title ) ){
			echo  $before_title.$title.$after_title;
		}
		echo '<ul class="list-unstyled">';
		if( !empty( $stripe ) ){
			echo '<li><a href="'.esc_url( $stripe ).'"><img src="'.esc_url( get_template_directory_uri() . '/images/small-stripe.png' ).'" width="32" height="22" alt="stripe"></a></li>';
		}
		if( !empty( $ideal ) ){
			echo '<li><a href="'.esc_url( $ideal ).'"><img src="'.esc_url( get_template_directory_uri() . '/images/small-ideal.png' ).'" width="32" height="22" alt="ideal"></a></li>';
		}
		if( !empty( $skrill ) ){
			echo '<li><a href="'.esc_url( $skrill ).'"><img src="'.esc_url( get_template_directory_uri() . '/images/small-skrill.png' ).'" width="32" height="22" alt="skrill"></a></li>';
		}
		if( !empty( $payumoney ) ){
			echo '<li><a href="'.esc_url( $payumoney ).'"><img src="'.esc_url( get_template_directory_uri() . '/images/small-payumoney.png' ).'" width="32" height="22" alt="payumoney"></a></li>';
		}
		if( !empty( $paypal ) ){
			echo '<li><a href="'.esc_url( $paypal ).'"><img src="'.esc_url( get_template_directory_uri() . '/images/small-paypal.png' ).'" width="32" height="22" alt="paypal"></a></li>';
		}
		if( !empty( $bank ) ){
			echo '<li><a href="'.esc_url( $bank ).'"><img src="'.esc_url( get_template_directory_uri() . '/images/small-bank.png' ).'" width="32" height="22" alt="bank"></a></li>';
		}
		echo '</ul>';
		echo  $after_widget;
	}
 	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'stripe' => '', 'ideal' => '', 'skrill' => '', 'payumoney' => '', 'paypal' => '', 'bank' => '') );
		
		$title = esc_attr( $instance['title'] );
		$stripe = esc_attr( $instance['stripe'] );
		$ideal = esc_attr( $instance['ideal'] );
		$skrill = esc_attr( $instance['skrill'] );
		$payumoney = esc_attr( $instance['payumoney'] );
		$paypal = esc_attr( $instance['paypal'] );
		$bank = esc_attr( $instance['bank'] );
		
		echo '<p><label for="'.esc_attr($this->get_field_id('title')).'">'.esc_html__('Title:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('title')).'"  name="'.esc_attr($this->get_field_name('title')).'" type="text" value="'.esc_attr( $title ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('stripe')).'">'.esc_html__('Stripe:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('stripe')).'"  name="'.esc_attr($this->get_field_name('stripe')).'" type="text" value="'.esc_attr( $stripe ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('ideal')).'">'.esc_html__('iDEAL:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('ideal')).'"  name="'.esc_attr($this->get_field_name('ideal')).'" type="text" value="'.esc_attr( $ideal ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('skrill')).'">'.esc_html__('Skrill:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('skrill')).'"  name="'.esc_attr($this->get_field_name('skrill')).'" type="text" value="'.esc_attr( $skrill ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('payumoney')).'">'.esc_html__('PayUMoney:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('payumoney')).'"  name="'.esc_attr($this->get_field_name('payumoney')).'" type="text" value="'.esc_attr( $payumoney ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('paypal')).'">'.esc_html__('Paypal:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('paypal')).'"  name="'.esc_attr($this->get_field_name('paypal')).'" type="text" value="'.esc_attr( $paypal ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('bank')).'">'.esc_html__('Bank:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('bank')).'"  name="'.esc_attr($this->get_field_name('bank')).'" type="text" value="'.esc_attr( $bank ).'" /></p>';	
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['stripe'] = strip_tags($new_instance['stripe']);
		$instance['ideal'] = strip_tags($new_instance['ideal']);
		$instance['skrill'] = strip_tags($new_instance['skrill']);
		$instance['payumoney'] = strip_tags($new_instance['payumoney']);
		$instance['paypal'] = strip_tags($new_instance['paypal']);
		$instance['bank'] = strip_tags($new_instance['bank']);
		return $instance;	
	}	
}

/******************************************************** 
CouponXXL Useful Links
********************************************************/
class CouponXXL_Banner extends WP_Widget {	
	public function __construct() {
		parent::__construct('couponxxl_banner', esc_html__('CouponXXL Banner','couponxxl'), array('description' =>esc_html__("CouponXXL Banner Widget","couponxxl") ));
	}
	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base);
		$image_id = empty( $instance['image_id'] ) ? '' : $instance['image_id'];
		$image_link = empty( $instance['image_link'] ) ? '' : $instance['image_link'];

		if( !empty( $image_id ) ){
			echo  $before_widget.$before_title.$title.$after_title;
			echo '<a href="'.esc_url( $image_link ).'" target="_blank">';
			echo wp_get_attachment_image( $image_id, 'full' );
			echo '</a>';
			echo  $after_widget;
		}
	}
 	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'image_id' => '', 'image_link' => '') );
		
		$title = esc_attr( $instance['title'] );
		$image_id = esc_attr( $instance['image_id'] );
		$image_link = esc_attr( $instance['image_link'] );
		
		echo '<p><label for="'.esc_attr($this->get_field_id('title')).'">'.esc_html__('Title:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('title')).'"  name="'.esc_attr($this->get_field_name('title')).'" type="text" value="'.esc_attr( $title ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('image_id')).'">'.esc_html__('Image ID:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('image_id')).'"  name="'.esc_attr($this->get_field_name('image_id')).'" type="text" value="'.esc_attr( $image_id ).'" /></p>';	

		echo '<p><label for="'.esc_attr($this->get_field_id('image_link')).'">'.esc_html__('Link:','couponxxl').'</label>';
		echo '<input class="widefat" id="'.esc_attr($this->get_field_id('image_link')).'"  name="'.esc_attr($this->get_field_name('image_link')).'" type="text" value="'.esc_attr( $image_link ).'" /></p>';	
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['image_id'] = strip_tags($new_instance['image_id']);
		$instance['image_link'] = strip_tags($new_instance['image_link']);
		return $instance;	
	}	
}

/********************************************************
Add CouponXXL Widgets
********************************************************/
function couponxxl_widgets_load(){
	register_widget( 'Couponxxl_Categories' );
	register_widget( 'Couponxxl_Popular_Stores' );
	register_widget( 'CouponXXL_Subscribe' );
	register_widget( 'CouponXXL_Staff_Picks' );
	register_widget( 'CouponXXL_Useful_Links' );
	register_widget( 'CouponXXL_Follow_Us' );
	register_widget( 'CouponXXL_Payments' );
	register_widget( 'CouponXXL_Banner' );
	
}
add_action( 'widgets_init', 'couponxxl_widgets_load' );
?>