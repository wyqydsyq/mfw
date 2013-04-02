<?php class load extends mfw {

	public function __construct() {
		$this->depends('router');
	}

	public function model($model) {
		if(!class_exists($model)) require('models/' . $model . '.php');
		return new $model();
	}

	public function controller($controller) {
		if (!file_exists('controllers/' . $controller . '.php')) {
			header('HTTP/1.1 404 Not Found');
			header('Location: ' . $this->router->base . 'error/?e=404&file=' . urlencode($this->request));
			return false;
		}
		if(!class_exists($controller)) require('controllers/' . $controller . '.php');
		return new $controller();
	}

}