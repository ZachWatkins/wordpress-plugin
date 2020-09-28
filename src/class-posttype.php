<?php
/**
 * The file that initializes custom post types
 *
 * A class definition that registers custom post types with their attributes
 *
 * @link       https://github.com/zachwatkins/wordpress-plugin/blob/master/src/class-posttype.php
 * @since      1.0.0
 * @package    wordpress-plugin
 * @subpackage wordpress-plugin/src
 */

namespace WordPress_Plugin;

/**
 * The post type registration class
 *
 * @since 1.0.0
 * @return void
 */
class PostType {

	/**
	 * Post type slug
	 *
	 * @var search_file
	 */
	private $post_type;

	/**
	 * Builds and registers the custom taxonomy.
	 *
	 * @param  array  $name       The post type name.
	 * @param  string $slug       The post type slug.
	 * @param  array  $taxonomies The taxonomies this post type supports. Accepts arguments found in
	 *                            WordPress core register_post_type function.
	 * @param  string $icon       The icon used in the admin navigation sidebar.
	 * @param  array  $supports   The attributes this post type supports. Accepts arguments found in
	 *                            WordPress core register_post_type function.
	 * @param  array  $user_args  Additional user arguments which override all others for the function register_post_type.
	 * @return void
	 */
	public function __construct(
		$name = array(
			'singular' => '',
			'plural'   => '',
		),
		$slug,
		$taxonomies = array(
			'category',
			'post_tag',
		),
		$icon = 'dashicons-portfolio',
		$supports = array( 'title' ),
		$user_args = array()
	) {

		$this->post_type = $slug;
		$singular        = $name['singular'];
		$plural          = $name['plural'];

		// Backend labels.
		$labels = array(
			'name'               => $plural,
			'singular_name'      => $singular,
			'add_new'            => __( 'Add New', 'wordpress-plugin-textdomain' ),
			'add_new_item'       => __( 'Add New', 'wordpress-plugin-textdomain' ) . " $singular",
			'edit_item'          => __( 'Edit', 'wordpress-plugin-textdomain' ) . " $singular",
			'new_item'           => __( 'New', 'wordpress-plugin-textdomain' ) . " $singular",
			'view_item'          => __( 'View', 'wordpress-plugin-textdomain' ) . " $singular",
			'search_items'       => __( 'Search', 'wordpress-plugin-textdomain' ) . " $plural",
			/* translators: placeholder is the plural taxonomy name */
			'not_found'          => sprintf( esc_html__( 'No %d Found', 'wordpress-plugin-textdomain' ), $plural ),
			/* translators: placeholder is the plural taxonomy name */
			'not_found_in_trash' => sprintf( esc_html__( 'No %d found in trash', 'wordpress-plugin-textdomain' ), $plural ),
			'parent_item_colon'  => '',
			'menu_name'          => $plural,
		);

		// Post type arguments.
		$this->args = array_merge(
			array(
				'can_export'         => true,
				'has_archive'        => true,
				'labels'             => $labels,
				'menu_icon'          => $icon,
				'menu_position'      => 20,
				'public'             => true,
				'publicly_queryable' => true,
				'show_in_rest'       => true,
				'show_in_menu'       => true,
				'show_in_admin_bar'  => true,
				'show_in_nav_menus'  => true,
				'show_ui'            => true,
				'supports'           => $supports,
				'taxonomies'         => $taxonomies,
				'rewrite'            => array(
					'with_front' => false,
					'slug'       => $slug,
				),
			),
			$user_args
		);

		// Register the post type.
		register_post_type( $this->post_type, $this->args );

	}

}
