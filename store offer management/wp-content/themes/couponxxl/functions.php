<?php

/**********************************************************************
 ***********************************************************************
 * COUPON FUNCTIONS
 **********************************************************************/
/* UPDATE CHECK */
if ( ! function_exists( 'couponxxl_update_check' ) ) {
	function couponxxl_update_check() {
		global $wpdb;
		$current_version = 11;
		$version         = get_option( 'couponxxl_version' );
		if ( empty( $version ) ) {
			$version = 0;
		}

		if ( function_exists( 'sm_init' ) ) {
			$smeta_data = get_plugins( '/smeta' );
			if ( $smeta_data['smeta.php']['Version'] != '1.1' ) {
				?>
				<div class="notice notice-success is-dismissible error">
					<p><?php esc_html_e( 'Reinstall Smeta plugin ( Delete it and theme will offer you to install it again )', 'boston' ); ?></p>
				</div>
				<?php
			}
		}
		if ( function_exists( 'couponxxl_post_types_and_taxonomies' ) ) {
			$smeta_data = get_plugins( '/couponxxl-cpt' );
			if ( $smeta_data['couponxxl-cpt.php']['Version'] != '1.1' ) {
				?>
				<div class="notice notice-success is-dismissible error">
					<p><?php esc_html_e( 'Reinstall CouponXXL Custom Post Types plugin ( Delete it and theme will offer you to install it again )', 'boston' ); ?></p>
				</div>
				<?php
			}
		}
		if ( function_exists( 'couponxxl_p_process_images' ) ) {
			$smeta_data = get_plugins( '/couponxxl-import' );
			if ( $smeta_data['couponxxl-import.php']['Version'] != '1.1' ) {
				?>
				<div class="notice notice-success is-dismissible error">
					<p><?php esc_html_e( 'Reinstall CouponXxL Import plugin ( Delete it and theme will offer you to install it again )', 'boston' ); ?></p>
				</div>
				<?php
			}
		}
	}

	add_action( 'init', 'couponxxl_update_check' );
}

/*
Check child theme for files
*/
if ( ! function_exists( 'couponxxl_load_path' ) ) {
	function couponxxl_load_path( $path ) {
		if ( file_exists( get_stylesheet_directory() . '/' . $path ) ) {
			return get_stylesheet_directory() . '/' . $path;
		} else {
			return get_template_directory() . '/' . $path;
		}
	}
}

$COUPONXXL_GATEWAYS = array();
foreach ( glob( get_template_directory() . "/includes/gateways/*.php" ) as $filename ) {
	require_once( couponxxl_load_path( 'includes/gateways/' . wp_basename( $filename ) ) );
}


load_theme_textdomain( 'couponxxl', get_template_directory() . '/languages' );

/*
Function to include required plugins
*/
if ( ! function_exists( 'couponxxl_requred_plugins' ) ) {
	function couponxxl_requred_plugins() {
		$plugins = array(
			array(
				'name'               => esc_html__( 'Redux Options', 'couponxxl' ),
				'slug'               => 'redux-framework',
				'source'             => get_template_directory() . '/lib/plugins/redux-framework.zip',
				'required'           => true,
				'version'            => '',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__( 'Smeta', 'couponxxl' ),
				'slug'               => 'smeta',
				'source'             => get_template_directory() . '/lib/plugins/smeta.zip',
				'required'           => true,
				'version'            => '',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__( 'Social Connect', 'couponxxl' ),
				'slug'               => 'social-connect',
				'source'             => get_template_directory() . '/lib/plugins/social-connect.zip',
				'required'           => false,
				'version'            => '',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__( 'User Avatars', 'couponxxl' ),
				'slug'               => 'wp-user-avatar',
				'source'             => get_template_directory() . '/lib/plugins/wp-user-avatar.zip',
				'required'           => true,
				'version'            => '',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__( 'CouponXxL CPT', 'couponxxl' ),
				'slug'               => 'couponxxl-cpt',
				'source'             => get_template_directory() . '/lib/plugins/couponxxl-cpt.zip',
				'required'           => true,
				'version'            => '',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__( 'CouponXxL import', 'couponxxl' ),
				'slug'               => 'couponxxl-import',
				'source'             => get_template_directory() . '/lib/plugins/couponxxl-import.zip',
				'required'           => false,
				'version'            => '',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__( 'Envato Market', 'couponxxl' ),
				'slug'               => 'envato-market',
				'source'             => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
				'required'           => false,
				'version'            => '',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
			),
		);

		/**
		 * Array of configuration settings. Amend each line as needed.
		 * If you want the default strings to be available under your own theme domain,
		 * leave the strings uncommented.
		 * Some of the strings are added into a sprintf, so see the comments at the
		 * end of each line for what each argument will be.
		 */
		$config = array(
			'domain'       => 'couponxxl',
			'default_path' => '',
			'menu'         => 'install-required-plugins',
			'has_notices'  => true,
			'is_automatic' => false,
			'message'      => ''
		);

		tgmpa( $plugins, $config );
	}

	add_action( 'tgmpa_register', 'couponxxl_requred_plugins' );
}

if ( ! isset( $content_width ) ) {
	$content_width = 1920;
}


/*
Register sidebars which theme uses
*/
if ( ! function_exists( 'couponxxl_widgets_init' ) ) {
	function couponxxl_widgets_init() {

		register_sidebar( array(
			'name'          => esc_html__( 'Blog Sidebar', 'couponxxl' ),
			'id'            => 'sidebar-blog',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears on the right side of the blog.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Page Sidebar Right', 'couponxxl' ),
			'id'            => 'sidebar-right',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears on the right side of the page.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Page Sidebar Left', 'couponxxl' ),
			'id'            => 'sidebar-left',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears on the left side of the page.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Coupon Page Sidebar', 'couponxxl' ),
			'id'            => 'sidebar-coupon',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears on the left side of the coupon listing page.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Deal Page Sidebar', 'couponxxl' ),
			'id'            => 'sidebar-deal',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears on the left side of the deal listing page.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Offer Sidebar', 'couponxxl' ),
			'id'            => 'sidebar-offer',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears on the right side of the offer single page.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Popular Page Sidebar', 'couponxxl' ),
			'id'            => 'sidebar-popular',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears on the left side of the popular listing page.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Search Sidebar', 'couponxxl' ),
			'id'            => 'sidebar-search',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears on the left side of the search page.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Bottom Sidebar 1', 'couponxxl' ),
			'id'            => 'sidebar-bottom-1',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears at the bottom of the page.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Bottom Sidebar 2', 'couponxxl' ),
			'id'            => 'sidebar-bottom-2',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears at the bottom of the page.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Bottom Sidebar 3', 'couponxxl' ),
			'id'            => 'sidebar-bottom-3',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears at the bottom of the page.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Bottom Sidebar 4', 'couponxxl' ),
			'id'            => 'sidebar-bottom-4',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Appears at the bottom of the page.', 'couponxxl' )
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Home Sidebar 1', 'couponxxl' ),
			'id'            => 'home-sidebar-1',
			'before_widget' => '<div class="widget white-block %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h5>',
			'after_title'   => '</h5></div>',
			'description'   => esc_html__( 'Used for the widget area on home page.', 'couponxxl' )
		) );

		$home_sidebars = couponxxl_get_option( 'home_sidebars' );
		if ( empty( $home_sidebars ) ) {
			$home_sidebars = 2;
		}

		for ( $i = 1; $i <= $home_sidebars; $i ++ ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Home Sidebar ', 'couponxxl' ) . $i,
				'id'            => 'home-sidebar-' . $i,
				'before_widget' => '<div class="widget white-block %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="widget-title"><h5>',
				'after_title'   => '</h5></div>',
				'description'   => esc_html__( 'Used for the widget area on home page.', 'couponxxl' )
			) );
		}

		$mega_menu_sidebars = couponxxl_get_option( 'mega_menu_sidebars' );
		if ( empty( $mega_menu_sidebars ) ) {
			$mega_menu_sidebars = 5;
		}

		for ( $i = 1; $i <= $mega_menu_sidebars; $i ++ ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Mega Menu Sidebar ', 'couponxxl' ) . $i,
				'id'            => 'mega-menu-' . $i,
				'before_widget' => '<li class="widget white-block %2$s">',
				'after_widget'  => '</li>',
				'before_title'  => '<div class="widget-title"><h5>',
				'after_title'   => '</h5></div>',
				'description'   => esc_html__( 'This will be shown as the dropdown menu in the navigation.', 'couponxxl' )
			) );
		}
	}

	add_action( 'widgets_init', 'couponxxl_widgets_init' );
}

/*
Redirect to store link
*/
if ( ! function_exists( 'couponxxl_store_url' ) ) {
	function couponxxl_store_url() {
		if ( isset( $_GET['rs'] ) ) {
			$store_link = get_post_meta( $_GET['rs'], 'store_link', true );
			if ( ! empty( $store_link ) ) {
				wp_redirect( $store_link );
			} else {
				wp_redirect( get_permalink( $_GET['rs'] ) );
			}
		}
		/* redirect to external deal link */
		if ( isset( $_GET['rd'] ) ) {
			$deal_link = get_post_meta( $_GET['rd'], 'deal_link', true );
			if ( ! empty( $deal_link ) ) {
				wp_redirect( $deal_link );
			}
		}
	}

	add_action( 'template_redirect', 'couponxxl_store_url', 0 );
}

/*
Generate title based on the filters which are being selected
*/
if ( ! function_exists( 'couponxxl_wp_title' ) ) {
	function couponxxl_wp_title( $title, $sep ) {
		global $paged, $page, $couponxxl_slugs;

		if ( is_feed() ) {
			return $title;
		}
		if ( is_page() && get_page_template_slug( get_the_ID() ) == 'page-tpl_profile.php' ) {
			return $title;
		}

		include( couponxxl_load_path( 'includes/search-args' ) );

		if ( ! empty( $keyword ) ) {
			$title = str_replace( '_', ' ', urldecode( $keyword ) ) . " $sep " . $title;
		}

		if ( ! empty( $offer_store ) ) {
			$title = get_the_title( $offer_store ) . " $sep " . $title;
		}

		if ( ! empty( $location ) ) {
			$term  = get_term_by( 'slug', $location, 'location' );
			$title = $term->name . " $sep " . $title;
		}

		if ( ! empty( $offer_cat ) ) {
			$term  = get_term_by( 'slug', $offer_cat, 'offer_cat' );
			$title = $term->name . " $sep " . $title;
		}

		return $title;
	}

	add_filter( 'wp_title', 'couponxxl_wp_title', 10, 2 );
}

/*
Generate title for the wordpress 4.4 and later
*/
if ( ! function_exists( 'couponxxl_wp_title_new' ) ) {
	function couponxxl_wp_title_new( $title ) {
		global $paged, $page, $couponxxl_slugs;

		if ( is_feed() ) {
			return $title;
		}
		if ( is_page() && get_page_template_slug( get_the_ID() ) == 'page-tpl_profile.php' ) {
			return $title;
		}

		if ( ! empty( $_GET[ $couponxxl_slugs['keyword'] ] ) ) {
			$title['title'] = str_replace( '_', ' ', urldecode( $_GET[ $couponxxl_slugs['keyword'] ] ) );
		}

		if ( ! empty( $_GET[ $couponxxl_slugs['offer_store'] ] ) ) {
			$title['title'] = get_the_title( $_GET[ $couponxxl_slugs['offer_store'] ] );
		}

		if ( ! empty( $_GET[ $couponxxl_slugs['location'] ] ) ) {
			$term           = get_term_by( 'slug', $_GET[ $couponxxl_slugs['location'] ], 'location' );
			$title['title'] = get_the_title( $term->name );
		}

		if ( ! empty( $_GET[ $couponxxl_slugs['offer_cat'] ] ) ) {
			$term           = get_term_by( 'slug', $_GET[ $couponxxl_slugs['offer_cat'] ], 'offer_cat' );
			$title['title'] = get_the_title( $term->name );
		}

		return $title;
	}

	add_filter( 'document_title_parts', 'couponxxl_wp_title_new', 10 );
}

/*
Set direction of the site
*/
if ( ! function_exists( 'couponxxl_set_direction' ) ) {
	function couponxxl_set_direction() {
		global $wp_locale, $wp_styles;

		$_user_id  = get_current_user_id();
		$direction = couponxxl_get_option( 'direction' );
		if ( empty( $direction ) ) {
			$direction = 'ltr';
		}

		if ( $direction ) {
			update_user_meta( $_user_id, 'rtladminbar', $direction );
		} else {
			$direction = get_user_meta( $_user_id, 'rtladminbar', true );
			if ( false === $direction ) {
				$direction = isset( $wp_locale->text_direction ) ? $wp_locale->text_direction : 'ltr';
			}
		}

		$wp_locale->text_direction = $direction;
		if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
			$wp_styles = new WP_Styles();
		}
		$wp_styles->text_direction = $direction;
	}

	add_action( 'init', 'couponxxl_set_direction' );
}


/*
Get page by the template
*/
if ( ! function_exists( 'couponxxl_get_permalink_by_tpl' ) ) {
	function couponxxl_get_permalink_by_tpl( $template_name ) {
		$page = get_pages( array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template_name . '.php'
		) );
		if ( ! empty( $page ) ) {
			return get_permalink( $page[0]->ID );
		} else {
			return "javascript:;";
		}
	}
}

/*
Generate random string
*/
if ( ! function_exists( 'couponxxl_confirm_hash' ) ) {
	function couponxxl_confirm_hash( $length = 100 ) {
		$characters    = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$random_string = '';
		for ( $i = 0; $i < $length; $i ++ ) {
			$random_string .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
		}

		return $random_string;
	}
}

/*
Add extra columns to the users listing
*/
if ( ! function_exists( 'couponxxl_active_column' ) ) {
	function couponxxl_active_column( $columns ) {
		$columns['active']              = esc_html__( 'Activation Status', 'couponxxl' );
		$columns['cxxl_access_backend'] = esc_html__( 'Can Access Backend?', 'couponxxl' );
		$columns['earnings_sent']       = esc_html__( 'Earnings Sent', 'couponxxl' );
		$columns['earnings_due']        = esc_html__( 'Earnings Due', 'couponxxl' );
		$columns['sales']               = esc_html__( 'Sales', 'couponxxl' );
		$columns['purchases']           = esc_html__( 'Purchases', 'couponxxl' );
		$columns['credits']             = esc_html__( 'Bank Transfer Credits', 'couponxxl' );

		return $columns;
	}

	add_filter( 'manage_users_columns', 'couponxxl_active_column' );
}

