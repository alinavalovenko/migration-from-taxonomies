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
			$term = get_term( $this->term_id );
			if ( ! strpos( $term->slug, '-old' ) ) {
				$page_id = $this->fd_create_new_page();
				$meta    = $this->fd_get_term_meta();
				$this->update_page_meta( $page_id, $meta );

				return 'Complete';
			}

			return 'Migration was done for this page';
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
			$term = get_term_by( 'id', $this->term_id, $this->taxonomy );

			$page      = get_page_by_path( $term->slug );
			$post_data = array(
				'post_title'    => $term->name,
				'post_name'     => $term->slug,
				'post_content'  => $term->description,
				'post_status'   => 'publish',
				'post_type'     => 'page',
				'post_author'   => $current_user->ID,
				'page_template' => $this->define_page_template( $term ),
				'post_category' => $this->term_id,
				'post_parent'   => $this->get_parrent_page_id( $term->parent )
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
			wp_update_term( $this->term_id, 'category', array(
				'slug' => $term->slug . '-old'
			) );

			return $page_id;
		}

		function update_page_meta( $page_id, $meta ) {
			foreach ( $meta as $id => $metadata ) {
				update_post_meta( $page_id, $metadata['meta_key'], $metadata['meta_value'] );
			}
			update_post_meta( $page_id, 'fd-category', $this->term_id );
		}

		function define_page_template( $term ) {
			if ( ! empty( $term->parrent ) ) {
				return 'page-fd-sub-category.php';
			}

			return 'page-fd-category.php';
		}

		function get_parrent_page_id( $parent_term_id ) {
			if($parent_term_id) {
				$parent_term = get_term( $parent_term_id );
				$slug        = $parent_term->slug;
				$pos         = strpos( $parent_term->slug, '-old' );
				if ( $pos ) {
					$slug = substr( $parent_term->slug, 0, $pos );
				}
				$page = get_page_by_path( $slug, OBJECT, 'page' );
				if ( is_wp_error( $page ) ) {
					die( "Parent Page doesn't exist, please create a page for " . $parent_term->name );
				}

				return $page->ID;
			} else
				return null;
		}
	}
}