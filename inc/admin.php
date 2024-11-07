<?php
/**
 * Admin functionality for Maker.json.
 *
 * @package Maker_Json_Manager
 */

namespace MakerJson;

/**
 * Enqueue any necessary scripts.
 *
 * @param  string $hook Hook name for the current screen.
 *
 * @return void
 */
function admin_enqueue_scripts( $hook ) {
	if ( ! preg_match( '/makerjson-settings$/', $hook ) ) {
		return;
	}

	wp_enqueue_script(
		'makerjson',
		esc_url( plugins_url( '/js/admin.js', dirname( __FILE__ ) ) ),
		array( 'jquery', 'wp-backbone', 'wp-codemirror' ),
		MAKER_JSON_MANAGER_VERSION,
		true
	);
	wp_enqueue_style( 'code-editor' );
	wp_enqueue_style(
		'makerjson',
		esc_url( plugins_url( '/css/admin.css', dirname( __FILE__ ) ) ),
		array(),
		MAKER_JSON_MANAGER_VERSION
	);

	$strings = array(
		'error_message' => esc_html__( 'Your Maker.json contains the following issues:', 'maker-json' ),
		'unknown_error' => esc_html__( 'An unknown error occurred.', 'maker-json' ),
	);

	wp_localize_script( 'makerjson', 'makerjson', $strings );
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_enqueue_scripts' );

/**
 * Output some CSS directly in the head of the document.
 *
 * Should there ever be more than ~25 lines of CSS, this should become a separate file.
 *
 * @return void
 */
function admin_head_css() {
	?>
<style>
.CodeMirror {
	width: 100%;
	min-height: 60vh;
	height: calc( 100vh - 295px );
	border: 1px solid #ddd;
	box-sizing: border-box;
	}
</style>
	<?php
}
add_action( 'admin_head-settings_page_makerjson-settings', __NAMESPACE__ . '\admin_head_css' );

/**
 * Appends a query argument to the edit url to redirect to the maker.json screen.
 *
 * @param string $url Edit url.
 * @return string Edit url.
 */
function maker_json_adjust_revisions_return_to_editor_link( $url ) {
	global $pagenow, $post;

	if ( 'revision.php' !== $pagenow || ! isset( $_REQUEST['makerjson'] ) ) { 
		return $url;
	}

	return admin_url( 'options-general.php?page=makerjson-settings' );
}
add_filter( 'get_edit_post_link', __NAMESPACE__ . '\maker_json_adjust_revisions_return_to_editor_link' );

/**
 * Modify revisions data to preserve makerjson argument.
 *
 * @param array $revisions_data The bootstrapped data for the revisions screen.
 * @return array Modified bootstrapped data for the revisions screen.
 */
function makerjson_revisions_restore( $revisions_data ) {
	if ( isset( $_REQUEST['makerjson'] ) ) {
		$revisions_data['restoreUrl'] = add_query_arg(
			'makerjson',
			1,
			$revisions_data['restoreUrl']
		);
	}

	return $revisions_data;
}
add_filter( 'wp_prepare_revision_for_js', __NAMESPACE__ . '\makerjson_revisions_restore' );

/**
 * Hide the revisions title with CSS, since WordPress always shows the title
 * field even if unchanged, and the title is not relevant for maker.json.
 */
function admin_header_revisions_styles() {
	$current_screen = get_current_screen();

	if ( ! $current_screen || 'revision' !== $current_screen->id ) {
		return;
	}

	if ( ! isset( $_REQUEST['makerjson'] ) ) {
		return;
	}

	?>
	<style>
		.revisions-diff .diff h3 {
			display: none;
		}
		.revisions-diff .diff table.diff:first-of-type {
			display: none;
		}
	</style>
	<?php

}
add_action( 'admin_head', __NAMESPACE__ . '\admin_header_revisions_styles' );

/**
 * Add admin menu page.
 *
 * @return void
 */
function admin_menu() {
	add_options_page(
		esc_html__( 'Maker.json', 'maker-json' ),
		esc_html__( 'Maker.json', 'maker-json' ),
		MAKER_JSON_MANAGE_CAPABILITY,
		'makerjson-settings',
		__NAMESPACE__ . '\makerjson_settings_screen'
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\admin_menu' );

/**
 * Set up settings screen for maker.json.
 *
 * @return void
 */
function makerjson_settings_screen() {
	$post_id = get_option( MAKER_JSON_MANAGER_POST_OPTION );

	$strings = array(
		'existing'      => __( 'Existing Maker.json file found', 'maker-json' ),
		'precedence'    => __( 'A maker.json file on the server will take precedence over any content entered here. You will need to rename or remove the existing maker.json file before you will be able to see any changes you make on this screen.', 'maker-json' ),
		'errors'        => __( 'Your Maker.json contains the following issues:', 'maker-json' ),
		'screen_title'  => __( 'Manage Maker.json', 'maker-json' ),
		'content_label' => __( 'Maker.json content', 'maker-json' ),
	);

	$args = array(
		'post_type'  => 'makerjson',
		'post_title' => 'Maker.json',
		'option'     => MAKER_JSON_MANAGER_POST_OPTION,
		'action'     => 'makerjson-save',
	);

	settings_screen( $post_id, $strings, $args );
}

/**
 * Output the settings screen for maker.json.
 *
 * @param int   $post_id Post ID associated with the file.
 * @param array $strings Translated strings that mention the specific file name.
 * @param array $args    Array of other necessary information to appropriately name items.
 *
 * @return void
 */
function settings_screen( $post_id, $strings, $args ) {
	$post             = false;
	$content          = false;
	$errors           = array();
	$revision_count   = 0;
	$last_revision_id = false;

	if ( $post_id ) {
		$post = get_post( $post_id );
	}

	if ( is_a( $post, 'WP_Post' ) ) {
		$content          = $post->post_content;
		$revisions        = wp_get_post_revisions( $post->ID );
		$revision_count   = count( $revisions );
		$last_revision    = array_shift( $revisions );
		$last_revision_id = $last_revision ? $last_revision->ID : false;
		$errors           = get_post_meta( $post->ID, 'makerjson_errors', true );
		$warnings         = get_post_meta( $post->ID, 'makerjson_warnings', true );
		$revisions_link   = $last_revision_id ? admin_url( 'revision.php?makerjson=1&revision=' . $last_revision_id ) : false;

	} else {
		$postarr = array(
			'post_title'   => $args['post_title'],
			'post_content' => '',
			'post_type'    => $args['post_type'],
			'post_status'  => 'publish',
		);

		$post_id = wp_insert_post( $postarr );
		if ( $post_id ) {
			update_option( $args['option'], $post_id );
		}
	}

	clean_orphaned_posts( $post_id, $args['post_type'] );
	?>
<div class="wrap">
	<?php if ( ! empty( $warnings ) ) : ?>
		<div class="notice notice-warning makerjson-notice">
		<ul>
			<?php
			foreach ( $warnings as $warning ) {
				echo '<li>';

				if ( isset( $warning['message'] ) ) {
					$message = sprintf( '%1$s', $warning['message'] );
					echo esc_html( $message );
				} else {
					display_formatted_error( $warning );
				}

				echo '</li>';
			}
			?>
		</ul>
		</div>
	<?php endif; ?>
	<div class="notice notice-error makerjson-notice existing-makerjson" style="display: none;">
		<p><strong><?php echo esc_html( $strings['existing'] ); ?></strong></p>
		<p><?php echo esc_html( $strings['precedence'] ); ?></p>

		<p><?php echo esc_html_e( 'Removed the existing file but are still seeing this warning?', 'maker-json' ); ?> <a class="makerjson-rerun-check" href="#"><?php echo esc_html_e( 'Re-run the check now', 'maker-json' ); ?></a> <span class="spinner" style="float:none;margin:-2px 5px 0"></span></p>
	</div>
	<?php if ( ! empty( $errors ) ) : ?>
	<div class="notice notice-error makerjson-notice makerjson-notice-save-error">
		<p><strong><?php echo esc_html( $strings['errors'] ); ?></strong></p>
		<ul>
			<?php
			foreach ( $errors as $error ) {
				echo '<li>';

				if ( isset( $error['message
