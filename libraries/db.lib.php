<?php

/*
 * db class
 *
 * provides database functionality: aliases for queries, db connection etc.
 * as well as the ability to chain query functions e.g.
 *
 * $this->db->query("SELECT * FROM `table`")->assoc();
 *
 * will return an associative array of the mysql result.
 */
if (!class_exists('db')) {

	class db extends mfw {

		// database configuration
		private $config = array('host' => false, 'user' => false, 'password' => false, 'database' => false);

		public function __construct() {
			$this->connect();
		}

		// db object
		public $db;
		// $resource is used to store the last data resource from the last query run by
		// this class, enables chaining and calling the
		// resource later on.
		public $resource;

		// connect to mysql server and select database
		public function connect() {
			if (empty($this->config['host'])) return false;
			$this->db = mysql_connect($this->config['host'], $this->config['user'], $this->config['password']);
			if(!$this->db) return false;
			$this->db = mysql_select_db($this->config['database'], $this->db);
			if(!$this->db) return false;
			return true;
		}

		public function query($query) {
			// run query and set it as $this->resource for later use by other functions, then
			// return $this so we can chain it up
			$this->resource = mysql_query($query) or new ErrorException('Errors occured querying database: ' . mysql_error());
			return $this;
		}

		public function num_rows($resource = false) {
			// get mysql_num_rows using either the mysql resource provided or $this->resource
			if (empty($resource))
				$resource = $this->resource;
			return mysql_num_rows($resource);
		}

		public function affected_rows() {
			return mysql_affected_rows();
		}

		public function error() {
			return mysql_error();
		}

		/**
		 * fetch an associative array form the resource. if $loop is true, it will force
		 * the output to be in an array regardless of
		 * whether or not there's more than one result
		 * @param resource $resource Should either be a MYSQL resource, or false to have
		 * the method try to find it
		 * @param bool $loop
		 * @return mixed
		 */
		public function assoc($loop = false, $resource = false) {
			// if resource isn't explicitly set, get it from $this
			if (empty($resource))
				$resource = $this->resource;
			// if it still isn't set, return false
			if (empty($resource))
				return false;
			if (!is_resource($resource)) {
				trigger_error(print_r($resource, true) . ' Is not a MYSQL Resource!');
			}
			$out = array();
			while ($r = mysql_fetch_assoc($resource)) {
				$out[] = $r;
			}
			if (count($out) == 1 && $loop == false) {
				return $out[0];
			}
			return $out;
		}

		public function obj($resource = false) {
			// convert associative array to object
			$res = $this->assoc(false, $resource);
			if ($this->num_rows() > 1) {
				foreach ($res as $k => $v) {
					$res['_' . $k] = (object)$v;
				}
			}
			return (object)$res;
		}

	}

}
?>
