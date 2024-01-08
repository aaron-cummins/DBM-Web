<?php
App::uses('ConnectionManager', 'Model'); 
App::import('Controller', 'Utilidades');

class AdministracionController extends AppController {	
	public function usuarios() {
		$this->loadModel('Usuario');
		$this->loadModel('Faena');
		$this->loadModel('UsuarioFaena');
		if (isset($this->request->data) && count($this->request->data) > 0) {
			if (isset($this->request->data["s"]) && is_array($this->request->data["s"])) {
				foreach ($this->request->data["s"] as $key => $value) {
					$value = split("_", $value);
					if ($value[1] == '0') {
						$data = array('id' => $value[0], 'e' => '0');
						$this->Usuario->save($data); 
					}
				}
			}
			if (isset($this->request->data["r"]) && is_array($this->request->data["r"])) {
				foreach ($this->request->data["r"] as $key => $value) {
					$value = split("_", $value);
					if ($value[1] == '0') {
						$data = array('id' => $value[0], 'e' => '1');
						$this->Usuario->save($data); 
					}
				}
			} 
		}
		$resultado = $this->Usuario->find('all', array(
			'fields' => array('Usuario.*', 'NivelUsuario.nombre'),
			'limit' => 200,
			'recursive' => 1
		));
		$this->set('usuarios', $resultado);
		$faenas = array();
		$resultado = $this->UsuarioFaena->find('all', array(
			'order' => 'Faena.nombre ASC', 
			'recursive' => 1
		));
		foreach($resultado as $val) {
			$faenas[$val["Usuario"]["id"]][] = $val["Faena"]["nombre"];
		}
		$this->set('faenas', $faenas);
		//$this->set('select_faena', $this->Faena->find('list', array('recursive' => -1)));
		$this->set('faena_id', @$this->request->data["faena_id"]);
		$this->set('perfil_id', @$this->request->data["perfil_id"]);
	}
	
	public function eliminarfolioform($folios) {
		$this->layout = null;
		$this->loadModel('Planificacion');
		$this->loadModel('Backup');
		$foliosa = explode(",",$folios);
		foreach($foliosa as $folio){
			$resultado = $this->Planificacion->find('first', array(
				'fields' => array('Planificacion.*'),
				'conditions' => "Planificacion.id=$folio", 
				'recursive' => -1
			));
			if(isset($resultado["Planificacion"]["id"])){
				$this->Backup->create();
				$this->Backup->save($resultado["Planificacion"]);
				$this->Planificacion->delete($resultado["Planificacion"]["id"]);
				$resultado2 = $this->Planificacion->find('first', array(
					'fields' => array('id','padre'),
					'conditions' => "Planificacion.padre = '{$resultado["Planificacion"]["folio"]}'", 
					'recursive' => -1
				));
				if(isset($resultado2["Planificacion"]["id"])){
					$this->eliminarfolio($resultado2["Planificacion"]["id"]);
				}else{
					return;
				}
				return;
			}else{
				return;
			}
		}
	}
	
	public function eliminarfolio() {
		if ($this->request->data) {
			$folios = $this->request->data["folios"];
			$this->eliminarfolioform($folios);
			$foliosa = explode(",",$folios);
			if(count($foliosa)==1){
				$this->Session->setFlash("Folio $folios eliminado con éxito!", 'guardar_exito');
			}else{
				$this->Session->setFlash("Folios $folios eliminados con éxito!", 'guardar_exito');
			}
		}
		$this->redirect('/Administracion/fix_intervenciones/');
	}
	
	public function reiniciarfolio() {
		if ($this->request->data) {
			$this->loadModel('Planificacion');
			$this->loadModel('LogIntervencion');
			$folios = $this->request->data["folios"];
			$foliosa = explode(",",$folios);
			foreach($foliosa as $folio){
				$resultado = $this->LogIntervencion->find('first', array(
					'fields' => array('LogIntervencion.planificacion_id', 'LogIntervencion.json'),
					'conditions' => "LogIntervencion.planificacion_id=$folio", 
					'order' => 'LogIntervencion.id ASC', 
					'limit' => 1,
					'recursive' => -1
				));
				print_r($resultado);
				if(isset($resultado["LogIntervencion"]["planificacion_id"])){
					$interv = array();
					$interv["id"] = $resultado["LogIntervencion"]["planificacion_id"];
					$interv["json"] = $resultado["LogIntervencion"]["json"];
					$this->Planificacion->save($interv);
				}
			}
			if(count($foliosa)==1){
				$this->Session->setFlash("Folio $folios arreglado con éxito!", 'guardar_exito');
			}else{
				$this->Session->setFlash("Folios $folios arreglados con éxito!", 'guardar_exito');
			}
		}
		$this->redirect('/Administracion/fix_intervenciones/');
	}
	
