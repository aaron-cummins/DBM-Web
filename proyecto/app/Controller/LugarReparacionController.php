<?php
	App::uses('ConnectionManager', 'Model'); 
	class LugarReparacionController extends AppController {
		public function index() {
			$this->layout = 'metronic_principal';
			$this->check_permissions($this);
			$this->loadModel('LugarReparacion');
			$limit = 10;
			
			if ($this->request->is('post')){
				try {
					foreach($this->request->data['registro'] as $key => $value) {
						if(isset($this->request->data['btn-guardar'])) {
							if ($value == '0') {
								if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
									// Estaba desactivado y se activo
									$data = array();
									$data["id"] = $key;
									$data["e"] = "1";
									$this->LugarReparacion->save($data);
								}
							}
							if ($value == '1') {
								if (!isset($this->request->data['estado'][$key])) {
									// Estaba activado y se desactivo
									$data = array();
									$data["id"] = $key;
									$data["e"] = "0";
									$this->LugarReparacion->save($data);
								}
							}
							$this->Session->setFlash('Cambio de estado de los registros realizados correctamente.','guardar_exito');
						}
						if(isset($this->request->data['btn-eliminar'])) {
							if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
								$this->LugarReparacion->delete($key);
								$this->Session->setFlash('Registro eliminado correctamente.','guardar_exito');
							}
						}
					}
				
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
					$conditions["LugarReparacion.e"] = $this->request->query['estado'];
				}
				foreach($this->request->query as $key => $value) {
					$this->set($key, $value);
				}
			}
			
			$this->paginate = array('limit' => $limit, 'order' => 'LugarReparacion.nombre', 'recursive' => -1, 'conditions' => $conditions);
			$this->set('registros', $this->paginate('LugarReparacion'));
			$this->set('limit', $limit);
			$this->set('titulo', 'Lugar Reparación');
			$this->set('breadcrumb', 'Administración');
		}
		
		public function agregar(){
			$this->layout = 'metronic_principal';
			$this->check_permissions($this);
			$this->loadModel('LugarReparacion');
			if ($this->request->is('post')){
				try {
					if ($this->LugarReparacion->save($this->request->data)){
						$this->Session->setFlash('Registro ingresado correctamente','guardar_exito');
						$this->redirect(array('action' => 'index'));
					}else{
						$this->Session->setFlash('No se pudo ingresar el registro.','guardar_error');
					}
				} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar agregar el registro, intente nuevamente.','guardar_error');
				}
			}
			$this->set('titulo', 'Lugar Reparación');
			$this->set('breadcrumb', 'Administración');
		}
		
		public function editar() {
			$this->layout = 'metronic_principal';
			$this->check_permissions($this);
			$this->loadModel('LugarReparacion');
			//get the id of the user to be edited
			$id = $this->request->params['pass'][0];
			 
			//set the user id
			$this->LugarReparacion->id = $id;
			 
			//check if a user with this id really exists
			if( $this->LugarReparacion->exists() ){
			 
				if( $this->request->is( 'post' ) || $this->request->is( 'put' ) ){
					try {
						if( $this->LugarReparacion->save( $this->request->data ) ){
							$this->Session->setFlash('Registro editado correctamente.','guardar_exito');
							$this->redirect(array('action' => 'index'));
						}else{
							$this->Session->setFlash('No se pudo editar el registro','guardar_error');
						}
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar editar el registro, intente nuevamente.','guardar_error');
					}
				}else{
				 
					//we will read the user data
					//so it will fill up our html form automatically
					$this->request->data = $this->LugarReparacion->read();
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
			$this->set('titulo', 'Lugar Reparación');
			$this->set('breadcrumb', 'Administración');
		}
	}
?>