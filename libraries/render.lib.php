<?php class render extends mfw {
	
	var $stats = array();

	public function __construct() {
		parent::__construct();
	}

	public function view($view, $_render_data = array(), $return = false) {
		ob_start();
		if (!file_exists('views/' . $view . '.php')) {
			new ErrorException('Could not load the view "' . $view . '" because it could not be found.');
		} else {
			$_render_data['path'] = $this->path;
			$_render_data['request'] = $this->request;
			$_render_data['parameters'] = $this->parameters;
			$_render_data['page'] = $this->page;
			$_render_data['site'] = $this->site;
			
			extract($_render_data);
			unset($_render_data);
			
			include('views/' . $view . '.php');
		}
		
		if($return) return ob_get_clean();
		else echo ob_get_clean();
	}

	public function page($view, $_render_data = array(), $return = false) {
		ob_start();
		if (!file_exists('views/' . $view . '.php')) {
			new ErrorException('Could not load the view "' . $view . '" because it could not be found.');
			include($this->template);
		} else {
			$_render_data['path'] = $this->path;
			$_render_data['request'] = $this->request;
			$_render_data['parameters'] = $this->parameters;
			$_render_data['page'] = $this->page;
			$_render_data['site'] = $this->site;

			extract($_render_data);
			unset($_render_data);
			
			if(empty($msg)) $msg = $this->input->get('msg');
			if(empty($msg_class)) $msg_class = $this->input->get('msg_class');

			$view = 'views/' . $view . '.php';
			include($this->template);
		}
		
		if($return) return ob_get_clean();
		else echo ob_get_clean();
	}
	
	function __destruct(){
		if(!empty($this->stats)) {
			echo '
				<!-- STATS
			';
				
				foreach($this->stats as $s){
					echo $s . "\n";
				}
				
			echo '
				-->
			';
		}
	}

}