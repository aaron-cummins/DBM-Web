<?php
set_time_limit(600);
ini_set('memory_limit', -1);
App::uses('ConnectionManager', 'Model'); 
App::uses('File', 'Utility');
App::import('Vendor', 'Classes/PHPExcel');

class CargaMasivaController extends AppController {
	public function usuario(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Usuario');
		$this->loadModel('Faena');
		$this->loadModel('NivelUsuario');
		$this->loadModel('PermisoUsuario');
		$this->loadModel('PermisoPersonalizado');
		$error_msj = array();
		$this->set('titulo', 'Carga Masiva Usuario');
		$this->set('breadcrumb', 'Administración');
		$permisos_id = array();
		$permisos_id[] = -1;
		$permisos_id[] = -2;

        $validTypes = array("application/vnd.ms-excel",
            "application/vnd.ms-excel.addin.macroEnabled.12",
            "application/vnd.ms-excel.sheet.binary.macroEnabled.12",
            "application/vnd.ms-excel.sheet.macroEnabled.12",
            "application/vnd.ms-excel.template.macroEnabled.12",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

		
		if ($this->request->is('post')){
			if (count($this->request->data)) {
				if(isset($this->request->data["file"])) {
					$new_file = "/tmp/sg_" . time();
					$data = $this->request->data["file"];
					if (copy($data["tmp_name"], $new_file)) {
						$this->set('archivo', $new_file);
					}
					$objPHPExcel = new PHPExcel();
					$inputFileName = $data["tmp_name"];
					$mimeType = mime_content_type($data["tmp_name"]);
                    if(!in_array($mimeType, $validTypes)) {
                        $this->Session->setFlash('El formato del archivo no es válido, intente nuevamente.','guardar_error');
                        return;
                    }
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar cargar el archivo, intente nuevamente.','guardar_error');
						$this->logger($this, $e->getMessage());
						return;
					}
					$sheet = $objPHPExcel->getSheet(0); 
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
					//$highestRow = 100;

					$filas = array();
					try {
						for ($row = 2; $row <= $highestRow; $row++){
							$rowData = array();
							$rowData["ucode"] = filter_var(trim($sheet->getCell('A' . $row)->getCalculatedValue()), FILTER_SANITIZE_STRING);
							$rowData["usuario"] = filter_var(trim($sheet->getCell('B' . $row)->getCalculatedValue()), FILTER_SANITIZE_STRING);
							$rowData["nombres"] = filter_var(trim($sheet->getCell('C' . $row)->getCalculatedValue()), FILTER_SANITIZE_STRING);
							$rowData["apellidos"] = filter_var(trim($sheet->getCell('D' . $row)->getCalculatedValue()), FILTER_SANITIZE_STRING);
							$rowData["correo_electronico"] = filter_var(trim($sheet->getCell('E' . $row)->getCalculatedValue()), FILTER_SANITIZE_STRING);
							$rowData["faena"] = filter_var(trim($sheet->getCell('F' . $row)->getCalculatedValue()), FILTER_SANITIZE_STRING);
							$rowData["cargo"] = filter_var(trim($sheet->getCell('G' . $row)->getCalculatedValue()), FILTER_SANITIZE_STRING);
							$rowData["pin"] = filter_var(trim($sheet->getCell('H' . $row)->getCalculatedValue()), FILTER_SANITIZE_STRING);
							$rowData["usuario"] = filter_var(strtolower(trim($rowData["usuario"])), FILTER_SANITIZE_STRING);
							
							if ($rowData["cargo"] == "TECNICO" || $rowData["cargo"] == "tecnico"){
								$rowData["cargo"] = "TÉCNICO";
							}
							
							if ($rowData["cargo"] == "ASESOR TECNICO" || $rowData["cargo"] == "asesor tecnico"){
								$rowData["cargo"] = "ASESOR TÉCNICO";
							}
							
							if(!is_numeric($rowData["usuario"])) {
								//echo "numeric";
								if (strpos($rowData["usuario"], '-') !== false) {
									$rowData["usuario"] = explode('-', $rowData["usuario"]);
									if (is_numeric($rowData["usuario"][0])){
										$rowData["usuario"] = $rowData["usuario"][0];
									}
								}
							}
							/*
							$rowData["usuario"] = str_ireplace(array("i","í","ì","ï"), "[iíìï]", strtolower(trim($rowData["usuario"])));
							$rowData["usuario"] = str_ireplace(array("a","á","à","ä"), "[aáàä]", strtolower(trim($rowData["usuario"])));
							$rowData["usuario"] = str_ireplace(array("e","é","è","ë"), "[aáàä]", strtolower(trim($rowData["usuario"])));
							$rowData["usuario"] = str_ireplace(array("o","ó","ò","ö"), "[aáàä]", strtolower(trim($rowData["usuario"])));
							$rowData["usuario"] = str_ireplace(array("u","ú","ù","ü"), "[aáàä]", strtolower(trim($rowData["usuario"])));*/
							
							$r = $this->Usuario->find('first', array(
								'fields' => array('Usuario.id'),
								'conditions' => array('Usuario.usuario' => $rowData["usuario"]),
								'recursive'=>-1
							));
							if(isset($r["Usuario"])){
								$rowData["usuario_id"] = $r["Usuario"]["id"];
							} else {
								$rowData["usuario_id"] = -1;
							}

							if (strlen($rowData["usuario"]) > 8) {
								$rowData["usuario_id"] = -1;
							}

							$r = $this->Faena->find('first', array(
								'fields' => array('Faena.id'),
								'conditions' => array("OR" => array('UPPER(TRIM(Faena.nombre))' => strtoupper(trim($rowData["faena"])), 'LOWER(TRIM(Faena.nombre))' => strtolower(trim($rowData["faena"])))),
								'recursive'=>-1
							));
							
							if(isset($r["Faena"])){
								$rowData["faena_id"] = $r["Faena"]["id"];
							} else {
								$rowData["faena_id"] = -1;
							}

							$r = $this->NivelUsuario->find('first', array(
								'fields' => array('NivelUsuario.id'),
								'conditions' => array("OR" => array('UPPER(TRIM(NivelUsuario.nombre))' => strtoupper(trim($rowData["cargo"])), 'LOWER(TRIM(NivelUsuario.nombre))' => strtolower(trim($rowData["cargo"])))),
								'recursive'=>-1
							));

							if(isset($r["NivelUsuario"])){
								$rowData["cargo_id"] = $r["NivelUsuario"]["id"];
							} else {
								$rowData["cargo_id"] = -1;
							}
							$r = $this->PermisoUsuario->find('first', array(
								'fields' => array('PermisoUsuario.id'),
								'conditions' => array('usuario_id' => $rowData["usuario_id"], 'faena_id' => $rowData["faena_id"], 'cargo_id' => $rowData["cargo_id"]),
								'recursive'=>-1
							));
							
							if(isset($r["PermisoUsuario"])){
								$rowData["permiso_id"] = $r["PermisoUsuario"]["id"];
								$permisos_id[] = $r["PermisoUsuario"]["id"];
							} else {
								$rowData["permiso_id"] = -1;
							}
							
							$rowData["valido"] = '1';
							if($rowData["usuario"]==''||$rowData["nombres"]==''||$rowData["apellidos"]==''||$rowData["correo_electronico"]==''||$rowData["faena"]==''||$rowData["cargo"]==''|| !$this->esContrasenaValida($rowData["pin"])||$rowData["faena_id"]=='-1'||$rowData["cargo_id"]=="-1"){
								$rowData["valido"] = '0';
								$error_msj[] = $row - 1;
							}
							
							// Verificamos que no exista correo electronico
							$r = $this->Usuario->find('first', array(
								'fields' => array('Usuario.id'),
								'conditions' => array('Usuario.usuario NOT' => strtolower(trim($rowData["usuario"])), 'Usuario.correo_electronico' => strtolower(trim($rowData["correo_electronico"]))),
								'recursive'=>-1
							));
							if(isset($r["Usuario"])){
								$rowData["valido"] = '0';
								$error_msj[] = ($row - 1) . " (correo duplicado)";	
							}
							
							$r = $this->Usuario->find('first', array(
								'fields' => array('Usuario.id'),
								'conditions' => array('Usuario.usuario NOT' => strtolower(trim($rowData["usuario"])), 'Usuario.u' => strtolower(trim($rowData["ucode"]))),
								'recursive'=>-1
							));
							if(isset($r["Usuario"])){
								$rowData["valido"] = '0';
								$error_msj[] = ($row - 1) . " (u duplicado)";	
							}
							
							$filas[] = $rowData;
						}
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente.','guardar_error');
						$this->logger($this, $e->getMessage());
					}
					$this->set('data', $filas);
					if (count($error_msj) > 0){
						$this->Session->setFlash("El archivo tiene ".count($filas)." registros y las lineas ".implode(", ", $error_msj)." no contienen todos los datos válidos y no se pueden registrar", 'guardar_error');
					} else {
						$this->Session->setFlash("Se están previsualizando ".count($filas)." líneas correctamente", 'guardar_exito');
					}
				}
				
				$cargos = $this->NivelUsuario->find('list', array('order' => array("NivelUsuario.nombre"), 'conditions' => array('e' => 1), 'recursive' => -1));		
				$this->set(compact('cargos'));
				
				$faenas = $this->Faena->find('list', array('order' => array("Faena.nombre"), 'conditions' => array('e' => 1), 'recursive' => -1));		
				$this->set(compact('faenas'));
				
				$permisos = $this->PermisoUsuario->find('all', array('conditions' => array("PermisoUsuario.id NOT IN" => $permisos_id),'order' => array("Faena.nombre", "Cargo.nombre", "Usuario.usuario"), 'recursive' => 1));		
				$this->set(compact('permisos'));
				
				$registros["OK"] = 0;
				$registros["NOK"] = 0;
				$registrado = array();
				if(isset($this->request->data["form-save"])) {
					if(isset($this->request->data["permiso_id"]) && is_array($this->request->data["permiso_id"])) {
						$this->PermisoUsuario->updateAll(
							array('PermisoUsuario.e' => "'0'"),
							array('PermisoUsuario.id NOT IN' => $this->request->data["permiso_id"])
						);
						$this->PermisoUsuario->updateAll(
							array('PermisoUsuario.e' => "'1'"),
							array('PermisoUsuario.id IN' => $this->request->data["permiso_id"])
						);
					}
					
					if(isset($this->request->data["registro"])) {
						foreach($this->request->data["registro"] as $key => $value){
							$data = array();
							$data["nombres"] = $this->request->data["nombres"][$key];
							$data["apellidos"] = $this->request->data["apellidos"][$key];
							$data["usuario"] = $this->request->data["usuario"][$key];
							$data["u"] = $this->request->data["ucode"][$key];
							$data["e"] = "1";
							$data["correo_electronico"] = $this->request->data["correo_electronico"][$key];
							$data["pin"] = password_hash($this->request->data["pin"][$key],PASSWORD_BCRYPT);
							if($data["correo_electronico"] == '') {
								continue;
							}
							$usuario_id = null;
							try {
								$usuario = $this->Usuario->find('first', array(
									'fields' => array('Usuario.id', 'Usuario.usuario'),
									'conditions' => array('Usuario.usuario' => strtolower(trim($data["usuario"]))),
									'recursive' => -1
								));
								if(isset($usuario["Usuario"]) && isset($usuario["Usuario"]["id"])){
									$usuario_id = $usuario["Usuario"]["id"];
									try {
										$data["id"] = $usuario_id;
										$this->Usuario->save($data);
									}catch(Exception $e){
										$this->Session->setFlash("Ocurrió un error al intentar actualizar la información de la línea ".$key.", por favor intente nuevamente.", 'guardar_error');
										$this->logger($this, $e->getMessage());
										return;
									}
								} else {
									$this->Usuario->create();
									$this->Usuario->save($data);
									$usuario_id = $this->Usuario->id;
								}

								if(!isset($registrado[$usuario_id])) {
									//$this->PermisoUsuario->deleteAll(array('PermisoUsuario.usuario_id' => $usuario_id), false);
									//$this->PermisoPersonalizado->deleteAll(array('PermisoPersonalizado.usuario_id' => $usuario_id), false);
								}
								
								$registrado[$usuario_id] = true;
							
								/*if($this->request->data["faena_id"][$key] == '-1') {
									$data = array();
									$data["nombre"] = $this->request->data["faena"][$key];
									try {
										$this->Faena->create();
										$this->Faena->save($data);
										$this->request->data["faena_id"][$key] = $this->Faena->id;
									}catch(Exception $e){
										$this->Session->setFlash("Ocurrió un error al intentar registrar la nueva faena, por favor intente nuevamente.", 'guardar_error');
										return;
									}
								}*/
								
								/*if($this->request->data["cargo_id"][$key] == '-1') {
									$data = array();
									$data["nombre"] = $this->request->data["cargo"][$key];
									try {
										$this->NivelUsuario->create();
										$this->NivelUsuario->save($data);
										$this->request->data["cargo_id"][$key] = $this->NivelUsuario->id;
									}catch(Exception $e){
										$this->Session->setFlash("Ocurrió un error al intentar registrar el nuevo cargo, por favor intente nuevamente.", 'guardar_error');
										return;
									}
								}*/
								
								try {
									$data = array();
									$data["faena_id"] = $this->request->data["faena_id"][$key];
									$data["cargo_id"] = $this->request->data["cargo_id"][$key];
									$data["usuario_id"] = $usuario_id;
									$data["updated"] = date("Y-m-d H:i:s");
									$data["e"] = "1";
									$this->PermisoUsuario->create();
									$this->PermisoUsuario->save($data);
									$registros["OK"]++;
								}catch(Exception $e){
									$this->Session->setFlash("Ocurrió un error al intentar registrar el permiso para la línea ".$key.", por favor intente nuevamente.", 'guardar_error');
									$this->logger($this, $e->getMessage());
									return;
								}
							}catch(Exception $e){
								$this->Session->setFlash("Ocurrió un error al intentar registrar el permiso para la línea ".$key.", por favor intente nuevamente.", 'guardar_error');
								$this->logger($this, $e->getMessage());
								return;
							}
						} 
						$this->Session->setFlash("Se ingresaron correctamente {$registros["OK"]} registros.", 'guardar_exito');
					}
					
					if(isset($this->request->data["usuario_id"])) {
						foreach($this->request->data["usuario_id"] as $key => $value){
							if ($this->request->data["usuario_id"][$key] != "-1") {
								$data = array();
								$data["nombres"] = $this->request->data["nombres"][$key];
								$data["apellidos"] = $this->request->data["apellidos"][$key];
								$data["u"] = $this->request->data["ucode"][$key];
								$data["correo_electronico"] = $this->request->data["correo_electronico"][$key];
								$data["id"] = $this->request->data["usuario_id"][$key];
								$data["e"] = "1";
								try {
									$this->Usuario->save($data);
								}catch(Exception $e){
									$this->logger($this, $e->getMessage());
								}
							}
						}
					}
					
					
				}
			}
		}
	}
	
