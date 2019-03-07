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
			add_action( 'admin_menu', array( $this, 'fd_admin_menu' ) );
			add_action( 'init', array( $this, 'fd_change_cat_object' ) );
//			add_action( '', array( $this, '' ) );
//			add_action( '', array( $this, '' ) );
		}

		public function fd_admin_menu() {
			add_menu_page( 'FD Migration', 'FD Migration', 'publish_pages', 'fd-migration', array(
				$this,
				'fd_run_migration'
			), plugin_dir_url( __FILE__ ) . 'assets/images/folder.png', 2 );

		}

		function fd_run_migration() {
			require_once 'view/dashboard.php';
		}

		function fd_change_cat_object(){
			global $wp_taxonomies;
			$category = &$wp_taxonomies['category'];
			$category->rewrite = false;
			$category->public = false;
			$category->publicly_queryable = false;

		}
	}
}
new FD_Migration();