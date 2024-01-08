<?php
App::uses('ConnectionManager', 'Model'); 
App::uses('File', 'Utility');
App::import('Controller', 'Utilidades');
/*
	Esta clase define el funcionamiento de la seccion de Planificacion
*/
class PlanificacionController extends AppController {
	/* Este metodo define el despliegue principal al planificar una nueva intervencion */
	public function index($id = "0") {
		$this->layout = 'metronic_principal';
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		$this->loadModel('Unidad');
		$this->loadModel('Planificacion');
		$this->loadModel('SintomaCategoria');
		$this->loadModel('UnidadDetalle');
		$usuario = $this->Session->read('Usuario');
		$faena_id = $this->Session->read('faena_id');
		
		if ($faena_id == 0) {
			$faenas = $this->Faena->find('list', array('fields'=>array('id','nombre'), 'order' => 'nombre', 'recursive' => -1));
			$this->set('faenas', $faenas);
		} else {
			$resultado = $this->Faena->find('first', array(
				'conditions' => array('id' => $faena_id),
				'recursive' => -1
			));
			$this->set('faena', $resultado['Faena']['nombre']);
		}
		
		if (!isset($this->request->query['fd']) || $this->request->query['fd'] == '') {
			$fd = date("Y-m-d");
		} else {
			$fd = $this->request->query['fd'];
		}
		
		$this->set('fecha_despliegue', $fd);
		$unidad = $this->Unidad->find('list', array('fields'=>array('id','unidad'), 'order' => 'unidad'));
		
		if ($faena_id != 0) {
			$faena = "Planificacion.faena_id = $faena_id";
		} else {
			$faena = 'Planificacion.faena_id <> 0';
		}
		
		if($id!='0'){
			$intervenciones = $this->Planificacion->find('all', array(
						'fields' => array('Planificacion.*'),
						'order' => 'Planificacion.id DESC',  
						'conditions' => array('Planificacion.estado' => 1, $faena, "Planificacion.usuario_id = {$usuario['id']}", "Planificacion.id<>$id"), 'recursive' => -1));
		}else{
			$intervenciones = $this->Planificacion->find('all', array(
						'fields' => array('Planificacion.*'),
						'order' => 'Planificacion.id DESC',  
						'conditions' => array('Planificacion.estado' => 1, $faena, "Planificacion.usuario_id = {$usuario['id']}"), 'recursive' => -1));
		}
		$sintomaCategoria = $this->SintomaCategoria->find('list', array('fields'=>array('SintomaCategoria.id','SintomaCategoria.nombre'), 'order' => 'SintomaCategoria.nombre', 'recursive' => -1));
		
		$this->set('sintomaCategoria', $sintomaCategoria);
		$this->set('intervenciones', $intervenciones);
		$this->set('titulo', 'Planificación');
		
		if ($id != "0") {
			$planificacion = $this->Planificacion->findById($id);
			$this->set('planificacion', $planificacion);
		} else {
			$this->set('planificacion', null);
		}
	}
	
