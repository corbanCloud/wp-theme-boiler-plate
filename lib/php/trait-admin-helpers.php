<?php

trait adminHelpers{

	public $post_types       = [];
	public $admin_only_pages = [];

	public function setup_admin(){
		add_action('admin_menu',array($this,'hide_pages'));
	}

	/**
	 * add a new custom post type for the theme
	 * @param string $name   custom post type name
	 * @param array  $args   argumets for the custom post type's definition
	 * @param array  $labels semantics for the custom post type
	 */
	public function add_post_type($name,$args = array(),$labels = array()){
				
		$cpt = new CustomPostType($name, $args, $labels);
		$this->post_types[$cpt->sanitized_name] =  $cpt;
	}

	/**
	 * allows for a single support declaration with an array
	 * @param  mixed  $supports [description]
	 * @return [type]           [description]
	 */
	public function add_support( $supports ){
		if(is_array($supports)){
			foreach ($supports as $support) {
				add_theme_support($support);
			}
		} else {
			add_theme_support($supports);
		}
		return true;
	}

	/**
	 * adds a given page(s) to the list of pages to hide from non-admin users
	 * @return [type] [description]
	 */
	public function admin_hide_page($pages = ''){
		$pages = (is_array($pages))? $pages : array($pages);
		$this->admin_only_pages = $pages;
	}

	/**
	 * hides all pages in the admin only list from non-admin usersz
	 * @return [type] [description]
	 */
	public function hide_pages(){

		if(!current_user_can( 'manage_options' )){
			foreach ($this->admin_only_pages as $page) {
				remove_menu_page($page);
			}

		}

	}

}