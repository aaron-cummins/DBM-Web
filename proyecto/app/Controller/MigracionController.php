<?php
App::uses('ConnectionManager', 'Model'); 
	
class MigracionController extends AppController {
	public function fluidos(){
		$this->layout = null;
		$this->loadModel('Planificacion');
		$this->loadModel('IntervencionFluido');
		exit;
		for($i=0;$i<50000;$i=$i+1000){
			$intervenciones = $this->Planificacion->find('all', array(
													'limit' => 999, 
													'offset' => $i,
													'conditions' => array('Planificacion.estado !=' => 2), 
													'recursive' => -1,
													'order' => array("Planificacion.id" => "DESC")));
		
		
		//$intervenciones = $this->Planificacion->find('all', array(
		//											'limit' => 100, 
		//											'conditions' => array('Planificacion.estado !=' => 2), 
		//											'recursive' => -1,
		//											'order' => array("Planificacion.id" => "DESC")));
		
			print_r("<pre>");
			print_r("Start: ".($i).", End: " . ($i + 999));
			
			//continue;
			
			foreach($intervenciones as $intervencion){
				$json = json_decode($intervencion["Planificacion"]["json"], true);
				$intervencion_id = $intervencion["Planificacion"]["id"];
				$cantidad = "";
				$fluido_id = "";
				$tipo_ingreso_id = "";
				
				if(isset($json["AceiteMotor"]) && is_numeric($json["AceiteMotor"]) && intval($json["AceiteMotor"]) > 0) {
					try {
						$data = array();
						$data["intervencion_id"] = $intervencion_id;
						$data["cantidad"] = $json["AceiteMotor"];
						$data["fluido_id"] = 1;
						$data["tipo_ingreso_id"] = $json["AceiteMotorTipo"] == "C" ? 1 : 2;
						print_r($data);
						$this->IntervencionFluido->create();
						$this->IntervencionFluido->save($data);
					} catch(Exception $e){
						//print_r($e);
					}
				}
				
				if(isset($json["AceiteReserva"]) && is_numeric($json["AceiteReserva"]) && intval($json["AceiteReserva"]) > 0) {
					try {
						$cantidad = $json["AceiteReserva"];
						$fluido_id = 2;
						$tipo_ingreso_id = $json["AceiteReservaTipo"] == "C" ? 1 : 2;
						$data = array();
						$data["intervencion_id"] = $intervencion_id;
						$data["cantidad"] = $cantidad;
						$data["fluido_id"] = $fluido_id;
						$data["tipo_ingreso_id"] = $tipo_ingreso_id;
						print_r($data);
						$this->IntervencionFluido->create();
						$this->IntervencionFluido->save($data);
					} catch(Exception $e){
						//print_r($e);
					}
				}
				
				if(isset($json["Combustible"]) && is_numeric($json["Combustible"]) && intval($json["Combustible"]) > 0) {	
					try {		
						$cantidad = $json["Combustible"];
						$fluido_id = 4;
						$tipo_ingreso_id = $json["CombustibleTipo"] == "C" ? 1 : 2;
						$data = array();
						$data["intervencion_id"] = $intervencion_id;
						$data["cantidad"] = $cantidad;
						$data["fluido_id"] = $fluido_id;
						$data["tipo_ingreso_id"] = $tipo_ingreso_id;
						print_r($data);
						$this->IntervencionFluido->create();
						$this->IntervencionFluido->save($data);
					} catch(Exception $e){
						//print_r($e);
					}
				} 
				
				if(isset($json["Refrigerante"]) && is_numeric($json["Refrigerante"]) && intval($json["Refrigerante"]) > 0) {
					try {
						$cantidad = $json["Refrigerante"];
						$fluido_id = 3;
						$tipo_ingreso_id = $json["RefrigeranteTipo"] == "C" ? 1 : 2;
						$data = array();
						$data["intervencion_id"] = $intervencion_id;
						$data["cantidad"] = $cantidad;
						$data["fluido_id"] = $fluido_id;
						$data["tipo_ingreso_id"] = $tipo_ingreso_id;
						print_r($data);
						$this->IntervencionFluido->create();
						$this->IntervencionFluido->save($data);
					} catch(Exception $e){
						//print_r($e);
					}
				}
		
				if(isset($json["Zerex"]) && is_numeric($json["Zerex"]) && intval($json["Zerex"]) > 0) {		
					try {	
						$cantidad = $json["Zerex"];
						$fluido_id = 5;
						$data = array();
						$data["intervencion_id"] = $intervencion_id;
						$data["cantidad"] = $cantidad;
						$data["fluido_id"] = $fluido_id;
						print_r($data);
						$this->IntervencionFluido->create();
						$this->IntervencionFluido->save($data);
					} catch(Exception $e){
						//print_r($e);
					}
				}
				
				if(isset($json["Resurs"]) && is_numeric($json["Resurs"]) && intval($json["Resurs"]) > 0) {
					try {
						$cantidad = $json["Resurs"];
						$fluido_id = 6;
						$data = array();
						$data["intervencion_id"] = $intervencion_id;
						$data["cantidad"] = $cantidad;
						$data["fluido_id"] = $fluido_id;
						print_r($data);
						$this->IntervencionFluido->create();
						$this->IntervencionFluido->save($data);
					} catch(Exception $e){
						//print_r($e);
					}
				}
				
				try {
					// Se actualiza intervencion sin los fluidos en duro
					$data = array();
					$data["id"] = $intervencion_id;
					$data["json"] = json_encode($json);
					//$this->Planificacion->save($data);
				} catch(Exception $e){
					//print_r($e);
				}
			}
		}
		exit;
	}	
	public function motivollamado(){
		$this->layout = null;
		$this->loadModel('Planificacion');
		for($i=0;$i<50000;$i=$i+1000){
			$intervenciones = $this->Planificacion->find('all', array(
													'limit' => 999, 
													'offset' => $i,
													'conditions' => array('Planificacion.motivo_llamado_id' => NULL), 
													'recursive' => -1,
													'order' => array("Planificacion.id" => "DESC")));
		
		
		//$intervenciones = $this->Planificacion->find('all', array(
		//											'limit' => 100, 
		//											'conditions' => array('Planificacion.estado !=' => 2), 
		//											'recursive' => -1,
		//											'order' => array("Planificacion.id" => "DESC")));
		
			print_r("<pre>");
			print_r("Start: ".($i).", End: " . ($i + 999));
			
			//continue;
			
			foreach($intervenciones as $intervencion){
				$json = json_decode($intervencion["Planificacion"]["json"], true);
				$intervencion_id = $intervencion["Planificacion"]["id"];
								
				if(isset($json["motivo_llamado"]) && $json["motivo_llamado"] != "") {
					try {
						$motivo_id = 0;
						switch($json["motivo_llamado"]){
							case "OP":
								$motivo_id = 5;
								break;
							case "OT":
								$motivo_id = 3;
								break;
							case "FC":
								$motivo_id = 2;
								break;
						}
						$data = array();
						$data["id"] = $intervencion_id;
						$data["motivo_llamado_id"] = $motivo_id;
						$this->Planificacion->save($data);
						print_r($data);
					} catch(Exception $e){
						//print_r($e);
					}
				}elseif(isset($json["MotivoID"]) && $json["MotivoID"] != "") {
					try {
						$motivo_id = 0;
						switch($json["MotivoID"]){
							case "OP":
								$motivo_id = 5;
								break;
							case "OT":
								$motivo_id = 3;
								break;
							case "FC":
								$motivo_id = 2;
								break;
						}
						$data = array();
						$data["id"] = $intervencion_id;
						$data["motivo_llamado_id"] = $motivo_id;
						$this->Planificacion->save($data);
						print_r($data);
					} catch(Exception $e){
						//print_r($e);
					}
				}
				
				
				try {
					// Se actualiza intervencion sin los fluidos en duro
					//$data = array();
					//$data["id"] = $intervencion_id;
					//$data["json"] = json_encode($json);
					//$this->Planificacion->save($data);
				} catch(Exception $e){
					//print_r($e);
				}
			}
		}
		exit;
	}	
	
