<?php

function couponxxl_gmap_location() {
	add_meta_box( 'store_gmap', esc_html__( 'Store Locations', 'couponxxl' ), 'couponxxl_gmap_location_callback', 'store' );
}

add_action( 'add_meta_boxes', 'couponxxl_gmap_location' );

function couponxxl_gmap_location_markers( $marker_id = '', $term_slug = 'X_ID_X', $latitude = '', $longitude = '', $name = '', $counter = 0 ) {
	?>
	<div class="store-marker">
		<input type="text" class="form-control store-gmap-location" value="<?php echo esc_attr( $name ) ?>"
		       name="store_location[<?php echo esc_attr( $term_slug ) ?>][<?php echo esc_attr( $counter ) ?>][name]">
		<input type="hidden" value="<?php echo esc_attr( $latitude ) ?>"
		       name="store_location[<?php echo esc_attr( $term_slug ) ?>][<?php echo esc_attr( $counter ) ?>][latitude]"
		       class="latitude">
		<input type="hidden" value="<?php echo esc_attr( $longitude ) ?>"
		       name="store_location[<?php echo esc_attr( $term_slug ) ?>][<?php echo esc_attr( $counter ) ?>][longitude]"
		       class="longitude">
		<input type="hidden" value="<?php echo esc_attr( $marker_id ) ?>"
		       name="store_location[<?php echo esc_attr( $term_slug ) ?>][<?php echo esc_attr( $counter ) ?>][marker_id]"
		       class="marker_id">
		<a href="javascript:;" class="btn remove-store-marker">X</a>
	</div>
	<?php
}

function couponxxl_gmap_location_pattern( $term_slug = '', $location_group = array(), $counter = 0 ) {
	?>
	<div class="store-location-wrap row" data-count="<?php echo esc_attr( $counter ) ?>">
		<div class="col-sm-4">
			<label for="<?php echo esc_attr( $term_slug ) ?>"><?php esc_html_e( 'Location', 'couponxxl' ) ?></label>
			<select id="<?php echo esc_attr( $term_slug ) ?>" class="form-control">
				<?php
				$taxonomy = get_terms( 'location', array( 'hide_empty' => false ) );
				if ( ! empty( $taxonomy ) ) {
					foreach ( $taxonomy as $taxonomy_item ) {
						echo '<option value="' . esc_attr( $taxonomy_item->slug ) . '" ' . ( $taxonomy_item->slug == $term_slug ? 'selected="selected"' : '' ) . '>' . $taxonomy_item->name . '</option>';
					}
				}
				?>
			</select>

			<div class="store-markers">

				<label><?php esc_html_e( 'Precise Locations', 'couponxxl' ) ?></label>

				<?php
				if ( ! empty( $location_group ) ) {
					$input_counter = 0;
					foreach ( $location_group as $saved_location ) {
						couponxxl_gmap_location_markers( $saved_location->marker_id, $saved_location->term_slug, $saved_location->latitude, $saved_location->longitude, $saved_location->name, $input_counter );
						$input_counter ++;
					}
				}
				?>
				<div class="store-marker-pattern hidden">
					<?php couponxxl_gmap_location_markers() ?>
				</div>

				<a href="javascript:;"
				   class="button btn add-new-store-marker"><?php esc_html_e( 'Add New Marker', 'couponxxl' ) ?></a>

			</div>

		</div>
		<div class="col-sm-8">
			<div class="store-gmap"></div>
			<a href="javascript:;" class="btn remove-store-location">X</a>
		</div>
	</div>
	<?php
}

function couponxxl_gmap_location_organize( $saved_locations ) {
	$organized_locations = array();
	if ( ! empty( $saved_locations ) ) {
		foreach ( $saved_locations as $saved_location ) {
			if ( empty( $organized_locations[ $saved_location->term_slug ] ) ) {
				$organized_locations[ $saved_location->term_slug ] = array();
			}
			$organized_locations[ $saved_location->term_slug ][] = $saved_location;
		}
	}

	return $organized_locations;
}

function couponxxl_gmap_location_callback( $post ) {
	global $wpdb;

	$saved_locations     = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}store_markers WHERE post_id = %d", $post->ID ) );
	$organized_locations = couponxxl_gmap_location_organize( $saved_locations );

	if ( ! empty( $organized_locations ) ) {
		$counter = 0;
		foreach ( $organized_locations as $term_slug => $location_group ) {
			couponxxl_gmap_location_pattern( $term_slug, $location_group, $counter );
			$counter ++;
		}
	}
	?>
	<div class="store-location-pattern hidden">
		<?php couponxxl_gmap_location_pattern() ?>
	</div>
	<a href="javascript:;"
	   class="button btn add-new-store-location"><?php esc_html_e( 'Add New Location', 'couponxxl' ) ?></a>
	<?php
}

function couponxxl_gmap_location_save( $post_id ) {
	global $wpdb;

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'store' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	$maker_id_list = array();
	if ( ! empty( $_POST['store_location'] ) ) {
		foreach ( $_POST['store_location'] as $term_slug => $markers ) {
			if ( $term_slug !== 'X_ID_X' ) {

				$all_old_markers = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}store_markers WHERE post_id = %d", $post_id ) );
				if ( ! empty( $all_old_markers ) ) {
					foreach ( $all_old_markers as $old_marker ) {
						$old_markers_list[] = $old_marker->marker_id;
					}
				}

				if ( ! empty( $markers ) ) {
					foreach ( $markers as $marker ) {
						if ( ! empty( $marker['longitude'] ) && ! empty( $marker['latitude'] ) ) {
							$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS count FROM {$wpdb->prefix}store_markers WHERE post_id = %d AND marker_id = %d", $post_id, $marker['marker_id'] ) );
							if ( $count > 0 ) {
								$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}store_markers SET name = %s, longitude = %f, latitude = %f, term_slug = %s WHERE post_id = %d AND marker_id = %d", $marker['name'], $marker['longitude'], $marker['latitude'], $term_slug, $post_id, $marker['marker_id'] ) );
								$maker_id_list[] = $marker['marker_id'];
							} else {
								$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}store_markers VALUES ('', %d, %s, %f, %f, %s )", $post_id, $term_slug, $marker['longitude'], $marker['latitude'], $marker['name'] ) );
								$maker_id_list[] = $wpdb->insert_id;
							}
						}
					}
				}
			}
		}
	}

	if ( ! empty( $maker_id_list ) ) {
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}store_markers WHERE marker_id NOT IN (" . esc_sql( join( ',', $maker_id_list ) ) . ") AND post_id = %d", $post_id ) );
	}

}

add_action( 'save_post', 'couponxxl_gmap_location_save' );

?>