<?php class router extends mfw {
	
	var $routes = array();
	var $matched = array();
	var $parsed_url = '';
	var $request = '';
	var $query_string = '';
	
	function __construct() {
		
		$path['^sample_route[\/]?$'] = 'hello';
		
		$this->routes = $path;
		$this->locate();
		$this->get_refresh();
		
	}
	
	/**
	 * Parse a URL and resolve any matching routes.
	 * 
	 * This function loops over router::routes, when it finds a match it will set $this->path to the route destination,
	 * then call itself again to see if the resolved path matches any other routes.
	 * 
	 * When it can't find any more routes to match to, it returns the current route and sets $mfw->path
	 * 
	 * @global type $mfw
	 * @param type $url
	 * @return type 
	 */
	function parse($url){
		global $mfw;
		if(!empty($mfw->request)) return $mfw->request;
		
		if(strpos($url, '?') !== false) {
			$addr = parse_url($url);
			parse_str($addr['query'], $qs);
			$GLOBALS['true_get'] = $qs;
		}
		
		$url = (strpos($url, '?') !== false) ? substr($url, 0, strpos($url, '?')) : $url;
		if(!preg_match('/^\/$/', $this->base)) $url = str_replace($this->base, '', $url);
		
		// try to match the path against routes
		foreach($this->routes as $path => $target){
			
			if(!in_array($path, $this->matched) && preg_match('/'.$path.'/', $url)){
				
				$parsed = $this->base.preg_replace('/'.$path.'/', $target, $url);
				
				$this->matched[] = $path;
				$this->request = $parsed;
				$_SERVER['REQUEST_URI'] = $parsed;
				$this->get_refresh();
				return $this->parse($parsed);
			}
		}
		
		
		// check for controller and method
		$p = explode('/', $url);
		if(empty($p[0])) $p[0] = $this->default_controller;
		if(empty($p[1])) $p[1] = $this->default_method;
		
		$url = implode('/', $p);
		$mfw->request = $this->request = $url;
		$this->parsed_url = parse_url('http'.((!empty($_SERVER['HTTPS']))?'s':'').'://'.$_SERVER['HTTP_HOST'].$this->base.$url.'?'.$this->query_string);
		return $url;
	}
	
	/**
	 * Calculate the directory that mfw lives in, relative to the web root
	 * @return string The directory that mfw lives in 
	 */
	function locate(){
		// if $this->base is false, try to auto-detect the base path
		if ($this->base == false) {
			$this->dir = str_replace('\\', '/', dirname(__FILE__)) . '/';

			$base[0] = explode('/', str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])));
			$base[1] = explode('/', substr($this->dir, 0, -1));
			$base[2] = explode('/', str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])));

			for ($i = count($base[1]); $i < count($base[0]); $i++)
				array_pop($base[2]);

			$url = implode('/', $base[2]);

			if ($url{strlen($url) - 1} == '/')
				$this->base = $url;
			else
				$this->base = $url . '/';

			unset($base, $url);
			$dir = $this->base;
		} else {
			$dir = (!empty($this->base)) ? $this->base : '/';
		}
		
		return $dir;
	}
	
	/**
	 * Refresh $_GET with any route-induced parameters. 
	 */
	function get_refresh(){
		
		// fix up $_GET
		if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
			$req = parse_url($_SERVER['REQUEST_URI']);
			$this->query_string = (!empty($req['query'])) ? $req['query'] : '';
			parse_str($this->query_string, $req);
			$this->_get = $_GET = $GLOBALS['true_get'] + $req;
		} else {
			$this->query_string = '';
			$this->_get = $_GET = array();
		}
	}
}
