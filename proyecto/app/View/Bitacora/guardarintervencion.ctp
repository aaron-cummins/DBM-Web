<?php
	header('Access-Control-Allow-Origin: *');
	if(isset($_GET['callback'])){
		$data = array('d', $resultado);
		echo $_GET['callback'].'('.json_encode($data).')';
	} else {
		echo $resultado;
	}
?>