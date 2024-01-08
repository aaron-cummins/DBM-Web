<?php
	if(isset($_GET['callback'])){
		$data = array('d', strrev(base64_encode(json_encode($diagnosticocategoria))));
		echo $_GET['callback'].'('.json_encode($data).')';
	} else {
		echo $callback."(".json_encode($diagnosticocategoria).");";
	}
?>