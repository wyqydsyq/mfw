<?php class db extends mfw {

	// database configuration
	private $config = array('host' => false, 'user' => false, 'password' => false, 'database' => false);

	public function __construct() {
		$this->connect();
	}
	
	// call overloader to route commands to the db object
	public function __call($method, $arg){
		// if the requested method exists in the mysqli instance, call that
		if(method_exists($this->i, $method)) return call_user_func_array(array($this->i, $method), $arg);
		else trigger_error('Error: Could not call \''.$method.'\' of db class.');
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