/*
Calculate earnings of the use to be displayed on the user listing page
*/
if ( ! function_exists( 'couponxxl_user_earnings' ) ) {
	function couponxxl_user_earnings( $user_id ) {
		global $wpdb;
		$earnings = $wpdb->get_results( $wpdb->prepare( "SELECT 
		COUNT(*) AS sales, 
		SUM(CASE WHEN status='sent' THEN seller_share END) AS paid, 
		SUM(CASE WHEN status='payout' THEN seller_share END) AS not_paid 
		FROM {$wpdb->prefix}order_items 
		WHERE seller_id = %d", $user_id ) );

		return $earnings[0];
	}
}

/*
Count user purchases
*/
if ( ! function_exists( 'couponxxl_user_purchases' ) ) {
	function couponxxl_user_purchases( $user_id = '', $source = 'orders' ) {
		global $wpdb;
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}
		if ( $source == 'orders' ) {
			$purchases = $wpdb->get_results( $wpdb->prepare( "SELECT 
			COUNT(ID) AS purchases 
			FROM {$wpdb->posts} 
			WHERE post_author = %d and post_type = 'ord' AND post_status = 'publish'", $user_id ) );
		} else {
			$purchases = $wpdb->get_results( $wpdb->prepare( "SELECT 
			COUNT(order_items.item_id) AS purchases 
			FROM {$wpdb->prefix}order_items AS order_items LEFT JOIN {$wpdb->prefix}vouchers AS vouchers ON order_items.item_id = vouchers.item_id 
			WHERE order_items.buyer_id = %d AND order_items.status = 'payout'", $user_id ) );
		}

		return $purchases[0]->purchases;
	}
}

/*
Populate extra columns on the users listing
*/
if ( ! function_exists( 'couponxxl_active_column_content' ) ) {
	function couponxxl_active_column_content( $value, $column_name, $user_id ) {
		if ( 'active' == $column_name ) {
			$usermeta = get_user_meta( $user_id, 'user_active_status', true );
			if ( empty( $usermeta ) || $usermeta == "active" ) {
				return esc_html__( 'Activated', 'couponxxl' );
			} else {
				return esc_html__( 'Need Confirmation', 'couponxxl' );
			}
		} else if ( 'cxxl_access_backend' == $column_name ) {
			$cxxl_access_backend = get_user_meta( $user_id, 'cxxl_access_backend', true );
			if ( $cxxl_access_backend == 'yes' ) {
				return esc_html__( 'Yes', 'couponxxl' );
			}
		} else if ( 'earnings_sent' == $column_name ) {
			$earnings = couponxxl_user_earnings( $user_id );

			return couponxxl_format_price_number( 0 + $earnings->paid );
		} else if ( 'earnings_due' == $column_name ) {
			$earnings = couponxxl_user_earnings( $user_id );

			return couponxxl_format_price_number( 0 + $earnings->not_paid );
		} else if ( 'sales' == $column_name ) {
			$earnings = couponxxl_user_earnings( $user_id );

			return 0 + $earnings->sales;
		} else if ( 'purchases' == $column_name ) {
			return couponxxl_user_purchases( $user_id );
		} else if ( 'credits' == $column_name ) {
			$credits = get_user_meta( $user_id, 'cxxl_credits_manual', true );
			if ( ! empty( $credits ) ) {
				return '<a href="javascript:;" class="process-credits" data-user_id="' . esc_attr( $user_id ) . '">' . esc_html__( 'Process', 'couponxxl' ) . '</a>';
			}
		}

		return $value;
	}

	add_action( 'manage_users_custom_column', 'couponxxl_active_column_content', 10, 3 );
}

/*
Change status of the user
*/
if ( ! function_exists( 'couponxxler_edit_user_status' ) ) {
	function couponxxler_edit_user_status( $user ) {
		$user_active_status  = get_user_meta( $user->ID, 'user_active_status', true );
		$cxxl_access_backend = get_user_meta( $user->ID, 'cxxl_access_backend', true );
		$cxxl_account_type   = get_user_meta( $user->ID, 'cxxl_account_type', true );
		?>
		<h3><?php esc_html_e( 'User Details', 'couponxxl' ) ?></h3>

		<table class="form-table">
			<tr>
				<th><label for="user_active_status"><?php esc_html_e( 'User Status', 'couponxxl' ); ?></label></th>
				<td>
					<select name="user_active_status">
						<option <?php echo ! empty( $user_active_status ) && $user_active_status != 'active' ? 'selected="selected"' : '' ?>
							value="inactive"><?php esc_html_e( 'Inactive', 'couponxxl' ) ?></option>
						<option <?php echo empty( $user_active_status ) || $user_active_status == 'active' ? 'selected="selected"' : '' ?>
							value="active"><?php esc_html_e( 'Active', 'couponxxl' ) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label
						for="cxxl_access_backend"><?php esc_html_e( 'User Can Access Backend?', 'couponxxl' ); ?></label>
				</th>
				<td>
					<select name="cxxl_access_backend">
						<option <?php echo $cxxl_access_backend != 'yes' ? 'selected="selected"' : '' ?>
							value="yes"><?php esc_html_e( 'Yes', 'couponxxl' ) ?></option>
						<option <?php echo empty( $cxxl_access_backend ) || $cxxl_access_backend == 'no' ? 'selected="selected"' : '' ?>
							value="no"><?php esc_html_e( 'No', 'couponxxl' ) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="cxxl_account_type"><?php esc_html_e( 'Account Type', 'couponxxl' ); ?></label></th>
				<td>
					<select name="cxxl_account_type">
						<option <?php echo $cxxl_account_type == 'vendor' ? 'selected="selected"' : '' ?>
							value="vendor"><?php esc_html_e( 'Vendor', 'couponxxl' ) ?></option>
						<option <?php echo $cxxl_account_type == 'agent' ? 'selected="selected"' : '' ?>
							value="agent"><?php esc_html_e( 'Vendor Agent', 'couponxxl' ) ?></option>
						<option <?php echo $cxxl_account_type == 'buyer' ? 'selected="selected"' : '' ?>
							value="buyer"><?php esc_html_e( 'Buyer', 'couponxxl' ) ?></option>
					</select>
				</td>
			</tr>
			<?php if ( $cxxl_account_type == 'vendor' ):
				$cxxl_credits = get_user_meta( $user->ID, 'cxxl_credits', true );
				?>
				<tr>
					<th><label for="cxxl_credits"><?php esc_html_e( 'Credits', 'couponxxl' ); ?></label></th>
					<td>
						<input type="text" name="cxxl_credits"
						       value="<?php echo ! empty( $cxxl_credits ) ? esc_attr( $cxxl_credits ) : 0 ?>"/>
					</td>
				</tr>
			<?php endif; ?>
		</table>
		<?php
	}

	add_action( 'show_user_profile', 'couponxxler_edit_user_status' );
	add_action( 'edit_user_profile', 'couponxxler_edit_user_status' );
}

/*
Save new values for the user meta
*/
if ( ! function_exists( 'couponxxler_save_user_meta' ) ) {
	function couponxxler_save_user_meta( $user_id ) {
		update_user_meta( $user_id, 'user_active_status', sanitize_text_field( $_POST['user_active_status'] ) );
		update_user_meta( $user_id, 'cxxl_access_backend', sanitize_text_field( $_POST['cxxl_access_backend'] ) );
		if ( empty( $_POST['cxxl_credits'] ) ) {
			delete_post_meta( $user_id, 'cxxl_credits' );
		} else {
			update_user_meta( $user_id, 'cxxl_credits', sanitize_text_field( $_POST['cxxl_credits'] ) );
		}

		if ( empty( $_POST['cxxl_account_type'] ) ) {
			delete_post_meta( $user_id, 'cxxl_account_type' );
		} else {
			update_user_meta( $user_id, 'cxxl_account_type', sanitize_text_field( $_POST['cxxl_account_type'] ) );
		}
	}

	add_action( 'personal_options_update', 'couponxxler_save_user_meta' );
	add_action( 'edit_user_profile_update', 'couponxxler_save_user_meta' );
}

/*
Save profile changes from frontend
*/
if ( ! function_exists( 'couponxxl_update_profile' ) ) {
	function couponxxl_update_profile() {
		global $current_user;
		$current_user    = wp_get_current_user();
		$first_name      = isset( $_POST['first_name'] ) ? $_POST['first_name'] : '';
		$last_name       = isset( $_POST['last_name'] ) ? $_POST['last_name'] : '';
		$email           = isset( $_POST['email'] ) ? $_POST['email'] : '';
		$password        = isset( $_POST['password'] ) ? $_POST['password'] : '';
		$repeat_password = isset( $_POST['repeat_password'] ) ? $_POST['repeat_password'] : '';

		$seller_payout_method  = isset( $_POST['seller_payout_method'] ) ? $_POST['seller_payout_method'] : '';
		$seller_payout_account = isset( $_POST[ 'seller_payout_account_' . $seller_payout_method ] ) ? $_POST[ 'seller_payout_account_' . $seller_payout_method ] : '';

		if ( ! empty( $email ) ) {
			$updated_password = '';
			if ( ! empty( $password ) && ! empty( $repeat_password ) ) {
				$updated_password = 'no';
				if ( $password == $repeat_password ) {
					$updated_password = 'yes';
				}
			}

			if ( ! empty( $updated_password ) && $updated_password == 'no' ) {
				$response['message'] = '<div class="danger">' . esc_html__( 'Passwords do not match', 'couponxxl' ) . '</div>';
			} else {
				$update_fields = array(
					'ID'           => $current_user->ID,
					'user_email'   => $email,
					'display_name' => $first_name . ' ' . $last_name
				);
				if ( $updated_password == 'yes' ) {
					$update_fields['user_pass'] = $password;
				}
				update_user_meta( $current_user->ID, 'first_name', $first_name );
				update_user_meta( $current_user->ID, 'last_name', $last_name );

				wp_update_user( $update_fields );

				$old_seller_payout_method  = get_user_meta( $current_user->ID, 'seller_payout_method', true );
				$old_seller_payout_account = get_user_meta( $current_user->ID, 'seller_payout_account', true );
				if ( ! empty( $old_seller_payout_method ) && $old_seller_payout_method !== $seller_payout_method ) {
					do_action( 'couponxxl_deregister_payout_account', $old_seller_payout_account, $old_seller_payout_method );
				}

				if ( empty( $seller_payout_method ) || empty( $seller_payout_account ) ) {
					delete_user_meta( $current_user->ID, 'seller_payout_method' );
					delete_user_meta( $current_user->ID, 'seller_payout_account' );
				} else {
					update_user_meta( $current_user->ID, 'seller_payout_method', $seller_payout_method );
					update_user_meta( $current_user->ID, 'seller_payout_account', $seller_payout_account );
				}

				$response['message'] = '<div class="success">' . esc_html__( 'Profile is updated', 'couponxxl' ) . '</div>';
			}
		} else {
			$response['message'] = '<div class="danger">' . esc_html__( 'First name, last name and email are required', 'couponxxl' ) . '</div>';
		}

		echo json_encode( $response );
		die();
	}

	add_action( 'wp_ajax_update_profile', 'couponxxl_update_profile' );
	add_action( 'wp_ajax_nopriv_update_profile', 'couponxxl_update_profile' );
}


/*
Disable admin bar for non admins
*/
if ( ! function_exists( 'couponxxl_remove_admin_bar' ) ) {
	function couponxxl_remove_admin_bar() {
		$user_ID             = get_current_user_id();
		$cxxl_access_backend = get_user_meta( $user_ID, 'cxxl_access_backend', true );
		if ( ! current_user_can( 'administrator' ) && ! is_admin() && ( ! $cxxl_access_backend || $cxxl_access_backend == 'no' ) ) {
			show_admin_bar( false );
		}
	}

	add_action( 'after_setup_theme', 'couponxxl_remove_admin_bar' );
}


/*
Count custom filter of posts
*/
if ( ! function_exists( 'couponxxl_custom_post_count' ) ) {
	function couponxxl_custom_post_count( $type ) {
		$num_posts = wp_count_posts( $type );

		return intval( $num_posts->publish );
	}
}


/*
Defaults values of the theme option
*/
if ( ! function_exists( 'couponxxl_defaults' ) ) {
	function couponxxl_defaults( $id ) {
		$defaults = array(
			'direction'                           => 'ltr',
			'theme_usage'                         => 'all',
			'home_sidebars'                       => '2',
			'trans_offer_type'                    => 'offer_type',
			'trans_offer_cat'                     => 'offer_cat',
			'trans_offer_tag'                     => 'offer_tag',
			'trans_location'                      => 'location',
			'trans_offer_store'                   => 'offer_store',
			'trans_keyword'                       => 'keyword',
			'trans_store'                         => 'store',
			'trans_letter'                        => 'letter',
			'trans_offer'                         => 'offer',
			'site_logo'                           => array( 'url' => '' ),
			'site_logo_width'                     => '',
			'site_logo_height'                    => '',
			'site_logo_padding'                   => '',
			'quick_search_chars'                  => '3',
			'enable_sticky'                       => 'no',
			'mega_menu_sidebars'                  => '5',
			'footer_copyrights'                   => '',
			'home_divider_section'                => '',
			'big_map_slider_height'               => '',
			'home_search_bg_image'                => '',
			'home_search_subtitle'                => '',
			'home_search_title'                   => '',
			'home_search_input_placeholder'       => '',
			'home_search_btn_text'                => '',
			'page_title_style'                    => '',
			'page_title_bg_image'                 => '',
			'search_sidebar_location'             => 'left',
			'show_deals_map'                      => 'yes',
			'marker_icon'                         => '',
			'map_trigger_img'                     => '',
			'all_categories_sortby'               => 'name',
			'all_categories_sort'                 => 'asc',
			'all_locations_sortby'                => 'name',
			'all_locations_sort'                  => 'asc',
			'contact_mail'                        => '',
			'contact_form_subject'                => '',
			'contact_map'                         => '',
			'contact_map_max_zoom'                => '',
			'contact_address'                     => '',
			'contact_phone'                       => '',
			'contact_link'                        => '',
			'contact_email'                       => '',
			'contact_facebook'                    => '',
			'contact_twitter'                     => '',
			'contact_google'                      => '',
			'contact_rss'                         => '',
			'stores_per_page'                     => '9',
			'offers_per_page'                     => '9',
			'stores_show_expired'                 => 'no',
			'store_no_offers_message'             => esc_html__( 'Currently there is no coupons and deals for this store.', 'couponxxl' ),
			'search_no_offers_message'            => esc_html__( 'No deals and coupons found.', 'couponxxl' ),
			'listing_map_zoom'                    => '',
			'deal_single_zoom'                    => '',
			'search_order_by'                     => 'offer_expire',
			'search_order'                        => 'ASC',
			'terms'                               => '',
			'show_search_slider'                  => '',
			'search_page_offer_type_filter_title' => esc_html__( 'I\'m looking for', 'couponxxl' ),
			'search_page_category_filter_title'   => esc_html__( 'Category', 'couponxxl' ),
			'search_page_location_filter_title'   => esc_html__( 'Location', 'couponxxl' ),
			'search_page_store_filter_title'      => esc_html__( 'Stores', 'couponxxl' ),
			'search_show_count'                   => 'yes',
			'search_visible_locations_count'      => '8',
			'search_visible_stores_count'         => '6',
			'deal_show_bought'                    => 'yes',
			'deal_show_similar'                   => 'yes',
			'similar_offers'                      => '2',
			'coupon_modal_content'                => 'content',
			'coupon_show_similar'                 => 'yes',
			'coupon_similar_offers'               => '2',
			'deal_owner_price_shared'             => '',
			'deal_owner_price_not_shared'         => '',
			'deal_submit_price'                   => '',
			'coupon_submit_price'                 => '',
			'credit_packages'                     => '',
			'unlimited_expire'                    => 'no',
			'date_ranges'                         => '',
			'email_sender'                        => '',
			'name_sender'                         => '',
			'registration_message'                => '',
			'registration_subject'                => '',
			'register_terms'                      => '',
			'new_agent_message'                   => '',
			'new_agent_subject'                   => '',
			'new_offer_email'                     => '',
			'lost_password_message'               => '',
			'lost_password_subject'               => '',
			'unit'                                => '',
			'main_unit_abbr'                      => '',
			'unit_position'                       => '',
			'mail_chimp_api'                      => '',
			'mail_chimp_list_id'                  => '',
			'theme_font'                          => 'Open Sans',
			'body_background'                     => '#f3f3f3',
			'footer_background'                   => '#262626',
			'footer_widget_background'            => '#ececec',
			'border_color'                        => '#f1f1f1',
			'font_color'                          => '#4a4a4a',
			'font_lighten'                        => '#4a4a4a',
			'font_light'                          => '#777',
			'footer_widget_font'                  => '#4a4a4a',
			'main_green_color'                    => '#4caf50',
			'sec_red_color'                       => '#ff5722',
			'thr_blue_color'                      => '#286eb5',
			'box_bg_color'                        => '#ffffff',
		);

		if ( isset( $defaults[ $id ] ) ) {
			return $defaults[ $id ];
		} else {

			return '';
		}
	}
}

/*
Get option either via Redux or default one.
*/
if ( ! function_exists( 'couponxxl_get_option' ) ) {
	function couponxxl_get_option( $id ) {
		global $couponxxl_options;
		if ( isset( $couponxxl_options[ $id ] ) ) {
			$value = $couponxxl_options[ $id ];
			if ( isset( $value ) ) {
				return apply_filters( 'couponxxl_get_options', $value, $id );
			} else {
				return apply_filters( 'couponxxl_get_options', '', $id );
			}
		} else {
			return apply_filters( 'couponxxl_get_options', couponxxl_defaults( $id ), $id );
		}
	}
}

/*
Initial settings of the theme
*/
if ( ! function_exists( 'couponxxl_setup' ) ) {
	function couponxxl_setup() {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( "title-tag" );
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list'
		) );
		register_nav_menu( 'top-navigation', esc_html__( 'Top Navigation', 'couponxxl' ) );

		add_theme_support( 'post-thumbnails' );

		set_post_thumbnail_size( 960, 540, true );
		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'couponxxl-shop-logo', 150 );
			add_image_size( 'couponxxl-shop-logo-widget', 90 );
			add_image_size( 'couponxxl-offer-box', 284, 160, true );
			add_image_size( 'couponxxl-blog-listing', 465, 262, true );
			add_image_size( 'couponxxl-slider-thumb', 200, 118, true );
			add_image_size( 'couponxxl-category-box', 45, 45, true );
			add_image_size( 'couponxxl-staff-box', 75, 75, true );
			add_image_size( 'couponxxl-popup-box', 125, 93, true );
			add_image_size( 'couponxxl-big-slider', 640, 520, true );
			add_image_size( 'couponxxl-featured-slider', 660, 404, true );
		}
		add_theme_support( 'custom-header' );
		add_theme_support( 'custom-background' );
		add_editor_style();
	}

	add_action( 'after_setup_theme', 'couponxxl_setup' );
}

/*
Load google fonts properly
*/
if ( ! function_exists( 'couponxxl_load_google_font' ) ) {
	function couponxxl_load_google_font( $font_family ) {
		$font_url = '';
		if ( 'off' !== _x( 'on', 'Google font: on or off', 'studio' ) ) {
			$font_url = add_query_arg( 'family', urlencode( $font_family . ':100,300,400,600,700,900,100italic,300italic,400italic,700italic,900italic' ), "//fonts.googleapis.com/css" );
		}

		return $font_url;
	}
}

/*
Add required scripts and styles
*/
if ( ! function_exists( 'couponxxl_scripts_styles' ) ) {
	function couponxxl_scripts_styles() {
		/* ENQUEUE STYLES */
		wp_enqueue_style( 'couponxxl-pline', get_template_directory_uri() . '/css/pline.css' );
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
		wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/css/magnific-popup.css' );
		/* BOOTSTRAP */
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css' );
		if ( get_page_template_slug() == 'page-tpl_profile.php' ) {
			/* BOOTSTRAP TABLES */
			wp_enqueue_style( 'bootstrap-table', get_template_directory_uri() . '/css/bootstrap-table.min.css' );
			/* DATE TIME PICKER */
			wp_enqueue_style( 'datetimepicker', get_template_directory_uri() . '/css/jquery.datetimepicker.css' );
		}

		$theme_font = couponxxl_get_option( 'theme_font' );
		if ( ! empty( $theme_font ) ) {
			wp_enqueue_style( 'couponxxl-theme-font', couponxxl_load_google_font( $theme_font ), array(), '1.0.0' );
		}

		$protocol = is_ssl() ? 'https' : 'http';
		wp_enqueue_script( 'googlemap', $protocol . '://maps.googleapis.com/maps/api/js?key=' . couponxxl_get_option( 'map_api' ) . '&libraries=places&sensor=false', array( 'jquery' ), false, true );

		/* BOOTSTRAP */
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'bootstrap-multilevel', get_template_directory_uri() . '/js/bootstrap-dropdown-multilevel.js', array( 'jquery' ), false, true );

		if ( get_page_template_slug() == 'page-tpl_profile.php' ) {
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			/* BOOTSTRAP TABLES */
			wp_enqueue_script( 'bootstrap-table', get_template_directory_uri() . '/js/bootstrap-table.min.js', array( 'jquery' ), false, true );
			wp_localize_script( 'bootstrap-table', 'couponxxl_tabe_strings', array(
				'search'     => esc_html__( 'Search', 'couponxxl' ),
				'no_records' => esc_html__( 'No matching records found', 'couponxxl' ),
			) );
			/* DATE TIME PICKER */
			wp_enqueue_script( 'datetimepicker', get_template_directory_uri() . '/js/jquery.datetimepicker.js', array( 'jquery' ), false, true );

			/* IMAGE UPLOADS */
			wp_enqueue_media();
			wp_enqueue_script( 'couponxxl-image-uploads', get_template_directory_uri() . '/js/front-uploader.js', array( 'jquery' ), false, true );

			/* STEPS */
			wp_enqueue_style( 'couponxxl-steps', get_template_directory_uri() . '/css/jquery.steps.css' );
			wp_enqueue_script( 'couponxxl-steps', get_template_directory_uri() . '/js/jquery.steps.min.js', array( 'jquery' ), false, true );

			wp_enqueue_script( 'couponxxl-location-managemenet', get_template_directory_uri() . '/js/location-management.js', array( 'jquery' ), false, true );
		}


		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		wp_enqueue_script( 'zeroclipboard', get_template_directory_uri() . '/js/ZeroClipboard.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'responsive-slides', get_template_directory_uri() . '/js/responsiveslides.min.js', array( 'jquery' ), false, true );

		wp_enqueue_script( 'couponxxl-countdown', get_template_directory_uri() . '/js/countdown.js', array( 'jquery' ), false, true );

		wp_enqueue_script( 'markerclusterer', get_template_directory_uri() . '/js/markerclusterer_compiled.js', array( 'jquery' ), false, true );


		/* OWL */
		wp_enqueue_style( 'owl.carousel', get_template_directory_uri() . '/css/owl.carousel.css' );
		wp_enqueue_script( 'owl.carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array( 'jquery' ), false, true );

		wp_enqueue_script( 'infobox', get_template_directory_uri() . '/js/infobox.js', array( 'jquery' ), false, true );

		if ( get_page_template_slug() == 'page-tpl_all_categories.php' || get_page_template_slug() == 'page-tpl_all_locations.php' || is_home() ) {
			wp_enqueue_script( 'imagesloaded', get_template_directory_uri() . '/js/imagesloaded.js', array( 'jquery' ), false, true );
			wp_enqueue_script( 'masonry', get_template_directory_uri() . '/js/masonry.js', array( 'jquery' ), false, true );
		}

		wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), false, true );

		wp_enqueue_script( 'couponxxl-custom', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), false, true );
		wp_localize_script( 'couponxxl-custom', 'couponxxl_data', array(
			'url'                  => get_template_directory_uri(),
			'email_friend'         => esc_html__( 'Friend\'s email address', 'couponxxl' ),
			'steps_cancel'         => esc_html__( 'Cancel', 'couponxxl' ),
			'steps_finish'         => esc_html__( 'Finish', 'couponxxl' ),
			'steps_next'           => esc_html__( 'Next', 'couponxxl' ),
			'steps_previous'       => esc_html__( 'Previous', 'couponxxl' ),
			'steps_loading'        => esc_html__( 'Loading...', 'couponxxl' ),
			'marker_icon'          => couponxxl_get_marker_image(),
			'contact_map_max_zoom' => couponxxl_get_option( 'contact_map_max_zoom' ),
			'in_cart_title'        => esc_html__( 'Added To Cart', 'couponxxl' ),
			'quick_search_chars'   => couponxxl_get_option( 'quick_search_chars' ),
			'listing_map_zoom'     => couponxxl_get_option( 'listing_map_zoom' ),
			'deal_single_zoom'     => couponxxl_get_option( 'deal_single_zoom' )
		) );


	}

	add_action( 'wp_enqueue_scripts', 'couponxxl_scripts_styles', 2 );
}

if ( ! function_exists( 'couponxxl_load_color_schema' ) ) {
	function couponxxl_load_color_schema() {
		wp_enqueue_style( 'couponxxl-custom-style', get_template_directory_uri() . '/css/customize.css' );
		wp_enqueue_style( 'couponxxl-style', get_stylesheet_uri(), array() );
	}

	add_action( 'wp_enqueue_scripts', 'couponxxl_load_color_schema', 4 );
}
/*
Get image url for the marker from theme option
*/
if ( ! function_exists( 'couponxxl_get_marker_image' ) ) {
	function couponxxl_get_marker_image() {
		$marker_icon = couponxxl_get_option( 'marker_icon' );
		if ( ! empty( $marker_icon['url'] ) ) {
			return $marker_icon['url'];
		} else {
			return '';
		}
	}
}

/*
Enqueue script and styles in the backend
*/
if ( ! function_exists( 'couponxxl_admin_resources' ) ) {
	function couponxxl_admin_resources() {
		global $post, $screen;;
		$screen = get_current_screen();
		wp_enqueue_style( 'couponxxl-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
		if ( ! empty( $screen->post_type ) && $screen->post_type == 'store' ) {
			$protocol = is_ssl() ? 'https' : 'http';
			wp_enqueue_script( 'couponxxl-googlemap', $protocol . '://maps.googleapis.com/maps/api/js?key=' . couponxxl_get_option( 'map_api' ) . '&libraries=places&sensor=false', false, false, true );
		}

		wp_enqueue_script( 'couponxxl-admin', get_template_directory_uri() . '/js/admin.js', false, false, true );
		wp_enqueue_script( 'couponxxl-admin-location-managemenet', get_template_directory_uri() . '/js/location-management.js', false, false, true );
		wp_enqueue_style( 'couponxxl-admin', get_template_directory_uri() . '/css/admin.css' );

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-dialog' );

		wp_enqueue_style( 'couponxxl-jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_style( 'couponxxl-shortcodes-style', get_template_directory_uri() . '/css/admin.css' );
		wp_enqueue_script( 'couponxxl-multidropdown', get_template_directory_uri() . '/js/multidropdown.js', false, false, true );
		wp_enqueue_media();
	}

	add_action( 'admin_enqueue_scripts', 'couponxxl_admin_resources' );
}

/*
Format date and time
*/
if ( ! function_exists( 'couponxxl_format_post_date' ) ) {
	function couponxxl_format_post_date( $date, $format ) {
		return date( $format, strtotime( $date ) );
	}
}

/*
Add ajaxurl to the frontend
*/
if ( ! function_exists( 'couponxxl_custom_head' ) ) {
	function couponxxl_custom_head() {
		echo '<script type="text/javascript">var ajaxurl = \'' . admin_url( 'admin-ajax.php' ) . '\';</script>';
	}

	add_action( 'wp_head', 'couponxxl_custom_head' );
}

/*
Gab images from the smeta gallry
*/
if ( ! function_exists( 'couponxxl_smeta_images' ) ) {
	function couponxxl_smeta_images( $meta_key, $post_id, $default ) {
		if ( class_exists( 'SM_Frontend' ) ) {
			global $sm;

			return $result = $sm->sm_get_meta( $meta_key, $post_id );
		} else {
			return $default;
		}
	}
}

/*
Get posts of the custom list
*/
if ( ! function_exists( 'couponxxl_get_custom_list' ) ) {
	function couponxxl_get_custom_list( $post_type, $args = array(), $orderby = '', $direction = 'right' ) {
		$post_array = array();
		$args       = array( 'post_type' => $post_type, 'post_status' => 'publish', 'posts_per_page' => - 1 ) + $args;
		if ( ! empty( $orderby ) ) {
			$args['orderby'] = $orderby;
			$args['order']   = 'ASC';
		}
		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			if ( $direction == 'right' ) {
				$post_array[ $post->ID ] = $post->post_title;
			} else {
				$post_array[ $post->post_title ] = $post->ID;
			}
		}

		return $post_array;
	}
}

/*
Get terms of custom list
*/
if ( ! function_exists( 'couponxxl_get_custom_tax_list' ) ) {
	function couponxxl_get_custom_tax_list( $taxonomy, $direction = 'right' ) {
		$terms     = get_terms( $taxonomy, array( 'hide_empty' => false ) );
		$term_list = array();
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( $direction == 'right' ) {
					$term_list[ $term->slug ] = $term->name;
				} else {
					$term_list[ $term->name ] = $term->slug;
				}
			}
		}

		return $term_list;
	}
}

/*
Get users for the select button
*/
if ( ! function_exists( 'couponxxl_get_users_select' ) ) {
	function couponxxl_get_users_select() {
		$users = get_users( array(
			'orderby' => 'nicename'
		) );

		$users_array = array();

		foreach ( $users as $user ) {
			$user_data                = get_userdata( $user->ID );
			$users_array[ $user->ID ] = $user_data->user_nicename;
		}

		return $users_array;
	}
}

