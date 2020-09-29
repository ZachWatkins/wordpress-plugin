<?php
/**
 * The file that creates a contact widget.
 *
 * @link       https://github.com/zachwatkins/wordpress-plugin/blob/master/src/class-widget.php
 * @since      1.0.0
 * @package    wordpress-plugin
 * @subpackage wordpress-plugin/src
 */

namespace WordPress_Plugin;

/**
 * Loads widgets.
 *
 * @since 1.0.0
 * @return void
 */
class Plugin_Name_Widget extends \WP_Widget {

	/**
	 * Default instance.
	 *
	 * @since 1.0.1
	 * @var array
	 */
	protected $default_instance = array(
		'title'   => '',
		'content' => '<div class="info icon-location">600 John Kimbrough Blvd, College Station, TX 77843</div><div><a class="info icon-phone" href="tel:979-845-4747">(979) 845-4747</a><span class="pipe"></span><a class="info icon-email" href="mailto:aglifesciences@tamu.edu">Contact Us</a></div>',
	);

	/**
	 * Construct the widget
	 *
	 * @since 1.0.1
	 * @return void
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'                   => 'plugin-name-contact',
			'description'                 => __( 'Contact information for this unit.' ),
			'customize_selective_refresh' => true,
		);

		$control_ops = array(
			'width'  => 400,
			'height' => 350,
		);

		parent::__construct( 'plugin_name_contact', __( 'WordPress Plugin - Contact Us' ), $widget_ops, $control_ops );

	}

	/**
	 * Echoes the widget content
	 *
	 * @since 1.0.1
	 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 * @return void
	 */
	public function widget( $args, $instance ) {

		$instance     = array_merge( $this->default_instance, $instance );
		$allowed_html = array(
			'div'  => array(
				'class' => array(),
			),
			'br'   => true,
			'span' => array(
				'class' => array(),
			),
			'a'    => array(
				'class' => array(),
				'href'  => array(),
			),
		);
		$before       = array_key_exists( 'before_widget', $args ) ? $args['before_widget'] : '';
		$after        = array_key_exists( 'after_widget', $args ) ? $args['after_widget'] : '';
		$title        = array_key_exists( 'title', $instance ) ? $instance['title'] : '';
		$content      = $instance['content'];

		$output = sprintf(
			'%s%s<div class="textwidget custom-html-widget">%s</div>%s',
			wp_kses_post( $before ),
			wp_kses_post( $title ),
			wp_kses( $instance['content'], $allowed_html ),
			wp_kses_post( $after )
		);

		echo $output;

	}

	/**
	 * Outputs the settings update form
	 *
	 * @since 1.0.1
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {

		$instance     = wp_parse_args( (array) $instance, $this->default_instance );
		$allowed_html = array(
			'p'        => array(),
			'label'    => array(
				'for' => array(),
			),
			'input'    => array(
				'type'  => array(),
				'id'    => array(),
				'name'  => array(),
				'class' => array(),
				'value' => array(),
			),
			'textarea' => array(
				'id'    => array(),
				'rows'  => array(),
				'name'  => array(),
				'class' => array(),
			),
		);
		$output       = '<p><label for="%s">%s</label><input type="text" id="%s" name="%s" class="title widefat" value="%s"/></p><p><textarea id="%s" rows="8" name="%s" class="content widefat">%s</textarea></p>';

		echo wp_kses(
			sprintf(
				$output,
				esc_attr( $this->get_field_id( 'title' ) ),
				esc_attr_e( 'Title:', 'wordpress-plugin' ),
				esc_attr( $this->get_field_id( 'title' ) ),
				$this->get_field_name( 'title' ),
				esc_attr( $instance['title'] ),
				$this->get_field_id( 'content' ),
				$this->get_field_name( 'content' ),
				esc_textarea( $instance['content'] )
			),
			$allowed_html
		);

	}

	/**
	 * Updates a particular instance of a widget
	 *
	 * @since 1.0.1
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance          = array_merge( $this->default_instance, $old_instance );
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['content'] = $new_instance['content'];
		} else {
			$instance['content'] = wp_kses_post( $new_instance['content'] );
		}
		return $instance;

	}
}
