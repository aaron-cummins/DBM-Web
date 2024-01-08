<?php
	App::uses('ConnectionManager', 'Model'); 
	class CuadroMandoController extends AppController {
		public function index(){
			$this->layout = 'metronic_principal';
			$this->check_permissions($this);
			$this->loadModel('NivelUsuario');
			$this->loadModel('Vista');
			$this->loadModel('PermisoGlobal');
			$this->loadModel('PermisoUsuario');
			$this->loadModel('PermisoPersonalizado');
			
			if ($this->request->is('post')){
				try {
					//$this->PermisoGlobal->deleteAll(array('PermisoGlobal.cargo_id IS NOT NULL'), false);
					$this->PermisoPersonalizado->deleteAll(array('PermisoPersonalizado.cargo_id IS NOT NULL'), false);
					
					$this->PermisoGlobal->updateAll(
						array('PermisoGlobal.e' => "'0'", 'PermisoGlobal.updated' => "'".date("Y-m-d H:i:s")."'"),
						array('PermisoGlobal.cargo_id NOT' => NULL)
					);
					
					$cargos1 = array();
					$cargos1[] = "-1";
					$cargos1[] = "-2";
					$cargos2 = array();
					$cargos2[] = "-1";
					$cargos2[] = "-2";
					$cargos3 = array();
					$cargos3[] = "-1";
					$cargos3[] = "-2";
					$vistas = array();
					$vistas[] = "-1";
					$vistas[] = "-2";
					$roles = array();
					$roles[] = "-1";
					$roles[] = "-2";
					$correos = array();
					$correos[] = "-1";
					$correos[] = "-2";
					$this->logger($this, "Actualizacion cuadro de mando");
					if(isset($this->request->data["vista"])){
						foreach($this->request->data["vista"] as $key => $value) {
							foreach($value as $key2 => $value2) {
								$this->logger($this, "cargo_id $key vista_id $key2");
								$permiso = $this->PermisoGlobal->find("first",array('conditions' => array('cargo_id' => $key, 'vista_id' => $key2), 'recursive' => -1));
								//print_r($permiso);
								$cargos1[] = $key;
								$vistas[] = $key2;
								if(!isset($permiso["PermisoGlobal"]["id"])){
									$this->logger($this, "Vista no existe, se crea");
									$data = array();
									$data["cargo_id"] = $key;
									$data["vista_id"] = $key2;
									$this->PermisoGlobal->create();
									$this->PermisoGlobal->save($data);
								} else {
									$this->logger($this, "Vista existe");
									if ($permiso["PermisoGlobal"]["e"] == '0'){
										$this->logger($this, "Vista estaba desactivada, se activa.");
										$this->PermisoGlobal->updateAll(
											array('PermisoGlobal.e' => "'1'", 'PermisoGlobal.updated' => "'".date("Y-m-d H:i:s")."'"),
											array('PermisoGlobal.id' => $permiso["PermisoGlobal"]["id"])
										);
									}
								}
							}
						}
					}
					
					if(isset($this->request->data["rol"])){
						foreach($this->request->data["rol"] as $key => $value) {
							foreach($value as $key2 => $value2) {
								$permiso = $this->PermisoGlobal->find("first",array('conditions' => array('cargo_id' => $key, 'rol_id' => $key2), 'recursive' => -1));
								$cargos2[] = $key;
								$roles[] = $key2;
								if(!isset($permiso["PermisoGlobal"]["id"])){
									$data = array();
									$data["cargo_id"] = $key;
									$data["rol_id"] = $key2;
									$this->PermisoGlobal->create();
									$this->PermisoGlobal->save($data);
								} else {
									if ($permiso["PermisoGlobal"]["e"] == '0'){
										$this->PermisoGlobal->updateAll(
											array('PermisoGlobal.e' => "'1'", 'PermisoGlobal.updated' => "'".date("Y-m-d H:i:s")."'"),
											array('PermisoGlobal.id' => $permiso["PermisoGlobal"]["id"])
										);
									}
								}
							}
						}
					}
					
					if(isset($this->request->data["correo"])){
						foreach($this->request->data["correo"] as $key => $value) {
							foreach($value as $key2 => $value2) {
								$permiso = $this->PermisoGlobal->find("first",array('conditions' => array('cargo_id' => $key, 'correo_id' => $key2), 'recursive' => -1));
								$cargos3[] = $key;
								$correos[] = $key2;
								if(!isset($permiso["PermisoGlobal"]["id"])){
									$data = array();
									$data["cargo_id"] = $key;
									$data["correo_id"] = $key2;
									$this->PermisoGlobal->create();
									$this->PermisoGlobal->save($data);
								} else {
									if ($permiso["PermisoGlobal"]["e"] == '0'){
										$this->PermisoGlobal->updateAll(
											array('PermisoGlobal.e' => "'1'", 'PermisoGlobal.updated' => "'".date("Y-m-d H:i:s")."'"),
											array('PermisoGlobal.id' => $permiso["PermisoGlobal"]["id"])
										);
									}
								}
							}
						}
					}
					/*
					$this->PermisoGlobal->updateAll(
						array('PermisoGlobal.e' => "'0'", 'PermisoGlobal.updated' => "'".date("Y-m-d H:i:s")."'"),
						array('PermisoGlobal.cargo_id NOT IN' => $cargos2, 'PermisoGlobal.rol_id NOT IN' => $roles, "PermisoGlobal.rol_id NOT" => null)
					);
					
					$this->PermisoGlobal->updateAll(
						array('PermisoGlobal.e' => "'0'", 'PermisoGlobal.updated' => "'".date("Y-m-d H:i:s")."'"),
						array('PermisoGlobal.cargo_id NOT IN' => $cargos3, 'PermisoGlobal.correo_id NOT IN' => $correos, "PermisoGlobal.correo_id NOT" => null)
					);
					*/
					// Actualizar todos los usuarios activos por cambio de permisos globales!
					// Alerta: Actualización grande, resincronizará todos los usuarios.
					//$date = date("Y-m-d H:i:s");
					//$this->Usuario->updateAll(
					//	array('Usuario.updated' => "'$date'"),
					//   array('Usuario.e' => "'1'")
					//);

					$this->recargarPermisos();
				} catch(Exception $e) {
					$this->Session->setFlash('Ocurrió un error al intentar actualizar el cuadro de mando, intente nuevamente.','guardar_error');
					$this->logger($this, $e->getMessage());
				}
			}
			
			$permisos = $this->PermisoGlobal->find('all', array('conditions' => array('PermisoGlobal.e' => '1'), 'recursive' => -1));
			$this->set('permisos', $permisos);
			
			$cargos = $this->NivelUsuario->find('list', array('conditions' => array('NivelUsuario.e' => '1'), 'order' => array("NivelUsuario.nombre" => 'asc'), 'recursive' => -1));
			$this->set(compact('cargos'));
			
			$vistas = $this->Vista->find('list', array('conditions' => array('Vista.e' => '1'), 'order' => array("Vista.nombre" => 'asc'), 'recursive' => -1));
			$this->set(compact('vistas'));
			$this->set('titulo', 'Cuadro de mando');
			$this->set('breadcrumb', 'Administración');
		}
	}
?>