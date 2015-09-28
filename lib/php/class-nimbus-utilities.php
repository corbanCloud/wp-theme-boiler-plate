<?php 

include_once "trait-string-manipulators.php";
include_once "trait-post-helpers.php";
include_once "trait-html-helpers.php";

/**
* 
*/
abstract class NimbusUtilities {
	use stringManipulators;
	use postHelpers;
	use htmlHelpers;


	function __construct(){}
	
	public function debug_to_console ($data) {

		if(is_array($data)){
			$data = json_encode($data);
		}

		echo '<script>console.log(' . $data . ')</script>';

	}
};


?>