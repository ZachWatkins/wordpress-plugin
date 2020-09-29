<?php
/**
 * The file that defines css and js files loaded for the plugin
 *
 * A class definition that includes css and js files used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/zachwatkins/wordpress-plugin/blob/master/src/class-publication-post-type.php
 * @since      1.0.0
 * @package    wordpress-plugin
 * @subpackage wordpress-plugin/src
 */

namespace WordPress_Plugin;

/**
 * Add assets
 *
 * @package wordpress-plugin
 * @since 1.0.0
 */
class Publication_Post_Type {

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
	 * The publication post type slug.
	 *
	 * @var publication_post_slug
	 */
	private $publication_post_slug = 'publication';

	/**
	 * The author post type slug.
	 *
	 * @var author_post_slug
	 */
	private $author_post_slug = 'pubauthor';

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {

		// Add custom fields.
		$this->load_custom_fields();

		// Template CSS.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 1 );

		// Register_post_types.
		add_action( 'init', array( $this, 'register_post_types' ) );

		// Add Authors URL parameter for the Publication post type.
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

		// Remove taxonomy metaboxes in place of ACF.
		add_action( "add_meta_boxes_{$this->author_post_slug}", array( $this, 'remove_taxonomies_metaboxes' ) );

		// Add post meta after post title.
		$theme = wp_get_theme();
		if ( 'Genesis' === $theme->name || 'Genesis' === $theme->parent()->name ) {
			add_filter( 'genesis_post_info', array( $this, 'post_info' ), 12 );
		} else {
			add_filter( 'the_title', array( $this, 'post_info' ), 12 );
		}

		// Add and modify post type search form.
		add_theme_support( 'html5', array( 'search-form' ) );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
		add_action( 'loop_start', array( $this, 'add_post_type_search_form' ), 99 );
		add_filter( 'wp_dropdown_cats', array( $this, 'add_search_form_multi_select' ), 10, 2 );
		add_filter( 'wp_dropdown_pages', array( $this, 'add_search_form_multi_select' ), 10, 2 );
		add_action( 'init', array( $this, 'kses_multiple_select' ) );

	}

	/**
	 * Add custom fields.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function load_custom_fields() {

		if ( class_exists( 'acf' ) ) {
			require_once WORDPRESS_PLUGIN_DIR_PATH . 'fields/publication-fields.php';
			require_once WORDPRESS_PLUGIN_DIR_PATH . 'fields/author-fields.php';
		}

	}

	/**
	 * Registers post type styles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_styles() {

		wp_register_style(
			'publication-styles',
			WORDPRESS_PLUGIN_DIR_URL . 'css/publication.css',
			false,
			filemtime( WORDPRESS_PLUGIN_DIR_PATH . 'css/publication.css' ),
			'screen'
		);

	}

	/**
	 * Enqueues post type styles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'publication-styles' );

	}

	/**
	 * Initialize the various classes
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function require_classes() {

		// Add post type classes.
		require_once WORDPRESS_PLUGIN_DIR_PATH . 'src/class-posttype.php';
		require_once WORDPRESS_PLUGIN_DIR_PATH . 'src/class-taxonomy.php';

	}

	/**
	 * Initialize custom post types
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function register_post_types() {

		// Add post type classes.
		$this->require_classes();

		// Register taxonomies.
		new \WordPress_Plugin\Taxonomy(
			'Topic Category',
			'topic-category',
			$this->publication_post_slug,
			array(
				'labels' => array(
					'name'         => 'Topic Categories',
					'search_items' => __( 'Search', 'wordpress-plugin-textdomain' ) . ' Topic Categories',
					'all_items'    => __( 'All', 'wordpress-plugin-textdomain' ) . ' Topic Categories',
					'menu_name'    => 'Topic Categories',
				),
			)
		);
		new \WordPress_Plugin\Taxonomy( 'Topic Area', 'topic-area', $this->publication_post_slug );
		new \WordPress_Plugin\Taxonomy( 'Department', 'department', array( $this->publication_post_slug, $this->author_post_slug ) );
		new \WordPress_Plugin\Taxonomy(
			'Specialty',
			'specialty',
			array( $this->publication_post_slug, $this->author_post_slug ),
			array(
				'labels' => array(
					'name'         => 'Specialties',
					'search_items' => __( 'Search', 'wordpress-plugin-textdomain' ) . ' Specialties',
					'all_items'    => __( 'All', 'wordpress-plugin-textdomain' ) . ' Specialties',
					'menu_name'    => 'Specialties',
				),
			)
		);
		new \WordPress_Plugin\Taxonomy( 'Role', 'role', array( $this->author_post_slug ) );

		/* Register post types */
		new \WordPress_Plugin\PostType(
			array(
				'singular' => 'Publication',
				'plural'   => 'Publications',
			),
			$this->publication_post_slug,
			array(),
			'dashicons-portfolio',
			array( 'title', 'editor' )
		);