	public function fix_intervenciones() {
	}
	
	
	public function fixelementos() {
		$this->layout = null;
		$this->loadModel('Planificacion');
		$this->loadModel('LogIntervencion');
		$resultado = $this->Planificacion->find('all', array(
			'fields' => array('id','estado','terminado','hijo','padre','json','tipointervencion'),
			'conditions' => "Planificacion.estado NOT IN (10,2) AND Planificacion.tipointervencion NOT IN ('EX','MP') AND Planificacion.json like '%,,,%'", 
			'order' => array('id DESC'),
			'recursive' => -1,
		));
		
		foreach($resultado as $intervencion) {
			$json_nuevo = json_decode($intervencion["Planificacion"]["json"],true);
			echo "Folio " . $intervencion["Planificacion"]["id"]. " | Tipo: ".$intervencion["Planificacion"]["tipointervencion"]." | ".$json_nuevo["fecha_inicio_g"]."<br />";
			//print_r($intervencion["Planificacion"]["json"]);
			$resultado2 = $this->LogIntervencion->find('first', array(
				'fields' => array('LogIntervencion.planificacion_id', 'LogIntervencion.json'),
				'conditions' => "LogIntervencion.planificacion_id={$intervencion["Planificacion"]["id"]}", 
				'limit' => 1,
				'recursive' => -1
			));
			
			$json_nuevo = json_decode($intervencion["Planificacion"]["json"],true);
			$cantidad = 0;
			$i = 1;
			
			while(isset($json_nuevo["elemento_$i"])){
			//	echo $json_nuevo["elemento_$i"];
			//	echo "<br />";
				$cantidad++;
				$i++;
			}
			
			if($cantidad != $json_nuevo["ele_cantidad"]){
			//	echo "<br />";
				//print_r($json_nuevo["ele_cantidad"]);
				//echo "<br />";
			//	print_r($cantidad);
				//echo "<br />";
				$i = 1;
				while(isset($json_nuevo["elemento_$i"])){
					//print_r($json_nuevo["elemento_$i"]);
					//echo "<br />";
					$i++;
				}
			}
			
			
			
			if (isset($resultado2["LogIntervencion"]["json"])){
				//echo "Existe modificacion<br />";
				/*$json_nuevo = json_decode($intervencion["Planificacion"]["json"],true);
				$json_original = json_decode($resultado2["LogIntervencion"]["json"],true);
				//print_r($resultado2["LogIntervencion"]["json"]);
				
				//echo "# de elementos " . $json_original["ele_cantidad"] ."<br />";
				$cambio=false;
				if(isset($json_original["ele_cantidad"])){
					echo "Elementos <br />";
					for ($i = 1; $i <= $json_original["ele_cantidad"]; $i++) {
						echo "{$json_original["elemento_$i"]} != {$json_nuevo["elemento_$i"]} NOK<br />";
						if (trim($json_original["elemento_$i"])!=trim($json_nuevo["elemento_$i"])){
							if (strpos($json_nuevo["elemento_$i"], ',,,') !== false) {
								//echo "{$json_original["elemento_$i"]} != {$json_nuevo["elemento_$i"]} NOK<br />";
								$json_nuevo["elemento_$i"] = $json_original["elemento_$i"];
								$cambio = true;
							}else{
								echo "{$json_original["elemento_$i"]} != {$json_nuevo["elemento_$i"]} OK <br />";
							}
						}
					}
				}
				if($cambio){
					$json_new = str_replace('\\"','',json_encode($json_nuevo));
					$save = array();
					$save["id"] = $intervencion["Planificacion"]["id"];
					$save["json"] = $json_new;
					//$this->Planificacion->save($save);
					//print_r($save);
				}*/
				//echo "<hr />";
			} else {
				//echo "No existe modificacion<br />";
				$json_nuevo = json_decode($intervencion["Planificacion"]["json"],true);
				$elementos = array();
				$elementos_m = array();
				$elementos_h = array();
				
				
				$ele_cantidad=0;
				for ($i = 1; $i <= $json_nuevo["ele_cantidad"]; $i++) {
					if (strpos($json_nuevo["elemento_$i"], ',,,') !== false) {
						//$j = $i + 1;
						//$json_nuevo["elemento_$i"] = @$json_nuevo["elemento_$j"];
						//$json_nuevo["d3_elemento_h_$i"] = @$json_nuevo["d3_elemento_h_$j"];
						//$json_nuevo["d3_elemento_m_$i"] = @$json_nuevo["d3_elemento_m_$j"];
					}else{
						$elementos[]=$json_nuevo["elemento_$i"];
						$elementos_h[]=$json_nuevo["d3_elemento_h_$i"];
						$elementos_m[]=$json_nuevo["d3_elemento_m_$i"];
						$ele_cantidad++;
					}
					unset($json_nuevo["elemento_$i"]);
					unset($json_nuevo["d3_elemento_h_$i"]);
					unset($json_nuevo["d3_elemento_m_$i"]);
				}
				
				
				
				
				if ($ele_cantidad > 0){
					//print("<pre>");
				//	print_r($elementos);
				//	print_r($elementos_h);
				//	print_r($elementos_m);
					//print("</pre>");
				
					for ($i = $ele_cantidad + 1 ; $i <= $json_nuevo["ele_cantidad"]; $i++) {
						unset($json_nuevo["elemento_$i"]);
						unset($json_nuevo["d3_elemento_h_$i"]);
						unset($json_nuevo["d3_elemento_m_$i"]); 
					}
					
					foreach($elementos as $key => $value){
						$i = $key + 1;
						$json_nuevo["elemento_$i"]=$value;
						$json_nuevo["d3_elemento_h_$i"]=$elementos_h[$key];
						$json_nuevo["d3_elemento_m_$i"]=$elementos_m[$key];
					}
					
				//	echo "# de elementos " . $json_nuevo["ele_cantidad"] ."<br />";
				//	echo "# de elementos nuevo " . $ele_cantidad."<br />";
					$json_nuevo["ele_cantidad"] = $ele_cantidad;
				echo "<pre>";
					print_r($json_nuevo);
					echo "</pre>";
					$json_new = str_replace('\\"','',json_encode($json_nuevo));
					$save = array();
					$save["id"] = $intervencion["Planificacion"]["id"];
					$save["json"] = $json_new;
					//$this->Planificacion->save($save);
				}
			}
		}
		
		//echo "<hr />";
		$resultado = $this->Planificacion->find('all', array(
			'fields' => array('id','estado','terminado','hijo','padre','json','fecha','hora'),
			'conditions' => "(Planificacion.estado = 7 OR Planificacion.estado = 4) AND Planificacion.hora = '01:00:00' and Planificacion.fecha IS NULL", 
			'recursive' => -1
		));
		
		foreach($resultado as $intervencion) {
			echo "Folio " . $intervencion["Planificacion"]["id"]. "<br />";
			//print_r($intervencion["Planificacion"]["json"]);
			$resultado2 = $this->LogIntervencion->find('first', array(
				'fields' => array('LogIntervencion.planificacion_id', 'LogIntervencion.json'),
				'conditions' => "LogIntervencion.planificacion_id={$intervencion["Planificacion"]["id"]}", 
				'order' => 'LogIntervencion.id ASC', 
				'limit' => 1,
				'recursive' => -1
			));
			
			if (isset($resultado2["LogIntervencion"]["json"])){
				echo "{$intervencion["Planificacion"]["id"]} Error encontrado!<br />";
				$json_original = json_decode($resultado2["LogIntervencion"]["json"],true);
				$inicio = explode(" ",$json_original["fecha_inicio_g"]);
				$termino = explode(" ",$json_original["fecha_termino_g"]);
				$save = array();
				$save["id"] = $intervencion["Planificacion"]["id"];
				$json_new = str_replace('\\"','',$resultado2["LogIntervencion"]["json"]);
				$save["json"] = $json_new;
				$save["hora"] = $inicio[1].':00 '.$inicio[2];
				$save["fecha"] = $inicio[0];
				$save["hora_termino"] = $termino[1].':00 '.$termino[2];
				$save["fecha_termino"] = $termino[0];
				//print_r($save);
				//$this->Planificacion->save($save);
			}
		}
	}
	
