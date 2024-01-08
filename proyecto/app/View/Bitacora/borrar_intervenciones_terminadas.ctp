<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
	$identificadores = "";
	foreach ($resultados as $resultado) {
		$identificadores .= $resultado["Planificacion"]["id"].",";
	}
	$identificadores .= '0';
	//echo strrev(base64_encode($identificadores));
	if(isset($_GET['callback'])){
		$data = array('d', strrev(base64_encode($identificadores)));
		echo $_GET['callback'].'('.json_encode($data).')';
	} else {
		echo strrev(base64_encode($identificadores));
	}
?>