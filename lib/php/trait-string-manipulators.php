<?php 
require_once (dirname(__FILE__) . "/var-special-plural-cases.php");

trait stringManipulators {

	
	/**
	 * [pluralize description]
	 * @param  [type] $word [description]
	 * @return [type]       [description]
	 */
	public static function pluralize($word){
		global $special_plural_cases;
		$words  = explode(" ", $word);
		$word   = strtolower(trim(array_pop($words)));
		$ftfy   = '';

		//if the last letter is not Y  
		//append an S
		if(substr($word, -1) !== 'y'){

			// if the word is a special case
			if(array_key_exists($word, $special_plural_cases)){
			
				$ftfy = $special_plural_cases[$word];
			
			} else {

				$ftfy =  $word . 's';
				
			}

		// other wise check if we should use 'ies' 
		// or still add an 's' 
		// IE monkeys	
		} else {

			$ftfy = rtrim($word, 'y') . 'ies';

		}

		return ucwords(implode(" ", $words) . ' ' . $ftfy);
	}
}