/*
Count specific post type
*/
if ( ! function_exists( 'couponxxl_count_post_type' ) ) {
	function couponxxl_count_post_type( $post_type, $args = array() ) {
		$args  = array( 'post_type' => $post_type, 'post_status' => 'publish', 'posts_per_page' => - 1 ) + $args;
		$posts = get_posts( $args );
		wp_reset_postdata();

		return count( $posts );
	}
}

/*
Create button for the coupons
*/
if ( ! function_exists( 'couponxxl_coupon_button' ) ) {
	function couponxxl_coupon_button( $post_id = '', $is_shortcode = false ) {
		global $couponxxl_slugs;
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$coupon_type    = get_post_meta( $post_id, 'coupon_type', true );
		$affiliate_link = get_post_meta( $post_id, 'coupon_link', true );
		$coupon_link    = 'javascript:;';
		$target         = '';
		if ( ! empty( $affiliate_link ) ) {
			$args = array();
			if ( ! empty( $_GET ) ) {
				foreach ( $_GET as $key => $value ) {
					if ( is_array( $value ) && ! empty( $value ) ) {
						if ( get_option( 'permalink_structure' ) ) {
							$args[ $key ] = join( '--', $value );
						} else {
							if ( ! empty( $value ) ) {
								foreach ( $value as $val ) {
									$args[ $key . '[]' ] = $val;
								}
							}
						}
					} else if ( ! empty( $value ) ) {
						$args[ $key ] = $value;
					}

				}
			}
			$coupon_link = esc_url( couponxxl_append_query_string( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ), $args, array( 'all' ) ) ) . '#cpn-' . $post_id;
			$target      = 'target="_blank"';
		}

		$button = '<a href="' . $coupon_link . '" data-affiliate="' . esc_url( $affiliate_link ) . '" data-offer_id="' . esc_attr( $post_id ) . '" class="btn show-code" ' . $target . '>';

		switch ( $coupon_type ) {
			case 'code':
				$button .= '
				<i class="pline-scissors coupon-type"></i>
				' . esc_html__( 'Show Code', 'couponxxl' );
				break;
			case 'sale':
				$button .= '
				<i class="pline-chain-alt coupon-type"></i>
				' . esc_html__( 'Check Sale', 'couponxxl' );
				break;
			case 'printable':
				$button .= '
				<i class="pline-notes coupon-type"></i>
				' . esc_html__( 'Print Coupon', 'couponxxl' );
				break;
		}

		$button .= '</a>';

		return $button;
	}
}

/*
Get post meta from the specific table
*/
if ( ! function_exists( 'couponxxl_get_post_meta' ) ) {
	function couponxxl_get_post_meta( $post_id, $field, $single = false ) {
		global $wpdb;
		$value = array();
		$metas = $wpdb->get_results( $wpdb->prepare( "SELECT " . esc_sql( $field ) . " FROM {$wpdb->prefix}offers WHERE post_id = %d LIMIT 1", $post_id ) );
		if ( ! empty( $metas[0] ) ) {
			$value = $metas[0];
			if ( $single ) {
				$value = $value->$field;
			}
		} else {
			if ( $single ) {
				$value = '';
			}
		}

		return maybe_unserialize( $value );
	}
}

/*
Update meta values to the specific meta table
*/
if ( ! function_exists( 'couponxxl_add_post_meta' ) ) {
	function couponxxl_add_post_meta( $meta_value, $meta_key, $post_id ) {
		global $wpdb;

		$defaults = array(
			'post_id'         => $post_id,
			'offer_type'      => '',
			'offer_start'     => '',
			'offer_expire'    => '',
			'offer_in_slider' => ''
		);

		$vals = array_merge( $defaults, array( $meta_key => $meta_value ) );

		$wpdb->insert( $wpdb->prefix . 'offers', $vals, array(
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
		) );
	}
}

/*
Update meta values to the specific meta table
*/
if ( ! function_exists( 'couponxxl_update_post_meta' ) ) {
	function couponxxl_update_post_meta( $meta_value, $meta_key, $post_id ) {
		global $wpdb;
		if ( ! empty( $meta_value ) ) {
			if ( is_array( $meta_value ) ) {
				$meta_value = array_shift( $meta_value );
			}

			$result = $wpdb->query( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}offers WHERE post_id = %d", $post_id ) );
			if ( $result > 0 ) {
				$wpdb->update( $wpdb->prefix . 'offers', array(
					$meta_key => $meta_value
				), array(
					'post_id' => $post_id
				), array(
					'%s',
				), array(
					'%d',
				) );
			} else {
				couponxxl_add_post_meta( $meta_value, $meta_key, $post_id );
			}
		}
	}
}


if ( ! function_exists( 'couponxxl_check_offer_availability' ) ) {
	function couponxxl_check_offer_availability( $offer_id, $items = '' ) {
		global $wpdb;
		if ( empty( $items ) ) {
			$items = get_post_meta( $offer_id, 'deal_items', true );
		}

		$generated_vouchers = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS count FROM {$wpdb->prefix}order_items AS order_items LEFT JOIN {$wpdb->prefix}vouchers AS vouchers ON order_items.item_id = vouchers.item_id WHERE order_items.offer_id = %d", $offer_id ) );

		if ( $items > $generated_vouchers ) {
			return '1';
		} else {
			return '0';
		}
	}
}

/*
Check on submit if it coupon or not
*/
if ( ! function_exists( 'couponxxl_check_for_deal_items' ) ) {
	function couponxxl_check_for_deal_items( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! empty( $_POST['offer_type'] ) ) {
			$offer_type = is_array( $_POST['offer_type'] ) ? array_shift( $_POST['offer_type'] ) : $_POST['offer_type'];
			if ( $offer_type == 'coupon' ) {
				couponxxl_update_post_meta( '1', 'offer_has_items', $post_id );
			} else if ( $offer_type == 'deal' ) {
				$offer_has_items = empty( $_POST['deal_items'] ) ? '0' : couponxxl_check_offer_availability( $post_id, is_array( $_POST['deal_items'] ) ? array_shift( $_POST['deal_items'] ) : $_POST['deal_items'] );
				couponxxl_update_post_meta( $offer_has_items, 'offer_has_items', $post_id );
			}
		}
	}

	add_action( 'save_post', 'couponxxl_check_for_deal_items', 20 );
}


