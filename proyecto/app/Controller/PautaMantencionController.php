<?php
App::uses('ConnectionManager', 'Model'); 
class PautaMantencionController extends AppController {
	public function ver($id) {
		$this->loadModel('Planificacion');
		$this->loadModel('Usuario');
		$this->layout = "metronic_principal";
		
		if ($this->request->is('post')){
			try {
				$intervencion = $this->Planificacion->find('first', array(
					'fields' => array('Planificacion.json', 'Planificacion.id'),
					'conditions' => array('Planificacion.id' => $id),
					'recursive' => -1
				));
				
				if(isset($intervencion["Planificacion"]) && isset($intervencion["Planificacion"]["json"])){
					$json = $intervencion["Planificacion"]["json"];
					$json = json_decode($json, true);
					//print_r($json);
					foreach($json as $key => $value){
						if (strpos($key, 'Pm_') === 0) {
							if (!isset($this->request->data[$key])) {
								unset($json[$key]);
							}
						}
					}
				}
				
				foreach($this->request->data as $key => $value){
					if (strpos($key, 'Pm_') === 0) {
						if($value != "") {
							if (strpos($key, '_medicion') !== false) {
							}elseif (strpos($key, '_responsable') !== false) {
							}elseif (strpos($key, '_obs') !== false) {
							}elseif (strpos($key, '_na') !== false) {
								$this->request->data[$key] = "1";
							}elseif (strpos($key, '_si') !== false) {
								$this->request->data[$key] = "1";
							}elseif (strpos($key, '_no') !== false) {
								$this->request->data[$key] = "1";
							}
							$json[$key] = $value;
						} else {
							unset($this->request->data[$key]);
						}
					}
				}
				
				$json = json_encode($json);
				//print_r($json);
				//print_r($this->request->data);
				$this->Planificacion->updateAll(
				    array('Planificacion.json' => "'$json'"),
				    array('Planificacion.id' => $id)
				);
				$this->Session->setFlash('Pauta de mantención guardada con éxito.', 'guardar_exito');
			} catch (Exception $ex) { 
				$this->Session->setFlash('Ocurrió un error al intentar aprobar la intervención, por favor intente nuevamente.', 'guardar_error');
			}
			$this->redirect('/Trabajo/Detalle2/' . $id);
		}
		
		$intervencion = $this->Planificacion->find('first', array(
			'fields' => array('Planificacion.json', 'Planificacion.id', 'Planificacion.estado', 'Unidad.motor_id', 'Planificacion.tipomantencion'),
			'conditions' => array('Planificacion.id' => $id),
			'recursive' => 1
		));
		
		$tecnicos = array();
		$estado = $intervencion["Planificacion"]["estado"];
		
		if(isset($intervencion["Planificacion"]) && isset($intervencion["Planificacion"]["json"])){
			$json = $intervencion["Planificacion"]["json"];
			$json = json_decode($json, true);
			
			if (isset($json["UserID"]) && is_numeric($json["UserID"])){
				$tecnicos[] = $json["UserID"];
			}
			if (isset($json["TecnicoApoyo02"]) && is_numeric($json["TecnicoApoyo02"])){
				$tecnicos[] = $json["TecnicoApoyo02"];
			}
			if (isset($json["TecnicoApoyo03"]) && is_numeric($json["TecnicoApoyo03"])){
				$tecnicos[] = $json["TecnicoApoyo03"];
			}
			if (isset($json["TecnicoApoyo04"]) && is_numeric($json["TecnicoApoyo04"])){
				$tecnicos[] = $json["TecnicoApoyo04"];
			}
			if (isset($json["TecnicoApoyo05"]) && is_numeric($json["TecnicoApoyo05"])){
				$tecnicos[] = $json["TecnicoApoyo05"];
			}
			if (isset($json["TecnicoApoyo06"]) && is_numeric($json["TecnicoApoyo06"])){
				$tecnicos[] = $json["TecnicoApoyo06"];
			}
			if (isset($json["TecnicoApoyo07"]) && is_numeric($json["TecnicoApoyo07"])){
				$tecnicos[] = $json["TecnicoApoyo07"];
			}
			if (isset($json["TecnicoApoyo08"]) && is_numeric($json["TecnicoApoyo08"])){
				$tecnicos[] = $json["TecnicoApoyo08"];
			}
			if (isset($json["TecnicoApoyo09"]) && is_numeric($json["TecnicoApoyo09"])){
				$tecnicos[] = $json["TecnicoApoyo09"];
			}
			if (isset($json["TecnicoApoyo10"]) && is_numeric($json["TecnicoApoyo10"])){
				$tecnicos[] = $json["TecnicoApoyo10"];
			}
			$tecnicos[] = -1;
			$usuarios = $this->Usuario->find('all', array(
				'fields' => array('Usuario.id', 'Usuario.nombres','Usuario.apellidos'),
				'conditions' => array('Usuario.id IN' => $tecnicos),
				'recursive' => -1
			));
			$this->set("usuarios", $usuarios);	
			$this->set("json", $json);	
			$this->set("id", $id);	
			$this->set("estado", $estado);	
		}
		
		$this->set("pautaMantencion", true);
		
		//echo $intervencion["Unidad"]["motor_id"];
		//echo $intervencion["Planificacion"]["tipomantencion"];
		//exit;
		
		if(isset($intervencion["Unidad"]["motor_id"]) && $intervencion["Unidad"]["motor_id"] != null) {
			if ($intervencion["Planificacion"]["tipomantencion"] == "250") {
				switch ($intervencion["Unidad"]["motor_id"]){
					case '5': $this->render("/PautaMantencion/p3_pm250_k1500_tier_i"); break;
					case '6': $this->render("/PautaMantencion/p3_pm250_k2000_tier_i"); break;
					case '1': $this->render("/PautaMantencion/p3_pm250_qsk60_tier_i");break;
					case '2': case '4': $this->render("/PautaMantencion/p3_pm250_qsk60_tier_ii");break;
					case '3': $this->render("/PautaMantencion/p3_pm250_qsk78_tier_i");break;
					default: $this->render("/PautaMantencion/p3_pm250_qsk60_tier_i");break;
				}
			}
			
			if ($intervencion["Planificacion"]["tipomantencion"] == "500") {
				switch($intervencion["Unidad"]["motor_id"]){
					case '5': $this->render("/PautaMantencion/p3_pm500_k1500_tier_i");break;
					case '6': $this->render("/PautaMantencion/p3_pm500_k2000_tier_i");break;
					case '1': $this->render("/PautaMantencion/p3_pm500_qsk60_tier_i");break;
					case '2': case '4': $this->render("/PautaMantencion/p3_pm500_qsk60_tier_ii");break;
					case '3': $this->render("/PautaMantencion/p3_pm500_qsk78_tier_i");break;
					default: $this->render("/PautaMantencion/p3_pm500_qsk60_tier_i");break;
				}
			}
			
			if ($intervencion["Planificacion"]["tipomantencion"] == "1000") {
				switch($intervencion["Unidad"]["motor_id"]){
					case '5': $this->render("/PautaMantencion/p3_pm1000_k1500_tier_i");break;
					case '6': $this->render("/PautaMantencion/p3_pm1000_k2000_tier_i");break;
					case '1': $this->render("/PautaMantencion/p3_pm1000_qsk60_tier_i");break;
					case '2': case '4': $this->render("/PautaMantencion/p3_pm1000_qsk60_tier_ii");break;
					case '3': $this->render("/PautaMantencion/p3_pm1000_qsk78_tier_i");break;
					default: $this->render("/PautaMantencion/p3_pm1000_qsk60_tier_i");break;
				}
			}
		} else {
			$this->render("/PautaMantencion/p3_pm1000_qsk60_tier_i");
		}	
	}
	
	public function p3_pm250_k1500_tier_i($id = NULL){}
	public function p3_pm250_k2000_tier_i($id = NULL){}
	public function p3_pm250_qsk60_tier_i($id = NULL){}
	public function p3_pm250_qsk60_tier_ii($id = NULL){}
	public function p3_pm250_qsk78_tier_i($id = NULL){}

	public function p3_pm500_k1500_tier_i($id = NULL){}
	public function p3_pm500_k2000_tier_i($id = NULL){}
	public function p3_pm500_qsk60_tier_i($id = NULL){}
	public function p3_pm500_qsk60_tier_ii($id = NULL){}
	public function p3_pm500_qsk78_tier_i($id = NULL){}
	
	public function p3_pm1000_k1500_tier_i($id = NULL){}
	public function p3_pm1000_k2000_tier_i($id = NULL){}
	public function p3_pm1000_qsk60_tier_i($id = NULL){}
	public function p3_pm1000_qsk60_tier_ii($id = NULL){}
	public function p3_pm1000_qsk78_tier_i($id = NULL){}
}
?>