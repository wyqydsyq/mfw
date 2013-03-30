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
	
	// an easy, inline way to embed an image
	function embed_image($file, $cid='', $name=false, $attr=array()){
		$this->AddEmbeddedImage($file, $cid, $name);
		$r = '<img src="cid:'.$cid.'" title="'.$name.'"';
		
		if(!empty($attr) && is_array($attr)){
			foreach($attr as $k=>$v){
				$r .= ' '.$k.'="'.$v.'"';
			}
		}
		
		$r .= ' />';
		
		return $r;
	}
	
	// overloader to pass method calls to PHPMailer
	public function __call($method, $arg){
		// if the requested method exists in the mysqli instance, call that
		try {
			call_user_func_array(array($this->i, $method), $arg);
		} catch (Exception $e) {
			tigger_error('Error: Could not call \''.$name.'\' of '.get_class().' class.');
		}
	}
	
	// overloaders for getting and setting on PHPMailer properties
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
}