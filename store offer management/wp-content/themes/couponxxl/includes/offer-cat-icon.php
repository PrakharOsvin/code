<?php

/* Custom Meta For Taxonomies */


/* Adding New */
/* icon meta */
function couponxxl_category_icon_add() {
	echo '
	<div class="form-field">
		<label for="term_meta[promo_text]">'.esc_html__( 'Promo Text:', 'pippin' ).'</label>
		<input type="text" name="term_meta[promo_text]" id="term_meta[category_icon]" value=""> 
		<p class="description">'.esc_html__( 'Input some promo text you wish to use for this category','couponxxl' ).'</p>
	</div>
	<div class="form-field">
		<label for="term_meta[category_image]">'.esc_html__( 'Image:', 'couponxxl' ).'</label>
		<input type="hidden" name="term_meta[category_image]" value="">
		<div class="image-holder">
		</div>
		<a href="javascript:;" class="add_cat_image button">'.esc_html__( 'Select Image', 'couponxxl' ).'</a>
		<p class="description">'.esc_html__( 'Select image for the category','couponxxl' ).'</p>
	</div>';
}
add_action( 'offer_cat_add_form_fields', 'couponxxl_category_icon_add', 10, 2 );

/* Editing */
function couponxxl_category_icon_edit( $term ) {
	$t_id = $term->term_id;
	$term_meta = get_option( "taxonomy_$t_id" );
	
	$promo_text = !empty( $term_meta['promo_text'] ) ? $term_meta['promo_text'] : '';
	$category_image = !empty( $term_meta['category_image'] ) ? $term_meta['category_image'] : '';
	?>
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row"><label for="term_meta[category_image]"><?php esc_html_e( 'Promo Text', 'couponxxl' ); ?></label></th>
				<td>
				<input type="text" name="term_meta[promo_text]" id="term_meta[category_icon]" value="<?php echo esc_attr( $promo_text ) ?>"> 
				<p class="description"><?php esc_html_e( 'Input some promo text you wish to use for this category', 'couponxxl' ); ?></p></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="term_meta[category_image]"><?php esc_html_e( 'Image', 'couponxxl' ); ?></label></th>
				<td>
					<input type="hidden" name="term_meta[category_image]" value="<?php echo esc_attr( $category_image ) ?>">
					<div class="image-holder">
						<?php
						if( !empty( $category_image ) ){
							echo wp_get_attachment_image( $category_image, 'thumbnail' );
						}
						?>
						<a href="javascript:;" class="remove_cat_image">X</a>
					</div>
					<a href="javascript:;" class="add_cat_image button"><?php esc_html_e( 'Select Image', 'couponxxl' ); ?></a>
				<p class="description"><?php esc_html_e( 'Select image for the category', 'couponxxl' ); ?></p></td>
			</tr>
		</tbody>
	</table>
	<?php
}
add_action( 'offer_cat_edit_form_fields', 'couponxxl_category_icon_edit', 10, 2 );

/* Save It */
function couponxxl_category_icon_save( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_offer_cat', 'couponxxl_category_icon_save', 10, 2 );  
add_action( 'create_offer_cat', 'couponxxl_category_icon_save', 10, 2 );

/* Delete meta */
function couponxxl_category_icon_delete( $term_id ) {
	delete_option( "taxonomy_$term_id" );
}  
add_action( 'delete_offer_cat', 'couponxxl_category_icon_delete', 10, 2 );

/* Add icon column */
function couponxxl_category_column( $columns ) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => esc_html__('Name', 'couponxxl'),
		'description' => esc_html__('Description', 'couponxxl'),
        'slug' => esc_html__( 'Slug', 'couponxxl' ),
        'posts' => esc_html__( 'Codes', 'couponxxl' ),
		'promo_text' => esc_html__( 'Promo Text', 'couponxxl' ),
		'category_image' => esc_html__( 'Image', 'couponxxl' )
        );
    return $new_columns;
}
add_filter("manage_edit-offer_cat_columns", 'couponxxl_category_column'); 

function couponxxl_populate_category_column( $out, $column_name, $label_id ){
    switch ( $column_name ) {
 		case 'promo_text':
			$term_meta = get_option( "taxonomy_$label_id" );
			$promo_text = !empty( $term_meta['promo_text'] ) ? $term_meta['promo_text'] : '';

            $out .= $promo_text;
            break;    	
 		case 'category_image':
			$term_meta = get_option( "taxonomy_$label_id" );
			$category_image = !empty( $term_meta['category_image'] ) ? $term_meta['category_image'] : '';

            $out .= '<div style="width: 50px; height: 50px;">'.wp_get_attachment_image( $category_image, 'thumbnail' ).'</div>';
            break;
 
        default:
            break;
    }
    return $out; 
}

add_filter("manage_offer_cat_custom_column", 'couponxxl_populate_category_column', 10, 3);
?>