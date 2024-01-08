<?php
App::uses('ConnectionManager', 'Model'); 
/*
	Esta clase define la funcionalidad para cambiar de perfil y de faena
*/
class NivelController extends AppController {
	/*
		Este metodo define el cambio perfil dentro del sistema
	*/
	public function cambiar($id) {
		//$this->loadModel('NivelUsuario');
		/*$nivel = $this->Session->read('Nivel');
		$nivel = $this->NivelUsuario->find('all', array('conditions' => array('id' => $id)));
		$nivel = $nivel[0]['NivelUsuario'];*/
		$this->Session->write('Nivel', $id);
		$faena_id = $this->Session->read('faena_id');
		$usuario=$this->Session->read('Usuario');
		$this->loadModel('UsuarioFaena');
		$this->loadModel('Faena');
		$this->loadModel('Planificacion');
		$this->Session->write('Trabajos', "0");
		$admin = $this->Session->read('esAdmin');
		if ($id == 5 || $id == 4) {
			$this->Session->write('faena_id', '0');
			$faena_id = "0";
		}
		
		$faena = "Planificacion.faena_id = $faena_id";
		if ($faena_id == "0") {
			$faena = "Planificacion.faena_id <> $faena_id";
		}
		
		if($id!=4){
			$faenas = $this->UsuarioFaena->find('all', array(
				'fields'=>array('Faena.*'),
				'conditions' => "usuario_id={$usuario["id"]} AND nivel_id=$id AND UsuarioFaena.e='1' AND Faena.e = '1'",
				'order' => "Faena.nombre ASC",
				'recursive' => 1
			));
			if(count($faenas)==0){
				//throw new Exception("El usuario ingresado no tiene asignada ninguna faena."); 
			}elseif(count($faenas)==1){
				$this->Session->write('faena_id', $faenas[0]["Faena"]["id"]);
				$faena_id=$faenas[0]["Faena"]["id"];
			}else{
				$this->Session->write('faena_id', 0);
			}
		}else{
			$faenas = $this->Faena->find('all', array('fields'=>array('Faena.*'),'conditions' => "Faena.e = '1'",'recursive' => -1,'order' => "Faena.nombre ASC"));
		}
		
		if($admin=='1'){
			$faenas = $this->Faena->find('all', array('fields'=>array('Faena.*'),'conditions' => "Faena.e = '1'",'recursive' => -1,'order' => "Faena.nombre ASC"));
		}
		
		$this->Session->write('faenas',$faenas);
		
		if ($id==2||$id=7) {
			//$trabajos = $this->Planificacion->find('all', array('conditions' => array('estado' => array(2, 6, 7), 'Planificacion.faena_id' => $faena_id)));
			$trabajos = $this->Planificacion->find('count', array(
													//'fields' => array('Planificacion.estado', 'Planificacion.faena_id', 'Planificacion.fecha', 'Planificacion.faena_id', 'Planificacion.unidad_id', 'Planificacion.flota_id'),
													'conditions' => array(
													'estado' => array(6, 7),
													$faena,
													"not" => array("Planificacion.fecha" => null),
													"not" => array("Planificacion.faena_id" => null),
													"not" => array("Planificacion.correlativo" => null),
													"not" => array("Planificacion.unidad_id" => null),
													"not" => array("Planificacion.flota_id" => null)),
								'recursive' => -1));
			$this->Session->write('Trabajos',$trabajos);
		}
		
		if ($id == 3) {
			$trabajos = $this->Planificacion->find('count', array(
													//'fields' => array('Planificacion.estado', 'Planificacion.faena_id', 'Planificacion.fecha', 'Planificacion.faena_id', 'Planificacion.unidad_id', 'Planificacion.flota_id'),
													'conditions' => array(
													'estado' => array(4),
													$faena,
													"not" => array("Planificacion.fecha" => null),
													"not" => array("Planificacion.faena_id" => null),
													"not" => array("Planificacion.correlativo" => null),
													"not" => array("Planificacion.unidad_id" => null),
													"not" => array("Planificacion.flota_id" => null)),
								'recursive' => -1));
			$this->Session->write('Trabajos',$trabajos);
		}
				
		$this->redirect('/Principal/');
	}
	/*
		Este metodo define el cambio de faena cuando un usuario tiene acceso a mas de una
	*/
	public function faena($id) {
		$faena_id = $this->Session->read('faena_id');
		//if ($this->Session->read('CambiaPerfil') && $faena_id != intval($id)) {
		if ($faena_id != intval($id)) {
			$faena_id = intval($id);
			$this->loadModel('Faena');
			$this->loadModel('Planificacion');
			$resultado = $this->Faena->find('first', array(
				'conditions' => array('id' => $faena_id, "Faena.e = '1'"),
				'recursive' => -1
			));
			if (count($resultado) > 0) {
				$this->Session->write('faena', $resultado['Faena']['nombre']);
				$this->Session->write('faena_id', $faena_id);
			} elseif ($faena_id == 0) {
				$this->Session->write('faena', "");
				$this->Session->write('faena_id', "0");
			}
			$faena = "Planificacion.faena_id = $faena_id";
			if ($faena_id == "0") {
				$faena = "Planificacion.faena_id <> $faena_id";
			}
			$trabajos = $this->Planificacion->find('count', array(
													'conditions' => array(
													'estado' => array(6, 7),
													$faena,
													"not" => array("Planificacion.fecha" => null),
													"not" => array("Planificacion.faena_id" => null),
													"not" => array("Planificacion.correlativo" => null),
													"not" => array("Planificacion.unidad_id" => null),
													"not" => array("Planificacion.flota_id" => null)),
								'recursive' => -1));
			$this->Session->write('Trabajos',$trabajos);
		}
		
		$this->redirect($_SERVER["HTTP_REFERER"]);
	}
}
?>