/*
Add custom meta fields to the theme
*/
if ( ! function_exists( 'couponxxl_custom_meta_boxes' ) ) {
	function couponxxl_custom_meta_boxes() {
		/* common fields for the deals and for the coupons */
		$offer_meta   = array(
			array(
				'id'              => 'offer_type',
				'name'            => esc_html__( 'Offer type', 'couponxxl' ),
				'type'            => 'select',
				'values_callback' => 'couponxxl_get_post_meta',
				'save_callback'   => 'couponxxl_update_post_meta',
				'options'         => array(
					'coupon' => esc_html__( 'Coupon', 'couponxxl' ),
					'deal'   => esc_html__( 'Deal', 'couponxxl' )
				),
				'desc'            => esc_html__( 'Choose type of offer between coupon and deal.', 'couponxxl' )
			),
			array(
				'id'              => 'offer_start',
				'name'            => esc_html__( 'Start date', 'couponxxl' ),
				'type'            => 'datetime_unix',
				'values_callback' => 'couponxxl_get_post_meta',
				'save_callback'   => 'couponxxl_update_post_meta',
				'desc'            => esc_html__( 'Set start date and time for the offer.', 'couponxxl' )
			),
			array(
				'id'              => 'offer_expire',
				'name'            => esc_html__( 'Expire date', 'couponxxl' ),
				'type'            => 'datetime_unix',
				'values_callback' => 'couponxxl_get_post_meta',
				'save_callback'   => 'couponxxl_update_post_meta',
				'desc'            => esc_html__( 'Set expire date and time for the offer or leave empty for unlimited last.', 'couponxxl' )
			),
			array(
				'id'              => 'offer_in_slider',
				'name'            => esc_html__( 'Offer In Slider', 'carell' ),
				'type'            => 'select',
				'values_callback' => 'couponxxl_get_post_meta',
				'save_callback'   => 'couponxxl_update_post_meta',
				'options'         => array(
					'no'  => esc_html__( 'No', 'couponxxl' ),
					'yes' => esc_html__( 'Yes', 'couponxxl' )
				),
				'desc'            => esc_html__( 'Put this offer in the slider on the listing pages.', 'couponxxl' )
			),
			array(
				'id'   => 'offer_thumbs_up',
				'name' => esc_html__( 'Thumbs Up', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Number of thumbs up for this offer. This is auto populated by user action.', 'couponxxl' )
			),
			array(
				'id'   => 'offer_thumbs_down',
				'name' => esc_html__( 'Thumbs Down', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Number of thumbs down for this offer. This is auto populated by user action.', 'couponxxl' )
			),
			array(
				'id'              => 'offer_clicks',
				'name'            => esc_html__( 'Click', 'couponxxl' ),
				'type'            => 'text',
				'values_callback' => 'couponxxl_get_post_meta',
				'save_callback'   => 'couponxxl_update_post_meta',
				'desc'            => esc_html__( 'Number of clicks on single page. This is auto populated on frontend.', 'couponxxl' )
			),
			array(
				'id'   => 'offer_views',
				'name' => esc_html__( 'Views', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Number of views in the listing pages.', 'couponxxl' )
			),
		);
		$meta_boxes[] = array(
			'title'  => esc_html__( 'Offer Common Data', 'couponxxl' ),
			'pages'  => 'offer',
			'fields' => $offer_meta,
		);

		$offer_requests = array(
			array(
				'id'   => 'offer_new_category',
				'name' => esc_html__( 'New category request', 'couponxxl' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'Request for new category is listed here.', 'couponxxl' )
			),
		);
		$meta_boxes[]   = array(
			'title'  => esc_html__( 'Offer Requests', 'couponxxl' ),
			'pages'  => 'offer',
			'fields' => $offer_requests,
		);


		/* custom meta fields for the offer as coupon */
		$coupon_meta  = array(
			array(
				'id'      => 'coupon_type',
				'name'    => esc_html__( 'Coupon type', 'couponxxl' ),
				'type'    => 'select',
				'options' => array(
					'code'      => esc_html__( 'Coupon With Code', 'couponxxl' ),
					'sale'      => esc_html__( 'Coupon For Sale', 'couponxxl' ),
					'printable' => esc_html__( 'Printable Coupon', 'couponxxl' )
				),
				'desc'    => esc_html__( 'Choose type of the coupon.', 'couponxxl' )
			),
			array(
				'id'   => 'coupon_code',
				'name' => esc_html__( 'Coupon Code', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input coupon code.', 'couponxxl' )
			),
			array(
				'id'   => 'coupon_sale',
				'name' => esc_html__( 'Coupon Sale Link', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input sale link.', 'couponxxl' )
			),
			array(
				'id'   => 'coupon_image',
				'name' => esc_html__( 'Coupon Image', 'couponxxl' ),
				'type' => 'image',
				'desc' => esc_html__( 'Upload printable coupon image.', 'couponxxl' )
			),
			array(
				'id'   => 'coupon_link',
				'name' => esc_html__( 'Coupon Affiliate Link', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input affiliate link which will be opened once the coupon is clicked.', 'couponxxl' )
			),

		);
		$meta_boxes[] = array(
			'title'  => esc_html__( 'Coupon Information', 'couponxxl' ),
			'pages'  => 'offer',
			'fields' => $coupon_meta,
		);

		$deal_meta    = array(
			array(
				'id'   => 'deal_items',
				'name' => esc_html__( 'Deal Items', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input number of deal items or services which will be available for purchase. If this is not set deal will not be displayed.', 'couponxxl' )
			),
			array(
				'id'   => 'deal_item_vouchers',
				'name' => esc_html__( 'Deal Items Vouchers', 'couponxxl' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'If you want to serve predefined vouchers instead of random generated ones, input them here one in a row and make sure that you have same amount of these vouchers as the number of items.', 'couponxxl' )
			),
			array(
				'id'   => 'deal_min_sales',
				'name' => esc_html__( 'Deal Minimum Items', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'minimum number of sales in order for deal to be valid.', 'couponxxl' )
			),
			array(
				'id'   => 'deal_price',
				'name' => esc_html__( 'Deal Price', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input real price of the deal without currency simbol. If this value is decimal than use . as decimal separator and max two decimal places.', 'couponxxl' )
			),
			array(
				'id'   => 'deal_sale_price',
				'name' => esc_html__( 'Deal Sale Price', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input sale price of the deal without currency simbol ( auto updated by the percentage change in the next field ). If this value is decimal than use . as decimal separator and max two decimal places.', 'couponxxl' )
			),
			array(
				'id'   => 'deal_voucher_expire',
				'name' => esc_html__( 'Deal Vouchers Expire Date', 'couponxxl' ),
				'type' => 'datetime_unix',
				'desc' => esc_html__( 'Set expire date and time for vouchers generated after purchase or leave empty for unlimited last ( How much time voucher is valid after purchase? ).', 'couponxxl' )
			),
			array(
				'id'         => 'deal_images',
				'name'       => esc_html__( 'Deal Images', 'couponxxl' ),
				'type'       => 'image',
				'repeatable' => 1,
				'desc'       => esc_html__( 'Choose images for the deal. Drag and drop to change their order.', 'couponxxl' ),
			),
			array(
				'id'      => 'deal_type',
				'name'    => esc_html__( 'Select deal type', 'couponxxl' ),
				'type'    => 'select',
				'options' => array(
					'shared'     => esc_html__( 'Website Offer', 'couponxxl' ),
					'not_shared' => esc_html__( 'Store Offer', 'couonxl' )
				),
				'desc'    => esc_html__( 'Website Offer - Sell discounted products and services trough website.', 'couponxxl' ) . '<br />' . esc_html__( 'Store Offer - Sell discounted products and services trough your store.', 'couponxxl' ),
			),
			array(
				'id'   => 'deal_link',
				'name' => esc_html__( 'Deal External Link', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input external link for the deal in order to avoid payment over this website.', 'couponxxl' ),
			),
		);
		$meta_boxes[] = array(
			'title'  => esc_html__( 'Deal Information', 'couponxxl' ),
			'pages'  => 'offer',
			'fields' => $deal_meta,
		);


		/* custom page for offers */
		 	add_action( 'admin_menu', 'my_admin_menu' );
			function my_admin_menu() {
			    add_menu_page( 'Offers Push', 'Offers Push', 'manage_options', 'duplicate-offers.php');
			}

		/* store custom meta fields */
		 
		 /* Broadcast Notification */
		 	add_action( 'admin_menu', 'my_admin_menu_broadcast' );
			function my_admin_menu_broadcast() {
			    add_menu_page( 'Broadcast Notification', 'Broadcast Notification', 'manage_options', 'broadcast-notification.php');
			}

		/* store custom meta fields */


		$store_meta   = array(
			array(
				'id'   => 'store_link',
				'name' => esc_html__( 'Store Link', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input link to the store site.', 'couponxxl' )
			),
			array(
				'id'   => 'store_facebook',
				'name' => esc_html__( 'Store Facebook Page Link', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input link to the store facebok page.', 'couponxxl' )
			),
			array(
				'id'   => 'store_twitter',
				'name' => esc_html__( 'Store Twitter Page Link', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input link to the store twitter page.', 'couponxxl' )
			),
			array(
				'id'   => 'store_google',
				'name' => esc_html__( 'Store Google+ Page Link', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input link to the store google+ page.', 'couponxxl' )
			),
			array(
				'id'   => 'store_instagram',
				'name' => esc_html__( 'Store Instagram Link', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input link to the store instagram page.', 'couponxxl' )
			),
			array(
				'id'   => 'store_rss',
				'name' => esc_html__( 'Store RSS Link', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input link to the store rss page.', 'couponxxl' )
			),
		);
		$meta_boxes[] = array(
			'title'  => esc_html__( 'Store information', 'couponxxl' ),
			'pages'  => 'store',
			'fields' => $store_meta,
		);

		/* ORDER META FIELDS */
		$order_meta   = array(
			array(
				'id'      => 'order_buyer_id',
				'name'    => esc_html__( 'Order Buyer', 'couponxxl' ),
				'type'    => 'select',
				'options' => couponxxl_get_users_select(),
				'desc'    => esc_html__( 'Select order buyer ( this is auto populated on voucher generation ).', 'couponxxl' )
			),
			array(
				'id'      => 'order_status',
				'name'    => esc_html__( 'Order Status', 'couponxxl' ),
				'type'    => 'select',
				'options' => array(
					'not_paid'        => esc_html__( 'Not Paid', 'couponxxl' ),
					'pending_payment' => esc_html__( 'Pending Payment', 'couponxxl' ),
					'paid'            => esc_html__( 'Paid', 'couponxxl' ),
				),
				'desc'    => esc_html__( 'Order status ( this is auto populated on purchase ).', 'couponxxl' )
			),
			array(
				'id'   => 'order_total',
				'name' => esc_html__( 'Order Total', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Total value of the order ( this is auto populated on purchase ).', 'couponxxl' )
			),
			array(
				'id'   => 'order_owner_share',
				'name' => esc_html__( 'Order Owner Share', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Total share of order which belongs to you ( this is auto populated on purchase ).', 'couponxxl' )
			),
			array(
				'id'      => 'order_gateway',
				'name'    => esc_html__( 'Order Gateway', 'couponxxl' ),
				'type'    => 'select',
				'options' => couponxxl_available_gateways(),
				'desc'    => esc_html__( 'Gateway used to pay for the order ( this is auto populated on purchase ).', 'couponxxl' )
			),
		);
		$meta_boxes[] = array(
			'title'  => esc_html__( 'Order Basic Information', 'couponxxl' ),
			'pages'  => 'ord',
			'fields' => $order_meta,
		);

		$post_meta_standard = array(
			array(
				'id'   => 'iframe_standard',
				'name' => esc_html__( 'Embed URL', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input custom URL which will be embeded as the blog post media.', 'couponxxl' )
			),
		);

		$meta_boxes[] = array(
			'title'  => esc_html__( 'Standard Post Information', 'couponxxl' ),
			'pages'  => 'post',
			'fields' => $post_meta_standard,
		);

		$post_meta_gallery = array(
			array(
				'id'         => 'gallery_images',
				'name'       => esc_html__( 'Gallery Images', 'couponxxl' ),
				'type'       => 'image',
				'repeatable' => 1,
				'desc'       => esc_html__( 'Add images for the gallery post format. Drag and drop to change their order.', 'couponxxl' )
			)
		);

		$meta_boxes[] = array(
			'title'  => esc_html__( 'Gallery Post Information', 'couponxxl' ),
			'pages'  => 'post',
			'fields' => $post_meta_gallery,
		);


		$post_meta_audio = array(
			array(
				'id'   => 'iframe_audio',
				'name' => esc_html__( 'Audio URL', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input url to the audio source which will be media for the audio post format.', 'couponxxl' )
			),

			array(
				'id'      => 'audio_type',
				'name'    => esc_html__( 'Audio Type', 'couponxxl' ),
				'type'    => 'select',
				'options' => array(
					'embed'  => esc_html__( 'Embed', 'couponxxl' ),
					'direct' => esc_html__( 'Direct Link', 'couponxxl' )
				),
				'desc'    => esc_html__( 'Select format of the audio URL ( Direct Link - for mp3, Embed - for the links from SoundCloud, MixCloud,... ).', 'couponxxl' )
			),
		);

		$meta_boxes[] = array(
			'title'  => esc_html__( 'Audio Post Information', 'couponxxl' ),
			'pages'  => 'post',
			'fields' => $post_meta_audio,
		);

		$post_meta_video = array(
			array(
				'id'   => 'video',
				'name' => esc_html__( 'Video URL', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input url to the video source which will be media for the audio post format.', 'couponxxl' )
			),
			array(
				'id'      => 'video_type',
				'name'    => esc_html__( 'Video Type', 'couponxxl' ),
				'type'    => 'select',
				'options' => array(
					'remote' => esc_html__( 'Embed', 'couponxxl' ),
					'self'   => esc_html__( 'Direct Link', 'couponxxl' ),
				),
				'desc'    => esc_html__( 'Select format of the video URL ( Direct Link - for ogg, mp4..., Embed - for the links from YouTube, Vimeo,... ).', 'couponxxl' )
			),
		);

		$meta_boxes[] = array(
			'title'  => esc_html__( 'Video Post Information', 'couponxxl' ),
			'pages'  => 'post',
			'fields' => $post_meta_video,
		);

		$post_meta_quote = array(
			array(
				'id'   => 'blockquote',
				'name' => esc_html__( 'Input Quotation', 'couponxxl' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'Input quote as blog media for the quote post format.', 'couponxxl' )
			),
			array(
				'id'   => 'cite',
				'name' => esc_html__( 'Input Quoted Person\'s Name', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input quoted person\'s name for the quote post format.', 'couponxxl' )
			),
		);

		$meta_boxes[] = array(
			'title'  => esc_html__( 'Quote Post Information', 'couponxxl' ),
			'pages'  => 'post',
			'fields' => $post_meta_quote,
		);

		$post_meta_link = array(
			array(
				'id'   => 'link',
				'name' => esc_html__( 'Input Link', 'couponxxl' ),
				'type' => 'text',
				'desc' => esc_html__( 'Input link as blog media for the link post format.', 'couponxxl' )
			),
		);

		$meta_boxes[] = array(
			'title'  => esc_html__( 'Link Post Information', 'couponxxl' ),
			'pages'  => 'post',
			'fields' => $post_meta_link,
		);

		return $meta_boxes;
	}

	add_filter( 'sm_meta_boxes', 'couponxxl_custom_meta_boxes' );
}

/*
Get list of the vailable payment gateways
*/
if ( ! function_exists( 'couponxxl_available_gateways' ) ) {
	function couponxxl_available_gateways() {
		global $COUPONXXL_GATEWAYS;
		$list = array();
		if ( ! empty( $COUPONXXL_GATEWAYS ) ) {
			foreach ( $COUPONXXL_GATEWAYS as $gateway ) {
				$list[ $gateway['slug'] ] = $gateway['name'];
			}
		}

		return $list;
	}
}

/*
	Transform color form hex to rgb
*/
if ( ! function_exists( 'couponxxl_hex2rgb' ) ) {
	function couponxxl_hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );

		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		return $r . ", " . $g . ", " . $b;
	}
}


/*
Check if the offer expired
*/
if ( ! function_exists( 'couponxxl_is_expired' ) ) {
	function couponxxl_is_expired( $expire_timestamp ) {
		if ( current_time( 'timestamp' ) > $expire_timestamp ) {
			return true;
		} else {
			return false;
		}
	}
}

/*
Registe rclick on the offer
*/
if ( ! function_exists( 'couponxxl_register_click' ) ) {
	function couponxxl_register_click() {
		$post_id      = get_the_ID();
		$offer_clicks = couponxxl_get_post_meta( $post_id, 'offer_clicks', true );
		$offer_clicks ++;
		couponxxl_update_post_meta( $offer_clicks, 'offer_clicks', $post_id );
	}
}


/*
Custom walker class for the menu
*/
if ( ! class_exists( 'couponxxl_walker' ) ) {
	class couponxxl_walker extends Walker_Nav_Menu {

		/**
		 * @see Walker::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			$output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\">\n";
		}

		/**
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param int $current_page Menu item ID.
		 * @param object $args
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			/**
			 * Dividers, Headers or Disabled
			 * =============================
			 * Determine whether the item is a Divider, Header, Disabled or regular
			 * menu item. To prevent errors we use the strcasecmp() function to so a
			 * comparison that is not case sensitive. The strcasecmp() function returns
			 * a 0 if the strings are equal.
			 */
			if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} else if ( strcasecmp( $item->title, 'divider' ) == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} else if ( strcasecmp( $item->attr_title, 'dropdown-header' ) == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
			} else if ( strcasecmp( $item->attr_title, 'disabled' ) == 0 ) {
				$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
			} else {

				$mega_menu_custom = get_post_meta( $item->ID, 'mega-menu-set', true );

				$class_names = $value = '';
				$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
				if ( ! empty( $mega_menu_custom ) ) {
					$classes[] = 'mega_menu_li';
				}
				$classes[]   = 'menu-item-' . $item->ID;
				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

				if ( $args->has_children ) {
					$class_names .= ' dropdown';
				}

				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
				$id          = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
				$id          = $id ? ' id="' . esc_attr( $id ) . '"' : '';

				$output .= $indent . '<li' . $id . $value . $class_names . '>';

				$atts           = array();
				$atts['title']  = ! empty( $item->title ) ? $item->title : '';
				$atts['target'] = ! empty( $item->target ) ? $item->target : '';
				$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

				// If item has_children add atts to a.
				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
				if ( $args->has_children ) {
					$atts['data-toggle']   = 'dropdown';
					$atts['class']         = 'dropdown-toggle';
					$atts['data-hover']    = 'dropdown';
					$atts['aria-haspopup'] = 'true';
				}

				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}

				$item_output = $args->before;

				/*
			* Glyphicons
			* ===========
			* Since the the menu item is NOT a Divider or Header we check the see
			* if there is a value in the attr_title property. If the attr_title
			* property is NOT null we apply it as the class name for the glyphicon.
			*/

				$item_output .= '<a' . $attributes . '>';
				if ( ! empty( $item->attr_title ) ) {
					$item_output .= '<div class="menu-tooltip">' . $item->attr_title . '</div>';
				}

				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				if ( ! empty( $mega_menu_custom ) ) {
					$registered_widgets = wp_get_sidebars_widgets();
					$count              = count( $registered_widgets[ $mega_menu_custom ] );
					$item_output .= ' <i class="fa fa-angle-down"></i>';
					$item_output .= '</a>';
					$mega_menu_min_height = couponxxl_get_option( 'mega_menu_min_height' );
					$style                = '';
					if ( ! empty( $mega_menu_min_height ) ) {
						$style = 'style="height: ' . esc_attr( $mega_menu_min_height ) . '"';
					}
					$item_output .= '<ul class="list-unstyled mega_menu col-' . $count . '" ' . $style . '>';
					ob_start();
					if ( is_active_sidebar( $mega_menu_custom ) ) {
						dynamic_sidebar( $mega_menu_custom );
					}
					$item_output .= ob_get_contents();
					ob_end_clean();
					$item_output .= '</ul>';
				} else {
					if ( $args->has_children && 0 === $depth ) {
						$item_output .= ' <i class="fa fa-angle-down"></i>';
					}
					$item_output .= '</a>';
				}
				$item_output .= $args->after;

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
		}

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth.
		 *
		 * This method shouldn't be called directly, use the walk() method instead.
		 *
		 * @see Walker::start_el()
		 * @since 2.5.0
		 *
		 * @param object $element Data object
		 * @param array $children_elements List of elements to continue traversing.
		 * @param int $max_depth Max depth to traverse.
		 * @param int $depth Depth of current element.
		 * @param array $args
		 * @param string $output Passed by reference. Used to append additional content.
		 *
		 * @return null Null on failure with no changes to parameters.
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return;
			}

			$id_field = $this->db_fields['id'];

			// Display this element.
			if ( is_object( $args[0] ) ) {
				$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
			}

			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

		/**
		 * Menu Fallback
		 * =============
		 * If this function is assigned to the wp_nav_menu's fallback_cb variable
		 * and a manu has not been assigned to the theme location in the WordPress
		 * menu manager the function with display nothing to a non-logged in user,
		 * and will add a link to the WordPress menu manager if logged in as an admin.
		 *
		 * @param array $args passed from the wp_nav_menu function.
		 *
		 */
		public static function fallback( $args ) {
			if ( current_user_can( 'manage_options' ) ) {

				extract( $args );

				$fb_output = null;

				if ( $container ) {
					$fb_output = '<' . $container;

					if ( $container_id ) {
						$fb_output .= ' id="' . $container_id . '"';
					}

					if ( $container_class ) {
						$fb_output .= ' class="' . $container_class . '"';
					}

					$fb_output .= '>';
				}

				$fb_output .= '<ul';

				if ( $menu_id ) {
					$fb_output .= ' id="' . $menu_id . '"';
				}

				if ( $menu_class ) {
					$fb_output .= ' class="' . $menu_class . '"';
				}

				$fb_output .= '>';
				$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
				$fb_output .= '</ul>';

				if ( $container ) {
					$fb_output .= '</' . $container . '>';
				}

				echo $fb_output;
			}
		}
	}
}

/*
Same size for all tags
*/
if ( ! function_exists( 'couponxxl_custom_tag_cloud_widget' ) ) {
	function couponxxl_custom_tag_cloud_widget( $args ) {
		$args['largest']  = 18; //largest tag
		$args['smallest'] = 10; //smallest tag
		$args['unit']     = 'px'; //tag font unit
		return $args;
	}

	add_filter( 'widget_tag_cloud_args', 'couponxxl_custom_tag_cloud_widget' );
}

/* format wp_link_pages so it has the right css applied to it */
if ( ! function_exists( 'couponxxl_link_pages' ) ) {
	function couponxxl_link_pages() {
		$post_pages = wp_link_pages( array(
			'before'           => '',
			'after'            => '',
			'link_before'      => '<span>',
			'link_after'       => '</span>',
			'next_or_number'   => 'number',
			'nextpagelink'     => esc_html__( '&raquo;', 'couponxxl' ),
			'previouspagelink' => esc_html__( '&laquo;', 'couponxxl' ),
			'separator'        => ' ',
			'echo'             => 0
		) );
		/* format pages that are not current ones */
		$post_pages = str_replace( '<a', '<li><a', $post_pages );
		$post_pages = str_replace( '</span></a>', '</a></li>', $post_pages );
		$post_pages = str_replace( '><span>', '>', $post_pages );

		/* format current page */
		$post_pages = str_replace( '<span>', '<li class="active"><a href="javascript:;">', $post_pages );
		$post_pages = str_replace( '</span>', '</a></li>', $post_pages );

		return $post_pages;
	}
}

/*
Create tags list
*/
/* create tags list */
if ( ! function_exists( 'couponxxl_tags_list' ) ) {
	function couponxxl_tags_list() {
		$tags     = get_the_tags();
		$tag_list = array();
		if ( ! empty( $tags ) ) {
			foreach ( $tags as $tag ) {
				$tag_list[] = '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . $tag->name . '</a>';
			}
		}

		return join( ', ', $tag_list );
	}
}

/*
Generate categories of the offer
*/
if ( ! function_exists( 'couponxxl_taxonomy' ) ) {
	function couponxxl_taxonomy( $taxonomy, $num = '', $post_id = '' ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$offers_cats_array = array();

		$terms = get_the_terms( $post_id, $taxonomy );
		if ( empty( $num ) ) {
			$num = count( $terms );
		}
		if ( ! empty( $terms ) ) {
			for ( $i = 0; $i < $num; $i ++ ) {
				$term                = array_shift( $terms );
				$permalink           = couponxxl_get_permalink_by_tpl( 'page-tpl_search' );
				$offers_cats_array[] = '<a href="' . esc_url( couponxxl_append_query_string( $permalink, array( $taxonomy => $term->slug ), array( 'all' ) ) ) . '">' . $term->name . '</a>';
			}
		}

		return join( ' ', $offers_cats_array );
	}
}

/*
Generate tags of the offer
*/
if ( ! function_exists( 'couponxxl_offer_tags' ) ) {
	function couponxxl_offer_tags( $post_id = '' ) {
		global $couponxxl_slugs;
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$offer_tags_array = array();

		$terms = get_the_terms( $post_id, 'offer_tag' );
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$permalink          = couponxxl_get_permalink_by_tpl( 'page-tpl_search' );
				$offer_tags_array[] = '<a href="' . esc_url( couponxxl_append_query_string( $permalink, array( $couponxxl_slugs['offer_tag'] => $term->slug ), array() ) ) . '">' . $term->name . '</a>';
			}
		}

		return join( ', ', $offer_tags_array );
	}
}


/*
Limit excerpt
*/
if ( ! function_exists( 'couponxxl_the_excerpt' ) ) {
	function couponxxl_the_excerpt() {
		$excerpt = get_the_excerpt();
		if ( strlen( $excerpt ) > 167 ) {
			$excerpt = substr( $excerpt, 0, 167 );
			$excerpt = substr( $excerpt, 0, strripos( $excerpt, " " ) );
			$excerpt = $excerpt . '...';
		}

		return '<p>' . $excerpt . '</p>';
	}
}

/*
Get list of categories
*/
if ( ! function_exists( 'couponxxl_categories_list' ) ) {
	function couponxxl_categories_list() {
		$category_list = get_the_category();
		$categories    = array();
		if ( ! empty( $category_list ) ) {
			foreach ( $category_list as $category ) {
				$categories[] = '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . $category->name . '</a>';
			}
		}

		return join( ', ', $categories );
	}
}

/*
Style pagination progrerllyGet list of categories
*/
if ( ! function_exists( 'couponxxl_format_pagination' ) ) {
	function couponxxl_format_pagination( $page_links ) {
		global $couponxxl_slugs;
		$list = '';
		if ( ! empty( $page_links ) ) {
			foreach ( $page_links as $page_link ) {
				if ( strpos( $page_link, 'page-numbers current' ) !== false ) {
					$page_link = str_replace( "<span class='page-numbers current'>", '<a href="javascript:;">', $page_link );
					$page_link = str_replace( '</span>', '</a>', $page_link );
					$list .= '<li class="active">' . $page_link . '</li>';
				} else {
					$list .= '<li>' . $page_link . '</li>';
				}

			}
		}

		return $list;
	}
}

/*
Get random stirng,
*/
if ( ! function_exists( 'couponxxl_random_string' ) ) {
	function couponxxl_random_string( $length = 10 ) {
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$random     = '';
		for ( $i = 0; $i < $length; $i ++ ) {
			$random .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
		}

		return $random;
	}
}

/*
Do not add anything to the excerpt
*/
if ( ! function_exists( 'couponxxl_new_excerpt_more' ) ) {
	function couponxxl_new_excerpt_more( $more ) {
		return '';
	}

	add_filter( 'excerpt_more', 'couponxxl_new_excerpt_more' );
}

/*
Generate select for the array.
*/
if ( ! function_exists( 'couponxxl_icons_list' ) ) {
	function couponxxl_icons_list( $value ) {
		$icons_list = couponxxl_awesome_icons_list();

		$select_data = '';

		foreach ( $icons_list as $key => $label ) {
			$select_data .= '<option value="' . esc_attr( $key ) . '" ' . ( $value == $key ? 'selected="selected"' : '' ) . '>' . $label . '</option>';
		}

		return $select_data;
	}
}

/*
Send contact
*/
if ( ! function_exists( 'couponxxl_send_contact' ) ) {
	function couponxxl_send_contact() {
		$errors  = array();
		$name    = isset( $_POST['name'] ) ? esc_sql( $_POST['name'] ) : '';
		$email   = isset( $_POST['email'] ) ? esc_sql( $_POST['email'] ) : '';
		$message = isset( $_POST['message'] ) ? esc_sql( $_POST['message'] ) : '';
		if ( ! isset( $_POST['captcha'] ) ) {
			if ( ! empty( $name ) && ! empty( $email ) && ! empty( $message ) ) {
				if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
					$email_to = couponxxl_get_option( 'contact_mail' );
					$subject  = couponxxl_get_option( 'contact_form_subject' );
					if ( ! empty( $email_to ) ) {
						$message = "
						" . esc_html__( 'Name: ', 'couponxxl' ) . " {$name} \n
						" . esc_html__( 'Email: ', 'couponxxl' ) . " {$email} \n
						" . esc_html__( 'Message: ', 'couponxxl' ) . "\n {$message} \n
					";
						$info    = @wp_mail( $email_to, $subject, $message );
						if ( $info ) {
							echo json_encode( array(
								'success' => esc_html__( 'Your message was successfully submitted.', 'couponxxl' ),
							) );
							die();
						} else {
							echo json_encode( array(
								'error' => esc_html__( 'Unexpected error while attempting to send e-mail.', 'couponxxl' ),
							) );
							die();
						}
					} else {
						echo json_encode( array(
							'error' => esc_html__( 'Message is not send since the recepient email is not yet set.', 'couponxxl' ),
						) );
						die();
					}
				} else {
					echo json_encode( array(
						'error' => esc_html__( 'Email is not valid.', 'couponxxl' ),
					) );
					die();
				}
			} else {
				echo json_encode( array(
					'error' => esc_html__( 'All fields are required.', 'couponxxl' ),
				) );
				die();
			}
		} else {
			echo json_encode( array(
				'error' => esc_html__( 'Captcha is wrong.', 'couponxxl' ),
			) );
			die();
		}
	}

	add_action( 'wp_ajax_contact', 'couponxxl_send_contact' );
	add_action( 'wp_ajax_nopriv_contact', 'couponxxl_send_contact' );
}

/*
Send subscription
*/
if ( ! function_exists( 'couponxxl_send_subscription' ) ) {
	function couponxxl_send_subscription( $email = '' ) {
		$email    = ! empty( $email ) ? $email : $_POST["email"];
		$response = array();
		if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			require_once( couponxxl_load_path( 'includes/classes/class.mailchimp.php' ) );
			$chimp_api     = couponxxl_get_option( "mail_chimp_api" );
			$chimp_list_id = couponxxl_get_option( "mail_chimp_list_id" );
			if ( ! empty( $chimp_api ) && ! empty( $chimp_list_id ) ) {
				$mc     = new MailChimp( $chimp_api );
				$result = $mc->call( 'lists/subscribe', array(
					'id'    => $chimp_list_id,
					'email' => array( 'email' => $email )
				) );

				if ( $result === false ) {
					$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'There was an error contacting the API, please try again.', 'couponxxl' ) . '</div>';
				} else if ( isset( $result['status'] ) && $result['status'] == 'error' ) {
					$response['message'] = '<div class="alert alert-danger">' . $result['error'] . '</div>';
				} else {
					$response['message'] = '<div class="alert alert-success">' . esc_html__( 'You have successfully subscribed to the newsletter.', 'couponxxl' ) . '</div>';
				}

			} else {
				$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'API data are not yet set.', 'couponxxl' ) . '</div>';
			}
		} else {
			$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Email is empty or invalid.', 'couponxxl' ) . '</div>';
		}

		echo json_encode( $response );
		die();
	}

	add_action( 'wp_ajax_subscribe', 'couponxxl_send_subscription' );
	add_action( 'wp_ajax_nopriv_subscribe', 'couponxxl_send_subscription' );
}


/*
Display popular uredjaj.
*/
if ( ! function_exists( 'couponxxl_popular_stores' ) ) {
	function couponxxl_popular_stores( $limit ) {
		global $wpdb;
		$popular_stores = array();
		$query          = "SELECT posts.ID, postmeta.meta_value, SUM(offer_clicks) as clicks_sum 
				FROM {$wpdb->posts} as posts
				INNER JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.meta_value
				INNER JOIN {$wpdb->prefix}offers as offers ON postmeta.post_id = offers.post_id 
				WHERE postmeta.meta_key = 'offer_store' 
				GROUP BY postmeta.meta_value ORDER BY clicks_sum DESC LIMIT {$limit}";
		$results        = $wpdb->get_results( $query );
		if ( ! empty( $results ) ) {
			$popular_stores = $results;
		}

		return $popular_stores;
	}
}

/*
Get avatar url
*/
if ( ! function_exists( 'couponxxl_get_avatar_url' ) ) {
	function couponxxl_get_avatar_url( $get_avatar ) {
		preg_match( "/src='(.*?)'/i", $get_avatar, $matches );
		if ( empty( $matches[1] ) ) {
			preg_match( "/src=\"(.*?)\"/i", $get_avatar, $matches );
		}

		return $matches[1];
	}
}

/*
Make embeded content responsive
*/
if ( ! function_exists( 'couponxxl_embed_html' ) ) {
	function couponxxl_embed_html( $html ) {
		return '<div class="video-container">' . $html . '</div>';
	}

	add_filter( 'embed_oembed_html', 'couponxxl_embed_html', 10, 3 );
	add_filter( 'video_embed_html', 'couponxxl_embed_html' ); // Jetpack
}


/*
Comments of the psots and pages
*/
if ( ! function_exists( 'couponxxl_comments' ) ) {
	function couponxxl_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		$add_below          = '';
		?>
		<!-- comment-1 -->
		<div class="media <?php echo $depth > 1 ? esc_attr( 'left-padding' ) : esc_attr( '' ); ?>">
			<div class="comment-inner">
				<?php
				$avatar = couponxxl_get_avatar_url( get_avatar( $comment, 75 ) );
				if ( ! empty( $avatar ) ): ?>
					<a class="pull-left" href="javascript:;">
						<img src="<?php echo esc_url( $avatar ); ?>" class="media-object comment-avatar" title=""
						     alt="">
					</a>
				<?php endif; ?>
				<div class="media-body comment-body">
					<div class="pull-left">
						<h4><?php comment_author(); ?></h4>
						<span><?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . esc_html__( ' ago', 'couponxxl' ); ?></span>
					</div>
					<div class="pull-right">
						<?php
						comment_reply_link( array_merge( $args, array(
							'reply_text' => '<span class="fa fa-reply"></span>',
							'add_below'  => $add_below,
							'depth'      => $depth,
							'max_depth'  => $args['max_depth']
						) ) );
						?>
					</div>
					<div class="clearfix"></div>
					<?php
					if ( $comment->comment_approved != '0' ) {
						?>
						<p><?php echo get_comment_text(); ?></p>
						<?php
					} else { ?>
						<p><?php esc_html_e( 'Your comment is awaiting moderation.', 'couponxxl' ); ?></p>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<!-- .comment-1 -->
		<?php
	}
}


/*
End function of the comments
*/
if ( ! function_exists( 'couponxxl_end_comments' ) ) {
	function couponxxl_end_comments() {
		return "";
	}
}

/*
Get store logo to display for the coupons
*/
if ( ! function_exists( 'couponxxl_store_logo' ) ) {
	function couponxxl_store_logo( $store_id = '', $echo = true, $size = 'couponxxl-shop-logo' ) {
		if ( empty( $store_id ) ) {
			$store_id = get_the_ID();
		}
		if ( has_post_thumbnail( $store_id ) ) {
			if ( $echo ) {
				echo get_the_post_thumbnail( $store_id, $size, array( 'class' => 'img-responsive' ) );
			} else {
				return get_the_post_thumbnail( $store_id, $size, array( 'class' => 'img-responsive' ) );
			}
		}
	}
}

/*
Get get deepest taxonomy
*/
if ( ! function_exists( 'couponxxl_get_deepest_taxonomy' ) ) {
	function couponxxl_get_deepest_taxonomy( $source ) {
		$organize_list = array();
		if ( sizeof( $source ) == 1 ) {
			return array_pop( $source );
		}
		couponxxl_sort_terms_hierarchicaly( $source, $organize_list );
		$organize_list = (array) $organize_list;
		if ( ! empty( $organize_list ) ) {
			$last_item = array_pop( $organize_list );
			if ( empty( $last_item->children ) ) {
				return $last_item;
			} else {
				return couponxxl_deepest_taxonomy( $last_item->children );
			}
		}
	}
}

/*
Get deepest taxonomy
*/
if ( ! function_exists( 'couponxxl_deepest_taxonomy' ) ) {
	function couponxxl_deepest_taxonomy( $children ) {
		$last_item = array_pop( $children );
		if ( empty( $last_item->children ) ) {
			return $last_item;
		} else {
			$last_one = couponxxl_deepest_taxonomy( $last_item->children );
		}

		return $last_one;
	}
}

/*
Sort taxonomies hierarchicaly.
*/
if ( ! function_exists( 'couponxxl_sort_terms_hierarchicaly' ) ) {
	function couponxxl_sort_terms_hierarchicaly( Array &$cats, Array &$into, $parentId = 0 ) {
		foreach ( $cats as $i => $cat ) {
			if ( $cat->parent == $parentId ) {
				$into[ $cat->term_id ] = $cat;
				unset( $cats[ $i ] );
			}
		}

		foreach ( $into as $topCat ) {
			$topCat->children = array();
			couponxxl_sort_terms_hierarchicaly( $cats, $topCat->children, $topCat->term_id );
		}
	}
}

/*
Get organized categories
*/
if ( ! function_exists( 'couponxxl_get_organized' ) ) {
	function couponxxl_get_organized( $taxonomy ) {
		$categories = get_terms( $taxonomy, array( 'hide_empty' => false ) );
		if ( ! is_wp_error( $categories ) ) {
			$taxonomy_organized = array();
			couponxxl_sort_terms_hierarchicaly( $categories, $taxonomy_organized );
			$taxonomy_organized = (array) $taxonomy_organized;

			if ( $taxonomy == 'offer_cat' ) {
				$sortby = couponxxl_get_option( 'all_categories_sortby' );
				$sort   = couponxxl_get_option( 'all_categories_sort' );
			} else {
				$sortby = couponxxl_get_option( 'all_locations_sortby' );
				$sort   = couponxxl_get_option( 'all_locations_sort' );
			}


			if ( $sort == 'asc' ) {
				switch ( $sortby ) {
					case 'name' :
						usort( $taxonomy_organized, "couponxxl_organized_sort_name_asc" );
						break;
					case 'slug' :
						usort( $taxonomy_organized, "couponxxl_organized_sort_slug_asc" );
						break;
					case 'count' :
						usort( $taxonomy_organized, "couponxxl_organized_sort_count_asc" );
						break;
					default :
						usort( $taxonomy_organized, "couponxxl_organized_sort_name_asc" );
						break;
				}

			} else {
				switch ( $sortby ) {
					case 'name' :
						usort( $taxonomy_organized, "couponxxl_organized_sort_name_desc" );
						break;
					case 'slug' :
						usort( $taxonomy_organized, "couponxxl_organized_sort_slug_desc" );
						break;
					case 'count' :
						usort( $taxonomy_organized, "couponxxl_organized_sort_count_desc" );
						break;
					default :
						usort( $taxonomy_organized, "couponxxl_organized_sort_name_desc" );
						break;
				}
			}

			return $taxonomy_organized;
		}
	}
}

/*
Organize by name ASC
*/
if ( ! function_exists( 'couponxxl_organized_sort_name_asc' ) ) {
	function couponxxl_organized_sort_name_asc( $a, $b ) {
		return strcmp( $a->name, $b->name );
	}
}

/*
Organize by name DESC
*/
if ( ! function_exists( 'couponxxl_organized_sort_name_desc' ) ) {
	function couponxxl_organized_sort_name_desc( $a, $b ) {
		return strcmp( $b->name, $a->name );
	}
}

/*
Organize by slug ASC
*/
if ( ! function_exists( 'couponxxl_organized_sort_slug_asc' ) ) {
	function couponxxl_organized_sort_slug_asc( $a, $b ) {
		return strcmp( $a->slug, $b->slug );
	}
}

/*
Organize by slug DESC
*/
if ( ! function_exists( 'couponxxl_organized_sort_slug_desc' ) ) {
	function couponxxl_organized_sort_slug_desc( $a, $b ) {
		return strcmp( $b->slug, $a->slug );
	}
}

/*
Organize by count ASC
*/
if ( ! function_exists( 'couponxxl_organized_sort_count_asc' ) ) {
	function couponxxl_organized_sort_count_asc( $a, $b ) {
		return strcmp( $a->count, $b->count );
	}
}

/*
Organize by count DESC
*/
if ( ! function_exists( 'couponxxl_organized_sort_count_desc' ) ) {
	function couponxxl_organized_sort_count_desc( $a, $b ) {
		return strcmp( $b->count, $a->count );
	}
}

/*
Display category tree.
*/
if ( ! function_exists( 'couponxxl_display_select_tree' ) ) {
	function couponxxl_display_select_tree( $cat, $selected = array(), $level = 0 ) {
		if ( ! empty( $cat->children ) ) {
			echo '<option value="" disabled>' . str_repeat( '&nbsp;&nbsp;', $level ) . '' . $cat->name . '</option>';
			$level ++;
			foreach ( $cat->children as $key => $child ) {
				couponxxl_display_select_tree( $child, $selected, $level );
			}
		} else {
			echo '<option value="' . $cat->term_id . '" ' . ( in_array( $cat->term_id, $selected ) ? 'selected="selected"' : '' ) . '>' . str_repeat( '&nbsp;&nbsp;', $level ) . '' . $cat->name . '</option>';
		}
	}
}


/*
Disaply ident tre on the select
*/
if ( ! function_exists( 'couponxxl_display_indent_select_tree' ) ) {
	function couponxxl_display_indent_select_tree( $term, $categories, $indent ) {
		echo '<option style="padding-left:' . ( 10 * $indent ) . 'px;" value="' . esc_attr( $term->slug ) . '" ' . ( in_array( $term->slug, $categories ) ? 'selected="selected"' : '' ) . '>' . $term->name . '</option>';
		if ( ! empty( $term->children ) ) {
			$indent ++;
			foreach ( $term->children as $key => $child ) {
				couponxxl_display_indent_select_tree( $child, $categories, $indent );
			}
		}
	}
}

/*
Display taxonomy tree
*/
if ( ! function_exists( 'couponxxl_display_tree' ) ) {
	function couponxxl_display_tree( $cat, $taxonomy ) {
		echo '<ul class="list-unstyled">';
		foreach ( $cat->children as $key => $child ) {
			echo '<li>
				<a href="' . esc_url( couponxxl_append_query_string( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ), array( $taxonomy => $child->slug ), array( 'all' ) ) ) . '">' . $child->name . '</a>
				<span class="count">' . $child->count . '</span>';
			if ( ! empty( $child->children ) ) {
				couponxxl_display_tree( $child, $taxonomy );
			}
			echo '</li>';
		}
		echo '</ul>';

	}
}

/*
Capitalizel
*/
if ( ! function_exists( 'couponxxl_ucfirst' ) ) {
	function couponxxl_ucfirst( $string ) {
		$strlen    = mb_strlen( $string );
		$firstChar = mb_substr( $string, 0, 1 );
		$then      = mb_substr( $string, 1, $strlen - 1 );

		return mb_strtoupper( $firstChar ) . $then;
	}
}
/*
AJAX Search by on type
*/
if ( ! function_exists( 'couponxxl_items_dropdown' ) ) {
	function couponxxl_items_dropdown() {
		global $wpdb;
		$value         = mb_strtolower( esc_sql( $_POST['val'] ) );
		$preg_callback = function ( $matches ) {
			return '<strong>' . $matches[1] . '</strong>';
		};

		$html = '<ul class="list-unstyled">';
		if ( ! empty( $value ) ) {
			$items = $wpdb->get_results( $wpdb->prepare( "SELECT posts.ID, posts.post_title, posts.post_type, offers.offer_type FROM {$wpdb->posts} AS posts LEFT JOIN {$wpdb->prefix}offers AS offers ON posts.ID = offers.post_id WHERE LOWER( posts.post_title ) LIKE %s AND ( posts.post_type = 'store' OR posts.post_type = 'offer' ) AND posts.post_status = 'publish'", '%' . $value . '%' ) );
			if ( ! empty( $items ) ) {
				foreach ( $items as $item ) {
					$post_title = $item->post_title;
					$post_title = preg_replace_callback( '/(' . $value . ')/i', $preg_callback, $post_title, - 1 );

					if ( $item->offer_type == 'coupon' ) {
						$store_id = get_post_meta( $item->ID, 'offer_store', true );
						$image    = couponxxl_store_logo( $store_id, false );
						$image    = '<div class="coupon-list-wrap">' . $image . '</div>';
					} else {
						$image = get_the_post_thumbnail( $item->ID, 'couponxxl-category-box' );
					}

					$html .= '
				<li>
					<a href="' . get_permalink( $item->ID ) . '">
						' . $image . '
						<div class="store-suggestion-info">
							<h6>' . $post_title . '</h6>
						</div>						
					</a>
				</li>
				';
				}
			}
		}
		$html .= '</ul>';

		echo $html;
		die();
	}

	add_action( 'wp_ajax_stores_dropdown', 'couponxxl_items_dropdown' );
	add_action( 'wp_ajax_nopriv_stores_dropdown', 'couponxxl_items_dropdown' );
}

/*
generating show code button
*/
if ( ! function_exists( 'couponxxl_show_code' ) ) {
	function couponxxl_show_code() {
		$offer_id = esc_sql( $_POST['offer_id'] );
		couponxxl_register_click( $offer_id );
		$offer       = get_post( $offer_id );
		$offer_modal = '';
		if ( ! empty( $offer ) ) {
			$offer_store = get_post_meta( $offer_id, 'offer_store', true );
			$coupon_type = get_post_meta( $offer_id, 'coupon_type', true );

			$store_link = get_post_meta( $offer_store, 'store_link', true );
			?>
			<?php if ( ! empty( $store_link ) ): ?>
				<a href="<?php the_permalink( $offer_store ) ?>" target="_blank">
			<?php endif; ?>
			<?php couponxxl_store_logo( $offer_store ); ?>
			<?php if ( ! empty( $store_link ) ): ?>
				</a>
			<?php endif; ?>

			<h4><?php echo $offer->post_title; ?></h4>
			<?php
			if ( $coupon_type == 'code' ) {
				$coupon_code = get_post_meta( $offer_id, 'coupon_code', true );
				echo '<input type="text" class="btn coupon-code-modal" readonly="readonly" value="' . esc_attr( $coupon_code ) . '" />';
				echo '<p class="coupon-code-copied" data-aftertext="' . esc_html__( 'Code is copied.', 'couponxxl' ) . '">' . esc_html__( 'Click the code to auto copy.', 'couponxxl' ) . '</p>';
			} else if ( $coupon_type == 'printable' ) {
				$coupon_image = get_post_meta( $offer_id, 'coupon_image', true );
				echo wp_get_attachment_image( $coupon_image, 'full', 0, array( 'class' => 'coupon-print-image' ) );
				echo '<a class="btn coupon-code-modal print" href="javascript:print();">' . esc_html__( 'PRINT', 'couponxxl' ) . '</a>';
			} else if ( $coupon_type == 'sale' ) {
				$coupon_sale = get_post_meta( $offer_id, 'coupon_sale', true );
				echo '<a class="btn coupon-code-modal" href="' . esc_url( $coupon_sale ) . '" target="_blank">' . esc_html__( 'SEE SALE', 'couponxxl' ) . '</a>';
			}
			?>

			<?php echo couponxxl_thumbs_html( $offer_id ); ?>


			<?php
			$coupon_modal_content = couponxxl_get_option( 'coupon_modal_content' );
			if ( $coupon_modal_content == 'content' ) {
				echo apply_filters( 'the_content', $offer->post_content );
			} else {
				echo $offer->post_excerpt;
			}
			?>

			<div class="code-footer">
				<a href="<?php echo get_permalink( $offer_store ) ?>">
					<?php echo esc_html__( 'See all ', 'couponxxl' ) . get_the_title( $offer_store ) . esc_html__( ' Coupons & Deals', 'couponxxl' ); ?>
				</a>

				<ul class="list-unstyled list-inline store-social-networks">
					<li>
						<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink( $offer_id ) ) ?>"
						   class="share" target="_blank">
							<i class="fa fa-facebook-square"></i>
						</a>
					</li>
					<li>
						<a href="http://twitter.com/intent/tweet?source=<?php echo esc_attr( get_bloginfo( 'name' ) ) ?>&amp;text=<?php echo urlencode( get_permalink( $offer_id ) ) ?>"
						   class="share" target="_blank">
							<i class="fa fa-twitter-square"></i>
						</a>
					</li>
					<li>
						<a href="https://plus.google.com/share?url=<?php echo urlencode( get_permalink( $offer_id ) ) ?>"
						   class="share" target="_blank">
							<i class="fa fa-google-plus-square"></i>
						</a>
					</li>
				</ul>

			</div>

			<?php
		}
		die();
	}

	add_action( 'wp_ajax_show_code', 'couponxxl_show_code' );
	add_action( 'wp_ajax_nopriv_show_code', 'couponxxl_show_code' );
}

/*
Disaply ident tre on the select
*/
if ( ! function_exists( 'couponxxl_deal_voucher_count' ) ) {
	function couponxxl_deal_voucher_count( $offer_id = '' ) {
		if ( empty( $offer_id ) ) {
			$offer_id = get_the_ID();
		}
		global $wpdb;
		$deal_vouchers = $wpdb->get_results( $wpdb->prepare( "SELECT COUNT(*) AS vouchers FROM {$wpdb->postmeta} WHERE meta_key = 'voucher_deal' AND meta_value = %d", $offer_id ) );
		$deal_vouchers = array_shift( $deal_vouchers );

		return $deal_vouchers->vouchers;
	}
}

/*
Check if offer exists
*/
if ( ! function_exists( 'couponxxl_check_offer' ) ) {
	function couponxxl_check_offer( $offer_id = '', $offer_type = 'coupon' ) {
		global $wpdb;
		$available = true;

		if ( empty( $offer_id ) ) {
			$offer_id = get_the_ID();
		}

		$offer = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}offers WHERE post_id = %d", $offer_id ) );
		if ( ! empty( $offer[0] ) ) {
			$offer        = $offer[0];
			$current_time = current_time( 'timestamp' );
			if ( ! empty( $offer->offer_start ) && $offer->offer_start > $current_time ) {
				$available = false;
			} else if ( ! empty( $offer_expire ) && $offer_expire < $current_time ) {
				$available = false;
			}

			if ( $offer_type == 'deal' ) {
				if ( $offer->offer_has_items == '0' ) {
					$available = false;
				}
			}
		} else {
			$available = false;
		}

		return $available;
	}
}


/*
Generate payment HTML
*/
if ( ! function_exists( 'couponxxl_generate_credit_payments' ) ) {
	function couponxxl_generate_credit_payments() {
		$package         = $_GET['package'];
		$credit_packages = couponxxl_get_option( 'credit_packages' );
		$credit_packages = explode( "\n", $credit_packages );
		if ( ! empty( $credit_packages[ $package - 1 ] ) ) {
			$temp   = explode( '|', $credit_packages[ $package - 1 ] );
			$amount = $temp[1];

			$main_unit_abbr = couponxxl_get_option( 'main_unit_abbr' );
			$title          = esc_html__( 'Credits', 'couponxxl' );
			$desc           = esc_html__( 'Purchasing credits for submitting coupons and deal.', 'couponxxl' );
			$permalink      = couponxxl_get_permalink_by_tpl( 'page-tpl_profile' );
			$permalink      = add_query_arg( array(
				'action'  => 'gateway-return',
				'subpage' => 'credits'
			), $permalink );

			$user_id = get_current_user_id();

			$transient       = 'cxxl_credits_' . $user_id;
			$transient_value = array(
				'purchase' => 'credits',
				'buyer_id' => get_current_user_id(),
				'credits'  => $temp[0],
				'amount'   => $amount
			);
			set_transient( $transient, $transient_value, 900 );

			do_action( 'couponxxl_generate_payments', $amount, $title, $desc, $main_unit_abbr, $permalink, $transient, $_GET['gateway'] );
		}
	}
}

/*
Format price numbers
*/
if ( ! function_exists( 'couponxxl_format_price_number' ) ) {
	function couponxxl_format_price_number( $price ) {
		if ( ! is_numeric( $price ) ) {
			return $price;
		}
		$unit_position = couponxxl_get_option( 'unit_position' );
		$unit          = couponxxl_get_option( 'unit' );

		if ( $unit_position == 'front' ) {
			return $unit . number_format( $price, 2 );
		} else {
			return number_format( $price, 2 ) . $unit;
		}
	}
}

/*
Check voucher and depending on the result display with not used or used
*/
if ( ! function_exists( 'couponxxl_voucher_check' ) ) {
	function couponxxl_voucher_check( $voucher ) {
		$used = false;
		if ( $voucher->voucher_status == '1' ) {
			$used = true;
		}

		return '<div class="voucher-wrap">' . $voucher->voucher_code . ' <a href="javascript:;" class="update_voucher button" data-id="' . esc_attr( $voucher->voucher_id ) . '" title="' . ( $used ? esc_attr__( 'Mark as not used', 'couponxxl' ) : esc_attr__( 'Mark as used', 'couponxxl' ) ) . '">' . ( $used ? '<i class="fa fa-times"></i>' : '<i class="fa fa-check"></i>' ) . '</a></div>';
	}
}

/*
Update voucher to set it as used or not
*/
if ( ! function_exists( 'couponxxl_update_voucher' ) ) {
	function couponxxl_update_voucher( $die = true ) {
		global $wpdb;
		$voucher = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}vouchers WHERE voucher_id = %d", $_POST['voucher_id'] ) );

		if ( ! empty( $voucher ) ) {
			$voucher                 = $voucher[0];
			$voucher->voucher_status = $_POST['status'];
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}vouchers SET voucher_status = %s WHERE voucher_id = %d", $_POST['status'], $_POST['voucher_id'] ) );
			if ( $die ) {
				echo couponxxl_voucher_check( $voucher );
			}
		}
		if ( $die ) {
			die();
		}
	}

	add_action( 'wp_ajax_update_voucher', 'couponxxl_update_voucher' );
	add_action( 'wp_ajax_nopriv_update_voucher', 'couponxxl_update_voucher' );
}

/*
Generate custom voucher
*/
if ( ! function_exists( 'couponxxl_generate_voucher' ) ) {
	function couponxxl_generate_voucher( $length = 10, $offer_id = '' ) {
		$deal_item_vouchers = get_post_meta( $offer_id, 'deal_item_vouchers', true );
		if ( ! empty( $deal_item_vouchers ) ) {
			$deal_item_vouchers = explode( "\n", $deal_item_vouchers );
			if ( ! empty( $deal_item_vouchers[0] ) ) {
				$voucher_code = array_shift( $deal_item_vouchers );
				update_post_meta( $offer_id, 'deal_item_vouchers', join( "\n", $deal_item_vouchers ) );
			} else {
				$voucher_code = couponxxl_generate_voucher( 5 );
			}
		} else {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			global $wpdb;
			$voucher_code = '';
			for ( $i = 0; $i < $length; $i ++ ) {
				$voucher_code .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
			}

			$exists = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}vouchers WHERE voucher_code = %s", $voucher_code ) );
			$exists = array_shift( $exists );
			if ( ! empty( $exists ) ) {
				$voucher_code = couponxxl_generate_voucher();
			}
		}

		return $voucher_code;
	}
}


/*
Send order message
*/
if ( ! function_exists( 'couponxxl_send_mail' ) ) {
	function couponxxl_send_mail( $to, $subject, $message ) {
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html; charset=UTF-8";

		$from_mail = couponxxl_get_option( 'email_sender' );
		$from_name = couponxxl_get_option( 'name_sender' );
		$headers[] = "From: " . $from_name . " <" . $from_mail . ">";

		$info = @wp_mail( $to, $subject, $message, $headers );
	}
}

/*
Add custom column on the store listing
*/
if ( ! function_exists( 'couponxxl_custom_store_columns' ) ) {
	add_filter( 'manage_edit-store_columns', 'couponxxl_custom_store_columns' );
	function couponxxl_custom_store_columns( $columns ) {
		$columns = array_slice( $columns, 0, count( $columns ) - 1, true ) + array(
				"store_logo" => esc_html__( 'Store Logo', 'coupon' ),
			) + array_slice( $columns, count( $columns ) - 1, count( $columns ) - 1, true );

		return $columns;
	}
}

/*
Populate custom store column
*/
if ( ! function_exists( 'couponxxl_custom_store_columns_populate' ) ) {
	add_action( 'manage_store_posts_custom_column', 'couponxxl_custom_store_columns_populate', 10, 2 );
	function couponxxl_custom_store_columns_populate( $column, $post_id ) {
		if ( $column == 'store_logo' ) {
			echo couponxxl_store_logo( $post_id );
		}
	}
}

/*
Add custom columns for the offer listing
*/
if ( ! function_exists( 'couponxxl_custom_offer_columns' ) ) {
	add_filter( 'manage_edit-offer_columns', 'couponxxl_custom_offer_columns' );
	function couponxxl_custom_offer_columns( $columns ) {
		$columns = array_slice( $columns, 0, count( $columns ) - 1, true ) + array(
				"offer_id"        => esc_html__( 'Offer ID', 'coupon' ),
				"offer_cat"       => esc_html__( 'category', 'coupon' ),
				"offer_store"     => esc_html__( 'Store', 'coupon' ),
				"offer_type"      => esc_html__( 'Type', 'coupon' ),
				"offer_expire"    => esc_html__( 'Expire Date', 'coupon' ),
				"offer_in_slider" => esc_html__( 'In Slider', 'coupon' ),
				"offer_clicks"    => esc_html__( 'Clicks', 'coupon' )
			) + array_slice( $columns, count( $columns ) - 1, count( $columns ) - 1, true );

		return $columns;
	}
}

/*
Populate custom fields on the offer listing
*/
if ( ! function_exists( 'couponxxl_custom_offer_columns_populate' ) ) {
	add_action( 'manage_offer_posts_custom_column', 'couponxxl_custom_offer_columns_populate', 10, 2 );
	function couponxxl_custom_offer_columns_populate( $column, $post_id ) {
		switch ( $column ) {
			case 'offer_id' :
				echo $post_id;
				break;
			case 'offer_cat':
				$terms = get_the_terms( $post_id, 'offer_cat' );
				global $couponxxl_slugs;
				if ( ! empty( $terms ) ) {
					$last      = couponxxl_get_deepest_taxonomy( $terms );
					$permalink = couponxxl_get_permalink_by_tpl( 'page-tpl_search' );
					echo '<a href="' . esc_url( couponxxl_append_query_string( $permalink, array( $couponxxl_slugs['offer_cat'] => $last->slug ), array( 'all' ) ) ) . '" target="_blank">' . $last->name . '</a>';
				}
				break;
			case 'offer_store' :
				$offer_store = get_post_meta( $post_id, 'offer_store', true );
				if ( ! empty( $offer_store ) ) {
					echo get_the_title( $offer_store );
				} else {
					echo '';
				}
				break;
			case 'offer_type' :
				$offer_type = couponxxl_get_post_meta( $post_id, 'offer_type', true );
				if ( $offer_type == 'deal' ) {
					esc_html_e( 'Deal', 'couponxxl' );
				} else {
					esc_html_e( 'Coupon', 'couponxxl' );
				}
				break;
			case 'offer_expire' :
				$offer_expire = couponxxl_get_post_meta( $post_id, 'offer_expire', true );
				if ( ! empty( $offer_expire ) && $offer_expire !== '99999999999' ) {
					echo date_i18n( 'F j, Y', $offer_expire );
				} else if ( $offer_expire == '99999999999' ) {
					esc_html_e( 'Unlimited', 'couponxxl' );
				}
				break;
			case 'offer_in_slider':
				$offer_in_slider = couponxxl_get_post_meta( $post_id, 'offer_in_slider', true );
				if ( $offer_in_slider == 'yes' ) {
					esc_html_e( 'Yes', 'couponxxl' );
				} else {
					esc_html_e( 'No', 'couponxxl' );
				}
				break;
			case 'offer_clicks' :
				echo couponxxl_get_post_meta( $post_id, 'offer_clicks', true );
				break;
		}
	}
}

/*
Make custom coumns on the offer listing sortable
*/
if ( ! function_exists( 'couponxxl_sorting_offer_columns' ) ) {
	add_filter( 'manage_edit-offer_sortable_columns', 'couponxxl_sorting_offer_columns' );
	function couponxxl_sorting_offer_columns( $columns ) {
		$custom = array(
			'offer_store'        => 'offer_store',
			'offer_type'         => 'offer_type',
			'offer_expire'       => 'offer_expire',
			'offer_average_rate' => 'offer_average_rate',
			'offer_in_slider'    => 'offer_in_slider',
			'offer_clicks'       => 'offer_clicks',
		);

		return wp_parse_args( $custom, $columns );
	}
}

/*
Add custom columns for the offer listing
*/
if ( ! function_exists( 'couponxxl_sort_offer_columns' ) ) {
	add_action( 'pre_get_posts', 'couponxxl_sort_offer_columns' );
	function couponxxl_sort_offer_columns( $query ) {
		if ( ! is_admin() ) {
			return;
		}

		$orderby = $query->get( 'orderby' );
		if ( $orderby == 'offer_store' ) {
			$query->set( 'meta_key', $orderby );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}
}

/*
Add join with custom table on post listing in admin
*/
if ( ! function_exists( 'couponxxl_posts_join_offer_columns' ) ) {
	function couponxxl_posts_join_offer_columns( $sql ) {
		global $wpdb, $typenow;

		if ( is_admin() && $typenow == 'offer' && ! empty( $_GET['orderby'] ) && ( in_array( $_GET['orderby'], array(
				'offer_type',
				'offer_clicks',
				'offer_expire'
			) ) )
		) {
			return $sql . " INNER JOIN {$wpdb->prefix}offers AS offers ON $wpdb->posts.ID = offers.post_id ";
		}

		return $sql;
	}

	add_action( 'posts_join', 'couponxxl_posts_join_offer_columns' );
}

/*
Filter offers on backend listing by custom values
*/
if ( ! function_exists( 'couponxxl_posts_orderby_offer_columns' ) ) {
	function couponxxl_posts_orderby_offer_columns( $sql ) {
		global $wpdb, $typenow;

		if ( is_admin() && $typenow == 'offer' && ! empty( $_GET['orderby'] ) && ( in_array( $_GET['orderby'], array(
				'offer_type',
				'offer_clicks',
				'offer_expire'
			) ) )
		) {
			$sql = esc_sql( $_GET['orderby'] ) . ' ' . esc_sql( $_GET['order'] );
		}

		return $sql;
	}

	add_action( 'posts_orderby', 'couponxxl_posts_orderby_offer_columns' );
}

/*
Login function
*/
if ( ! function_exists( 'couponxxl_login' ) ) {
	function couponxxl_login() {
		$response = array();
		if ( wp_verify_nonce( $_POST['login_field'], 'login' ) ) {
			$username = isset( $_POST['username'] ) ? esc_sql( $_POST['username'] ) : '';
			$password = isset( $_POST['password'] ) ? esc_sql( $_POST['password'] ) : '';

			$user = get_user_by( 'login', $username );
			if ( $user ) {
				$is_active = get_user_meta( $user->ID, 'user_active_status', true );
				if ( empty( $is_active ) || $is_active == 'active' ) {
					$user = wp_signon( array(
						'user_login'    => $username,
						'user_password' => $password,
						'remember'      => isset( $_POST['remember_me'] ) ? true : false
					), is_ssl() );
					if ( is_wp_error( $user ) ) {
						switch ( $user->get_error_code() ) {
							case 'invalid_username':
								$message = esc_html__( 'Invalid username', 'couponxxl' );
								break;
							case 'incorrect_password':
								$message = esc_html__( 'Invalid password', 'couponxxl' );
								break;
						}
						$message = '<div class="alert alert-danger">' . $message . '</div>';
					} else {
						$response['message'] = '<div class="alert alert-success">' . esc_html__( 'Congratulations! You will be redirected in 1 second....', 'couponxxl' ) . '</div>';
						if ( ! empty( $_POST['redirect'] ) ) {
							$response['url'] = $_POST['redirect'];
						} else {
							$response['url'] = esc_url( add_query_arg( array( 'c' => time() ), home_url( '/' ) ) );
						}
					}
				} else {
					$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Your account is not activated. Check you mail for the activation link.', 'couponxxl' ) . '</div>';
				}
			} else {
				$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Invalid username', 'couponxxl' ) . '</div>';
			}
		} else {
			$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'You do not permission for your action', 'couponxxl' ) . '</div>';
		}

		echo json_encode( $response );
		die();
	}

	add_action( 'wp_ajax_login', 'couponxxl_login' );
	add_action( 'wp_ajax_nopriv_login', 'couponxxl_login' );
}

/*
Forgot password
*/
if ( ! function_exists( 'couponxxl_recover' ) ) {
	function couponxxl_recover() {
		$response = array();
		if ( wp_verify_nonce( $_POST['recover_field'], 'recover' ) ) {
			$email = $_POST['email'];
			if ( ! empty( $email ) ) {
				if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
					if ( email_exists( $email ) ) {
						$user                  = get_user_by( 'email', $email );
						$new_password          = couponxxl_random_string( 5 );
						$update_fields         = array(
							'ID'        => $user->ID,
							'user_pass' => $new_password,
						);
						$update_id             = wp_update_user( $update_fields );
						$lost_password_message = couponxxl_get_option( 'lost_password_message' );
						$lost_password_message = str_replace( "%USERNAME%", $user->user_login, $lost_password_message );
						$lost_password_message = str_replace( "%PASSWORD%", $new_password, $lost_password_message );

						$email_sender = couponxxl_get_option( 'email_sender' );
						$name_sender  = couponxxl_get_option( 'name_sender' );
						$headers      = array();
						$headers[]    = "MIME-Version: 1.0";
						$headers[]    = "Content-Type: text/html; charset=UTF-8";
						$headers[]    = "From: " . $name_sender . " <" . $email_sender . ">";

						$lost_password_subject = couponxxl_get_option( 'lost_password_subject' );

						$message_info = @wp_mail( $email, $lost_password_subject, $lost_password_message, $headers );
						if ( $message_info ) {
							$response['message'] = '<div class="alert alert-success">' . esc_html__( 'Email with the new password and your username is sent to the provided email address', 'couponxxl' ) . '</div>';
						} else {
							$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'There was an error trying to send an email', 'couponxxl' ) . '</div>';
						}
					} else {
						$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'There is no user with the provided email address', 'couponxxl' ) . '</div>';
					}
				} else {
					$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Email address is invalid', 'couponxxl' ) . '</div>';
				}
			} else {
				$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Email address is empty', 'couponxxl' ) . '</div>';
			}
		} else {
			$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'You do not permission for your action', 'couponxxl' ) . '</div>';
		}

		echo json_encode( $response );
		die();
	}

	add_action( 'wp_ajax_recover', 'couponxxl_recover' );
	add_action( 'wp_ajax_nopriv_recover', 'couponxxl_recover' );
}

/*
Parse url of the video
*/
if ( ! function_exists( 'couponxxl_parse_url' ) ) {
	function couponxxl_parse_url( $url ) {
		if ( stripos( $url, 'youtube' ) ) {
			$temp = explode( '?v=', $url );

			return 'https://www.youtube.com/embed/' . $temp[1];
		} else if ( stripos( $url, 'vimeo' ) ) {
			$temp = explode( 'vimeo.com/', $url );

			return '//player.vimeo.com/video/' . $temp[ sizeof( $temp ) - 1 ];
		} else {
			return $url;
		}
	}
}


/*
Add user's avatar
*/
if ( ! function_exists( 'couponxxl_change_avatar' ) ) {
	function couponxxl_change_avatar() {
		global $wp_user_avatar;
		$user_id = get_current_user_id();
		$wp_user_avatar->wpua_action_process_option_update( $user_id );
		echo couponxxl_get_avatar_url( get_avatar( $user_id, 55 ) );
		die();
	}

	add_action( 'wp_ajax_change_avatar', 'couponxxl_change_avatar' );
	add_action( 'wp_ajax_nopriv_change_avatar', 'couponxxl_change_avatar' );
}

/*
Create breadcrumbs
*/
if ( ! function_exists( 'couponxxl_get_breadcrumbs' ) ) {
	function couponxxl_get_breadcrumbs() {
		global $offer_type, $offer_cat, $location, $couponxxl_slugs;
		$breadcrumb = '';
		if ( is_front_page() ) {
			return '';
		}
		$breadcrumb .= '<ul class="breadcrumb">';
		if ( is_home() ) {
			$breadcrumb .= '<li><a href="' . home_url() . '">' . esc_html__( 'Home', 'couponxxl' ) . '</a></li><li>' . esc_html__( 'Blog', 'couponxxl' ) . '</li>';
		} else {
			$blog_page = get_option( 'page_for_posts' );
			$blog_url  = get_permalink( $blog_page );
			if ( ! is_home() ) {
				$breadcrumb .= '<li><a href="' . home_url() . '">' . esc_html__( 'Home', 'couponxxl' ) . '</a></li>';
			}
			if ( is_category() ) {
				$breadcrumb .= '<li><a href="' . esc_url( $blog_url ) . '">' . esc_html__( 'Blog', 'couponxxl' ) . '</a></li>';
				$breadcrumb .= '<li>' . single_cat_title( '', false ) . '</li>';
			} else if ( is_404() ) {
				$breadcrumb .= '<li>' . esc_html__( '404 Page Doesn\'t exists', 'couponxxl' ) . '</li>';
			} else if ( is_tag() ) {
				$breadcrumb .= '<li><a href="' . esc_url( $blog_url ) . '">' . esc_html__( 'Blog', 'couponxxl' ) . '</a></li>';
				$breadcrumb .= '<li>' . esc_html__( 'Search by tag: ', 'couponxxl' ) . get_query_var( 'tag' ) . '</li>';
			} else if ( is_author() ) {
				$breadcrumb .= '<li><a href="' . esc_url( $blog_url ) . '">' . esc_html__( 'Blog', 'couponxxl' ) . '</a></li>';
				$breadcrumb .= '<li>' . esc_html__( 'Posts by', 'couponxxl' ) . '</li>';
			} else if ( is_archive() ) {
				$breadcrumb .= '<li><a href="' . esc_url( $blog_url ) . '">' . esc_html__( 'Blog', 'couponxxl' ) . '</a></li>';
				$breadcrumb .= '<li>' . esc_html__( 'Archive for:', 'couponxxl' ) . single_month_title( ' ', false ) . '</li>';
			} else if ( is_search() ) {
				$breadcrumb .= '<li><a href="' . esc_url( $blog_url ) . '">' . esc_html__( 'Blog', 'couponxxl' ) . '</a></li>';
				$breadcrumb .= '<li>' . esc_html__( 'Search results for: ', 'couponxxl' ) . ' ' . get_search_query() . '</li>';
			} else if ( is_singular( 'offer' ) ) {
				$terms = get_the_terms( get_the_ID(), 'offer_cat' );
				if ( ! empty( $terms ) ) {
					$last = array_pop( $terms );
					$breadcrumb .= '<li><a href="' . esc_url( couponxxl_append_query_string( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ), array( $couponxxl_slugs['offer_cat'] => $last->slug ), array( 'all' ) ) ) . '">' . $last->name . '</a></li>';
				}
				$breadcrumb .= '<li>' . get_the_title() . '</li>';
			} else {
				$page_template = get_page_template_slug();
				if ( $page_template == 'page-tpl_search.php' ) {
					if ( empty( $offer_type ) ) {
						$breadcrumb .= '<li>' . esc_html__( 'Deals and coupons ', 'couponxxl' );
					} else if ( $offer_type == 'deal' ) {
						$breadcrumb .= '<li>' . esc_html__( 'Deals ', 'couponxxl' );
					} else {
						$breadcrumb .= '<li>' . esc_html__( 'Coupons ', 'couponxxl' );
					}

					if ( ! empty( $offer_cat ) ) {
						$offer_cat_term = get_term_by( 'slug', esc_sql( $offer_cat ), 'offer_cat' );
						if ( ! empty( $offer_cat_term ) ) {
							$breadcrumb .= esc_html__( 'from category ', 'couponxxl' );
							$breadcrumb .= $offer_cat_term->name . " ";
						}
					}

					if ( ! empty( $location ) ) {
						$location_term = get_term_by( 'slug', esc_sql( $location ), 'location' );
						if ( ! empty( $location_term ) ) {
							$breadcrumb .= esc_html__( 'located in ', 'couponxxl' );
							$breadcrumb .= $location_term->name;
						}
					}

					$breadcrumb .= '</li>';
				} else {
					if ( is_singular( 'store' ) ) {
						$all_stores = couponxxl_get_permalink_by_tpl( 'page-tpl_all_stores.php' );
						if ( stristr( $all_stores, 'http' ) ) {
							$breadcrumb .= '<li><a href="' . esc_url( $all_stores ) . '">' . esc_html__( 'All Stores', 'couponxxl' ) . '</a></li>';
						}
					}
					if ( is_singular( 'post' ) ) {
						$breadcrumb .= '<li><a href="' . esc_url( $blog_url ) . '">' . esc_html__( 'Blog', 'couponxxl' ) . '</a></li>';
					}
					$breadcrumb .= '<li>' . get_the_title() . '</li>';
				}
			}
		}
		$breadcrumb .= '</ul>';

		return $breadcrumb;
	}
}

