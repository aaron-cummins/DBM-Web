<?php
App::uses('ConnectionManager', 'Model'); 
App::uses('CakeEmail', 'Network/Email');

/*
	Esta clase define el comportamiento y despliegue del Perfil Supervisor Cliente
*/

class ClienteController extends AppController {
	/*
		Este metodo define el desliegue de los graficos en la pantalla principal
	*/
	public function index($order = "", $order_d = "") {
		$usuario = $this->Session->read('Usuario');
		$faena_id = $this->Session->read('faena_id');
		if ($faena_id != 0) {
			$faena = "Planificacion.faena_id = $faena_id";
		} else {
			$faena = 'Planificacion.faena_id <> 0';
		}
		$correlativo="";
		$query = "";
		$estado = "0";
		$tipo_intervencion = "";
		$flota_id = "";
		$unidad_id = "";
		$sid="";
		$tipo_evento = "";
		$codigo = "";
		$fecha_inicio =  date('Y-m-d', time() - 365*24*60*60);
		$fecha_inicio_termino = date('Y-m-d');
		$fecha_termino = $fecha_inicio;
		$fecha_termino_termino = $fecha_inicio_termino;
		$tipo_terminado="";
		$query = "Planificacion.estado IN (4) AND ";
		$url = "1";
		if (isset($this->request->query) && count($this->request->query) > 0) {
			if (isset($this->request->query['tipo_evento']) && $this->request->query['tipo_evento'] != "") {
				$tipo_evento = $this->request->query['tipo_evento'];
				$url .= "&tipo_evento=$tipo_evento";
			}
			
			if (isset($this->request->query['fecha_inicio']) && $this->request->query['fecha_inicio'] != "") {
				$fecha_inicio = $this->request->query['fecha_inicio'];
				$url .= "&fecha_inicio=$fecha_inicio";
			}
			
			if (isset($this->request->query['fecha_inicio_termino']) && $this->request->query['fecha_inicio_termino'] != "") {
				$fecha_inicio_termino = $this->request->query['fecha_inicio_termino'];
				$url .= "&fecha_inicio_termino=$fecha_inicio_termino";
			}
			
			if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != "") {
				$fecha_termino = $this->request->query['fecha_termino'];
				$url .= "&fecha_termino=$fecha_termino";
			}
			
			if (isset($this->request->query['fecha_termino_termino']) && $this->request->query['fecha_termino_termino'] != "") {
				$fecha_termino_termino = $this->request->query['fecha_termino_termino'];
				$url .= "&fecha_termino_termino=$fecha_termino_termino";
			}
			
			if (isset($this->request->query['tipo_intervencion']) && $this->request->query['tipo_intervencion'] != "") {
				$tipo_intervencion = $this->request->query['tipo_intervencion'];
				$url .= "&tipo_intervencion=$tipo_intervencion";
			}
			
			if (isset($this->request->query['estado']) && $this->request->query['estado'] != "0") {
				$query = "Planificacion.estado = " . intval($this->request->query['estado']) . " AND ";
				$estado = $this->request->query['estado'];
				$url .= "&estado=$estado";
			}
			
			if (isset($this->request->query['sid']) && $this->request->query['sid'] != "") {
				$query = "Planificacion.aprobador_id = " . intval($this->request->query['sid']) . " AND ";
				$sid = $this->request->query['sid'];
				$url .= "&sid=$sid";
			}
			
			if (isset($this->request->query['correlativo']) && $this->request->query['correlativo'] != "") {
				$query = "Planificacion.correlativo = " . intval($this->request->query['correlativo']) . " AND ";
				$correlativo = $this->request->query['correlativo'];
				$url .= "&correlativo=$correlativo";
			}
			
			if (isset($this->request->query['flota_id_h']) && $this->request->query['flota_id_h'] != "" && $this->request->query['flota_id_h'] != "undefined") {
				$query .= "Planificacion.flota_id = " . intval($this->request->query['flota_id_h']) . " AND ";
				$flota_id = $this->request->query['flota_id_h'];
				$url .= "&flota_id_h=$flota_id";
			}
			
			if (isset($this->request->query['unidad_id_h']) && $this->request->query['unidad_id_h'] != "" && $this->request->query['unidad_id_h'] != "undefined") {
				$query .= "Planificacion.unidad_id = " . intval($this->request->query['unidad_id_h']) . " AND ";
				$unidad_id = $this->request->query['unidad_id_h'];
				$url .= "&unidad_id_h=$unidad_id";
			}
			
			if (isset($this->request->query['codigo']) && $this->request->query['codigo'] != "" && $this->request->query['codigo'] != "undefined") {
				$query .= "CAST(Planificacion.id AS TEXT) LIKE '" . intval($this->request->query['codigo']) . "%' AND ";
				$codigo = $this->request->query['codigo'];
				$url .= "&codigo=$codigo";
			}
			
			if (isset($this->request->query['tipo_terminado']) && $this->request->query['tipo_terminado'] != "") {
				if($this->request->query['tipo_terminado']=='T'){
					$query .= "(Planificacion.hijo IS NULL OR Planificacion.hijo='') AND ";
				}elseif($this->request->query['tipo_terminado']='P'){
					$query .= "(Planificacion.hijo IS NOT NULL AND Planificacion.hijo<>'') AND ";
				}
				$tipo_terminado = $this->request->query['tipo_terminado'];
				$url .= "&tipo_terminado=$tipo_terminado";
			}
			
			// Fix tipo de evento y tipo de intervencion
			switch ($tipo_evento) {
				case "": // Todos
					break;
				case "PR": // Programados
					switch ($tipo_intervencion) {
						case "": // Todos
							$query .= "UPPER(Planificacion.tipointervencion) IN ('MP', 'RP', 'BL') AND ";
							break;
						default: // MP, RP y BL
							$query .= "UPPER(Planificacion.tipointervencion) = '" . $tipo_intervencion . "' AND ";
							break;
					}
					break;
				case "NP": // No programados
					switch ($tipo_intervencion) {
						case "": // Todos
							$query .= "UPPER(Planificacion.tipointervencion) IN ('EX', 'RI') AND ";
							break;
						default: // EX y RI
							$query .= "UPPER(Planificacion.tipointervencion) = '" . $tipo_intervencion . "' AND ";
							break;
					}
					break;
			}
		}
		
