<?php
/**
 * Plugin Name: BW Post Grid
 * Description: Elementor extension to display posts in grid layout.
 * Plugin URI:  http://bluwebz.com/
 * Version:     0.0.2
 * Author:      Bluwebzwp
 * Author URI:  
 * Text Domain: bwpg
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require( __DIR__ . '/bw-post-grid-shortcode.php' );   //loading the main plugin

define( 'BW_POST_GRID_ELEMENTS_FILE_', __FILE__ );


add_action( 'elementor/frontend/after_register_scripts', function() {		

			wp_enqueue_style( 'bw-post-grid-bootstrap', plugins_url ( '/css/bootstrap.css', BW_POST_GRID_ELEMENTS_FILE_ ),false,'3.3.7','all');
			wp_enqueue_style( 'bw-post-grid-style', plugins_url ( '/css/style.css', BW_POST_GRID_ELEMENTS_FILE_ ),false,'3.3.12','all');
			
			} );

final class Elementor_BW_Post_Grid_Extension {

	private static $_instance = null;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	public function init() {

		// Add Plugin actions
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		
	}
	
	public function widget_styles() {

		wp_register_style( 'widget-1', plugins_url( '/css/bootstrap.css', __FILE__ ) );
		wp_enqueue_style( 'widget-1', plugins_url ( '/css/bootstrap.css', BW_POST_GRID_ELEMENTS_FILE_ ),false,'3.3.7','all');

	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

		// Include Widget files
		require_once( __DIR__ . '/widget.php' );

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_BW_Post_Grid_Widget() );

	}


}

Elementor_BW_Post_Grid_Extension::instance()->init();



function bw_grid_post_type(){
	$args= array(
			'public'	=> 'true',
			'_builtin'	=> false
		);
	$post_types = get_post_types( $args, 'names', 'and' );
	$post_types = array( 'post'	=> 'post' ) + $post_types;
	return $post_types;
}


function bw_post_orderby_options(){
    $orderby = array(
        'ID' => 'Post Id',
        'author' => 'Post Author',
        'title' => 'Title',
        'date' => 'Date',
        'modified' => 'Last Modified Date',
        'parent' => 'Parent Id',
        'rand' => 'Random',
        'comment_count' => 'Comment Count',
        'menu_order' => 'Menu Order',
    );

    return $orderby;
}