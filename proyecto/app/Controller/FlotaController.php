<?php
App::uses('ConnectionManager', 'Model'); 
/*
	Esta clase define el despliegue de las flotas existentes en la base de datos.
*/
class FlotaController extends AppController {
	//public $scaffold = 'admin';
	public function index() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Flota');
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
							$this->Flota->save( $data );
						}
					}
					if ($value == '1') {
						if (isset($this->request->data['estado']) && !isset($this->request->data['estado'][$key])) {
							// Estaba activado y se desactivo
							$data = array();
							$data["id"] = $key;
							$data["e"] = "0";
							$this->Flota->save( $data );
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
				$conditions["Flota.e"] = $this->request->query['estado'];
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		$this->paginate = array('limit' => $limit, 'order' => 'Flota.nombre', 'recursive' => -1, 'conditions' => $conditions);
		$this->set('registros', $this->paginate('Flota'));
		$this->set('limit', $limit);
		$this->set('titulo', 'Flota');
		$this->set('breadcrumb', 'Administración');
	}
	
	public function agregar(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Flota');
		if ($this->request->is('post')){
			try {
				if ($this->Flota->save($this->request->data)){
					$this->Session->setFlash('Registro agregado correctamente','guardar_exito');
					$this->redirect(array('action' => 'index'));
				}else{
					$this->Session->setFlash('No se pudo agregar el registro.','guardar_error');
				}
			} catch(Exception $e) {
				$this->Session->setFlash('Ocurrió un error al intentar agregar el registro, intente nuevamente.','guardar_error');
			}
		}
		$this->set('titulo', 'Flota');
		$this->set('breadcrumb', 'Administración');
	}
	
	public function editar() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		//$this->loadModel('Faena');
		$this->loadModel('Flota');
		//get the id of the user to be edited
		$id = $this->request->params['pass'][0];
		 
		//set the user id
		$this->Flota->id = $id;
		 
		//check if a user with this id really exists
		if( $this->Flota->exists() ){
			if( $this->request->is( 'post' ) || $this->request->is( 'put' ) ) {
				try {
					if( $this->Flota->save( $this->request->data ) ){
						$this->Session->setFlash('Registro editado correctamente.','guardar_exito');
						$this->redirect(array('action' => 'index'));						 
					}else{
						$this->Session->setFlash('No se pudo editar el registro.','guardar_error');
					}
				} catch(Exception $e) {
					$this->Session->setFlash('Ocurrió un error al intentar editar el registro, intente nuevamente.','guardar_error');
				}
			}else{
				$this->request->data = $this->Flota->read();
				$this->set('data', $this->request->data);
			}
			 
		}else{
			//if not found, we will tell the user that user does not exist
			$this->Session->setFlash('The user you are trying to edit does not exist.');
			$this->redirect(array('action' => 'index'));
				 
			//or, since it we are using php5, we can throw an exception
			//it looks like this
			//throw new NotFoundException('The user you are trying to edit does not exist.');
		}
		$this->set('titulo', 'Flota');
		$this->set('breadcrumb', 'Administración');
	}
	
	/*
		Este metodo despliega todos las flotas, se llamda desde el despliegue de Planificacion a traves de ajax.
	*/
	public function select($id) {
		$this->layout = null;
		$this->loadModel('UnidadDetalle');
		$flota = $this->UnidadDetalle->find('all', array('fields'=>array('DISTINCT flota_id','flota'), 'order' => 'flota', 'conditions' => array('UnidadDetalle.faena_id' => $id), 'recursive' => -1));
		$this->set('flota', $flota);
	}
}

?>