	/* Este metodo crear una intervencion y la define como "Borrador" */
	public function agregar() {
		$this->loadModel('Planificacion');
		$fecha = $this->request->data("programacion_fecha");
		$hora = $this->request->data("programacion_hora").':'.$this->request->data("programacion_minuto").':00 '.$this->request->data("programacion_periodo");
		$tiempo_trabajo = str_pad($this->request->data("estimado_hora"), 2, "0", STR_PAD_LEFT).':'.str_pad($this->request->data("estimado_minuto"), 2, "0", STR_PAD_LEFT);
		
		$faena_id = $this->request->data("faena_id");
		$flota_id = $this->request->data("flota_id");
		$unidad_id = $this->request->data("unidad_id");
		$tecnico_principal = $this->request->data("usuario_id");
		$tipointervencion = $this->request->data("tipointervencion");
		$fecha2 = date("YmdHi",strtotime($fecha.' '.$hora));					
					
		$folio = $tipointervencion.$faena_id.$flota_id.$unidad_id.$tecnico_principal.$fecha2;
		
		$data = array(
			'flota_id' => $this->request->data("flota_id"),
			'faena_id' => $this->request->data("faena_id"),
			'esn' => $this->request->data("esn"),
			'usuario_id' => $this->request->data("usuario_id"),
			'estado' => $this->request->data("estado"),
			'unidad_id' => $this->request->data("unidad_id"),
			'tipointervencion' => $this->request->data("tipointervencion"),
			'backlog_id' => $this->request->data("backlog_id"),
			'tipomantencion' => $this->request->data("tipomantencion"),
			'sintoma_id' => $this->request->data("sintoma_id"),
			'fecha' => $fecha,
			'hora_termino' => $hora,
			'fecha_termino' => $fecha,
			'hora' => $hora,
			'fecha_planificacion' => $fecha,
			'hora_planificacion' => $hora,
			'tiempo_trabajo' => $tiempo_trabajo,
			'tiempo_estimado' => $tiempo_trabajo,
			'observacion' => $this->request->data("observacion"),
			'folio' => $folio,
			'correlativo' => $folio
		);
		try {
			if ($this->request->data("id") != null && $this->request->data("id") != '') {
				$data["id"] = $this->request->data("id");
				$this->Planificacion->save($data);
			} else {
				$this->Planificacion->create();
				$this->Planificacion->save($data);
			}
			$this->Session->setFlash('Borrador ingresado!', 'guardar_exito');
		} catch (Exception $ex) { 
			die($ex);
		}
		
		$this->redirect('/Planificacion/');
	}
	
