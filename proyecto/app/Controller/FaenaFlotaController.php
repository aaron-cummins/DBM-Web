<?php
App::uses('ConnectionManager', 'Model'); 

class FaenaFlotaController extends AppController {
	public function index() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Faena');
		$this->loadModel('FaenaFlota');
		$this->loadModel('Motor');
		$limit = 10;
		$motor_id = '';
		$faena_id = '';
		$flota_id = '';
		if ($this->request->is('post')){
			try {
				foreach($this->request->data['registro'] as $key => $value) {
					if ($value == '0') {
						if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
							// Estaba desactivado y se activo
							$data = array();
							$data["id"] = $key;
							$data["e"] = "1";
							$this->FaenaFlota->save($data);
						}
					}
					if ($value == '1') {
						if (!isset($this->request->data['estado'][$key])) {
							// Estaba activado y se desactivo
							$data = array();
							$data["id"] = $key;
							$data["e"] = "0"; 
							$this->FaenaFlota->save($data);
						}
					}
				}
				$this->Session->setFlash('Cambio de estado de los registros realizados correctamente.','guardar_exito');
				} catch(Exception $e) {
					$this->Session->setFlash('Ocurrió un error al intentar cambiar el estado de los registros, intente nuevamente.','guardar_error');
				}
				$this->redirect(Router::url($this->referer(), true));
		}
		
		$conditions = array();
		if ($this->request->is('get')){
			if(isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
				$limit = $this->request->query['limit'];
			}
			if(isset($this->request->query['estado']) && is_numeric($this->request->query['estado'])) {
				$conditions["FaenaFlota.e"] = $this->request->query['estado'];
			}
			if(isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
				$faena_id = $this->request->query['faena_id'];
				$conditions["FaenaFlota.faena_id"] = $faena_id;
			}
			if(isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
				$flota_id = explode("_", $this->request->query['flota_id']);
				$flota_id = $flota_id[1];
				$conditions["FaenaFlota.flota_id"] = $flota_id;
			}
			if(isset($this->request->query['motor_id']) && $this->request->query['motor_id'] != '') {
				$motor_id = $this->request->query['motor_id'];
				$conditions["FaenaFlota.motor_id"] = $motor_id; 
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		$conditions["FaenaFlota.faena_id IN"] = $this->Session->read("FaenasFiltro");
		
		$this->paginate = array('limit' => $limit, 'recursive' => 2, 'conditions' => $conditions);
		$this->set('registros', $this->paginate('FaenaFlota'));
		$this->set('limit', $limit);
		
		$faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
		$this->set(compact('faenas'));
		
		$flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id","faena_id","Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1', 'Flota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
		$this->set(compact('flotas'));
		
		//$motores = $this->Motor->find('list', array('conditions' => array('Motor.e' => '1'), 'order' => array("Motor.nombre"), 'recursive' => -1));
		$motores = $this->Motor->find('all', array('fields' => array('Motor.id','Motor.nombre','TipoAdmision.nombre','TipoEmision.nombre'), 'conditions' => array('Motor.e' => '1'), 'order' => array("Motor.nombre"), 'recursive' => 1));
		$this->set(compact('motores'));
		
		$this->set('motor_id', $motor_id);
		$this->set('flota_id', $flota_id);
		$this->set('faena_id', $faena_id);
		
		$this->set('titulo', 'Faena Flota');
		$this->set('breadcrumb', 'Administración');
	}
	
	
	public function agregar() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('FaenaFlota');
		$this->loadModel('Motor');
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		 
		if ($this->request->is('post')){
			try {
				if ($this->FaenaFlota->save($this->request->data)){
					$this->Session->setFlash('La asociación Faena - Flota ha sido ingresada correctamente.', 'guardar_exito');
					$this->redirect(array('action' => 'index'));
				}
			} catch (Exception $e) {
				$this->Session->setFlash('No se pudo ingresar el registro, por favor revise la información e intente nuevamente.', 'guardar_error');
			}
		}
		
		$faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
		$this->set(compact('faenas'));
		
		$flotas = $this->Flota->find('list', array('order' => array("Flota.nombre"), 'conditions' => array('Flota.e' => '1'), 'recursive' => -1));		
		$this->set(compact('flotas'));
		
		//$motores = $this->Motor->find('list', array('order' => array("Motor.nombre"),  'conditions' => array('Motor.e' => '1'), 'recursive' => -1));		
		$motores = $this->Motor->find('all', array('fields' => array('Motor.id','Motor.nombre','TipoAdmision.nombre','TipoEmision.nombre'), 'conditions' => array('Motor.e' => '1'), 'order' => array("Motor.nombre"), 'recursive' => 1));
		$this->set(compact('motores'));
	}
	
	public function editar(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		
		$this->loadModel('FaenaFlota');
		$this->loadModel('Motor');
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		$this->loadModel('Unidad');
		
		$id = $this->request->params['pass'][0];
		$this->FaenaFlota->id = $id;
		if( $this->FaenaFlota->exists() ){
			if( $this->request->is( 'post' ) || $this->request->is( 'put' ) ){
				try {
					if( $this->FaenaFlota->save( $this->request->data ) ) {
						$this->Unidad->updateAll(
							array('Unidad.avance_teorico' => $this->request->data["avance_teorico"]),
							array('Unidad.flota_id' => $this->request->data["flota_id"], 'Unidad.faena_id' => $this->request->data["faena_id"])
						);
						$this->Session->setFlash('Registro editado correctamente, se actualizó el avance teórico en todas las unidades pertenecientes a la faena y flota.','guardar_exito');
						$this->redirect(array('action' => 'index'));
					}else{
						$this->Session->setFlash('No se pudo editar el registro.','guardar_error');
					}
				} catch(Exception $e) {
					$this->Session->setFlash('Ocurrió un error al editar el registro, intente nuevamente.','guardar_error');
				}
			}else{
				$this->request->data = $this->FaenaFlota->read();
				$this->set('data', $this->request->data);
			}	 
		}else{
			$this->Session->setFlash('The user you are trying to edit does not exist.');
			$this->redirect(array('action' => 'index'));
		}
		
		$faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
		$this->set(compact('faenas'));
		
		$flotas = $this->Flota->find('list', array('order' => array("Flota.nombre"), 'conditions' => array('Flota.e' => '1'), 'recursive' => -1));		
		$this->set(compact('flotas'));
		
		//$motores = $this->Motor->find('list', array('order' => array("Motor.nombre"),  'conditions' => array('Motor.e' => '1'), 'recursive' => -1));		
		$motores = $this->Motor->find('all', array('fields' => array('Motor.id','Motor.nombre','TipoAdmision.nombre','TipoEmision.nombre'), 'conditions' => array('Motor.e' => '1'), 'order' => array("Motor.nombre"), 'recursive' => 1));
		$this->set(compact('motores'));
		
		$this->set('titulo', 'Flota');
		$this->set('breadcrumb', 'Administración');
	}
}