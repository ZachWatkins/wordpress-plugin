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
	 * Initialize the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {

		// Public asset files.
		require_once WORDPRESS_PLUGIN_DIR_PATH . 'src/class-assets.php';
		new \WordPress_Plugin\Assets();

		// Register post types.
		require_once WORDPRESS_PLUGIN_DIR_PATH . 'src/class-publication-post-type.php';
		new \WordPress_Plugin\Publication_Post_Type();

		// Register templates.
		$this->register_templates();

		// Init hook.
		add_action( 'init', array( $this, 'init' ) );

		// Register widgets.
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

	}

	/**
	 * Initialize page templates
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function register_templates() {

		// Register page templates.
		require_once WORDPRESS_PLUGIN_DIR_PATH . 'src/class-pagetemplate.php';
		$landing = new \WordPress_Plugin\PageTemplate( WORDPRESS_PLUGIN_TEMPLATE_PATH, 'page-template.php', 'Landing Page' );
		$landing->register();

	}

	/**
	 * Init action hook
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {

		$this->register_shortcodes();

	}

	/**
	 * Register shortcodes.
	 *
	 * @since 1.0.2
	 *
	 * @return void
	 */
	public static function register_shortcodes() {

		require_once WORDPRESS_PLUGIN_DIR_PATH . 'src/class-shortcode.php';
		new \WordPress_Plugin\Shortcode();

	}

	/**
	 * Register widgets
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function register_widgets() {

		require_once WORDPRESS_PLUGIN_DIR_PATH . 'src/class-widget.php';
		$widget = new \WordPress_Plugin\Widget();
		register_widget( $widget );

	}

}
