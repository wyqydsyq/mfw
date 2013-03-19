<?php

/*
 * input class
 * 
 * provides input related functions and manipulation
 */
if (!class_exists('input')) {

	class input extends mfw {
		
		/**
		 * Filter type toggles.
		 * 
		 * $tag_filter will filter our any XML tags
		 * $sql_filter will escape any quotes for SQL queries
		 */
		public $tag_filter = true;
		public $sql_filter = true;

		public function __construct() {
			
		}

		public function cleanse($input) {
			if($input === false) return false;
			if (is_array($input)) {
				$output = array();
				foreach ($input as $k => $v) {
					if(is_array($v)){
						$output[$k] = $this->cleanse($v);
						continue;
					}
					$output[$k] = $this->filter($v);
				}
				return $output;
			} else {
				return $this->filter($input);
			}
		}
		
		// filter the input based on the filter settings
		public function filter($input = ''){
			if($this->tag_filter) $input = filter_var($input, FILTER_SANITIZE_STRING);
			if($this->sql_filter) $input = filter_var($input, FILTER_SANITIZE_MAGIC_QUOTES);
			return $input;
		}
		
		
		/**
		 * 
		 * Each of the input functions below return the value of the input key requested, the entire input array if no key specified, or false if empty
		 * 
		 */
		public function get($input = false) {
			$in = $this->cleanse(($input === false) ? $_GET : ((isset($_GET[$input]) ? $_GET[$input] : false)));
			
			foreach(func_get_args() as $i => $a){
				if($i == 0) continue;
				$in = (!empty($in[$a])) ? $in[$a] : false;
			}
			
			return $in;
		}
		
		public function post($input = false) {
			$in = $this->cleanse(($input === false) ? $_POST : ((isset($_POST[$input]) ? $_POST[$input] : false)));
			
			foreach(func_get_args() as $i => $a){
				if($i == 0) continue;
				$in = (!empty($in[$a])) ? $in[$a] : false;
			}
			
			return $in;
		}
		
		public function request($input = false) {
			$_REQUEST = $_POST + $_GET;
			
			$in = $this->cleanse(($input === false) ? $_REQUEST : ((isset($_REQUEST[$input]) ? $_REQUEST[$input] : false)));
			
			foreach(func_get_args() as $i => $a){
				if($i == 0) continue;
				$in = (!empty($in[$a])) ? $in[$a] : false;
			}
			
			return $in;
		}
		
		public function session($input = false) {
			$in = $this->cleanse(($input === false) ? $_SESSION : ((isset($_SESSION[$input]) ? $_SESSION[$input] : false)));
			
			foreach(func_get_args() as $i => $a){
				if($i == 0) continue;
				$in = (!empty($in[$a])) ? $in[$a] : false;
			}
			
			return $in;
		}
		
		public function cookie($input = false) {
			$in = $this->cleanse(($input === false) ? $_COOKIE : ((isset($_COOKIE[$input]) ? $_COOKIE[$input] : false)));
			
			foreach(func_get_args() as $i => $a){
				if($i == 0) continue;
				$in = (!empty($in[$a])) ? $in[$a] : false;
			}
			
			return $in;
		}

	}

}
?>
