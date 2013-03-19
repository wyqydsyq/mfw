<?php

/*
 * url class
 * 
 * provides url related functions and manipulation
 */
if (!class_exists('url')) {

	class url extends mfw {

		public function __construct() {
			$this->depends(array('router', 'input'));
			$this->path();
		}

		/**
		 * Creates a nice, clean URI slug
		 * 
		 * @param string $str String to slugify
		 * @param array $replace Array of extra characters to replace with $delimiter
		 * @param string $delimiter String to use for replacement of uri-unfriendly characters. Usually an underscore (_) or dash (-)
		 */
		public function slug($str, $replace = array(), $delimiter = '-') {
			if (!empty($replace)) {
				$str = str_replace((array) $replace, ' ', $str);
			}

			$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
			$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
			$clean = strtolower(trim($clean, '-'));
			$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

			return $clean;
		}
		
		/**
		 * Turns a URI slug into some user-friendly text
		 */
		public function humanize($str, $capitalize = true){
			$str = str_replace(array('_', '-'), ' ', urldecode($str));
			if($capitalize) $str = ucwords($str);
			return $str;
		}

		/*
		 * Redirects to $to and sets http 303 status.
		 * 
		 * useage: $this->url->redirect('controller/parameter');
		 */

		public function redirect($to = '') {
			header('HTTP/1.1 303 See Other');
			header('Location: ' . $this->router->base . $to);
		}
		
		/*
		 * Same as redirect(), except relative to the HTTP root,
		 * so you might end up outside the mfw application with this.
		 */
		public function redirect_out($to = '') {
			header('HTTP/1.1 303 See Other');
			header('Location: ' . $to);
		}
		
		/*
		 * Returns a url relative to the base_url, useful for
		 * linking to controllers when you're not sure if
		 * the application will always be in the same place
		 */
		public function anchor($to = ''){
			return $this->router->base . $to;
		}

		public function build_query($array){
			$str = '';
			foreach($array as $k => $v){
				if(is_array($v)) {
					foreach($v as $vv){
						$str .= '&'.urlencode($k).'[]='.urlencode($vv);
					}
				} else {
					$str .= '&'.urlencode($k).'='.urlencode($v);
				}
			}
			return $str;
		}

	}

}
