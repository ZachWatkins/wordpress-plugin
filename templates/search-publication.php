<?php
/**
* Template Name: Search Publication
*
* @package WordPress_Plugin
* @subpackage wordpress-plugin/templates
* @since 1.0.0
*/

add_filter( 'get_search_form', function( $form, $args ){

	if ( 'publication' === $args['aria_label'] ) {

		$taxonomies      = get_object_taxonomies( 'publication', 'objects' );
		$search_filters  = array(
			'post-type' => "<input type=\"hidden\" value=\"publication\" name=\"post_type\" id=\"post_type\" />",
		);
		$author_dropdown = wp_dropdown_pages(
			array(
				'echo'        => 0,
				'post_type'   => 'pubauthor',
				'name'        => 'pubauthor',
				'value_field' => 'post_name',
				'class'       => 'postform',
				'multiple'    => true,
				'selected'    => esc_attr( get_query_var( 'pubauthor', null ) ),
			)
		);

		// Author filter.
		if ( ! empty( $author_dropdown ) ) {

			$search_filters['authors']  = '<div class="filter">';
			$search_filters['authors'] .= '<label class="author-label">Authors</label>';
			$search_filters['authors'] .= $author_dropdown;
			$search_filters['authors'] .= '</div>';

		}

		// Taxonomy filters.
		foreach ( $taxonomies as $key => $taxonomy ) {

			$terms = get_terms( array( 'taxonomy' => $key ) );

			if ( is_array( $terms ) && 0 < count( $terms ) ) {

				$search_filters[ $key ]  = '<div class="filter">';
				$search_filters[ $key ] .= "<label for=\"{$taxonomy->name}\" class=\"taxonomy-label\">{$taxonomy->label}</label>";
				$search_filters[ $key ] .= wp_dropdown_categories(
					array(
						'echo'        => 0,
						'taxonomy'    => $key,
						'name'        => $key,
						'value_field' => 'slug',
						'orderby'     => 'name',
						'multiple'    => true,
						'selected'    => get_query_var( $key, null ),
					)
				);
				$search_filters[ $key ] .= '</div>';

			}

		}

		$content = implode( '', $search_filters );
		preg_match( '/^<form[^>]*>(.*)<\/form>$/', $form, $matches );
		// Add search filters.
		$form = str_replace( $matches[1], $content . $matches[1], $form );
		// Update search button text.
		$form = str_replace( 'value="Search"', 'value="Search Publications"', $form );

	}

	return $form;

}, 11, 2);

?>
<h3>Search Publications</h3>
<div class="epub-search-form">
	<?php
		get_search_form(
			array(
				'aria_label' => 'publication'
			)
		);
	?>
	<!-- <form class="search-form" method="get" action="<?php ?>https://epubs.agrilifelearn.tamu.edu/" role="search" itemprop="potentialAction" itemscope="" itemtype="https://schema.org/SearchAction"> -->
		<!-- <meta content="https://epubs.agrilifelearn.tamu.edu/?s={s}" itemprop="target"> -->
	<!-- </form> -->
	<hr>
</div>