	public function quitar() {
		if ($this->request->data('quitar') != null) {
			$this->Planificacion->delete($this->request->data('id'));
			$this->redirect('/Planificacion/');
		} elseif ($this->request->data('modificar') != null) {
			$this->redirect('/Planificacion/index/'.$this->request->data('id'));
		}
	}
	/* Este metodo muestra el historial de las planificaciones */
	public function historial() {
		$util = new UtilidadesController();
		$util->actualizar_correlativos();
		$db = ConnectionManager::getDataSource('default');
		$this->loadModel('Planificacion');
		$this->loadModel('Flota');
		$intervenciones = array();
		$usuario = $this->Session->read('Usuario');
		$faena_id = $this->Session->read('faena_id');
		$fecha = date('Y-m-d', time() - 365*24*60*60);
		$fecha_termino = date('Y-m-d');
		$query = "";
		// Si no es administrador, sacar eliminados
		if($this->Session->read('esAdmin')!='1'){
			$query = "Planificacion.estado <> 10 AND ";
		}
		$estado = "0";
		$tipo_intervencion = "";
		$flota_id = "";
		$unidad_id = "";
		$tipo_evento = "";
		$codigo = "";
		$correlativo="";
		$aprobador_id="";
		// Se limpia data, si la fecha es null se reasigna
		$db->query("UPDATE planificacion SET fecha_termino=fecha WHERE fecha_termino iS NULL;");
		$db->query("UPDATE planificacion SET hora_termino=hora WHERE hora_termino iS NULL;");
		$db->query("UPDATE planificacion SET padre='' WHERE padre IS NULL;");
		/*if (isset($this->request->query) && count($this->request->query)) {
			$fecha = $this->request->query['fecha'];
			$fecha_termino = $this->request->query['fecha'];
		}*/
		$url = "1";
		if (isset($this->request->query) && count($this->request->query) > 0) {
			if (isset($this->request->query['tipo_evento']) && $this->request->query['tipo_evento'] != "") {
				$tipo_evento = $this->request->query['tipo_evento'];
				$url .= "&tipo_evento=$tipo_evento";
			}
			
			if (isset($this->request->query['fecha']) && $this->request->query['fecha'] != "") {
				$fecha = $this->request->query['fecha'];
				$url .= "&fecha=$fecha";
			}
			
			if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != "") {
				$fecha_termino = $this->request->query['fecha_termino'];
				$url .= "&fecha_termino=$fecha_termino";
			}
			
			if (isset($this->request->query['tipo_intervencion']) && $this->request->query['tipo_intervencion'] != "") {
				$tipo_intervencion = $this->request->query['tipo_intervencion'];
				$url .= "&tipo_intervencion=$tipo_intervencion";
			}
			
			if (isset($this->request->query['aprobador_id']) && $this->request->query['aprobador_id'] != "") {
				$query = "Planificacion.aprobador_id = " . intval($this->request->query['aprobador_id']) . " AND ";
				$aprobador_id = $this->request->query['aprobador_id'];
				$url .= "&aprobador_id=$aprobador_id";
			}
			
			if (isset($this->request->query['correlativo']) && $this->request->query['correlativo'] != "") {
				$query = "CAST(Planificacion.correlativo_final AS TEXT) LIKE '" . intval($this->request->query['correlativo']) . "%' AND "; 
				$correlativo = $this->request->query['correlativo'];
				$url .= "&correlativo=$correlativo";
			}
			
			if (isset($this->request->query['estado']) && $this->request->query['estado'] != "0") {
				$query .= "Planificacion.estado = " . intval($this->request->query['estado']) . " AND ";
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
			
			if (isset($this->request->query['codigo']) && $this->request->query['codigo'] != "" && $this->request->query['codigo'] != "undefined") {
				$query .= "CAST(Planificacion.id AS TEXT) LIKE '" . intval($this->request->query['codigo']) . "%' AND ";
				$codigo = $this->request->query['codigo'];
				$url .= "&codigo=$codigo";
			}
			
			// Fix tipo de evento y tipo de intervencion
			switch ($tipo_evento) {
				case "": // Todos
					switch ($tipo_intervencion) {
						case "": // Todos
							$query .= "UPPER(Planificacion.tipointervencion) IN ('MP', 'RP', 'OP', 'BL' ,'RI', 'EX') AND ";
							break;
						default: // MP, RP y BL
							$query .= "UPPER(Planificacion.tipointervencion) = '" . $tipo_intervencion . "' AND ";
							break;
					}
					break;
				case "PR": // Programados
					switch ($tipo_intervencion) {
						case "": // Todos
							$query .= "UPPER(Planificacion.tipointervencion) IN ('MP', 'RP', 'OP') AND ";
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
			
			
			/*if (isset($this->request->data['tipo_evento']) && $this->request->data['tipo_evento'] != "" && $this->request->data['tipo_intervencion'] != "undefined") {
				$query .= "UPPER(Planificacion.tipointervencion) = '" . $this->request->data['tipo_intervencion'] . "' AND ";
				$tipo_intervencion = $this->request->data['tipo_intervencion'];
			}*/
			
			
			/*
			if (isset($this->request->data['tipo_intervencion']) && $this->request->data['tipo_intervencion'] != "" && $this->request->data['tipo_intervencion'] != "undefined") {
				$query .= "UPPER(Planificacion.tipointervencion) = '" . $this->request->data['tipo_intervencion'] . "' AND ";
				$tipo_intervencion = $this->request->data['tipo_intervencion'];
			}*/
		}
		
		if ($faena_id != 0) {
			$faena = "faena_id = $faena_id";
		} else {
			$faena = 'faena_id <> 0';
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
			
			if ($order2[0] == "fecha") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = "Planificacion.fecha {$order2[1]}, Planificacion.hora {$order2[1]}";	
			}
			
			if ($order2[0] == "esn") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Planificacion.esn' => $order2[1]);
			}
			
			if ($order2[0] == "faena") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Faena.nombre' => $order2[1]);
			}
			
			if ($order2[0] == "estado") {
				$order = array('Estado.nombre' => $order2[1]);
			}
			
			if ($order2[0] == "duracion") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = "(DATE_PART('day', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) * 24 * 60 + ";
				$order .= "(DATE_PART('hour', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) * 60 + ";
				$order .= "(DATE_PART('minute', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) {$order2[1]}";
			}
			
