<?php
/*
Plugin Name:       Monster Widget
Description:       A widget that allows for quick and easy testing of multiple widgets. Not intended for production use.
Version:           0.2
Author:            Automattic
Author URI:        http://automattic.com/
License:           GPLv2 or later
*/

/**
 * Register the Monster Widget.
 *
 * Hooks into the widgets_init action.
 *
 * @since 0.1
 */
function register_monster_widget() {
	register_widget( 'Monster_Widget' );
}
add_action( 'widgets_init', 'register_monster_widget' );

/**
 * Monster Widget.
 *
 * A widget that allows for quick and easy testing of multiple widgets.
 *
 * @todo Figure out a way to automatically register a nav menu widget.
 * @todo Find a substitute image to use in breaker text.
 *
 * @since 0.1
 */
class Monster_Widget extends WP_Widget {

	/**
	 * Iterator (int).
	 *
	 * Used to set a unique html id attribute for each
	 * widget instance generated by Monster_Widget::widget().
	 *
	 * @since 0.1
	 */
	static $iterator = 1;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {
		parent::__construct( 'Monster', __( 'Monster', 'monster-widget' ), array(
			'classname'   => 'monster',
			'description' => __( 'Test multiple widgets at the same time.', 'monster-widget' )
		) );
	}

	/**
	 * Print the Monster widget on the front-end.
	 *
	 * @uses $wp_registered_sidebars
	 * @uses Monster_Widget::$iterator
	 * @uses Monster_Widget::get_widget_class()
	 * @uses $this->get_widget_config()
	 *
	 * @since 0.1
	 */
	public function widget( $args, $instance ) {
		global $wp_registered_sidebars;

		$id = $args['id'];
		$args = $wp_registered_sidebars[$id];
		$before_widget = $args['before_widget'];

		foreach( $this->get_widget_config() as $widget ) {
			$_instance = ( isset( $widget[1] ) ) ? $widget[1] : null;

			// Override cache for the Recent Posts widget.
			if ( 'WP_Widget_Recent_Posts' == $widget[0] )
				$args['widget_id'] = 'monster-widget-recent-posts-cache-' . self::$iterator;

			$args['before_widget'] = sprintf(
				$before_widget,
				'monster-widget-placeholder-' . self::$iterator,
				$this->get_widget_class( $widget[0] )
			);

			the_widget( $widget[0], $_instance, $args );

			self::$iterator++;
		}
    }

	/**
	 * Widgets (array).
	 *
	 * Numerically indexed array of Pre-configured widgets to
	 * display in every instance of a Monster widget. Each entry
	 * requires two values:
	 *
	 * 0 - The name of the widget's class as registered with register_widget().
	 * 1 - An associative array representing an instance of the widget.
	 *
	 * @uses Monster_Widget::get_text()
	 * @uses Monster_Widget::get_nav_menu()
	 *
	 * This list can be altered by using the `monster-widget-config` filter.
	 *
	 * @return array Widget configuration.
	 * @since 0.1
	 */
	public function get_widget_config() {
		$widgets = array(
			array( 'WP_Widget_Archives', array(
				'title'    => __( 'Archives List', 'monster-widget' ),
				'count'    => 1,
				'dropdown' => 0,
			) ),
			array( 'WP_Widget_Archives', array(
				'title'    => __( 'Archives Dropdown', 'monster-widget' ),
				'count'    => 1,
				'dropdown' => 1,
			) ),
			array( 'WP_Widget_Calendar', array(
				'title' => __( 'Calendar', 'monster-widget' ),
			) ),
			array( 'WP_Widget_Categories', array(
				'title'        => __( 'Categories List', 'monster-widget' ),
				'count'        => 1,
				'hierarchical' => 1,
				'dropdown'     => 0,
			) ),
			array( 'WP_Widget_Categories', array(
				'title'        => __( 'Categories Dropdown', 'monster-widget' ),
				'count'        => 1,
				'hierarchical' => 1,
				'dropdown'     => 1,
			) ),
			array( 'WP_Widget_Pages', array(
				'title'   => __( 'Pages', 'monster-widget' ),
				'sortby'  => 'menu_order',
				'exclude' => '',
			) ),
			array( 'WP_Widget_Meta', array(
				'title'   => __( 'Meta', 'monster-widget' ),
			) ),
			array( 'WP_Widget_Recent_Comments', array(
				'title'  => __( 'Recent Comments', 'monster-widget' ),
				'number' => 7,
			) ),
			array( 'WP_Widget_Recent_Posts', array(
				'title'  => __( 'Recent Posts', 'monster-widget' ),
				'number' => 1,
			) ),
			array( 'WP_Widget_RSS', array(
				'title'        => __( 'RSS', 'monster-widget' ),
				'url'          => 'http://themeshaper.com/feed',
				'items'        => 10,
				'show_author'  => true,
				'show_date'    => true,
				'show_summary' => true,
			) ),
			array( 'WP_Widget_Search', array(
				'title'   => __( 'Search', 'monster-widget' ),
			) ),
			array( 'WP_Widget_Text', array(
				'title'  => __( 'Text', 'monster-widget' ),
				'text'   => $this->get_text(),
				'filter' => true,
			) ),
			array( 'WP_Widget_Tag_Cloud', array(
				'title'    => __( 'Tag Cloud', 'monster-widget' ),
				'taxonomy' => 'post_tag',
			) ),
		);

		if ( $menu = $this->get_nav_menu() ) {
			$widgets[] = array( 'WP_Nav_Menu_Widget', array(
				'title'    => __( 'Nav Menu', 'monster-widget' ),
				'nav_menu' => $menu,
			) );
		}

		global $wp_widget_factory;
		$available_widgets = array_keys( $wp_widget_factory->widgets );
		if ( in_array( 'WP_Widget_Links', $available_widgets ) ) {
			$widgets[] = array( 'WP_Widget_Links', array(
				'title'       => __( 'Links', 'monster-widget' ),
				'description' => 1,
				'name'        => 1,
				'rating'      => 1,
				'images'      => 1,
			) );
		}

		return apply_filters( 'monster-widget-config', $widgets );
	}

