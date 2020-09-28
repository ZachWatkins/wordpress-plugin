<?php
/**
 * The file that defines the Publication post type custom fields
 *
 * @link       https://github.com/zachwatkins/wordpress-plugin/blob/master/fields/publication-fields.php
 * @since      1.0.0
 * @package    wordpress-plugin
 * @subpackage wordpress-plugin/fields
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_5eab2b7199afb',
			'title'                 => 'Publication Details',
			'fields'                => array(
				array(
					'key'               => 'field_5eb596e9f5eff',
					'label'             => 'Original Author(s)',
					'name'              => 'pubauthors',
					'type'              => 'post_object',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'post_type'         => array(
						0 => 'pubauthor',
					),
					'taxonomy'          => '',
					'allow_null'        => 0,
					'multiple'          => 1,
					'return_format'     => 'id',
					'ui'                => 1,
				),
				array(
					'key'               => 'field_5eb590549cef5',
					'label'             => 'Revision Records',
					'name'              => 'revision_recs',
					'type'              => 'repeater',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => '',
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'block',
					'button_label'      => 'New Revision',
					'sub_fields'        => array(
						array(
							'key'               => 'field_5eb591d59cef7',
							'label'             => 'Author(s)',
							'name'              => 'authors',
							'type'              => 'post_object',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'post_type'         => array(
								0 => 'pubauthor',
							),
							'taxonomy'          => '',
							'allow_null'        => 0,
							'multiple'          => 0,
							'return_format'     => 'id',
							'ui'                => 1,
						),
						array(
							'key'               => 'field_5eb5924b9cef8',
							'label'             => 'Date',
							'name'              => 'datetime',
							'type'              => 'date_time_picker',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'display_format'    => 'F j, Y g:i a',
							'return_format'     => 'Y-m-d H:i:s',
							'first_day'         => 1,
						),
					),
				),
				array(
					'key'               => 'field_5eb5c7a48910b',
					'label'             => 'Review Records',
					'name'              => 'review_records',
					'type'              => 'repeater',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => '',
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'block',
					'button_label'      => 'Add Reviewer',
					'sub_fields'        => array(
						array(
							'key'               => 'field_5eb5c7af8910c',
							'label'             => 'Reviewer',
							'name'              => 'reviewer',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
						array(
							'key'               => 'field_5eb5c7be8910d',
							'label'             => 'Date',
							'name'              => 'datetime',
							'type'              => 'date_time_picker',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'display_format'    => 'F j, Y g:i a',
							'return_format'     => 'Y-m-d H:i:s',
							'first_day'         => 0,
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'publication',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'side',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		)
	);

endif;
