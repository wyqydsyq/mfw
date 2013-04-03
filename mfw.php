<?php

/*
 * mfw.php
 *
 * The core engine of the framework. Initializes everything, and manages
 * loading of libraries, as well as providing some basic debug tools.
 */

class mfw {

	/**
	 * ========================================================================
	 * CONFIGURATION
	 * ========================================================================
	 */

	/**
	 * Default controller and method to load
	 */
	public $default_controller = 'hello';
	public $default_method = 'index';

	/**
	 * Settings for HTML template-based output
	 */
	public $application = 'mfw'; // name of the application or project
	public $template = 'assets/php/template.php'; // location of the default template file

	/**
	 * Logging settings
	 */
	// true/false to turn logging on/off (generally you'd only have loggin on when debugging)
	public $log_switch = false;
	// where to send the logs ('file' to log to $log_file, 'error_log' to log to the PHP error log, 'email' to email the log to $log_email)
	public $log_to = 'file';
	// file to log to when $log_to is set to 'file'
	public $log_file = 'mfw.log';
	// email to log to when $log_to is set to 'email'
	public $log_email = '';

	/**
	 * Path variables, generally you can leave these be, but if auto-detection of the
	 * base installation directory doesn't work, you'll want to change these variables
	 * (usually only $base) to suit your setup.
	 */
	public $base = false;
	public $path = '';
	public $request = '';
	public $parameters = array();
	public $_get = array();

	/**
	 * Libraries to be auto-loaded on initialization. These will be loaded for every class that extends mfw.
	 *
	 * If you have a library that won't be widely used, you can save execution time by
	 * only loading it when it's needed, by running $this->depends($library_name);
	 */
	public $libraries = array('load', 'render', 'db', 'string', 'url', 'input', 'arrays', 'mail');


	/**
	 * ========================================================================
	 * mfw CORE - HERE BE DRAGONS!
	 * ========================================================================
	 */
	 
	/**
	 * Path figures out where we actually are, because that usually helps.
	 * 
	 * Tries to auto-detect the base path (or use manually specified $this->base), then chop up the address into
	 * useful stuff like an array of url segments and so on.
	 */
	public function path($path = false) {
		// request is the url relative to the location of the application (the path minus
		// any subdirectories that the application might be in)
		$this->request = (!empty($path))?$path:$this->path;
		$this->host = $_SERVER['HTTP_HOST'];
		$this->parameters = explode('/', $this->request);
		
		$this->root_url = $_SERVER['DOCUMENT_ROOT'];
		$this->page = ($this->parameters[count($this->parameters) - 1] != 'index') ? $this->parameters[count($this->parameters) - 1] : $this->parameters[count($this->parameters) - 2];
	}

	/**
	 * Initialize mfw.
	 * 
	 * Find what controller/method we're supposed to be looking for, then run it. Pretty straightforward.
	 */
	public function start() {
		
		session_name('mfw');
		session_start();
		
		$this->path();

		$controller = $this->parameters[0];

		// load controller
		$load_controller = $this->$controller = $this->load->controller($controller);

		if ($load_controller !== false) {
			// find the method to run
			$method = $this->parameters[1];
                        
                        $properties = array();
			for ($i = 2; $i <= count($this->parameters) - 1; $i++) {
				$properties[] = $this->parameters[$i];
			}
			
			// if method not found, error out
			if(!method_exists($this->$controller, $method)) {
				$_GET['e'] = '404';
				$_GET['file'] = $controller.'/'.$method;
				$this->$controller = $this->load->controller('error');
				$method = 'index';
			}
			
			// run method
			call_user_func_array(array($this->$controller, $method), $properties);
		}
	}
	
	/**
	 * The CONSTRUCTOR.
	 * 
	 * Makes sure all required libraries are loaded.
	 * You want this to run in all your controllers, so if you have a __construct() in one, make sure it runs parent::__construct();
	 */
	public function __construct($start = false) {
		global $mfw;
		if($start) $mfw = $this;
		
		$this->depends('router');
		$mfw->base = $this->base = $this->router->locate();
		$baseless_url = (preg_match('/^\/$/', $this->base)) ? substr($_SERVER['REQUEST_URI'], 1) : str_replace($this->base, '', $_SERVER['REQUEST_URI']);
		$mfw->path = $this->path = $this->router->parse($baseless_url);
		
		$this->depends($this->libraries);
		$this->path($this->path);
	}

	/**
	 * Load dependant libraries.
	 * 
	 * Pass a string or array of library names, they'll be loaded into $this under their own name.
	 * For example, depends('load') will load '/libraries/load.lib.php' as $this->load.
	 * 
	 * @param mixed $dependencies The librar(ies/y) to load.
	 * @return boolean True if all libraries loaded successfully, false if any libraries couldn't be loaded.
	 */
	public function depends($dependencies = array()) {
		global $mfw;
		$r = true;
		
		// cast $dependencies to an array, just in case someone tried to pass a string
		$dependencies = (array) $dependencies;
		
		// foreach library
		foreach ($dependencies as $dependency) {
			// if library is already loaded in this scope, skip it
			if (isset($this->$dependency) || get_class($this) == $dependency)
				continue;

			// if library has been previously loaded, import it to this scope
			if (isset($mfw->$dependency)) {
				$this->_log(get_class($this) . ': importing already loaded dependency => ' . $dependency);
				$this->$dependency = $mfw->$dependency;
				continue;
			}

			// library not loaded, so load it!
			include_once ('libraries/' . $dependency . '.lib.php');
			if (!method_exists(get_class($this), $this->$dependency) && class_exists($dependency)) {
				$this->_log(get_class($this) . ': loading dependency => ' . $dependency);
				$this->$dependency = $mfw->$dependency = new $dependency();
			} else {
				$this->_log('Error loading dependency ' . $dependency, 'error_log');
				$r = false;
			}
		}
		return $r;
	}

	/**
	 * Overloader for getting a non-existant parameter of mfw
	 *
	 * Return false when we try to access (get) a non-existing class parameter from mfw
	 *
	 * @param string $name The parameter trying to be accessed
	 * @return boolean Always returns false.
	 */
	public function __get($name) {
		return false;
	}

	/**
	 * Logs data to the specified location
	 *
	 * @param string $string The string to log
	 * @param mixed $to The destination for the logged string, defaults to false to get this value from $this->log_to
	 * @return boolean True if the logging completed successfully, false on error
	 */
	public function _log($string, $to = false) {
		if($this->log_switch === false) return true;
		
		if (!$to) {
			$to = $this->log_to;
		}

		$string = date('Y-m-d Hi') . ': ' . $string;

		switch($to) {
			case 'error_log':
				error_log($string);
				return true;
			break;
			case 'file':
				$handle = fopen($this->log_file, 'a+');
				fwrite($handle, $string . "\n", strlen($string . "\n"));
				fclose($handle);
				return true;
			break;
			case 'email':
				if (mail($this->log_email, 'mfw log', $string)) {
					return true;
				} else {
					$this->_log('Logging error: Could not email log to ' . $this->log_email);
					return false;
				}
			break;
		}
	}

}
