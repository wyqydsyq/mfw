<?php class load extends mfw {

	public function __construct() {
		$this->depends('router');
	}

	public function model($model) {
		if(!class_exists($model)) require('models/' . $model . '.php');
		
		$parameters = func_get_args();
		unset($parameters[0]);
		
		$reflect  = new ReflectionClass($model);
		return $reflect->newInstanceArgs($parameters);
	}

	public function controller($controller) {
		if (!file_exists('controllers/' . $controller . '.php')) {
			header('HTTP/1.1 404 Not Found');
			header('Location: ' . $this->router->base . 'error/?e=404&file=' . urlencode($this->router->request));
			return false;
		}
		
		if(!class_exists($controller)) require('controllers/' . $controller . '.php');
		
		$parameters = func_get_args();
		unset($parameters[0]);
		
		$reflect  = new ReflectionClass($controller);
		return $reflect->newInstanceArgs($parameters);
	}

}