<?php
App::uses('ConnectionManager', 'Model'); 

class CategoriaSintomaController extends AppController {
	public function agregar(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('SintomaCategoria');
		if ($this->request->is('post')){
			try {
				if ($this->SintomaCategoria->save($this->request->data)){
					$this->Session->setFlash('Registro ingresado correctamente','guardar_exito');
					$this->redirect(array('action' => 'index'));
				}else{
					$this->Session->setFlash('No se pudo ingresar el registro, intente nuevamente.','guardar_error');
				}
			} catch(Exception $e) {
				$this->Session->setFlash('Ocurrió un error al ingresar el registro, intente nuevamente.','guardar_error');
			}
		}
		
		$this->set('titulo', 'Categorías de síntomas');
		$this->set('breadcrumb', 'Administración');
	}
	
	public function index() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('SintomaCategoria');
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
							$this->SintomaCategoria->save( $data );
						}
					}
					if ($value == '1') {
						if (isset($this->request->data['estado']) && !isset($this->request->data['estado'][$key])) {
							// Estaba activado y se desactivo
							$data = array();
							$data["id"] = $key;
							$data["e"] = "0";
							$this->SintomaCategoria->save( $data );
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
				$conditions["SintomaCategoria.e"] = $this->request->query['estado'];
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		$this->paginate = array('limit' => $limit, 'order' => 'SintomaCategoria.nombre', 'recursive' => -1, 'conditions' => $conditions);
		$this->set('registros', $this->paginate('SintomaCategoria'));
		$this->set('limit', $limit);
		$this->set('titulo', 'Categorías de síntomas');
		$this->set('breadcrumb', 'Administración');
	}
	
	public function editar() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('SintomaCategoria');
		$id = $this->request->params['pass'][0];
		$this->SintomaCategoria->id = $id;
		if( $this->SintomaCategoria->exists() ){
			if( $this->request->is( 'post' ) || $this->request->is( 'put' ) ){
				try {
					if( $this->SintomaCategoria->save( $this->request->data ) ){
					 
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
				$this->request->data = $this->SintomaCategoria->read();
				$this->set('data', $this->request->data);
			}
			 
		}else{
			$this->Session->setFlash('The user you are trying to edit does not exist.');
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('titulo', 'Categorías de síntomas');
		$this->set('breadcrumb', 'Administración');
	}
}
?>