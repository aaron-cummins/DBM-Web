<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Vendor', 'aws/sdk.class');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array('AWSSES','Session');

	//public function appError($method, $messages) {
	//	die('Application error: called handler method ' . $method);
	//}
	
	public function check_permissions($class) {
		$this->loadModel('Vista');
		$this->loadModel('Usuario');
		$this->loadModel('PermisoGlobal');
		$this->loadModel('PermisoUsuario'); 
		$this->loadModel('PermisoPersonalizado');
		
		$this->logger($class, "Acceso");
		
		debug($this->request->clientIp());
		$session_id = $this->Session->id();
		$faena_session = $this->Session->read('faena_id');
		$usuario_id = $this->Session->read('usuario_id');
		$cargos = $this->Session->read("PermisosCargos");
		$controller = $class->name;
		$action = $class->action;
		
		if($this->request->clientIp() != '163.247.42.100' && $this->request->clientIp() != '190.164.185.168'){
			//$this->response->statusCode(403);
			//exit; 
		}
		
		// ORYX 163.247.42.100
		// SSL 186.9.134.184
		
		debug($this->Session->read("PermisosFaenas"));
		debug($this->Session->read("NombresFaenas"));
		debug($this->Session->read("PermisosCargos"));
		debug($this->Session->read("faena_id"));
		debug($this->Session->read("usuario_id"));

		
		
		if($this->Session->read('usuario_id') == null || $this->Session->read('usuario_id') == '' || !is_numeric($this->Session->read('usuario_id'))){
			$this->Session->setFlash('Por seguridad ingrese nuevamente al sistema','guardar_error');
			$this->redirect(array('controller' => 'Login', 'action' => 'Index'));
			return;
			//$this->redirect(array('controller' => 'Login', 'action' => 'Index'));
			//debug("El usuario que está navengado el sistema no está logueado");
			//return;
		}
		
		if($this->Session->read('PermisosCargos') == null || count($this->Session->read('PermisosCargos')) == 0){
			$this->Session->setFlash('Por seguridad ingrese nuevamente al sistema','guardar_error');
			$this->redirect(array('controller' => 'Login', 'action' => 'Index'));
			return;
			return;
			//$this->redirect(array('controller' => 'Login', 'action' => 'Index'));
			//debug("El usuario que está navengado el sistema no tiene ningun cargo asignado");
			//return;
		}
		
		/* Se verifica si esta la session iniciada en otro equipo, si es asi se cierra session */
		$usuario = $this->Usuario->find('first', array(
			'fields' => array('Usuario.id','Usuario.current_login'),
			'conditions' => array("Usuario.id" => $usuario_id, 'Usuario.current_login' => $session_id, 'Usuario.e' => '1'),
			'recursive' => -1
		));
		
		if (isset($usuario) && isset($usuario["Usuario"]) && isset($usuario["Usuario"]["id"])) {
		} else {
			$this->Session->setFlash('Se detectó que ha iniciado sesión en otro equipo, por lo que su sesión en este equipo ha sido terminada.','guardar_error');
			$this->redirect(array('controller' => 'Login', 'action' => 'Index'));
			return;
		}
		
		//debug("faena: ".$faena_id);
		//debug("usuario: ".$usuario_id);
		//debug($cargos);
		
		$vista_todos = null;
		$vista_todos = $this->Vista->find('first', array(
			'fields' => array('Vista.id'),
			'conditions' => array("LOWER(Vista.controller)" => "*", 'LOWER(Vista.action)' => "*"),
			'recursive' => -1
		));
		if(isset($vista_todos) && isset($vista_todos["Vista"]["id"])) {
			$vista_todos = $vista_todos["Vista"]["id"];
			//debug("La vista $controller/$action está definida en los permisos.");
			//print_r($vista_todos);
		}else{
			$this->render('/Permiso/Vista');
			//debug("La vista $controller/$action no está definida en los permisos.");
			return;
		}
		
		$vista_id = null;
		$vista = $this->Vista->find('first', array(
			'fields' => array('Vista.id'),
			'conditions' => array("LOWER(Vista.controller)" => strtolower($controller), 'LOWER(Vista.action) LIKE' => "%".strtolower($action)."%"),
			'recursive' => -1
		));
		 
		if(isset($vista) && isset($vista["Vista"]["id"])) {
			$vista_id = $vista["Vista"]["id"];
			//debug("La vista $controller/$action está definida en los permisos.");
		}else{
			$vista_id = -1;
			//$this->render('/Permiso/Vista');
			//debug("La vista $controller/$action no está definida en los permisos.");
			//return;
		}
		
		if(!isset($cargos[$faena_session]) || !isset($cargos[$faena_session][0]) || !is_numeric($cargos[$faena_session][0])) {
			$this->render('/Permiso/Cargo');
			//die;
			//debug("El usuario: $usuario_id no tiene cargo definido para la faena: $faena_id");
			return;
		}
		
		
		
		$cargos[$faena_session][] = -1;
		$acceso_a_todo = false;
		if($vista_todos != null) {
			$permisos = $this->PermisoPersonalizado->find('all', array(
				'fields' => array('PermisoPersonalizado.id'),
				'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session, "Cargo.e" => '1', "Faena.e" => '1'),
				'recursive' => 1
			));
			debug($permisos);
			if(isset($permisos) && isset($permisos[0]["PermisoPersonalizado"])) {
				
				$permiso = $this->PermisoPersonalizado->find('first', array(
					'fields' => array('PermisoPersonalizado.id'),
					'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.vista_id" => $vista_todos, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session, "Cargo.e" => '1', "Faena.e" => '1'),
					'recursive' => 1
				));
				
				if(isset($permiso) && isset($permiso["PermisoPersonalizado"]["id"])) {
					$acceso_a_todo = true;
				} else {				
					$acceso_a_todo = false;
				}
			} else {
				$permiso = $this->PermisoGlobal->find('first', array(
					'fields' => array('PermisoGlobal.id'),
					'conditions' => array("PermisoGlobal.cargo_id IN" => $cargos[$faena_session], "PermisoGlobal.vista_id" => $vista_todos, 'PermisoGlobal.e' => '1', "Cargo.e" => '1'),
					'recursive' => 1
				));
				if(isset($permiso) && isset($permiso["PermisoGlobal"]["id"])) {
					$acceso_a_todo = true;
				} else {				
					$acceso_a_todo = false;
				}
			}
		}
		
		if($vista_id != null && $acceso_a_todo == false) {
			$permisos = $this->PermisoPersonalizado->find('all', array(
				'fields' => array('PermisoPersonalizado.id'),
				'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session, "Cargo.e" => '1', "Faena.e" => '1'),
				'recursive' => 1
			));
			debug($permisos); 
			if(isset($permisos) && isset($permisos[0]["PermisoPersonalizado"])) {
				$permiso = $this->PermisoPersonalizado->find('first', array(
					'fields' => array('PermisoPersonalizado.id'),
					'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.vista_id" => $vista_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session, "Cargo.e" => '1', "Faena.e" => '1'),
					'recursive' => 1
				));
				if(isset($permiso) && isset($permiso["PermisoPersonalizado"]["id"])) {
					//debug("El usuario: $usuario_id tiene permisos personalizados en faena: $faena_id para acceder a $controller/$action.");
					//return;
				} else {				 
					$this->render('/Permiso/Acceso');
					return;
				}
			} else {
				$permiso = $this->PermisoGlobal->find('first', array(
					'fields' => array('PermisoGlobal.id'),
					'conditions' => array("PermisoGlobal.cargo_id IN" => $cargos[$faena_session], "PermisoGlobal.vista_id" => $vista_id, 'PermisoGlobal.e' => '1', "Cargo.e" => '1'),
					'recursive' => 1
				));
				if(isset($permiso) && isset($permiso["PermisoGlobal"]["id"])) {
					//debug("El usuario: $usuario_id tiene permisos globales en faena: $faena_id para acceder a $controller/$action.");
					//return;
				} else {				
					$this->render('/Permiso/Acceso');
					return;
				}
			}
		}
		
		//print_r("<pre>");
		// Carga de faenas habilitadas para filtrar
		// Faena 20 y 1
		// No tiene que salir faena 3
		
		$faenas_personalizadas = array();
		$faenas_filtro = array();
		
		$faenas_personalizadas[] = -1;
		$faenas_personalizadas[] = -2;
		$faenas_filtro[] = -1;
		$faenas_filtro[] = -2;
		
		$permisos_personalizados = $this->PermisoPersonalizado->find('all', array(
			'fields' => array('PermisoPersonalizado.faena_id', 'PermisoPersonalizado.usuario_id','PermisoPersonalizado.cargo_id','PermisoPersonalizado.vista_id'),
			'conditions' => array("PermisoPersonalizado.usuario_id" => $usuario_id, "Cargo.e" => '1', "Faena.e" => '1'),
			'recursive' => 1
		));
		
		//print_r($permisos_personalizados);
		
		foreach($permisos_personalizados as $key => $value){
			$faenas_personalizadas[] = $value["PermisoPersonalizado"]["faena_id"];
			if ($vista_id == $value["PermisoPersonalizado"]["vista_id"]) {
				$faenas_filtro[] = $value["PermisoPersonalizado"]["faena_id"];
			}
		}
		
		//print_r($faenas_personalizadas);
		//print_r($faenas_filtro);
		
		$permisos_usuarios = $this->PermisoUsuario->find('all', array(
			'fields' => array('PermisoUsuario.faena_id','PermisoUsuario.usuario_id','PermisoUsuario.cargo_id'),
			'conditions' => array("PermisoUsuario.usuario_id" => $usuario_id, 'PermisoUsuario.faena_id NOT IN' => $faenas_personalizadas, 'PermisoUsuario.e' => '1', "Cargo.e" => '1', "Faena.e" => '1'),
			'recursive' => 1
		));
		
		//print_r($permisos_usuarios);
		
		foreach($permisos_usuarios as $key => $value){
			$permisos_globales = $this->PermisoGlobal->find('first', array(
				'fields' => array('PermisoGlobal.cargo_id','PermisoGlobal.vista_id'),
				'conditions' => array("PermisoGlobal.vista_id" => $vista_id, 'PermisoGlobal.cargo_id' => $value["PermisoUsuario"]["cargo_id"], 'PermisoGlobal.e' => '1', "Cargo.e" => '1'),
				'recursive' => 1
			));
			if(isset($permisos_globales["PermisoGlobal"]["vista_id"])){
				$faenas_filtro[] = $value["PermisoUsuario"]["faena_id"];
			}
		}
		
		//print_r($faenas_filtro);
		$this->Session->write("FaenasFiltro", $faenas_filtro);
	}
	
	public function recargarPermisos(){
		$this->loadModel('Vista');
		$this->loadModel('Usuario');
		$this->loadModel('PermisoGlobal');
		$this->loadModel('PermisoUsuario'); 
		$this->loadModel('PermisoPersonalizado');
		$correo = $this->Session->read('user_email');
		$usuario = $this->Usuario->find('first', array(
			'fields' => array('Usuario.*'),
			'conditions' => array("correo_electronico" => $correo),
			'recursive' => -1
		)); 
		
		if (isset($usuario["Usuario"]) && isset($usuario["Usuario"]["id"])){
			$usuario_id = $usuario["Usuario"]["id"];
			$faenas = array();
			$faenas_nombres = array();
			$cargos = array();
			
			$permisos_usuarios = $this->PermisoUsuario->find('all', array(
				'fields' => array('PermisoUsuario.usuario_id', 'PermisoUsuario.faena_id', 'PermisoUsuario.cargo_id', 'Faena.nombre'),
				'conditions' => array("PermisoUsuario.usuario_id" => $usuario_id, 'PermisoUsuario.cargo_id NOT' => NULL, 'PermisoUsuario.e' => '1', "Cargo.e" => '1', "Faena.e" => '1'), 
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
			
			$this->Session->write("PermisosFaenas", $faenas);
			$this->Session->write("NombresFaenas", $faenas_nombres);
			$this->Session->write("PermisosCargos", $cargos);
			$this->Session->write("usuario_id", $usuario_id);
		}
	}
	
	public function enviarAlertaCorreo($nivel, $asunto, $mensaje){
		if($nivel == 1){
			// Alerta a administradores
			// se obtienen todos los correos de usuarios con perfil personalizado
			$destinatarios = array();
			$destinatarios[] = "daniel.fuentes.b@gmail.com";
			$destinatarios[] = "daniel@salmonsoftware.cl";
			$html="<html>";
			$html.="<body>";
			$html.= "<table width=\"90%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
			$html.= "<tr style=\"background-color: red; color: white;\">";
			$html.= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">ALERTA DBM</td>";
			$html.= "</tr>";
			$html.= "</table>";	
			$html.= "<table width=\"90%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
			$html.= "<tr style=\"background-color: #808080; color: white;\">";
			$html.= "<td style=\"background-color: #808080; color: white; text-align: center;\" colspan=\"2\"><p>".$mensaje."</p></td>";
			$html.= "</tr>";
			$html.= "</table>";	
			$html.="</body>";
			$html.="</html>";
			

			$this->AWSSES->to = $destinatarios;
			$this->AWSSES->from = 'alerta@zeke.cl';
			$this->AWSSES->send('Alerta DBM / ' . $asunto,$html);
		}
	}
	
	public function getUsuarioID(){
		return $this->Session->read("usuario_id");
	}
	
	public function getGoogleJsonFile(){
		return $this->google_archivo_json;
	}
	
	public function getGoogleIDCliente(){
		return $this->google_id_cliente;
	}
	
	public function getGoogleSecretoCliente(){
		return $this->google_secreto_cliente;
	}
	
	public function logger($class, $mensaje){
		$this->loadModel('Log');
		$usuario_id = $this->Session->read('usuario_id');
		try {
			$data = array();
			$data["controller"] = $class->name;
			$data["action"] = $class->action;
			$data["here"] = $this->request->here;
			$data["method"] = $this->request->method();
			$data["base"] = $this->request->base;
			$data["query"] = http_build_query($this->request->query);
			$data["post"] = http_build_query($this->request->data);
			$data["usuario_id"] = $usuario_id;
			$data["mensaje"] = $mensaje;
			$data["fecha"] = date("Y-m-d H:i:s");
			$this->Log->create();
			$this->Log->save($data);
		} catch(Exception $e) {
			$controller = $class->name;
			$action = $class->action;
			$log_file = "/var/www/html/log/dbm-".date("Y-m-d").".log";
			$log = fopen($log_file, "a");
			fwrite($log, date("Y-m-d h:i:s A") ."\t".$controller."\t".$action."\tUID:".$usuario_id."\t".$mensaje."\t".($e->getMessage()));
			fclose($log);
		}	
	}

    public function intervencionLogger($planificacion, $seccion, $datos){
        $this->loadModel('LogIntervencion');
        $usuario_id = $this->Session->read('usuario_id');
        try{
            $data = array();
            $data["id_intervencion"] = $planificacion['Planificacion']['id'];
            $data["folio"] = $planificacion['Planificacion']['folio'];
            $data["correlativo"] = $planificacion['Planificacion']['correlativo_final'];
            $data["fecha"] = date("Y-m-d H:i:s");
            $data["usuario_id"] = $usuario_id;
            $data["seccion"] = $seccion;
            $data["datos"] = http_build_query($datos);

            $this->LogIntervencion->create();
            $this->LogIntervencion->save($data);
        } catch(Exception $e) {
            $controller = "LogIntervencion";
            $action = "logger";
            $log_file = "/var/www/html/log/error.log";
            $log = fopen($log_file, "a");
            fwrite($log, date("Y-m-d h:i:s A") ."\t".$controller."\t".$action."\tUID:".$usuario_id."\t Error al guardar el log de intervenciones, seccion: ". $seccion ."\t".($e->getMessage()));
            fclose($log);
        }
    }
}
