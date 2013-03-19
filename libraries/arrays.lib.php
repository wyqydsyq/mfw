<?php

/*
 * arrays class
 * 
 * provides arrays related functions and manipulation
 */
if (!class_exists('arrays')) {

	class arrays extends mfw {

		public function __construct() {
		}
		
		/*
		 * Removes empty array elements
		 */
		public function trim($array, $bad=''){
			foreach($array as $key => $value){
				if($value == $bad){
					unset($array[$key]);
				}
			}
			return $array;
		}

	}

}
?>