/*
Filter iamges so user can see only its own
*/
if ( ! function_exists( 'couponxxl_filter_images' ) ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		add_filter( 'ajax_query_attachments_args', 'couponxxl_filter_images', 10, 1 );
	}
	function couponxxl_filter_images( $query = array() ) {
		$has_memebers_page = couponxxl_get_permalink_by_tpl( 'page-tpl_profile' );
		if ( $has_memebers_page !== 'javascript:;' ) {
			$query['author'] = get_current_user_id();
		}

		return $query;
	}
}

/*
Do not allow backend to non authorized users
*/
if ( ! function_exists( 'couponxxl_non_admin_users' ) ) {
	add_action( 'admin_init', 'couponxxl_non_admin_users' );
	function couponxxl_non_admin_users() {
		$user_ID    = get_current_user_id();
		$user_agent = get_user_meta( $user_ID, 'user_agent', true );
		if ( ! current_user_can( 'manage_options' ) && ! stristr( $_SERVER['PHP_SELF'], 'admin-ajax.php' ) && ! stristr( $_SERVER['PHP_SELF'], 'async-upload.php' ) && ( ! $user_agent || $user_agent == 'no' ) ) {
			wp_redirect( home_url() );
			exit;
		}
	}
}


/*
Add overall information on the dashboard
*/
if ( ! function_exists( 'couponxxl_dashboard_overview' ) ) {
	function couponxxl_dashboard_overview() {
		global $wpdb;
		if ( $wpdb->get_var( "SHOW TABLES LIKE 'order_items'" ) == $wpdb->prefix . 'order_items' ) {
			add_meta_box( 'couponxxl_coupon_overall', esc_html__( 'Coupon', 'couponxxl' ), 'couponxxl_coupon_overall', 'dashboard', 'side', 'high' );
			add_meta_box( 'couponxxl_deal_overall', esc_html__( 'Deal', 'couponxxl' ), 'couponxxl_deal_overall', 'dashboard', 'side', 'high' );
			add_meta_box( 'couponxxl_user_overall', esc_html__( 'User', 'couponxxl' ), 'couponxxl_user_overall', 'dashboard', 'side', 'high' );
			add_meta_box( 'couponxxl_earnings_overall', esc_html__( 'Earnings', 'couponxxl' ), 'couponxxl_earnings_overall', 'dashboard', 'side', 'high' );
		}
	}

	add_action( 'wp_dashboard_setup', 'couponxxl_dashboard_overview' );
}

