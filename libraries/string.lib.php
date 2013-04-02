<?php class string extends mfw {

	public function __construct() {

	}

	/*
	 * sym_substr
	 *
	 * Finds the longest common substring of an array of strings
	 *
	 * @param $words array An array of strings to compare
	 */

	public function sym_substr($words) {
		$words = array_map('strtolower', array_map('trim', $words));
		$sort_by_strlen = create_function('$a, $b', 'if (strlen($a) == strlen($b)) { return strcmp($a, $b); } return (strlen($a) < strlen($b)) ? -1 : 1;');
		usort($words, $sort_by_strlen);
		// We have to assume that each string has something in common with the first
		// string (post sort), we just need to figure out what the longest common
		// string is. If any string DOES NOT have something in common with the first
		// string, return false.
		$longest_common_substring = array();
		$shortest_string = str_split(array_shift($words));
		while (sizeof($shortest_string)) {
			array_unshift($longest_common_substring, '');
			foreach ($shortest_string as $ci => $char) {
				foreach ($words as $wi => $word) {
					if (!strstr($word, $longest_common_substring[0] . $char)) {
						// No match
						break 2;
					} // if
				}// foreach
				// we found the current char in each word, so add it to the first
				// longest_common_substring element,
				// then start checking again using the next char as well
				$longest_common_substring[0] .= $char;
			}// foreach
			// We've finished looping through the entire shortest_string.
			// Remove the first char and start all over. Do this until there are no more
			// chars to search on.
			array_shift($shortest_string);
		}
		// If we made it here then we've run through everything
		usort($longest_common_substring, $sort_by_strlen);
		return array_pop($longest_common_substring);
	}

	public function secho($echo = '') {
		echo $echo;
	}

	public function ordinal($cdnl) {
		$test_c = abs($cdnl) % 10;
		$ext = ((abs($cdnl) % 100 < 21 && abs($cdnl) % 100 > 4) ? 'th' : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
		return $cdnl . $ext;
	}

}