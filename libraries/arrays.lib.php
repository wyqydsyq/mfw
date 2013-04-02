<?php class arrays extends mfw {

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