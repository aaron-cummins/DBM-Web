<?php
App::uses('ConnectionManager', 'Model'); 

class PermisoController extends AppController {
	public function usuario() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Faena');
		$this->loadModel('NivelUsuario');
		$this->loadModel('PermisoUsuario');
		$limit = 10;
		
		$conditions = array();
		if ($this->request->is('get')){
			if(isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
				$limit = $this->request->query['limit'];
			}
			if(isset($this->request->query['faena']) && is_numeric($this->request->query['faena'])) {
				$conditions["PermisoUsuario.faena_id"] = $this->request->query['faena'];
			}
			if(isset($this->request->query['cargo']) && is_numeric($this->request->query['cargo'])) {
				$conditions["PermisoUsuario.cargo_id"] = $this->request->query['cargo'];
			}
			if(isset($this->request->query['nombres']) && ($this->request->query['nombres'] != '')) {
				$conditions["LOWER(Usuario.nombres) LIKE"] = "%".strtolower($this->request->query['nombres'])."%";
			}
			if(isset($this->request->query['apellidos']) && ($this->request->query['apellidos']) != '') {
				$conditions["LOWER(Usuario.apellidos) LIKE"] = "%".strtolower($this->request->query['apellidos'])."%";
			}
			if(isset($this->request->query['rut']) && is_numeric($this->request->query['rut'])) {
				$conditions["CAST(Usuario.usuario AS TEXT) LIKE"] = $this->request->query['rut']."%";
			}
			if(isset($this->request->query['ucode']) && ($this->request->query['ucode']) != '') {
				$conditions["Usuario.ucode LIKE"] = "%".$this->request->query['ucode']."%";
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		$faenas = $this->Faena->find('list', array('conditions' => array('Faena.e' => '1'), 'order' => array("Faena.nombre" => 'asc'), 'recursive' => -1));
		$this->set(compact('faenas'));
		
		$cargos = $this->NivelUsuario->find('list', array('conditions' => array('NivelUsuario.e' => '1'), 'order' => array("NivelUsuario.nombre" => 'asc'), 'recursive' => -1));
		$this->set(compact('cargos'));
		
		$this->paginate = array('limit' => $limit, 'recursive' => 1, 'conditions' => $conditions);
		$this->set('registros', $this->paginate('PermisoUsuario'));
		$this->set('limit', $limit);
		$this->set('titulo', 'Permiso');
		$this->set('breadcrumb', 'Administración');
	}
	public function agregar() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Usuario');
		$this->loadModel('Faena');
		$this->loadModel('NivelUsuario');
		$this->loadModel('PermisoUsuario');
		
		$faenas = $this->Faena->find('list', array('conditions' => array('Faena.e' => '1'), 'order' => array("Faena.nombre" => 'asc'), 'recursive' => -1));
		$this->set(compact('faenas'));
		
		$cargos = $this->NivelUsuario->find('list', array('conditions' => array('NivelUsuario.e' => '1'), 'order' => array("NivelUsuario.nombre" => 'asc'), 'recursive' => -1));
		$this->set(compact('cargos'));
		
		$usuarios = $this->Usuario->find('all', array('conditions' => array('Usuario.e' => '1'), 'order' => array("Usuario.nombres" => 'asc', "Usuario.apellidos" => 'asc'), 'recursive' => -1));
		$this->set('usuarios', $usuarios);
		
		$this->set('titulo', 'Permiso');
		$this->set('breadcrumb', 'Administración');
	}
	
	public function error() {
		$this->layout = 'metronic_principal';
 
	}
	
	public function prohibido() {
		$this->layout = 'metronic_principal';
 
	}
	
	public function login() {
		//$this->layout = 'metronic_principal';
		echo "no login";
	}
	
	public function cargo() {
		$this->layout = 'metronic_principal';
 
	}
	
	public function vista() {
		$this->layout = 'metronic_principal';
 
	}
	
	public function acceso() {
		$this->layout = 'metronic_principal';
 
	}
	
	public function faena($faena_id) {
		$this->layout = null;
		if(is_numeric($faena_id)){
			$this->Session->write("faena_id", $faena_id);
			$this->redirect( Router::url( $this->referer(), true ) );
		}
	}
	
	public function check_menu_item($controller, $view, $faena_id, $usuario_id, $cargos){
		$this->loadModel('Vista');
		$this->loadModel('PermisoGlobal');
		$this->loadModel('PermisoUsuario'); 
		$this->loadModel('PermisoPersonalizado');
		$vista_id = 0;
		
		$vista = $this->Vista->find('first', array(
			'fields' => array('Vista.id'),
			'conditions' => array("LOWER(Vista.controller)" => strtolower($controller), 'LOWER(Vista.action) LIKE' => "%".strtolower($action)."%"),
			'recursive' => -1
		));
		
		if(isset($vista) && isset($vista["Vista"]["id"])) {
			$vista_id = $vista["Vista"]["id"];
		}else{
			return false;
		}
		
		if(!isset($cargos[$faena_id]) || !isset($cargos[$faena_id][0]) || !is_numeric($cargos[$faena_id][0])) {
			return false;
		}
		
		if($vista_id != null) {
			$permisos = $this->PermisoPersonalizado->find('all', array(
				'fields' => array('PermisoPersonalizado.id'),
				'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session),
				'recursive' => -1
			));
			debug($permisos);
			if(isset($permisos) && isset($permisos[0]["PermisoPersonalizado"])) {
				$permiso = $this->PermisoPersonalizado->find('first', array(
					'fields' => array('PermisoPersonalizado.id'),
					'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.vista_id" => $vista_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session),
					'recursive' => -1
				));
				if(isset($permiso) && isset($permiso["PermisoPersonalizado"]["id"])) {
					return true;
				} else {				
					return false;
				}
			} else {
				$permiso = $this->PermisoGlobal->find('first', array(
					'fields' => array('PermisoGlobal.id'),
					'conditions' => array("PermisoGlobal.cargo_id IN" => $cargos[$faena_session], "PermisoGlobal.vista_id" => $vista_id),
					'recursive' => -1
				));
				if(isset($permiso) && isset($permiso["PermisoGlobal"]["id"])) {
					return true;
				} else {				
					return false;
				}
			}
		}
	}
}
?>