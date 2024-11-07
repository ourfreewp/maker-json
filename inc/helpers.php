<?php
/**
 * Helper functions for Maker.json.
 *
 * @package Maker_Json_Manager
 */

namespace MakerJson;

/**
 * Display the contents of /maker.json when requested.
 *
 * @return void
 */
function display_maker_json() {
	$request = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : false;
	if ( '/maker.json' === $request || '/maker.json?' === substr( $request, 0, 11 ) ) {
		$post_id = get_option( MAKER_JSON_MANAGER_POST_OPTION );

		// Set custom header for maker.json
		header( 'X-Maker-Json-Generator: https://wordpress.org/plugins/maker-json/' );

		// Will fall through if no option found, likely to a 404.
		if ( ! empty( $post_id ) ) {
			$post = get_post( $post_id );

			if ( ! $post instanceof \WP_Post ) {
				return;
			}

			header( 'Content-Type: application/json' );
			$makerjson_content = $post->post_content;

			/**
			 * Placeholder for JSON parsing logic.
			 * Consider validating and encoding JSON data here.
			 */
			echo apply_filters( 'maker_json_content', $makerjson_content );
			die();
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\display_maker_json' );

/**
 * Add custom capabilities.
 *
 * @return void
 */
function add_capabilities() {
	$role = get_role( 'administrator' );

	// Bail early if the administrator role doesn't exist.
	if ( null === $role ) {
		return;
	}

	if ( ! $role->has_cap( MAKER_JSON_MANAGE_CAPABILITY ) ) {
		$role->add_cap( MAKER_JSON_MANAGE_CAPABILITY );
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\add_capabilities' );
register_activation_hook( __FILE__, __NAMESPACE__ . '\add_capabilities' );

/**
 * Remove custom capabilities when deactivating the plugin.
 *
 * @return void
 */
function remove_capabilities() {
	$role = get_role( 'administrator' );

	// Bail early if the administrator role doesn't exist.
	if ( null === $role ) {
		return;
	}

	$role->remove_cap( MAKER_JSON_MANAGE_CAPABILITY );
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\remove_capabilities' );

/**
 * Add a query var to detect when maker.json has been saved.
 *
 * @param array $qvars Array of query vars.
 *
 * @return array Array of query vars.
 */
function add_query_vars( $qvars ) {
	$qvars[] = 'maker_json_saved';
	return $qvars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\add_query_vars' );