			if ($order2[0] == "supervisor") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Supervisor.nombres' => $order2[1], 'Supervisor.apellidos' => $order2[1]);
			}
			
			if ($order2[0] == "correlativo") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = "Planificacion.correlativo {$order2[1]}, Planificacion.id {$order2[1]}";	
			}
		} else {
			$order = "Planificacion.fecha ASC, Planificacion.hora ASC";	
		}
		
		/*$intervenciones = $this->Planificacion->find('all', 
									array('order' => $order, 
										  'fields' => array('Planificacion.*', 'Unidad.unidad', 'Flota.nombre', 'Sintoma.nombre', 'Faena.nombre'),
										  'conditions' => array("Planificacion.fecha BETWEEN '$fecha' AND '$fecha_termino'", 'Planificacion.'.$faena, "$query 1 = 1",
										  "not" => array("Planificacion.fecha" => null),
													"not" => array("Planificacion.faena_id" => null),
													"not" => array("Planificacion.flota_id" => null),
													"not" => array("Planificacion.unidad_id" => null)), 
										  'recursive' => 1));*/
										  
										  
		$this->paginate = array(
			'limit' => 25,
			'fields' => array('Planificacion.*', 'Unidad.unidad', 'Flota.nombre', 'Sintoma.nombre', 'Faena.nombre', 'Estado.nombre'),
			'conditions' => array("Planificacion.fecha BETWEEN '$fecha 00:00:00' AND '$fecha_termino 23:59:59'", 'Planificacion.'.$faena, "$query 1 = 1",
				"not" => array("Planificacion.fecha" => null),
				"not" => array("Planificacion.faena_id" => null),
				"not" => array("Planificacion.flota_id" => null),
				"not" => array("Planificacion.correlativo" => null),
				"not" => array("Planificacion.unidad_id" => null)),
			'order' => $order, 
			'recursive' => 1);
		$intervenciones = $this->paginate('Planificacion');
										  
		if (isset($this->request->query['espera']) && is_numeric($this->request->query['espera'])) {
			$fecha_actual = date_create(date("Y-m-d"));
			$espera = intval($this->request->query['espera']);
			$new_intervenciones = array();
			foreach ($intervenciones as $intervencion) {		
				$interval = date_diff(date_create($intervencion["Planificacion"]["fecha"]), $fecha_actual);
				//echo $interval->format("%d") . " ";
				if ($espera == 12) {
					if (intval($interval->format("%d")) == 1 || intval($interval->format("%d")) == 2) {
						$new_intervenciones[] = $intervencion;
					}
				} elseif ($espera == 0) {
					if (intval($interval->format("%d")) <= 0) {
						$new_intervenciones[] = $intervencion;
					}
				} elseif ($espera == 3) {
					if (intval($interval->format("%d")) >= 3) {
						//echo $interval->format("%d") . " ";
						$new_intervenciones[] = $intervencion;
					}
				}
			}
			$intervenciones = array();
			$intervenciones = $new_intervenciones;
		}
		
		$this->loadModel('UnidadDetalle');
		$this->loadModel('UsuarioFaena');
		$flotas = $this->UnidadDetalle->find('all', array('fields'=>array('DISTINCT flota_id','flota'), 'order' => 'flota', 'conditions' => array($faena), 'recursive' => -1));
		
		if($faena_id!=0){
			$supervisores=$this->UsuarioFaena->find('all', array('fields'=>array('Usuario.*'), 'order' =>  array('Usuario.nombres'=>'asc', 'Usuario.apellidos'=>'asc'), 'conditions' => array("(Usuario.nivelusuario_id IN (2) AND UsuarioFaena.faena_id=$faena_id and UsuarioFaena.e='1')"), 'recursive' => 1));
		}else{
			$supervisores=$this->UsuarioFaena->find('all', array('fields'=>array('Usuario.*'), 'order' =>  array('Usuario.nombres'=>'asc', 'Usuario.apellidos'=>'asc'), 'conditions' => array("(Usuario.nivelusuario_id IN (2) AND UsuarioFaena.faena_id<>0 and UsuarioFaena.e='1')"), 'recursive' => 1));
		}
		$this->set('supervisores',$supervisores);
		$this->set('unidad_id', $unidad_id);
		$this->set('flota_id', $flota_id);
		$this->set('flotas', $flotas);
		$this->set('estado', $estado);
		$this->set('url', $url);
		$this->set('tipo_intervencion', $tipo_intervencion);
		$this->set('fecha', $fecha);
		$this->set('fecha_termino', $fecha_termino);
		$this->set('tipo_evento', $tipo_evento);
		$this->set('codigo', $codigo);
		$this->set('intervenciones', $intervenciones);
		$this->set('correlativo', $correlativo);
		$this->set('aprobador_id', $aprobador_id);
		$this->set('titulo', 'Registro de Intervenciones');
		$this->render('historial');
	}
	
	/* DEPRECADO */
	public function export_xls() {
		die;
		$this->layout = null;
		$this->loadModel('Planificacion');
		$this->loadModel('Flota');
		$intervenciones = array();
		$usuario = $this->Session->read('Usuario');
		$faena_id = $this->Session->read('faena_id');
		$fecha = date('Y-m-d', time() - 7*24*60*60);
		$fecha_termino = date('Y-m-d');
		$query = "";
		$estado = "0";
		$tipo_intervencion = "";
		$flota_id = "";
		$unidad_id = "";
		$tipo_evento = "";
		$codigo = "";
		
		/*if (isset($this->request->query) && count($this->request->query)) {
			$fecha = $this->request->query['fecha'];
			$fecha_termino = $this->request->query['fecha'];
		}*/
		$url = "1";
		if (isset($this->request->query) && count($this->request->query) > 0) {
			if (isset($this->request->query['tipo_evento']) && $this->request->query['tipo_evento'] != "") {
				$tipo_evento = $this->request->query['tipo_evento'];
				$url .= "&tipo_evento=$tipo_evento";
			}
			
			if (isset($this->request->query['fecha']) && $this->request->query['fecha'] != "") {
				$fecha = $this->request->query['fecha'];
				$url .= "&fecha=$fecha";
			}
			
			if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != "") {
				$fecha_termino = $this->request->query['fecha_termino'];
				$url .= "&fecha_termino=$fecha_termino";
			}
			
			if (isset($this->request->query['tipo_intervencion']) && $this->request->query['tipo_intervencion'] != "") {
				$tipo_intervencion = $this->request->query['tipo_intervencion'];
				$url .= "&tipo_intervencion=$tipo_intervencion";
			}
			
			if (isset($this->request->query['estado']) && $this->request->query['estado'] != "0") {
				$query .= "Planificacion.estado = " . intval($this->request->query['estado']) . " AND ";
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
			
			
			/*if (isset($this->request->data['tipo_evento']) && $this->request->data['tipo_evento'] != "" && $this->request->data['tipo_intervencion'] != "undefined") {
				$query .= "UPPER(Planificacion.tipointervencion) = '" . $this->request->data['tipo_intervencion'] . "' AND ";
				$tipo_intervencion = $this->request->data['tipo_intervencion'];
			}*/
			
			
			/*
			if (isset($this->request->data['tipo_intervencion']) && $this->request->data['tipo_intervencion'] != "" && $this->request->data['tipo_intervencion'] != "undefined") {
				$query .= "UPPER(Planificacion.tipointervencion) = '" . $this->request->data['tipo_intervencion'] . "' AND ";
				$tipo_intervencion = $this->request->data['tipo_intervencion'];
			}*/
		}
		
		if ($faena_id != 0) {
			$faena = "faena_id = $faena_id";
		} else {
			$faena = 'faena_id <> 0';
		}
		
		if (isset($this->request->query["order"])) {
			$order = split(":", $this->request->query["order"]);
			$order = "Planificacion.".$order[0] . " " . $order[1];
		} else {
			$order = "Planificacion.fecha ASC, Planificacion.hora ASC";	
		}
		
		$intervenciones = $this->Planificacion->find('all', 
									array('order' => $order, 
										  'fields' => array('Planificacion.*', 'Unidad.unidad', 'Flota.nombre', 'Sintoma.nombre', 'Faena.nombre'),
										  'conditions' => array("Planificacion.fecha BETWEEN '$fecha' AND '$fecha_termino'", 'Planificacion.'.$faena, /*"UPPER(Planificacion.tipointervencion) NOT IN ('EX', 'RI')",*/ "$query 1 = 1"), 
										  'recursive' => 1));
		$this->set('intervenciones', $intervenciones);
	}
	
	public function resumen() {
		$this->loadModel('Planificacion');
		$this->loadModel('Trabajo');
		$intervenciones = array();
		$usuario = $this->Session->read('Usuario');
		$faena_id = $this->Session->read('faena_id');
		$this->set('desplegar', false);
		
		if ($faena_id != 0) {
			$faena = "Planificacion.faena_id = $faena_id";
		} else {
			$faena = 'Planificacion.faena_id <> 0';
		}
		
		//$trabajos = $this->Trabajo->find('all', array('order' => 'fecha', 'conditions' => array('faena_id' => $faena_id)));
		
		$trabajos = $this->Planificacion->find('all', array('fields' => 'Planificacion.fecha, COUNT(*) as trabajos, COUNT(CASE WHEN estado >= 3 THEN 1 END) as ejecucion, COUNT(CASE WHEN estado = 4 THEN 1 END) as aprobadodcc, COUNT(CASE WHEN estado = 5 THEN 1 END) as aprobadocliente, COUNT(CASE WHEN estado = 6 THEN 1 END) as rechazadocliente',
			//'order' => 'Planificacion.fecha DESC', 
			'order' => 'Planificacion.fecha DESC', 
			'conditions' => array($faena, 'Planificacion.fecha !=' => NULL,"UPPER(Planificacion.tipointervencion) NOT IN ('EX', 'RI')"),
			'group' => 'Planificacion.fecha'));

				
		$this->set('trabajos', $trabajos);
		$this->set('titulo', 'Resumen Planificaciones');
	}
	/* Este metodo guardar un conjunto de planificaciones, las pasa del estado "Borrador" a "Planificado" */
	public function guardar() {
		$this->loadModel('Planificacion');
		//$this->loadModel('Trabajo');
		$faena_id = intval($this->Session->read('faena_id'));
		$usuario = $this->Session->read('Usuario');
		if ($faena_id != 0) {
			$faena = "Planificacion.faena_id = $faena_id";
		} else {
			$faena = 'Planificacion.faena_id <> 0';
		}
		$intervenciones = $this->Planificacion->find('all', array('fields' => array("Planificacion.*", 'Backlog.*'), 'conditions' => array('estado' => 1, $faena, "Planificacion.usuario_id = {$usuario['id']}"), 'recursive' => 1));
		
		if (count($intervenciones) > 0) {
			foreach ($intervenciones as $intervencion) { 
				//$intervencion['Planificacion']['trabajoid'] = $trabajoid;
				$intervencion['Planificacion']['estado'] = 2;
				$intervencion['Planificacion']['correlativo_final'] = $intervencion['Planificacion']['id'];
				if (strtoupper($intervencion['Planificacion']['tipointervencion'] == "BL")) {
					$intervencion['Planificacion']['sintoma_id'] = $intervencion['Backlog']['sistema_id'];
				}
				$this->Planificacion->save($intervencion['Planificacion']);
				
				// Si es BL, se marca como planificado
				if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "BL") {
					if ($intervencion["Planificacion"]["backlog_id"] != NULL && is_numeric($intervencion["Planificacion"]["backlog_id"])) {
						$this->loadModel('Backlog');
						$resultado_bl = $this->Backlog->find('first', array(
							'conditions' => array('Backlog.id' => $intervencion["Planificacion"]["backlog_id"]),
							'recursive' => -1
						));
						$resultado_bl["Backlog"]["realizado"] = "S";
						$this->Backlog->save($resultado_bl["Backlog"]);
					}
				}
			}
		}
		
		if (count($intervenciones) == 1) {
			$this->Session->setFlash('Una intervención planificada!', 'guardar_exito');
		} else {
			$this->Session->setFlash(count($intervenciones) . ' intervenciones planificadas!', 'guardar_exito');
		}
		
		$this->redirect('/Planificacion/');
	}
	
	public function elint($folio) {
		$usuario = $this->Session->read('Usuario');
		if ($usuario["nivelusuario_id"] == '4') {
			$this->loadModel('Planificacion');
			$intervencion = $this->Planificacion->find('first', array('fields' => array("Planificacion.*"), 'conditions' => array('id' => $folio), 'recursive' => -1));
			$intervencion = $intervencion["Planificacion"];
			$folio = $intervencion["id"];
			
			// Restricciones de borrado
			// Borrado en cualquier estado
			// Borrado por etapa, 1. replanifica, elimina
			// Si se trata de eliminar una intervencion planificada con un padre activo, no dejar eliminar.
			// Eliminar intervencion tipo padre padre
			if ($intervencion["padre"] == '' || $intervencion["padre"] == NULL) {
				// Borrar todos los hijos
				if ($intervencion["hijo"] != '' && $intervencion["hijo"] != NULL) {
					$this->elhijos($intervencion["hijo"]);
				}
				// programado
				if ($intervencion["tipointervencion"] != 'EX' && $intervencion["tipointervencion"] != 'RI') {
					// Reprograma misma intervención
					// generar planificacion con fecha inicial de planificacion / generar columna nueva
					// genera nuevo folio y correlativo
					if ($intervencion["estado"] != '2') {
						unset($intervencion["id"]);
						$intervencion["hijo"] = NULL;
						$intervencion["correlativo"] = NULL;
						$intervencion["padre"] = NULL;
						$intervencion["json"] = NULL;
						$intervencion["estado"] = 2;
						$intervencion["terminado"] = 'N';
						$intervencion["tiempo_trabajo"] = "00:00";
						
						if ($intervencion["fecha_planificacion"] != NULL && $intervencion["hora_planificacion"] != NULL) {
							$intervencion["fecha"] = $intervencion["fecha_planificacion"];
							$intervencion["hora"] = $intervencion["hora_planificacion"];
						}
						
						$intervencion["fecha_termino"] = $intervencion["fecha"];
						$intervencion["hora_termino"] = $intervencion["hora"];
						
						@$this->Planificacion->create();
						@$this->Planificacion->save($intervencion);
						$nfolio = 0;
						if (isset($this->Planificacion->id)) {
							$nfolio = @$this->Planificacion->id;
						}
						$data = array (
							'id' => $folio,
							'correlativo'=>null,
							'estado' => 10,
							'terminado' => 'S'
						);
						$this->Planificacion->save($data);
						$this->Session->setFlash("La intervención con folio $folio ha sido eliminada y replanificada con folio $nfolio!", 'guardar_exito');
					} else {
						$data = array (
							'id' => $folio,
							'correlativo'=>null,
							'estado' => 10,
							'terminado' => 'S'
						);
						$this->Planificacion->save($data);
						$this->Session->setFlash("Intervención con folio $folio eliminada y no fue replanificada!", 'guardar_exito');
					}
					$this->redirect('/Planificacion/historial');
					return true;
				} else {
					// Se borra intervencion completa
					// NO programado
					// NO Reprograma misma intervención
					// NO generar planificacion con fecha inicial de planificacion / generar columna nueva
					// NO genera nuevo folio y correlativo
					$data = array (
						'id' => $folio,
						'correlativo'=>null,
						'estado' => 10,
						'terminado' => 'S'
					);
					$this->Planificacion->save($data);
					$this->Session->setFlash("Intervención con folio $folio eliminada y no fue replanificada!", 'guardar_exito');
					$this->redirect('/Planificacion/historial');
					return true;
				}
				
			} else {
				// 	Eliminar un hijo n, no puede ser planificado
				// estado = 2
				if ($intervencion["estado"] == '2') {
					$this->Session->setFlash("No se puede eliminar continuaciones planificadas!", 'guardar_exito');
					$this->redirect('/Planificacion/historial');
					return true;
				} else {				
					if ($intervencion["hijo"] != '' && $intervencion["hijo"] != NULL) {
						$this->elhijos($intervencion["hijo"]);
					}
					$intervencion_padre = $this->Planificacion->find('first', array('fields' => array("Planificacion.*"), 'conditions' => array('hijo' => $intervencion["padre"]), 'recursive' => -1));
					$intervencion_padre = $intervencion_padre["Planificacion"];
					unset($intervencion_padre["id"]);
					$intervencion_padre["padre"] = $intervencion["padre"];
					$intervencion_padre["hijo"] = NULL;
					$intervencion_padre["correlativo"] = NULL;
					$intervencion_padre["estado"] = 2;
					$intervencion_padre["terminado"] = 'N';
					$intervencion_padre["tiempo_trabajo"] = "00:00";
					$intervencion_padre["fecha"] = $intervencion_padre["fecha_termino"];
					$intervencion_padre["hora"] = $intervencion_padre["hora_termino"];
					$intervencion_padre["fecha_planificacion"] = $intervencion_padre["fecha_termino"];
					$intervencion_padre["hora_planificacion"] = $intervencion_padre["hora_termino"];
					$this->Planificacion->create();
					@$this->Planificacion->save($intervencion_padre);
					$nfolio = 0;
					if (isset($this->Planificacion->id)) {
						$nfolio = @$this->Planificacion->id;
					}
					$data = array (
						'id' => $folio,
						'correlativo'=>null,
						'estado' => 10,
						'terminado' => 'S'
					);
					$this->Planificacion->save($data);
					$this->Session->setFlash("La intervención con folio $folio ha sido eliminada y replanificada con folio $nfolio!", 'guardar_exito');
					$this->redirect('/Planificacion/historial');
					return true;	
				}
			}
		} else {
			$this->Session->setFlash("No tiene permisos para eliminar la intervención!", 'guardar_exito');
		}
		$this->redirect('/Planificacion/historial');
	}
	
	/*Metodo para buscar si la intervención tiene hijos*/
	public function elhijos($h) {
		$this->loadModel('Planificacion');
		$intervencion = $this->Planificacion->find('first', array('fields' => array("id", "padre", "hijo"), 'conditions' => array('padre' => $h), 'recursive' => -1));
		if (isset($intervencion) && isset($intervencion["Planificacion"]) && isset($intervencion["Planificacion"]["id"])) {
			$data = array (
				'id' => $intervencion["Planificacion"]["id"],
				'estado' => 10,
				'correlativo'=>null,
				'terminado' => 'S'
			);
			$this->Planificacion->save($data);
			if ($intervencion["Planificacion"]["hijo"] != '' && $intervencion["Planificacion"]["hijo"] != null) {
				$this->elhijos($intervencion["Planificacion"]["hijo"]);
			}
		}
	}
	
	/*Metodo que obtiene contenido para impresión*/
	public function p($folio="") {
		if($folio==""){return false;}
		$this->layout=null;
		$this->loadModel('Planificacion');
		$i=$this->Planificacion->find('first', array('conditions' => array('Planificacion.id' => $folio), 'recursive' => 1));
		$this->set('intervencion', $i);
	}
	
	
}
?>