<?php
/**
 * The file that renders the single page template
 *
 * @link       https://github.com/zachwatkins/wordpress-plugin/blob/master/templates/page-template.php
 * @since      1.0.0
 * @package    wordpress-plugin
 * @subpackage wordpress-plugin/templates
 */

get_header();

$theme = wp_get_theme();
if ( 'Genesis' === $theme->name || 'Genesis' === $theme->parent()->name ) {

	genesis();

} else {

	?><div id="primary" class="content-area"><main id="main" class="site-main">
	<?php

		the_content();

	?>
	</main></div>
	<?php

}

/**
 * Registers and enqueues template styles.
 *
 * @since 1.0.0
 * @return void
 */
function wordpress_plugin_landing_styles() {

	wp_register_style(
		'wordpress-plugin-template-landing',
		WORDPRESS_PLUGIN_DIR_URL . 'css/page-template.css',
		false,
		filemtime( WORDPRESS_PLUGIN_DIR_PATH . 'css/page-template.css' ),
		'screen'
	);

	wp_enqueue_style( 'wordpress-plugin-template-landing' );

}
add_action( 'wp_enqueue_scripts', 'wordpress_plugin_landing_styles', 1 );

get_footer();
