<?php

include 'WordpressSettings.php';

class WordPressMetabox extends WordPressSettings {
	
	/**
	 * Metabox Title
	 */
	protected $title = '';
	
	/**
	 * Metabox ID
	 */
	protected $slug = '';
	
	/**
	 * Array of post types for which we allow the metabox
	 */
	protected $post_types = array();
	
	/**
	 * Post ID used to save or retrieve the settings
	 */
	protected $post_id = 0;
	
	/**
	 * Metabox context
	 */
	protected $context = '';
	
	/**
	 * Metabox priority
	 */
	protected $priority = '';
	
	// ...
	
	public function __construct( $title, $slug, $post_types = array( 'post' ), $context = 'advanced', $priority = 'high' ) {
		
		if( $slug == '' || $context == '' || $priority == '' )  {
			return;
		}
		
		if( $title == '' ) {
			$this->title = ucfirst( $slug );
		}
		
		if( empty( $post_types ) ) {
			return;
		}
		
		$this->title = $title;
		$this->slug = $slug;
		$this->post_types = $post_types;
		$this->settings_id = $this->slug;
		$this->context = $context;
		$this->priority = $priority;
		
		add_action( 'add_meta_boxes', array( $this, 'register' ) );
		add_action( 'save_post', array( $this, 'save_meta_settings' ) );
	}
	
	public function register( $post_type ) {
		if ( in_array( $post_type, $this->post_types ) ) {
			add_meta_box( $this->slug, $this->title, array( $this, 'render' ), $post_type );
		}
	}
	
	public function render( $post ) {
		$this->post_id = $post->ID;
		wp_nonce_field( 'metabox_' . $this->slug, 'metabox_' . $this->slug . '_nonce' );
		echo '<table class="form-table">';
		$this->render_fields( 'general' );
		echo '</table>';
	}
	
	public function init_settings() {
		
		$post_id = $this->post_id;
		$this->settings = get_post_meta( $post_id, $this->settings_id, true );
		
		foreach ( $this->fields as $tab_key => $tab ) {
			
			foreach ( $tab as $name => $field ) {
				
				if( isset( $this->settings[ $name ] ) ) {
					$this->fields[ $tab_key ][ $name ]['default'] = $this->settings[ $name ];
				}
				
			}
		}
	}
	
	public function save_meta_settings( $post_id ) {
		
		// Check if our nonce is set.
		if ( ! isset( $_POST['metabox_' . $this->slug . '_nonce'] ) ) {
			return $post_id;
		}
		
		$nonce = $_POST['metabox_' . $this->slug . '_nonce'];
		
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'metabox_' . $this->slug ) ) {
			return $post_id;
		}
		
		/*
		* If this is an autosave, our form has not been submitted,
		* so we don't want to do anything.
		*/
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		
		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}
		
		
		
		$this->post_id = $post_id;
		$this->save_settings();
	}
	
	
	/**
	 * Save settings from POST
	 * @return [type] [description]
	 */
	public function save_settings(){
		$this->posted_data = $_POST;
		
		// extract all fields to simple array
		$all_custom_fields = array();
		foreach ( $this->fields['general'] as $name ) {
			$all_custom_fields[] = $name['name'];
		}
		
		foreach ($this->posted_data as $key => $value) {
			
			if ( in_array( $key, $all_custom_fields ) ) {
				update_post_meta( $this->post_id, $key, $value );
			}
			
		}
	}
	
	
	
}