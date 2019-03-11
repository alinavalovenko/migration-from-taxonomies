<?php
if ( ! class_exists( 'Single_Migration' ) ) {

	class Single_Migration {
		private $term_id;
		private $taxonomy;
		public $status;

		function __construct( $term_id ) {
			$this->term_id  = $term_id;
			$this->taxonomy = 'category';
			$this->status   = $this->get_status();
		}

		function get_status() {
			$page   = $this->fd_create_new_page();
			$meta   = $this->fd_get_term_meta();
			$this->update_page_meta( $page, $meta );

			return 'Complete';
		}

		function fd_get_term_meta( $term_id = null ) {
			global $wpdb;
			if ( ! $term_id ) {
				$term_id = $this->term_id;
			}
			$sql       = $wpdb->prepare( "SELECT * FROM $wpdb->termmeta WHERE term_id = %s", $term_id );
			$meta_data = $wpdb->get_results( $sql, ARRAY_A );

			return $meta_data;
		}

		function fd_create_new_page() {
			global $current_user;
			$term      = get_term_by( 'id', $this->term_id, $this->taxonomy );
			$page      = get_page_by_path( $term->slug );
			$post_data = array(
				'post_title'    => $term->name,
				'post_name'     => $term->slug,
				'post_content'  => $term->description,
				'post_status'   => 'publish',
				'post_type'     => 'page',
				'post_author'   => $current_user->ID,
				'page_template' => 'page-fd-category.php'
			);
			if ( $page ) {
				$post_data['ID'] = $page->ID;
				wp_update_post( $post_data );
			} else {
				$page_id = wp_insert_post( $post_data );
			}

			if ( is_wp_error( $page_id ) ) {
				die( 'Unable to insert new page' );
			}

			return $page_id;
		}

		function update_page_meta( $page, $meta ) {
			foreach ( $meta as $id => $metadata ) {
				update_post_meta( $page, $metadata['meta_key'], $metadata['meta_value'] );
			}
		}
	}
}