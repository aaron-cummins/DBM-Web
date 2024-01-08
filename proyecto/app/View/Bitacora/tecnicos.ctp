<?php
	header('Access-Control-Allow-Origin: *'); 
	if(isset($_GET['callback'])){
		$data = array('d', strrev(base64_encode(json_encode($tecnicos))));
		echo $_GET['callback'].'('.json_encode($data).')';
	} else {
		echo strrev(base64_encode(json_encode($tecnicos)));
	}
?>