<?php
/**
 * Template Name: Search Publication
 *
 * @package WordPress_Plugin
 * @subpackage wordpress-plugin/templates
 * @since 1.0.0
 */

/**
 * If selected taxonomies are an array, add selected property elements.
 *
 * @since 1.0.0
 *
 * @param string $output      HTML output.
 * @param array  $parsed_args Arguments used to build the drop-down.
 */
function wpp_multi_select_atts( $output, $parsed_args ) {

	$taxonomy_slug = $parsed_args['name'];
	$selected      = get_query_var( $taxonomy_slug, null );
	if ( is_array( $selected ) ) {
		foreach ( $selected as $taxonomy ) {
			$output = str_replace( "value=\"$taxonomy\"", "selected=\"selected\" value=\"$taxonomy\"", $output );
		}
	}
	return $output;

}
add_filter( 'wp_dropdown_cats', 'wpp_multi_select_atts', 11, 2 );

/**
 * Filters the HTML output of the search form.
 *
 * @since 1.0.0
 *
 * @param string $form The search form HTML output.
 * @param array  $args The array of arguments for building the search form.
 */
function wpp_add_search_filters( $form, $args ) {

	if ( 'publication' === $args['aria_label'] ) {

		$taxonomies      = get_object_taxonomies( 'publication', 'objects' );
		$search_filters  = array(
			'post-type' => '<input type="hidden" value="publication" name="post_type" id="post_type" />',
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

			if ( is_array( $terms ) && ! empty( $terms ) ) {

				// Show dropdown with values selected if present in URL parameter.
				$args = array(
					'echo'        => 0,
					'id'          => "taxonomy-{$taxonomy->name}",
					'taxonomy'    => $key,
					'name'        => $key,
					'value_field' => 'slug',
					'orderby'     => 'name',
					'multiple'    => true,
				);

				// If the taxonomy URL parameter value is not an array then we can set it here.
				// Otherwise, we must set it with a filter function.
				$selected_query_tax = get_query_var( $key, null );
				if ( ! is_array( $selected_query_tax ) ) {
					$args['selected'] = $selected_query_tax;
				}
				$dropdown = wp_dropdown_categories( $args );

				$search_filters[ $key ] = sprintf(
					'<div class="filter"><label for="taxonomy-%s" class="taxonomy-label">%s</label>%s</div>',
					$taxonomy->name,
					$taxonomy->label,
					$dropdown
				);

			}
		}

		$taxonomy_filters = implode( '', $search_filters );
		preg_match( '/^<form[^>]*>(.*)<\/form>$/', $form, $matches );
		// Add search filters.
		$form = str_replace( $matches[1], $taxonomy_filters . $matches[1], $form );
		// Update search button text.
		$form = str_replace( 'value="Search"', 'value="Search Publications"', $form );

	}

	return $form;

}
add_filter( 'get_search_form', 'wpp_add_search_filters', 11, 2 );

?>
<h3>Search Publications</h3>
<div class="epub-search-form"><?php get_search_form( array( 'aria_label' => 'publication' ) ); ?><hr></div>