		new \WordPress_Plugin\PostType(
			array(
				'singular' => 'Author',
				'plural'   => 'Authors',
			),
			$this->author_post_slug,
			array(),
			'dashicons-portfolio',
			array( 'title' ),
			array(
				'public'              => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'exclude_from_search' => true,
				'show_in_nav_menus'   => false,
				'has_archive'         => true,
				'rewrite'             => false,
				'show_in_rest'        => true,
				'hierarchical'        => true,
			)
		);

		if ( ! get_option( 'wordpress_plugin_permalinks_flushed' ) ) {

			flush_rewrite_rules( false );
			update_option( 'wordpress_plugin_permalinks_flushed', 1 );

		}

	}

	/**
	 * Remove taxonomy metaboxes from Author post edit pages.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function remove_taxonomies_metaboxes() {

		remove_meta_box( 'tagsdiv-department', $this->author_post_slug, 'side' );
		remove_meta_box( 'tagsdiv-specialty', $this->author_post_slug, 'side' );
		remove_meta_box( 'tagsdiv-role', $this->author_post_slug, 'side' );

	}

	/**
	 * Make post type searchable by taxonomies and attached custom field value.
	 *
	 * @since 1.0.0
	 * @param object $query The WP_Query object.
	 * @return object
	 */
	public static function pre_get_posts( $query ) {

		// Do not modify queries in the admin.
		if ( is_admin() ) {

			return $query;

		}

		// Add this post type to searchable post types.
		if ( $query->is_main_query() ) {
			if ( $query->is_search ) {
				$post_types = $query->get( 'post_type' );
				if ( ! is_array( $post_types ) ) {
					$post_types = array();
				}
				$post_types[] = $this->publication_post_slug;
				$query->set( 'post_type', $post_types );
			}
		}

		// Only modify queries for post type.
		if ( isset( $query->query_vars['post_type'] ) && $this->publication_post_slug === $query->query_vars['post_type'] ) {

			$tax_query = array();

			if ( array_key_exists( 'topic-category', $query->query ) ) {
				$tax_query[] = array(
					'taxonomy' => 'topic-category',
					'field'    => 'slug',
					'terms'    => $query->query['topic-category'],
					'operator' => 'IN',
				);
			}

			if ( array_key_exists( 'topic-area', $query->query ) ) {
				$tax_query[] = array(
					'taxonomy' => 'topic-area',
					'field'    => 'slug',
					'terms'    => $query->query['topic-area'],
					'operator' => 'IN',
				);
			}

			if ( array_key_exists( 'department', $query->query ) ) {
				$tax_query[] = array(
					'taxonomy' => 'department',
					'field'    => 'slug',
					'terms'    => $query->query['department'],
					'operator' => 'IN',
				);
			}

			if ( array_key_exists( 'specialty', $query->query ) ) {
				$tax_query[] = array(
					'taxonomy' => 'specialty',
					'field'    => 'slug',
					'terms'    => $query->query['specialty'],
					'operator' => 'IN',
				);
			}

			if ( 1 < count( $tax_query ) ) {
				$tax_query['relation'] = 'AND';
			}

			if ( 0 < count( $tax_query ) ) {
				$query->set( 'tax_query', $tax_query );
			}

			// Allow publication authors to be searchable in a URL.
			if ( isset( $query->query_vars[ $this->author_post_slug ] ) ) {

				$slugs      = $query->query_vars[ $this->author_post_slug ];
				$meta_query = $query->get( 'meta_query' );

				if ( empty( $meta_query ) ) {

					$meta_query = array();

				}

				foreach ( $slugs as $slug ) {
					$post         = get_page_by_path( $slug, OBJECT, $this->author_post_slug );
					$id           = strval( $post->ID );
					$serial_value = sprintf(
						's:%s:"%s";',
						strlen( $id ),
						$id
					);
					$meta_query[] = array(
						'key'     => "{$this->author_post_slug}s",
						'value'   => $serial_value,
						'compare' => 'LIKE',
					);
				}

				$query->set( 'meta_query', $meta_query );

			}
		}

		return $query;

	}

	/**
	 * Display Publication post type meta on single or list pages.
	 *
	 * @param string $title The post title.
	 * @since 1.0.0
	 * @return string
	 */
	public static function post_info( $title ) {

		global $post;

		if ( in_the_loop() && is_object( $post ) && property_exists( $post, 'post_type' ) && $this->publication_post_slug === $post->post_type ) {

			$post_meta_out    = '';
			$original_authors = get_field( 'authors' );
			$oauthor_plural   = count( $original_authors ) > 1 ? 's' : '';
			$oauthor_pubs     = array(); // Links to all publications by the author(s).
			$oauth_out        = '';
			$tcategories      = get_the_terms( $post, 'topic-category' );
			$tcat_plural      = count( $tcategories ) > 1 ? 'ies' : 'y';
			$tcat_pubs        = array();
			$tcat_out         = '';

			// Create original author output.
			if ( ! empty( $original_authors ) ) {

				foreach ( $original_authors as $oauthor_id ) {
					$author         = get_post( $oauthor_id );
					$archive        = get_post_type_archive_link( $this->publication_post_slug );
					$separator      = false !== strpos( $archive, '?' ) ? '&' : '?';
					$oauthor_pubs[] = sprintf(
						'<a href="%s%s%s=%s">%s</a>',
						$archive,
						$separator,
						$this->author_post_slug,
						$author->post_name,
						$author->post_title
					);
				}

				$oauth_out = sprintf(
					'<strong>Author%s</strong>: %s',
					$oauthor_plural,
					implode( ', ', $oauthor_pubs )
				);

			}

			// Create topic category output.
			if ( ! empty( $tcategories ) ) {

				foreach ( $tcategories as $tcategory ) {
					$tcat_pubs[] = sprintf(
						'<a href="%s?topic-category=%s">%s</a>',
						get_post_type_archive_link( $this->publication_post_slug ),
						$tcategory->slug,
						$tcategory->name
					);
				}

				$tcat_out = sprintf(
					'<strong>Topical Categor%s</strong>: %s',
					$tcat_plural,
					implode( ', ', $tcat_pubs )
				);

			}

			if ( ! is_single() ) {

				// Add original author information.
				if ( ! empty( $oauth_out ) ) {
					$post_meta_out .= $oauth_out . ' <br>';
				}

				// Add topic category information.
				if ( ! empty( $tcategories ) ) {
					$post_meta_out .= $tcat_out;
				}
			} else {

				$revision_records = get_field( 'revision_recs' );
				$post_date        = get_the_date( 'F j, Y', $post );
				$last_revision    = false;
				$departments      = get_the_terms( $post, 'department' );
				$dept_plural      = count( $departments ) > 1 ? 's' : '';
				$dept_pubs        = array();
				$tares            = get_the_terms( $post, 'topic-area' );
				$tare_plural      = count( $tares ) > 1 ? 's' : '';
				$tare_pubs        = array();
				$post_meta_out   .= '';

				// Add date information.
				if ( ! empty( $post_date ) ) {

					$post_meta_out .= "<strong>Original Release Date</strong>: {$post_date}; ";

				}

				if ( ! empty( $revision_records ) ) {

					$revision_order = array();
					foreach ( $revision_records as $i => $row ) {
						$revision_order[ $i ] = $row['datetime'];
					}
					array_multisort( $revision_order, SORT_ASC, $revision_records );
					$last_revision = end( $revision_records );
					$last_rev_time = strtotime( $last_revision['datetime'] );

					if ( ! empty( $post_date ) ) {
						$post_meta_out .= ' <br>';
					}

					$post_meta_out .= '<strong>Last Revision Date</strong>: ';
					$post_meta_out .= date( 'F j, Y', $last_rev_time );

				}

				// Add original author information.
				if ( ! empty( $oauth_out ) ) {
					$post_meta_out .= $oauth_out;
				}

				// Add department information.
				if ( ! empty( $departments ) ) {

					foreach ( $departments as $department ) {

						$dept_pubs[] = sprintf(
							'<a href="%s?department=%s">%s</a>',
							get_post_type_archive_link( $this->publication_post_slug ),
							$department->slug,
							$department->name
						);

					}

					if ( ! empty( $oauth_out ) ) {
						$post_meta_out .= '; ';
					}

					$post_meta_out .= sprintf(
						'<strong>Department%s</strong>: %s',
						$dept_plural,
						implode( ', ', $dept_pubs )
					);

				}

				if ( ! empty( $oauth_out ) || ! empty( $departments ) ) {
					$post_meta_out .= ' <br>';
				}

				// Add topic category information.
				if ( ! empty( $tcategories ) ) {
					$post_meta_out .= $tcat_out;
				}

				// Add topic area information.
				if ( ! empty( $tares ) ) {

					foreach ( $tares as $tare ) {
						$tare_pubs[] = sprintf(
							'<a href="%s?topic-area=%s">%s</a>',
							get_post_type_archive_link( $this->publication_post_slug ),
							$tare->slug,
							$tare->name
						);
					}

					if ( ! empty( $tcategories ) ) {
						$post_meta_out .= ' <br>';
					}

					$post_meta_out .= sprintf(
						'<strong>Topic Area%s</strong>: %s',
						$tare_plural,
						implode( ', ', $tare_pubs )
					);

				}
			}

			if ( ! empty( $post_meta_out ) ) {
				// Theme-dependent output based on what hook is generating the content.
				$theme = wp_get_theme();
				if ( 'Genesis' === $theme->name || 'Genesis' === $theme->parent()->name ) {
					$title = $post_meta_out;
				} else {
					$title .= "<p class=\"entry-meta\">{$post_meta_out}</p>";
				}
			}
		}

		return $title;

	}

	/**
	 * Add variables to allowed public query vars.
	 *
	 * @param array $public_query_vars Publicly allowed query variables.
	 * @since 1.0.0
	 * @return array
	 */
	public static function add_query_vars( $public_query_vars ) {

		$public_query_vars[] = $this->author_post_slug;
		return $public_query_vars;

	}

	/**
	 * Add multi-select support to publication search taxonomy and pubauthor dropdowns.
	 *
	 * @since 1.0.0
	 * @param string $output      HTML output.
	 * @param array  $parsed_args Arguments used to build the drop-down.
	 * @return string
	 */
	public static function add_search_form_multi_select( $output, $parsed_args ) {

		if ( isset( $parsed_args['multiple'] ) && $parsed_args['multiple'] ) {

			$output = preg_replace( '/^<select/i', '<select multiple', $output );

			$output = str_replace( "name='{$parsed_args['name']}'", "name='{$parsed_args['name']}[]'", $output );

			$selected = $parsed_args['selected'];

			if ( is_array( $selected ) && ! empty( $selected ) ) {
				foreach ( $selected as $value ) {
					$output = str_replace( "value=\"{$value}\"", "value=\"{$value}\" selected", $output );
				}
			}
		}

		return $output;

	}

	/**
	 * Add multi-select support to kses attributes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function kses_multiple_select() {

		global $allowedposttags, $allowedtags;
		$newattribute = 'multiple';

		$allowedposttags['select'][ $newattribute ] = true; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
		$allowedtags['select'][ $newattribute ]     = true; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited

	}

	/**
	 * Add publication search form to page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function add_post_type_search_form() {

		if ( is_singular( $this->publication_post_slug ) || is_archive( $this->publication_post_slug ) ) {

			include WORDPRESS_PLUGIN_DIR_PATH . 'templates/search-publication.php';

		}

	}

}
