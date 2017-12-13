<?php
include( couponxxl_load_path( 'includes/title/before-page-title.php' ) );
	echo '<h1>';
	if ( is_category() ){
		echo single_cat_title();
	}
	else if( is_404() ){
		esc_html_e( '404 Page Doesn\'t exists', 'couponxxl' );
	}
	else if( is_tag() ){
		echo esc_html__('Search results for: ', 'couponxxl'). get_query_var('tag'); 
	}
	else if( is_author() ){
		esc_html_e('Posts by', 'couponxxl'); 
	}
	else if( is_tax('offer_cat') ){
		$term = get_term_by( 'slug', get_query_var('term'), get_query_var('taxonomy') );
		echo esc_html__('Category: ', 'couponxxl'). $term->name; 
	}
	else if( is_tax('location') ){
		$term = get_term_by( 'slug', get_query_var('term'), get_query_var('taxonomy') );
		echo esc_html__('Location: ', 'couponxxl'). $term->name; 
	}
	else if( is_tax('offer_tag') ){
		$term = get_term_by( 'slug', get_query_var('term'), get_query_var('taxonomy') );
		echo esc_html__('Tag: ', 'couponxxl'). $term->name; 
	}
	else if( is_archive() ){
		echo esc_html__('Archive for:', 'couponxxl'). single_month_title( ' ', false ); 
	}
	else if( is_search() ){ 
		echo esc_html__('Search results for: ', 'couponxxl').' '. get_search_query();
	}
	else if( is_front_page() || is_home() ){
		if( !class_exists('ReduxFramework') ){
			bloginfo( 'name' );
		}
		else{
			$blog_id = get_option('page_for_posts' );
			echo get_the_title( $blog_id );
		}
	}
	else if( is_singular('post') ){
		esc_html_e( 'News', 'couponxxl' );
	}
	else{
		$page_template = get_page_template_slug();
		include( couponxxl_load_path('includes/search-args.php') );
		if( $page_template == 'page-tpl_search.php' ){
			global $couponxxl_slugs;
			if( !empty( $offer_cat ) ){
				$offer_cat_title = get_term_by( 'slug', $offer_cat, 'offer_cat' );
				if( !empty( $offer_cat_title ) ){
					echo  $offer_cat_title->name;
				}
			}
			else if( !empty( $location ) ){
				$loc_titles = array();
				foreach( $location as $loc_slug ){
					$loc_term = get_term_by( 'slug', $loc_slug, 'location' );
					$loc_titles[] = $loc_term->name;
				}
				
				echo esc_html__( 'Results for location ', 'couponxxl' )."'".join( ' ', $loc_titles )."'";
			}
			else if( !empty( $keyword ) ){
				echo esc_html__( 'Results for keyword ', 'couponxxl' )."'".$keyword."'";
			}
			else{
				the_title();
			}
		}
		else if( $page_template == 'page-tpl_register.php' ){
			if( !empty( $_GET['account'] ) ){
				echo esc_html__( 'Register as ', 'couponxxl' ).( $_GET['account'] == 'vendor' ? esc_html__( 'Vendor', 'couponxxl' ) : esc_html__( 'Buyer', 'couponxxl' ) );
			}
			else{
				the_title();
			}
		}
		else{
			the_title();
		}
	}
	echo '</h1>';
include( couponxxl_load_path( 'includes/title/after-page-title.php' ) );
?>