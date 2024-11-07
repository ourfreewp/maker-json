# Maker.json Manager for WordPress

## Features

maker.json is a new standard enabling publishers and makers to list their available resources, channels, and services directly from a root file. Drawing on our experience with publishers and inspired by 10up’s work with ads.txt, we created an intuitive way to manage and validate your maker.json file from within WordPress. This helps avoid malformed records, which could otherwise lead to visibility and accessibility issues for your listed resources.

What about other standards?

The maker.json Manager positions you to meet emerging needs as the landscape for digital publishing evolves. Our team continues to monitor new standards and adjustments, and we intend for this plugin to keep pace with future updates.

Can I use this with multisite?

Yes! For subfolder installations, however, maker.json will only work for the main site, as only one maker.json can be served from the root of a domain or subdomain. For multisite setups, we recommend activating the Maker.json Manager per site.

## Requirements

	•	Requires PHP 7.4+.
	•	Requires WordPress 6.4+.
	•	Rewrites need to be enabled so WordPress can handle requests to /maker.json.
	•	Your site URL must be at the root of the domain (e.g., https://example.com/) for maker.json to display correctly, adhering to the emerging maker.json standard.

## Installation

	1.	Install the plugin via the plugin installer, by searching for it or uploading a .zip file.
	2.	Activate the plugin.
	3.	Navigate to Settings → Maker.json to add or edit entries for your maker.json file.
	4.	Check it out at yoursite.com/maker.json!

Note: If an existing maker.json file is already in your web root, the plugin will not overwrite it. You’ll need to rename or remove it (keeping a backup if needed) to allow the plugin to manage maker.json through WordPress.

## Screenshots

1. Example of editing a maker.json file with errors and a link to browse maker.json file revisions.

2. Example of comparing maker.json file revisions.

3. Example of comparing two disparate maker.json file revisions.

## Support Level


## Changelog

A complete list of all notable changes for Maker.json Manager is documented in CHANGELOG.md.

## Contributing

Please read CODE_OF_CONDUCT.md for our code of conduct, CONTRIBUTING.md for contribution guidelines, and CREDITS.md for a list of contributors and libraries used by Maker.json Manager.
