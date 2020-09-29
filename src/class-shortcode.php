<?php
/**
 * Assets for the plugin.
 *
 * @link       https://github.com/zachwatkins/wordpress-plugin/blob/master/src/class-shortcode.php
 * @since      1.0.0
 * @package    wordpress-plugin
 * @subpackage wordpress-plugin/src
 */

namespace WordPress_Plugin;

/**
 * Create shortcode to display the faculty search form.
 *
 * @package wordpress-plugin
 * @since 1.0.0
 */
class Shortcode {

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {

		add_shortcode( 'plugin_name_shortcode', array( $this, 'shortcode' ) );
		add_shortcode( 'plugin_name_search', array( $this, 'shortcode_search' ) );

	}

	/**
	 * Output for plugin_name_shortcode shortcode.
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function shortcode( $atts ) {

		$defaults = array(
			'param' => '',
			'class' => 'foo',
		);

		$atts = shortcode_atts(
			$defaults,
			$atts
		);

		// Sanitize shortcode parameters for security.
		$atts['param'] = esc_attr( $atts['param'] );
		$atts['class'] = esc_attr( $atts['class'] );

		// Restrict output to certain elements and attributes.
		$allowed_html = array(
			'p' => array(
				'class' => array(),
			),
		);

		$output = sprintf(
			'<p class="%s">%s</p>',
			$atts['class'],
			$atts['param']
		);

		$return = wp_kses(
			$output,
			$allowed_html
		);

		return $return;

	}

	/**
	 * Output for plugin_name_search shortcode.
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function shortcode_search( $atts ) {

		$defaults = array(
			'param' => '',
			'class' => 'foo',
		);

		$atts = shortcode_atts(
			$defaults,
			$atts
		);

		// Sanitize shortcode parameters for security.
		$atts['param'] = esc_attr( $atts['param'] );
		$atts['class'] = esc_attr( $atts['class'] );

		// Restrict output to certain elements and attributes.
		$allowed_html = array(
			'p' => array(
				'class' => array(),
			),
		);

		ob_start();
		include WORDPRESS_PLUGIN_DIR_PATH . 'templates/search-publication.php';
		$return = ob_get_clean();

		return $return;

	}

}
