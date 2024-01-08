<?php
	header('Access-Control-Allow-Origin: *'); 
	ini_set("memory_limit","512M");
	if(isset($_GET['callback'])){
		$data = array('d', strrev(base64_encode(json_encode($resultados))));
		echo $_GET['callback'].'('.json_encode($data).')';
	} else {
		echo strrev(base64_encode(json_encode($resultados)));
	}
?>