# Maker.json Manager for WordPress

Create, manage, and validate your maker.json file from within WordPress to easily list your resources and channels.

Features

maker.json is an emerging standard allowing creators to publish structured lists of their available resources, tools, and channels. Inspired by our work with publishers, we created Maker.json Manager to help you manage and validate your maker.json file directly in WordPress, without needing manual uploads. Built-in validation helps avoid malformed records, ensuring accurate discovery of your resources.

Preparing for New Standards

We’re closely following developments in the content management and publishing spaces. Maker.json Manager is designed not only to simplify maker.json file management but to be adaptable for future upgrades as the maker.json standard evolves.

Can I use this with multisite?

Yes! However, for subfolder multisite setups, maker.json will only work for the main site because only one maker.json file is allowed per domain or subdomain. We recommend activating Maker.json Manager per-site in multisite configurations.

Requirements

	•	Requires PHP 7.4+.
	•	Requires WordPress 6.4+.
	•	Rewrites need to be enabled for WordPress to respond to requests for /maker.json.
	•	For root access: Your site URL must be at the root of the domain (e.g., https://example.com/) as per the maker.json specification.

Installation

	1.	Install the plugin via the plugin installer, by searching for it or uploading a .zip file.
	2.	Activate the plugin.
	3.	Head to Settings → Maker.json and add the resource listings you need.
	4.	View your file at yoursite.com/maker.json.

Note: If an existing maker.json file is in your web root, the plugin will not read or overwrite its contents. You’ll need to rename or remove any existing maker.json file (keeping a backup if needed) before fully managing it through WordPress.

Screenshots

1. Example of editing a maker.json file with errors and a link to browse maker.json file revisions.

2. Example of comparing maker.json file revisions.

3. Example of viewing a maker.json file in the Revisions editor.

Support Level

Changelog

A complete listing of all notable changes to Maker.json Manager is documented in CHANGELOG.md.

Contributing

Please read CODE_OF_CONDUCT.md for details on our code of conduct, CONTRIBUTING.md for contribution guidelines, and CREDITS.md for a list of contributors and libraries used by Maker.json Manager.

Like what you see?

<p align="center">
<a href="http://10up.com/contact/"><img src="https://10up.com/uploads/2016/10/10up-Github-Banner.png" width="850"></a>
</p>
