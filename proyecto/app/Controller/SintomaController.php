<?php
App::uses('ConnectionManager', 'Model'); 

class SintomaController extends AppController {
	public function select($id) {
		$this->layout = null;
		$this->loadModel('Sintoma');
		$sintoma = $this->Sintoma->find('all', array('fields'=>array('Sintoma.id','Sintoma.nombre','Sintoma.codigo'), 'order' => 'Sintoma.nombre', 'conditions' => array('sintoma_categoria_id' => $id)));
		$this->set('sintoma', $sintoma);
	}
	
	public function agregar(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Sintoma');
		if ($this->request->is('post')){
			try {
				if (!is_numeric($this->request->data["codigo"])) {
						$this->request->data["codigo"] = "0";
					}
				if ($this->Sintoma->save($this->request->data)){
					$this->Session->setFlash('Registro ingresado correctamente','guardar_exito');
					$this->redirect(array('action' => 'index'));
				}else{
					$this->Session->setFlash('No se pudo ingresar el registro, intente nuevamente.','guardar_error');
				}
			} catch(Exception $e) {
				$this->Session->setFlash('Ocurrió un error al ingresar el registro, intente nuevamente.','guardar_error');
			}
		}
		
		$this->set('titulo', 'Síntomas');
		$this->set('breadcrumb', 'Administración');
	}
	
	public function index() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Sintoma');
		$limit = 10;
		
		if ($this->request->is('post')){
			try {
				foreach($this->request->data['registro'] as $key => $value) {
					if ($value == '0') {
						if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
							// Estaba desactivado y se activo
							$data = array();
							$data["id"] = $key;
							$data["e"] = "1";
							$this->Sintoma->save( $data );
						}
					}
					if ($value == '1') {
						if (isset($this->request->data['estado']) && !isset($this->request->data['estado'][$key])) {
							// Estaba activado y se desactivo
							$data = array();
							$data["id"] = $key;
							$data["e"] = "0";
							$this->Sintoma->save( $data );
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
				$conditions["Sintoma.e"] = $this->request->query['estado'];
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		$this->paginate = array('limit' => $limit, 'order' => 'Sintoma.nombre', 'recursive' => -1, 'conditions' => $conditions);
		$this->set('registros', $this->paginate('Sintoma'));
		$this->set('limit', $limit);
		$this->set('titulo', 'Síntomas');
		$this->set('breadcrumb', 'Administración');
	}
	
	public function editar() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Sintoma');
		$id = $this->request->params['pass'][0];
		$this->Sintoma->id = $id;
		if( $this->Sintoma->exists() ){
			if( $this->request->is( 'post' ) || $this->request->is( 'put' ) ){
				try {
					if (!is_numeric($this->request->data["codigo"])) {
						$this->request->data["codigo"] = "0";
					}
					if( $this->Sintoma->save( $this->request->data ) ){
					 
						//set to user's screen
						$this->Session->setFlash('Registro editado correctamente.','guardar_exito');
						 
						//redirect to user's list
						$this->redirect(array('action' => 'index'));
						 
					}else{
						$this->Session->setFlash('No se pudo editar el registro.','guardar_error');
					}
				} catch(Exception $e) {
					$this->Session->setFlash('Ocurrió un error al editar el registro, intente nuevamente.','guardar_error');
				}
			}else{
				$this->request->data = $this->Sintoma->read();
				$this->set('data', $this->request->data);
			}
			 
		}else{
			$this->Session->setFlash('The user you are trying to edit does not exist.');
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('titulo', 'Síntomas');
		$this->set('breadcrumb', 'Administración');
	}
}
?>