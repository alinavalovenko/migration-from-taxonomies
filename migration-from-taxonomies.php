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
	require_once 'include/class-single-migration.php';
	require_once 'include/acf-fields.php';
	class FD_Migration {
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'fd_admin_menu' ) );
			add_action( 'init', array( $this, 'fd_change_cat_object' ) );
			add_action( 'wp_ajax_fd_single_migrate', array( $this, 'fd_single_migrate_callback' ) );
//			add_action( '', array( $this, '' ) );
		}

		public function fd_admin_menu() {
			add_menu_page( 'FD Migration', 'FD Migration', 'publish_pages', 'fd-migration', array(
				$this,
				'fd_run_migration'
			), plugin_dir_url( __FILE__ ) . 'assets/images/folder.png', 2 );

		}

		function scripts_enqueue(){
			wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.3.1.min.js', '', '1.0.0', true );
			wp_enqueue_script( 'fd-scripts', plugin_dir_url(__FILE__) . 'assets/scripts.js', array( 'jquery' ), '1.0.0', true );
		}

		function fd_run_migration() {
			$this->scripts_enqueue();
			$terms = $this->fd_get_all_terms();
			require_once 'view/dashboard.php';
		}

		function fd_change_cat_object() {
			global $wp_taxonomies;
			$category                     = &$wp_taxonomies['category'];
			$category->rewrite            = false;
			$category->public             = false;
			$category->publicly_queryable = false;

		}

		function fd_get_all_terms($taxonomy = 'category') {
			$terms    = get_terms( $taxonomy, array( 'hide_empty' => false ) );
			return $terms;
		}

		function fd_single_migrate_callback(){
			$term_id = $_POST['term_id'];
			$response = new Single_Migration($term_id);
			echo $response->status;
			wp_die();
		}
	}
}
new FD_Migration();