	public function estadomotores(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Unidad');
		$this->loadModel('Usuario');
		$this->loadModel('Faena');
		$this->loadModel('Motor');
		$this->loadModel('Flota');
		$this->loadModel('Cargo');
		$this->loadModel('TipoContrato');
		$this->loadModel('EstadosMotores');
		$this->loadModel('EstadoMotor');
		$this->loadModel('EstadoMotorInstalacion');
		$this->loadModel('PermisoUsuario');
		$this->set('titulo', 'Carga Masiva Estado Motores');
		$this->set('breadcrumb', 'Administración');
		if ($this->request->is('post')){
			if (count($this->request->data)) {
				if(isset($this->request->data["file"])) {
					$f_t = array();
					$faenas = $this->Faena->find('all', array(
						'recursive'=>-1
					));
					foreach($faenas as $faena){
						$f_t[strtolower($faena["Faena"]["nombre"])] = $faena["Faena"]["id"];
					}
					$faenas = $f_t;
					
					$f_t = array();
					$motores = $this->Motor->find('all', array(
						'fields' => array('Motor.nombre','Motor.id','TipoEmision.nombre','TipoAdmision.nombre'),
						'recursive'=>1
					));
					foreach($motores as $motor){
						$f_t[strtolower($motor["Motor"]["nombre"]) . ' ' . strtolower($motor["TipoEmision"]["nombre"]) . '_' . strtolower($motor["Motor"]["nombre"]) . ' ' . strtolower($motor["TipoAdmision"]["nombre"])] = $motor["Motor"]["id"];
					}
					$motores = $f_t;
					
					$f_t = array();
					$flotas = $this->Flota->find('all', array(
						'recursive'=>-1
					));
					foreach($flotas as $flota){
						$f_t[strtolower($flota["Flota"]["nombre"])] = $flota["Flota"]["id"];
					}
					$flotas = $f_t;
					
					$f_t = array();
					$registros = $this->EstadoMotor->find('all', array(
						'recursive'=>-1
					));
					foreach($registros as $registro){
						$f_t[strtolower($registro["EstadoMotor"]["nombre"])] = $registro["EstadoMotor"]["id"];
					}
					$estadomotor = $f_t;
					
					$f_t = array();
					$registros = $this->TipoContrato->find('all', array(
						'recursive'=>-1
					));
					foreach($registros as $registro){
						$f_t[strtolower($registro["TipoContrato"]["nombre"])] = $registro["TipoContrato"]["id"];
					}
					$tipocontrato = $f_t;
					
					$f_t = array();
					$registros = $this->EstadoMotorInstalacion->find('all', array(
						'recursive'=>-1
					));
					foreach($registros as $registro){
						$f_t[strtolower($registro["EstadoMotorInstalacion"]["nombre"])] = $registro["EstadoMotorInstalacion"]["id"];
					}
					$estadomotorinstalacion = $f_t;
					
					$f_t = array();
					$registros = $this->Unidad->find('all', array(
						'recursive'=>-1
					));
					foreach($registros as $registro){
						$f_t[strtolower($registro["Unidad"]["unidad"])."_".$registro["Unidad"]["faena_id"]."_".$registro["Unidad"]["flota_id"]] = $registro["Unidad"]["id"];
					}
					$unidades = $f_t;
					
					
					 
					$new_file = "/tmp/sg_" . time(); 
					$data = $this->request->data["file"];
					if (copy($data["tmp_name"], $new_file)) {
						$this->set('archivo', $new_file);
					}
					$objPHPExcel = new PHPExcel();
					$inputFileName = $data["tmp_name"];
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
					}
					$sheet = $objPHPExcel->getSheet(0); 
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
					$filas = array();
					$currentRow = 0;
					try {
						for ($row = 2; $row <= $highestRow; $row++) {
							$currentRow = $row;
							$rowData = array();
							
							$rowData["faena"] = $sheet->getCell('B' . $row)->getCalculatedValue();
							$rowData["tipo_contrato"] = $sheet->getCell('C' . $row)->getCalculatedValue();
							$rowData["flota"] = $sheet->getCell('D' . $row)->getCalculatedValue();

							$rowData["unidad"] = $sheet->getCell('G' . $row)->getCalculatedValue();
							
							$rowData["esn_placa"] = $sheet->getCell('I' . $row)->getCalculatedValue();
							$rowData["esn"] = $sheet->getCell('H' . $row)->getCalculatedValue();
							
							$rowData["arreglo_motor"] = $sheet->getCell('J' . $row)->getCalculatedValue();
							$rowData["modelo_motor"] = $sheet->getCell('K' . $row)->getCalculatedValue();

							$rowData["estado_motores"] = $sheet->getCell('L' . $row)->getCalculatedValue();
							$rowData["hr_equipo_instalacion"] = $sheet->getCell('M' . $row)->getCalculatedValue();
							$rowData["hr_motor_instalacion"] = $sheet->getCell('N' . $row)->getCalculatedValue();
							$rowData["estado_motor_instalacion"] = $sheet->getCell('O' . $row)->getCalculatedValue();
							$rowData["fecha_ps"] = $sheet->getCell('P' . $row)->getFormattedValue();
							$rowData["hr_operadas_motor"] = $sheet->getCell('Q' . $row)->getCalculatedValue();
							$rowData["hr_acumuladas_motor"] = $sheet->getCell('S' . $row)->getCalculatedValue();
							$rowData["hr_historico_motor"] = $sheet->getCell('T' . $row)->getCalculatedValue();
								
							$rowData["fecha_falla"] = $sheet->getCell('V' . $row)->getFormattedValue();

							$rowData["motivo_cambio"] = $sheet->getCell('Y' . $row)->getCalculatedValue();
							$rowData["tipo_salida"] = $sheet->getCell('Z' . $row)->getCalculatedValue();
							
							
							$rowData["tsr"] = $sheet->getCell('AA' . $row)->getCalculatedValue();
							$rowData["causa_raiz"] = $sheet->getCell('AB' . $row)->getCalculatedValue();
							$rowData["responsable"] = $sheet->getCell('AC' . $row)->getCalculatedValue();
							$rowData["fecha_llegada_taller"] = $sheet->getCell('AD' . $row)->getFormattedValue();
							$rowData["estado_final"] = $sheet->getCell('AE' . $row)->getCalculatedValue();
							$rowData["costo"] = $sheet->getCell('AF' . $row)->getCalculatedValue();
							$rowData["ot"] = $sheet->getCell('AG' . $row)->getCalculatedValue();
							$rowData["lugar_reparacion"] = $sheet->getCell('AH' . $row)->getCalculatedValue();

							/*$r = $this->Faena->find('first', array(
								'fields' => array('Faena.id'),
								'conditions' => array('LOWER(TRIM(Faena.nombre))' => strtolower(trim($rowData["faena"]))),
								'recursive'=>-1
							));*/
							
							if(isset($faenas[strtolower($rowData["faena"])])){
								$rowData["faena_id"] = $faenas[strtolower($rowData["faena"])];
							} else {
								$rowData["faena_id"] = NULL;
							}
							
							if(isset($motores[strtolower($rowData["arreglo_motor"]).'_'.strtolower($rowData["modelo_motor"])])){
								$rowData["motor_id"] = $motores[strtolower($rowData["arreglo_motor"]).'_'.strtolower($rowData["modelo_motor"])];
							} else {
								$rowData["motor_id"] = NULL;
							}
							
							if(isset($flotas[strtolower($rowData["flota"])])){
								$rowData["flota_id"] = $flotas[strtolower($rowData["flota"])];
							} else {
								$rowData["flota_id"] = NULL;
							}
							
							if(isset($estadomotor[strtolower($rowData["estado_motores"])])){
								$rowData["estado_motor_id"] = $estadomotor[strtolower($rowData["estado_motores"])];
							} else {
								$rowData["estado_motor_id"] = NULL;
							}
							
							if(isset($estadomotorinstalacion[strtolower($rowData["estado_motor_instalacion"])])){
								$rowData["estado_motor_instalacion_id"] = $estadomotorinstalacion[strtolower($rowData["estado_motor_instalacion"])];
							} else {
								$rowData["estado_motor_instalacion_id"] = NULL;
							}
							
							
							if(isset($tipocontrato[strtolower($rowData["tipo_contrato"])])){
								$rowData["tipo_contrato_id"] = $tipocontrato[strtolower($rowData["tipo_contrato"])];
							} else {
								$rowData["tipo_contrato_id"] = NULL;
							}
							
							if(strtolower($rowData["tipo_salida"]) == 'programado'){
								$rowData["tipo_salida_id"] = "2";
							} elseif(strtolower($rowData["tipo_salida"]) == 'fuera programa'){
								$rowData["tipo_salida_id"] = "1";
							} else {
								$rowData["tipo_salida_id"] = NULL;
							}
							
							if(isset($unidades[strtolower($rowData["unidad"])."_".$rowData["faena_id"]."_".$rowData["flota_id"]])){
								$rowData["unidad_id"] = $unidades[strtolower($rowData["unidad"])."_".$rowData["faena_id"]."_".$rowData["flota_id"]];
							} else {
								$rowData["unidad_id"] = NULL;
							}
							$rowData["estado_equipo_id"] = "1";
							if($rowData["faena"] != '' && $rowData["flota"] != '' && $rowData["unidad"] != '' && $rowData["faena"] != '0' && $rowData["flota"] != '0' && $rowData["unidad"] != '0') {
								$filas[] = $rowData;
							}
						}
					} catch(Exception $e) {
						debug($e);
						$this->log($e);
						$this->log($currentRow);
						$this->Session->setFlash('Ocurrió un error al intentar procesar la fila '.$currentRow.', corrija el problema e intente nuevamente.','guardar_error');
					}
					$currentRow = 2;
					$lastRow = null;
					try {
						$dataSource = ConnectionManager::getDataSource('default');
						$dataSource->begin();
						foreach($filas as $value) {
							$lastRow = $value;
							$this->EstadosMotores->create();
							$this->EstadosMotores->save($value);
							// Actualizacion tipo contrato y estado de motor
							$data = array();
							$data["id"] = $value["unidad_id"];
							$data["motor_id"] = $value["motor_id"];
							$data["tipo_contrato_id"] = $value["tipo_contrato_id"];
							$this->Unidad->save($data);
							$currentRow++;
						}
						$dataSource->commit();
						$this->Session->setFlash('Archivo procesado con éxito, se guardaron '.count($filas).' registros.','guardar_exito');
					} catch(Exception $e) {
						$dataSource->rollback();
						$this->log($e);
						$this->log($currentRow);
						$this->log($lastRow);
						debug($e);
						debug($lastRow);
						$this->Session->setFlash('Ocurrió un error al intentar guardar los registros en base de datos (fila '.$currentRow.'), intente nuevamente.','guardar_error');
					}
				}
			}
		}
	}

	public function sistema(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Sistema');
		$this->loadModel('Motor');
		$this->loadModel('Subsistema');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$this->set('is_post', 'false');
		$this->set('titulo', 'Carga Sistema');
		$this->set('breadcrumb', 'Administración');
		if ($this->request->is('post')){
			if (count($this->request->data)) {
				if(isset($this->request->data["file"])) {
					$this->set('is_post', 'true');
					$invalidos = array();
					$data = $this->request->data["file"];
					$objPHPExcel = new PHPExcel();
					$inputFileName = $data["tmp_name"];
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar cargar el archivo, intente nuevamente.','guardar_error');
						return;
					}
					
					$motores = array();
					$resultados = $this->Motor->find('all', array(
						'fields' => array('Motor.id','Motor.nombre','TipoEmision.nombre','TipoAdmision.nombre'),
						'recursive' => 1
					));
					foreach($resultados as $resultado){
						$motores[strtolower($resultado["Motor"]["nombre"] . ' ' . $resultado["TipoEmision"]["nombre"])] = $resultado["Motor"]["id"];
					}
					
					$sheet = $objPHPExcel->getSheet(0); 
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
					//$highestRow = 100;
					$filas = array();
					try {
						for ($row = 2; $row <= $highestRow; $row++){
							$rowData = array();
							$rowData["motor"] = trim($sheet->getCell('A' . $row)->getCalculatedValue());
							$rowData["seccion"] = trim($sheet->getCell('B' . $row)->getCalculatedValue());
							$rowData["sistema"] = trim($sheet->getCell('C' . $row)->getCalculatedValue());
							$rowData["subsistema"] = trim($sheet->getCell('D' . $row)->getCalculatedValue());
							$rowData["posicion"] = trim($sheet->getCell('E' . $row)->getCalculatedValue());
							$rowData["condicion"] = trim($sheet->getCell('F' . $row)->getCalculatedValue());
							
							if (isset($motores[strtolower($rowData["motor"])])) {
								$rowData["motor_id"] = $motores[strtolower($rowData["motor"])];
							} else { 
								$rowData["motor_id"] = -1;
							}
							$resultado = $this->Sistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["sistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Sistema'])) {
								$rowData["sistema_id"] = $resultado['Sistema']["id"];
							} else {
								$rowData["sistema_id"] = -1;
							}
							$resultado = $this->Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["subsistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Subsistema'])) {
								$rowData["subsistema_id"] = $resultado['Subsistema']["id"];
							} else {
								$rowData["subsistema_id"] = -1;
							}
							$resultado = $this->Posiciones_Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["posicion"]))),
								'recursive' => -1
							));
							if (isset($resultado['Posiciones_Subsistema'])) {
								$rowData["posicion_id"] = $resultado['Posiciones_Subsistema']["id"];
							} else {
								$rowData["posicion_id"] = -1;
							}
							$resultado = $this->MotorSistemaSubsistemaPosicion->find('first', array(
								'conditions' => array('motor_id' => $rowData["motor_id"],'sistema_id' => $rowData["sistema_id"],'subsistema_id' => $rowData["subsistema_id"],'posicion_id' => $rowData["posicion_id"]),
								'recursive' => -1
							));
							if (isset($resultado['MotorSistemaSubsistemaPosicion'])) {
								$rowData["estado_id"] = 1; // Ambos
							} else {
								$rowData["estado_id"] = 2; // Nuevo
							}
                                                        
                                                       if(empty($rowData["motor"])||(empty($rowData["seccion"])&&$rowData["seccion"]!='0')||empty($rowData["sistema"])||empty($rowData["subsistema"])||empty($rowData["posicion"])||empty($rowData["condicion"])){
								$rowData["estado_id"] = 3; // Inválido
								$invalidos[] = $row - 1;
							}
							$filas[] = $rowData;
							
						}
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
					$this->set('data', $filas);
					
					$sistemas = $this->Sistema->find('all', array(
						'fields' => array('Sistema.id','Sistema.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('sistemas'));
					$motores = $this->Motor->find('all', array(
						'fields' => array('Motor.id','Motor.nombre','TipoEmision.nombre'),
						'order' => array('Motor.nombre' => 'asc'),
						'recursive' => 1
					));
					$this->set(compact('motores'));
					$subsistemas = $this->Subsistema->find('all', array(
						'fields' => array('Subsistema.id','Subsistema.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('subsistemas'));
					
					$posiciones = $this->Posiciones_Subsistema->find('all', array(
						'fields' => array('Posiciones_Subsistema.id','Posiciones_Subsistema.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('posiciones'));
					
					
					if(is_array($invalidos) && count($invalidos)){
						$this->Session->setFlash('Las lineas '.implode(',', $invalidos).' contienen datos inválidos y no podrán seleccionarse.','guardar_error');
					}
				} elseif(isset($this->request->data["form-save"])) {
					try {
						$es_actualizar = array();
						$es_nuevo = false;
						$claves = array();
						$filas = 0;
						// Se trabajan solo filas seleccionadas (solo nuevos, con cualquier condición)
						foreach($this->request->data["seleccion"] as $key => $value){
							$condicion_id = $this->request->data["condicion_id"][$key];
							$motor_id = $this->request->data["motor_id"][$key];
							$sistema_id = $this->request->data["sistema_id"][$key];
							$subsistema_id = $this->request->data["subsistema_id"][$key];
							$posicion_id = $this->request->data["posicion_id"][$key];
							$motor = $this->request->data["motor"][$key];
							$sistema = $this->request->data["sistema"][$key];
							$subsistema = $this->request->data["subsistema"][$key];
							$posicion = $this->request->data["posicion"][$key];
							
							// Se verifica que exista registro, si no existe, se crea y utiliza ID
							if ($motor_id == -1) {
								$data = array();
								$data["nombre"] = $motor; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Motor->create();
								$this->Motor->save($data);
								$motor_id = $this->Motor->id;
							}
							
							if ($sistema_id == -1) {
								$data = array();
								$data["nombre"] = $sistema; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Sistema->create();
								$this->Sistema->save($data);
								$sistema_id = $this->Sistema->id;
							}
							
							if ($subsistema_id == -1) {
								$data = array();
								$data["nombre"] = $subsistema; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Subsistema->create();
								$this->Subsistema->save($data);
								$subsistema_id = $this->Subsistema->id;
							}
							
							if ($posicion_id == -1) {
								$data = array();
								$data["nombre"] = $posicion; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Posiciones_Subsistema->create();
								$this->Posiciones_Subsistema->save($data);
								$posicion_id = $this->Posiciones_Subsistema->id;
							}
							
							if(strtolower($condicion_id) == 'nuevo') {
								$data = array();
								$data["motor_id"] = $motor_id;
								$data["sistema_id"] = $sistema_id;
								$data["subsistema_id"] = $subsistema_id;
								$data["posicion_id"] = $posicion_id;
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->MotorSistemaSubsistemaPosicion->create();
								$this->MotorSistemaSubsistemaPosicion->save($data);
								$claves[$motor.' '.$sistema.' '.$subsistema] = true;
							} elseif(strtolower($condicion_id) == 'actualizar') {
								// Obtención de clave
								$clave = $motor_id.' '.$sistema_id.' '.$subsistema_id;
								if (!isset($es_actualizar[$clave])) {
									// Desactivamos registro que cumplan la clave (todo el motor-sistema-subsistema)
									$data = array();
									$data["motor_id"] = $motor_id;
									$data["sistema_id"] = $sistema_id;
									$data["subsistema_id"] = $subsistema_id;
									$this->MotorSistemaSubsistemaPosicion->updateAll(array("e" => '0', 'updated' => 'now()'), $data);
									$es_actualizar[$clave] = true;
									//$claves[$motor.'-'.$sistema.'-'.$subsistema] = true;
								}
								$data = array();
								$data["motor_id"] = $motor_id;
								$data["sistema_id"] = $sistema_id;
								$data["subsistema_id"] = $subsistema_id;
								$data["posicion_id"] = $posicion_id;
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->MotorSistemaSubsistemaPosicion->create();
								$this->MotorSistemaSubsistemaPosicion->save($data);
							} elseif(strtolower($condicion_id) == 'existe') {
								//No se hace nada
							}
							$filas++;
						}
						foreach($claves as $key => $value){
							$this->enviarAlertaCorreo(1, 'Modificación de clave en carga masiva sistema', 'Se actualizó la información de la clave Motor-Sistema-Subsistema -> '.$key.', favor verificar posibles cambios relacionados con la clave haciendo click en el link http://agosto.salmonsoftware.cl/Administracion/Imagenes');
						}
						$this->Session->setFlash('Se proceso correctamente el archivo, se registraron '.$filas.' filas.','guardar_exito');
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
				}
			}
		}
	}
	
	public function sintoma(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('MotivoLlamado');
		$this->loadModel('SintomaCategoria');
		$this->loadModel('Sintoma');
		$this->loadModel('MotivoCategoriaSintoma');
		$this->set('is_post', 'false');
		$this->set('titulo', 'Carga Sintoma');
		$this->set('breadcrumb', 'Administración');
		$matriz_id = array();
		$matriz_id[] = -1;
		$matriz_id[] = -2;
		if ($this->request->is('post')){
			if (count($this->request->data)) {
				if(isset($this->request->data["file"])) {
					$this->set('is_post', 'true');
					$invalidos = array();
					$data = $this->request->data["file"];
					$objPHPExcel = new PHPExcel();
					$inputFileName = $data["tmp_name"];
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar cargar el archivo, intente nuevamente.','guardar_error');
						return;
					}
					
					$sheet = $objPHPExcel->getSheet(0); 
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
					//$highestRow = 100;
					$filas = array();
					try {
						for ($row = 2; $row <= $highestRow; $row++){
							$rowData = array();
							$rowData["motivo_llamado"] = trim($sheet->getCell('A' . $row)->getCalculatedValue());
							$rowData["categoria_sintoma"] = trim($sheet->getCell('B' . $row)->getCalculatedValue());
							$rowData["sintoma"] = trim($sheet->getCell('C' . $row)->getCalculatedValue());
							//$rowData["condicion"] = trim($sheet->getCell('D' . $row)->getCalculatedValue());
							
							$rowData["sintoma_q"] = str_ireplace(array("í","ì","ï","í"),array("i","i","i","i"), strtolower(trim($rowData["sintoma"])));
							$rowData["sintoma_q"] = str_ireplace(array("i"), array('[iíìï]'), strtolower(trim($rowData["sintoma_q"])));
							
							$rowData["sintoma_q"] = str_ireplace(array("á","à","ä"), array("a","a","a"), strtolower(trim($rowData["sintoma_q"])));
							$rowData["sintoma_q"] = str_ireplace(array("a"), array("[aáàä]"), strtolower(trim($rowData["sintoma_q"])));
							
							$rowData["sintoma_q"] = str_ireplace(array("é","è","ë"), array("e","e","e"), strtolower(trim($rowData["sintoma_q"])));
							$rowData["sintoma_q"] = str_ireplace(array("e"), array("[eéèë]"), strtolower(trim($rowData["sintoma_q"])));
							
							$rowData["sintoma_q"] = str_ireplace(array("ó","ò","ö"), array("o","o","o"), strtolower(trim($rowData["sintoma_q"])));
							$rowData["sintoma_q"] = str_ireplace(array("o"), array("[oóòö]"), strtolower(trim($rowData["sintoma_q"])));
							
							$rowData["sintoma_q"] = str_ireplace(array("ú","ù","ü"), array("u","u","u"), strtolower(trim($rowData["sintoma_q"])));
							$rowData["sintoma_q"] = str_ireplace(array("u"), array("[uúùü]"), strtolower(trim($rowData["sintoma_q"])));
							
							$rowData["sintoma_q"] = str_ireplace("(", '\(', strtolower(trim($rowData["sintoma_q"])));
							$rowData["sintoma_q"] = str_ireplace(")", '\)', strtolower(trim($rowData["sintoma_q"])));
							
							$resultado = $this->MotivoLlamado->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower($rowData["motivo_llamado"])),
								'recursive' => -1
							));
							if (isset($resultado['MotivoLlamado'])) {
								$rowData["motivo_id"] = $resultado['MotivoLlamado']["id"];
							} else {
								$rowData["motivo_id"] = -1;
							}
							$resultado = $this->SintomaCategoria->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["categoria_sintoma"]))),
								'recursive' => -1
							));
							if (isset($resultado['SintomaCategoria'])) {
								$rowData["categoria_id"] = $resultado['SintomaCategoria']["id"];
							} else {
								$rowData["categoria_id"] = -1;
							}
							$rowData["codigo"] = "";
							$tmp = explode("_", $rowData["sintoma_q"]);
							$tmp1 = explode("_", $rowData["sintoma"]);
							if (isset($tmp[0]) && is_numeric($tmp[0])) {
								$resultado = $this->Sintoma->find('first', array(
									'conditions' => array('LOWER(TRIM(nombre)) ~*' => strtolower(($tmp[1])),'codigo' => $tmp[0]),
									'recursive' => -1
								));
								$rowData["sintoma"] = $tmp1[1];
								$rowData["codigo"] = $tmp1[0];
								if (isset($resultado['Sintoma'])) {
									$rowData["sintoma_id"] = $resultado['Sintoma']["id"];
								} else {
									$rowData["sintoma_id"] = -1;
								}
							} else {
								$resultado = $this->Sintoma->find('first', array(
									'conditions' => array('LOWER(TRIM(nombre)) ~*' => strtolower(($rowData["sintoma_q"]))),
									'recursive' => -1
								));
								if (isset($resultado['Sintoma'])) {
									$rowData["sintoma_id"] = $resultado['Sintoma']["id"];
								} else {
									$rowData["sintoma_id"] = -1;
								}
							}
							
							$resultado = $this->MotivoCategoriaSintoma->find('first', array(
								'conditions' => array('motivo_id' => $rowData["motivo_id"],'categoria_id' => $rowData["categoria_id"],'sintoma_id' => $rowData["sintoma_id"]),
								'recursive' => -1
							));
							if (isset($resultado['MotivoCategoriaSintoma'])) {
								$rowData["estado_id"] = 1; // Ambos
								$matriz_id[] = $resultado['MotivoCategoriaSintoma']["id"];
							} else {
								$rowData["estado_id"] = 2; // Nuevo
							}
							if(empty($rowData["motivo_llamado"])||empty($rowData["categoria_sintoma"])||empty($rowData["sintoma"])){
								$rowData["estado_id"] = 3; // Inválido
								$invalidos[] = $row - 1;
							}
							unset($rowData["sintoma_q"]);
							$filas[] = $rowData;
							
						}
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
					$this->set('data', $filas);
					
					$motivos = $this->MotivoLlamado->find('all', array(
						'fields' => array('MotivoLlamado.id','MotivoLlamado.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('motivos'));
					$categorias = $this->SintomaCategoria->find('all', array(
						'fields' => array('SintomaCategoria.id','SintomaCategoria.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => 1
					));
					$this->set(compact('categorias'));
					$sintomas = $this->Sintoma->find('all', array(
						'fields' => array('Sintoma.id','Sintoma.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('sintomas'));
					
					$matriz = $this->MotivoCategoriaSintoma->find('all', array('conditions' => array("MotivoCategoriaSintoma.id NOT IN" => $matriz_id),'order' => array("Motivo.nombre", "Categoria.nombre", "Sintoma.nombre"), 'recursive' => 1));		
					$this->set(compact('matriz'));
					
					if(is_array($invalidos) && count($invalidos)){
						$this->Session->setFlash('Las lineas '.implode(',', $invalidos).' contienen datos inválidos y no podrán seleccionarse.','guardar_error');
					}
				} elseif(isset($this->request->data["form-save"])) {
					try {
						$es_actualizar = array();
						$es_nuevo = false;
						$claves = array();
						$filas = 0;
						// Se trabajan solo filas seleccionadas (solo nuevos, con cualquier condición)
						foreach($this->request->data["seleccion"] as $key => $value){
							try {
								//$condicion_id = $this->request->data["condicion_id"][$key];
								$motivo_id = $this->request->data["motivo_id"][$key];
								$categoria_id = $this->request->data["categoria_id"][$key];
								$sintoma_id = $this->request->data["sintoma_id"][$key];
								$motivo = $this->request->data["motivo"][$key];
								$categoria = $this->request->data["categoria"][$key];
								$sintoma = $this->request->data["sintoma"][$key];
								$codigo = $this->request->data["codigo"][$key];
								
								// Se verifica que exista registro, si no existe, se crea y utiliza ID
								if ($motivo_id == "-1") {
									$data = array();
									$data["nombre"] = $motivo; 
									$data["e"] = '1';
									//$data["updated"] = date("Y-m-d H:i:s");
									$this->MotivoLlamado->create();
									$this->MotivoLlamado->save($data);
									$motivo_id = $this->MotivoLlamado->id;
								}
								
								if ($categoria_id == "-1") {
									$data = array();
									$data["nombre"] = $categoria; 
									$data["e"] = '1';
									//$data["updated"] = date("Y-m-d H:i:s");
									$this->SintomaCategoria->create();
									$this->SintomaCategoria->save($data);
									$categoria_id = $this->SintomaCategoria->id;
								}
								
								if ($sintoma_id == "-1") {
									$data = array();
									$data["nombre"] = $sintoma;
									if (is_numeric($codigo)) {
										$data["codigo"] = $codigo;
									}								
									$data["e"] = '1';
									//$data["updated"] = date("Y-m-d H:i:s");
									$this->Sintoma->create();
									$this->Sintoma->save($data);
									$sintoma_id = $this->Sintoma->id;
								}

								//if(strtolower($condicion_id) == 'nuevo') {
									$data = array();
									$data["motivo_id"] = $motivo_id;
									$data["categoria_id"] = $categoria_id;
									$data["sintoma_id"] = $sintoma_id;
									$data["e"] = '1';
									//print_r($data);
									//$data["updated"] = date("Y-m-d H:i:s");
									$this->MotivoCategoriaSintoma->create();
									$this->MotivoCategoriaSintoma->save($data);
									$claves[$motivo_id.' '.$categoria_id.' '.$sintoma_id] = true;
								/*} elseif(strtolower($condicion_id) == 'actualizar') {
									// Obtención de clave
									$clave = $motivo_id.' '.$categoria_id.' '.$sintoma_id;
									if (!isset($es_actualizar[$clave])) {
										// Desactivamos registro que cumplan la clave (todo el motor-sistema-subsistema)
										$data = array();
										$data["motivo_id"] = $motivo_id;
										$data["categoria_id"] = $categoria_id;
										$data["sintoma_id"] = $sintoma_id;
										$this->MotivoCategoriaSintoma->updateAll(array("e" => '0'), $data);
										$es_actualizar[$clave] = true;
										//$claves[$motor.'-'.$sistema.'-'.$subsistema] = true;
									}*/
									/*$data = array();
									$data["motivo_id"] = $motivo_id;
									$data["categoria_id"] = $categoria_id;
									$data["sintoma_id"] = $sintoma_id;
									$data["e"] = '1';*/
									//$data["updated"] = date("Y-m-d H:i:s");
									//$this->MotivoCategoriaSintoma->create();
									//$this->MotivoCategoriaSintoma->save($data);
								//} elseif(strtolower($condicion_id) == 'existe') {
									//No se hace nada
								//}
								$filas++;
							} catch(Exception $e) {
							}
						}
						$this->Session->setFlash('Se proceso correctamente el archivo, se registraron '.$filas.' filas.','guardar_exito');
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
				}
			}
		}
	}
	
	public function elemento(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Sistema');
		$this->loadModel('Motor');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('Posiciones_Elemento');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->set('is_post', 'false');
		$this->set('titulo', 'Carga Elemento');
		$this->set('breadcrumb', 'Administración');
		if ($this->request->is('post')){
			if (count($this->request->data)) {
				if(isset($this->request->data["file"])) {
					$this->set('is_post', 'true');
					$invalidos = array();
					$data = $this->request->data["file"];
					$objPHPExcel = new PHPExcel();
					$inputFileName = $data["tmp_name"];
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar cargar el archivo, intente nuevamente.','guardar_error');
						return;
					}
					
					$motores = array();
					$resultados = $this->Motor->find('all', array(
						'fields' => array('Motor.id','Motor.nombre','TipoEmision.nombre','TipoAdmision.nombre'),
						'recursive' => 1
					));
					foreach($resultados as $resultado){
						$motores[strtolower($resultado["Motor"]["nombre"] . ' ' . $resultado["TipoEmision"]["nombre"])] = $resultado["Motor"]["id"];
					}
					
					$sheet = $objPHPExcel->getSheet(0); 
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
					//$highestRow = 100;
					$filas = array();
					try {
						for ($row = 2; $row <= $highestRow; $row++){
							$rowData = array();
							$rowData["motor"] = trim($sheet->getCell('A' . $row)->getCalculatedValue());
							$rowData["seccion"] = trim($sheet->getCell('B' . $row)->getCalculatedValue());
							$rowData["sistema"] = trim($sheet->getCell('C' . $row)->getCalculatedValue());
							$rowData["subsistema"] = trim($sheet->getCell('D' . $row)->getCalculatedValue());
							$rowData["n_id"] = trim($sheet->getCell('E' . $row)->getCalculatedValue());
							$rowData["elemento"] = trim($sheet->getCell('F' . $row)->getCalculatedValue());
							$rowData["posicion"] = trim($sheet->getCell('G' . $row)->getCalculatedValue());
							$rowData["condicion"] = trim($sheet->getCell('H' . $row)->getCalculatedValue());
							
							if (isset($motores[strtolower($rowData["motor"])])) {
								$rowData["motor_id"] = $motores[strtolower($rowData["motor"])];
							} else { 
								$rowData["motor_id"] = -1;
							}
							$resultado = $this->Sistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["sistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Sistema'])) {
								$rowData["sistema_id"] = $resultado['Sistema']["id"];
							} else {
								$rowData["sistema_id"] = -1;
							}
							$resultado = $this->Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["subsistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Subsistema'])) {
								$rowData["subsistema_id"] = $resultado['Subsistema']["id"];
							} else {
								$rowData["subsistema_id"] = -1;
							}
							$resultado = $this->Elemento->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["elemento"]))),
								'recursive' => -1
							));
							if (isset($resultado['Elemento'])) {
								$rowData["elemento_id"] = $resultado['Elemento']["id"];
							} else {
								$rowData["elemento_id"] = -1;
							}
							$resultado = $this->Posiciones_Elemento->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["posicion"]))),
								'recursive' => -1
							));
							if (isset($resultado['Posiciones_Elemento'])) {
								$rowData["posicion_id"] = $resultado['Posiciones_Elemento']["id"];
							} else {
								$rowData["posicion_id"] = -1;
							}
							$resultado = $this->Sistema_Subsistema_Motor_Elemento->find('first', array(
								'conditions' => array('motor_id' => $rowData["motor_id"],'sistema_id' => $rowData["sistema_id"],'subsistema_id' => $rowData["subsistema_id"], 'elemento_id' => $rowData["elemento_id"], 'codigo' => $rowData["n_id"],'posicion_id' => $rowData["posicion_id"]),
								'recursive' => -1
							));
							if (isset($resultado['Sistema_Subsistema_Motor_Elemento'])) {
								$rowData["estado_id"] = 1; // Ambos
							} else {
								$rowData["estado_id"] = 2; // Nuevo
							}
							if(empty($rowData["motor"])||(empty($rowData["seccion"])&&$rowData["seccion"]!='0')||empty($rowData["sistema"])||empty($rowData["subsistema"])||empty($rowData["posicion"])||empty($rowData["n_id"])||empty($rowData["elemento"])||empty($rowData["condicion"])){
								$rowData["estado_id"] = 3; // Inválido
								$invalidos[] = $row - 1;
							}
							$filas[] = $rowData;
							
						}
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
					$this->set('data', $filas);
					
					$sistemas = $this->Sistema->find('all', array(
						'fields' => array('Sistema.id','Sistema.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('sistemas'));
					$motores = $this->Motor->find('all', array(
						'fields' => array('Motor.id','Motor.nombre','TipoEmision.nombre'),
						'order' => array('Motor.nombre' => 'asc'),
						'recursive' => 1
					));
					$this->set(compact('motores'));
					$subsistemas = $this->Subsistema->find('all', array(
						'fields' => array('Subsistema.id','Subsistema.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('subsistemas'));
					
					$posiciones = $this->Posiciones_Elemento->find('all', array(
						'fields' => array('Posiciones_Elemento.id','Posiciones_Elemento.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('posiciones'));
					
					$elementos = $this->Elemento->find('all', array(
						'fields' => array('Elemento.id','Elemento.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('elementos'));
					
					
					if(is_array($invalidos) && count($invalidos)){
						$this->Session->setFlash('Las lineas '.implode(',', $invalidos).' contienen datos inválidos y no podrán seleccionarse.','guardar_error');
					}
				} elseif(isset($this->request->data["form-save"])) {
					try {
						$es_actualizar = array();
						$es_nuevo = false;
						$claves = array();
						$filas = 0;
						// Se trabajan solo filas seleccionadas (solo nuevos, con cualquier condición)
						foreach($this->request->data["seleccion"] as $key => $value){
							$condicion_id = $this->request->data["condicion_id"][$key];
							$motor_id = $this->request->data["motor_id"][$key];
							$sistema_id = $this->request->data["sistema_id"][$key];
							$subsistema_id = $this->request->data["subsistema_id"][$key];
							$elemento_id = $this->request->data["elemento_id"][$key];
							$n_id = $this->request->data["n_id"][$key];
							$posicion_id = $this->request->data["posicion_id"][$key];
							$motor = $this->request->data["motor"][$key];
							$sistema = $this->request->data["sistema"][$key];
							$subsistema = $this->request->data["subsistema"][$key];
							$elemento = $this->request->data["elemento"][$key];
							$posicion = $this->request->data["posicion"][$key];
							
							// Se verifica que exista registro, si no existe, se crea y utiliza ID
							if ($motor_id == -1) {
								$data = array();
								$data["nombre"] = $motor; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Motor->create();
								$this->Motor->save($data);
								$motor_id = $this->Motor->id;
							}
							
							if ($sistema_id == -1) {
								$data = array();
								$data["nombre"] = $sistema; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Sistema->create();
								$this->Sistema->save($data);
								$sistema_id = $this->Sistema->id;
							}
							
							if ($subsistema_id == -1) {
								$data = array();
								$data["nombre"] = $subsistema; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Subsistema->create();
								$this->Subsistema->save($data);
								$subsistema_id = $this->Subsistema->id;
							}
							
							if ($posicion_id == -1) {
								$data = array();
								$data["nombre"] = $posicion; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Posiciones_Elemento->create();
								$this->Posiciones_Elemento->save($data);
								$posicion_id = $this->Posiciones_Elemento->id;
							}
							
							if ($elemento_id == -1) {
								$data = array();
								$data["nombre"] = $elemento; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Elemento->create();
								$this->Elemento->save($data);
								$elemento_id = $this->Elemento->id;
							}
							
							if(strtolower($condicion_id) == 'nuevo') {
								$data = array();
								$data["motor_id"] = $motor_id;
								$data["sistema_id"] = $sistema_id;
								$data["subsistema_id"] = $subsistema_id;
								$data["elemento_id"] = $elemento_id;
								$data["codigo"] = $n_id;
								$data["posicion_id"] = $posicion_id;
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Sistema_Subsistema_Motor_Elemento->create();
								$this->Sistema_Subsistema_Motor_Elemento->save($data);
								$claves[$motor.' '.$sistema.' '.$subsistema] = true;
							} elseif(strtolower($condicion_id) == 'actualizar') {
								// Obtención de clave
								$clave = $motor_id.' '.$sistema_id.' '.$subsistema_id;
								if (!isset($es_actualizar[$clave])) {
									// Desactivamos registro que cumplan la clave (todo el motor-sistema-subsistema)
									$data = array();
									$data["motor_id"] = $motor_id;
									$data["sistema_id"] = $sistema_id;
									$data["subsistema_id"] = $subsistema_id;
									$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("e" => '0', 'updated' => 'now()'), $data);
									$es_actualizar[$clave] = true;
									//$claves[$motor.'-'.$sistema.'-'.$subsistema] = true;
								}
								$data = array();
								$data["motor_id"] = $motor_id;
								$data["sistema_id"] = $sistema_id;
								$data["subsistema_id"] = $subsistema_id;
								$data["elemento_id"] = $elemento_id;
								$data["codigo"] = $n_id;
								$data["codigo_relacion"] = $n_id;
								$data["posicion_id"] = $posicion_id;
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Sistema_Subsistema_Motor_Elemento->create();
								$this->Sistema_Subsistema_Motor_Elemento->save($data);
							} elseif(strtolower($condicion_id) == 'existe') {
								//No se hace nada
							}
							$filas++;
						}
						foreach($claves as $key => $value){
							$this->enviarAlertaCorreo(1, 'Modificación de clave en carga masiva elemento', 'Se actualizó la información de la clave Motor-Sistema-Subsistema -> '.$key.', favor verificar posibles cambios relacionados con la clave haciendo click en el link http://agosto.salmonsoftware.cl/Administracion/Imagenes');
						}
						$this->Session->setFlash('Se proceso correctamente el archivo, se registraron '.$filas.' filas.','guardar_exito');
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
				}
			}
		}
	}
	
	public function diagnostico(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Sistema');
		$this->loadModel('Motor');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('Diagnostico');
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		$this->set('is_post', 'false');
		$this->set('titulo', 'Carga Diagnóstico');
		$this->set('breadcrumb', 'Administración');
		if ($this->request->is('post')){
			if (count($this->request->data)) {
				if(isset($this->request->data["file"])) {
					$this->set('is_post', 'true');
					$invalidos = array();
					$data = $this->request->data["file"];
					$objPHPExcel = new PHPExcel();
					$inputFileName = $data["tmp_name"];
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar cargar el archivo, intente nuevamente.','guardar_error');
						return;
					}
					
					$motores = array();
					$resultados = $this->Motor->find('all', array(
						'fields' => array('Motor.id','Motor.nombre','TipoEmision.nombre','TipoAdmision.nombre'),
						'recursive' => 1
					));
					foreach($resultados as $resultado){
						$motores[strtolower($resultado["Motor"]["nombre"] . ' ' . $resultado["TipoEmision"]["nombre"])] = $resultado["Motor"]["id"];
					}
					
					$sheet = $objPHPExcel->getSheet(0); 
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
					//$highestRow = 100;
					$filas = array();
					try {
						for ($row = 2; $row <= $highestRow; $row++){
							$rowData = array();
							$rowData["motor"] = trim($sheet->getCell('A' . $row)->getCalculatedValue());
							$rowData["seccion"] = trim($sheet->getCell('B' . $row)->getCalculatedValue());
							$rowData["sistema"] = trim($sheet->getCell('C' . $row)->getCalculatedValue());
							$rowData["subsistema"] = trim($sheet->getCell('D' . $row)->getCalculatedValue());
							$rowData["n_id"] = trim($sheet->getCell('E' . $row)->getCalculatedValue());
							$rowData["elemento"] = trim($sheet->getCell('F' . $row)->getCalculatedValue());
							$rowData["diagnostico"] = trim($sheet->getCell('G' . $row)->getCalculatedValue());
							$rowData["condicion"] = trim($sheet->getCell('H' . $row)->getCalculatedValue());
							
							if (isset($motores[strtolower($rowData["motor"])])) {
								$rowData["motor_id"] = $motores[strtolower($rowData["motor"])];
							} else { 
								$rowData["motor_id"] = -1;
							}
							$resultado = $this->Sistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["sistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Sistema'])) {
								$rowData["sistema_id"] = $resultado['Sistema']["id"];
							} else {
								$rowData["sistema_id"] = -1;
							}
							$resultado = $this->Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["subsistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Subsistema'])) {
								$rowData["subsistema_id"] = $resultado['Subsistema']["id"];
							} else {
								$rowData["subsistema_id"] = -1;
							}
							$resultado = $this->Elemento->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["elemento"]))),
								'recursive' => -1
							));
							if (isset($resultado['Elemento'])) {
								$rowData["elemento_id"] = $resultado['Elemento']["id"];
							} else {
								$rowData["elemento_id"] = -1;
							}
							$resultado = $this->Diagnostico->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["diagnostico"]))),
								'recursive' => -1
							));
							if (isset($resultado['Diagnostico'])) {
								$rowData["diagnostico_id"] = $resultado['Diagnostico']["id"];
							} else {
								$rowData["diagnostico_id"] = -1;
							}
							$resultado = $this->MotorSistemaSubsistemaElementoDiagnostico->find('first', array(
								'conditions' => array('motor_id' => $rowData["motor_id"],'sistema_id' => $rowData["sistema_id"],'subsistema_id' => $rowData["subsistema_id"], 'elemento_id' => $rowData["elemento_id"],'diagnostico_id' => $rowData["diagnostico_id"]),
								'recursive' => -1
							));
							if (isset($resultado['MotorSistemaSubsistemaElementoDiagnostico'])) {
								$rowData["estado_id"] = 1; // Ambos
							} else {
								$rowData["estado_id"] = 2; // Nuevo
							}
							if(empty($rowData["motor"])||(empty($rowData["seccion"])&&$rowData["seccion"]!='0')||empty($rowData["sistema"])||empty($rowData["subsistema"])||empty($rowData["n_id"])||empty($rowData["elemento"])||empty($rowData["diagnostico"])){
								$rowData["estado_id"] = 3; // Inválido
								$invalidos[] = $row - 1;
							}
							$filas[] = $rowData;
							
						}
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
					$this->set('data', $filas);
					
					$sistemas = $this->Sistema->find('all', array(
						'fields' => array('Sistema.id','Sistema.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('sistemas'));
					$motores = $this->Motor->find('all', array(
						'fields' => array('Motor.id','Motor.nombre','TipoEmision.nombre'),
						'order' => array('Motor.nombre' => 'asc'),
						'recursive' => 1
					));
					$this->set(compact('motores'));
					$subsistemas = $this->Subsistema->find('all', array(
						'fields' => array('Subsistema.id','Subsistema.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('subsistemas'));
					
					$elementos = $this->Elemento->find('all', array(
						'fields' => array('Elemento.id','Elemento.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('elementos'));
					
					$diagnosticos = $this->Diagnostico->find('all', array(
						'fields' => array('Diagnostico.id','Diagnostico.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('diagnosticos'));
					
					
					if(is_array($invalidos) && count($invalidos)){
						$this->Session->setFlash('Las lineas '.implode(',', $invalidos).' contienen datos inválidos y no podrán seleccionarse.','guardar_error');
					}
				} elseif(isset($this->request->data["form-save"])) {
					try {
						$es_actualizar = array();
						$es_nuevo = false;
						$claves = array();
						$filas = 0;
						// Se trabajan solo filas seleccionadas (solo nuevos, con cualquier condición)
						foreach($this->request->data["seleccion"] as $key => $value){
							$condicion_id = $this->request->data["condicion_id"][$key];
							$motor_id = $this->request->data["motor_id"][$key];
							$sistema_id = $this->request->data["sistema_id"][$key];
							$subsistema_id = $this->request->data["subsistema_id"][$key];
							$elemento_id = $this->request->data["elemento_id"][$key];
							$diagnostico_id = $this->request->data["diagnostico_id"][$key];
							$motor = $this->request->data["motor"][$key];
							$sistema = $this->request->data["sistema"][$key];
							$subsistema = $this->request->data["subsistema"][$key];
							$elemento = $this->request->data["elemento"][$key];
							$diagnostico = $this->request->data["diagnostico"][$key];
							
							// Se verifica que exista registro, si no existe, se crea y utiliza ID
							if ($motor_id == -1) {
								$data = array();
								$data["nombre"] = $motor; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Motor->create();
								$this->Motor->save($data);
								$motor_id = $this->Motor->id;
							}
							
							if ($sistema_id == -1) {
								$data = array();
								$data["nombre"] = $sistema; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Sistema->create();
								$this->Sistema->save($data);
								$sistema_id = $this->Sistema->id;
							}
							
							if ($subsistema_id == -1) {
								$data = array();
								$data["nombre"] = $subsistema; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Subsistema->create();
								$this->Subsistema->save($data);
								$subsistema_id = $this->Subsistema->id;
							}
							
							if ($diagnostico_id == -1) {
								$data = array();
								$data["nombre"] = $diagnostico; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Diagnostico->create();
								$this->Diagnostico->save($data);
								$diagnostico_id = $this->Diagnostico->id;
							}
							
							if ($elemento_id == -1) {
								$data = array();
								$data["nombre"] = $elemento; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Elemento->create();
								$this->Elemento->save($data);
								$elemento_id = $this->Elemento->id;
							}
							
							if(strtolower($condicion_id) == 'nuevo') {
								$data = array();
								$data["motor_id"] = $motor_id;
								$data["sistema_id"] = $sistema_id;
								$data["subsistema_id"] = $subsistema_id;
								$data["elemento_id"] = $elemento_id;
								$data["diagnostico_id"] = $diagnostico_id;
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->MotorSistemaSubsistemaElementoDiagnostico->create();
								$this->MotorSistemaSubsistemaElementoDiagnostico->save($data);
								$claves[$motor.' '.$sistema.' '.$subsistema] = true;
							} elseif(strtolower($condicion_id) == 'actualizar') {
								// Obtención de clave
								$clave = $motor_id.' '.$sistema_id.' '.$subsistema_id;
								if (!isset($es_actualizar[$clave])) {
									// Desactivamos registro que cumplan la clave (todo el motor-sistema-subsistema)
									$data = array();
									$data["motor_id"] = $motor_id;
									$data["sistema_id"] = $sistema_id;
									$data["subsistema_id"] = $subsistema_id;
									//$this->MotorSistemaSubsistemaElementoDiagnostico->updateAll(array("e" => '0', 'updated' => 'now()'), $data);
									$es_actualizar[$clave] = true;
									//$claves[$motor.'-'.$sistema.'-'.$subsistema] = true;
								}
								$data = array();
								$data["motor_id"] = $motor_id;
								$data["sistema_id"] = $sistema_id;
								$data["subsistema_id"] = $subsistema_id;
								$data["elemento_id"] = $elemento_id;
								$data["diagnostico_id"] = $diagnostico_id;
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->MotorSistemaSubsistemaElementoDiagnostico->create();
								$this->MotorSistemaSubsistemaElementoDiagnostico->save($data);
							} elseif(strtolower($condicion_id) == 'existe') {
								//No se hace nada
							}
							$filas++;
						}
						foreach($claves as $key => $value){
							$this->enviarAlertaCorreo(1, 'Modificación de clave en carga masiva diagnostico', 'Se actualizó la información de la clave Motor-Sistema-Subsistema -> '.$key.', favor verificar posibles cambios relacionados con la clave haciendo click en el link http://agosto.salmonsoftware.cl/Administracion/Imagenes');
						}
						$this->Session->setFlash('Se proceso correctamente el archivo, se registraron '.$filas.' filas.','guardar_exito');
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
				}
			}
		}
	}
	
	public function pool(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Sistema');
		$this->loadModel('Motor');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('ElementoPool');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->set('is_post', 'false');
		$this->set('titulo', 'Carga Pool');
		$this->set('breadcrumb', 'Administración');
		
		/*
		$resultados = $this->ElementoPool->find('all', array(
			'recursive' => -1
		));
		foreach($resultados as $resultado){
			$data = $resultado["ElementoPool"];
			unset($data["e"],$data["updated"],$data["id"]);
			$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("e" => '1', 'updated' => 'now()', 'pool' => '1'), $data);
		}
		*/
		
		if ($this->request->is('post')){
			if (count($this->request->data)) {
				if(isset($this->request->data["file"])) {
					$this->set('is_post', 'true');
					$invalidos = array();
					$data = $this->request->data["file"];
					$objPHPExcel = new PHPExcel();
					$inputFileName = $data["tmp_name"];
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar cargar el archivo, intente nuevamente.','guardar_error');
						return;
					}
					
					$motores = array();
					$resultados = $this->Motor->find('all', array(
						'fields' => array('Motor.id','Motor.nombre','TipoEmision.nombre','TipoAdmision.nombre'),
						'recursive' => 1
					));
					foreach($resultados as $resultado){
						$motores[strtolower($resultado["Motor"]["nombre"] . ' ' . $resultado["TipoEmision"]["nombre"])] = $resultado["Motor"]["id"];
					}
					
					$sheet = $objPHPExcel->getSheet(0); 
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
					//$highestRow = 100;
					$filas = array();
					try {
						for ($row = 2; $row <= $highestRow; $row++){
							$rowData = array();
							$rowData["motor"] = trim($sheet->getCell('A' . $row)->getCalculatedValue());
							$rowData["seccion"] = trim($sheet->getCell('B' . $row)->getCalculatedValue());
							$rowData["sistema"] = trim($sheet->getCell('C' . $row)->getCalculatedValue());
							$rowData["subsistema"] = trim($sheet->getCell('D' . $row)->getCalculatedValue());
							$rowData["n_id"] = trim($sheet->getCell('E' . $row)->getCalculatedValue());
							$rowData["elemento"] = trim($sheet->getCell('F' . $row)->getCalculatedValue());
							$rowData["pool"] = trim($sheet->getCell('G' . $row)->getCalculatedValue());
							$rowData["condicion"] = trim($sheet->getCell('H' . $row)->getCalculatedValue());
							
							if (isset($motores[strtolower($rowData["motor"])])) {
								$rowData["motor_id"] = $motores[strtolower($rowData["motor"])];
							} else { 
								$rowData["motor_id"] = -1;
							}
							$resultado = $this->Sistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["sistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Sistema'])) {
								$rowData["sistema_id"] = $resultado['Sistema']["id"];
							} else {
								$rowData["sistema_id"] = -1;
							}
							$resultado = $this->Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["subsistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Subsistema'])) {
								$rowData["subsistema_id"] = $resultado['Subsistema']["id"];
							} else {
								$rowData["subsistema_id"] = -1;
							}
							$resultado = $this->Elemento->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["elemento"]))),
								'recursive' => -1
							));
							if (isset($resultado['Elemento'])) {
								$rowData["elemento_id"] = $resultado['Elemento']["id"];
							} else {
								$rowData["elemento_id"] = -1;
							}
							if($rowData["pool"] != null && $rowData["pool"] != '' && !empty($rowData["pool"])) {
								$rowData["pool"] = '1';
							} else {
								$rowData["pool"] = '0';
							}
							$resultado = $this->ElementoPool->find('first', array(
								'conditions' => array('motor_id' => $rowData["motor_id"],'sistema_id' => $rowData["sistema_id"],'subsistema_id' => $rowData["subsistema_id"], 'elemento_id' => $rowData["elemento_id"], 'codigo' => $rowData["n_id"]),
								'recursive' => -1
							));
							if (isset($resultado['ElementoPool'])) {
								$rowData["estado_id"] = 1; // Ambos
							} else {
								$rowData["estado_id"] = 2; // Nuevo
							}
							if(empty($rowData["motor"])||(empty($rowData["seccion"])&&$rowData["seccion"]!='0')||empty($rowData["sistema"])||empty($rowData["subsistema"])||empty($rowData["n_id"])||empty($rowData["elemento"])){
								$rowData["estado_id"] = 3; // Inválido
								$invalidos[] = $row - 1;
							}
							$filas[] = $rowData;
							
						}
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
					$this->set('data', $filas);
					
					$sistemas = $this->Sistema->find('all', array(
						'fields' => array('Sistema.id','Sistema.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('sistemas'));
					$motores = $this->Motor->find('all', array(
						'fields' => array('Motor.id','Motor.nombre','TipoEmision.nombre'),
						'order' => array('Motor.nombre' => 'asc'),
						'recursive' => 1
					));
					$this->set(compact('motores'));
					$subsistemas = $this->Subsistema->find('all', array(
						'fields' => array('Subsistema.id','Subsistema.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('subsistemas'));
					
					$elementos = $this->Elemento->find('all', array(
						'fields' => array('Elemento.id','Elemento.nombre'),
						'order' => array('nombre' => 'asc'),
						'recursive' => -1
					));
					$this->set(compact('elementos'));
					
					
					if(is_array($invalidos) && count($invalidos)){
						$this->Session->setFlash('Las lineas '.implode(',', $invalidos).' contienen datos inválidos y no podrán seleccionarse.','guardar_error');
					}
				} elseif(isset($this->request->data["form-save"])) {
					try {
						$es_actualizar = array();
						$es_nuevo = false;
						$claves = array();
						$filas = 0;
						// Se trabajan solo filas seleccionadas (solo nuevos, con cualquier condición)
						foreach($this->request->data["seleccion"] as $key => $value){
							$condicion_id = $this->request->data["condicion_id"][$key];
							$motor_id = $this->request->data["motor_id"][$key];
							$sistema_id = $this->request->data["sistema_id"][$key];
							$subsistema_id = $this->request->data["subsistema_id"][$key];
							$elemento_id = $this->request->data["elemento_id"][$key];
							$n_id = $this->request->data["n_id"][$key];
							$motor = $this->request->data["motor"][$key];
							$sistema = $this->request->data["sistema"][$key];
							$subsistema = $this->request->data["subsistema"][$key];
							$elemento = $this->request->data["elemento"][$key];
							$pool = $this->request->data["pool"][$key];
							
							// Se verifica que exista registro, si no existe, se crea y utiliza ID
							if ($motor_id == -1) {
								$data = array();
								$data["nombre"] = $motor; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Motor->create();
								$this->Motor->save($data);
								$motor_id = $this->Motor->id;
							}
							
							if ($sistema_id == -1) {
								$data = array();
								$data["nombre"] = $sistema; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Sistema->create();
								$this->Sistema->save($data);
								$sistema_id = $this->Sistema->id;
							}
							
							if ($subsistema_id == -1) {
								$data = array();
								$data["nombre"] = $subsistema; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Subsistema->create();
								$this->Subsistema->save($data);
								$subsistema_id = $this->Subsistema->id;
							}
							
							if ($elemento_id == -1) {
								$data = array();
								$data["nombre"] = $elemento; 
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Elemento->create();
								$this->Elemento->save($data);
								$elemento_id = $this->Elemento->id;
							}
							
							if(strtolower($condicion_id) == 'nuevo') {
								$data = array();
								$data["motor_id"] = $motor_id;
								$data["sistema_id"] = $sistema_id;
								$data["subsistema_id"] = $subsistema_id;
								$data["elemento_id"] = $elemento_id;
								$data["codigo"] = $n_id;
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->ElementoPool->create();
								$this->ElementoPool->save($data);
								$claves[$motor.' '.$sistema.' '.$subsistema] = true;
								
								// Se actualiza tabla elementos
								$data = array();
								$data["motor_id"] = $motor_id;
								$data["sistema_id"] = $sistema_id;
								$data["subsistema_id"] = $subsistema_id;
								$data["elemento_id"] = $elemento_id;
								$data["codigo"] = $n_id;
								$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("e" => '1', 'updated' => 'now()', 'pool' => '1'), $data);
							} elseif(strtolower($condicion_id) == 'actualizar') {
								// Obtención de clave
								$clave = $motor_id.' '.$sistema_id.' '.$subsistema_id;
								if (!isset($es_actualizar[$clave])) {
									// Desactivamos registro que cumplan la clave (todo el motor-sistema-subsistema)
									$data = array();
									$data["motor_id"] = $motor_id;
									$data["sistema_id"] = $sistema_id;
									$data["subsistema_id"] = $subsistema_id;
									$this->ElementoPool->updateAll(array("e" => '0', 'updated' => 'now()'), $data);
									$es_actualizar[$clave] = true;
									//$claves[$motor.'-'.$sistema.'-'.$subsistema] = true;
								}
								$data = array();
								$data["motor_id"] = $motor_id;
								$data["sistema_id"] = $sistema_id;
								$data["subsistema_id"] = $subsistema_id;
								$data["elemento_id"] = $elemento_id;
								$data["codigo"] = $n_id;
								$data["e"] = '1';
								$data["updated"] = date("Y-m-d H:i:s");
								$this->ElementoPool->create();
								$this->ElementoPool->save($data);
								
								// Se actualiza tabla elementos
								$data = array();
								$data["motor_id"] = $motor_id;
								$data["sistema_id"] = $sistema_id;
								$data["subsistema_id"] = $subsistema_id;
								$data["elemento_id"] = $elemento_id;
								$data["codigo"] = $n_id;
								$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("e" => '1', 'updated' => 'now()', 'pool' => '1'), $data);
							} elseif(strtolower($condicion_id) == 'existe') {
								//No se hace nada
							}
							$filas++;
						}
						foreach($claves as $key => $value){
							$this->enviarAlertaCorreo(1, 'Modificación de clave en carga masiva pool', 'Se actualizó la información de la clave Motor-Sistema-Subsistema -> '.$key.', favor verificar posibles cambios relacionados con la clave haciendo click en el link http://agosto.salmonsoftware.cl/Administracion/Imagenes');
						}
						$this->Session->setFlash('Se proceso correctamente el archivo, se registraron '.$filas.' filas.','guardar_exito');
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
				}
			}
		}
	}
	
	public function backlogspecto(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Backlog');
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		$this->loadModel('Unidad');
		$this->loadModel('Sistema');
		$this->loadModel('Sintoma');
		$this->loadModel('SintomaCategoria');
		$this->loadModel('Criticidad');
		$this->loadModel('ResponsableBacklog');
		$this->set('is_post', 'false');
		$this->set('titulo', 'Carga Backlog Specto');
		$this->set('breadcrumb', 'Administración');
		if ($this->request->is('post')){
			if (count($this->request->data)) {
				if(isset($this->request->data["file"])) {
					$this->set('is_post', 'true');
					$invalidos = array();
                                        $wrong =  array();
                                        $rowsempty = 0;
					$data = $this->request->data["file"];
					$objPHPExcel = new PHPExcel();
					$inputFileName = $data["tmp_name"];
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar cargar el archivo, intente nuevamente.','guardar_error');
						return;
					}
					
					$sheet = $objPHPExcel->getSheet(0); 
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
					$filas = array();
					try {
						for ($row = 2; $row <= $highestRow; $row++){
							$rowData = array();
							$rowData["faena"] = trim($sheet->getCell('A' . $row)->getCalculatedValue());
							$rowData["flota"] = trim($sheet->getCell('B' . $row)->getCalculatedValue());
							$rowData["equipo"] = trim($sheet->getCell('C' . $row)->getCalculatedValue());
							$rowData["criticidad"] = trim($sheet->getCell('D' . $row)->getCalculatedValue());
							$rowData["responsable"] = trim($sheet->getCell('E' . $row)->getCalculatedValue());
							$rowData["sistema"] = trim($sheet->getCell('F' . $row)->getCalculatedValue());
							$rowData["categoria"] = trim($sheet->getCell('G' . $row)->getCalculatedValue());
							$rowData["sintoma"] = trim($sheet->getCell('H' . $row)->getCalculatedValue());
							$rowData["comentario"] = trim($sheet->getCell('I' . $row)->getCalculatedValue());
							$rowData["tiempo"] = trim($sheet->getCell('J' . $row)->getCalculatedValue());
							$rowData["estado"] = 1;
							$resultado = $this->Faena->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["faena"]))),
								'recursive' => -1
							));
							if (isset($resultado['Faena'])) {
								$rowData["faena_id"] = $resultado['Faena']["id"];
							} else {
								$rowData["faena_id"] = -1;
                                                                $rowData["estado"] = 2;
							}
							
							$resultado = $this->Flota->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["flota"]))),
								'recursive' => -1
							));
							if (isset($resultado['Flota'])) {
								$rowData["flota_id"] = $resultado['Flota']["id"];
							} else {
								$rowData["flota_id"] = -1;
                                                                $rowData["estado"] = 2;
							}
							
							$resultado = $this->Unidad->find('first', array(
								'conditions' => array('faena_id' => $rowData["faena_id"],'flota_id' => $rowData["flota_id"],'unidad' => $rowData["equipo"]),
								'recursive' => -1
							));
							if (isset($resultado['Unidad'])) {
								$rowData["equipo_id"] = $resultado['Unidad']["id"];
							} else {
								$rowData["equipo_id"] = -1;
                                                                $rowData["estado"] = 2;
							}
							
							$resultado = $this->Sistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["sistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Sistema'])) {
								$rowData["sistema_id"] = $resultado['Sistema']["id"];
							} else {
								$rowData["sistema_id"] = -1;
                                                                $rowData["estado"] = 2;
							}
							
							$resultado = $this->Sintoma->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["sintoma"]))),
								'recursive' => -1
							));
							if (isset($resultado['Sintoma'])) {
								$rowData["sintoma_id"] = $resultado['Sintoma']["id"];
							} else {
								$rowData["sintoma_id"] = -1;
                                                                $rowData["estado"] = 2;
							}
							
							$resultado = $this->Criticidad->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["criticidad"]))),
								'recursive' => -1
							));
							if (isset($resultado['Criticidad'])) {
								$rowData["criticidad_id"] = $resultado['Criticidad']["id"];
							} else {
								$rowData["criticidad_id"] = -1;
                                                                $rowData["estado"] = 2;
							}
							
							$resultado = $this->ResponsableBacklog->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["responsable"]))),
								'recursive' => -1
							));
							if (isset($resultado['ResponsableBacklog'])) {
								$rowData["responsable_id"] = $resultado['ResponsableBacklog']["id"];
							} else {
								$rowData["responsable_id"] = -1;
                                                                $rowData["estado"] = 2;
							}
							
							$resultado = $this->SintomaCategoria->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["categoria"]))),
								'recursive' => -1
							));
							if (isset($resultado['SintomaCategoria'])) {
								$rowData["categoria_id"] = $resultado['SintomaCategoria']["id"];
							} else {
								$rowData["categoria_id"] = -1;
                                                                $rowData["estado"] = 2;
							}
							
							if(empty($rowData["faena"])||empty($rowData["flota"])||empty($rowData["equipo"])||empty($rowData["criticidad"])||empty($rowData["responsable"])||empty($rowData["sintoma"])||empty($rowData["sistema"])||empty($rowData["categoria"])){
								$invalidos[] = $row - 1;
								$rowData["estado"] = 2;
							}
                                                        
                                                        
                                                        /***** Veerifica si la columna faena viene mas de 2 veces vacías seguidas salta la excepción y temina la ajecución *****/
                                                        if(empty($rowData["faena"])){
                                                            $rowsempty += 1;
                                                            if($rowsempty >= 2){
                                                                throw new Exception("Archivo con errores o filas vacías.");
                                                            }
                                                        }else{
                                                            $rowsempty = 0;
                                                        }
                                                        
                                                        /***** identifica las filas con problemas *****/
                                                        //FAENA
                                                        if(empty($rowData["faena"])){
                                                            $wrong[] = 'Columna Faena - campo (A'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["faena_id"] == -1){
                                                            $wrong[] = 'Columna Faena - campo (A'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //FLOTA
                                                        if(empty($rowData["flota"])){
                                                            $wrong[] = 'Columna Flota - campo (B'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["flota_id"] == -1){
                                                            $wrong[] = 'Columna Flota - campo (B'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //EQUIPO
                                                        if(empty($rowData["equipo"])){
                                                            $wrong[] = 'Columna Equipo - campo (C'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["equipo_id"] == -1){
                                                            $wrong[] = 'Columna Equipo - campo (C'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //CITRICIDAD
                                                        if(empty($rowData["criticidad"])){
                                                            $wrong[] = 'Columna Citricidad - campo (D'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["criticidad_id"] == -1){
                                                            $wrong[] = 'Columna Citricidad - campo (D'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //RESPONSABLE
                                                        if(empty($rowData["responsable"])){
                                                            $wrong[] = 'Columna Responsable - campo (E'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["responsable_id"] == -1){
                                                            $wrong[] = 'Columna Responsable - campo (E'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //SISTEMA
                                                        if(empty($rowData["sistema"])){
                                                            $wrong[] = 'Columna Sistema - campo (F'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["sistema_id"] == -1){
                                                            $wrong[] = 'Columna Sistema - campo (F'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //CATEGORIA SINTOMA
                                                        if(empty($rowData["categoria"])){
                                                            $wrong[] = 'Columna Categoria Sintoma - campo (G'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["categoria_id"] == -1){
                                                            $wrong[] = 'Columna Categoria Sintoma - campo (G'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //SINTOMA
                                                        if(empty($rowData["sintoma"])){
                                                            $wrong[] = 'Columna Sintoma - campo (H'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["sintoma_id"] == -1){
                                                            $wrong[] = 'Columna Sintoma - campo (H'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        
                                                        
                                                        /***** Cuento las filas que tienen error *****/
                                                        if($rowData["estado"] == 2){
                                                            $rowswitherror += 1;
                                                        }
                                                        
                                                        
							$filas[] = $rowData;
						}
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
					$this->set('data', $filas);
					
					/*if(is_array($invalidos) && count($invalidos)){
						$this->Session->setFlash('Las lineas '.implode(',', $invalidos).' contienen datos inválidos y no podrán seleccionarse.','guardar_error');
					}*/
                                        
                                        /***** Si existen problemas con el documento salta la alerta *****/
                                        If(is_array($wrong) && count($wrong) || is_array($invalidos) && count($invalidos) ){
                                            $prom = (count($filas) - $rowswitherror). ' de '.count($filas);  
                                            $this->Session->setFlash('Solo se procesaran ' . $prom .' registros. Se encontraron los siguientes problemas: ' . implode(', ', $wrong) ,'guardar_error');
                                        }
                                        
                                        
				} elseif(isset($this->request->data["form-save"])) {
					try {
						$es_actualizar = array();
						$es_nuevo = false;
						$claves = array();
						$filas = 0;
						// Se trabajan solo filas seleccionadas (solo nuevos, con cualquier condición)
						foreach($this->request->data["seleccion"] as $key => $value){
							$faena_id = $this->request->data["faena_id"][$key];
							$flota_id = $this->request->data["flota_id"][$key];
							$unidad_id = $this->request->data["equipo_id"][$key];
							$criticidad_id = $this->request->data["criticidad_id"][$key];
							$responsable_id = $this->request->data["responsable_id"][$key];
							$sistema_id = $this->request->data["sistema_id"][$key];
							$categoria_id = $this->request->data["categoria_id"][$key];
							$sintoma_id = $this->request->data["sintoma_id"][$key];
							$comentario = $this->request->data["comentario"][$key];
							$tiempo = $this->request->data["tiempo"][$key];
							
							
							$data["usuario_id"] = $this->getUsuarioID();
							$data["fecha_creacion"] = date("Y-m-d H:i:s");
							$data["estado_id"] = "8";
							$data["creacion_id"] = "2";
							$data["equipo_id"] = $unidad_id;
							$data["flota_id"] = $flota_id;
							$data["faena_id"] = $faena_id;
							if($tiempo != "" && is_numeric($tiempo)) {
								$data["tiempo_estimado"] = $tiempo;
							}
							$data["comentario"] = $comentario;
							$data["criticidad_id"] = $criticidad_id;
							$data["responsable_id"] = $responsable_id;
							$data["sistema_id"] = $sistema_id;
							$data["categoria_sintoma_id"] = $categoria_id;
							$data["sintoma_id"] = $sintoma_id;

							$this->Backlog->create();
							$this->Backlog->save($data);
							$filas++;
						}
						$this->redirect("/Backlog/");
						$this->Session->setFlash('Se proceso correctamente el archivo, se registraron '.$filas.' filas.','guardar_exito');
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
				}
			}
		}
	}
	
	public function backlogweb(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Backlog');
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		$this->loadModel('Unidad');
		$this->loadModel('Sintoma');
		$this->loadModel('SintomaCategoria');
		$this->loadModel('Criticidad');
		$this->loadModel('ResponsableBacklog');
		
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('Posiciones_Elemento');
		$this->loadModel('Diagnostico');
		$this->loadModel('IntervencionElementos');
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		
                $this->loadModel('LugarCreacion');
                
		$this->set('is_post', 'false');
		$this->set('titulo', 'Carga Backlog Web');
		$this->set('breadcrumb', 'Administración');
                
                $backlogs = array();
		if ($this->request->is('post')){
			if (count($this->request->data)) {
				if(isset($this->request->data["file"])) {
					$this->set('is_post', 'true');
					$invalidos = array();
                                        $wrong = array();
                                        $rowswitherror = 0;
                                        $rowsempty = 0;
					$data = $this->request->data["file"];
					$objPHPExcel = new PHPExcel();
					$inputFileName = $data["tmp_name"];
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar cargar el archivo, intente nuevamente.','guardar_error');
						return;
					}
					
					$sheet = $objPHPExcel->getSheet(0); 
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
					//$highestRow = 100;
					$filas = array();
					try {
						for ($row = 2; $row <= $highestRow; $row++){
							$rowData = array();
							$rowData["faena"] = trim($sheet->getCell('A' . $row)->getCalculatedValue());
							$rowData["flota"] = trim($sheet->getCell('B' . $row)->getCalculatedValue());
							$rowData["equipo"] = trim($sheet->getCell('C' . $row)->getCalculatedValue());
							$rowData["criticidad"] = trim($sheet->getCell('D' . $row)->getCalculatedValue());
							$rowData["responsable"] = trim($sheet->getCell('E' . $row)->getCalculatedValue());
							$rowData["categoria"] = trim($sheet->getCell('F' . $row)->getCalculatedValue());
							$rowData["sintoma"] = trim($sheet->getCell('G' . $row)->getCalculatedValue());
							$rowData["sistema"] = trim($sheet->getCell('H' . $row)->getCalculatedValue());
							$rowData["subsistema"] = trim($sheet->getCell('I' . $row)->getCalculatedValue());
							$rowData["pos_subsistema"] = trim($sheet->getCell('J' . $row)->getCalculatedValue());
							$rowData["id_elemento"] = trim($sheet->getCell('K' . $row)->getCalculatedValue());
							$rowData["elemento"] = trim($sheet->getCell('L' . $row)->getCalculatedValue());
							$rowData["pos_elemento"] = trim($sheet->getCell('M' . $row)->getCalculatedValue());
							$rowData["diagnostico"] = trim($sheet->getCell('N' . $row)->getCalculatedValue());
							$rowData["part_number"] = trim($sheet->getCell('O' . $row)->getCalculatedValue());
							$rowData["comentario"] = trim($sheet->getCell('P' . $row)->getCalculatedValue());
							$rowData["tiempo"] = trim($sheet->getCell('Q' . $row)->getCalculatedValue());
							$rowData["estado"] = 1;
                                                        
                                                        $rowData["creacion"] = trim($sheet->getCell('R' . $row)->getCalculatedValue());
                                                        
                                                        $resultado = $this->Faena->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["faena"]))),
								'recursive' => -1
							));
							if (isset($resultado['Faena'])) {
								$rowData["faena_id"] = $resultado['Faena']["id"];
                                                                //$rowData["faena"] = $resultado['Faena']["nombre"];
							} else {
								$rowData["faena_id"] = -1;
								$rowData["estado"] = 2;
							}
							
							$resultado = $this->Flota->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["flota"]))),
								'recursive' => -1
							));
							if (isset($resultado['Flota'])) {
								$rowData["flota_id"] = $resultado['Flota']["id"];
                                                                //$rowData["flota"] = $resultado['Flota']["nombre"];
							} else {
								$rowData["flota_id"] = -1;
								$rowData["estado"] = 2;
							}
							$rowData["motor_id"] = -1;
							$resultado = $this->Unidad->find('first', array(
								'conditions' => array('faena_id' => $rowData["faena_id"],'flota_id' => $rowData["flota_id"],'unidad' => $rowData["equipo"]),
								'recursive' => -1
							));
							if (isset($resultado['Unidad'])) {
								$rowData["equipo_id"] = $resultado['Unidad']["id"];
                                                                //$rowData["equipo_str"] = $resultado['Unidad']["nombre"];
								$rowData["motor_id"] = $resultado['Unidad']["motor_id"];
							} else {
								$rowData["equipo_id"] = -1;
								$rowData["estado"] = 2;
							}
							
							//$tmp = explode("-", $rowData["sintoma"]);
							if (is_numeric(trim($tmp[0]))) {
								$resultado = $this->Sintoma->find('first', array(
									'conditions' => array('LOWER(TRIM(nombre))' => strtolower((trim($rowData["sintoma"])))),//, 'codigo' => trim($tmp[0])),
									'recursive' => -1
								));
								if (isset($resultado['Sintoma'])) {
									$rowData["sintoma_id"] = $resultado['Sintoma']["id"];
                                                                        //$rowData["sintoma"] = $resultado['Sintoma']["nombre"];
								} else {
									$rowData["sintoma_id"] = -1;
									$rowData["estado"] = 2;
								}
							} else {
								$resultado = $this->Sintoma->find('first', array(
									'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["sintoma"]))),
									'recursive' => -1
								));
								if (isset($resultado['Sintoma'])) {
									$rowData["sintoma_id"] = $resultado['Sintoma']["id"];
                                                                        //$rowData["sintoma"] = $resultado['Sintoma']["nombre"];
								} else {
									$rowData["sintoma_id"] = -1;
									$rowData["estado"] = 2;
								}
							}
							
							$resultado = $this->Criticidad->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["criticidad"]))),
								'recursive' => -1
							));
							if (isset($resultado['Criticidad'])) {
								$rowData["criticidad_id"] = $resultado['Criticidad']["id"];
                                                                //$rowData["criticidad"] = $resultado['Criticidad']["nombre"];
							} else {
								$rowData["criticidad_id"] = -1;
								$rowData["estado"] = 2;
							}
							
							$resultado = $this->ResponsableBacklog->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["responsable"]))),
								'recursive' => -1
							));
							if (isset($resultado['ResponsableBacklog'])) {
								$rowData["responsable_id"] = $resultado['ResponsableBacklog']["id"];
							} else {
								$rowData["responsable_id"] = -1;
								$rowData["estado"] = 2;
							}
							
							$resultado = $this->SintomaCategoria->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["categoria"]))),
								'recursive' => -1
							));
							if (isset($resultado['SintomaCategoria'])) {
								$rowData["categoria_id"] = $resultado['SintomaCategoria']["id"];
							} else {
								$rowData["categoria_id"] = -1;
								$rowData["estado"] = 2;
							}
							
							// Verificacion de elementos
							$resultado = $this->Sistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["sistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Sistema'])) {
								$rowData["sistema_id"] = $resultado['Sistema']["id"];
                                                                //$rowData["sistema"] = $resultado['Sistema']["nombre"];
							} else {
								$rowData["sistema_id"] = -1;
								$rowData["estado"] = 2;
							}
							
							$resultado = $this->Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["subsistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Subsistema'])) {
								$rowData["subsistema_id"] = $resultado['Subsistema']["id"];
                                                                //$rowData["subsistema_id"] = $resultado['Subsistema']["nombre"];
							} else {
								$rowData["subsistema_id"] = -1;
								$rowData["estado"] = 2;
							}
							
							$resultado = $this->Posiciones_Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["pos_subsistema"]))),
								'recursive' => -1
							));
							if (isset($resultado['Posiciones_Subsistema'])) {
								$rowData["pos_subsistema_id"] = $resultado['Posiciones_Subsistema']["id"];
							} else {
								$rowData["pos_subsistema_id"] = -1;
								$rowData["estado"] = 2;
							}
							
							$resultado = $this->Elemento->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["elemento"]))),
								'recursive' => -1
							));
                                                        //print_r($resultado);
							if (isset($resultado['Elemento'])) {
								$rowData["elemento_id"] = $resultado['Elemento']["id"];
                                                                //$rowData["elemento"] = $resultado['Elemento']["nombre"];
							} else {
								$rowData["elemento_id"] = -1;
								$rowData["estado"] = 2;
							}
							
							$resultado = $this->Posiciones_Elemento->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["pos_elemento"]))),
								'recursive' => -1
							));
							if (isset($resultado['Posiciones_Elemento'])) {
								$rowData["pos_elemento_id"] = $resultado['Posiciones_Elemento']["id"];
							} else {
								$rowData["pos_elemento_id"] = -1;
								$rowData["estado"] = 2;
							}
							
							$resultado = $this->Diagnostico->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["diagnostico"]))),
								'recursive' => -1
							));
							if (isset($resultado['Diagnostico'])) {
								$rowData["diagnostico_id"] = $resultado['Diagnostico']["id"];
							} else {
								$rowData["diagnostico_id"] = -1;
								$rowData["estado"] = 2;
							}
							
                                                        $resultado = $this->LugarCreacion->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(($rowData["creacion"]))),
								'recursive' => -1
							));
							if (isset($resultado['LugarCreacion'])) {
								$rowData["creacion_id"] = $resultado['LugarCreacion']['id'];
							} else {
								$rowData["creacion_id"] = -1;
								$rowData["estado"] = 2;
							}
                                                        
							
							if(empty($rowData["faena"])||
                                                                empty($rowData["flota"])||
                                                                empty($rowData["equipo"])||
                                                                empty($rowData["criticidad"])||
                                                                empty($rowData["responsable"])||
                                                                empty($rowData["sintoma"])||
                                                                empty($rowData["categoria"])||
                                                                empty($rowData["sistema"])||
                                                                empty($rowData["subsistema"])||
                                                                empty($rowData["pos_subsistema"])||
                                                                empty($rowData["id_elemento"])||
                                                                empty($rowData["elemento"])||
                                                                empty($rowData["pos_elemento"])||
                                                                empty($rowData["diagnostico"])||
                                                                empty($rowData["creacion"])
                                                                //||empty($rowData["part_number"]) /** NO NECESARIO ** /
                                                                ){
								$invalidos[] = $row - 1;
								$rowData["estado"] = 2;
							}
                                                        
                                                        
                                                        /***** Veerifica si la columna faena viene mas de 2 veces vacías seguidas salta la excepción y temina la ajecución *****/
                                                        if(empty($rowData["faena"])){
                                                            $rowsempty += 1;
                                                            if($rowsempty >= 2){
                                                                throw new Exception("Archivo con errores o filas vacías.");
                                                            }
                                                        }else{
                                                            $rowsempty = 0;
                                                        }
                                                        
                                                        /***** identifica las filas con problemas *****/
                                                        //FAENA
                                                        if(empty($rowData["faena"])){
                                                            $wrong[] = 'Columna Faena - campo (A'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["faena_id"] == -1){
                                                            $wrong[] = 'Columna Faena - campo (A'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //FLOTA
                                                        if(empty($rowData["flota"])){
                                                            $wrong[] = 'Columna Flota - campo (B'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["flota_id"] == -1){
                                                            $wrong[] = 'Columna Flota - campo (B'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //EQUIPO
                                                        if(empty($rowData["equipo"])){
                                                            $wrong[] = 'Columna Equipo - campo (C'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["equipo_id"] == -1){
                                                            $wrong[] = 'Columna Equipo - campo (C'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //CITRICIDAD
                                                        if(empty($rowData["criticidad"])){
                                                            $wrong[] = 'Columna Citricidad - campo (D'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["criticidad_id"] == -1){
                                                            $wrong[] = 'Columna Citricidad - campo (D'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //RESPONSABLE
                                                        if(empty($rowData["responsable"])){
                                                            $wrong[] = 'Columna Responsable - campo (E'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["responsable_id"] == -1){
                                                            $wrong[] = 'Columna Responsable - campo (E'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //CATEGORIA SINTOMA
                                                        if(empty($rowData["categoria"])){
                                                            $wrong[] = 'Columna Categoria Sintoma - campo (F'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["categoria_id"] == -1){
                                                            $wrong[] = 'Columna Categoria Sintoma - campo (F'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //SINTOMA
                                                        if(empty($rowData["sintoma"])){
                                                            $wrong[] = 'Columna Sintoma - campo (G'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["sintoma_id"] == -1){
                                                            $wrong[] = 'Columna Sintoma - campo (G'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //SISTEMA
                                                        if(empty($rowData["sistema"])){
                                                            $wrong[] = 'Columna Sistema - campo (H'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["sistema_id"] == -1){
                                                            $wrong[] = 'Columna Sistema - campo (H'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //SUBSISTEMA
                                                        if(empty($rowData["subsistema"])){
                                                            $wrong[] = 'Columna SubSistema - campo (I'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["subsistema_id"] == -1){
                                                            $wrong[] = 'Columna SubSistema - campo (I'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //POS. SUBSISTEMA
                                                        if(empty($rowData["pos_subsistema"])){
                                                            $wrong[] = 'Columna Pos. SubSistema - campo (J'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["pos_subsistema_id"] == -1){
                                                            $wrong[] = 'Columna Pos. SubSistema - campo (J'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //ID ELEMENTO
                                                        if(empty($rowData["id_elemento"])){
                                                            $wrong[] = 'Columna ID Elemento - campo (K'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["id_elemento"] == -1){
                                                            $wrong[] = 'Columna ID Elemento - campo (K'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //ELEMENTO
                                                        if(empty($rowData["elemento"])){
                                                            $wrong[] = 'Columna Elemento - campo (L'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["elemento_id"] == -1){
                                                            $wrong[] = 'Columna Elemento - campo (L'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //POS ELEMENTO
                                                        if(empty($rowData["pos_elemento"])){
                                                            $wrong[] = 'Columna Pos. Elemento - campo (M'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["pos_elemento_id"] == -1){
                                                            $wrong[] = 'Columna Pos. Elemento - campo (M'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        //DIAGNOSTICO
                                                        if(empty($rowData["diagnostico"])){
                                                            $wrong[] = 'Columna Diagnostico - campo (N'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["diagnostico_id"] == -1){
                                                            $wrong[] = 'Columna Diagnostico - campo (N'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        
                                                        //LUGAR CREACION
                                                        if(empty($rowData["creacion"])){
                                                            $wrong[] = 'Columna Creacion - campo (R'.($row - 1). ') - no puede ser vacío.';
                                                        }elseif($rowData["creacion_id"] == -1){
                                                            $wrong[] = 'Columna Creacion - campo (R'.($row - 1). ') - Valor no encontrado, verifique que este correctamente escrito.';
                                                        }
                                                        
							// si la relación sistema-subsistema-elemento-etc no existe en las matrices el registro se considera nulo.
							$resultado = $this->MotorSistemaSubsistemaPosicion->find('first', array(
								'conditions' => array('sistema_id' => $rowData["sistema_id"], 'motor_id' => $rowData["motor_id"], 'subsistema_id' => $rowData["subsistema_id"], 'posicion_id' => $rowData["pos_subsistema_id"]),
								'recursive' => -1
							));
							if (isset($resultado['MotorSistemaSubsistemaPosicion'])) {
							} else {
								//$rowData["estado"] = 2;
							}
							
							$resultado = $this->Sistema_Subsistema_Motor_Elemento->find('first', array(
								'conditions' => array('sistema_id' => $rowData["sistema_id"], 'motor_id' => $rowData["motor_id"], 'subsistema_id' => $rowData["subsistema_id"], 'posicion_id' => $rowData["pos_elemento_id"], 'elemento_id' => $rowData["elemento_id"], 'codigo' => $rowData["id_elemento"]),
								'recursive' => -1
							));
							if (isset($resultado['Sistema_Subsistema_Motor_Elemento'])) {
							} else {
								$rowData["estado"] = 2;
							}
							
							$resultado = $this->MotorSistemaSubsistemaElementoDiagnostico->find('first', array(
								'conditions' => array('sistema_id' => $rowData["sistema_id"], 'motor_id' => $rowData["motor_id"], 'subsistema_id' => $rowData["subsistema_id"], 'diagnostico_id' => $rowData["diagnostico_id"],'elemento_id' => $rowData["elemento_id"]),
								'recursive' => -1
							));
							if (isset($resultado['MotorSistemaSubsistemaElementoDiagnostico'])) {
							} else {
								$rowData["estado"] = 2;
							}
							
							/***** Cuento las filas que tienen error *****/
                                                        if($rowData["estado"] == 2){
                                                            $rowswitherror += 1;
                                                        }
                                                        
                                                        /***** Si existen problemas con el documento salta la alerta *****/
                                                        If(is_array($wrong) && count($wrong) || is_array($invalidos) && count($invalidos) ){
                                                            
                                                        }else{
                                                            $rowData["estado"] = 1;
                                                        }
                                                        
							$filas[] = $rowData;
						}
                                                
                                                
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
					$this->set('data', $filas);
                                        
                                        /***** Si existen problemas con el documento salta la alerta *****/
                                        If(is_array($wrong) && count($wrong) || is_array($invalidos) && count($invalidos) ){
                                            $prom = (count($filas) - $rowswitherror). ' de '.count($filas);  
                                            $this->Session->setFlash('Solo se procesaran ' . $prom .' registros. Se encontraron los siguientes problemas: ' . implode(', ', $wrong) ,'guardar_error');
                                        }
                                        
				} elseif(isset($this->request->data["form-save"])) {
					try {
						$es_actualizar = array();
						$es_nuevo = false;
						$claves = array();
						$filas = 0;
						// Se trabajan solo filas seleccionadas (solo nuevos, con cualquier condición)
						foreach($this->request->data["seleccion"] as $key => $value){
							$faena_id = $this->request->data["faena_id"][$key];
							$flota_id = $this->request->data["flota_id"][$key];
							$unidad_id = $this->request->data["equipo_id"][$key];
							$criticidad_id = $this->request->data["criticidad_id"][$key];
							$responsable_id = $this->request->data["responsable_id"][$key];
							$categoria_id = $this->request->data["categoria_id"][$key];
							$sintoma_id = $this->request->data["sintoma_id"][$key];
							
							$sistema_id = $this->request->data["sistema_id"][$key];
							$subsistema_id = $this->request->data["subsistema_id"][$key];
							$pos_subsistema_id = $this->request->data["pos_subsistema_id"][$key];
							$id_elemento = $this->request->data["id_elemento"][$key];
							$elemento_id = $this->request->data["elemento_id"][$key];
							$pos_elemento_id = $this->request->data["pos_elemento_id"][$key];
							$diagnostico_id = $this->request->data["diagnostico_id"][$key];
							$part_number = $this->request->data["part_number"][$key];
							
							$comentario = $this->request->data["comentario"][$key];
							$tiempo = $this->request->data["tiempo"][$key];
                                                        
                                                        $creacion = $this->request->data["id_creacion"][$key];
                                                        
                                                        
                                                        /** creacion array backlogs para correo **/
                                                        $fecha_creacion = date("Y-m-d H:i:s");
                                                        
                                                        $bklg['faena'] = $this->request->data["faena"][$key];;
                                                        $bklg['flota']= $this->request->data["flota"][$key];
                                                        $bklg['equipo']= $this->request->data["equipo"][$key];
                                                        $bklg['criticidad'] = $this->request->data["criticidad"][$key];
                                                        $bklg['sintoma'] = $this->request->data["sintoma"][$key];
                                                        $bklg['sistema']= $this->request->data["sistema"][$key];
                                                        $bklg['subSistema']= $this->request->data["subsistema"][$key];
                                                        $bklg['fecha'] = $fecha_creacion;
                                                        $bklg['comentario'] = $this->request->data["comentario"][$key];;
                                                        
                                                        $backlogs[] = $bklg;
                                                        
                                                        $data = array();
							$data["sistema_id"] = $sistema_id;
							$data["subsistema_id"] = $subsistema_id;
							$data["subsistema_posicion_id"] = $pos_subsistema_id;
							$data["elemento_id"] = $elemento_id;
							$data["id_elemento"] = $id_elemento;
							$data["elemento_posicion_id"] = $pos_elemento_id;
							$data["diagnostico_id"] = $diagnostico_id;
							$data["pn_saliente"] = $part_number;
							$data["tipo_registro"] = "2";
							$data["equipo_id"] = $unidad_id;
							$data["flota_id"] = $flota_id;
							$data["faena_id"] = $faena_id;
							$data["folio"] = "BK". date("YmdHis");
							
							$this->IntervencionElementos->create();
							$this->IntervencionElementos->save($data);
							$elemento_id = $this->IntervencionElementos->id;
							
							$data = array();
							$data["usuario_id"] = $this->getUsuarioID();
							$data["fecha_creacion"] = $fecha_creacion;
							$data["estado_id"] = "8";
							$data["creacion_id"] = $creacion; //"1";
							$data["equipo_id"] = $unidad_id;
							$data["flota_id"] = $flota_id;
							$data["elemento_id"] = $elemento_id;
							$data["faena_id"] = $faena_id;
							if($tiempo != "" && is_numeric($tiempo)) {
								$data["tiempo_estimado"] = $tiempo;
							}
							$data["comentario"] = $comentario;
							$data["criticidad_id"] = $criticidad_id;
							$data["responsable_id"] = $responsable_id;
							$data["sistema_id"] = $sistema_id;
							$data["categoria_sintoma_id"] = $categoria_id;
							$data["sintoma_id"] = $sintoma_id;
                                                        
                                                                                                               
							$this->Backlog->create();
							$this->Backlog->save($data);
							$filas++;
						}
                                                
                                                $email_send = "";
                                                if($this->request->data["envia_correo"] == 1){
                                                    
                                                    $destinatarios = $this->request->data["destinatarios"];
                                                    
                                                    //$email_send = $destinatarios;
                                                    
                                                    if($this->Mail($backlogs, $destinatarios))
                                                        $email_send = " - Correo enviado correctamente.";
                                                }
                                                
						//$this->redirect("/Backlog/");
						$this->Session->setFlash('Se proceso correctamente el archivo, se registraron '.$filas.' filas.'.$email_send,'guardar_exito');
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar procesar el archivo, intente nuevamente. ' . $e->getMessage(),'guardar_error');
					}
				}
			}
		}
	}
        
        
    public function Mail($backlogs, $destinatario){
        $email = new CakeEmail();
        $email->config('amazon');
        $email->emailFormat('html');
        $destinatarios = array();
        try {
            $de = split(",", $destinatario);
            
            foreach($de as $d){
                $destinatarios[] = $d;
            }
            
            $destinatarios[] = "DCCCLDB_monitoreo.condiciones.mg@global.komatsu";
            $destinatarios[] = "DCCCLDB_tribologia.dcc@global.komatsu";
            
            $html = "<html>";
            $html.= "<body>";
            $html.= "<table width=\"100%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
            $html.= "<tr style=\"background-color: red; color: white;\">";
            $html.= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">Backlog(s) Creado(s)</td>";
            $html.= "</tr>";
            $html.= "</table>";	

            $html.= '<table border="1" width="100%">';
            $html.= '	<tr>';
            $html.= '		<th>Faena</th>';
            $html.= '		<th>Flota</th>';
            $html.= '		<th>Equipo</th>';
            $html.= '		<th>Criticidad</th>';
            $html.= '		<th>Sintoma</th>';
            $html.= '		<th>Sistema</th>';
            $html.= '		<th>SubSistema</th>';
            $html.= '		<th>Fecha</th>';
            $html.= '		<th>Comentario</th>';
            $html.= '	</tr>';
            
            foreach($backlogs as $bklg){
            
                $html.= ' <tr>';
                $html.= "       <td nowrap>{$bklg['faena']}</td>";
                $html.= "       <td nowrap>{$bklg['flota']}</td>";
                $html.= "       <td nowrap>{$bklg['equipo']}</td>";
                $html.= "       <td nowrap>{$bklg['criticidad']}</td>";
                $html.= "       <td nowrap>{$bklg['sintoma']}</td>";
                $html.= "       <td nowrap>{$bklg['sistema']}</td>";
                $html.= "       <td nowrap>{$bklg['subSistema']}</td>";
                $html.= "       <td nowrap>{$bklg['fecha']}</td>";
                $html.= "       <td nowrap>{$bklg['comentario']}</td>";
                $html.= ' </tr>';
            }
            $html.= '</table>';			

            $html.="</body>";
            $html.="</html>";
            
            $asunto = "Backlogs cargados - DBM"; //. $int["Faena"];
            
            
            //print_r($html);
            
            /*if(MAIL_DEBUG == ""){
                    if(is_array($destinatarios) && count($destinatarios) > 0) {

                            $this->AWSSES->to = $destinatarios;
                            $this->AWSSES->sendRaw(utf8_encode($asunto), utf8_encode($html));
                            $this->AWSSES->charset = 'UTF-8';

                    }
            } else {
                    $destinatarios = array();
                    $destinatarios[] = MAIL_DEBUG;
                    $this->AWSSES->to = $destinatarios;
                    $this->AWSSES->sendRaw(utf8_encode($asunto), utf8_encode($html));
            }*/
            
            if(is_array($destinatarios) && count($destinatarios) > 0) {

                    $this->sendMail($destinatarios, $asunto, $html);
            }
            
            $this->AWSSES->reset();
            //$email->reset();
            return true;
        } catch (Exception $e) {
            $this->Session->setFlash('No se pudo enviar correo', 'guardar_error');
            $this->logger($this, $e->getMessage());
            return false;
        }
	
    }


	private function esContrasenaValida($password) {
		$errors = '';

		if( strpos($password, ' ') > 0) {
			$errors .= "La contraseña no puede contener espacios. ";
		}
		
		if (strlen($password) < 8) {
			$errors .= "La contraseña es muy corta. ";
		}

		if (strlen($password) > 16 ) {
			$errors .= "La contraseña supero el limite de 16 caracteres. ";
		}
	
		if (!ctype_alnum($password)) {
			$errors .= "La contraseña solo puede contener letras o numeros. ";
		}

		if (!preg_match("#[0-9]+#", $password)) {
			$errors .= "La contraseña debe poseer al menos un número. ";
		}
	
		if (!preg_match("#[a-zA-Z]+#", $password)) {
			$errors .= "La contraseña debe contener al menos una letra. ";
		}     

		if($errors != '') {

			return false;
		}else {
			return true;
		}

			
	}
        
    function sendMail($to, $subject, $body) {
       
        $this->ses = new Aws\Ses\SesClient([
                'version' => '2010-12-01',
                'region' => AMAZON_SES_REGION,
                'credentials' => [
                        'key' => AMAZON_SES_ACCESS_KEY_ID,
                        'secret' => AMAZON_SES_SECRET_ACCESS_KEY
                ]
        ]);
		
	if(is_string($to)) {
            $to = [$to];
        }

        $destination = array(
                'ToAddresses' => $to
        );
        $message = array(
                'Subject' => array(
                        'Data' => $subject
                ),
                'Body' => array()
        );



        if ($body != NULL) {
                $message['Body']['Html'] = array(
                        'Data' => $body
                );
        }

        $char_set = 'UTF-8';
        $ok = true;

        try {

        $response = $this->ses->sendEmail([
        'Destination' => $destination,
        'Source' => AMAZON_SES_FROM_EMAIL,
        'Message' => $message


        ]);
        
        
        
        } catch (AwsException $e) {
        $ok = false;
        $this->log('Error sending email from AWS SES: ' .$e->getMessage(), 'error');
        $this->log('Error sending email from AWS SES: ' .$e->getAwsErrorMessage(), 'error');
        }

        return $response;
   }
}