<?php

class isjd_trigger {
	
	public $post_type;
	public $post_identifier;
	public $taxonomy;
	public $email_notification;
	
	public function create_rest_api() {
		
		// https://yourwebsite.com/wp-json/isjd-api/v1/hook
		register_rest_route( 'isjd-api/v1', '/hook', array(
				'callback' => array($this, 'isjd_rest_api_callback'),
				'permission_callback' => '__return_true'
			)
		);
		
		// https://yourwebsite.com/wp-json/isjd-api/v1/export
		register_rest_route( 'isjd-api/v1', '/export', array(
				'callback' => array($this, 'isjd_export'),
				'permission_callback' => '__return_true'
			)
		);
	}
	
	/**
	 * Rest API callback
	 */
	public function isjd_rest_api_callback() {
		header('Content-Type:text/html; charset=UTF-8');
		date_default_timezone_set('Europe/Belgrade');
		
		$data = file_get_contents(dirname(__DIR__) . '/data.json');
		$data = json_decode($data);
		
		$updated = 0;
		$added = 0;
		foreach ($data as $item) {
			if ( $this->if_post_exist($item->id) !== false ) {
				$this->add_or_update($this->if_post_exist($item->id)->ID, $item, 'update');
				$updated++;
			} else {
				$this->add_or_update($item->id, $item, 'add');
				$added++;
			}
		}
		
		echo 'Updated: ' . $updated . '<br>';
		echo 'New records: ' . $added;
		
		// Send email
		$headers = array();
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		//$headers[] = 'From: isjd Dev <'.$this->email_notification.'>';
		$headers[] = 'From: isjd Dev <isjddevtest@co-nic.com>';
		
		$to_admin = $this->email_notification;
		
		$subject = __('Information about imported data -'. date('d.m.y H:i:s'),'ldt');
		
		$message = <<<OUTP
		<h3>Information about imported records</h3>
		<p>Updated: $updated</p>
		<p>New records: $added</p>
OUTP;
		wp_mail( $to_admin, $subject, $message, $headers);
		
		exit;
	}
	
	public function isjd_export() {
		header('Content-disposition: attachment; filename=data.json');
		header('Content-type: application/json');
		
		$args = array(
			'post_type' => $this->post_type,
			'posts_per_page' => -1,
			'meta_key'  => 'adv_timestamp',
			'orderby' => 'adv_timestamp',
			'order' => 'ASC',
		);
		
		$the_query = new WP_Query( $args );
		
		$posts = $the_query->posts;
		
		$data = array();
		foreach ( $posts as $record ) {
			$title = get_post_meta($record->ID, 'adv_title', true);
			$about = get_post_meta($record->ID, 'adv_about',  true);
			$organizer = get_post_meta($record->ID, 'adv_organizer', true);
			$timestamp = get_post_meta($record->ID, 'adv_timestamp', true);
			$email = get_post_meta($record->ID, 'adv_email', true);
			$address = get_post_meta($record->ID, 'adv_address', true);
			$latitude = get_post_meta($record->ID, 'adv_latitude', true);
			$longitude = get_post_meta($record->ID, 'adv_longitude', true);
			
			$tag_data = array();
			$tags = get_the_terms($record->ID, $this->taxonomy);
			if ( !empty($tags) ) {
				foreach ( $tags as $tag ) {
					$tag_data[] = $tag->name;
				}
			}
			
			$data[] = array(
				'title' => $title,
				'about' => $about,
				'organizer' => $organizer,
				'timestamp' => $timestamp,
				'email' => $email,
				'address' => $address,
				'latitude' => $latitude,
				'longitude' => $longitude,
				'tags' => $tag_data
			);
		}
		
		echo json_encode($data);
		
		exit;
	}
	
	
	/**
	 * Check if post already exist
	 */
	public function if_post_exist($id) {
		
		$args = array(
			'post_type' => $this->post_type,
			'meta_query' => array(
				array(
					'key' => $this->post_identifier,
					'value' => $id
				)
			),
		);
		
		$the_query = new WP_Query( $args );
		
		if ( $the_query->found_posts > 0 ) {
			return $the_query->posts[0];
		} else {
			return false;
		}
	}
	
	/**
	 * Add new post or update if exist
	 */
	public function add_or_update($id, $data, $action) {
		if ( $action === 'add' ) {
			
			$post_id = wp_insert_post(array(
				'post_type' => $this->post_type,
				'post_title' => $data->title,
				'post_status' => 'publish'
			));
			
			update_post_meta($post_id, 'adv_id', $data->id );
			update_post_meta($post_id, 'adv_title', $data->title );
			update_post_meta($post_id, 'adv_about', $data->about );
			update_post_meta($post_id, 'adv_organizer', $data->organizer );
			update_post_meta($post_id, 'adv_timestamp', $data->timestamp );
			update_post_meta($post_id, 'adv_email', $data->email );
			update_post_meta($post_id, 'adv_address', $data->address );
			update_post_meta($post_id, 'adv_latitude', $data->latitude );
			update_post_meta($post_id, 'adv_longitude', $data->longitude );
			
			$tags = $data->tags;
			
			wp_set_post_terms($id, $tags, $this->taxonomy, false);
			
		} else if ( $action === 'update' ) {
				
				update_post_meta($id, 'adv_id', $data->id );
				update_post_meta($id, 'adv_title', $data->title );
				update_post_meta($id, 'post_title', $data->title );
				update_post_meta($id, 'adv_about', $data->about );
				update_post_meta($id, 'adv_organizer', $data->organizer );
				update_post_meta($id, 'adv_timestamp', $data->timestamp );
				update_post_meta($id, 'adv_email', $data->email );
				update_post_meta($id, 'adv_address', $data->address );
				update_post_meta($id, 'adv_latitude', $data->latitude );
				update_post_meta($id, 'adv_longitude', $data->longitude );
				
				$tags = $data->tags;
			
				wp_set_post_terms($id, $tags, $this->taxonomy, false);
		}
	}

}