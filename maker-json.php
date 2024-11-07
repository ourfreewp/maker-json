<?php
/**
 * Plugin Name:       Maker.json Manager
 * Plugin URI:        https://github.com/OurFreeWP/maker-json
 * Description:       Create, manage, and validate your Maker.json file from within WordPress, just like any other content asset. Requires PHP 7.4+ and WordPress 6.4+.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            FreeWP
 * Author URI:        https://freewp.com
 * License:           GPL-2.0-or-later
 * License URI:       https://spdx.org/licenses/GPL-2.0-or-later.html
 * Text Domain:       maker-json
 *
 * @package Maker_Json_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'MAKER_JSON_MANAGER_VERSION', '1.0.0' );
define( 'MAKER_JSON_MANAGE_CAPABILITY', 'edit_maker_json' );
define( 'MAKER_JSON_MANAGER_POST_OPTION', 'makerjson_post' );

/**
 * Get the minimum version of PHP required by this plugin.
 *
 * @return string Minimum version required.
 */
function makerjson_minimum_php_requirement() {
	return '7.4';
}

/**
 * Whether PHP installation meets the minimum requirements.
 *
 * @return bool True if meets minimum requirements, false otherwise.
 */
function makerjson_site_meets_php_requirements() {
	return version_compare( phpversion(), makerjson_minimum_php_requirement(), '>=' );
}

// Ensuring our PHP version requirement is met before loading the plugin.
if ( ! makerjson_site_meets_php_requirements() ) {
	add_action(
		'admin_notices',
		function() {
			?>
			<div class="notice notice-error">
				<p>
					<?php
					printf(
						/* translators: %s: Minimum required PHP version */
						esc_html__( 'Maker.json Manager requires PHP version %s or later. Please upgrade PHP or disable the plugin.', 'maker-json' ),
						esc_html( makerjson_minimum_php_requirement() )
					);
					?>
				</p>
			</div>
			<?php
		}
	);
	return;
}

require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/post-type.php';
require_once __DIR__ . '/inc/admin.php';
require_once __DIR__ . '/inc/save.php';