	/**
	 * Get the html class attribute value for a given widget.
	 *
	 * @uses $wp_widget_factory
	 *
	 * @param string $widget The name of a registered widget class.
	 * @return string Dynamic class name a given widget.
	 *
	 * @since 0.1
	 */
	public function get_widget_class( $widget ) {
		global $wp_widget_factory;

		$widget_obj = '';
		if ( isset( $wp_widget_factory->widgets[$widget] ) )
			$widget_obj = $wp_widget_factory->widgets[$widget];

		if ( ! is_a( $widget_obj, 'WP_Widget') )
			return '';

		if ( ! isset( $widget_obj->widget_options['classname'] ) )
			return '';

		return $widget_obj->widget_options['classname'];
	}

	/**
	 * Get the nav menu with the most links.
	 *
	 * @return mixed Term object on success; False otherwise.
	 * @since 0.1
	 */
	public static function get_nav_menu() {
		$menus = wp_get_nav_menus();

		if ( is_wp_error( $menus ) || empty( $menus ) )
			return false;

		$counts = wp_list_pluck( $menus, 'count' );
		$menus = array_combine( $counts, $menus );
		ksort( $menus );
		$menus = array_reverse( $menus );
		$menus = array_values( $menus );
		$menu = array_shift( $menus );

		if ( empty( $menu->count ) )
			return false;

		return $menu;
	}

	/**
	 * Widget Breaker Text.
	 *
	 * Used to populate the Text widget with html designed
	 * to "break" out of the sidebar.
	 *
	 * The "monster-widget-get-text" filter can be used
	 * to modify the output.
	 *
	 * @since 0.1
	 */
	public function get_text() {
		$html = array();

		$html[] = '<strong>' . __( 'Large image: Hand Coded', 'monster-widget' ) . '</strong>';
		$html[] = '<img src="http://wpthemetestdata.files.wordpress.com/2008/09/test-image-landscape-900.jpg">';

		$html[] = '<strong>' . __( 'Large image: linked in a caption', 'monster-widget' ) . '</strong>';
		$html[] = '<div class="wp-caption alignnone"><a href="#"><img src="http://wpthemetestdata.files.wordpress.com/2008/09/test-image-landscape-900.jpg" class="size-large" height="598" width="900"></a><p class="wp-caption-text">' . __( 'This image is 900 by 598 pixels.', 'monster-widget' ) . '</p></div>';

		$html[] = '<strong>' . __( 'Meat!', 'monster-widget' ) . '</strong>';
		$html[] = __( 'Hamburger fatback andouille, ball tip bacon t-bone turkey tenderloin. Ball tip shank pig, t-bone turducken prosciutto ground round rump bacon pork chop short loin turkey. Pancetta ball tip salami, hamburger t-bone capicola turkey ham hock pork belly tri-tip. Biltong bresaola tail, shoulder sausage turkey cow pork chop fatback. Turkey pork pig bacon short loin meatloaf, chicken ham hock flank andouille tenderloin shank rump filet mignon. Shoulder frankfurter shankle pancetta. Jowl andouille short ribs swine venison, pork loin pork chop meatball jerky filet mignon shoulder tenderloin chicken pork.', 'monster-widget' );

		$html[] = '<strong>' . __( 'Pipe Test', 'monster-widget' ) . '</strong>';
		$html[] = '||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||';

		$html[] = '<strong>' . __( 'Smile!', 'monster-widget' ) . '</strong>';
		$html[] = convert_smilies( ';)' ) . ' ' . convert_smilies( ':)' ) . ' ' . convert_smilies( ':-D' );

		$html = implode( "\n", $html );

		return apply_filters( 'monster-widget-get-text', $html );
    }
}