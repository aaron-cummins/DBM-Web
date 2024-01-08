<!DOCTYPE html> 
<html>
<head> 
	<title>SIS-RAI<?php if(isset($titulo)){echo " - $titulo";} ?></title>
	<META NAME="AUTHOR" CONTENT="Salmon Software Ltda." />	
	<META NAME="ROBOTS" CONTENT="NONE" />
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache, mustrevalidate" />
	<meta http-equiv="Cache-Control" content="no-store" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 2010 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
	<meta name="format-detection" content="telephone=no">
	
	<link rel="stylesheet" href="../css/jquery.mobile-1.4.5.min.css" />
	<script src="../js/jquery-1.8.2.min.js"></script>
	<script src="../js/jquery.mobile-1.4.5.min.js"></script>
	<script src="../js/load_db_dcc.js"></script>
	<script src="../js/custom_dcc.js"></script>
	<script>
		
	</script>
</head>
<body>
	<div data-role="page">
		<div data-role="header" data-theme="b">
			<h1><?php if(isset($titulo)){echo $titulo;} ?></h1>
		</div>
		<div data-role="content">
			<?php echo $this->fetch('content'); ?>
		</div>
		<div data-role="footer" data-theme="b">
			<div data-role="navbar">
				<ul>
					<li><a href="./p1_login.html" data-ajax="false" class="ui-btn nav-btn" data-icon="delete" data-iconpos="left">Salir</a></li>
				</ul>
			</div>
		</div>
	</div>
</body>
</html>