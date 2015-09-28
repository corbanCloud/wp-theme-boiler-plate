<?php 
/**
 * Useful HTML generators
 */
trait htmlHelpers{

	protected $template_data;

	/**
	 * retrieves an ACf field
	 * @param  mixed $mixed_args a string of the field name to get or an array 
	 *                           with the field name and page to get field from
	 *                           
	 * @param  boolean $is_sub    if the field should be called with
	 *                            get_sub_field instead of get_field
	 * @return mixed 			the data   
	 */
	protected function get_acf_data(
		$mixed_args, 
		$is_sub = false) {


		if(is_string($mixed_args)){
			
			return get_field($mixed_args);
		
		} else if(is_array($mixed_args) && count($mixed_args) > 1) {
			return get_field($mixed_args[0], $mixed_args[1]);
		}
	
	}

	/**
	 * prints an HTML5 data attribute of a given field name
	 * @param  string $value the name of the field to get and print
	 * @return none        
	 */
	private function print_single_acf_data_attr($value, $is_sub){
		$value = sanitize_title($value);
		echo 'data-'. $value . '="' . $this->get_acf_data($value, $is_sub) .'"';
	}


	/**
	 * takes an array or string and prints the given values as HTML data attributes
	 * @param  mixed $data string or array of string of field name(s) to print 
	 * @return none
	 */
	public function print_acf_html5_data( $data = null){
		
		if(is_array($data)){

			foreach ($data as $key => $datum) {
			
				$this->print_single_acf_data_attr($datum);
			
			}

		} else {
			
			$this->print_single_acf_data_attr($data);
		
		}
	}

	/**
	 * prints the inline style for a background image
	 * @param  string $field_name the name of the image object field to get
	 * @param  string $image_size the size of the image to get
	 * @param  string $fall_back  a fall back image to use if the field is not set
	 * @return none
	 */
	public function print_bg_image_from_field($field_name, $image_size = "full",$is_sub_field = false){
		
		$image = ($is_sub_field)?get_sub_field($field_name): get_field($field_name);
		$image_url = '';


		if(!empty($image)){


			if(array_key_exists($image_size, $image['sizes'])){
			
			$image_url = $image['sizes'][$image_size];

			} elseif(array_key_exists('url', $image)) {

				$image_url = $image['url'];
			}

			echo 'background-image: url(' . $image_url . ');';
		}
	}

	/**
	 * Set data for use in rendering a template part
	 * @param array $data the data we should set
	 */
	private function set_template_data($data) {
		return $this->template_data = $data;
	}

	/**
	 * Get data for use in rendering a template part, 
	 * prevents the need to assign data to global name space, 
	 * and call global $var in template parts.
	 * 
	 * @param array $data the data we should get
	 */
	public function get_template_data(){
		return $this->template_data;
	}

	/**
	 * Clear data used in rendering a template part
	 * @param none
	 */
	private function clear_template_data(){
		return $this->template_data = null;
	}

	
	/**
	 * Sets data for the template to get, then 
	 * @param  [type] $template_part [description]
	 * @param  [type] $data          [description]
	 * @return [type]                [description]
	 */
	protected function render_template_part($template_part, $data) {
		

		$template_data = $this->set_template_data($data);
		
		get_template_part('partials/' . $template_part);

		$this->clear_template_data();
	}

	public static function render_error_message($message){
		
		if(constant('WP_DEBUG')){

			echo '<!-- Templating Error: ' . $message . '-->';
		
		};
		return false;
	} 
	


	/**
	 * Because anchor tags get really old with all their inlined PHP
	 * @param  string $slug           the slug for the page to link to
	 * @param  string $text           the text for the link
	 * @param  string $post_type      specify post type if nessecary
	 * @param  string $base_class     the anchor tags main class(es). If multi classput element lvl class last
	 * @param  string $class_modifier class modifier (see BEM syntax)
	 * @return none                 
	 */
	public function print_anchor_tag(
			$slug, 
			$text, 
			$post_type = 'page',
			$base_class = 'nav__link', 
			$class_modifier = '--current'){
		
		$post_to_link = $this->get_posts(array(
			'meta_key'=> 'slug', 
			'meta_value' => $slug, 
			'post_type' => $post_type));

		if(!$post_to_link)
			return null;

		$classes = $base_class;

		if($this->check_is_current_post($post_to_link)){
			// add a class like 'nav__link--current'
			$classes += ($base_class . $class_modifier);
		}

		if(!empty($text))
			$text = $post_to_link->post_title;

		echo '<a title="' . $post_to_link->post_title . '" href="' . $post_to_link->post_permalink . '" class="' . $classes . '">' . $text . '</a>';
	}
}