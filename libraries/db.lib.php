<?php class db extends mfw {

	// database configuration
	private $config = array('host' => false, 'user' => false, 'password' => false, 'database' => false);

	public function __construct() {
		$this->connect();
	}
	
	// overloader to pass method calls to mysqli
	public function __call($method, $arg){
		// if the requested method exists in the mysqli instance, call that
		try {
			call_user_func_array(array($this->i, $method), $arg);
		} catch (Exception $e) {
			tigger_error('Error: Could not call \''.$name.'\' of '.get_class().' class.');
		}
	}
	
	// overloaders for getting and setting on mysqli properties
	function __get($name) {
		try {
			return $this->i->$name;
		} catch (Exception $e) {
			tigger_error('Error: Could not get \''.$name.'\' of '.get_class().' class.');
		}
	}
	
	function __set($name, $value) {
		try {
			return $this->i->$name = $value;
		} catch (Exception $e) {
			trigger_error('Error: Could not set \''.$name.'\' of '.get_class().' class.');
		}
	}

	// db object
	public $i;
	
	// $resource is used to store the last data resource from the last query run by
	// this class, enables chaining and calling the
	// resource later on.
	public $resource;

	// connect to mysql server and select database
	public function connect() {
		if (empty($this->config['host'])) return false;
		$this->i = new mysqli($this->config['host'], $this->config['user'], $this->config['password'], $this->config['database']);

		// false out if there's any errors
		if (!$this->i || $this->i->connect_errno) return false;
		return true;
	}

}