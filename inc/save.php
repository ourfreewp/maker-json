<?php
/**
 * Save functionality for Maker.json.
 *
 * @package Maker_Json_Manager
 */

namespace MakerJson;

/**
 * Process and save the maker.json data.
 *
 * Handles both AJAX and POST saves via `admin-ajax.php` and `admin-post.php` respectively.
 * AJAX calls output JSON; POST calls redirect back to the Maker.json edit screen.
 *
 * @return void
 */
function save() {
	current_user_can( MAKER_JSON_MANAGE_CAPABILITY ) || die;
	check_admin_referer( 'makerjson_save' );
	$_post      = stripslashes_deep( $_POST );
	$doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

	$post_id = (int) $_post['post_id'];
	$ays     = isset( $_post['makerjson_ays'] ) ? $_post['makerjson_ays'] : null;

	$json_content = $_post['makerjson'];
	$sanitized = '';
	$errors    = array();
	$response  = array();

	// Placeholder for JSON validation using schema.
	if ( ! validate_makerjson_schema( $json_content, $errors ) ) {
		// If schema validation fails, errors are populated.
		$response['errors'] = $errors;
	} else {
		// Schema validation successful, sanitize JSON content.
		$sanitized = sanitize_textarea_field( $json_content );
	}

	$postarr = array(
		'ID'           => $post_id,
		'post_title'   => 'Maker.json',
		'post_content' => $sanitized,
		'post_type'    => 'makerjson',
		'post_status'  => 'publish',
		'meta_input'   => array(
			'makerjson_errors' => $errors,
		),
	);

	if ( ! $doing_ajax || empty( $errors ) || 'y' === $ays ) {
		$post_id = wp_insert_post( $postarr );

		if ( $post_id ) {
			$response['saved'] = true;
		}
	}

	if ( $doing_ajax ) {
		$response['sanitized'] = $sanitized;

		if ( ! empty( $errors ) ) {
			$response['errors'] = $errors;
		}

		echo wp_json_encode( $response );
		die();
	}

	wp_safe_redirect( esc_url_raw( $_post['_wp_http_referer'] ) . '&updated=true' );
	exit;
}
add_action( 'admin_post_makerjson-save', __NAMESPACE__ . '\save' );
add_action( 'wp_ajax_makerjson-save', __NAMESPACE__ . '\save' );

/**
 * Validate the Maker.json content against a predefined JSON schema.
 *
 * @param string $json_content JSON content to validate.
 * @param array  $errors       Array to hold validation errors.
 *
 * @return bool True if valid, false otherwise.
 */
function validate_makerjson_schema( $json_content, &$errors ) {
	$errors = array(); // Reset errors array.

	// Decode the JSON content.
	$data = json_decode( $json_content );

	// Check if JSON decoding was successful.
	if ( json_last_error() !== JSON_ERROR_NONE ) {
		$errors[] = array(
			'type'    => 'invalid_json',
			'message' => __( 'Invalid JSON format.', 'maker-json' ),
		);
		return false;
	}

	// Placeholder for JSON schema validation logic.
	// This could be replaced with actual schema validation using a library or custom validation logic.
	$is_valid = true; // Assume validation passes for now.

	// Add logic to validate `$data` against schema here.

	if ( ! $is_valid ) {
		$errors[] = array(
			'type'    => 'schema_validation_failed',
			'message' => __( 'Maker.json content does not match the required schema.', 'maker-json' ),
		);
	}

	return $is_valid;
}

/**
 * Delete `makerjson_errors` meta when restoring a revision.
 *
 * @param int $post_id Post ID, not revision ID.
 *
 * @return void
 */
function clear_error_meta( $post_id ) {
	delete_post_meta( $post_id, 'makerjson_errors' );
}
add_action( 'wp_restore_post_revision', __NAMESPACE__ . '\clear_error_meta', 10, 1 );