		if ($faena_id != 0) {
			$faena = "Planificacion.faena_id = $faena_id";
		} else {
			$faena = 'Planificacion.faena_id <> 0';
		}
		$this->set('tipo_terminado', $tipo_terminado);
		if (isset($this->request->query["order"])) {
			$order2 = split(":", $this->request->query["order"]);
			$order = "Planificacion.".$order2[0] . " " . $order2[1];
		
			if ($order2[0] == "tipointervencion") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Planificacion.tipointervencion' => $order2[1],'Planificacion.padre' => 'asc');
			}
			
			if ($order2[0] == "unidad_id") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Unidad.unidad' => $order2[1]);
			}
			
			if ($order2[0] == "flota_id") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Flota.nombre' => $order2[1]);
			}
			
			if ($order2[0] == "estado") {
				$order = array('Estado.nombre' => $order2[1]);
			}
			
			if ($order2[0] == "faena_id") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Faena.nombre' => $order2[1]);
			}
			
			if ($order2[0] == "dcc") {
				$order = array('Planificacion.tiempo_dcc' => $order2[1]);
			}
			
			if ($order2[0] == "oem") {
				$order = array('Planificacion.tiempo_oem' => $order2[1]);
			}
			
			if ($order2[0] == "aprobacion") {
				$order = array('Planificacion.fecha_aprobacion' => $order2[1]);
			}
			
			if ($order2[0] == "mina") {
				$order = array('Planificacion.tiempo_mina' => $order2[1]);
			}
			
			if ($order2[0] == "supervisor") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Supervisor.nombres' => $order2[1], 'Supervisor.apellidos' => $order2[1]);
			}
			
			if ($order2[0] == "fecha") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = "Planificacion.fecha {$order2[1]}, Planificacion.hora {$order2[1]}";	
			}
			if ($order2[0] == "duracion") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = " Planificacion.tiempo_dcc+Planificacion.tiempo_oem+Planificacion.tiempo_mina {$order2[1]}";
				//$order = "(DATE_PART('day', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) * 24 * 60 + ";
			//	$order .= "(DATE_PART('hour', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) * 60 + ";
				//$order .= "(DATE_PART('minute', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) {$order2[1]}";
			}
		} else {
			$order = "Planificacion.id ASC";	
		}
		
		$this->loadModel('Planificacion');
		
		/*$intervenciones = $this->Planificacion->find('all', array(
													'fields' => array('Planificacion.*', 'Unidad.unidad', 'Flota.nombre', 'Sintoma.nombre', 'Faena.nombre'),
													'order' => $order, 
													'conditions' => array(
													"not" => array("Planificacion.fecha" => null),
													"not" => array("Planificacion.faena_id" => null),
													"not" => array("Planificacion.flota_id" => null),
													"not" => array("Planificacion.unidad_id" => null),
													"Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_inicio_termino'",
													"Planificacion.fecha_termino BETWEEN '$fecha_termino' AND '$fecha_termino_termino'",
													$faena,
													"$query 1 = 1"),
													'recursive' => 1));*/
							
		$this->paginate = array(
			'limit' => 25,
			'fields' => array('Planificacion.*', 'Unidad.unidad', 'Flota.nombre', 'Sintoma.nombre', 'Faena.nombre','Estado.nombre'),
				'order' => $order, 
				'conditions' => array(
				"not" => array("Planificacion.fecha" => null),
				"not" => array("Planificacion.faena_id" => null),
				"not" => array("Planificacion.flota_id" => null),
				"not" => array("Planificacion.unidad_id" => null),
				"not" => array("Planificacion.correlativo" => null),
				"Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_inicio_termino'",
				"Planificacion.fecha_aprobacion BETWEEN '$fecha_termino 00:00:00' AND '$fecha_termino_termino 23:59:59'",
				$faena,
				"$query 1 = 1"),
			'recursive' => 1);
		$intervenciones = $this->paginate('Planificacion');
							
		//$intervenciones = $this->Planificacion->find('all', array('order' => array('Planificacion.fecha desc', 'Planificacion.hora desc'), 'conditions' => array('estado' => 4, "$faena"), 'recursive' => -1));
		
		// Calculo de Delta y Responsables
		/*
		<option value="1">DCC</option>
		<option value="2">OEM</option>
		<option value="3">MINA</option>
		*/
		
		$datos_delta = array();
		
		//foreach ($intervenciones as $intervencion) {
			/*$json = json_decode($intervencion["Planificacion"]["json"], true);
			$fi=strtotime($intervencion['Planificacion']['fecha'] . ' ' .$intervencion['Planificacion']['hora']);
			$ft=strtotime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
			$tiempos = array();
			$tiempos[1] = 0;
			$tiempos[2] = 0;
			$tiempos[3] = 0;
			if(strtoupper($intervencion["Planificacion"]["tipointervencion"])=='MP'){
				$tiempos[1]=$ft-$fi;
			}else{
				foreach ($json as $key => $value) {
					if((substr($key,0,2)=='d_'||substr($key,0,3)=='d2_'||substr($key,0,3)=='d3_'||substr($key,0,3)=='d4_')&&$this->endsWith($key, "_r")&&intval($value)>0){
						$hora = preg_replace("/_r$/","_h",  $key);
						$minuto = preg_replace("/_r$/", "_m", $key);
						$tiempos[intval($value)] += intval($json[$hora]) * 60 + intval($json[$minuto]);
					}
				}
			}
			
			// Se hace una verificacion extra, DCC + KCH + MINA = TTOTAL => DCC = TTOTAL - KCH - MINA
			/*$tiempo_total = $intervencion['Planificacion']['tiempo_trabajo'];
			$tiempo_total = split(":", $tiempo_total);
			$tiempo_total = $tiempo_total[0] * 60 + $tiempo_total[1];
			$tiempo_dcc = $tiempo_total - $tiempos[2] - $tiempos[3];*/
			/*$tiempos[1] = sprintf("%02d", floor($tiempos[1] / 60)) . ":" . sprintf("%02d", $tiempos[1] % 60);
			$tiempos[2] = sprintf("%02d", floor($tiempos[2] / 60)) . ":" . sprintf("%02d", $tiempos[2] % 60);
			$tiempos[3] = sprintf("%02d", floor($tiempos[3] / 60)) . ":" . sprintf("%02d", $tiempos[3] % 60);
			$datos_delta[intval($intervencion["Planificacion"]["id"])] = $tiempos;*/
			//print_r($tiempos);
		//}
		$this->set('datos_delta', $datos_delta);
		$this->set('intervenciones', $intervenciones);
		$this->set('titulo', 'Intervenciones aprobadas');
		if ($faena_id != 0) {
			$faena = "faena_id = $faena_id";
		} else {
			$faena = 'faena_id <> 0';
		}
		$this->loadModel('UnidadDetalle');
		$this->loadModel('Usuario');
		$this->loadModel('UsuarioFaena');
		$flotas = $this->UnidadDetalle->find('all', array('fields'=>array('DISTINCT flota_id','flota'), 'order' => 'flota', 'conditions' => array($faena), 'recursive' => -1));
		
		if($faena_id!=0){
			$supervisores=$this->UsuarioFaena->find('all', array('fields'=>array('Usuario.*'), 'order' =>  array('Usuario.nombres'=>'asc', 'Usuario.apellidos'=>'asc'), 'conditions' => array("(Usuario.nivelusuario_id IN (2) AND UsuarioFaena.faena_id=$faena_id and UsuarioFaena.e='1')"), 'recursive' => 1));
			//$supervisores=$this->Usuario->find('all', array('order' => array('nombres'=>'asc', 'apellidos'=>'asc'), 'conditions' => array('nivelusuario_id IN (2,4)'), 'recursive' => -1));
		}else{
			//$supervisores=$this->Usuario->find('all', array('order' => array('nombres'=>'asc', 'apellidos'=>'asc'), 'conditions' => array('nivelusuario_id IN (2,4)'), 'recursive' => -1));
			$supervisores=$this->UsuarioFaena->find('all', array('fields'=>array('Usuario.*'), 'order' =>  array('Usuario.nombres'=>'asc', 'Usuario.apellidos'=>'asc'), 'conditions' => array("(Usuario.nivelusuario_id IN (2) AND UsuarioFaena.faena_id<>0 and UsuarioFaena.e='1')"), 'recursive' => 1));
		}
		$this->set('flotas', $flotas);
		$this->set('fecha_inicio', $fecha_inicio);
		$this->set('fecha_inicio_termino', $fecha_inicio_termino);
		$this->set('fecha_termino', $fecha_termino);
		$this->set('fecha_termino_termino', $fecha_termino_termino);
		$this->set('flota_id', $flota_id);
		$this->set('sid',$sid);
		$this->set('unidad_id', $unidad_id);
		$this->set('flota_id', $flota_id);
		$this->set('flotas', $flotas);
		$this->set('estado', $estado);
		$this->set('url', $url);
		$this->set('tipo_intervencion', $tipo_intervencion);
		$this->set('fecha_inicio', $fecha_inicio);
		$this->set('fecha_inicio_termino', $fecha_inicio_termino);
		$this->set('fecha_termino', $fecha_termino);
		$this->set('fecha_termino_termino', $fecha_termino_termino);
		$this->set('tipo_evento', $tipo_evento);
		$this->set('codigo', $codigo);
		$this->set('correlativo', $correlativo);
		$this->set('supervisores',$supervisores);
	}
	
	/*
		Este metodo define el despliegue del historial de intervenciones
	*/
	public function historial() {
		$this->loadModel('Planificacion');
		$this->loadModel('Flota');
		$intervenciones = array();
		$usuario = $this->Session->read('Usuario');
		$faena_id = $this->Session->read('faena_id');
		$usuario = $this->Session->read('Usuario');
		$faena_id = $this->Session->read('faena_id');
		if ($faena_id != 0) {
			$faena = "Planificacion.faena_id = $faena_id";
		} else {
			$faena = 'Planificacion.faena_id <> 0';
		}
		$fecha = date('Y-m-d');
		$fecha_termino = date('Y-m-d');
		$query = "";
		$correlativo="";
		$estado = "0";
		$tipo_intervencion = "";
		$flota_id = "";
		$unidad_id = "";
		$sid="";
		$query = "";
		$estado = "0";
		$tipo_intervencion = "";
		$flota_id = "";
		$unidad_id = "";
		$tipo_evento = "";
		$order = "";
		$codigo = "";
		$fecha_inicio =  date('Y-m-d', time() - 365*24*60*60);
		$fecha_inicio_termino = date('Y-m-d');
		$fecha_termino = $fecha_inicio;
		$fecha_termino_termino = $fecha_inicio_termino;
		
		$query = "Planificacion.estado IN (4,5,6) AND ";
		$url = "1";
		if (isset($this->request->query) && count($this->request->query) > 0) {
			if (isset($this->request->query['tipo_evento']) && $this->request->query['tipo_evento'] != "") {
				$tipo_evento = $this->request->query['tipo_evento'];
				$url .= "&tipo_evento=$tipo_evento";
			}
			
			if (isset($this->request->query['fecha_inicio']) && $this->request->query['fecha_inicio'] != "") {
				$fecha_inicio = $this->request->query['fecha_inicio'];
				$url .= "&fecha_inicio=$fecha_inicio";
			}
			
			if (isset($this->request->query['fecha_inicio_termino']) && $this->request->query['fecha_inicio_termino'] != "") {
				$fecha_inicio_termino = $this->request->query['fecha_inicio_termino'];
				$url .= "&fecha_inicio_termino=$fecha_inicio_termino";
			}
			
			if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != "") {
				$fecha_termino = $this->request->query['fecha_termino'];
				$url .= "&fecha_termino=$fecha_termino";
			}
			
			if (isset($this->request->query['correlativo']) && $this->request->query['correlativo'] != "") {
				$query = "Planificacion.correlativo = " . intval($this->request->query['correlativo']) . " AND ";
				$correlativo = $this->request->query['correlativo'];
				$url .= "&correlativo=$correlativo";
			}
			
			if (isset($this->request->query['fecha_termino_termino']) && $this->request->query['fecha_termino_termino'] != "") {
				$fecha_termino_termino = $this->request->query['fecha_termino_termino'];
				$url .= "&fecha_termino_termino=$fecha_termino_termino";
			}
			
			if (isset($this->request->query['tipo_intervencion']) && $this->request->query['tipo_intervencion'] != "") {
				$tipo_intervencion = $this->request->query['tipo_intervencion'];
				$url .= "&tipo_intervencion=$tipo_intervencion";
			}
			
			if (isset($this->request->query['estado']) && $this->request->query['estado'] != "0") {
				$query = "Planificacion.estado = " . intval($this->request->query['estado']) . " AND ";
				$estado = $this->request->query['estado'];
				$url .= "&estado=$estado";
			}
			
			if (isset($this->request->query['flota_id_h']) && $this->request->query['flota_id_h'] != "" && $this->request->query['flota_id_h'] != "undefined") {
				$query .= "Planificacion.flota_id = " . intval($this->request->query['flota_id_h']) . " AND ";
				$flota_id = $this->request->query['flota_id_h'];
				$url .= "&flota_id_h=$flota_id";
			}
			
			if (isset($this->request->query['unidad_id_h']) && $this->request->query['unidad_id_h'] != "" && $this->request->query['unidad_id_h'] != "undefined") {
				$query .= "Planificacion.unidad_id = " . intval($this->request->query['unidad_id_h']) . " AND ";
				$unidad_id = $this->request->query['unidad_id_h'];
				$url .= "&unidad_id_h=$unidad_id";
			}
			
			if (isset($this->request->query['sid']) && $this->request->query['sid'] != "") {
				$query = "Planificacion.aprobador_id = " . intval($this->request->query['sid']) . " AND ";
				$sid = $this->request->query['sid'];
				$url .= "&sid=$sid";
			}
			
			if (isset($this->request->query['codigo']) && $this->request->query['codigo'] != "" && $this->request->query['codigo'] != "undefined") {
				$query .= "CAST(Planificacion.id AS TEXT) LIKE '" . intval($this->request->query['codigo']) . "%' AND ";
				$codigo = $this->request->query['codigo'];
				$url .= "&codigo=$codigo";
			}
			
			
			// Fix tipo de evento y tipo de intervencion
			switch ($tipo_evento) {
				case "": // Todos
					break;
				case "PR": // Programados
					switch ($tipo_intervencion) {
						case "": // Todos
							$query .= "UPPER(Planificacion.tipointervencion) IN ('MP', 'RP', 'BL') AND ";
							break;
						default: // MP, RP y BL
							$query .= "UPPER(Planificacion.tipointervencion) = '" . $tipo_intervencion . "' AND ";
							break;
					}
					break;
				case "NP": // No programados
					switch ($tipo_intervencion) {
						case "": // Todos
							$query .= "UPPER(Planificacion.tipointervencion) IN ('EX', 'RI') AND ";
							break;
						default: // EX y RI
							$query .= "UPPER(Planificacion.tipointervencion) = '" . $tipo_intervencion . "' AND ";
							break;
					}
					break;
			}
		}
		
		if (isset($this->request->query["order"])) {
			$order2 = split(":", $this->request->query["order"]);
			$order = "Planificacion.".$order2[0] . " " . $order2[1];
		
			if ($order2[0] == "tipointervencion") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Planificacion.tipointervencion' => $order2[1],'Planificacion.padre' => 'asc');
			}
			
			if ($order2[0] == "unidad_id") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Unidad.unidad' => $order2[1]);
			}
			
			if ($order2[0] == "flota_id") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Flota.nombre' => $order2[1]);
			}
			
			if ($order2[0] == "aprobacion") {
				$order = array('Planificacion.fecha_aprobacion' => $order2[1]);
			}
			
			if ($order2[0] == "dcc") {
				$order = array('Planificacion.tiempo_dcc' => $order2[1]);
			}
			
			if ($order2[0] == "oem") {
				$order = array('Planificacion.tiempo_oem' => $order2[1]);
			}
			
			if ($order2[0] == "mina") {
				$order = array('Planificacion.tiempo_mina' => $order2[1]);
			}
			
			if ($order2[0] == "estado") {
				$order = array('Estado.nombre' => $order2[1]);
			}
			
			if ($order2[0] == "supervisor") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Supervisor.nombres' => $order2[1], 'Supervisor.apellidos' => $order2[1]);
			}
			
			if ($order2[0] == "faena_id") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Faena.nombre' => $order2[1]);
			}
			
			if ($order2[0] == "fecha") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = "Planificacion.fecha {$order2[1]}, Planificacion.hora {$order2[1]}";	
			}
			if ($order2[0] == "duracion") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				//$order = "(DATE_PART('day', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) * 24 * 60 + ";
				//$order .= "(DATE_PART('hour', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) * 60 + ";
				//$order .= "(DATE_PART('minute', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) {$order2[1]}";
				$order = " Planificacion.tiempo_dcc+Planificacion.tiempo_oem+Planificacion.tiempo_mina {$order2[1]}";
			}
		} else {
			$order = "Planificacion.id ASC";	
		}
		
		/*if (isset($this->request->data) && count($this->request->data) > 0) {
			$fecha = $this->request->data['fecha'];
			$fecha_termino = $this->request->data['fecha_termino'];
			
			if (isset($this->request->data['estado']) && $this->request->data['estado'] != "0") {
				$query .= "Planificacion.estado = " . intval($this->request->data['estado']) . " AND ";
				$estado = $this->request->data['estado'];
			} else {
				$query .= "Planificacion.estado IN (4, 5, 6) AND ";
			}
			
			if (isset($this->request->data['tipo_intervencion']) && $this->request->data['tipo_intervencion'] != "" && $this->request->data['tipo_intervencion'] != "undefined") {
				$query .= "UPPER(Planificacion.tipointervencion) = '" . $this->request->data['tipo_intervencion'] . "' AND ";
				$tipo_intervencion = $this->request->data['tipo_intervencion'];
			}
			
			if (isset($this->request->data['flota_id_h']) && $this->request->data['flota_id_h'] != "" && $this->request->data['flota_id_h'] != "undefined") {
				$query .= "Planificacion.flota_id = " . intval($this->request->data['flota_id_h']) . " AND ";
				$flota_id = $this->request->data['flota_id_h'];
			}
			
			if (isset($this->request->data['unidad_id_h']) && $this->request->data['unidad_id_h'] != "" && $this->request->data['unidad_id_h'] != "undefined") {
				$query .= "Planificacion.unidad_id = " . intval($this->request->data['unidad_id_h']) . " AND ";
				$unidad_id = $this->request->data['unidad_id_h'];
			}
		} else {
			$query .= "Planificacion.estado IN (4, 5, 6) AND ";
		}*/
		
		
		
		/*$intervenciones = $this->Planificacion->find('all', 
									array('order' => 'Planificacion.fecha', 'order' => 'hora', 
										  'fields' => array('Planificacion.*', 'Unidad.unidad', 'Flota.nombre', 'Sintoma.nombre', 'Faena.nombre'),
										  'conditions' => array("Planificacion.fecha BETWEEN '$fecha' AND '$fecha_termino'", 'Planificacion.'.$faena, "UPPER(Planificacion.tipointervencion) NOT IN ('EX', 'RI')", "$query 1 = 1"),
										  'recursive' => 1));*/
										  
		/*$intervenciones = $this->Planificacion->find('all', array(
													'fields' => array('Planificacion.*', 'Unidad.unidad', 'Flota.nombre', 'Sintoma.nombre', 'Faena.nombre'),
													'order' => $order,
													'conditions' => array(
													"not" => array("Planificacion.fecha" => null),
													"not" => array("Planificacion.faena_id" => null),
													"not" => array("Planificacion.flota_id" => null),
													"not" => array("Planificacion.unidad_id" => null),
													"Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_inicio_termino'",
													"Planificacion.fecha_termino BETWEEN '$fecha_termino' AND '$fecha_termino_termino'",
													$faena,
													"$query 1 = 1"),
													'recursive' => 1));*/
												
		$this->paginate = array(
			'limit' => 25,
			'fields' => array('Planificacion.*', 'Unidad.unidad', 'Flota.nombre', 'Sintoma.nombre', 'Faena.nombre','Estado.nombre'),
				'order' => $order,
				'conditions' => array(
				"not" => array("Planificacion.fecha" => null),
				"not" => array("Planificacion.faena_id" => null),
				"not" => array("Planificacion.flota_id" => null),
				"not" => array("Planificacion.unidad_id" => null),
				"Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_inicio_termino'",
				"Planificacion.fecha_aprobacion BETWEEN '$fecha_termino' AND '$fecha_termino_termino'",
				$faena,
				"$query 1 = 1"),
			'recursive' => 1);
		$intervenciones = $this->paginate('Planificacion');
													
		if ($faena_id != 0) {
			$faena = "faena_id = $faena_id";
		} else {
			$faena = 'faena_id <> 0';
		}
		$this->loadModel('UnidadDetalle');
		$flotas = $this->UnidadDetalle->find('all', array('fields'=>array('DISTINCT flota_id','flota'), 'order' => 'flota', 'conditions' => array($faena), 'recursive' => -1));
		$this->loadModel('Usuario');
		$supervisores=$this->Usuario->find('all', array('order' => array('nombres'=>'asc', 'apellidos'=>'asc'), 'conditions' => array('nivelusuario_id IN (2,4)'), 'recursive' => -1));
		$this->set('unidad_id', $unidad_id);
		$this->set('flota_id', $flota_id);
		$this->set('flotas', $flotas);
		$this->set('estado', $estado);
		$this->set('tipo_intervencion', $tipo_intervencion);
		$this->set('fecha', $fecha);
		$this->set('fecha_termino', $fecha_termino);
		$this->set('intervenciones', $intervenciones);
		$this->set('titulo', 'Registro de Intervenciones');
		
		$this->set('flotas', $flotas);
		$this->set('fecha_inicio', $fecha_inicio);
		$this->set('fecha_inicio_termino', $fecha_inicio_termino);
		$this->set('fecha_termino', $fecha_termino);
		$this->set('fecha_termino_termino', $fecha_termino_termino);
		$this->set('flota_id', $flota_id);
		$this->set('supervisores', $supervisores);
		$this->set('sid', $sid);
		$this->set('unidad_id', $unidad_id);
		$this->set('flota_id', $flota_id);
		$this->set('flotas', $flotas);
		$this->set('estado', $estado);
		$this->set('url', $url);
		$this->set('tipo_intervencion', $tipo_intervencion);
		$this->set('fecha_inicio', $fecha_inicio);
		$this->set('fecha_inicio_termino', $fecha_inicio_termino);
		$this->set('fecha_termino', $fecha_termino);
		$this->set('fecha_termino_termino', $fecha_termino_termino);
		$this->set('tipo_evento', $tipo_evento);
		$this->set('codigo', $codigo);
		$this->set('correlativo', $correlativo);
	}
	
	/*
		Este metodo define el funcionamiento de la aprovacion o rechazo de una intervencion por parte del supervisor cliente
	*/
	public function procesar() {
		if (count($this->request->data)) {
			$this->loadModel('Planificacion');
			$faena_id = $this->Session->read('faena_id');
			//$faena_id = $this->Session->read('faena_id');
			$intervencion = $this->Planificacion->find('first', array(
				'conditions' => array('Planificacion.id' => $this->request->data['id'])
			));
			// Se envia correo a Supervisor Cliente avisando de Aprobación
			// Se buscan los Supervisores DCC para enviar correo
			$this->loadModel('UsuarioFaena');
			$resultados = $this->UsuarioFaena->find('all', array(
				'conditions' => array('faena_id' => $faena_id, 'nivelusuario_id' => 2)
			));
			
			foreach ($resultados as $resultado) {	
				$correo = $resultado["Usuario"]["correo_electronico"];
				$Email = new CakeEmail();
				$Email->config('gmail');
				$Email->from(array('daniel@salmonsoftware.cl' => 'Alertas Bitácora DCC'));
				//$Email->to($correo);
				$Email->emailFormat('html');
				$intervencion['Planificacion']['comentario_cliente']= $this->request->data['comentario'];
				if (isset($this->request->data['cliente_aprobar'])) {
					$intervencion['Planificacion']['estado'] = 5;
					$this->Planificacion->save($intervencion['Planificacion']);
					$Email->subject('Intervención Aprobada por Cliente');
					//$Email->send('Estimado,<br /><br /> El cliente ha aprobado una interveción en su faena, puede revisarla haciendo <a href="http://cummins.salmonsoftware.cl/">click acá</a>.');
					$this->Session->setFlash('Intervención Aprobada, se ha enviado un correo con una alerta al Supervisor DCC!', 'guardar_exito');
				} elseif (isset($this->request->data['cliente_rechazar'])) {
					$intervencion['Planificacion']['estado'] = 6;
					$this->Planificacion->save($intervencion['Planificacion']);
					$Email->subject('Intervención Rechazada por Cliente');
					//$Email->send('Estimado,<br /><br /> El cliente ha rechazado una interveción en su faena, puede revisarla haciendo <a href="http://cummins.salmonsoftware.cl/">click acá</a>.');
					$this->Session->setFlash('Intervención rechazada, se ha enviado un correo con una alerta al Supervisor DCC!', 'guardar_exito');
				}
			}
			$this->redirect('/Cliente');
		}
	}
	
	function startsWith($haystack, $needle) {
		 $length = strlen($needle);
		 return (substr($haystack, 0, $length) === $needle);
	}

	function endsWith($haystack, $needle) {
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}
}

?>