/*
Box with overall information about coupons
*/
if ( ! function_exists( 'couponxxl_coupon_overall' ) ) {
	function couponxxl_coupon_overall() {
		global $wpdb;
		/* COUNT COUPONS */
		$coupons_live = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) as count FROM {$wpdb->prefix}offers WHERE offer_type = 'coupon' AND offer_start <= %d AND offer_expire > %d", current_time( 'timestamp' ), current_time( 'timestamp' ) ) );

		$coupons_expired = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) as count FROM {$wpdb->prefix}offers WHERE offer_type = 'coupon' AND offer_expire <= %d", current_time( 'timestamp' ) ) );


		$coupons_scheduled = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) as count FROM {$wpdb->prefix}offers WHERE offer_type = 'coupon' AND offer_start > %d", current_time( 'timestamp' ) ) );

		$total_coupons = $coupons_live + $coupons_expired + $coupons_scheduled;

		echo '
		<ul class="couponxxl-overall-stats">
			<li>' . esc_html__( 'Valid Coupons:', 'couponxxl' ) . '<span class="value">' . ( ! empty( $coupons_live ) ? $coupons_live : 0 ) . '</span></li>
			<li>' . esc_html__( 'Expired Coupons:', 'couponxxl' ) . '<span class="value">' . ( ! empty( $coupons_expired ) ? $coupons_expired : 0 ) . '</span></li>
			<li>' . esc_html__( 'Scheduled Coupons:', 'couponxxl' ) . '<span class="value">' . ( ! empty( $coupons_scheduled ) ? $coupons_scheduled : 0 ) . '</span></li>
			<li>' . esc_html__( 'Total Coupons:', 'couponxxl' ) . '<span class="value">' . ( ! empty( $total_coupons ) ? $total_coupons : 0 ) . '</span></li>
		</ul>
	';
	}
}

/*
Box with overall information about deals
*/
if ( ! function_exists( 'couponxxl_deal_overall' ) ) {
	function couponxxl_deal_overall() {
		global $wpdb;
		/* COUNT DEALS */
		$deals_live = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) as count FROM {$wpdb->prefix}offers WHERE offer_type = 'deal' AND offer_start <= %d AND offer_expire > %d", current_time( 'timestamp' ), current_time( 'timestamp' ) ) );

		$deals_expired = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) as count FROM {$wpdb->prefix}offers WHERE offer_type = 'deal' AND offer_expire <= %d", current_time( 'timestamp' ) ) );

		$deals_scheduled = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) as count FROM {$wpdb->prefix}offers WHERE offer_type = 'deal' AND offer_start > %d", current_time( 'timestamp' ) ) );

		$total_deals = $deals_live + $deals_expired + $deals_scheduled;

		echo '
		<ul class="couponxxl-overall-stats">
			<li>' . esc_html__( 'Valid Deals:', 'couponxxl' ) . '<span class="value">' . ( ! empty( $deals_live ) ? $deals_live : 0 ) . '</span></li>
			<li>' . esc_html__( 'Expired Deals:', 'couponxxl' ) . '<span class="value">' . ( ! empty( $deals_expired ) ? $deals_expired : 0 ) . '</span></li>
			<li>' . esc_html__( 'Scheduled Deals:', 'couponxxl' ) . '<span class="value">' . ( ! empty( $deals_scheduled ) ? $deals_scheduled : 0 ) . '</span></li>
			<li>' . esc_html__( 'Total Deals:', 'couponxxl' ) . '<span class="value">' . $total_deals . '</span></li>
		</ul>
	';
	}
}

/*
Box with overall information about users
*/
if ( ! function_exists( 'couponxxl_user_overall' ) ) {
	function couponxxl_user_overall() {
		/* COUNT USERS */
		$result      = count_users();
		$total_users = 0;
		if ( ! empty( $result['avail_roles']['editor'] ) ) {
			$total_users = $result['avail_roles']['editor'];
		}
		global $wpdb;

		$date = date( 'Y-m-d', current_time( 'timestamp' ) );

		$morning = new DateTime( $date . ' 00:00:00' );
		$night   = new DateTime( $date . ' 23:59:59' );
		$m       = $morning->format( 'Y-m-d H:i:s' );
		$n       = $night->format( 'Y-m-d H:i:s' );

		$sql = $wpdb->prepare( "SELECT COUNT(*) AS users_count FROM " . $wpdb->users . " WHERE 1=1 AND CAST(user_registered AS DATE) BETWEEN %s AND %s ORDER BY user_login ASC", $m, $n );

		$users = $wpdb->get_results( $sql );
		$users = array_shift( $users );

		$users_today = $users->users_count;


		$date    = date( 'Y-m-d', current_time( 'timestamp' ) - 86400 );
		$morning = new DateTime( $date . ' 00:00:00' );
		$night   = new DateTime( $date . ' 23:59:59' );
		$m       = $morning->format( 'Y-m-d H:i:s' );
		$n       = $night->format( 'Y-m-d H:i:s' );

		$sql = $wpdb->prepare( "SELECT COUNT(*) AS users_count FROM " . $wpdb->users . " WHERE 1=1 AND CAST(user_registered AS DATE) BETWEEN %s AND %s ORDER BY user_login ASC", $m, $n );

		$users = $wpdb->get_results( $sql );
		$users = array_shift( $users );

		$users_yesterday = $users->users_count;
		echo '
		<ul class="couponxxl-overall-stats">
			<li>' . esc_html__( 'Registered Users Today:', 'couponxxl' ) . '<span class="value">' . $users_today . '</span></li>
			<li>' . esc_html__( 'Registered Users Yesterday:', 'couponxxl' ) . '<span class="value">' . $users_yesterday . '</span></li>
			<li>' . esc_html__( 'Total Users:', 'couponxxl' ) . '<span class="value">' . $total_users . '</span></li>
		</ul>
	';
	}
}

