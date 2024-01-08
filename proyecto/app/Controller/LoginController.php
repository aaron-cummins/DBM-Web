<?php
App::uses('ConnectionManager', 'Model'); 
App::import('Controller', 'Utilidades');
require_once '../../vendor/autoload.php';

App::import('Lib','AzureADSimpleProvider');

/*
	Esta clase define el funcionamiento del ingreso de usuarios al sistema
*/
class LoginController extends AppController {
	/* Este metodo define la validacion de existencia de un usuario */
	public function oauth2callback(){
		$this->layout = null;
		$this->loadModel('Vista');
		$this->loadModel('Usuario');
		$this->loadModel('PermisoGlobal');
		$this->loadModel('PermisoUsuario'); 
		$this->loadModel('PermisoPersonalizado');		
	
		$azureCode = $this->request->query["code"];
		$azureState = $this->request->query["state"];
		$sessionState =$this->Session->read('oauth2state');
		$this->log($sessionState, 'debug');
		$this->log($azureState, 'debug');
		if(empty($azureCode)) {
			$this->Session->setFlash("Código no válido", 'guardar_error');
			$this->redirect('/Login');
			return;
		}

		if (empty($azureState) || $azureState !== $sessionState) {
			$this->Session->setFlash("Estado no válido", 'guardar_error');
			$this->redirect('/Login');
			return;
		}
		$azure = new AzureADSimpleProvider();
		$token = $azure->getAccessToken($azureCode);		
		
		$correo = $azure->getEmail($token);
		$correo = strtolower($correo);
		$this->Session->write('user_fullname', $azure->getUserFullname($token));
		$this->Session->write('user_email', $correo);
		$this->Session->write('google_image', '');
		$usuario = $this->Usuario->find('first', array(
			'fields' => array('Usuario.*'),
			'conditions' => array("LOWER(TRIM(correo_electronico))" => $correo),
			'recursive' => -1
		)); 
		if (isset($usuario["Usuario"]) && isset($usuario["Usuario"]["id"])){
			if (isset($usuario["Usuario"]["e"]) && $usuario["Usuario"]["e"] != '1'){
				$this->Session->setFlash("El usuario asociado al correo electrónico $correo no está activo.", 'guardar_error');
				$this->redirect('/Login');
				return;
			}
			
			$usuario_id = $usuario["Usuario"]["id"];
			$faenas = array();
			$faenas_nombres = array();
			$cargos = array();
			
			$permisos_usuarios = $this->PermisoUsuario->find('all', array(
				'fields' => array('PermisoUsuario.usuario_id', 'PermisoUsuario.faena_id', 'PermisoUsuario.cargo_id', 'Faena.nombre'),
				'conditions' => array("PermisoUsuario.usuario_id" => $usuario_id, 'PermisoUsuario.cargo_id NOT' => NULL, 'PermisoUsuario.e' => '1', "Cargo.e" => '1', 'Faena.e' => '1'), 
				'recursive' => 1
			));
			
			foreach($permisos_usuarios as $permiso) {
				$faenas[] = $permiso["PermisoUsuario"]["faena_id"];
				$faenas_nombres[$permiso["PermisoUsuario"]["faena_id"]] = $permiso["Faena"]["nombre"];
				$cargos[$permiso["PermisoUsuario"]["faena_id"]][] = $permiso["PermisoUsuario"]["cargo_id"];
			}
			
			$faenas = array_values(array_unique($faenas));
			$faenas[] = -1;
			if(count($faenas) == 1) {
				$this->Session->setFlash("El usuario ingresado no tiene faenas asignadas.", 'guardar_error');
				$this->redirect('/Login');
				return;
			}
			
			$session_id = $this->Session->id();
			
			$this->Usuario->updateAll(
			    array('Usuario.current_login' => "'$session_id'"),
			    array('Usuario.id' => $usuario_id)
			);
			
			$this->Session->write("PermisosFaenas", $faenas);
			$this->Session->write("NombresFaenas", $faenas_nombres);
			$this->Session->write("PermisosCargos", $cargos);
			$this->Session->write("faena_id", $faenas[0]);
			$this->Session->write("usuario_id", $usuario_id);
			
			$this->log("8","debug");
                        
                        if ($permisos_usuarios[0]["PermisoUsuario"]["cargo_id"] == 1){
                            $this->redirect('/Medicion');
                        }else{
                            $this->redirect('/Dashboard');
                        }
                        
			
		} else {
			$this->Session->setFlash("El correo electrónico $correo no está registrado en el sistema.", 'guardar_error');
			$this->redirect('/Login');
		}				
	}
	
	public function login(){
		$this->log("Se ingreso al login","debug");
		$azure = new AzureADSimpleProvider();
		$this->Session->write('oauth2state', $azure->getState());
		header('Location: ' . filter_var($azure->getAuthorizationUrl(), FILTER_SANITIZE_URL));
		exit;
	}
	
	public function index() {
		$this->log("Login");
		$this->layout = 'metronic_login';
 	}
	
	public function faena() {
		$db = ConnectionManager::getDataSource('default');
		$this->layout = 'login';
		$this->loadModel('Faena');
		$faena = $this->Faena->find('list', array('fields'=>array('id','nombre'), 'order' => 'nombre'));
		$this->set('faena', $faena);
		
		if ($this->request->data('faena_id')) {
			$this->Session->write('faena_id', $this->request->data('faena_id'));
			$faena_id = $this->request->data('faena_id');
			$this->loadModel('Faena');
			$resultado = $this->Faena->find('first', array(
				'conditions' => array('id' => $faena_id)
			));
			$this->Session->write('faena', $resultado['Faena']['nombre']);
			$this->redirect('/Principal/');
		}
	}
}
?>
