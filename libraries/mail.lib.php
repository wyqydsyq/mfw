<?php class mail extends mfw {
	
	/**
	 * This class utilizes PHPMailer
	 * 
	 * For a tutorial on usage, see: 
	 * https://code.google.com/a/apache-extras.org/p/phpmailer/wiki/UsefulTutorial
	 * 
	 * When using this library, you simply declare a dependance if not already done (is always included by default):
	 * $this->depends('mail');
	 * 
	 * Then use PHPMail methods on $this->mail, e.g.
	 * $this->mail->AddAddress('example@example.com');
	 * $this->mail->Send();
	 */
	
	// Instance of PHPMailer
	public $i;
	// path for PHPMailer
	public $mailer_path = 'bin/PHPMailer/class.phpmailer.php';
	// template path should be relative to framework root path
	public $template = 'assets/php/email_template.php';
	// template vars
	public $template_set = array();
	// defaults
	public $from = '';
	// default email type
	public $email_html = true;
	public $content_type = 'text/html';

	public function __construct() {
		$this->depends('router');
		parent::path();
		
		require_once($this->mailer_path);
		
		// initiate phpmailer with settings
		$i = new PHPMailer();
		$i->ContentType = $this->content_type;
		$i->IsHTML($this->email_html);
		
		$this->i = $i;
	}
	
	// wrap the send function with this to process stuff like the email template
	public function Send(){
		// if an email template is to be used, inject the email body into it.
		if(!empty($this->template)){
			ob_start();
			
			$body = $this->i->Body;
			require($this->template);
			
			$this->i->Body = ob_get_clean();
		}
		
		// finish the Send
		return $this->i->Send();
	}
	
	// overloader to pass method calls to PHPMailer
	public function __call($method, $arg){
		// if the requested method exists in the mysqli instance, call that
		if(method_exists($this->i, $method)) return call_user_func_array(array($this->i, $method), $arg);
		else trigger_error('Error: Could not call \''.$method.'\' of mail class.');
	}
	
	// overloaders for getting and setting on PHPMailer properties
	function __get($name) {
		if(property_exists($this->i, $name)) return $this->i->$name;
		else trigger_error('Error: Could not get \''.$method.'\' of mail class.');
	}
	function __set($name, $value) {
		if(property_exists($this->i, $name)) return $this->i->$name = $value;
		else trigger_error('Error: Could not set \''.$method.'\' of mail class.');
	}
}