	public function categoriasintoma(){
		$this->layout = null;
		$this->loadModel('Planificacion');
		
		for($i=0;$i<50000;$i=$i+1000){
			$intervenciones = $this->Planificacion->find('all', array(
													'limit' => 999, 
													'offset' => $i,
													'fields' => array("Planificacion.id","Planificacion.sintoma_id","Sintoma.sintoma_categoria_id"),
													'conditions' => array('Planificacion.categoria_sintoma_id' => NULL, 'Planificacion.sintoma_id NOT' => NULL, 'Planificacion.motivo_llamado_id NOT' => NULL), 
													'recursive' => 1,
													'order' => array("Planificacion.id" => "DESC")));
		
		
		//$intervenciones = $this->Planificacion->find('all', array(
		//											'limit' => 100, 
		//											'conditions' => array('Planificacion.estado !=' => 2), 
		//											'recursive' => -1,
		//											'order' => array("Planificacion.id" => "DESC")));
		
			print_r("<pre>");
			print_r("Start: ".($i).", End: " . ($i + 999));
			
			//continue;
			
			foreach($intervenciones as $intervencion){
				//$json = json_decode($intervencion["Planificacion"]["json"], true);
				$intervencion_id = $intervencion["Planificacion"]["id"];
				//$sintoma_id = $intervencion["Planificacion"]["sintoma_id"];
				$categoria_sintoma_id = $intervencion["Sintoma"]["sintoma_categoria_id"];
								
				try {
					$data = array();
					$data["id"] = $intervencion_id;
					$data["categoria_sintoma_id"] = $categoria_sintoma_id;
					$this->Planificacion->save($data);
					print_r($data);
				} catch(Exception $e){
					//print_r($e);
				}
			
				try {
					// Se actualiza intervencion sin los fluidos en duro
					//$data = array();
					//$data["id"] = $intervencion_id;
					//$data["json"] = json_encode($json);
					//$this->Planificacion->save($data);
				} catch(Exception $e){
					//print_r($e);
				}
			}
		}
		exit;
	}
	