/*
Box with overall information about all offers
*/
if ( ! function_exists( 'couponxxl_earnings_overall' ) ) {
	function couponxxl_earnings_overall() {
		/* COUNT TOTAL EARNINGS */
		global $wpdb;
		$per_coupon      = couponxxl_get_option( 'coupon_submit_price' );
		$coupon_earnings = esc_html__( 'Disabled', 'couponxxl' );
		if ( ! empty( $per_coupon ) ) {
			$coupon_paid_submissions = $wpdb->get_var( "SELECT COUNT(*) as count FROM {$wpdb->prefix}offers WHERE offer_type = 'coupon'" );
			$coupon_earnings         = $per_coupon * $coupon_paid_submissions;
		}

		$per_deal          = couponxxl_get_option( 'deal_submit_price' );
		$deal_sub_earnings = esc_html__( 'Disabled', 'couponxxl' );
		if ( ! empty( $per_deal ) ) {
			$deal_paid_submissions = $wpdb->get_var( "SELECT COUNT(*) as count FROM {$wpdb->prefix}offers WHERE offer_type = 'deal'" );
			$deal_sub_earnings     = $per_deal * $deal_paid_submissions;
		}

		$vouchers_earnings = $wpdb->get_var( "SELECT SUM(price*qty) as count FROM {$wpdb->prefix}order_items WHERE status = 'payout'" );
		$total_earnings    = $coupon_earnings + $deal_sub_earnings + $vouchers_earnings;

		echo '
		<ul class="couponxxl-overall-stats">
			<li>' . esc_html__( 'Coupon Subbmission Earnings:', 'couponxxl' ) . '<span class="value">' . couponxxl_format_price_number( $coupon_earnings ) . '</span></li>
			<li>' . esc_html__( 'Deals Submission Earnings:', 'couponxxl' ) . '<span class="value">' . couponxxl_format_price_number( $deal_sub_earnings ) . '</span></li>
			<li>' . esc_html__( 'Deal Vouchers Sell Earnings:', 'couponxxl' ) . '<span class="value">' . couponxxl_format_price_number( $vouchers_earnings ) . '</span></li>
			<li>' . esc_html__( 'Total Earnings:', 'couponxxl' ) . '<span class="value">' . couponxxl_format_price_number( $total_earnings ) . '</span></li>
		</ul>
	';
	}
}

/*
Count offers based on the filter used on the search page
*/
if ( ! function_exists( 'couponxxl_custom_term_count' ) ) {
	$couponxxl_term_count = array(
		'offer_cat' => array(),
		'location'  => array(),
		'stores'    => array()
	);
	function couponxxl_custom_term_count( $term, $taxonomy ) {
		global $couponxxl_term_count;
		if ( empty( $couponxxl_term_count[ $taxonomy ] ) ) {
			global $wpdb, $offer_type, $offer_cat, $location, $offer_tag, $offer_store, $keyword;

			$offer_store_sql = array_map( function ( $el ) {
				global $wpdb;

				return $wpdb->prepare( "%s", $el );
			}, $offer_store );

			$location_sql = array_map( function ( $el ) {
				global $wpdb;

				return $wpdb->prepare( "%s", $el );
			}, $location );

			$query = "SELECT terms1.term_id as ID, COUNT(posts.ID) AS posts 
					FROM {$wpdb->posts} AS posts 
					LEFT JOIN {$wpdb->prefix}offers AS offers ON posts.ID = offers.post_id 
					LEFT JOIN {$wpdb->term_relationships} AS termsrel1 ON posts.ID = termsrel1.object_id 
					LEFT JOIN {$wpdb->terms} AS terms1 ON termsrel1.term_taxonomy_id = terms1.term_id 
					LEFT JOIN {$wpdb->term_taxonomy} AS tax ON termsrel1.term_taxonomy_id = tax.term_taxonomy_id ";
			if ( ! empty( $offer_store ) ) {
				$query .= "LEFT JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id ";
			}
			if ( ( ! empty( $offer_cat ) && $taxonomy == 'location' ) || ( ! empty( $location ) && $taxonomy == 'offer_cat' ) ) {
				$query .= "LEFT JOIN {$wpdb->term_relationships} AS termsrel2 ON posts.ID = termsrel2.object_id  ";
				$query .= "LEFT JOIN {$wpdb->terms} AS terms2 ON termsrel2.term_taxonomy_id = terms2.term_id ";
				$query .= "LEFT JOIN {$wpdb->term_taxonomy} AS tax2 ON termsrel2.term_taxonomy_id = tax2.term_taxonomy_id ";
			}
			$query .= "
					WHERE posts.post_type = 'offer' AND posts.post_status = 'publish'
					AND offers.offer_start <= " . esc_sql( current_time( 'timestamp' ) ) . " AND offers.offer_expire > " . esc_sql( current_time( 'timestamp' ) ) . "
					AND tax.taxonomy = '" . esc_sql( $taxonomy ) . "' 
					";
			if ( ! empty( $offer_type ) ) {
				$query .= "AND offers.offer_type = '" . esc_sql( $offer_type ) . "' ";
			}
			if ( ! empty( $offer_store ) ) {
				$query .= "AND postmeta.meta_key = 'offer_store' AND postmeta.meta_value IN (" . join( ',', $offer_store_sql ) . ") ";
			}
			if ( ! empty( $keyword ) ) {
				$keywords = explode( " ", $keyword );
				$query .= "AND ";
				for ( $i = 0; $i < sizeof( $keywords ); $i ++ ) {
					$query .= "( posts.post_title LIKE '%" . esc_sql( $keywords[ $i ] ) . "%' OR posts.post_content LIKE '%" . esc_sql( $keywords[ $i ] ) . "%' ) ";
					if ( $i < sizeof( $keywords ) - 1 ) {
						$query .= "AND ";
					}
				}
			}
			if ( ( ! empty( $offer_cat ) && $taxonomy == 'location' ) || ( ! empty( $location ) && $taxonomy == 'offer_cat' ) ) {
				$slug = $taxonomy == 'offer_cat' ? join( ',', $location_sql ) : $offer_cat;
				if ( $taxonomy == 'offer_cat' ) {
					$query .= "AND terms2.slug IN ( " . $slug . " ) ";
				} else {
					$query .= "AND terms2.slug = '" . esc_sql( $slug ) . "' ";
				}
				$tax2 = $taxonomy == 'offer_cat' ? 'location' : 'offer_cat';
				$query .= "AND tax2.taxonomy = '" . esc_sql( $tax2 ) . "' ";
			}
			$query .= "GROUP BY terms1.term_id";

			$couponxxl_term_count[ $taxonomy ] = $wpdb->get_results( $query );
		}

		$count = 0;
		if ( ! empty( $couponxxl_term_count[ $taxonomy ] ) ) {
			foreach ( $couponxxl_term_count[ $taxonomy ] as $key => $item ) {
				if ( $item->ID == $term->term_id ) {
					$count = $item->posts;
					unset( $couponxxl_term_count[ $taxonomy ][ $key ] );
					break;
				}
			}
		} else {
			$count = 0;
		}

		return '<span class="count">' . $count . '</span>';

	}
}

/*
Count offers of each store basd on the filters o the search page
*/
if ( ! function_exists( 'couponxxl_custom_store_count' ) ) {
	function couponxxl_custom_store_count( $store_id ) {
		global $couponxxl_term_count;
		if ( empty( $couponxxl_term_count['stores'] ) ) {
			global $wpdb, $offer_type, $offer_cat, $location, $offer_tag, $offer_store, $keyword;
			$query = "SELECT postmeta.meta_value as ID, COUNT(posts.ID) AS posts 
					FROM {$wpdb->posts} AS posts 
					LEFT JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id 
					LEFT JOIN {$wpdb->prefix}offers AS offers ON posts.ID = offers.post_id ";
			if ( ! empty( $offer_cat ) ) {
				$query .= "LEFT JOIN {$wpdb->term_relationships} AS offer_cat_rel ON posts.ID = offer_cat_rel.object_id ";
				$query .= "LEFT JOIN {$wpdb->terms} AS offer_cat_terms ON offer_cat_rel.term_taxonomy_id = offer_cat_terms.term_id ";
				$query .= "LEFT JOIN {$wpdb->term_taxonomy} AS ofer_cat_tax ON offer_cat_rel.term_taxonomy_id = ofer_cat_tax.term_taxonomy_id ";
			}
			if ( ! empty( $location ) ) {
				$query .= "LEFT JOIN {$wpdb->term_relationships} AS location_rel ON posts.ID = location_rel.object_id  ";
				$query .= "LEFT JOIN {$wpdb->terms} AS location_terms ON location_rel.term_taxonomy_id = location_terms.term_id ";
				$query .= "LEFT JOIN {$wpdb->term_taxonomy} AS location_tax ON location_rel.term_taxonomy_id = location_tax.term_taxonomy_id ";
			}
			$query .= "
			WHERE posts.post_type = 'offer' AND posts.post_status = 'publish'
			AND offers.offer_start <= " . esc_sql( current_time( 'timestamp' ) ) . " AND offers.offer_expire > " . esc_sql( current_time( 'timestamp' ) ) . " ";
			if ( ! empty( $offer_type ) ) {
				$query .= "AND offers.offer_type = '" . esc_sql( $offer_type ) . "' ";
			}
			if ( ! empty( $keyword ) ) {
				$keywords = explode( " ", $keyword );
				$query .= "AND ";
				for ( $i = 0; $i < sizeof( $keywords ); $i ++ ) {
					$query .= "( posts.post_title LIKE '%" . esc_sql( $keywords[ $i ] ) . "%' OR posts.post_content LIKE '%" . esc_sql( $keywords[ $i ] ) . "%' ) ";
					if ( $i < sizeof( $keywords ) - 1 ) {
						$query .= "AND ";
					}
				}
			}
			if ( ! empty( $offer_cat ) ) {
				$query .= "AND offer_cat_terms.slug = '" . esc_sql( $offer_cat ) . "' ";
				$query .= "AND ofer_cat_tax.taxonomy = 'offer_cat' ";
			}
			if ( ! empty( $location ) ) {
				$query .= "AND location_terms.slug IN ( '" . esc_sql( join( ',', $location ) ) . "' ) ";
				$query .= "AND location_tax.taxonomy = 'location' ";
			}

			$query .= " AND postmeta.meta_key = 'offer_store'";

			$query .= "GROUP BY postmeta.meta_value";

			$couponxxl_term_count['stores'] = $wpdb->get_results( $query );

		}

		$count = 0;
		foreach ( $couponxxl_term_count['stores'] as $key => $item ) {
			if ( $item->ID == $store_id ) {
				$count = $item->posts;
				unset( $couponxxl_term_count['stores'][ $key ] );
				break;
			}
		}

		return '<span class="count">' . $count . '</span>';
	}
}

/*
Append shortcode style
*/
if ( ! function_exists( 'couponxxl_shortcode_style' ) ) {
	function couponxxl_shortcode_style( $style_css ) {
		$style_css = str_replace( '<style', '<style scoped', $style_css );

		return $style_css;
	}
}


/*
Custom slugs in URL
*/
$couponxxl_slugs = array(
	'offer'             => 'offer',
	'offer_type'        => 'offer_type',
	'offer_cat'         => 'offer_cat',
	'offer_tag'         => 'offer_tag',
	'location'          => 'location',
	'offer_store'       => 'offer_store',
	'store'             => 'store',
	'offer_view'        => 'offer_view',
	'offer_sort'        => 'offer_sort',
	'keyword'           => 'keyword',
	'coupon'            => 'coupon',
	'letter'            => 'letter',
	'username'          => 'username',
	'confirmation_hash' => 'confirmation_hash',
	'account'           => 'account'
);

/*
Get translated version of the slugs
*/
if ( ! function_exists( 'couponxxl_get_couponxxl_slugs' ) ) {
	function couponxxl_get_couponxxl_slugs() {
		global $couponxxl_slugs;
		foreach ( $couponxxl_slugs as &$slug ) {
			$trans = couponxxl_get_option( 'trans_' . $slug );
			if ( ! empty( $trans ) ) {
				$slug = $trans;
			}
		}
	}

	add_action( 'init', 'couponxxl_get_couponxxl_slugs', 1, 0 );
}

/*
Create queries
*/
if ( ! function_exists( 'couponxxl_append_query_string' ) ) {
	function couponxxl_append_query_string( $permalink = '', $include = array(), $exclude = array( 'coupon' ) ) {
		global $couponxxl_slugs;
		global $wp;
		if ( ! $permalink ) {
			$permalink = get_permalink();
		}

		// Map endpoint to options
		if ( get_option( 'permalink_structure' ) ) {
			if ( strstr( $permalink, '?' ) ) {
				$query_string = '?' . parse_url( $permalink, PHP_URL_QUERY );
				$permalink    = current( explode( '?', $permalink ) );
			} else {
				$query_string = '';
			}

			$permalink = trailingslashit( $permalink );
			if ( ! empty( $include ) ) {
				foreach ( $include as $arg => $value ) {
					$permalink .= $couponxxl_slugs[ $arg ] . '/' . $value . '/';
				}
			}
			foreach ( $couponxxl_slugs as $slug => $trans_slug ) {
				if ( isset( $wp->query_vars[ $trans_slug ] ) && ! isset( $include[ $slug ] ) && ! in_array( $slug, $exclude ) && ! in_array( 'all', $exclude ) && ! is_single() ) {
					$permalink .= $trans_slug . '/' . $wp->query_vars[ $trans_slug ] . '/';
				}
			}
			$permalink .= $query_string;
		} else {
			if ( ! empty( $include ) ) {
				foreach ( $include as $arg => $value ) {
					$permalink = esc_url( add_query_arg( array( $couponxxl_slugs[ $arg ] => $value ), $permalink ) );
				}
			}
			foreach ( $couponxxl_slugs as $slug => $trans_slug ) {
				if ( isset( $wp->query_vars[ $trans_slug ] ) && ! isset( $include[ $slug ] ) && ! in_array( $slug, $exclude ) && ! in_array( 'all', $exclude ) ) {
					$permalink = esc_url( add_query_arg( array( $trans_slug => $wp->query_vars[ $trans_slug ] ), $permalink ) );
				}
			}

		}

		return $permalink;
	}
}

/*
Add new rewrite rules for pretty permalinks
*/
if ( ! function_exists( 'couponxxl_add_rewrite_rules' ) ) {
	function couponxxl_add_rewrite_rules() {
		global $wp_rewrite;
		global $couponxxl_slugs;
		$new_rules    = array();
		$custom_rules = array();
		for ( $i = count( $couponxxl_slugs ); $i >= 1; $i -- ) {
			$key = str_repeat( '(' . join( '|', $couponxxl_slugs ) . ')/(.+?)/', $i );

			$key_1      = '([^/]*)/' . $key . '(page)/(.+?)/?$';
			$key_2      = '([^/]*)/' . $key . '?$';
			$rewrite_to = 'index.php?pagename=' . $wp_rewrite->preg_index( 1 );

			for ( $k = 2; $k <= ( $i * 2 ) + 1; $k += 2 ) {
				$rewrite_to .= '&' . $wp_rewrite->preg_index( $k ) . '=' . $wp_rewrite->preg_index( $k + 1 );
			}

			$custom_rules[ $key_1 ] = $rewrite_to . '&paged=' . $wp_rewrite->preg_index( $k + 1 );
			$custom_rules[ $key_2 ] = $rewrite_to;

		}

		$test              = array(
			$couponxxl_slugs['store'] . '/(.+?)/' . $couponxxl_slugs['offer_type'] . '/(.+?)/page/(.+?)/?$' => 'index.php?' . $couponxxl_slugs['store'] . '=$matches[1]&' . $couponxxl_slugs['offer_type'] . '=$matches[2]&page=$matches[3]',
			$couponxxl_slugs['store'] . '/(.+?)/' . $couponxxl_slugs['offer_type'] . '/(.+?)/?$'            => 'index.php?' . $couponxxl_slugs['store'] . '=$matches[1]&' . $couponxxl_slugs['offer_type'] . '=$matches[2]',
		);
		$wp_rewrite->rules = $test + $custom_rules + $wp_rewrite->rules;
	}

	add_action( 'generate_rewrite_rules', 'couponxxl_add_rewrite_rules' );

	function couponxxl_rewrite_tag() {
		global $couponxxl_slugs;
		foreach ( $couponxxl_slugs as $slug ) {
			add_rewrite_tag( '%' . $slug . '%', '([^&]+)' );
		}
	}

	add_action( 'init', 'couponxxl_rewrite_tag', 2, 0 );
}


/*
Register function
*/
if ( ! function_exists( 'couponxxl_register' ) ) {
	function couponxxl_register() {
		$first_name      = isset( $_POST['first_name'] ) ? esc_sql( $_POST['first_name'] ) : '';
		$last_name       = isset( $_POST['last_name'] ) ? esc_sql( $_POST['last_name'] ) : '';
		$email           = isset( $_POST['email'] ) ? esc_sql( $_POST['email'] ) : '';
		$username        = isset( $_POST['username'] ) ? esc_sql( $_POST['username'] ) : '';
		$password        = isset( $_POST['password'] ) ? esc_sql( $_POST['password'] ) : '';
		$repeat_password = isset( $_POST['repeat_password'] ) ? esc_sql( $_POST['repeat_password'] ) : '';
		$vendor          = isset( $_POST['vendor'] ) ? esc_sql( $_POST['vendor'] ) : '';
		$message         = '';

		if ( isset( $_POST['register_field'] ) ) {
			if ( wp_verify_nonce( $_POST['register_field'], 'register' ) ) {
				if ( ! isset( $_POST['captcha'] ) ) {
					if ( ! empty( $first_name ) && ! empty( $last_name ) && ! empty( $email ) && ! empty( $username ) && ! empty( $password ) && ! empty( $repeat_password ) ) {
						if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
							if ( stristr( $username, " " ) === false && stristr( $username, "." ) === false ) {
								if ( $password == $repeat_password ) {
									if ( ! username_exists( $username ) ) {
										if ( ! email_exists( $email ) ) {
											$user_id = wp_insert_user( array(
												'user_login' => $username,
												'user_pass'  => $password,
												'user_email' => $email
											) );
											if ( ! is_wp_error( $user_id ) ) {
												wp_update_user( array(
													'ID'   => $user_id,
													'role' => 'editor'
												) );
												$confirmation_hash = couponxxl_confirm_hash();
												update_user_meta( $user_id, "first_name", $first_name );
												update_user_meta( $user_id, "last_name", $last_name );
												update_user_meta( $user_id, 'user_active_status', 'inactive' );
												update_user_meta( $user_id, 'confirmation_hash', $confirmation_hash );
												if ( $vendor == '1' ) {
													update_user_meta( $user_id, 'cxxl_account_type', 'vendor' );
												} else {
													update_user_meta( $user_id, 'cxxl_account_type', 'buyer' );
												}

												$confirmation_message = couponxxl_get_option( 'registration_message' );
												$confirmation_link    = couponxxl_get_permalink_by_tpl( 'page-tpl_register' );
												$confirmation_link    = couponxxl_append_query_string( $confirmation_link, array(
													'username'          => remove_accents( $username ),
													'confirmation_hash' => $confirmation_hash
												) );

												$confirmation_message = str_replace( '%LINK%', $confirmation_link, $confirmation_message );

												$registration_subject = couponxxl_get_option( 'registration_subject' );

												$email_sender = couponxxl_get_option( 'email_sender' );
												$name_sender  = couponxxl_get_option( 'name_sender' );
												$headers      = array();
												$headers[]    = "MIME-Version: 1.0";
												$headers[]    = "Content-Type: text/html; charset=UTF-8";
												$headers[]    = "From: " . $name_sender . " <" . $email_sender . ">";

												$info = wp_mail( $email, $registration_subject, $confirmation_message, $headers );
												if ( $info ) {
													$message = '<div class="alert alert-success">' . esc_html__( 'Thank you for registering, an email to confirm your email address is sent to your inbox.', 'couponxxl' ) . '</div>';
													if ( $vendor == '1' ) {
														wp_insert_post( array(
															'post_type'   => 'store',
															'post_status' => 'publish',
															'post_title'  => esc_html__( 'Store', 'couponxxl' ) . $user_id,
															'post_author' => $user_id
														) );
													}
													$success = true;
												} else {
													$message = '<div class="alert alert-danger">' . esc_html__( 'There was an error trying to send an email', 'couponxxl' ) . '</div>';
												}
											} else {
												$message = '<div class="alert alert-danger">' . esc_html__( 'There was an error while trying to register you', 'couponxxl' ) . '</div>';
											}
										} else {
											$message = '<div class="alert alert-danger">' . esc_html__( 'Email is already registered', 'couponxxl' ) . '</div>';
										}
									} else {
										$message = '<div class="alert alert-danger">' . esc_html__( 'Username is already registered', 'couponxxl' ) . '</div>';
									}
								} else {
									$message = '<div class="alert alert-danger">' . esc_html__( 'Provided passwords do not match', 'couponxxl' ) . '</div>';
								}
							} else {
								$message = '<div class="alert alert-danger">' . esc_html__( 'Username can not hold empty spaces or dots', 'couponxxl' ) . '</div>';
							}
						} else {
							$message = '<div class="alert alert-danger">' . esc_html__( 'Email address is invalid', 'couponxxl' ) . '</div>';
						}
					} else {
						$message = '<div class="alert alert-danger">' . esc_html__( 'All fields are required', 'couponxxl' ) . '</div>';
					}
				} else {
					$message = '<div class="alert alert-danger">' . esc_html__( 'Captcha is wrong', 'couponxxl' ) . '</div>';
				}
			} else {
				$message = '<div class="alert alert-danger">' . esc_html__( 'You do not permission for your action', 'couponxxl' ) . '</div>';
			}
		}

		echo json_encode( array(
			'message' => $message,
		) );
		die();
	}

	add_action( 'wp_ajax_register', 'couponxxl_register' );
	add_action( 'wp_ajax_nopriv_register', 'couponxxl_register' );
}

/*
Make sure SQL can be executed
*/
if ( ! function_exists( 'couponxxl_safe_sql' ) ) {
	function couponxxl_safe_sql() {
		global $wpdb;
		$wpdb->query( 'SET SESSION SQL_BIG_SELECTS=1' );
	}

	add_action( 'init', 'couponxxl_safe_sql' );
}

