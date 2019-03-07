<?php
/**
 * Plugin Name: Migration from Taxonomies
 * Plugin URI:  https://github.com/alinavalovenko/event-calendar
 * Description: Create pages base on the taxonomies
 * Version:     1.0.0
 * Author:      Alina Valovenko
 * Author URI:  http://www.valovenko.pro
 * Text Domain: fd
 * Domain Path: /languages
 * License:     GPL2
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'FD_Migration' ) ) {
	class FD_Migration {
		public function __construct() {

			if( function_exists('acf_add_options_page') ) {
				acf_add_options_page(array(
					'page_title' 	=> 'FD Migration',
					'menu_title' 	=> 'FD Migration',
					'menu_slug' 	=> 'fd-migration',
					'capability' 	=> 'edit_posts',
					'redirect' 	=> false
				));
			}
//			add_action( '', array( $this, '' ) );
//			add_action( '', array( $this, '' ) );
//			add_action( '', array( $this, '' ) );
		}


	}
}
new FD_Migration();