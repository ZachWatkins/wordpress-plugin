<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/zachwatkins/wordpress-plugin/blob/master/src/class-aglifesciences.php
 * @since      1.0.0
 * @package    wordpress-plugin
 * @subpackage wordpress-plugin/src
 */

/**
 * The core plugin class
 *
 * @since 1.0.0
 * @return void
 */
class WordPress_Plugin {

	/**
	 * File name
	 *
	 * @var file
	 */
	private static $file = __FILE__;

	/**
	 * Instance
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Post type.
	 *
	 * @var publication_post_type
	 */
	private $publication_post_type;

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {

		// Require classes.
		// $this->require_classes();

		// Load Publications post type.
		require_once WORDPRESS_PLUGIN_DIR_PATH . 'src/class-publication-post-type.php';
		$this->publication_post_type = new \WordPress_Plugin\Publication_Post_Type();

		// Register page templates.
		// $this->register_templates();

		// add_action( 'init', array( $this, 'init' ) );

	}

	/**
	 * Init action hook
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init() {

		$this->register_post_types();

	}

	/**
	 * Initialize page templates
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function register_templates() {

		require_once WORDPRESS_PLUGIN_DIR_PATH . 'src/class-pagetemplate.php';
		$home = new \WordPress_Plugin\PageTemplate( WORDPRESS_PLUGIN_TEMPLATE_PATH, 'home.php', 'Home' );
		$home->register();

	}

	/**
	 * Initialize the various classes
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function require_classes() {

		// Add assets.
		require_once WORDPRESS_PLUGIN_DIR_PATH . 'src/class-assets.php';
		new \WordPress_Plugin\Assets();

	}

	/**
	 * Initialize custom post types
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function register_post_types() {

		$publication_post_type = $this->publication_post_type;
		// error_log( gettype( $publication_post_type ) );
		// error_log( gettype( $publication_post_type->register_post_types ) );
		// $publication_post_type->register_post_types();

	}

}