	public function Imagenes(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		
		if ($this->request->is('post')){
			try {
				$image_name = $this->request->data["image-name"];
				if(isset($this->request->data["file"])) {
					$data = $this->request->data["file"];
					$inputFileName = $data["tmp_name"];
					try {
						$newfile = '/var/www/html/dcc_agosto/app/webroot/images/motor/'.$image_name.'.png';
						copy($inputFileName, $image_name);
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar cargar el archivo, intente nuevamente.','guardar_error');
						return;
					}
				}
				$data = array();
				$data["motor_id"] = $this->request->data['motor_id'];
				$data["sistema_id"] = $this->request->data['sistema_id'];
				$data["subsistema_id"] = $this->request->data['subsistema_id'];
				$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("e" => '0', 'updated' => 'now()'), $data);
				foreach($this->request->data['elemento'] as $key => $value) {
					$data = array();
					$data["id"] = $key;
					$data["codigo_relacion"] = $value;
					$data["e"] = "1";
					$this->Sistema_Subsistema_Motor_Elemento->save($data);
					print_r($data);
				}
				
			} catch(Exception $e) {
				$this->Session->setFlash('Ocurrió un error al intentar actualizar la clave, intente nuevamente.','guardar_error');
			}
		}
		
		$conditions = array();
		if ($this->request->is('get')){
			if(isset($this->request->query['motor_id']) && is_numeric($this->request->query['motor_id'])) {
				$conditions["Motor.id"] = $this->request->query['motor_id'];
			}
			if(isset($this->request->query['sistema_id']) && is_numeric($this->request->query['sistema_id'])) {
				$conditions["Sistema.id"] = $this->request->query['sistema_id'];
			}
			if(isset($this->request->query['subsistema_id']) && is_numeric($this->request->query['subsistema_id'])) {
				$conditions["Subsistema.id"] = $this->request->query['subsistema_id'];
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		if(count($conditions)) {
			$conditions["codigo_relacion IS NOT"] = NULL;
 			$resultados = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
	 			'fields' => array('Sistema_Subsistema_Motor_Elemento.codigo','Elemento.nombre','Sistema_Subsistema_Motor_Elemento.e','Sistema_Subsistema_Motor_Elemento.codigo_relacion'),
				'conditions' => $conditions, 
				'recursive' => 1
			));
			$this->set(compact('resultados'));
			unset($conditions["codigo_relacion IS NOT"]);
			$conditions["codigo_relacion IS"] = NULL;
			$resultados_pendientes = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
				'fields' => array('Sistema_Subsistema_Motor_Elemento.id','Sistema_Subsistema_Motor_Elemento.codigo','Elemento.nombre','Sistema_Subsistema_Motor_Elemento.e','Sistema_Subsistema_Motor_Elemento.codigo_relacion'),
				'conditions' => $conditions, 
				'recursive' => 1
			));
			$this->set(compact('resultados_pendientes'));
			$image_source = $this->motorimagen($conditions["Motor.id"], $conditions["Sistema.id"], $conditions["Subsistema.id"]);
			$this->set(compact('image_source'));
		}
		
		//$this->set('motores', $this->Motor->find('all', array('order' => 'nombre','conditions'=>array("Motor.e='1'"), 'recursive' => -1)));
		
		$motores = $this->Motor->find('all', array(
			'fields' => array('Motor.id','Motor.nombre','TipoEmision.nombre'),
			'order' => array('Motor.nombre' => 'asc'),
			'recursive' => 1
		));
		$this->set(compact('motores'));
					
					
		$this->set('sistemas', $this->Sistema->find('all', array('order' => 'nombre','conditions'=>array("Sistema.e='1'"), 'recursive' => -1)));
		$this->set('subsistemas', $this->Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Subsistema.e='1'"), 'recursive' => -1)));
		
		// Se verifican alertas
		$script = array();
		$script[] = 'toastr.options = {
					  "closeButton": true,
					  "debug": false,
					  "positionClass": "toast-bottom-right",
					  "onclick": null,
					  "showDuration": "1000",
					  "hideDuration": "1000",
					  "timeOut": "50000",
					  "extendedTimeOut": "10000",
					  "showEasing": "swing",
					  "hideEasing": "linear",
					  "showMethod": "fadeIn",
					  "hideMethod": "fadeOut"
					}';
		
		$conditions = array();		
		$conditions["Sistema_Subsistema_Motor_Elemento.codigo_relacion IS"] = NULL;
		$conditions["Sistema_Subsistema_Motor_Elemento.e"] = "1";
		$resultados = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
			'fields' => array("Motor.nombre","Sistema.nombre","Subsistema.nombre","Motor.id","Sistema.id","Subsistema.id"),
			'group' => array("Motor.nombre","Sistema.nombre","Subsistema.nombre","Motor.id","Sistema.id","Subsistema.id"),
			'conditions' => $conditions, 
			'recursive' => 1
		));			
		foreach($resultados as $resultado){
			$script[] = "toastr.warning('Revisar imagen clave <a href=\"/Administracion/Imagenes?motor_id={$resultado["Motor"]["id"]}&sistema_id={$resultado["Sistema"]["id"]}&subsistema_id={$resultado["Subsistema"]["id"]}\">{$resultado["Motor"]["nombre"]} {$resultado["Sistema"]["nombre"]} {$resultado["Subsistema"]["nombre"]}</a>','Alerta DBM');";
		}
		$this->set('script', implode("\n", $script));
		$this->set('seccion','AdministracionImagenes');
	}
	
	public function motorimagen($motor_id, $sistema_id, $subsistema_id){
		$this->layout = 'metronic_principal';
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		
		$resultado = $this->Motor->find('first', array(
										'conditions'=>array("Motor.id" => $motor_id), 
										'recursive' => 1));
		
		if(isset($resultado["Motor"])){
			$motor = $resultado["Motor"]["nombre"] . ' ' . $resultado["TipoEmision"]["nombre"];
		}
		
		$resultado = $this->Sistema->find('first', array(
										'conditions'=>array("id" => $sistema_id), 
										'recursive' => -1));
		
		if(isset($resultado["Sistema"])){
			$sistema = $resultado["Sistema"]["nombre"];
			$sistema = explode("_", $sistema);
			$sistema = $sistema[1];
		}
		
		$resultado = $this->Subsistema->find('first', array(
										'conditions'=>array("id" => $subsistema_id), 
										'recursive' => -1));
		
		if(isset($resultado["Subsistema"])){
			$subsistema = $resultado["Subsistema"]["nombre"];
		}
		
		$image_source = $motor . ' ' . $sistema . ' ' . $subsistema;
		$image_source = str_replace(" ", "_", $image_source);
		$image_source = strtoupper($image_source);
		return $image_source = str_replace(array("Á","É","Í","Ó","Ú","Ñ"), array("A","E","I","O","U","N"), $image_source);
	}
	
	public function motor_imagen_source($motor_id, $sistema_id, $subsistema_id){
		$this->layout = null;
		echo $this->motorimagen($motor_id, $sistema_id, $subsistema_id) . ".png";
		die;
	}
	
	public function folio(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Planificacion');
		$this->loadModel('ReporteBase');
		$this->loadModel('Backup');
		$this->loadModel('DeltaDetalle');
		$this->loadModel('IntervencionFechas');
		$this->loadModel('IntervencionComentarios');
		$this->loadModel('IntervencionElementos');
		$this->loadModel('IntervencionDecisiones');
                
                $this->loadModel('Backlog');
		
		if ($this->request->is('post')) {
			if (isset($this->request->data) && count($this->request->data) > 0) {
				try {
					// Desaprobar folios
					if (isset($this->request->data["desaprobar"]) && is_array($this->request->data["desaprobar"])) {
						foreach ($this->request->data["desaprobar"] as $key => $folio) {
							$resultado = $this->Planificacion->find('first', array('conditions'=>array("id" => $folio), 'recursive' => -1));
							if(isset($resultado["Planificacion"])) {
								$data = $resultado["Planificacion"];
								$this->Backup->create();
								$this->Backup->save($data);
								$data["estado"] = "7";
								$data["fecha_operacion"] = null;
								$data["aprobador_id"] = null;
								$data["fecha_aprobacion"] = null;
								$data["reporte_base"] = null;
								$data["json"] = json_decode($data["json"], true);
								foreach ($data["json"] as $key2 => $value2){
									if (substr($key2, 0, 4) == "ds1_") {
										unset($data["json"][$key2]);
									}
									if (substr($key2, 0, 4) == "ds3_") {
										unset($data["json"][$key2]);
									}
								}
								$data["json"] = json_encode($data["json"]);
								$this->Planificacion->save($data);
								// Eliminamos de tabla reporte base
								$this->ReporteBase->deleteAll(array('folio' => $folio), false);
							}
						}
					}
					
					// Borrar folios
					if (isset($this->request->data["borrar"]) && is_array($this->request->data["borrar"])) {
						foreach ($this->request->data["borrar"] as $key => $folio) {
							$resultado = $this->Planificacion->find('first', array('conditions'=>array("id" => $folio), 'recursive' => -1));
							if(isset($resultado["Planificacion"])) {
								$data = $resultado["Planificacion"];
								$this->Backup->create();
								$this->Backup->save($data);
								// Se eliminan datos de las tablas relacionadas; fechas, elementos, comentarios, decisiones y deltas.
								$this->DeltaDetalle->deleteAll(array('folio' => $data["folio"]), false);
								$this->IntervencionFechas->deleteAll(array('folio' => $data["folio"]), false);
								$this->IntervencionComentarios->deleteAll(array('folio' => $data["folio"]), false);
								$this->IntervencionElementos->deleteAll(array('folio' => $data["folio"]), false);
								$this->IntervencionDecisiones->deleteAll(array('folio' => $data["folio"]), false);
                                                                $this->Backlog->updateAll(array('intervencion_id' => null, 'realizado' => null, 'estado_id' => 8), array('intervencion_id' => $data['id']));
                                                                if(isset($this->request->data["planificar"]) && intval($this->request->data["planificar"]) == intval($data["id"])) {
									// Replanificar folio
									
									// Se busca fecha de término del padre si es que existe
									$resultado2 = $this->Planificacion->find('first', array('conditions'=>array("folio" => $data["padre"]), 'recursive' => -1));
									if(isset($resultado2["Planificacion"])) {
										$data["hora"] = $resultado2["Planificacion"]["hora_termino"];
										$data["fecha"] = $resultado2["Planificacion"]["fecha_termino"];
									} else {
										if ($data["fecha_planificacion"] != NULL && $data["hora_planificacion"] != NULL)
										$data["fecha"] = $data["fecha_planificacion"];
										$data["hora"] = $data["hora_planificacion"];
									}
									
									$data["estado"] = "2";
									$data["hora_termino"] = $data["hora"];
									$data["fecha_termino"] = $data["fecha"];
									$data["fecha_operacion"] = null;
									$data["tiempo_trabajo"] = "00:00";
									$data["aprobador_id"] = null;
									$data["terminado"] = null;
									$data["fecha_aprobacion"] = null;
									$data["reporte_base"] = null;
									$data["supervisor_responsable"] = null;
									$data["turno"] = null;
									$data["periodo"] = null;
									$data["lugar_reparacion"] = null;
									$data["alert_mail"] = null;
									$data["fecha_registro"] = null;
									$data["fecha_guardado"] = null;
									$data["fecha_sincronizacion"] = null;
									$data["fecha_guardado"] = null;
									$data["message_id"] = null;
										
									$data["json"] = json_decode($data["json"], true);
									foreach ($data["json"] as $key2 => $value2){
										if (substr($key2, 0, 4) == "ds1_") {
											unset($data["json"][$key2]);
										}
										if (substr($key2, 0, 4) == "ds3_") {
											unset($data["json"][$key2]);
										}
										if (substr($key2, 0, 9) == "Elemento_") {
											unset($data["json"][$key2]);
										}
										if (substr($key2, 0, 5) == "Delta") {
											unset($data["json"][$key2]);
										}
									}
									$data["json"] = json_encode($data["json"]);
									$this->Planificacion->save($data);
								} else {								
									$this->Planificacion->delete($data["id"]);
								}
								// Eliminamos de tabla reporte base
								$this->ReporteBase->deleteAll(array('folio' => $folio), false);
							}
						}
					}
					
					// Mensajes
					if (isset($this->request->data["desaprobar"]) && is_array($this->request->data["desaprobar"])) {
						$desaprobados = "Folios desaprobados: " . implode(", ", $this->request->data["desaprobar"]).". ";
					}
					if (isset($this->request->data["borrar"]) && is_array($this->request->data["borrar"])) {
						$borrados = "Folios borrados: ".implode(", ", $this->request->data["borrar"]).". ";
					}
					if (isset($this->request->data["planificar"]) && is_numeric($this->request->data["planificar"])) {
						$planificados = "Folio replanificado: " . $this->request->data["planificar"] .".";
					}
					
					$this->Session->setFlash($desaprobados.$borrados.$planificados, 'guardar_exito');
				} catch (Exception $e) {
					$this->Session->setFlash("Ocurrió un error al intentar procesar los folios, favor intentar nuevamente. ", 'guardar_error');
					$this->logger($this, $e->getMessage());
				}
			}
		}
		
		$conditions = array();
		if ($this->request->is('get') || $this->request->is('post')){
			if(isset($this->request->query['correlativo']) && is_numeric($this->request->query['correlativo'])) {
				$conditions["Planificacion.correlativo_final"] = $this->request->query['correlativo'];
			}
			if(isset($this->request->query['folio']) && is_numeric($this->request->query['folio'])) {
				$resultado = $this->Planificacion->find('first', array('conditions'=>array("id" => $this->request->query['folio']), 'recursive' => -1));
				if (isset($resultado["Planificacion"])) {
					$conditions["Planificacion.correlativo_final"] = $resultado["Planificacion"]["correlativo_final"];
				} else {
					$conditions["Planificacion.correlativo_final"] = "-1";
				}
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		if(isset($conditions["Planificacion.correlativo_final"]) && is_numeric($conditions["Planificacion.correlativo_final"])) {
			$this->paginate = array('order' => array('Planificacion.id' => "asc",'Planificacion.fecha' => "asc", 'Planificacion.hora' => "asc"), 'recursive' => 1, 'conditions' => $conditions);
			$registros = $this->paginate('Planificacion');
			$this->set('registros', $registros);
			if(count($registros) == 0) {
				if (!$this->request->is('post')) {
					$this->Session->setFlash("No existen resultados para la búsqueda.", 'guardar_error');
				}
			}
		}
		
		$this->set("limit", 100);
	}
	
	public function aws() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('AwsMessage');
		$limit = 100;
		$this->set("limit", $limit);
		$conditions = array();
		if(isset($this->request->query['folio'])) {
			$conditions["AwsMessage.folio"] = $this->request->query['folio'];
		}
		$this->paginate = array('limit' => $limit, 'order' => array('AwsMessage.fecha_guardado' => "DESC"), 'recursive' => 1, 'conditions' => $conditions);
		$registros = $this->paginate('AwsMessage');
		$this->set('registros', $registros);
	}
	
	public function logs() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Log');
		$limit = 100;
		$this->set("limit", $limit);
		$conditions = array();
		
		if(isset($this->request->query['seccion'])) {
			$conditions["Log.controller"] = $this->request->query['seccion'];
		}
		
		if(isset($this->request->query['subseccion'])) {
			$conditions["Log.action"] = $this->request->query['subseccion'];
		}
		
		$this->paginate = array('limit' => $limit, 'order' => array('Log.fecha' => "DESC"), 'recursive' => 1, 'conditions' => $conditions);
		$registros = $this->paginate('Log');
		$this->set('registros', $registros);
	}
	
	public function intervenciones() {
		$this->layout = 'metronic_principal';
		//$this->check_permissions($this);
		$this->loadModel('Planificacion');
		$limit = 100;
		$this->set("limit", $limit);
		$this->paginate = array('limit' => $limit, 'order' => array('Planificacion.id' => "DESC"), 'recursive' => 1);
		$registros = $this->paginate('Planificacion');
		$this->set('registros', $registros);
	}
}
?>