<?php
/**
 * WordPress post helper functions
 */
trait postHelpers{
	/**
	 * check if a given post if the post we are currently viewing.
	 * @param  WP_Post $check_post 	a WP_Post object that we want to evaluate
	 * @return bool             	whether or not given post is current post
	 */
	public function check_is_current_post(WP_Post $check_post = null){
		global $post;
		
		if(!!$check_post){
			return null;
		}

		return ($check_post->ID === $post->ID);
	}

	/**
	 * give it a slug get a permalink! woo hoo!
	 * @param  string $page_slug  the slug of the page to retrieve
	 * @param  string $post_type  the post type to get slug fom
	 * @return mixed              null or string that is the permalink
	 */
	public function get_permalink_by_slug($page_slug, $post_type = 'page'){
		
		$this_post = get_page_by_path($page_slug, $rtn, $post_type);
	    
	    if (!empty($this_post)){

	        return get_permalink($this_post->ID);
	    
	    } else {
	    
	        return null;
	    
	    }
	}
}