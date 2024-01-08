<?php
App::uses('ConnectionManager', 'Model'); 

class MotivoCategoriaSintomaController extends AppController {
	public function agregar(){
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('MotivoCategoriaSintoma');
		$this->loadModel('MotivoLlamado');
		$this->loadModel('SintomaCategoria');
		$this->loadModel('Sintoma');
		if ($this->request->is('post')){
			try {
				if ($this->MotivoCategoriaSintoma->save($this->request->data)){
					$this->Session->setFlash('Registro ingresado correctamente','guardar_exito');
					$this->redirect(array('action' => 'index'));
				}else{
					$this->Session->setFlash('No se pudo ingresar el registro, intente nuevamente.','guardar_error');
				}
			} catch(Exception $e) {
				$this->Session->setFlash('Ocurrió un error al ingresar el registro, intente nuevamente.','guardar_error');
			}
		}
		
		$this->set('titulo', 'Matriz síntoma');
		$this->set('breadcrumb', 'Administración');
		
		$motivos = $this->MotivoLlamado->find('list', array('order' => 'nombre'));
		$this->set(compact('motivos'));
		$categorias = $this->SintomaCategoria->find('list', array('order' => 'nombre'));
		$this->set(compact('categorias'));
		$sintomas = $this->Sintoma->find('all', array('order' => array('codigo','nombre'), 'recursive' => -1));
		$this->set('sintomas', $sintomas);
	}
	
	public function index() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('MotivoCategoriaSintoma');
		$this->loadModel('MotivoLlamado');
		$this->loadModel('SintomaCategoria');
		$this->loadModel('Sintoma');
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
									$this->MotivoCategoriaSintoma->save($data);
								}
							}
							if ($value == '1') {
								if (!isset($this->request->data['estado'][$key])) {
									// Estaba activado y se desactivo
									$data = array();
									$data["id"] = $key;
									$data["e"] = "0";
									$this->MotivoCategoriaSintoma->save($data);
								}
							}
							$this->Session->setFlash('Cambio de estado de los registros realizados correctamente. ','guardar_exito');
						}
						if(isset($this->request->data['btn-eliminar'])) {
							if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
								$this->MotivoCategoriaSintoma->delete($key);
								$this->Session->setFlash('Registro(s) eliminado(s) correctamente.','guardar_exito');
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
				$conditions["MotivoCategoriaSintoma.e"] = $this->request->query['estado'];
			}
			if(isset($this->request->query['motivo_id']) && is_numeric($this->request->query['motivo_id'])) {
				$conditions["MotivoCategoriaSintoma.motivo_id"] = $this->request->query['motivo_id'];
			}
			if(isset($this->request->query['categoria_id']) && is_numeric($this->request->query['categoria_id'])) {
				$conditions["MotivoCategoriaSintoma.categoria_id"] = $this->request->query['categoria_id'];
			}
			if(isset($this->request->query['sintoma_id']) && is_numeric($this->request->query['sintoma_id'])) {
				$conditions["MotivoCategoriaSintoma.sintoma_id"] = $this->request->query['sintoma_id'];
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		$this->paginate = array('limit' => $limit, 'order' => array('Motivo.nombre','Categoria.nombre','Sintoma.nombre'), 'recursive' => 1, 'conditions' => $conditions);
		$this->set('registros', $this->paginate('MotivoCategoriaSintoma'));
		$this->set('limit', $limit);
		$this->set('titulo', 'Matriz síntoma');
		$this->set('breadcrumb', 'Administración');
		
		$motivos = $this->MotivoLlamado->find('list', array('order' => 'nombre'));
		$this->set(compact('motivos'));
		$categorias = $this->SintomaCategoria->find('list', array('order' => 'nombre'));
		$this->set(compact('categorias'));
		$sintomas = $this->Sintoma->find('all', array('order' => array('codigo','nombre'), 'recursive' => -1));
		$this->set('sintomas', $sintomas);
	}
	
	public function editar() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('MotivoCategoriaSintoma');
		$this->loadModel('MotivoLlamado');
		$this->loadModel('SintomaCategoria');
		$this->loadModel('Sintoma');
		$id = $this->request->params['pass'][0];
		$this->MotivoCategoriaSintoma->id = $id;
		if( $this->MotivoCategoriaSintoma->exists() ){
			if( $this->request->is( 'post' ) || $this->request->is( 'put' ) ){
				try {
					if( $this->MotivoCategoriaSintoma->save( $this->request->data ) ){					 
						$this->Session->setFlash('Registro editado correctamente.','guardar_exito');
						$this->redirect(array('action' => 'index'));
						 
					}else{
						$this->Session->setFlash('No se pudo editar el registro.','guardar_error');
					}
				} catch(Exception $e) {
					$this->Session->setFlash('Ocurrió un error al editar el registro, intente nuevamente.','guardar_error');
				}
			}else{
				$this->request->data = $this->MotivoCategoriaSintoma->read();
				$this->set('data', $this->request->data);
			}
		}else{
			$this->Session->setFlash('The user you are trying to edit does not exist.');
			$this->redirect(array('action' => 'index'));
		}
		$motivos = $this->MotivoLlamado->find('list', array('order' => 'nombre'));
		$this->set(compact('motivos'));
		$categorias = $this->SintomaCategoria->find('list', array('order' => 'nombre'));
		$this->set(compact('categorias'));
		$sintomas = $this->Sintoma->find('all', array('order' => array('codigo','nombre'), 'recursive' => -1));
		$this->set('sintomas', $sintomas);
		$this->set('titulo', 'Matriz síntoma');
		$this->set('breadcrumb', 'Administración');
	}
	 
	public function get_categoria($motivo_id){
		$this->layout = null;
		$this->loadModel('MotivoCategoriaSintoma');
		$registros = $this->MotivoCategoriaSintoma->find('list', array('fields' => array('Categoria.id', 'Categoria.nombre'), 'conditions' => array('motivo_id' => $motivo_id), 'order' => 'Categoria.nombre', 'recursive' => 1));
		echo json_encode($registros, true);
		exit;
	}
	
	public function get_sintoma($categoria_id){
		$this->layout = null;
		$this->loadModel('MotivoCategoriaSintoma');
		$registros = $this->MotivoCategoriaSintoma->find('all', array('fields' => array('Sintoma.id', 'Sintoma.nombre','Sintoma.codigo'), 'conditions' => array('categoria_id' => $categoria_id), 'order' => array('Sintoma.nombre' => "ASC"), 'recursive' => 1));
		$tmp = array();
		foreach($registros as $registro) {
			if(is_numeric($registro["Sintoma"]["codigo"]) && $registro["Sintoma"]["codigo"] != "0"){
				$tmp[$registro["Sintoma"]["id"]] = "FC ".$registro["Sintoma"]["codigo"] ." " .$registro["Sintoma"]["nombre"];
			} else {
				$tmp[$registro["Sintoma"]["id"]] = $registro["Sintoma"]["nombre"];
			}
		}
		$registros = $tmp;
		echo json_encode($registros, true);
		exit;
	}
}
?>