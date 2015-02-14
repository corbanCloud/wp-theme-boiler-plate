<?php 

include "trait-string-manipulators.php";
include "trait-asset-helpers.php";
include "trait-admin-helpers.php";
//include "trait-post-helpers.php";
//include "trait-html-helpers.php";

/**
* 
*/
class NimbusTheme
{
	use stringManipulators;
	use assetHelpers;
	use adminHelpers;

	public $theme_name;

	function __construct($name){
		$this->theme_name = $name;

		$this->setup_assets();
		$this->setup_admin();

	}

};


?>