<?php
	App::uses('ConnectionManager', 'Model'); 
	class FluidosController extends AppController {
		public function index() {
			$this->layout = 'metronic_principal';
			$this->check_permissions($this);
			$this->loadModel('Fluido');
			$this->loadModel('FluidoTipoIngreso');
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
									$this->Fluido->save($data);
								}
							}
							if ($value == '1') {
								if (isset($this->request->data['estado']) && !isset($this->request->data['estado'][$key])) {
									// Estaba activado y se desactivo
									$data = array();
									$data["id"] = $key;
									$data["e"] = "0";
									$this->Fluido->save($data);
								}
							}
						}
						if(isset($this->request->data['btn-eliminar'])) {
							if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
								$this->Fluido->delete($key);
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
					$conditions["Fluido.e"] = $this->request->query['estado'];
				}
				foreach($this->request->query as $key => $value) {
					$this->set($key, $value);
				}
			}
			
			$this->paginate = array('limit' => $limit, 'order' => 'Fluido.nombre', 'recursive' => 1, 'conditions' => $conditions);
			$this->set('registros', $this->paginate('Fluido'));
			$this->set('limit', $limit);
			$tipos_ingresos = $this->FluidoTipoIngreso->find('list');
			$this->set(compact('tipos_ingresos'));
			$this->set('titulo', 'Fluidos');
			$this->set('breadcrumb', 'Administración');
		}
		
		public function agregar(){
			$this->layout = 'metronic_principal';
			$this->check_permissions($this);
			$this->loadModel('Fluido');
			$this->loadModel('FluidoUnidad');
			$this->loadModel('FluidoTipoIngreso');
			if ($this->request->is('post')){
				try {
					$data = array();
					$data = $this->request->data;
					$data["tipo_ingreso"] = implode(",", $data["tipos_ingresos"]);
					if($this->Fluido->save($data)){
						
						$this->Session->setFlash('Registro ingresado correctamente','guardar_exito');
						$this->redirect(array('action' => 'index'));
					
					}else{
					
						$this->Session->setFlash('No se pudo ingresar el registro.','guardar_error');
					
					}
				} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar agregar el registro, intente nuevamente.','guardar_error');
				}
			}
			$unidades = $this->FluidoUnidad->find('list');
			$this->set(compact('unidades'));
			$tipos_ingresos = $this->FluidoTipoIngreso->find('list');
			$this->set(compact('tipos_ingresos'));
			$this->set('titulo', 'Fluido');
			$this->set('breadcrumb', 'Administración');
		}
		
		public function editar() {
			$this->layout = 'metronic_principal';
			$this->check_permissions($this);
			$this->loadModel('Fluido');
			$this->loadModel('FluidoUnidad');
			$this->loadModel('FluidoTipoIngreso');
			//get the id of the user to be edited
			$id = $this->request->params['pass'][0];
			 
			//set the user id
			$this->Fluido->id = $id;
			 
			//check if a user with this id really exists
			if( $this->Fluido->exists() ){
			 
				if( $this->request->is( 'post' ) || $this->request->is( 'put' ) ){
					try {
						$data = array();
						$data = $this->request->data;
						$data["tipo_ingreso"] = implode(",", $data["tipos_ingresos"]);
						if($this->Fluido->save($data)){
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
					$this->request->data = $this->Fluido->read();
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
			
			$unidades = $this->FluidoUnidad->find('list');
			$this->set(compact('unidades'));
			$tipos_ingresos = $this->FluidoTipoIngreso->find('list');
			$this->set(compact('tipos_ingresos'));

			$this->set('titulo', 'Fluido');
			$this->set('breadcrumb', 'Administración');
		}
	}
?>