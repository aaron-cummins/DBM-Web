<?php
	App::uses('ConnectionManager', 'Model'); 
	class UsuarioController extends AppController {
		
		public function perfil() {
			$this->check_permissions($this);
			$this->set('titulo', 'Perfil de Usuario');
			
			if (count($this->request->data)) {
				$this->loadModel('Usuario');
				
				$resultado = $this->Usuario->find('first', array(
					'conditions' => array('Usuario.id' => $this->request->data["id"])
				));
				
				$resultado["Usuario"]["nombres"] = $this->request->data["nombres"];
				$resultado["Usuario"]["apellidos"] = $this->request->data["apellidos"];
				$resultado["Usuario"]["correo_electronico"] = $this->request->data["correo_electronico"];
				$this->Usuario->save($resultado["Usuario"]);
				$this->Session->write('Usuario', $resultado["Usuario"]);
				$this->Session->setFlash('Datos actualizados con éxito!', 'guardar_exito');
			}
			
			$usuario = $this->Session->read('Usuario');
			$this->set('nombres', $usuario['nombres']);
			$this->set('apellidos', $usuario['apellidos']);
			$this->set('correo_electronico', $usuario['correo_electronico']);
			$this->set('id', $usuario['id']);
		}
		
		public function index() {
			$this->layout = 'metronic_principal'; 
			$this->check_permissions($this);
			$this->loadModel('Usuario');
			$this->loadModel('Faena');
			$this->loadModel('NivelUsuario');
			$this->loadModel('PermisoUsuario');
			$limit = 10;
			$nombres = '';
			$apellidos = '';
			$ucode = '';
			$usuario = '';
			$correo_electronico = '';
			$faena_id = '';
			$cargo_id = '';
			
			if ($this->request->is('post')){
				try {
					foreach($this->request->data['registro'] as $key => $value) {
						if ($value == '0') {
							if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
								// Estaba desactivado y se activo
								$data = array();
								$data["id"] = $key; 
								$data["e"] = "1";
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Usuario->save( $data );
							}
						}
						if ($value == '1') {
							if (!isset($this->request->data['estado'][$key])) {
								// Estaba activado y se desactivo
								$data = array();
								$data["id"] = $key;
								$data["e"] = "0"; 
								$data["updated"] = date("Y-m-d H:i:s");
								$this->Usuario->save( $data );
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
					$conditions["Usuario.e"] = $this->request->query['estado'];
				}
				if(isset($this->request->query['data']['nombres']) && $this->request->query['data']['nombres'] != '') {
					$conditions["LOWER(Usuario.nombres) LIKE"] = '%'.strtolower($this->request->query['data']['nombres']).'%';
					$nombres = $this->request->query['data']['nombres'];
				}
				if(isset($this->request->query['data']['apellidos']) && $this->request->query['data']['apellidos'] != '') {
					$conditions["LOWER(Usuario.apellidos) LIKE"] = '%'.strtolower($this->request->query['data']['apellidos']).'%';
					$apellidos = $this->request->query['data']['apellidos'];
				}
				if(isset($this->request->query['data']['ucode']) && $this->request->query['data']['ucode'] != '') {
					$conditions["LOWER(Usuario.u) LIKE"] = '%'.strtolower($this->request->query['data']['ucode']).'%';
					$ucode = $this->request->query['data']['ucode'];
				}
				if(isset($this->request->query['data']['usuario']) && $this->request->query['data']['usuario'] != '') {
					$conditions["CAST(Usuario.usuario AS TEXT) LIKE"] = '%'.strtolower($this->request->query['data']['usuario']).'%';
					$usuario = $this->request->query['data']['usuario'];
				}
				if(isset($this->request->query['data']['correo_electronico']) && $this->request->query['data']['correo_electronico'] != '') {
					$conditions["LOWER(Usuario.correo_electronico) LIKE"] = '%'.strtolower($this->request->query['data']['correo_electronico']).'%';
					$correo_electronico = $this->request->query['data']['correo_electronico'];
				}
				if(isset($this->request->query['data']['faena_id']) && $this->request->query['data']['faena_id'] != '') {
					$conditions["Faena.id"] = $this->request->query['data']['faena_id'];
					$faena_id = $this->request->query['data']['faena_id'];
				}
				if(isset($this->request->query['data']['cargo_id']) && $this->request->query['data']['cargo_id'] != '') {
					$conditions["Cargo.id"] = $this->request->query['data']['cargo_id'];
					$cargo_id = $this->request->query['data']['cargo_id'];
				}
				foreach($this->request->query as $key => $value) {
					$this->set($key, $value);
				}
			}
			
			$faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
			$this->set(compact('faenas'));
			
			$cargos = $this->NivelUsuario->find('list', array('order' => array("NivelUsuario.nombre"), 'conditions' => array('e' => '1'), 'recursive' => -1));		
			$this->set(compact('cargos'));
			
			if($faena_id != '' && $cargo_id != ''){
				$conditions["PermisoUsuario.e"] = "1";
				$this->paginate = array('limit' => $limit, 'fields' => array('DISTINCT Usuario.id','Usuario.nombres','Usuario.apellidos','Usuario.correo_electronico','Usuario.u','Usuario.e','Usuario.usuario','Faena.nombre','Cargo.nombre'), 'order' => 'Usuario.nombres, Usuario.apellidos', 'recursive' => 1, 'conditions' => $conditions);
				$this->set('registros', $this->paginate('PermisoUsuario'));
			} elseif ($faena_id != '') {
				$conditions["PermisoUsuario.e"] = "1";
				$this->paginate = array('limit' => $limit, 'fields' => array('DISTINCT Usuario.id','Usuario.nombres','Usuario.apellidos','Usuario.correo_electronico','Usuario.u','Usuario.e','Usuario.usuario','Faena.nombre'), 'order' => 'Usuario.nombres, Usuario.apellidos', 'recursive' => 1, 'conditions' => $conditions);
				$this->set('registros', $this->paginate('PermisoUsuario'));
			} elseif ($cargo_id != ''){
				$conditions["PermisoUsuario.e"] = "1";
				$this->paginate = array('limit' => $limit, 'fields' => array('DISTINCT Usuario.id','Usuario.nombres','Usuario.apellidos','Usuario.correo_electronico','Usuario.u','Usuario.e','Usuario.usuario','Cargo.nombre'), 'order' => 'Usuario.nombres, Usuario.apellidos', 'recursive' => 1, 'conditions' => $conditions);
				$this->set('registros', $this->paginate('PermisoUsuario'));
			} else {
				$this->paginate = array('limit' => $limit, 'order' => 'Usuario.nombres, Usuario.apellidos', 'recursive' => -1, 'conditions' => $conditions);
				$this->set('registros', $this->paginate('Usuario'));
			}
			
			$permisos = null;
			if($faena_id != ''){
				$permisos = $this->PermisoUsuario->find('all', array('fields' => array('Faena.nombre','Cargo.nombre','Faena.id','Cargo.id','Usuario.id','PermisoUsuario.e'), 'conditions' => array('Faena.id' => $faena_id, 'PermisoUsuario.e' => '1'), 'recursive' => 1));		
			} elseif($cargo_id != ''){
				$permisos = $this->PermisoUsuario->find('all', array('fields' => array('Faena.nombre','Cargo.nombre','Faena.id','Cargo.id','Usuario.id','PermisoUsuario.e'), 'conditions' => array('Cargo.id' => $cargo_id, 'PermisoUsuario.e' => '1'), 'recursive' => 1));		
			} else {
				$permisos = $this->PermisoUsuario->find('all', array('fields' => array('Faena.nombre','Cargo.nombre','Faena.id','Cargo.id','Usuario.id','PermisoUsuario.e'), 'conditions' => array('PermisoUsuario.e' => '1'), 'recursive' => 1));		
			}
			
			//echo $permiso["PermisoUsuario"]["e"]." ";
			$listado = array();
			foreach($permisos as $permiso){
				if ($permiso["PermisoUsuario"]["e"] == "1") {
					$listado[$permiso["Usuario"]["id"]]["Faenas"][$permiso["Faena"]["id"]] = $permiso["Faena"]["nombre"];
					$listado[$permiso["Usuario"]["id"]]["Cargos"][$permiso["Cargo"]["id"]] = $permiso["Cargo"]["nombre"];
				}
			}
			
			//echo "<pre>";
			//print_r($listado);
			//echo "</pre>";
			//debug($listado);
			
			//$this->paginate = array('limit' => $limit, 'order' => 'Usuario.nombres, Usuario.apellidos', 'recursive' => -1, 'conditions' => $conditions);
			//$this->set('registros', $this->paginate('Usuario'));
			$this->set('listado', $listado);
			$this->set('limit', $limit);
			$this->set('nombres', $nombres);
			$this->set('faena_id', $faena_id);
			$this->set('cargo_id', $cargo_id);
			$this->set('ucode', $ucode);
			$this->set('usuario', $usuario);
			$this->set('correo_electronico', $correo_electronico);
			$this->set('apellidos', $apellidos);
			$this->set('titulo', 'Usuario');
			$this->set('breadcrumb', 'Administración');
		}
		
		public function usuario_editar($id) {
			$this->layout="login";
			$usuario = $this->Session->read('Usuario');
			if ($usuario["nivelusuario_id"] != '4') {
				header("HTTP/1.0 404 Not Found");
				exit;
			}
			$this->loadModel('Usuario');
			
			if(isset($this->request->data)&&isset($this->request->data["btnsubmit"])){
				$usuario = $this->Usuario->find('first', array('conditions'=>array('id='.$this->request->data["id"]),'recursive'=>-1));
				$usuario=$usuario["Usuario"];
				$usuario["nombres"]=$this->request->data["nombres"];
				$usuario["apellidos"]=$this->request->data["apellidos"];
				$usuario["usuario"]=$this->request->data["usuario"];
				$usuario["correo_electronico"]=$this->request->data["correo_electronico"];
				$this->Usuario->save($usuario);
				$this->Session->setFlash('Datos guardados con éxito!', 'guardar_exito');
			}
			
			$resultado = $this->Usuario->find('first', array(
				'order' => 'Usuario.apellidos, Usuario.nombres',
				'conditions' => "Usuario.id=$id",
				'recursive' => -1
			));
			$this->set('resultado', $resultado);
		}
		
		
		
		public function editar() {
			$this->layout = 'metronic_principal';
			$this->check_permissions($this);
			$this->loadModel('Usuario');
			//get the id of the user to be edited
			$id = $this->request->params['pass'][0];
			 
			//set the user id
			$this->Usuario->id = $id;
			 
			//check if a user with this id really exists
			if( $this->Usuario->exists() ){
			 
				if( $this->request->is( 'post' ) || $this->request->is( 'put' ) ){
					try {
						// Se verifica que el rut ingresado no exista
						{
							$usuario_check = $this->Usuario->find('first', array('fields' => array("Usuario.id"),
																			   'conditions' => array('Usuario.id !=' => $this->Usuario->id, 'Usuario.usuario' => $this->request->data["usuario"]), 
																			   'recursive' => -1));
							if(isset($usuario_check["Usuario"]["id"])){
								$this->Session->setFlash('El rut ingresado ya existe en DBM.','guardar_error');
								$this->request->data["Usuario"] = $this->request->data;
								$this->set('data', $this->request->data);
								return;
							}
						}
						
						// Se verifica que el correo ingresado no exista
						{
							$usuario_check = $this->Usuario->find('first', array('fields' => array("Usuario.id"),
																			   'conditions' => array('Usuario.id !=' => $this->Usuario->id, 'Usuario.correo_electronico' => $this->request->data["correo_electronico"]), 
																			   'recursive' => -1));
							if(isset($usuario_check["Usuario"]["id"])){
								$this->Session->setFlash('El correo electrónico ingresado ya existe en DBM.','guardar_error');
								$this->request->data["Usuario"] = $this->request->data;
								$this->set('data', $this->request->data);
								return;
							}
						}
						
						// Se verifica que el U no exista
						{
							$usuario_check = $this->Usuario->find('first', array('fields' => array("Usuario.id"),
																			   'conditions' => array('Usuario.id !=' => $this->Usuario->id, 'Usuario.u' => $this->request->data["u"]), 
																			   'recursive' => -1));
							if(isset($usuario_check["Usuario"]["id"])){
								$this->Session->setFlash('El U ingresado ya existe en DBM.','guardar_error');
								$this->request->data["Usuario"] = $this->request->data;
								$this->set('data', $this->request->data);
								return;
							}
						}
                                                /*
						if( !$this->esContrasenaValida($this->request->data["pin"])){
							return;
						}
                                                */
						if ( ($this->request->data["pin"] != '') && (!$this->esContrasenaValida($this->request->data["pin"])) ) {
                                                    return;
                                                }

						
						if(trim($this->request->data["pin"]) != '') {
							$this->request->data["pin"] = password_hash($this->request->data["pin"],PASSWORD_BCRYPT);
						} else {
							unset($this->request->data["pin"]);
						}
						if( $this->Usuario->save( $this->request->data ) ){
						 
							//set to user's screen
							$this->Session->setFlash('Registro editado correctamente.','guardar_exito');
							 
							//redirect to user's list
							$this->redirect(array('action' => 'index'));
							 
						}else{
							$this->Session->setFlash('No se pudo editar el registro.','guardar_error');
						}
					} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar editar el registro, intente nuevamente. ' . ($e->getMessage()),'guardar_error');
					}
				}else{
				 
					//we will read the user data
					//so it will fill up our html form automatically
					$this->request->data = $this->Usuario->read();
					$this->request->data['Usuario']['nombres'] = ucwords(strtolower($this->request->data['Usuario']['nombres']));
					$this->request->data['Usuario']['apellidos'] = ucwords(strtolower($this->request->data['Usuario']['apellidos']));
					$this->request->data['Usuario']['correo_electronico'] = strtolower($this->request->data['Usuario']['correo_electronico']);
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
			
			$this->set('titulo', 'Usuario');
			$this->set('breadcrumb', 'Administración');
		}
		
		public function contrasena() {
			$this->check_permissions($this);
			//$this->loadModel('Faena');
			$this->loadModel('Usuario');
			//get the id of the user to be edited
			$id = $this->request->params['pass'][0];
			 
			//set the user id
			$this->Usuario->id = $id;
			 
			//check if a user with this id really exists
			if( $this->Usuario->exists() ){
			 
				if( $this->request->is( 'post' ) || $this->request->is( 'put' ) ){
					//save user
					if(trim($this->request->data["pin"]) != '') {
						$this->request->data["pin"] = password_hash($this->request->data["pin"],PASSWORD_BCRYPT);
					} else {
						unset($this->request->data["pin"]);
					}
					if( $this->Usuario->save( $this->request->data ) ){
					 
						//set to user's screen
						$this->Session->setFlash('User was edited.');
						 
						//redirect to user's list
						$this->redirect(array('action' => 'index'));
						 
					}else{
						$this->Session->setFlash('Unable to edit user. Please, try again.');
					}
					 
				}else{
				 
					//we will read the user data
					//so it will fill up our html form automatically
					$this->request->data = $this->Usuario->read();
				}
				 
			}else{
				//if not found, we will tell the user that user does not exist
				$this->Session->setFlash('The user you are trying to edit does not exist.');
				$this->redirect(array('action' => 'index'));
					 
				//or, since it we are using php5, we can throw an exception
				//it looks like this
				//throw new NotFoundException('The user you are trying to edit does not exist.');
			}
		}
		
		public function agregar(){
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
			
			if ($this->request->is('post')){
				try {
					
					// Se verifica que el rut ingresado no exista
					{
						$usuario_check = $this->Usuario->find('first', array('fields' => array("Usuario.id"),
																		   'conditions' => array('Usuario.usuario' => $this->request->data["usuario"]), 
																		   'recursive' => -1));
						if(isset($usuario_check["Usuario"]["id"])){
							$this->Session->setFlash('El rut ingresado ya existe en DBM.','guardar_error');
							$this->request->data["Usuario"] = $this->request->data;
							$this->set('data', $this->request->data);
							return;
						}
					}
					
					// Se verifica que el correo ingresado no exista
					{
						$usuario_check = $this->Usuario->find('first', array('fields' => array("Usuario.id"),
																		   'conditions' => array('Usuario.correo_electronico' => $this->request->data["correo_electronico"]), 
																		   'recursive' => -1));
						if(isset($usuario_check["Usuario"]["id"])){
							$this->Session->setFlash('El correo electrónico ingresado ya existe en DBM.','guardar_error');
							$this->request->data["Usuario"] = $this->request->data;
							$this->set('data', $this->request->data);
							return;
						}
					}
					
					// Se verifica que el U no exista
					{
						$usuario_check = $this->Usuario->find('first', array('fields' => array("Usuario.id"),
																		   'conditions' => array('Usuario.u' => $this->request->data["u"]), 
																		   'recursive' => -1));
						if(isset($usuario_check["Usuario"]["id"])){
							$this->Session->setFlash('El U ingresado ya existe en DBM.','guardar_error');
							$this->request->data["Usuario"] = $this->request->data;
							$this->set('data', $this->request->data);
							return;
						}
					}
                                        /*
					if(!$this->esContrasenaValida($this->request->data["pin"])) {
							return;
					}
					$this->request->data["pin"] = password_hash($this->request->data["pin"],PASSWORD_BCRYPT);
                                        */
                                        $pass = "DCC" . substr($this->request->data["usuario"], 0, 5);
                                        $this->request->data["pin"]= password_hash($pass, PASSWORD_BCRYPT);
					
					if ($this->Usuario->save($this->request->data)){
						// Asignar cargo y faena inicial
						$data = array();
						$data["faena_id"] = $this->request->data["faena"];
						$data["cargo_id"] = $this->request->data["cargo"];
						$data["usuario_id"] = $this->Usuario->id;
						$this->PermisoUsuario->create();
						$this->PermisoUsuario->save($data);
						$this->Session->setFlash('Registro ingresado con éxito.', 'guardar_exito');
						$this->redirect(array('action' => 'index'));
					}else{
						$this->Session->setFlash('No se pudo agregar el registro, intente nuevamente.','guardar_error');
					}
				} catch(Exception $e) {
						$this->Session->setFlash('Ocurrió un error al intentar agregar el registro, intente nuevamente.','guardar_error');
				}
			}
			
			$this->set('titulo', 'Usuario');
			$this->set('breadcrumb', 'Administración');
		}
		
		public function faena(){
			$this->layout = 'metronic_principal';
			$this->check_permissions($this);
			$this->loadModel('Faena');
			$this->loadModel('Usuario');
			$this->loadModel('PermisoUsuario');
			$this->loadModel('PermisoPersonalizado');
			$id = $this->request->params['pass'][0];
			$this->Usuario->id = $id;
			
			if ($this->request->is('post')){
				try {
					$faenas = array();
					$faenas = $this->request->data('faenas');
					$faenas[] = "-1";
					
					// Update a todos los permisos existentes, se les asigna e = '0' y upteda = now()
					$this->PermisoUsuario->updateAll(
						array('PermisoUsuario.e' => "'0'", 'PermisoUsuario.updated' => "'".date("Y-m-d H:i:s")."'"),
						array('PermisoUsuario.usuario_id' => $this->Usuario->id, 'PermisoUsuario.faena_id NOT IN' => $faenas)
					);
					
					/*$this->PermisoPersonalizado->updateAll(
						array('PermisoPersonalizado.e' => "'0'", 'PermisoPersonalizado.updated' => "'".date("Y-m-d H:i:s")."'"),
						array('PermisoPersonalizado.usuario_id' => $this->Usuario->id, 'PermisoPersonalizado.faena_id NOT IN' => $faenas)
					);*/

                    $this->PermisoPersonalizado->deleteAll(
                        array('PermisoPersonalizado.usuario_id' => $this->Usuario->id, 'PermisoPersonalizado.faena_id NOT IN' => $faenas)
                    );


                    //$this->PermisoUsuario->deleteAll(array('usuario_id' => $this->Usuario->id, 'NOT'=> array("faena_id" => $this->request->data('faenas'))), false);
					//$this->PermisoPersonalizado->deleteAll(array('usuario_id' => $this->Usuario->id, 'NOT' => array("faena_id" => $this->request->data('faenas'))), false);c
					
					foreach($this->request->data('faenas') as $value) {
						$permiso = $this->PermisoUsuario->find("first",array('conditions' => array('usuario_id' => $this->Usuario->id, 'faena_id' => $value), 'recursive' => -1));
						if(!isset($permiso["PermisoUsuario"]["id"])){
							$data = array();
							$data["faena_id"] = $value;
							$data["usuario_id"] = $this->Usuario->id;
							$this->PermisoUsuario->create();
							$this->PermisoUsuario->save($data);
						} else {
							if ($permiso["PermisoUsuario"]["e"] == '0'){
								$this->PermisoUsuario->updateAll(
									array('PermisoUsuario.e' => "'1'", 'PermisoUsuario.updated' => "'".date("Y-m-d H:i:s")."'"),
									array('PermisoUsuario.id' => $permiso["PermisoUsuario"]["id"])
								);
							}
						}
					}
					$this->Session->setFlash('Asignación de faenas aplicada exitosamente.','guardar_exito');
				} catch(Exception $e) {
					$this->Session->setFlash('Ocurrió un error al intentar editar el registro, intente nuevamente.'. $e->getMessage(),'guardar_error');
				}
			}
			
			if( $this->Usuario->exists() ){
				$this->request->data = $this->Usuario->read();
				$this->request->data['Usuario']['nombres'] = $this->request->data['Usuario']['nombres'];
				$this->request->data['Usuario']['apellidos'] = $this->request->data['Usuario']['apellidos'];
				$this->request->data['Usuario']['correo_electronico'] = strtolower($this->request->data['Usuario']['correo_electronico']);
				$this->set('data', $this->request->data);
			}
			
			$faenas = $this->PermisoUsuario->find("all", array('fields' => array("faena_id"),'conditions' => array('usuario_id' => $this->Usuario->id, 'e' => '1'), 'recursive' => -1));
			$faenas_permiso = array();
			foreach($faenas as $value) {
				$faenas_permiso[] = $value["PermisoUsuario"]["faena_id"];
			}
			$this->set('faenas_permiso', $faenas_permiso);
			$this->set('titulo', 'Usuario');
			$this->set('breadcrumb', 'Administración');
			$faenas = $this->Faena->find('list', array('conditions' => array('Faena.e' => '1'), 'order' => array("Faena.nombre" => 'asc'), 'recursive' => -1));
			$this->set(compact('faenas'));
		}
		
		public function cuadromando(){
			$this->layout = 'metronic_principal';
			$this->check_permissions($this);
			$this->loadModel('Faena');
			$this->loadModel('NivelUsuario');
			$this->loadModel('PermisoUsuario');
			$this->loadModel('PermisoPersonalizado');
			$this->loadModel('PermisoGlobal');
			$this->loadModel('Vista');
			$this->set("faena_id", "");
			
			$id = $this->request->params['pass'][0];
			$this->Usuario->id = $id;
			$this->set("usuario_id", $id);
			if ($this->request->is('post')){
				try {
					if(isset($this->request->data['cuadro_mando'])) {
						$this->logger($this, "Actualizacion cuadro de mando");
						$this->PermisoUsuario->deleteAll(array('usuario_id' => $this->Usuario->id, "faena_id" => $this->request->data('faena')), false);
						
						if(isset($this->request->data["cargo"])){
							foreach($this->request->data["cargo"] as $key => $value) {
								$data = array();
								$data["cargo_id"] = $key;
								$data["usuario_id"] = $this->Usuario->id;
								$data["faena_id"] = $this->request->data["faena"];
								$this->PermisoUsuario->create();
								$this->PermisoUsuario->save($data);
							}
						}
						
						$this->PermisoPersonalizado->deleteAll(array('usuario_id' => $this->Usuario->id, "faena_id" => $this->request->data('faena')), false);


						if(isset($this->request->data["vista"])){
                            foreach($this->request->data["vista"] as $key => $value) {
								foreach($value as $key2 => $value2) {
                                       $data = array();
									$data["cargo_id"] = $key;
									$data["vista_id"] = $key2;
									$data["usuario_id"] = $this->Usuario->id;
									$data["faena_id"] = $this->request->data('faena');

                                    $this->PermisoPersonalizado->create();
									$this->PermisoPersonalizado->save($data);
								}
							}
						}
						
						if(isset($this->request->data["rol"])){
							foreach($this->request->data["rol"] as $key => $value) {
								foreach($value as $key2 => $value2) {
									$data = array();
									$data["cargo_id"] = $key;
									$data["rol_id"] = $key2;
									$data["usuario_id"] = $this->Usuario->id;
									$data["faena_id"] = $this->request->data('faena');
									$this->PermisoPersonalizado->create();
									$this->PermisoPersonalizado->save($data);
								}
							}
						}
						
						if(isset($this->request->data["correo"])){
							foreach($this->request->data["correo"] as $key => $value) {
								foreach($value as $key2 => $value2) {
									$data = array();
									$data["cargo_id"] = $key;
									$data["correo_id"] = $key2;
									$data["usuario_id"] = $this->Usuario->id;
									$data["faena_id"] = $this->request->data('faena');
									$this->PermisoPersonalizado->create();
									$this->PermisoPersonalizado->save($data);
								}
							}
						}
						
						// Actualizar usuario
						$data = array();
						$data["id"] = $this->Usuario->id;
						$data["updated"] = date("Y-m-d H:i:s");
						$this->Usuario->save($data);
						$this->recargarPermisos();

						
						$this->Session->setFlash('Permisos actualizados correctamente.','guardar_exito');
					}
				} catch(Exception $e) {
					$this->Session->setFlash('Ocurrió un error al intentar editar el cuadro de mando, intente nuevamente.','guardar_error');
				}
				
				if(isset($this->request->data['faena'])) {
					$cargos = $this->PermisoUsuario->find("all", array('fields' => array("cargo_id"),'conditions' => array('usuario_id' => $this->Usuario->id, 'faena_id' => $this->request->data['faena'], 'PermisoUsuario.e' => '1'), 'recursive' => -1));
					$cargos_permiso = array();
					foreach($cargos as $value) {
						$cargos_permiso[] = $value["PermisoUsuario"]["cargo_id"];
					}
					$this->set('cargos_permisos', $cargos_permiso);
					
					$cargos = $this->NivelUsuario->find('list', array('conditions' => array('NivelUsuario.e' => '1'), 'order' => array("NivelUsuario.nombre" => 'asc'), 'recursive' => -1));
					$this->set(compact('cargos'));
					
					$vistas = $this->Vista->find('list', array('conditions' => array('Vista.e' => '1'), 'order' => array("Vista.nombre" => 'asc'), 'recursive' => -1));
					$this->set(compact('vistas'));
					
					$this->set("faena_id", $this->request->data['faena']);
					
					$permisos_personalizados = $this->PermisoPersonalizado->find('all', array('conditions' => array('usuario_id' => $this->Usuario->id, 'faena_id' => $this->request->data['faena']), 'recursive' => -1));
					//$this->set('permisos_global', $permisos_global);
					//print_r(count($permisos_global));
					//print_r($permisos_global);
                    $permisos = array();
					foreach($permisos_personalizados as $key => $value){
						//print_r($value);
						foreach($value as $key => $value){
							//print_r($value);
							$permisos[]["PermisoGlobal"] = $value;
						}
					}
					/** Se muestran todos los permisos de usuarios, independiente de los que tenga asignados **/
                    /*if(count($permisos) == 0){
						$permisos_global = $this->PermisoGlobal->find('all', array('conditions' => array('PermisoGlobal.e' => '1'), 'recursive' => -1));
					} else {
						$permisos_global = $permisos;
					}*/

                    $permisos_global = $this->PermisoGlobal->find('all', array('conditions' => array('PermisoGlobal.e' => '1'), 'recursive' => -1));
                    $permisos_todos = array_merge($permisos, $permisos_global);

					$this->set('permisos_global', $permisos_todos);
                                        
                     /** -- FIN -- **/
				}
			}
			
			$faenas = $this->PermisoUsuario->find("all", array('fields' => array("faena_id"),'conditions' => array('usuario_id' => $this->Usuario->id, 'PermisoUsuario.e' => '1'), 'recursive' => -1));
			$faenas_permiso = array();
			foreach($faenas as $value) {
				$faenas_permiso[] = $value["PermisoUsuario"]["faena_id"];
			}
			
			$this->set('titulo', 'Usuario');
			$this->set('breadcrumb', 'Administración');
			$faenas = $this->Faena->find('list', array('conditions' => array('Faena.id' => $faenas_permiso), 'order' => array("Faena.nombre" => 'asc'), 'recursive' => -1));
			$this->set(compact('faenas'));

		}

		private function esContrasenaValida($password) {
			$errors = '';

			if( strpos($password, ' ') > 0) {
				$errors .= "La contraseña no puede contener espacios. ";
			}
			
			if (strlen($password) < 8) {
				$errors .= "La contraseña es muy corta. ";
			}

			if (strlen($password) > 16 ) {
				$errors .= "La contraseña supero el limite de 16 caracteres. ";
			}
		
			if (!ctype_alnum($password)) {
				$errors .= "La contraseña solo puede contener letras o numeros. ";
			}

			if (!preg_match("#[0-9]+#", $password)) {
				$errors .= "La contraseña debe poseer al menos un número. ";
			}
		
			if (!preg_match("#[a-zA-Z]+#", $password)) {
				$errors .= "La contraseña debe contener al menos una letra. ";
			}     


			$this->log($errors, "debug");
			if($errors != '') {
				$this->log("Error al guardar usuario: ".$errors, "debug");
				$this->Session->setFlash($errors,'guardar_error');
				$this->request->data["Usuario"] = $this->request->data;
				$this->set('data', $this->request->data);
				return false;
			}else {
				return true;
			}

				
		}
	}
?>