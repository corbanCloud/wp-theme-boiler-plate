<?php
/**
* Class and Function List:
* Function list:
* - setup_assets()
* - add_style()
* - add_script()
* - add_wp_script()
* - theme_assets_handler()
* Classes list:
*/

/**
 * Shortcuts the addition of theme resources
 * includes NTR assets by default
 */

require_once 'class-custom-post-type.php';

trait assetHelpers
{
	
	private $styles = null;
	private $scripts = null;
	private $wp_scripts = null;
	private $prefix = '';
	
	/**
	 * puts basic styles and scripts in resource collection as defaults
	 * @return none
	 */
	private function setup_assets() {
		$this->prefix    = sanitize_title($this->theme_name) . '-';
		$public_lib      = '/lib/pub/';
		$source_lib      = '/lib/src/';

		//IF WP DEBUG Is ON, load source maps and assets
		if (constant("WP_DEBUG") === true) {
			//Style Resources
			$this->styles[] = array(
				'slug' => $this->prefix . 'styles',
				'path' => $public_lib . 'css/master.css',
				'deps' => array()
			);
			
			$this->styles[] = array(
				'slug' => $this->prefix . 'scss',
				'path' => $source_lib . 'scss/master.scss',
				'deps' => array( $this->prefix . 'styles')
			);

			$this->styles[] = array(
				'slug' => $this->prefix . 'css-map',
				'path' => $source_lib . 'maps/master.css.map',
				'deps' => array( $this->prefix . 'styles', $this->prefix . 'scss')
			);
		

			$this->scripts[] = array(
					'slug' => $this->prefix . 'scripts',
					'path' => $public_lib . 'js/scripts.js',
					'deps' => array(
						'jquery'
					)
			);
			$this->scripts[] = array(
				'slug' => $this->prefix . 'script-map',
				'path' => $source_lib . 'maps/scripts.js.map',
				'deps' => array(
					'jquery',
					$this->prefix . 'scripts'
				)
			);
	
		// Otherwise load only minified assets
		} else {

			$this->styles[] = array(
				'slug' => $this->prefix . 'styles-min',
				'path' => $public_lib . 'css/master.min.css',
				'deps' => array()
			);

			$this->scripts[] = array(
					'slug' => $this->prefix . 'scripts-min',
					'path' => $public_lib . 'js/scripts.min.js',
					'deps' => array(
						'jquery'
					)
			);
			
		}
		
		$this->add_wp_script('jquery');
		
		add_action('wp_enqueue_scripts', array(
			$this,
			'theme_assets_handler'
		));
	}
	
	/**
	 * adds a style to the theme to be enqueued later
	 * @param string $slug name w/o prefixing of the style to add
	 * @param string $src  path relative to theme root
	 * @param [type] $deps dependencies of the style if any
	 * @return none
	 */
	public function add_style($slug = null, $src = null, $deps = null) {
		$style = [];
		
		$style['slug'] = $slug;
		$style['src'] = $src;
		$style['deps'] = $deps;
		
		array_push($this->styles, $style);
	}
	
	/**
	 * adds a script to the theme to be enqueued later
	 * @param string $slug name w/o prefixing of the script to add
	 * @param mixed  $src  path relative to theme root
	 * @param array $deps  dependencies of the script if any
	 * @return none
	 */
	public function add_script($slug = null, $src = null, $deps = null) {
		$script = [];
		
		$script['slug'] = $slug;
		$script['src'] = $src;
		$script['deps'] = $deps;
		
		array_push($this->scripts, $script);
	}
	
	/**
	 * add one of WP's default scripts into the theme
	 * @param string $slug the script to require
	 */
	public function add_wp_script($slug) {
		array_push($this->wp_scripts, $slug);
	}
	
	/**
	 * handles registering and enqueue scripts and styles
	 * @return none
	 */
	public function theme_assets_handler() {
		
		//Enqueue stylesheets
		foreach ($this->styles as $style) {
			wp_register_style($this->prefix . $style['slug'], get_template_directory_uri() . $style['path'], $style['deps']);
			wp_enqueue_style($this->prefix . $style['slug']);
		}
		
		//Enqueue Scripts
		foreach ($this->scripts as $script) {
			wp_register_script($this->prefix . $script['slug'], get_template_directory_uri() . $script['path'], $script['deps']);
			wp_enqueue_script($this->prefix . $script['slug']);
		}
		
		//Enqueue WP Scripts
		foreach ($this->wp_scripts as $script) {
			wp_enqueue_script($script);
		}
	}
}
