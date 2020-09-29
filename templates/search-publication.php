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

			if ( is_array( $terms ) && ! empty( $terms ) ) {

				$dropdown = wp_dropdown_categories(
					array(
						'echo'        => 0,
						'id'          => "taxonomy-{$taxonomy->name}",
						'taxonomy'    => $key,
						'name'        => $key,
						'value_field' => 'slug',
						'orderby'     => 'name',
						'multiple'    => true,
						'selected'    => get_query_var( $key, null ),
					)
				);

				$search_filters[ $key ]  = sprintf(
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

}, 11, 2);

?>
<h3>Search Publications</h3>
<div class="epub-search-form"><?php get_search_form( array( 'aria_label' => 'publication' ) ); ?><hr></div>
