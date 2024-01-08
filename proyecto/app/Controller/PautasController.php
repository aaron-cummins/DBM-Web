<?php
App::uses('ConnectionManager', 'Model'); 
class PautasController extends AppController {
	public function index($folio = "") {
		$this->loadModel('Planificacion');
		$this->loadModel('Motor');
		$this->layout=null;
		$intervencion = $this->Planificacion->find('first', array(
			'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad'),
			'conditions' => array(
				"Planificacion.id = $folio"),
			'recursive' => 1));
		if(strtoupper($intervencion["Planificacion"]["tipointervencion"])=='MP'&&$intervencion["Planificacion"]["tipomantencion"]!=null&&$intervencion["Planificacion"]["tipomantencion"]!='1500'){
			$json = json_decode($intervencion["Planificacion"]["json"], true);
			unset($intervencion["Planificacion"]["json"]);
			$this->set('folio', $folio);
			$this->set('intervencion', $intervencion["Planificacion"]);
			$this->set('json', $json);
			$motor = $this->Motor->find('first', array(
			'fields' => array('Motor.id', 'Motor.nombre'),
			'conditions' => array(
				"Motor.id = {$json["motor_id"]}"),
			'recursive' => -1));
			$this->set('motor', $motor["Motor"]["nombre"]);
		}else{
			die;
		}
	}
}
?>