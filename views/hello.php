<div class="hero-unit">
	<h1>Hello, I'm mfw.</h1>
	<p>
		And I've just been installed successfully, you can start coding away!
	</p>
</div>
<h2>Getting started:</h2>
<p>
	Some common post-install tasks are:
</p>
<ul>
	<li>
		Open up <code>mfw.php</code> and change the configuration variables to suit your needs
	</li>
	<li>
		Delete the <code>welcome</code> controller and view, and make your own default controller (remember to change <code>$default_controller</code> in <code>mfw.core.php</code>!)
	</li>
	<li>
		Set up your MySQL database configuration in <code>libraries/db.lib.php</code>
	</li>
</ul>
<h2>Install Info:</h2>
<ul>
	<li>
		Application name: <code><?= $this->application; ?></code>
	</li>
	<li>
		Application base directory: <code><?= $this->base_url; ?></code>
	</li>
	<li>
		Default controller: <code><?= $this->default_controller; ?></code>
	</li>
	<li>
		Default method: <code><?= $this->default_method; ?></code>
	</li>
	<li>
		Page template file: <code><?= $this->template; ?></code>
	</li>
	<li>
		Database status: <code><?= (($db->connect()) ? 'connected' : 'disconnected') ?></code>
	</li>
	<li>
		Execution time: <code><?= round(microtime() - $GLOBALS['start'], 4) ?> seconds</code>
	</li>
</ul>