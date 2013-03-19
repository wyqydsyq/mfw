<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php if(!empty($page)){  echo ucwords(str_replace('-', ' ', $page)); ?> - <?php } echo $this->application; ?></title>
		<link type="text/css" rel="Stylesheet" href="<?php echo @$this->router->base; ?>assets/css/jquery.ui.css" />
		<link type="text/css" rel="Stylesheet" href="<?php echo @$this->router->base; ?>assets/css/stylesheet.css" />
	</head>
	<body class="<?=$this->url->slug(strtolower($request))?>">
		<div class="container">
			<?php include($view); ?>
		</div>
		<script type="text/javascript" src="<?php echo @$this->router->base; ?>assets/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo @$this->router->base; ?>assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo @$this->router->base; ?>assets/js/jquery.ui.js"></script>
		<?php if(true){ // toggle live lesscss JS parsing ?>
		    <link rel="stylesheet/less" type="text/css" href="<?php echo @$this->router->base; ?>assets/less/stylesheet.less" />
		    <script type="text/javascript" src="<?php echo @$this->router->base; ?>assets/js/lesscss.js"></script>
		<?php } ?>
		<script type="text/javascript" src="<?php echo @$this->router->base; ?>assets/js/scripts.js"></script>
	</body>
</html>