/*
Wrap messages in admin
*/
function couponxxl_wrap_message( $message, $type ) {
	return '
    <div id="message" class="' . esc_attr( $type ) . ' notice">
        <p>' . $message . '.</p>
    </div>';
}

/*
List categories on the search sidebar
*/
if ( ! function_exists( 'couponxxl_list_search_sidebar_cats' ) ) {
	function couponxxl_list_search_sidebar_cats( $ancestors, $parent, $selected = '', $search_show_count, $permalink ) {
		global $couponxxl_slugs;
		$children = get_terms( 'offer_cat', array( 'parent' => $parent ) );
		if ( ! empty( $children ) ) {
			echo '<ul class="list-unstyled">';
			foreach ( $children as $child ) {
				$li_class = in_array( $child->term_id, $ancestors ) ? 'active' : '';
				if ( ! empty( $selected ) ) {
					$li_class .= $child->slug == $selected->slug ? ' current' : '';
				}

				$count = '';
				if ( $search_show_count == 'yes' ) {
					$count = couponxxl_custom_term_count( $child, 'offer_cat' );
				}
				if ( empty( $ancestors ) || ( ! empty( $ancestors ) && in_array( $child->term_id, $ancestors ) ) || ( ! empty( $ancestors ) && ! empty( $selected ) && $child->parent == $selected->term_id ) ) {
					echo '<li class="' . esc_attr( $li_class ) . '"><a href="javascript:;" data-cat="' . esc_attr( $child->slug ) . '">' . $child->name . ' <span class="count">(' . $count . ')</span></a>';
					if ( ! empty( $li_class ) ) {
						couponxxl_list_search_sidebar_cats( $ancestors, $child->term_id, $selected, $search_show_count, $permalink );
						echo '</li>';
					} else {
						echo '</li>';
					}
				}
			}
			echo '</ul>';
		}
	}
}

/*
List locations in hierarchy for the modal
*/
if ( ! function_exists( 'couponxxl_modal_lcoations' ) ) {
	function couponxxl_modal_locations( $locations, $location, $search_show_count ) {
		global $couponxxl_slugs;
		echo '<ul class="list-unstyled">';
		foreach ( $locations as $location_term ) {
			$count = '';
			if ( $search_show_count = 'yes' ) {
				$count = couponxxl_custom_term_count( $location_term, 'location' );
			}
			echo '
        <li class="' . ( in_array( $location_term->slug, $location ) ? 'active current' : '' ) . '">
            <input type="checkbox" name="' . $couponxxl_slugs['location'] . '[]" id="m_location_' . esc_attr( $location_term->slug ) . '" value="' . esc_attr( $location_term->slug ) . '" ' . ( in_array( $location_term->slug, $location ) ? 'checked="checked"' : '' ) . '>
            <label for="m_location_' . esc_attr( $location_term->slug ) . '">' . $location_term->name . ' <span class="count">(' . $count . ')</span></label>';
			if ( ! empty( $location_term->children ) ) {
				couponxxl_modal_locations( $location_term->children, $location, $search_show_count );
			}
			echo '</li>';
		}
		echo '</ul>';
	}
}

/*
Disable canonical redirection on store single page so  offers can be paginated
*/
if ( ! function_exists( 'couponxxl_allow_store_pagination' ) ) {
	function couponxxl_allow_store_pagination( $redirect_url ) {
		if ( is_singular( 'store' ) ) {
			$redirect_url = false;
		}

		return $redirect_url;
	}

	add_filter( 'redirect_canonical', 'couponxxl_allow_store_pagination' );
}

/*
Save store details
*/
if ( ! function_exists( 'couponxxl_save_store' ) ) {
	function couponxxl_save_store() {
		$store_id          = isset( $_POST['store_id'] ) ? $_POST['store_id'] : '';
		$store_title       = isset( $_POST['store_title'] ) ? $_POST['store_title'] : '';
		$store_description = isset( $_POST['store_description'] ) ? $_POST['store_description'] : '';
		$store_facebook    = isset( $_POST['store_facebook'] ) ? $_POST['store_facebook'] : '';
		$store_twitter     = isset( $_POST['store_twitter'] ) ? $_POST['store_twitter'] : '';
		$store_link        = isset( $_POST['store_link'] ) ? $_POST['store_link'] : '';
		$store_google      = isset( $_POST['store_google'] ) ? $_POST['store_google'] : '';
		$store_rss         = isset( $_POST['store_rss'] ) ? $_POST['store_rss'] : '';
		$store             = get_post( $store_id );
		$response          = array();
		if ( ! empty( $store ) && ! empty( $store_title ) ) {
			wp_update_post( array(
				'ID'           => $store_id,
				'post_title'   => $store_title,
				'post_name'    => sanitize_title( $store_title ),
				'post_content' => $store_description,
			) );

			update_post_meta( $store_id, 'store_facebook', $store_facebook );
			update_post_meta( $store_id, 'store_twitter', $store_twitter );
			update_post_meta( $store_id, 'store_facebook', $store_facebook );
			update_post_meta( $store_id, 'store_google', $store_google );
			update_post_meta( $store_id, 'store_rss', $store_rss );
			update_post_meta( $store_id, 'store_link', $store_link );

			$response['message'] = '<div class="alert alert-success">' . esc_html__( 'Store details are updated', 'couponxxl' ) . '</div>';
		} else {
			$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Store title can not be empty', 'couponxxl' ) . '</div>';
		}
		echo json_encode( $response );
		die();
	}

	add_action( 'wp_ajax_update_store', 'couponxxl_save_store' );
	add_action( 'wp_ajax_nopriv_update_store', 'couponxxl_save_store' );
}

/*
Save store logo
*/
if ( ! function_exists( 'couponxxl_save_store_logo' ) ) {
	function couponxxl_save_store_logo() {
		$store_id = isset( $_POST['store_id'] ) ? $_POST['store_id'] : '';
		$image_id = isset( $_POST['image_id'] ) ? $_POST['image_id'] : '';
		$store    = get_post( $store_id );
		if ( ! empty( $store ) && ! empty( $image_id ) ) {
			set_post_thumbnail( $store_id, $image_id );
			echo wp_get_attachment_image( $image_id, 'thumbnail' );
		}
		die();
	}

	add_action( 'wp_ajax_update_store_logo', 'couponxxl_save_store_logo' );
	add_action( 'wp_ajax_nopriv_update_store_logo', 'couponxxl_save_store_logo' );
}

/*
Verify if voucher exists and if it is not used
*/
if ( ! function_exists( 'couponxxl_verify_voucher' ) ) {
	function couponxxl_verify_voucher( $voucher_id = '' ) {
		$voucher_code = isset( $_POST['voucher_code'] ) ? $_POST['voucher_code'] : '';
		$vendor_id    = isset( $_POST['vendor_id'] ) ? $_POST['vendor_id'] : '';
		$response     = array();
		if ( ! empty( $voucher_code ) || ! empty( $voucher_id ) ) {
			global $wpdb;
			if ( ! empty( $voucher_id ) ) {
				$voucher = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}order_items AS order_items LEFT JOIN {$wpdb->prefix}vouchers AS vouchers ON order_items.item_id = vouchers.item_id WHERE order_items.seller_id = %d AND vouchers.voucher_id = %d", $vendor_id, $voucher_id ) );
			} else {
				$voucher = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}order_items AS order_items LEFT JOIN {$wpdb->prefix}vouchers AS vouchers ON order_items.item_id = vouchers.item_id WHERE order_items.seller_id = %d AND vouchers.voucher_code = %s", $vendor_id, $voucher_code ) );
			}
			if ( ! empty( $voucher ) ) {
				$voucher             = array_shift( $voucher );
				$response['message'] = '
			<div class="voucher-check-wrap table-responsive">
				<table>
					<tr>
						<th>
							' . esc_html__( 'Code', 'couponxxl' ) . '
						</th>
						<th>
							' . esc_html__( 'Offer', 'couponxxl' ) . '
						</th>
						<th>
							' . esc_html__( 'Status', 'couponxxl' ) . '
						</th>
						<th>
							' . esc_html__( 'Action', 'couponxxl' ) . '
						</th>
					</tr>
					<tr>
						<td>
							' . $voucher->voucher_code . '
						</td>
						<td>
							<a href="' . get_permalink( $voucher->offer_id ) . '" target="_blank">
								' . $voucher->offer_title . '
							</a>
						</td>
						<td>
							' . ( $voucher->voucher_status == '1' ? esc_html__( 'Used', 'couponxxl' ) : esc_html__( 'Not Used', 'couponxxl' ) ) . '
						</td>
						<td>
							<a href="javascript:;" class="update-voucher" data-voucher_id="' . esc_attr( $voucher->voucher_id ) . '" data-status="' . ( $voucher->voucher_status == '1' ? esc_attr( '0' ) : esc_attr( '1' ) ) . '" title="' . ( $voucher->voucher_status == '1' ? esc_attr__( 'Mark voucher as not used', 'couponxxl' ) : esc_attr__( 'Mark voucher as used', 'couponxxl' ) ) . '">' . ( $voucher->voucher_status == '1' ? '<i class="fa fa-times"></i>' : '<i class="fa fa-check"></i>' ) . '</a>
						</td>
					</tr>
				<table>
			</div>
			';
			} else {
				$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Voucher code does not exists', 'couponxxl' ) . '</div>';
			}
		} else {
			$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Voucher code is required', 'couponxxl' ) . '</div>';
		}

		echo json_encode( $response );
		die();
	}

	add_action( 'wp_ajax_verify_voucher', 'couponxxl_verify_voucher' );
	add_action( 'wp_ajax_nopriv_verify_voucher', 'couponxxl_verify_voucher' );
}

/*
Update voucher status and return table again
*/
if ( ! function_exists( 'couponxxl_update_voucher_from_form' ) ) {
	function couponxxl_update_voucher_from_form() {
		couponxxl_update_voucher( false );
		couponxxl_verify_voucher( $_POST['voucher_id'] );
	}

	add_action( 'wp_ajax_update_voucher_from_form', 'couponxxl_update_voucher_from_form' );
	add_action( 'wp_ajax_nopriv_update_voucher_from_form', 'couponxxl_update_voucher_from_form' );
}

/*
Get current user type
*/
if ( ! function_exists( 'couponxxl_get_account_type' ) ) {
	$couponxxl_account_type = '';
	function couponxxl_get_account_type() {
		global $couponxxl_account_type;
		if ( empty( $couponxxl_account_type ) ) {
			$couponxxl_account_type = get_user_meta( get_current_user_id(), 'cxxl_account_type', true );
		}

		return $couponxxl_account_type;
	}
}

/*
Get ID of the vendor based if the account is vendor or ots agent
*/
if ( ! function_exists( 'couponxxl_get_vendor_id' ) ) {
	$couponxxl_vendor_id = '';
	function couponxxl_get_vendor_id() {
		global $couponxxl_vendor_id;
		if ( empty( $couponxxl_vendor_id ) ) {
			$account_type = get_user_meta( get_current_user_id(), 'cxxl_account_type', true );
			if ( $account_type == 'vendor' ) {
				$couponxxl_vendor_id = get_current_user_id();
			} else if ( $account_type == 'agent' ) {
				$couponxxl_vendor_id = get_user_meta( get_current_user_id(), 'cxxl_vendor_agent_parent', true );
			}
		}

		return $couponxxl_vendor_id;
	}
}
/*
Add new agent
*/
if ( ! function_exists( 'couponxxl_add_agent' ) ) {
	function couponxxl_add_agent() {
		$response        = array();
		$agent_username  = isset( $_POST['agent_username'] ) ? $_POST['agent_username'] : '';
		$agent_email     = isset( $_POST['agent_email'] ) ? $_POST['agent_email'] : '';
		$password        = isset( $_POST['password'] ) ? $_POST['password'] : '';
		$repeat_password = isset( $_POST['repeat_password'] ) ? $_POST['repeat_password'] : '';

		if ( ! empty( $agent_username ) && ! empty( $agent_email ) ) {
			if ( ! username_exists( $agent_username ) ) {
				if ( ! email_exists( $agent_email ) ) {
					if ( $repeat_password == $password ) {
						$user_id = wp_insert_user( array(
							'user_login' => $agent_username,
							'user_pass'  => $password,
							'user_email' => $agent_email
						) );
						wp_update_user( array(
							'ID'   => $user_id,
							'role' => 'editor'
						) );
						update_user_meta( $user_id, 'cxxl_vendor_agent_parent', get_current_user_id() );
						update_user_meta( $user_id, 'cxxl_account_type', 'agent' );
						$response['message'] = '<div class="alert alert-success">' . esc_html__( 'Agent has been created', 'couponxxl' ) . '</div>';

						$new_agent_message = couponxxl_get_option( 'new_agent_message' );
						$new_agent_message = str_replace( "%USERNAME%", $agent_username, $new_agent_message );
						$new_agent_message = str_replace( "%PASSWORD%", $password, $new_agent_message );

						$email_sender = couponxxl_get_option( 'email_sender' );
						$name_sender  = couponxxl_get_option( 'name_sender' );
						$headers      = array();
						$headers[]    = "MIME-Version: 1.0";
						$headers[]    = "Content-Type: text/html; charset=UTF-8";
						$headers[]    = "From: " . $name_sender . " <" . $email_sender . ">";

						$new_agent_subject = couponxxl_get_option( 'new_agent_subject' );

						$message_info = @wp_mail( $agent_email, $new_agent_subject, $new_agent_message, $headers );

						$response['message'] = '<div class="alert alert-success">' . esc_html__( 'Agent has been created and an email is sent to the email you have provided', 'couponxxl' ) . '</div>';
					} else {
						$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Passwords do not match', 'couponxxl' ) . '</div>';
					}
				} else {
					$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Agent with that email is already registered', 'couponxxl' ) . '</div>';
				}
			} else {
				$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Agent with that username is already registered', 'couponxxl' ) . '</div>';
			}
		} else {
			$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Agent name and email are required', 'couponxxl' ) . '</div>';
		}
		echo json_encode( $response );
		die();
	}

	add_action( 'wp_ajax_add_agent', 'couponxxl_add_agent' );
	add_action( 'wp_ajax_nopriv_add_agent', 'couponxxl_add_agent' );
}

if ( ! function_exists( 'couponxxl_edit_agent' ) ) {
	function couponxxl_edit_agent() {
		$response        = array();
		$agent_id        = isset( $_POST['agent_id'] ) ? $_POST['agent_id'] : '';
		$agent_email     = isset( $_POST['agent_email'] ) ? $_POST['agent_email'] : '';
		$password        = isset( $_POST['password'] ) ? $_POST['password'] : '';
		$repeat_password = isset( $_POST['repeat_password'] ) ? $_POST['repeat_password'] : '';

		$user = get_user_by( 'id', $agent_id );
		if ( ! empty( $user ) ) {
			$user    = $user->data;
			$proceed = true;
			if ( $user->user_email !== $agent_email ) {
				if ( email_exists( $agent_email ) ) {
					$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Agent with that email is already registered', 'couponxxl' ) . '</div>';
					$proceed             = false;
				}
			}

			if ( ! empty( $password ) ) {
				if ( $repeat_password !== $password ) {
					$response['message'] = '<div class="alert alert-danger">' . esc_html__( 'Passwords do not match', 'couponxxl' ) . '</div>';
					$proceed             = false;
				}
			}

			if ( $proceed ) {
				$update_fields = array(
					'ID'         => $agent_id,
					'user_email' => $agent_email,
				);
				if ( ! empty( $password ) ) {
					$update_fields['user_pass'] = $password;
				}
				wp_update_user( $update_fields );
				$response['message'] = '<div class="alert alert-success">' . esc_html__( 'Agent\'s profile is edited', 'couponxxl' ) . '</div>';
			}
		}

		echo json_encode( $response );
		die();
	}

	add_action( 'wp_ajax_edit_agent', 'couponxxl_edit_agent' );
	add_action( 'wp_ajax_nopriv_edit_agent', 'couponxxl_edit_agent' );
}

/*
Add filter by letter for stores
Find all with title which starts wit the selected letter
*/
if ( ! function_exists( 'couponxxl_filter_stores_by_letter' ) ) {
	function couponxxl_filter_stores_by_letter( $sql ) {
		global $wpdb, $couponxxl_slugs;
		$sql .= $wpdb->prepare( " AND LOWER( post_title ) LIKE %s ", mb_strtolower( get_query_var( $couponxxl_slugs['letter'] ) ) . '%' );

		return $sql;
	}
}

/*
Get all store letters
*/
if ( ! function_exists( 'couponxxl_get_store_letters' ) ) {
	function couponxxl_get_store_letters( $store_link, $selected_letter ) {
		global $wpdb, $couponxxl_slugs;
		$letters = $wpdb->get_results( "SELECT DISTINCT LEFT(post_title, 1) AS letter FROM {$wpdb->posts} WHERE post_type = 'store' ORDER BY letter ASC" );
		if ( ! empty( $letters ) ) {
			foreach ( $letters as $letter_item ) {
				?>
				<a href="<?php echo esc_url( couponxxl_append_query_string( $store_link, array( $couponxxl_slugs['letter'] => $letter_item->letter ) ) ) ?>"
				   class="<?php echo mb_strtolower( $selected_letter ) == mb_strtolower( $letter_item->letter ) ? esc_attr( 'active' ) : esc_attr( '' ) ?>">
					<?php echo mb_strtoupper( $letter_item->letter ) ?>
				</a>
				<?php
			}
		}
	}
}


/*
Create export button
*/
if ( ! function_exists( 'couponxxl_create_menu_items' ) ) {
	function couponxxl_create_menu_items() {
		add_theme_page( esc_html__( 'Export / Import Custom Data', 'couponxxl' ), esc_html__( 'Export / Import Custom Data', 'couponxxl' ), 'switch_themes', 'cxxl_export_import', 'couponxxl_cd_import_export' );
	}

	add_action( 'admin_menu', 'couponxxl_create_menu_items' );
}

/*
Include export import file
*/
if ( ! function_exists( 'couponxxl_cd_import_export' ) ) {
	function couponxxl_cd_import_export() {
		include( couponxxl_load_path( 'includes/cd-exp-imp.php' ) );
	}
}

/*
Export custom data values
*/
if ( ! function_exists( 'couponxxl_export_cd_values' ) ) {
	function couponxxl_export_cd_values() {
		global $wpdb;
		$tables = array(
			'offers',
			'order_items',
			'store_markers',
			'vouchers'
		);

		$exp_data = array();

		foreach ( $tables as $table ) {
			$table_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}" . esc_sql( $table ) . "", ARRAY_A );
			if ( ! empty( $table_data ) ) {
				$exp_data[ $table ] = $table_data;
			}
		}

		echo '<textarea class="cd-import">' . json_encode( $exp_data ) . '</textarea>';
	}
}


/*
Import cutsom data values
*/
if ( ! function_exists( 'couponxxl_import_cd_values' ) ) {
	function couponxxl_import_cd_values() {
		global $wpdb;
		$tables = array(
			'offers'        => array( '%d', '%d', '%s', '%d', '%d', '%s', '%s', '%f', '%d' ),
			'order_items'   => array( '%d', '%d', '%s', '%s', '%s', '%s' ),
			'store_markers' => array( '%d', '%d', '%s', '%d', '%d', '%d', '%s', '%f', '%f', '%d', '%s' ),
			'vouchers'      => array( '%d', '%d', '%d', '%s' )
		);
		if ( ! empty( $_POST['cxxl_custom_data'] ) ) {
			$cxxl_custom_data = json_decode( stripslashes( $_POST['cxxl_custom_data'] ), true );
			if ( json_last_error() > 0 ) {
				$cxxl_custom_data = json_decode( $_POST['cxxl_custom_data'], true );
			}
			if ( ! empty( $cxxl_custom_data ) ) {
				foreach ( $cxxl_custom_data as $table => $data ) {
					foreach ( $data as $row ) {
						$info = $wpdb->insert( $wpdb->prefix . $table, $row, $tables[ $table ] );
					}
				}
				?>
				<div class="updated notice notice-success is-dismissible">
					<p><?php esc_html_e( 'Import process finished', 'couponxxl' ) ?></p>
					<button type="button" class="notice-dismiss"><span
							class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'couponxxl' ) ?></span>
					</button>
				</div>
				<?php
			}
		}
	}
}

require_once( couponxxl_load_path( 'includes/google-fonts.php' ) );
require_once( couponxxl_load_path( 'includes/awesome-icons.php' ) );
require_once( couponxxl_load_path( 'includes/widgets.php' ) );

require_once( couponxxl_load_path( 'includes/theme-options.php' ) );

require_once( couponxxl_load_path( 'includes/classes/class.mollie.php' ) );
require_once( couponxxl_load_path( 'includes/classes/class.paypal.php' ) );

require_once( couponxxl_load_path( 'includes/classes/class.cart.php' ) );
require_once( couponxxl_load_path( 'includes/classes/class.order.php' ) );

require_once( couponxxl_load_path( 'includes/gallery.php' ) );

require_once( couponxxl_load_path( 'includes/classes/class.offers.php' ) );
require_once( couponxxl_load_path( 'includes/profile-pages/vendor/insert-offer.php' ) );

require_once( couponxxl_load_path( 'includes/store-locations.php' ) );
require_once( couponxxl_load_path( 'includes/deal-locations.php' ) );

foreach ( glob( get_template_directory() . "/includes/shortcodes/*.php" ) as $filename ) {
	require_once( couponxxl_load_path( 'includes/shortcodes/' . wp_basename( $filename ) ) );
}

require_once( couponxxl_load_path( 'includes/order-management.php' ) );
require_once( couponxxl_load_path( 'includes/thumbs-rate.php' ) );

if ( is_admin() ) {
	require_once( couponxxl_load_path( 'includes/classes/class.tgm-plugin-activation.php' ) );
	require_once( couponxxl_load_path( 'includes/offer-cat-icon.php' ) );
	require_once( couponxxl_load_path( 'includes/menu-extender.php' ) );
	require_once( couponxxl_load_path( 'includes/classes/class.custom-bulk.php' ) );
	require_once( couponxxl_load_path( 'includes/shortcodes.php' ) );
}
?>
