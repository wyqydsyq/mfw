<?php class hello extends mfw {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->render->page('hello', array('db' => $this->db));
	}

}