<?php

if (!class_exists('error')) {

	class error extends mfw {

		public function __construct() {
			parent::__construct();
		}

		public function index() {
			$this->render->page('error/'.(($this->input->get('e') !== false) ? $this->input->get('e') : 'unknown'), array('file' => $this->input->get('file')));
		}

	}

}