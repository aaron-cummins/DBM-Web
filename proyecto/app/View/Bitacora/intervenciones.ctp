<?php
	header('Access-Control-Allow-Origin: *'); 
	ini_set("memory_limit","128M");
	//echo $callback."(".json_encode($intervenciones).");";
	if(isset($_GET['callback'])){
		$data = array('d', strrev(base64_encode(json_encode($intervenciones))));
		echo $_GET['callback'].'('.json_encode($data).')';
	} else {
		echo strrev(base64_encode(json_encode($intervenciones)));
	}
?>