<?php 
require_once (dirname(__FILE__) . "/var-special-plural-cases.php");

trait stringManipulators {

	
	/**
	 * Returns the plural form of an english word, 
	 * takes into account most special cases such as fish -> fish. 
	 * 
	 * @param  string $word the word you want pluralized
	 * @return string       the pluralized form of the word
	 */
	public static function pluralize($word){
		global $special_plural_cases; //import the list of words with special plural cases
		$words  = explode(" ", $word);
		$word   = strtolower(trim(array_pop($words)));
		$pluralized = '';

		//if the last letter is not Y  

		if(substr($word, -1) !== 'y'){

			// and if the word is a special case
			if(array_key_exists($word, $special_plural_cases)){
				//give the special case
				$pluralized = $special_plural_cases[$word];

			//else just use ye old regular add 's'
			} else {

				$pluralized =  $word . 's';	
			
			}

		// other wise we should use 'ies' to pluralize the word
		// EG monkeys	
		} else {

			$pluralized = rtrim($word, 'y') . 'ies';

		}

		return ucwords(implode(" ", $words) . ' ' . $ftfy);
	}
}