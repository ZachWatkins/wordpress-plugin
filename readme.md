# WordPress Plugin Boilerplate
You may repurpose code from this repository for your own WordPress development since it uses a GPL-2.0+ license.

## WordPress Requirements

1. First requirement.

## Installation

1. [Download the latest release](https://github.com/zachwatkins/wordpress-plugin/releases/latest)
2. Upload the plugin to your site

## Features

* Settings page
* Shortcode
* Widget
* Custom post types using Advanced Custom Fields and various features and enhancements I've developed for custom post types and taxonomies

## Development Installation

1. Copy this repo to the desired location.
2. In your terminal, navigate to the plugin location 'cd /path/to/the/plugin'.
3. Run "npm start" to configure your local copy of the repo, install dependencies, and build files for a production environment.
4. Or, run "npm start -- develop" to configure your local copy of the repo, install dependencies, and build files for a development environment.

## Development Notes

When you stage changes to this repository and initiate a commit, they must pass PHP and Sass linting tasks before they will complete the commit step. Release tasks can only be used by the repository's owners.

### Post Type Development Notes
1. Post type registration should always occur on the `init` action hook.
2. Post type content should use hooks or template parts instead of full template files, to maintain compatibility between themes.

### Admin Settings Page Notes
`add_options_page` puts a menu/link in the “Settings” menu
`add_menu_page` puts a menu/link at the same level as “Dashboard”, “Posts”, “Media”, etc.
`add_submenu_page` puts a menu/link as a child underneath “Dashboard”, “Posts”, “Media”, etc.

## Development Tasks

1. Run "grunt develop" to compile the css when developing the plugin.
2. Run "grunt watch" to automatically compile the css after saving a *.scss file.
3. Run "grunt" to compile the css when publishing the plugin.
4. Run "npm run checkwp" to check PHP files against WordPress coding standards.

## Development Requirements

* A shell environment variable for `RELEASE_KEY` that contains your Github release key.
* Node: http://nodejs.org/
* NPM: https://npmjs.org/
* Ruby: http://www.ruby-lang.org/en/, version >= 2.0.0p648
* Ruby Gems: http://rubygems.org/
* Ruby Sass: version >= 3.4.22

