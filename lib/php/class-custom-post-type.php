<?php

require_once (dirname(__FILE__) . "/trait-string-manipulators.php");

$cpt_count = 0;

class CustomPostType{

	use stringManipulators;

	public $given_name      = null;
	public $sanitized_name  = null;
	public $pluralized_name = null;
	public $data            = null;
	public $arguments       = [];

	
	public function __construct($name,array $args,array $labels){
		global $cpt_count;

		$this->set_names($name);
		$this->set_arguments($args, $labels);

		// Hook into the 'init' action
		add_action( 'init', array($this,'register_custom_post_type'));
		$cpt_count += 1;
	}	
	
	////////////////////////////////
	// Registering the post Type //
	////////////////////////////////

	// Register Custom Post Type
	public function register_custom_post_type() {
  		$this->data = register_post_type( $this->plural_sanitized_name, $this->arguments );

	}

	private function set_names($name){
		
		
		$this->given_name = $name;
		$this->pluralized_name = $this->pluralize($name);
		$this->sanitized_name = (string) sanitize_title($name);
		$this->plural_sanitized_name = (string) sanitize_title($this->pluralized_name);
	
	}

	private function set_arguments($given_args = array(), $given_labels = array()){
		global $cpt_count;
		//if a seperate array of labels is not nested array of labels from $given_args
		$labels = (function(){

		if(!empty($given_labels)){
			return $given_labels;
		} else {
			if( array_key_exists('labels', $given_args)){
			return $given_args['labels'];
			} else {
			return array();
			}
		}
			
		});
		

		$final_agrs = [];
		$final_labels = [];

		$default_labels = array(
			'name'               => "$this->pluralized_name",
			'singular_name'      => "$this->given_name",
			'menu_name'          => "$this->pluralized_name",
			'name_admin_bar'     => "$this->given_name",
			'add_new_item'       =>	"Add New $this->given_name",
			'new_item'           => "New $this->given_name",
			'edit_item'          => "Edit $this->given_name",
			'view_item'          => "View $this->given_name",
			'all_items'          => "All $this->pluralized_name",
			'search_items'       => "Search $this->pluralized_name",
			'not_found'          => "No $this->pluralized_name Found",
			'not_found_in_trash' => "No $this->pluralized_name Found In Trash"
		);

		$default_args = array(
			'label'               => $this->given_name,
			'description'         => "The Custom Post Type for $this->pluralized_name",
			'supports'            => array( 'title', 'revisions', 'page-attributes'),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => (20 + $cpt_count),
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		
		//Merge labels
		$final_labels    = array_merge($default_labels, $given_labels);
		//Merge arguments
		$final_args      = array_merge($default_args, $given_args);

		
		$final_args['labels'] = $final_labels;
		

		$this->arguments = $final_args;

	}
	////////////////////////////////
	// End Registering Post Type //
	////////////////////////////////
}