<?php
App::uses('ConnectionManager', 'Model'); 
class PautaPuestaMarchaController extends AppController {
	public function ver($pagina = 1, $id) {
		$this->loadModel('Planificacion');
		$this->loadModel('Asesor');
		$this->loadModel('Usuario');
		$this->loadModel('IntervencionFechas');
		
		$this->layout = "metronic_principal";

		if ($this->request->is('post')){
			try {
				$intervencion = $this->Planificacion->find('first', array(
					'fields' => array('Planificacion.json', 'Planificacion.id','Planificacion.folio'),
					'conditions' => array('Planificacion.id' => $id),
					'recursive' => -1
				));
				
				if(isset($intervencion["Planificacion"]) && isset($intervencion["Planificacion"]["json"])){
					$json = $intervencion["Planificacion"]["json"];
					$json = json_decode($json, true);
					$pagina = $this->request->data["pagina"];
					//print_r($json);
					foreach($json as $key => $value){
						if (strpos($key, 'Ppm_'.$pagina.'_') === 0) {
							if (!isset($this->request->data[$key])) {
								unset($json[$key]);
							}
						}
					}
				}
				
				foreach($this->request->data as $key => $value){
					if (strpos($key, 'Ppm_'.$pagina.'_') === 0) {
						if($value != "") {
							if (strpos($key, '_na') !== false) {
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
				$this->Session->setFlash('Pauta de puesta en marcha guardada con éxito.', 'guardar_exito');
			} catch (Exception $ex) { 
				$this->Session->setFlash('Ocurrió un error al intentar aprobar la intervención, por favor intente nuevamente.', 'guardar_error');
			}
			//$this->redirect('/Trabajo/Detalle2/' . $id);
		}

		$tecnicos = array();
		$intervencion = $this->Planificacion->find('first', array(
			'fields' => array('Planificacion.json', 'Planificacion.id', 'Planificacion.estado', 'Unidad.motor_id', 'Planificacion.tipomantencion', 'Planificacion.folio', 'Planificacion.faena_id'),
			'conditions' => array('Planificacion.id' => $id),
			'recursive' => 1
		));
				
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
			//print_r($json);
			//exit;
			
			$asesores = $this->Asesor->find('all', array(
				'fields' => array('Asesor.id', 'Asesor.nombre'),
				'conditions' => array('Asesor.faena_id' => $intervencion["Planificacion"]["faena_id"]),
				'recursive' => -1
			));
			
			$estado = $intervencion["Planificacion"]["estado"];
			$this->set("asesores", $asesores);	
			$this->set("usuarios", $usuarios);	
			$this->set("json", $json);	
			$this->set("id", $id);	
			$this->set("estado", $estado);
			
			$fechas = $this->IntervencionFechas->find('first', array(
				'fields' => array('IntervencionFechas.id', 'IntervencionFechas.folio', 'IntervencionFechas.inicio_puesta_marcha'),
				'conditions' => array('IntervencionFechas.folio' => $intervencion["Planificacion"]["folio"]),
				'recursive' => -1
			));
			
			if(isset($fechas["IntervencionFechas"]["inicio_puesta_marcha"])){
				$Ppm_01_fecha_inspeccion = strtotime($fechas["IntervencionFechas"]["inicio_puesta_marcha"]);
				$Ppm_01_fecha_inspeccion = date("Y-m-d", $Ppm_01_fecha_inspeccion);
				$this->set("Ppm_01_fecha_inspeccion", $Ppm_01_fecha_inspeccion);
			}
		}
		
		$this->set("pautaPuestaMarcha", true);
		switch($pagina){
			case "1": $this->render("/PautaPuestaMarcha/p15_pauta_puesta_marcha_pagina_1"); break;
			case "2": $this->render("/PautaPuestaMarcha/p15_pauta_puesta_marcha_pagina_2"); break;
			case "3": $this->render("/PautaPuestaMarcha/p15_pauta_puesta_marcha_pagina_3"); break;
			case "4": $this->render("/PautaPuestaMarcha/p15_pauta_puesta_marcha_pagina_4"); break;
			case "5": $this->render("/PautaPuestaMarcha/p15_pauta_puesta_marcha_pagina_5"); break;
			case "6": $this->render("/PautaPuestaMarcha/p15_pauta_puesta_marcha_pagina_6"); break;
			case "7": $this->render("/PautaPuestaMarcha/p15_pauta_puesta_marcha_pagina_7"); break;
			case "8": $this->render("/PautaPuestaMarcha/p15_pauta_puesta_marcha_pagina_8"); break;
			case "9": $this->render("/PautaPuestaMarcha/p15_pauta_puesta_marcha_pagina_9"); break;
			case "10": $this->render("/PautaPuestaMarcha/p15_pauta_puesta_marcha_pagina_10"); break;
			default: $this->render("/PautaPuestaMarcha/p15_pauta_puesta_marcha_pagina_1"); break;
		}
	}
	
	public function p15_pauta_puesta_marcha_pagina_1($pagina = 1, $id){}
	public function p15_pauta_puesta_marcha_pagina_2($pagina = 1, $id){}
	public function p15_pauta_puesta_marcha_pagina_3($pagina = 1, $id){}
	public function p15_pauta_puesta_marcha_pagina_4($pagina = 1, $id){}
	public function p15_pauta_puesta_marcha_pagina_5($pagina = 1, $id){}
	public function p15_pauta_puesta_marcha_pagina_6($pagina = 1, $id){}
	public function p15_pauta_puesta_marcha_pagina_7($pagina = 1, $id){}
	public function p15_pauta_puesta_marcha_pagina_8($pagina = 1, $id){}
	public function p15_pauta_puesta_marcha_pagina_9($pagina = 1, $id){}
	public function p15_pauta_puesta_marcha_pagina_10($pagina = 1, $id){}
}
?>