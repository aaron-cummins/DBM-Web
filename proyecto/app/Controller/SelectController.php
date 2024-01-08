<?php
App::uses('ConnectionManager', 'Model'); 

class SelectController extends AppController {
        public function getSistema($motor_id){
            $this->layout = null;
            $this->loadModel('MotorSistemaSubsistemaPosicion');
            $resultados = $this->MotorSistemaSubsistemaPosicion->find('all', array(
                                            'fields' => array('Sistema.id','Sistema.nombre'),
                                            'conditions'=> array("MotorSistemaSubsistemaPosicion.motor_id" => $motor_id),
                                            'group' => array('Sistema.id','Sistema.nombre'),
                                            'order' => array('Sistema.nombre' => "ASC"),
                                            'recursive' => 1));
            $resultados = json_encode($resultados);
            print($resultados);
            die;
        }
    
	public function Subsistema($motor_id, $sistema_id) {
		$this->layout = null;
		$this->loadModel('Sistema_Subsistema_Motor');
		$results = $this->Sistema_Subsistema_Motor->find('all', array('fields'=>array('Subsistema.id','Subsistema.nombre'), 'order' => 'Subsistema.nombre','conditions' => array('motor_id' => $motor_id, 'sistema_id' => $sistema_id), 'recursive' => 1));
		echo json_encode($results);
		die;
	}
	
	public function PosicionesSubsistema($motor_id, $sistema_id, $subsistema_id) {
		$this->layout = null;
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$results = $this->MotorSistemaSubsistemaPosicion->find('all', array('fields'=>array('Posicion.id','Posicion.nombre'), 'order' => 'Posicion.nombre', 'conditions' => array('motor_id' => $motor_id, 'sistema_id' => $sistema_id, 'subsistema_id' => $subsistema_id), 'recursive' => 1));
		echo json_encode($results);
		die;
	}
	
	public function Elemento($motor_id, $sistema_id, $subsistema_id) {
		$this->layout = null;
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$results = $this->Sistema_Subsistema_Motor_Elemento->find('all', array('fields'=>array('Elemento.id','Elemento.nombre','Sistema_Subsistema_Motor_Elemento.codigo'), 'conditions' => array('motor_id' => $motor_id, 'sistema_id' => $sistema_id, 'subsistema_id' => $subsistema_id), 'order' => 'Sistema_Subsistema_Motor_Elemento.codigo', 'group' => array('Sistema_Subsistema_Motor_Elemento.codigo','Elemento.nombre','Elemento.id'), 'recursive' => 1));
		echo json_encode($results);
		die;
	}
	
	public function PosicionesElemento($motor_id, $sistema_id, $subsistema_id, $elemento_id, $codigo) {
		$this->layout = null;
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$results = $this->Sistema_Subsistema_Motor_Elemento->find('all', array('fields'=>array('Posicion.id','Posicion.nombre'), 'conditions' => array('motor_id' => $motor_id, 'sistema_id' => $sistema_id, 'subsistema_id' => $subsistema_id, 'elemento_id' => $elemento_id, 'codigo' => $codigo), 'order' => 'Posicion.nombre', 'group' => array('Posicion.id','Posicion.nombre'), 'recursive' => 1));
		echo json_encode($results);
		die;
	}
	
	public function Diagnostico($motor_id, $sistema_id, $subsistema_id, $elemento_id, $codigo = "") {
		$this->layout = null;
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		$results = $this->MotorSistemaSubsistemaElementoDiagnostico->find('all', array('fields'=>array('Diagnostico.id','Diagnostico.nombre'), 'conditions' => array('motor_id' => $motor_id, 'sistema_id' => $sistema_id, 'subsistema_id' => $subsistema_id, 'elemento_id' => $elemento_id), 'order' => 'Diagnostico.nombre', 'group' => array('Diagnostico.id','Diagnostico.nombre'), 'recursive' => 1));
		echo json_encode($results);
		die;
	}

    public function getPlanesDeAccion($motor_id){
        $this->layout = null;
        $this->loadModel('PlanAccion');
        $resultados = $this->PlanAccion->find('all', array(
            'fields' => array('PlanAccion.id','PlanAccion.nombre'),
            'conditions'=> array("PlanAccion.motor_id" => $motor_id, 'PlanAccion.e' => '1'),
            'group' => array('PlanAccion.id','PlanAccion.nombre'),
            'order' => array('PlanAccion.nombre' => "ASC"),
            'recursive' => 1));
        $resultados = json_encode($resultados);
        print($resultados);
        die;
    }
}
?>