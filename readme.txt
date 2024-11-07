Maker.json Manager

Contributors:      vinnysgreen, 10up, helen, adamsilverstein, jakemgold, peterwilsoncc, jeffpaul
Tags:              maker.json, web standards, resource manager
Tested up to:      6.6
Stable tag:        1.0.0
License:           GPL-2.0-or-later
License URI:       https://spdx.org/licenses/GPL-2.0-or-later.html

Create, manage, and validate your maker.json file from within WordPress to easily list your resources and channels.

== Description ==

Manage and validate your maker.json file from within WordPress. Inspired by 10up’s work on Ads.txt Manager, this plugin is designed to help content creators and publishers efficiently share their resources through the emerging maker.json standard. Requires PHP 7.4+ and WordPress 5.7+.

=== What is maker.json? ===

Maker.json is a new standard allowing creators to publish a structured list of their resources, tools, and channels directly from the root of their website. Inspired by work with ads.txt, 10up has developed this tool for easy management and validation of maker.json in WordPress. Avoiding malformed entries is essential, as they can prevent tools from accurately indexing your resources, impacting discoverability.

=== Technical Notes ===

	•	Requires PHP 7.4+.
	•	Requires WordPress 6.3+.
	•	Some ad blockers may interfere with syntax highlighting and error-checking on the edit screen.
	•	Rewrites must be enabled to allow WordPress to supply /maker.json on request.
	•	For root access: The site URL must be at the domain’s root (e.g., https://example.com/) due to the maker.json specification.

=== Roadmap ===

As the maker.json specification evolves, this plugin will adapt to incorporate updates that improve content listing and visibility. We’ll continue to monitor emerging best practices.

=== Can I use this with multisite? ===

Yes! For subfolder installations, maker.json will only work for the main site, as each domain or subdomain can only serve one maker.json. We recommend activating Maker.json Manager per-site on multisite setups.

== Screenshots ==

	1.	Example of editing a maker.json file with validation options.
	2.	Example of comparing maker.json file revisions.
	3.	Example of viewing the contents of a maker.json file.

== Installation ==

	1.	Install the plugin via the plugin installer by searching or uploading a .zip file.
	2.	Activate the plugin.
	3.	Navigate to Settings → Maker.json and enter the resource listings you need.
	4.	Verify at yoursite.com/maker.json!

Note: If you have an existing maker.json file in the web root, the plugin will not read its contents, and changes in WordPress will not overwrite the physical file.

You’ll need to rename or remove any existing maker.json file (keeping a copy if needed) to manage it fully through WordPress.

== Changelog ==

= 1.0.0 - 2024-11-10 =

	•	Initial Release: Added support for creating and managing maker.json files directly from WordPress.
	•	Features: Syntax validation for maker.json content, revision comparison, and support for multisite installations.
