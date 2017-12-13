<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: https://docs.reduxframework.com
 * */

global $couponxxl_opts;

if ( ! class_exists( 'CouponXXL_Options' ) ) {

	class CouponXXL_Options {

		public $args = array();
		public $sections = array();
		public $theme;
		public $ReduxFramework;

		public function __construct() {

			if ( ! class_exists( 'ReduxFramework' ) ) {
				return;
			}

			// This is needed. Bah WordPress bugs.  ;)
			if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
				$this->initSettings();
			} else {
				add_action( 'plugins_loaded', array(
					$this,
					'initSettings'
				), 10 );
			}

		}

		public function initSettings() {
			// Just for demo purposes. Not needed per say.
			$this->theme = wp_get_theme();

			// Set the default arguments
			$this->setArguments();

			// Create the sections and fields
			$this->setSections();

			if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
				return;
			}

			// If Redux is running as a plugin, this will remove the demo notice and links
			//add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

			$this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
		}

		// Remove the demo link and the notice of integrated demo from the redux-framework plugin
		function remove_demo() {

			// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
			if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
				remove_filter( 'plugin_row_meta', array(
					ReduxFrameworkPlugin::instance(),
					'plugin_metalinks'
				), null, 2 );

				// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
				remove_action( 'admin_notices', array(
					ReduxFrameworkPlugin::instance(),
					'admin_notices'
				) );
			}
		}

		public function setSections() {

			/**
			 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
			 * */
			// Background Patterns Reader
			$sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
			$sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
			$sample_patterns      = array();

			if ( is_dir( $sample_patterns_path ) ):
				if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ):
					$sample_patterns = array();
					while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

						if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
							$name              = explode( '.', $sample_patterns_file );
							$name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
							$sample_patterns[] = array(
								'alt' => $name,
								'img' => $sample_patterns_url . $sample_patterns_file
							);
						}
					}
				endif;
			endif;

			/////////////////////////////////////////////////////////////////////////////// 1. OVERALL //

			$this->sections[] = array(
				'title'  => esc_html__( 'Overall Setup', 'couponxxl' ),
				'desc'   => esc_html__( 'Here in overall setup section you can edit basic settings related to overall website.', 'couponxxl' ),
				'icon'   => 'el-icon-cogs',
				'indent' => true,
				'fields' => array()
			);

			// Direction //
			$this->sections[] = array(
				'title'      => esc_html__( 'Content Direction', 'couponxxl' ),
				'desc'       => esc_html__( 'Choose overall website text direction which can be RTL (right to left) or LTR (left to right).', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'      => 'direction',
						'type'    => 'select',
						'options' => array(
							'ltr' => esc_html__( 'LTR', 'couponxxl' ),
							'rtl' => esc_html__( 'RTL', 'couponxxl' )
						),
						'title'   => esc_html__( 'Choose site content direction.', 'couponxxl' ),
						'desc'    => esc_html__( 'Choose overall website text direction which can be RTL (right to left) or LTR (left to right).', 'couponxxl' ),
						'default' => 'ltr'
					),

				)
			);

			// Theme Usage //
			$this->sections[] = array(
				'title'      => esc_html__( 'Theme Usage', 'couponxxl' ),
				'desc'       => esc_html__( 'Choose will you use CouponXXL for coupons only, deals only or for both.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'      => 'theme_usage',
						'type'    => 'select',
						'options' => array(
							'all'    => esc_html__( 'Coupons & Deals', 'couponxxl' ),
							'coupon' => esc_html__( 'Coupons Only', 'couponxxl' ),
							'deal'   => esc_html__( 'Deals Only', 'couponxxl' )
						),
						'title'   => esc_html__( 'Choose Purpose', 'couponxxl' ),
						'desc'    => esc_html__( 'Choose will you use CouponXXL for coupons only, deals only or for both.', 'couponxxl' ),
						'default' => 'all'
					),
					//Number of home sidebars
					array(
						'id'      => 'home_sidebars',
						'type'    => 'text',
						'title'   => esc_html__( 'Home Sidebars', 'couponxxl' ),
						'desc'    => esc_html__( 'Input hom many home sidebars you wish to use.', 'couponxxl' ),
						'default' => '2'
					),
				)
			);

			// Theme Usage //
			$this->sections[] = array(
				'title'      => esc_html__( 'Permalinks', 'couponxxl' ),
				'desc'       => esc_html__( 'Translate permalinks.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'      => 'trans_offer_type',
						'type'    => 'text',
						'title'   => esc_html__( 'Offer Type Slug', 'couponxxl' ),
						'desc'    => esc_html__( 'Input custom slug for offer type ( only small letters and underscore ).', 'couponxxl' ),
						'default' => 'offer_type'
					),
					array(
						'id'      => 'trans_offer_cat',
						'type'    => 'text',
						'title'   => esc_html__( 'Offer Category Slug', 'couponxxl' ),
						'desc'    => esc_html__( 'Input custom slug for offer category ( only small letters and underscore ).', 'couponxxl' ),
						'default' => 'offer_cat'
					),
					array(
						'id'      => 'trans_offer_tag',
						'type'    => 'text',
						'title'   => esc_html__( 'Offer Tag Slug', 'couponxxl' ),
						'desc'    => esc_html__( 'Input custom slug for offer tag ( only small letters and underscore ).', 'couponxxl' ),
						'default' => 'offer_tag'
					),
					array(
						'id'      => 'trans_location',
						'type'    => 'text',
						'title'   => esc_html__( 'Offer Location Slug', 'couponxxl' ),
						'desc'    => esc_html__( 'Input custom slug for offer location ( only small letters and underscore ).', 'couponxxl' ),
						'default' => 'location'
					),
					array(
						'id'      => 'trans_offer_store',
						'type'    => 'text',
						'title'   => esc_html__( 'Offer Store Slug', 'couponxxl' ),
						'desc'    => esc_html__( 'Input custom slug for offer store for the search purposes ( only small letters and underscore ).', 'couponxxl' ),
						'default' => 'offer_store'
					),
					array(
						'id'      => 'trans_keyword',
						'type'    => 'text',
						'title'   => esc_html__( 'Offer Keyword Slug', 'couponxxl' ),
						'desc'    => esc_html__( 'Input custom slug for offer keyword ( only small letters and underscore ).', 'couponxxl' ),
						'default' => 'keyword'
					),
					array(
						'id'      => 'trans_store',
						'type'    => 'text',
						'title'   => esc_html__( 'Store Custom Post Type Slug', 'couponxxl' ),
						'desc'    => esc_html__( 'Input custom slug for store custom post type ( only small letters and underscore ).', 'couponxxl' ),
						'default' => 'store'
					),
					array(
						'id'      => 'trans_letter',
						'type'    => 'text',
						'title'   => esc_html__( 'Store Letter Custom Post Type Slug', 'couponxxl' ),
						'desc'    => esc_html__( 'Input custom slug for store letter custom post type ( only small letters and underscore ).', 'couponxxl' ),
						'default' => 'letter'
					),
					array(
						'id'      => 'trans_offer',
						'type'    => 'text',
						'title'   => esc_html__( 'Offer Custom Post Type Slug', 'couponxxl' ),
						'desc'    => esc_html__( 'Input custom slug for offer custom post type ( only small letters and underscore ).', 'couponxxl' ),
						'default' => 'offer'
					),
				)
			);


			//////////////////////////////////////////////////////////////////////////// 3. HEADER //
			$this->sections[] = array(
				'title'  => esc_html__( 'Header', 'couponxxl' ),
				'desc'   => esc_html__( 'Header CouponXXL Settings', 'couponxxl' ),
				'icon'   => '',
				'fields' => array(
					array(
						'id'       => 'site_logo',
						'type'     => 'media',
						'title'    => esc_html__( 'Site Logo', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Upload site logo', 'couponxxl' )
					),
					array(
						'id'       => 'site_logo_padding',
						'type'     => 'text',
						'title'    => esc_html__( 'Logo Padding', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Set padding for logo if needed ( set 0 if not )', 'couponxxl' )
					),
					array(
						'id'      => 'quick_search_chars',
						'type'    => 'text',
						'title'   => esc_html__( 'Quick Seach Min Chars', 'couponxxl' ),
						'desc'    => esc_html__( 'Input number of chars for the quick search field ( best 3, 4, 5 )', 'couponxxl' ),
						'default' => '3'
					)

				)
			);

			// Navigation //
			$this->sections[] = array(
				'title'      => esc_html__( 'Navigation', 'couponxxl' ),
				'desc'       => esc_html__( 'Set up basic things for navigation.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'enable_sticky',
						'type'     => 'select',
						'title'    => esc_html__( 'Enable Sticky Navigation', 'couponxxl' ),
						'compiler' => 'true',
						'options'  => array(
							'yes' => esc_html__( 'Yes', 'couponxxl' ),
							'no'  => esc_html__( 'No', 'couponxxl' ),
						),
						'desc'     => esc_html__( 'Show or hide sticky navigation', 'couponxxl' ),
						'default'  => 'no'
					)

				)
			);

			// Mega Menu //
			$this->sections[] = array(
				'title'      => esc_html__( 'Mega Menu', 'couponxxl' ),
				'desc'       => esc_html__( 'Set up mega menu.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'      => 'mega_menu_sidebars',
						'type'    => 'text',
						'title'   => esc_html__( 'Mega Menu Sidebars', 'couponxxl' ),
						'desc'    => esc_html__( 'Input number of mega menu sidebars you wish to use.', 'couponxxl' ),
						'default' => '5'
					),
				)
			);


			////////////////////////////////////////////////////////////////////// 4. COPYRIGHTS //
			// Copyrights //
			$this->sections[] = array(
				'title'  => esc_html__( 'Copyrights', 'couponxxl' ),
				'desc'   => esc_html__( 'Copyrights content.', 'couponxxl' ),
				'icon'   => '',
				'fields' => array(
					array(
						'id'       => 'footer_copyrights',
						'type'     => 'text',
						'title'    => esc_html__( 'Copyrights', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input copyrights', 'couponxxl' )
					)

				)
			);


			///////////////////////////////////////////////////////////////////////////////////////////// 5. HOME PAGE //
			$this->sections[] = array(
				'title'  => esc_html__( 'Home Page', 'couponxxl' ),
				'desc'   => esc_html__( 'Home Page CouponXXL Settings', 'couponxxl' ),
				'icon'   => '',
				'fields' => array(
					array(
						'id'       => 'home_divider_section',
						'type'     => 'select',
						'title'    => esc_html__( 'Home Search / Map / Slider', 'couponxxl' ),
						'compiler' => 'true',
						'options'  => array(
							''       => esc_html__( 'None', 'couponxxl' ),
							'slider' => esc_html__( 'Featured Slider', 'couponxxl' ),
							'search' => esc_html__( 'Search', 'couponxxl' ),
							'map'    => esc_html__( 'Map', 'couponxxl' )
						),
						'desc'     => esc_html__( 'Select what to show on home page bellow the navigation', 'couponxxl' ),
					),
					array(
						'id'       => 'big_map_slider_height',
						'type'     => 'text',
						'title'    => esc_html__( 'Big Map Height', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input height of the big map in pixels which is also used as height for the slider of featured offers', 'couponxxl' ),
						'default'  => '400px'
					),
					array(
						'id'       => 'home_search_bg_image',
						'type'     => 'media',
						'title'    => esc_html__( 'Home Search Background Image', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Select background image for the big search section on the home page', 'couponxxl' ),
					),
					array(
						'id'       => 'home_search_subtitle',
						'type'     => 'text',
						'title'    => esc_html__( 'Home Search Subitle', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input home search subtitle', 'couponxxl' ),
					),
					array(
						'id'       => 'home_search_title',
						'type'     => 'text',
						'title'    => esc_html__( 'Home Search Title', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input home search title', 'couponxxl' ),
					),
					array(
						'id'       => 'home_search_input_placeholder',
						'type'     => 'text',
						'title'    => esc_html__( 'Home Search Input Placeholder', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input home search input placeholder', 'couponxxl' ),
					),
					array(
						'id'       => 'home_search_btn_text',
						'type'     => 'text',
						'title'    => esc_html__( 'Home Search Button Text', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input home search button text', 'couponxxl' ),
					),
				)
			);


			/////////////////////////////////////////////////////////////////////////////////////////// 6. INNER PAGES //
			$this->sections[] = array(
				'title'  => esc_html__( 'Inner Pages', 'couponxxl' ),
				'desc'   => esc_html__( 'Setup basic things for inner pages.', 'couponxxl' ),
				'icon'   => '',
				'fields' => array()
			);

			// Page Title //
			$this->sections[] = array(
				'title'      => esc_html__( 'Page Title', 'couponxxl' ),
				'desc'       => esc_html__( 'Choose basic things for page title on inner pages.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'      => 'page_title_style',
						'type'    => 'select',
						'options' => array(
							'plain' => esc_html__( 'Text Only', 'couponxxl' ),
							'image' => esc_html__( 'With Background Image', 'couponxxl' ),
						),
						'title'   => esc_html__( 'Page Title Style', 'couponxxl' ),
						'desc'    => esc_html__( 'Select style of the page title.', 'couponxxl' ),
						'default' => 'plain'
					),
					array(
						'id'    => 'page_title_bg_image',
						'type'  => 'media',
						'title' => esc_html__( 'Page Title Style', 'couponxxl' ),
						'desc'  => esc_html__( 'Set image for the page title with iamge background.', 'couponxxl' ),
					),
				)
			);


			// Search Page //
			$this->sections[] = array(
				'title'      => esc_html__( 'Search Page', 'couponxxl' ),
				'desc'       => esc_html__( 'Set search page options.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'       => 'search_sidebar_location',
						'type'     => 'select',
						'options'  => array(
							'left'  => esc_html__( 'Left', 'couponxxl' ),
							'right' => esc_html__( 'Right', 'couponxxl' ),
						),
						'title'    => esc_html__( 'Sidebar Position', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Select position of the sidebar on the search page.', 'couponxxl' ),
						'default'  => 'left'
					),
					array(
						'id'       => 'show_deals_map',
						'type'     => 'select',
						'options'  => array(
							'yes' => esc_html__( 'Yes', 'couponxxl' ),
							'no'  => esc_html__( 'No', 'couponxxl' ),
						),
						'title'    => esc_html__( 'Show Deals On Map', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Enable or disable displaying of the deals on the map on search page.', 'couponxxl' ),
						'default'  => 'yes'
					),
					array(
						'id'       => 'marker_icon',
						'type'     => 'media',
						'title'    => esc_html__( 'Marker Icon', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Set marker icon which will be used for the deal markers.', 'couponxxl' ),
					),
					array(
						'id'       => 'map_trigger_img',
						'type'     => 'media',
						'title'    => esc_html__( 'Map Trigger Image', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Set image which will be used for the map trigger on the search page..', 'couponxxl' ),
					),
				)
			);


			// All Categories Page //
			$this->sections[] = array(
				'title'      => esc_html__( 'All Categories Page', 'couponxxl' ),
				'desc'       => esc_html__( 'Set options for the all categories page.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'       => 'all_categories_sortby',
						'type'     => 'select',
						'options'  => array(
							'name'  => esc_html__( 'Name', 'couponxxl' ),
							'slug'  => esc_html__( 'Slug', 'couponxxl' ),
							'count' => esc_html__( 'Offers Count', 'couponxxl' ),
						),
						'title'    => esc_html__( 'Sort Categories By', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Choose by which field to sort listing of all categories', 'couponxxl' ),
						'default'  => 'name'
					),

					array(
						'id'       => 'all_categories_sort',
						'type'     => 'select',
						'options'  => array(
							'asc'  => esc_html__( 'Ascending', 'couponxxl' ),
							'desc' => esc_html__( 'Descending', 'couponxxl' ),
						),
						'title'    => esc_html__( 'Categories Sort Order', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Choose how to sort listing of all categories', 'couponxxl' ),
						'default'  => 'asc'
					),
				)
			);

			// All Locations Page //
			$this->sections[] = array(
				'title'      => esc_html__( 'All Locations Page', 'couponxxl' ),
				'desc'       => esc_html__( 'Set options for the all locations page.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'       => 'all_locations_sortby',
						'type'     => 'select',
						'options'  => array(
							'name'  => esc_html__( 'Name', 'couponxxl' ),
							'slug'  => esc_html__( 'Slug', 'couponxxl' ),
							'count' => esc_html__( 'Offers Count', 'couponxxl' ),
						),
						'title'    => esc_html__( 'Sort Locations By', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Choose by which field to sort listing of all locations', 'couponxxl' ),
						'default'  => 'name'
					),

					array(
						'id'       => 'all_locations_sort',
						'type'     => 'select',
						'options'  => array(
							'asc'  => esc_html__( 'Ascending', 'couponxxl' ),
							'desc' => esc_html__( 'Descending', 'couponxxl' ),
						),
						'title'    => esc_html__( 'Categories Sort Order', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Choose how to sort listing of all locations', 'couponxxl' ),
						'default'  => 'asc'
					),
				)
			);

			/////////////////////////////////////////////////////////////////////////////////////////// 7. CONTACT PAGE //
			$this->sections[] = array(
				'title'  => esc_html__( 'Contact Page', 'couponxxl' ),
				'desc'   => esc_html__( 'Contact page settings.', 'couponxxl' ),
				'icon'   => '',
				'fields' => array(
					array(
						'id'       => 'contact_mail',
						'type'     => 'text',
						'title'    => esc_html__( 'Mail', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input email where sent messages will arrive', 'couponxxl' )
					),
					array(
						'id'       => 'contact_form_subject',
						'type'     => 'text',
						'title'    => esc_html__( 'Mail Subject', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input subject for the message.', 'couponxxl' )
					),

					array(
						'id'       => 'contact_map',
						'type'     => 'multi_text',
						'title'    => esc_html__( 'Google Map Markers', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input longitudes and latitudes separated by comma for example 92.3123,-105.54353 (latitude,longitude). <a href="http://www.latlong.net/" target="_blank">Find Long/Lat</a>', 'couponxxl' )
					),
					array(
						'id'       => 'contact_map_max_zoom',
						'type'     => 'text',
						'title'    => esc_html__( 'Map Zoom', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Set map zoom from 0 to 19 or leave empty to fit marker in bounds.', 'couponxxl' ),
					),
					array(
						'id'       => 'contact_address',
						'type'     => 'text',
						'title'    => esc_html__( 'Your Address', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input your address.', 'couponxxl' ),
					),
					array(
						'id'       => 'contact_phone',
						'type'     => 'text',
						'title'    => esc_html__( 'Your Phone Number', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input your phone number.', 'couponxxl' ),
					),
					array(
						'id'       => 'contact_link',
						'type'     => 'text',
						'title'    => esc_html__( 'Your Link', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input your link.', 'couponxxl' ),
					),
					array(
						'id'       => 'contact_email',
						'type'     => 'text',
						'title'    => esc_html__( 'Your Email', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input your email.', 'couponxxl' ),
					),
					array(
						'id'       => 'contact_facebook',
						'type'     => 'text',
						'title'    => esc_html__( 'Your Facebook Page / Profile', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input link to tyour facebook profile or page.', 'couponxxl' ),
					),
					array(
						'id'       => 'contact_twitter',
						'type'     => 'text',
						'title'    => esc_html__( 'Your Twitter Profile', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input link to your twitter profile.', 'couponxxl' ),
					),
					array(
						'id'       => 'contact_google',
						'type'     => 'text',
						'title'    => esc_html__( 'Your Google Profile', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input link to your google profile.', 'couponxxl' ),
					),
					array(
						'id'       => 'contact_rss',
						'type'     => 'text',
						'title'    => esc_html__( 'Your RSS', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input link to your RSS.', 'couponxxl' ),
					),
				)
			);


			/////////////////////////////////////////////////////////////////////////////////// 8. OFFERS //
			$this->sections[] = array(
				'title'  => esc_html__( 'Offers', 'couponxxl' ),
				'desc'   => esc_html__( 'Offers setup.', 'couponxxl' ),
				'icon'   => '',
				'fields' => array(

					array(
						'id'      => 'stores_per_page',
						'type'    => 'text',
						'title'   => esc_html__( 'Stores Per Page', 'couponxxl' ),
						'desc'    => esc_html__( 'Select how many stores to show per page', 'couponxxl' ),
						'default' => '9'
					),
					array(
						'id'      => 'offers_per_page',
						'type'    => 'text',
						'title'   => esc_html__( 'Offers Per Page', 'couponxxl' ),
						'desc'    => esc_html__( 'Select how many offers to show per page', 'couponxxl' ),
						'default' => '9'
					),
					array(
						'id'      => 'stores_show_expired',
						'type'    => 'select',
						'title'   => esc_html__( 'Show Expired Offers On Store Page', 'couponxxl' ),
						'desc'    => esc_html__( 'Show or hide expired offers on their respective stores pages', 'couponxxl' ),
						'options' => array(
							'yes' => esc_html__( 'Yes', 'couponxxl' ),
							'no'  => esc_html__( 'No', 'couponxxl' )
						),
						'default' => 'yes'
					),
					array(
						'id'      => 'store_offers_order',
						'type'    => 'select',
						'title'   => esc_html__( 'Choose Offers Order On Store Page', 'couponxxl' ),
						'desc'    => esc_html__( 'Choose order of the offers on their store page', 'couponxxl' ),
						'options' => array(
							'ASC' => esc_html__( 'Ascending', 'couponxxl' ),
							'DESC'  => esc_html__( 'Descending', 'couponxxl' )
						),
						'default' => 'DESC'
					),
					array(
						'id'      => 'store_no_offers_message',
						'type'    => 'text',
						'title'   => esc_html__( 'No Offers From Store Message', 'couponxxl' ),
						'desc'    => esc_html__( 'Input notification text which will be disaplyed on the store single page when there is no associated offers.', 'couponxxl' ),
						'default' => esc_html__( 'Currently there is no coupons and deals for this store.', 'couponxxl' )
					),

					array(
						'id'      => 'search_no_offers_message',
						'type'    => 'text',
						'title'   => esc_html__( 'No Search Results Message', 'couponxxl' ),
						'desc'    => esc_html__( 'Input notification text which will be disaplyed on the search page when there is no offers found.', 'couponxxl' ),
						'default' => esc_html__( 'No deals and coupons found.', 'couponxxl' )
					),
					array(
						'id'    => 'listing_map_zoom',
						'type'  => 'text',
						'title' => esc_html__( 'Map Listing Marker Zoom', 'couponxxl' ),
						'desc'  => esc_html__( 'Input value for the max zoom on listing pages and big map on home. Value can be 0 - 19', 'couponxxl' ),
					),
					array(
						'id'    => 'deal_single_zoom',
						'type'  => 'text',
						'title' => esc_html__( 'Deal Single Map Zoom', 'couponxxl' ),
						'desc'  => esc_html__( 'Input value for the max zoom on deal single map or leave empty to fit markers in bounds. Value can be 0 - 19', 'couponxxl' ),
					),
					array(
						'id'      => 'search_order_by',
						'type'    => 'select',
						'options' => array(
							'offer_expire' => esc_html__( 'Offer Expire', 'couponxxl' ),
							'date'         => esc_html__( 'Offer Publish Date', 'couponxxl' ),
							'title'        => esc_html__( 'Offer Title', 'couponxxl' ),
							'rand'         => esc_html__( 'Random', 'couponxxl' )
						),
						'title'   => esc_html__( 'Search Page Order By', 'couponxxl' ),
						'desc'    => esc_html__( 'Select by which field to sort results on the search page', 'couponxxl' ),
						'default' => 'offer_expire'
					),
					array(
						'id'      => 'search_order',
						'type'    => 'select',
						'options' => array(
							'ASC'  => esc_html__( 'Ascending', 'couponxxl' ),
							'DESC' => esc_html__( 'Descending', 'couponxxl' )
						),
						'title'   => esc_html__( 'Search Page Order', 'couponxxl' ),
						'desc'    => esc_html__( 'Select how to sort results on the search page', 'couponxxl' ),
						'default' => 'ASC'
					),
				)
			);

			// Terms //
			$this->sections[] = array(
				'title'      => esc_html__( 'Terms', 'couponxxl' ),
				'desc'       => esc_html__( 'Show terms and conditions on submit page.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'      => 'terms',
						'type'    => 'editor',
						'title'   => esc_html__( 'Terms & Conditions', 'couponxxl' ),
						'desc'    => esc_html__( 'Input terms and conditions which users must accept in order to submit offer or leave empty to disable.', 'couponxxl' ),
						'default' => ''
					)

				)
			);

			// Slider //
			$this->sections[] = array(
				'title'      => esc_html__( 'Slider', 'couponxxl' ),
				'desc'       => esc_html__( 'Show slider in listing/main search page.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'      => 'show_search_slider',
						'type'    => 'select',
						'title'   => esc_html__( 'Slider On Search Page', 'couponxxl' ),
						'desc'    => esc_html__( 'Show or hide slider on the search page', 'couponxxl' ),
						'options' => array(
							'yes' => esc_html__( 'Yes', 'couponxxl' ),
							'no'  => esc_html__( 'No', 'couponxxl' )
						),
						'default' => 'yes'
					),
					array(
						'id'      => 'slider_auto_rotate',
						'type'    => 'select',
						'title'   => esc_html__( 'Autoplay', 'couponxxl' ),
						'desc'    => esc_html__( 'Enable autoplay of the slider', 'couponxxl' ),
						'options' => array(
							'yes' => esc_html__( 'Yes', 'couponxxl' ),
							'no'  => esc_html__( 'No', 'couponxxl' )
						),
						'default' => 'yes'
					),
					array(
						'id'      => 'slider_speed',
						'type'    => 'text',
						'title'   => esc_html__( 'Autoplay Timeout', 'couponxxl' ),
						'desc'    => esc_html__( 'Input value in miliseconds for autoplay timeout', 'couponxxl' ),
						'default' => '4000'
					)

				)
			);


			// Filter By //
			$this->sections[] = array(
				'title'      => esc_html__( 'Filter By', 'couponxxl' ),
				'desc'       => esc_html__( 'Sidebar filter basic options.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'      => 'search_page_offer_type_filter_title',
						'type'    => 'text',
						'title'   => esc_html__( 'Offer Type Filter Title', 'couponxxl' ),
						'desc'    => esc_html__( 'Input title for the filter by offer type on search page', 'couponxxl' ),
						'default' => esc_html__( 'I\'m looking for', 'couponxxl' )
					),
					array(
						'id'      => 'search_page_category_filter_title',
						'type'    => 'text',
						'title'   => esc_html__( 'Category Filter Title', 'couponxxl' ),
						'desc'    => esc_html__( 'Input title for the filter by category on search page', 'couponxxl' ),
						'default' => esc_html__( 'Category', 'couponxxl' )
					),
					array(
						'id'      => 'search_page_location_filter_title',
						'type'    => 'text',
						'title'   => esc_html__( 'Location Filter Title', 'couponxxl' ),
						'desc'    => esc_html__( 'Input title for the filter by offer type on search page', 'couponxxl' ),
						'default' => esc_html__( 'Location', 'couponxxl' )
					),
					array(
						'id'      => 'search_page_store_filter_title',
						'type'    => 'text',
						'title'   => esc_html__( 'Visible Stores', 'couponxxl' ),
						'desc'    => esc_html__( 'Input title for the filter by store on search page', 'couponxxl' ),
						'default' => esc_html__( 'Stores', 'couponxxl' )
					),
					array(
						'id'      => 'search_show_count',
						'type'    => 'select',
						'title'   => esc_html__( 'Search Filter Number Of Offers', 'couponxxl' ),
						'desc'    => esc_html__( 'Show or hide number of offers for a certain location / category on search page.', 'couponxxl' ),
						'options' => array(
							'yes' => esc_html__( 'Yes', 'couponxxl' ),
							'no'  => esc_html__( 'No', 'couponxxl' )
						),
						'default' => 'yes'
					),
					array(
						'id'      => 'search_visible_locations_count',
						'type'    => 'text',
						'title'   => esc_html__( 'Visible Search Locations', 'couponxxl' ),
						'desc'    => esc_html__( 'Number of visible location to show on the search page before See More button', 'couponxxl' ),
						'default' => '8'
					),
					array(
						'id'      => 'search_visible_stores_count',
						'type'    => 'text',
						'title'   => esc_html__( 'Visible Stores', 'couponxxl' ),
						'desc'    => esc_html__( 'Number of visible stores to show on the search page before See More button', 'couponxxl' ),
						'default' => '6'
					)
				)
			);


			// Deal Single //
			$this->sections[] = array(
				'title'      => esc_html__( 'Deal Page', 'couponxxl' ),
				'desc'       => esc_html__( 'Settings for deal single page.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'deal_show_bought',
						'type'     => 'select',
						'options'  => array(
							'yes' => esc_html__( 'Yes', 'couponxxl' ),
							'no'  => esc_html__( 'No', 'couponxxl' )
						),
						'title'    => esc_html__( 'Show Bought Information', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Show information on how many people bought deal', 'couponxxl' ),
						'default'  => 'yes'
					),
					array(
						'id'       => 'deal_show_similar',
						'type'     => 'select',
						'options'  => array(
							'yes' => esc_html__( 'Yes', 'couponxxl' ),
							'no'  => esc_html__( 'No', 'couponxxl' )
						),
						'title'    => esc_html__( 'Show Similar Deals', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Show similar deals on deals single page', 'couponxxl' ),
						'default'  => 'yes'
					),
					array(
						'id'       => 'similar_offers',
						'type'     => 'text',
						'title'    => esc_html__( 'Number Of Similar Deals', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input number of similar deals to show on deal single page', 'couponxxl' ),
						'default'  => '2'
					),
				)
			);

			// Coupon Single //
			$this->sections[] = array(
				'title'      => esc_html__( 'Coupon Page', 'couponxxl' ),
				'desc'       => esc_html__( 'Settings for coupon single page.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'coupon_modal_content',
						'type'     => 'select',
						'options'  => array(
							'content' => esc_html__( 'Whole Content', 'couponxxl' ),
							'excerpt' => esc_html__( 'Excerpt Only', 'couponxxl' )
						),
						'title'    => esc_html__( 'Modal Content', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Select what to show in modal', 'couponxxl' ),
						'default'  => 'excerpt'
					),
					array(
						'id'       => 'coupon_show_similar',
						'type'     => 'select',
						'options'  => array(
							'yes' => esc_html__( 'Yes', 'couponxxl' ),
							'no'  => esc_html__( 'No', 'couponxxl' )
						),
						'title'    => esc_html__( 'Show Similar Coupons', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Show similar coupon on deals single page', 'couponxxl' ),
						'default'  => 'yes'
					),
					array(
						'id'       => 'coupon_similar_offers',
						'type'     => 'text',
						'title'    => esc_html__( 'Number Of Similar Coupons', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input number of similar coupon to show on deal single page', 'couponxxl' ),
						'default'  => '2'
					),
				)
			);

			// Payments //
			$this->sections[] = array(
				'title'      => esc_html__( 'Payments', 'couponxxl' ),
				'desc'       => esc_html__( 'Payments sharing setup.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'    => 'deal_owner_price_shared',
						'type'  => 'text',
						'title' => esc_html__( 'Website Offer Fee', 'couponxxl' ),
						'desc'  => esc_html__( 'Input number without currency symbol for fixed fee. Input percentage with percentage symbol for percentage based fee.', 'couponxxl' )
					),
					array(
						'id'    => 'deal_owner_price_not_shared',
						'type'  => 'text',
						'title' => esc_html__( 'Store Offer Fee', 'couponxxl' ),
						'desc'  => esc_html__( 'Input number without currency symbol for fixed fee. Input percentage with percentage symbol for percentage based fee.', 'couponxxl' )
					),
					array(
						'id'    => 'deal_submit_price',
						'type'  => 'text',
						'title' => esc_html__( 'Submit Deal Credits', 'couponxxl' ),
						'desc'  => esc_html__( 'Input how many credits for the deal submission.', 'couponxxl' )
					),
					array(
						'id'    => 'coupon_submit_price',
						'type'  => 'text',
						'title' => esc_html__( 'Submit Coupon Credits', 'couponxxl' ),
						'desc'  => esc_html__( 'Input how many credits for the coupon submission.', 'couponxxl' )
					),
					array(
						'id'    => 'credit_packages',
						'type'  => 'textarea',
						'title' => esc_html__( 'Credit Packages', 'couponxxl' ),
						'desc'  => esc_html__( 'Input credit packages in form CREDITS|PRICE for example 10|5 and put each package in new line.', 'couponxxl' )
					)

				)
			);

			// Submitting //
			$this->sections[] = array(
				'title'      => esc_html__( 'Submitting', 'couponxxl' ),
				'desc'       => esc_html__( 'Submitting deals & coupons settings.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'       => 'unlimited_expire',
						'type'     => 'select',
						'title'    => esc_html__( 'Allow Unlimited Expire Date', 'couponxxl' ),
						'compiler' => 'true',
						'options'  => array(
							'no'  => esc_html__( 'No', 'couponxxl' ),
							'yes' => esc_html__( 'Yes', 'couponxxl' )
						),
						'desc'     => esc_html__( 'Allow or disallow unlimited expire date for deals and coupons.', 'couponxxl' ),
						'default'  => 'no'
					),
					array(
						'id'       => 'date_ranges',
						'type'     => 'text',
						'title'    => esc_html__( 'Maximum Date Ranges', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input maximum number of days between start and expire date.', 'couponxxl' )
					)

				)
			);

			///////////////////////////////////////////////////////////////////////////////////////// 9. MESSAGING //


			$this->sections[] = array(
				'title'  => esc_html__( 'Messaging', 'couponxxl' ),
				'desc'   => esc_html__( 'Interaction trough emails settings.', 'couponxxl' ),
				'icon'   => '',
				'fields' => array(
					array(
						'id'       => 'email_sender',
						'type'     => 'text',
						'title'    => esc_html__( 'Email Of Sender', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input email address you wish to show on the email messages.', 'couponxxl' )
					),
					array(
						'id'       => 'name_sender',
						'type'     => 'text',
						'title'    => esc_html__( 'Name Of Sender', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input name you wish to show on the email messages.', 'couponxxl' )
					)
				)
			);

			// Registration //
			$this->sections[] = array(
				'title'      => esc_html__( 'Registration', 'couponxxl' ),
				'desc'       => esc_html__( 'Registration basic settings setup.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'       => 'registration_message',
						'type'     => 'textarea',
						'title'    => esc_html__( 'Registration Message', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input registration message which will be sent to the users to verify their email address. Put %LINK% in the place you want to show confirmation link.', 'couponxxl' )
					),
					array(
						'id'       => 'registration_subject',
						'type'     => 'text',
						'title'    => esc_html__( 'Registration Message Subject', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input registration message subject.', 'couponxxl' )
					),
					array(
						'id'      => 'register_terms',
						'type'    => 'text',
						'title'   => esc_html__( 'Terms & Conditions', 'couponxxl' ),
						'desc'    => esc_html__( 'Input URL to the page with terms and condition.', 'couponxxl' ),
						'default' => ''
					)

				)
			);


			// Registration //
			$this->sections[] = array(
				'title'      => esc_html__( 'Agents', 'couponxxl' ),
				'desc'       => esc_html__( 'Agents basic settings setup.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'       => 'new_agent_message',
						'type'     => 'textarea',
						'title'    => esc_html__( 'New Agent Message', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input new agent message which will be sent to the agents. Put %USERNAME% and %PASSWORD% in the places you want to show data.', 'couponxxl' )
					),
					array(
						'id'       => 'new_agent_subject',
						'type'     => 'text',
						'title'    => esc_html__( 'New Agent Message Subject', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input new agent message subject.', 'couponxxl' )
					)

				)
			);


			// Registration //
			$this->sections[] = array(
				'title'      => esc_html__( 'New Offers', 'couponxxl' ),
				'desc'       => esc_html__( 'New offers basic settings setup.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'       => 'new_offer_email',
						'type'     => 'text',
						'title'    => esc_html__( 'New Offer / Order Email', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input email address on which information about new submission and new orders will arrive.', 'couponxxl' )
					),
				)
			);


			// Lost Password //
			$this->sections[] = array(
				'title'      => esc_html__( 'Lost password', 'couponxxl' ),
				'desc'       => esc_html__( 'Lost password basic settings setup.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'       => 'lost_password_message',
						'type'     => 'textarea',
						'title'    => esc_html__( 'Lost Password Message', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input lost password message which will be sent to the users to verify their email address. Put %PASSWORD% in the place you want to show new password and put %USERNAME% where to place username.', 'couponxxl' )
					),
					array(
						'id'       => 'lost_password_subject',
						'type'     => 'text',
						'title'    => esc_html__( 'Lost Password Message Subject', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input lost password message subject.', 'couponxxl' )
					),


				)
			);

			///////////////////////////////////////////////////////////////////////////////////////// 10. API //


			$this->sections[] = array(
				'title'  => esc_html__( 'API Setup', 'couponxxl' ),
				'desc'   => esc_html__( 'Setup external API needed for different website services.', 'couponxxl' ),
				'icon'   => '',
				'fields' => array(
					array(
						'id'    => 'unit',
						'type'  => 'text',
						'title' => esc_html__( 'Main Currency Unit', 'couponxxl' ),
						'desc'  => esc_html__( 'Input main currency unit. ($, £, €, руб).', 'couponxxl' )
					),
					array(
						'id'    => 'main_unit_abbr',
						'type'  => 'text',
						'title' => esc_html__( 'Main Currency Unit Abbreviation', 'couponxxl' ),
						'desc'  => esc_html__( 'Input main currency unit abbreviation.  (USD, EUR, RUB, AUD, GBP...)', 'couponxxl' )
					),
					array(
						'id'      => 'unit_position',
						'title'   => esc_html__( 'Unit Position', 'couponxxl' ),
						'desc'    => esc_html__( 'Select position of the unit.', 'couponxxl' ),
						'type'    => 'select',
						'options' => array(
							'front' => esc_html__( 'Front', 'couponxxl' ),
							'back'  => esc_html__( 'Back', 'couponxxl' )
						)
					),
				)
			);

			$gateway_apis = array();
			do_action_ref_array( 'couponxxl_add_options', array( &$gateway_apis ) );
			if ( ! empty( $gateway_apis ) ) {
				foreach ( $gateway_apis as $gateway_api ) {
					$this->sections[] = $gateway_api;
				}
			}


			// Mailchimp API //
			$this->sections[] = array(
				'title'      => esc_html__( 'Mail chimp API', 'couponxxl' ),
				'desc'       => esc_html__( 'Important PayPal Settings.', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'       => 'mail_chimp_api',
						'type'     => 'text',
						'title'    => esc_html__( 'Mail Chimp API', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input API key of your MailChimp. More <a href="http://kb.mailchimp.com/accounts/management/about-api-keys" target="_blank">here</a>', 'couponxxl' )
					),
					array(
						'id'       => 'mail_chimp_list_id',
						'type'     => 'text',
						'title'    => esc_html__( 'Mail Chimp List ID', 'couponxxl' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input ID of the ailchimp list on which the users will subscribe. More <a href="http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id" target="_blank">here</a>', 'couponxxl' )
					)

				)
			);

			// Google Map API //
			$this->sections[] = array(
				'title'      => esc_html__( 'Google Map Api', 'couponxxl' ),
				'desc'       => esc_html__( 'Google Map Api Settings', 'couponxxl' ),
				'icon'       => '',
				'subsection' => true,
				'fields'     => array(

					array(
						'id'       => 'map_api',
						'type'     => 'text',
						'title'    => esc_html__( 'Google Map Api Key', 'classifieds' ),
						'compiler' => 'true',
						'desc'     => esc_html__( 'Input your google map api key that can be obtained by going on this link: https://developers.google.com/maps/documentation/javascript/get-api-key. It might take up to 10 minutes to apply. This is necessary to enable autocomplete!', 'classifieds' ),
						'default'  => ''
					),
				)
			);


			$this->sections[] = array(
				'title'  => esc_html__( 'Appearance', 'couponxxl' ),
				'desc'   => esc_html__( 'Setup the look and feel of the site.', 'couponxxl' ),
				'icon'   => '',
				'indent' => true,
				'fields' => array(
					array(
						'id'       => 'theme_font',
						'type'     => 'select',
						'title'    => esc_html__( 'Theme Font', 'couponxxl' ),
						'compiler' => 'true',
						'options'  => couponxxl_google_fonts(),
						'desc'     => esc_html__( 'Select font for the theme.', 'couponxxl' ),
						'default'  => 'Open Sans'
					),
					array(
						'id'          => 'body_background',
						'type'        => 'color',
						'title'       => esc_html__( 'Body Background Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select color for the body background.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#f3f3f3'
					),
					array(
						'id'          => 'footer_background',
						'type'        => 'color',
						'title'       => esc_html__( 'Footer Background Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select background color for the footer.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#262626'
					),
					array(
						'id'          => 'footer_widget_background',
						'type'        => 'color',
						'title'       => esc_html__( 'Footer Widget Background Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select background color for the footer widget.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#ececec'
					),
					array(
						'id'          => 'border_color',
						'type'        => 'color',
						'title'       => esc_html__( 'Borders Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select color of the borders accross the site.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#f1f1f1'
					),
					array(
						'id'          => 'font_color',
						'type'        => 'color',
						'title'       => esc_html__( 'Font Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select font color accross the site.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#4a4a4a'
					),
					array(
						'id'          => 'font_lighten',
						'type'        => 'color',
						'title'       => esc_html__( 'Font Lighten Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select font color for the title shortcode.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#4a4a4a'
					),
					array(
						'id'          => 'font_light',
						'type'        => 'color',
						'title'       => esc_html__( 'Footer Font Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select font color for the footer.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#777'
					),
					array(
						'id'          => 'footer_widget_font',
						'type'        => 'color',
						'title'       => esc_html__( 'Footer Widget Font Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select font color for the footer.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#4a4a4a'
					),
					array(
						'id'          => 'main_green_color',
						'type'        => 'color',
						'title'       => esc_html__( 'Primary Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select color as primary instead of default green one.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#4caf50'
					),
					array(
						'id'          => 'sec_red_color',
						'type'        => 'color',
						'title'       => esc_html__( 'Secondary Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select color as secondary instead of default red one.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#ff5722'
					),
					array(
						'id'          => 'thr_blue_color',
						'type'        => 'color',
						'title'       => esc_html__( 'Tertiary Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select color as tertiary instead of default blue one.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#286eb5'
					),
					array(
						'id'          => 'box_bg_color',
						'type'        => 'color',
						'title'       => esc_html__( 'Box Background Color', 'classifieds' ),
						'compiler'    => 'true',
						'desc'        => esc_html__( 'Select background color of the boxes.', 'classifieds' ),
						'transparent' => false,
						'default'     => '#ffffff'
					),
				)
			);
		}

		/**
		 * All the possible arguments for Redux.
		 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
		 * */
		public function setArguments() {

			$theme = wp_get_theme(); // For use with some settings. Not necessary.

			$this->args = array(
				// TYPICAL -> Change these values as you need/desire
				'opt_name'             => 'couponxxl_options',
				// This is where your data is stored in the database and also becomes your global variable name.
				'display_name'         => $theme->get( 'Name' ),
				// Name that appears at the top of your panel
				'display_version'      => $theme->get( 'Version' ),
				// Version that appears at the top of your panel
				'menu_type'            => 'menu',
				//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
				'allow_sub_menu'       => true,
				// Show the sections below the admin menu item or not
				'menu_title'           => esc_html__( 'CouponXXL', 'redux-framework-demo' ),
				'page_title'           => esc_html__( 'CouponXXL', 'redux-framework-demo' ),
				// You will need to generate a Google API key to use this feature.
				// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
				'google_api_key'       => '',
				// Set it you want google fonts to update weekly. A google_api_key value is required.
				'google_update_weekly' => false,
				// Must be defined to add google fonts to the typography module
				'async_typography'     => true,
				// Use a asynchronous font on the front end or font string
				//'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
				'admin_bar'            => true,
				// Show the panel pages on the admin bar
				'admin_bar_icon'       => 'dashicons-portfolio',
				// Choose an icon for the admin bar menu
				'admin_bar_priority'   => 50,
				// Choose an priority for the admin bar menu
				'global_variable'      => '',
				// Set a different name for your global variable other than the opt_name
				'dev_mode'             => false,
				// Show the time the page took to load, etc
				'update_notice'        => true,
				// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
				'customizer'           => true,
				// Enable basic customizer support
				//'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
				//'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

				// OPTIONAL -> Give you extra features
				'page_priority'        => null,
				// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
				'page_parent'          => 'themes.php',
				// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
				'page_permissions'     => 'manage_options',
				// Permissions needed to access the options panel.
				//'menu_icon'            => get_template_directory_uri().'/images/icon.png',
				// Specify a custom URL to an icon
				'last_tab'             => '',
				// Force your panel to always open to a specific tab (by id)
				'page_icon'            => 'icon-themes',
				// Icon displayed in the admin panel next to your menu_title
				'page_slug'            => '_options',
				// Page slug used to denote the panel
				'save_defaults'        => true,
				// On load save the defaults to DB before user clicks save or not
				'default_show'         => false,
				// If true, shows the default value next to each field that is not the default value.
				'default_mark'         => '',
				// What to print by the field's title if the value shown is default. Suggested: *
				'show_import_export'   => true,
				// Shows the Import/Export panel when not used as a field.

				// CAREFUL -> These options are for advanced use only
				'transient_time'       => 60 * MINUTE_IN_SECONDS,
				'output'               => true,
				// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
				'output_tag'           => true,
				// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
				// 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

				// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
				'database'             => '',
				// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
				'system_info'          => false,
				// REMOVE

				// HINTS
				'hints'                => array(
					'icon'          => 'icon-question-sign',
					'icon_position' => 'right',
					'icon_color'    => 'lightgray',
					'icon_size'     => 'normal',
					'tip_style'     => array(
						'color'   => 'light',
						'shadow'  => true,
						'rounded' => false,
						'style'   => ''
					),
					'tip_position'  => array(
						'my' => 'top left',
						'at' => 'bottom right'
					),
					'tip_effect'    => array(
						'show' => array(
							'effect'   => 'slide',
							'duration' => '500',
							'event'    => 'mouseover'
						),
						'hide' => array(
							'effect'   => 'slide',
							'duration' => '500',
							'event'    => 'click mouseleave'
						)
					)
				)
			);


		}

	}

	global $couponxxl_opts;
	$couponxxl_opts = new CouponXXL_Options();
} else {
	echo "The class named CouponXXL_Options has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
}