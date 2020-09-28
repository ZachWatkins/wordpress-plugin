<?php
/**
 * The file that renders the single page template
 *
 * @link       https://github.com/zachwatkins/wordpress-plugin/blob/master/templates/home.php
 * @since      1.0.0
 * @package    wordpress-plugin
 * @subpackage wordpress-plugin/templates
 */

// Template CSS.
add_action( 'wp_enqueue_scripts', 'wordpress_plugin_single_template_styles', 1 );

/**
 * Registers and enqueues template styles.
 *
 * @since 1.0.0
 * @return void
 */
function wordpress_plugin_single_template_styles() {

	wp_register_style(
		'wordpress-plugin-template-single',
		ZWWPPN_DIR_URL . 'css/single-publication.css',
		array(),
		filemtime( ZWWPPN_DIR_PATH . 'css/single.css' ),
		'screen'
	);

	wp_enqueue_style( 'wordpress-plugin-template-single' );

}