	public function sintomaspendientes(){
		$this->layout = null;
		$this->loadModel('Planificacion');
		$this->loadModel('MotivoCategoriaSintoma');
		
		for($i=0;$i<50000;$i=$i+1000){
			$intervenciones = $this->Planificacion->find('all', array(
													'limit' => 999, 
													'offset' => $i,
													'fields' => array("Planificacion.id","Planificacion.sintoma_id","Planificacion.categoria_sintoma_id","Planificacion.motivo_llamado_id"),
													'conditions' => array('Planificacion.categoria_sintoma_id NOT' => NULL, 'Planificacion.sintoma_id NOT' => NULL, 'Planificacion.motivo_llamado_id NOT' => NULL),
													'recursive' => -1,
													'order' => array("Planificacion.id" => "DESC")));
		
		
		//$intervenciones = $this->Planificacion->find('all', array(
		//											'limit' => 100, 
		//											'conditions' => array('Planificacion.estado !=' => 2), 
		//											'recursive' => -1,
		//											'order' => array("Planificacion.id" => "DESC")));
		
			print_r("<pre>");
			print_r("Start: ".($i).", End: " . ($i + 999));
			
			//continue;
			
			foreach($intervenciones as $intervencion){
				//$json = json_decode($intervencion["Planificacion"]["json"], true);
				$motivo_id = $intervencion["Planificacion"]["motivo_llamado_id"];
				$sintoma_id = $intervencion["Planificacion"]["sintoma_id"];
				$categoria_sintoma_id = $intervencion["Planificacion"]["categoria_sintoma_id"];
				//print_r($intervencion["Planificacion"]["id"]);				
				try {
					$data = array();
					$data["motivo_id"] = $motivo_id;
					$data["categoria_id"] = $categoria_sintoma_id;
					$data["sintoma_id"] = $sintoma_id;
					$data["e"] = "1";
					$this->MotivoCategoriaSintoma->create();
					$this->MotivoCategoriaSintoma->save($data);
					print_r($data);
				} catch(Exception $e){
					//print_r($e);
				}
			
				try {
					// Se actualiza intervencion sin los fluidos en duro
					//$data = array();
					//$data["id"] = $intervencion_id;
					//$data["json"] = json_encode($json);
					//$this->Planificacion->save($data);
				} catch(Exception $e){
					//print_r($e);
				}
			}
		}
		exit;
	}

}
?>