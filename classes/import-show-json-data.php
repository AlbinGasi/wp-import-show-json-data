<?php

/**
 * Class import_show_json_data
 */
class import_show_json_data {
	
	public function init() {
		// Call admin menu
		add_action( 'admin_menu', array($this, 'admin_menu'), 99 );
		
		// isjd dev options
		add_action( 'init', array($this, 'isjd_register_options') );
		
		// Custom post type and taxonomies
    if ( get_option('isjd_enable') === 'yes' ) {}
		add_action('init', array($this, 'isjd_cp_data'), 10 );
		add_action('init', array($this, 'custom_meta_box'), 20 );
    
		// Create REST API & prepare isjd_trigger class for use
		include 'isjd-trigger.php';
		$isjd_trigger = new isjd_trigger();
		$isjd_trigger->post_type = 'adventure'; // set post type we are going to use
		$isjd_trigger->taxonomy = 'adventure-tags'; // set taxonomy
		$isjd_trigger->post_identifier = 'adv_id'; // set identifier for recognition
		$isjd_trigger->email_notification = 'albin.g@live.com';
		
		add_action( 'rest_api_init', array($isjd_trigger, 'create_rest_api') );
		
		// Create a shortcode to display posts
		add_shortcode('show_adventure', array($this, 'show_adventure'));
		
		
	}
	
	/**
	 * Admin menu
	 */
	public function admin_menu() {
		add_submenu_page( 'options-general.php', __('JSON Test data'), __('JSON Test data'), 'manage_options', 'isjd-dev-test', array($this, 'isjd_show_options') );
	}
	
	/**
	 * isjd Dev Test register options
	 */
	public function isjd_register_options() {
		register_setting( 'isjd-dev-test', 'isjd_enable' );
	}
	
	public function isjd_show_options () {
		wp_enqueue_media();
		
		include dirname(__DIR__) . '/views/admin/isjd-options.php';
  }
  
  /**
   * isjd custom post type and taxonomies data
   */
  public function isjd_cp_data() {
    
    // Custom post type
	  register_post_type( 'adventure',
		  array(
			  'labels' => array(
				  'name' => 'Adventure',
				  'singular_name' => 'Adventure',
			  ),
			
			  'public' => TRUE,
			  'publicly_queryable' => TRUE,
			  'menu_position' => 10,
			  'supports' => array('title','thumbnail','revisions','page-attributes','editor'),
			  'rewrite' => array('slug' => 'adventure'),
			  'taxonomies' => array('adventure_tags'),
			  'menu_icon' => 'dashicons-admin-post',
			  'has_archive' => TRUE
		  )
	  );
	  
	  // custom taxonomies
	  $labels = array(
		  'name'              => _x('Tags', 'Tags', 'ldt'),
		  'singular_name'     => _x('Tags', 'Tags', 'ldt'),
		  'parent_item'       => NULL,
		  'parent_item_colon' => NULL,
		  'menu_name'         => __('Tags', 'ldt'),
	  );
	
	  $args = array(
		  'hierarchical'      => FALSE,
		  'labels'            => $labels,
		  'show_ui'           => TRUE,
		  'show_admin_column' => TRUE,
		  'show_in_quick_edit' => FALSE,
		  //'meta_box_cb' => TRUE,
		  'query_var'         => TRUE,
		  'rewrite'           => array('slug' => 'adventure-tags'),
		  'public' => TRUE,
	  );
	
	  register_taxonomy('adventure-tags', array('adventure'), $args );
   
  }
	
	/**
	 * Create a custom meta boxes for Adventure post type
	 */
  public function custom_meta_box() {
    include 'WordPressMetabox.php';
	  $metabox = new WordPressMetabox( 'Extra Settings', 'extra_settings', array( 'adventure' ), 'advanced', 'high' );
	
	  $metabox->add_field(
		  array(
			  'type' => 'text',
			  'name' => 'adv_id',
			  'title' => 'ID',
      )
    );
	  $metabox->add_field(
		  array(
			  'type' => 'text',
			  'name' => 'adv_title',
			  'title' => 'Advanture title',
		  )
	  );
	  $metabox->add_field(
		  array(
			  'type' => 'textarea',
			  'name' => 'adv_about',
			  'title' => 'Advanture about',
		  )
	  );
	  $metabox->add_field(
		  array(
			  'type' => 'text',
			  'name' => 'adv_organizer',
			  'title' => 'Advanture organizer',
		  )
	  );
	  $metabox->add_field(
		  array(
			  'type' => 'text',
			  'name' => 'adv_timestamp',
			  'title' => 'Advanture time',
		  )
	  );
	  $metabox->add_field(
		  array(
			  'type' => 'text',
			  'name' => 'adv_email',
			  'title' => 'Advanture email',
		  )
	  );
	  $metabox->add_field(
		  array(
			  'type' => 'text',
			  'name' => 'adv_address',
			  'title' => 'Advanture address',
		  )
	  );
	  $metabox->add_field(
		  array(
			  'type' => 'text',
			  'name' => 'adv_latitude',
			  'title' => 'Latitude',
		  )
	  );
	  $metabox->add_field(
		  array(
			  'type' => 'text',
			  'name' => 'adv_longitude',
			  'title' => 'Longitude',
		  )
	  );
	  
  }
	
  
	public function show_adventure() {
		
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
  	
  	$args = array(
  		'post_type' => 'adventure',
		  'posts_per_page' => 18,
		  'paged' => $paged,
		  'meta_key'  => 'adv_timestamp',
		  'orderby' => 'adv_timestamp',
		  'order' => 'ASC',
	  );
		
		$the_query = new WP_Query( $args );
		
		$posts = $the_query->posts;
		
		ob_start();

		
		include dirname(__DIR__) . '/views/public/show-adventure-shortcode.php';
		
		$output = ob_get_clean();
		return $output;
  	
	}
	
}