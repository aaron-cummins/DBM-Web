<?php
ini_set('memory_limit', '1024M');
App::uses('ConnectionManager', 'Model'); 
App::import('Controller', 'UtilidadesReporte');
App::import('Vendor', 'Classes/PHPExcel');
	
class MatricesController extends AppController {
	public function faenas($id="",$state=""){
		$this->set('titulo', 'Faenas');
		$this->loadModel('Faena');
		
		if (count($this->request->data)&&isset($this->request->data["nombre"])&&$this->request->data["nombre"]!="") {
			$data = array("nombre"=>$this->request->data["nombre"]);
			$this->Faena->create();
			$this->Faena->save($data);
		}
		
		if($id!=""&&is_numeric($id)&&($state=="1"||$state=="0")){
			$data=array('id'=>$id,'e'=>$state);
			$this->Faena->save($data);
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}
		$this->paginate = array(
			'limit' => 25,
			'order' => array('Faena.nombre' => 'asc')
		);
		$resultados = $this->paginate('Faena');
		$this->set('resultado', $resultados);
	}
	
	public function sintomas($id="",$state=""){
		$this->set('titulo', 'Sintomas');
		$this->loadModel('Sintoma');
		$this->loadModel('SintomaCategoria');
		
		$conditions="";
		$codigo="";
		$categoria_id="";
		$nombre="";
		$e="";
		if (count($this->request->data)&&isset($this->request->data["btnNuevo"])&&$this->request->data["btnNuevo"]!="") {
			$data = array("nombre"=>$this->request->data["nombre"]);
			$data["e"]="1";
			$data["codigo"]=$this->request->data["codigo"];
			$data["sintoma_categoria_id"]=$this->request->data["sintoma_categoria_id"];
			$this->Sintoma->create();
			$this->Sintoma->save($data);
		}
		
		if($id!=""&&is_numeric($id)&&($state=="1"||$state=="0")){
			$data=array('id'=>$id,'e'=>$state);
			$this->Sintoma->save($data);
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}
		
		if (count($this->request->query)&&isset($this->request->query["codigo"])&&$this->request->query["codigo"]!="") {
			$conditions.="Sintoma.codigo=".$this->request->query["codigo"]." AND ";
			$codigo=$this->request->query["codigo"];
		}
		
		if (count($this->request->query)&&isset($this->request->query["categoria_id"])&&$this->request->query["categoria_id"]!="") {
			$conditions.="Sintoma.sintoma_categoria_id=".$this->request->query["categoria_id"]." AND ";
			$categoria_id=$this->request->query["categoria_id"];
		}
		
		if (count($this->request->query)&&isset($this->request->query["nombre"])&&$this->request->query["nombre"]!="") {
			$conditions.="Sintoma.nombre ilike '%".$this->request->query["nombre"]."%' AND ";
			$nombre=$this->request->query["nombre"];
		}
		
		if (count($this->request->query)&&isset($this->request->query["e"])&&$this->request->query["e"]!="") {
			$conditions.="Sintoma.e='".$this->request->query["e"]."' AND ";
			$e=$this->request->query["e"];
		}
		
		$this->paginate = array(
			'limit' => 25,
			'fields'=>array("Sintoma.*","Categoria.nombre"),
			'order' => array('Categoria.nombre' => 'asc','Sintoma.nombre' => 'asc'),
			'conditions' => $conditions . " 1=1",
			'recursive'=>1
		);
		$resultados = $this->paginate('Sintoma');
		$this->set('resultado', $resultados);
		$this->set('codigo', $codigo);
		$this->set('e', $e);
		$this->set('categoria_id', $categoria_id);
		$this->set('nombre', $nombre);
		$this->set('categorias',$this->SintomaCategoria->find('all', array('order' => array('nombre' => 'asc'))));
	}
	
	
	public function equipos($up=""){
		$this->set('titulo', 'Equipos');
		$this->loadModel('Unidad');
		$this->loadModel('Motor');
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		$conditions="";
		$faena_id="";
		$flota_id="";
		$motor_id="";
		$e="";
		$nserie="";
		$esn="";
		$aplicacion="";
		$horometro="";
		$fabricante="";
		$modelo_motor="";
		$modelo_equipo="";
		$unidad="";
		if($up!=""){
			if (count($this->request->query)&&isset($this->request->query["reg"])&&is_array($this->request->query["reg"])) {
				if(isset($this->request->query["de"])){
					foreach($this->request->query["reg"] as $k=>$v){
						$data=array('id'=>$v,'e'=>'0');
						$this->Unidad->save($data);
					}
				}
				if(isset($this->request->query["ac"])){
					foreach($this->request->query["reg"] as $k=>$v){
						$data=array('id'=>$v,'e'=>'1');
						$this->Unidad->save($data);
					}
				}
			}
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}
		/*if($id!=""&&is_numeric($id)&&($state=="1"||$state=="0")){
			$data=array('id'=>$id,'e'=>$state);
			$this->Unidad->save($data);
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}*/
		if (count($this->request->query)&&isset($this->request->query["fid"])&&$this->request->query["fid"]!="") {
			$conditions.="Faena.id=".$this->request->query["fid"]." AND ";
			$faena_id=$this->request->query["fid"];
		}
		if (count($this->request->query)&&isset($this->request->query["mid"])&&$this->request->query["mid"]!="") {
			$conditions.="Motor.id=".$this->request->query["mid"]." AND ";
			$motor_id=$this->request->query["mid"];
		}
		if (count($this->request->query)&&isset($this->request->query["flid"])&&$this->request->query["flid"]!="") {
			$conditions.="Flota.id=".$this->request->query["flid"]." AND ";
			$flota_id=$this->request->query["flid"];
		}
		if (count($this->request->query)&&isset($this->request->query["e"])&&$this->request->query["e"]!="") {
			$conditions.="Unidad.e='".$this->request->query["e"]."' AND ";
			$e=$this->request->query["e"];
		}
		if (count($this->request->query)&&isset($this->request->query["nserie"])&&$this->request->query["nserie"]!="") {
			$conditions.="Unidad.nserie LIKE '%".$this->request->query["nserie"]."%' AND ";
			$nserie=$this->request->query["nserie"];
		}
		if (count($this->request->query)&&isset($this->request->query["esn"])&&$this->request->query["esn"]!="") {
			$conditions.="Unidad.esn LIKE '%".$this->request->query["esn"]."%' AND ";
			$esn=$this->request->query["esn"];
		}
		if (count($this->request->query)&&isset($this->request->query["aplicacion"])&&$this->request->query["aplicacion"]!="") {
			$conditions.="UPPER(Unidad.aplicacion)=UPPER('".$this->request->query["aplicacion"]."') AND ";
			$aplicacion=$this->request->query["aplicacion"];
		}
		if (count($this->request->query)&&isset($this->request->query["horometro"])&&$this->request->query["horometro"]!="") {
			$conditions.="  CAST(Unidad.horometro AS TEXT) LIKE '%".$this->request->query["horometro"]."%' AND ";
			$horometro=$this->request->query["horometro"];
		}
		if (count($this->request->query)&&isset($this->request->query["fabricante"])&&$this->request->query["fabricante"]!="") {
			$conditions.="Unidad.fabricante iLIKE '%".$this->request->query["fabricante"]."%' AND ";
			$fabricante=$this->request->query["fabricante"];
		}
		if (count($this->request->query)&&isset($this->request->query["modelo_motor"])&&$this->request->query["modelo_motor"]!="") {
			$conditions.="Unidad.modelo_motor iLIKE '%".$this->request->query["modelo_motor"]."%' AND ";
			$modelo_motor=$this->request->query["modelo_motor"];
		}
		if (count($this->request->query)&&isset($this->request->query["modelo_equipo"])&&$this->request->query["modelo_equipo"]!="") {
			$conditions.="Unidad.modelo_equipo iLIKE '%".$this->request->query["modelo_equipo"]."%' AND ";
			$modelo_equipo=$this->request->query["modelo_equipo"];
		}
		if (count($this->request->query)&&isset($this->request->query["unidad"])&&$this->request->query["unidad"]!="") {
			$conditions.="Unidad.unidad iLIKE '%".$this->request->query["unidad"]."%' AND ";
			$unidad=$this->request->query["unidad"];
		}
		
		$this->paginate = array(
			'limit' => 25,
			'conditions' => $conditions . " 1=1",
			'order' => array('Faena.nombre'=>'asc','Unidad.unidad' => 'asc'),
			'recursive' => 1
		);
		
		
		$motores = $this->Motor->find('all', array('order' => array('nombre' => 'asc'), 'recursive' => -1));
		$faenas = $this->Faena->find('all', array('order' => array('nombre' => 'asc'), 'recursive' => -1));
		$flotas = $this->Flota->find('all', array('order' => array('nombre' => 'asc'), 'recursive' => -1));
		$this->set('motor_id', $motor_id);
		$this->set('flota_id', $flota_id);
		$this->set('e', $e);
		$this->set('motor_id', $motor_id);
		$this->set('aplicacion', $aplicacion);
		$this->set('horometro', $horometro);
		$this->set('nserie', $nserie);
		$this->set('esn', $esn);
		$this->set('faena_id', $faena_id);
		$this->set('unidad', $unidad);
		$this->set('faenas', $faenas);
		$this->set('motores', $motores);
		$this->set('flotas', $flotas);
		$this->set('fabricante', $fabricante);
		$this->set('modelo_motor', $modelo_motor);
		$this->set('modelo_equipo', $modelo_equipo);
		$resultados = $this->paginate('Unidad');
		$this->set('resultado', $resultados);
	}
	
	public function flotas($id="",$state=""){
		$this->set('titulo', 'Flotas');
		$this->loadModel('Flota');
		
		if($id!=""&&is_numeric($id)&&($state=="1"||$state=="0")){
			$data=array('id'=>$id,'e'=>$state);
			$this->Flota->save($data);
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}
		$this->paginate = array(
			'limit' => 25,
			'order' => array('Flota.nombre'=>'asc'),
			'recursive'=>1
		);
		$resultados = $this->paginate('Flota');
		$this->set('resultado', $resultados);
	}
	
	public function soluciones($id="",$state=""){
		$this->set('titulo', 'Tabla Soluciones');
		$this->loadModel('Solucion');
		
		if($id!=""&&is_numeric($id)&&($state=="1"||$state=="0")){
			$data=array('id'=>$id,'e'=>$state);
			$this->Solucion->save($data);
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}
		
		if (count($this->request->data)&&isset($this->request->data["nombre"])&&$this->request->data["nombre"]!="") {
			$data = array("nombre"=>$this->request->data["nombre"]);
			$this->Solucion->create();
			$this->Solucion->save($data);
		}
		
		$this->paginate = array(
			'limit' => 25,
			'order' => array('Solucion.nombre'=>'asc'),
			'recursive'=>1
		);
		$resultados = $this->paginate('Solucion');
		$this->set('resultado', $resultados);
	}
	
	public function motores($id="",$state=""){
		$this->set('titulo', 'Tabla Motores');
		$this->loadModel('Motor');
		
		if($id!=""&&is_numeric($id)&&($state=="1"||$state=="0")){
			$data=array('id'=>$id,'e'=>$state);
			$this->Motor->save($data);
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}
		
		if (count($this->request->data)&&isset($this->request->data["nombre"])&&$this->request->data["nombre"]!="") {
			$data = array("nombre"=>$this->request->data["nombre"]);
			$this->Motor->create();
			$this->Motor->save($data);
		}
		
		$this->paginate = array(
			'limit' => 25,
			'order' => array('Motor.nombre'=>'asc'),
			'recursive'=>1
		);
		$resultados = $this->paginate('Motor');
		$this->set('resultado', $resultados);
	}
	
	public function sistemas($up=""){
		$this->set('titulo', 'Sistemas');
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Posiciones_Subsistema');
		$conditions="";
		$sistema_id="";
		$subsistema_id="";
		$motor_id="";
		$posicion_id="";
		$e="";
		
		if($up!=""){
			if (count($this->request->query)&&isset($this->request->query["reg"])&&is_array($this->request->query["reg"])) {
				if(isset($this->request->query["de"])){
					foreach($this->request->query["reg"] as $k=>$v){
						$data=array('id'=>$v,'e'=>'0','mtime'=>time());
						$this->MotorSistemaSubsistemaPosicion->save($data);
					}
				}
				if(isset($this->request->query["ac"])){
					foreach($this->request->query["reg"] as $k=>$v){
						$data=array('id'=>$v,'e'=>'1','mtime'=>time());
						$this->MotorSistemaSubsistemaPosicion->save($data);
					}
				}
			}
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}
		
		/*if($id!=""&&is_numeric($id)&&($state=="1"||$state=="0")){
			$data=array('id'=>$id,'e'=>$state);
			$this->MotorSistemaSubsistemaPosicion->save($data);
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}*/
		
		if (count($this->request->query)&&isset($this->request->query["mid"])&&$this->request->query["mid"]!="") {
			$conditions.="Motor.id=".$this->request->query["mid"]." AND ";
			$motor_id=$this->request->query["mid"];
		}
		if (count($this->request->query)&&isset($this->request->query["sid"])&&$this->request->query["sid"]!="") {
			$conditions.="Sistema.id=".$this->request->query["sid"]." AND ";
			$sistema_id=$this->request->query["sid"];
		}
		if (count($this->request->query)&&isset($this->request->query["suid"])&&$this->request->query["suid"]!="") {
			$conditions.="Subsistema.id=".$this->request->query["suid"]." AND ";
			$subsistema_id=$this->request->query["suid"];
		}
		if (count($this->request->query)&&isset($this->request->query["pid"])&&$this->request->query["pid"]!="") {
			$conditions.="Posicion.id=".$this->request->query["pid"]." AND ";
			$posicion_id=$this->request->query["pid"];
		}
		if (count($this->request->query)&&isset($this->request->query["e"])&&$this->request->query["e"]!="") {
			$conditions.="MotorSistemaSubsistemaPosicion.e='".$this->request->query["e"]."' AND ";
			$e=$this->request->query["e"];
		}
		$this->paginate = array(
			'limit' => 25,
			'conditions' => $conditions . " 1=1",
			'order' => array('Motor.nombre'=>'asc','Sistema.nombre'=>'asc','Subsistema.nombre'=>'asc','Posicion.nombre'=>'asc'),
			'recursive'=>1
		);
		$resultados = $this->paginate('MotorSistemaSubsistemaPosicion');
		$motores = $this->Motor->find('all', array('order' => array('nombre' => 'asc')));
		$sistemas = $this->Sistema->find('all', array('order' => array('nombre' => 'asc')));
		$subsistemas = $this->Subsistema->find('all', array('order' => array('nombre' => 'asc')));
		$posiciones = $this->Posiciones_Subsistema->find('all', array('order' => array('nombre' => 'asc')));
		$this->set('resultado', $resultados);
		$this->set('motor_id', $motor_id);
		$this->set('sistema_id', $sistema_id);
		$this->set('subsistema_id', $subsistema_id);
		$this->set('posiciones', $posiciones);
		$this->set('posicion_id', $posicion_id);
		$this->set('motores', $motores);
		$this->set('e', $e);
		$this->set('sistemas', $sistemas);
		$this->set('subsistemas', $subsistemas);
	}
	
	public function elementos($up=""){
		$this->set('titulo', 'Elementos');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Posiciones_Elemento');
		$this->loadModel('Elemento');
		$conditions="";
		$sistema_id="";
		$subsistema_id="";
		$motor_id="";
		$posicion_id="";
		$elemento_id="";
		$codigo="";
		$e="";
		if($up!=""){
			if (count($this->request->query)&&isset($this->request->query["reg"])&&is_array($this->request->query["reg"])) {
				if(isset($this->request->query["de"])){
					foreach($this->request->query["reg"] as $k=>$v){
						$data=array('id'=>$v,'e'=>'0','mtime'=>time());
						$this->Sistema_Subsistema_Motor_Elemento->save($data);
					}
				}
				if(isset($this->request->query["ac"])){
					foreach($this->request->query["reg"] as $k=>$v){
						$data=array('id'=>$v,'e'=>'1','mtime'=>time());
						$this->Sistema_Subsistema_Motor_Elemento->save($data);
					}
				}
			}
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}
		
		/*if($id!=""&&is_numeric($id)&&($state=="1"||$state=="0")){
			$data=array('id'=>$id,'e'=>$state);
			$this->Sistema_Subsistema_Motor_Elemento->save($data);
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}*/
		if (count($this->request->query)&&isset($this->request->query["mid"])&&$this->request->query["mid"]!="") {
			$conditions.="Motor.id=".$this->request->query["mid"]." AND ";
			$motor_id=$this->request->query["mid"];
		}
		if (count($this->request->query)&&isset($this->request->query["sid"])&&$this->request->query["sid"]!="") {
			$conditions.="Sistema.id=".$this->request->query["sid"]." AND ";
			$sistema_id=$this->request->query["sid"];
		}
		if (count($this->request->query)&&isset($this->request->query["suid"])&&$this->request->query["suid"]!="") {
			$conditions.="Subsistema.id=".$this->request->query["suid"]." AND ";
			$subsistema_id=$this->request->query["suid"];
		}
		if (count($this->request->query)&&isset($this->request->query["pid"])&&$this->request->query["pid"]!="") {
			$conditions.="Posicion.id=".$this->request->query["pid"]." AND ";
			$posicion_id=$this->request->query["pid"];
		}
		if (count($this->request->query)&&isset($this->request->query["eid"])&&$this->request->query["eid"]!="") {
			$conditions.="Elemento.id=".$this->request->query["eid"]." AND ";
			$elemento_id=$this->request->query["eid"];
		}
		if (count($this->request->query)&&isset($this->request->query["e"])&&$this->request->query["e"]!="") {
			$conditions.="Sistema_Subsistema_Motor_Elemento.e='".$this->request->query["e"]."' AND ";
			$e=$this->request->query["e"];
		}
		if (count($this->request->query)&&isset($this->request->query["codigo"])&&$this->request->query["codigo"]!="") {
			$conditions.="Sistema_Subsistema_Motor_Elemento.codigo ilike '%".$this->request->query["codigo"]."%' AND ";
			$codigo=$this->request->query["codigo"];
		}
		$this->paginate = array(
			'limit' => 25,
			'conditions' => $conditions . " 1=1",
			'order' => array('Motor.nombre'=>'asc','Sistema.nombre'=>'asc','Subsistema.nombre'=>'asc','Elemento.nombre'=>'asc'),
			'recursive'=>1
		);
		$resultados = $this->paginate('Sistema_Subsistema_Motor_Elemento');
		$motores = $this->Motor->find('all', array('order' => array('nombre' => 'asc')));
		$sistemas = $this->Sistema->find('all', array('order' => array('nombre' => 'asc')));
		$subsistemas = $this->Subsistema->find('all', array('order' => array('nombre' => 'asc')));
		$posiciones = $this->Posiciones_Elemento->find('all', array('order' => array('nombre' => 'asc')));
		$elementos = $this->Elemento->find('all', array('order' => array('nombre' => 'asc')));
		$this->set('resultado', $resultados);
		$this->set('motor_id', $motor_id);
		$this->set('sistema_id', $sistema_id);
		$this->set('subsistema_id', $subsistema_id);
		$this->set('elemento_id', $elemento_id);
		$this->set('posicion_id', $posicion_id);
		$this->set('posiciones', $posiciones);
		$this->set('motores', $motores);
		$this->set('e', $e);
		$this->set('codigo', $codigo);
		$this->set('elementos', $elementos);
		$this->set('sistemas', $sistemas);
		$this->set('subsistemas', $subsistemas);
	}
	
	
	public function pool($up=""){
		$this->set('titulo', 'Pool de Elementos');
		$this->loadModel('ElementoPool');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$conditions="";
		$sistema_id="";
		$subsistema_id="";
		$motor_id="";
		$elemento_id="";
		$codigo="";
		$e="";
		if($up!=""){
			if (count($this->request->query)&&isset($this->request->query["reg"])&&is_array($this->request->query["reg"])) {
				if(isset($this->request->query["de"])){
					foreach($this->request->query["reg"] as $k=>$v){
						$data=array('id'=>$v,'e'=>'0');
						$this->ElementoPool->save($data);
					}
				}
				if(isset($this->request->query["ac"])){
					foreach($this->request->query["reg"] as $k=>$v){
						$data=array('id'=>$v,'e'=>'1');
						$this->ElementoPool->save($data);
					}
				}
			}
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}
		
		/*if($id!=""&&is_numeric($id)&&($state=="1"||$state=="0")){
			$data=array('id'=>$id,'e'=>$state);
			$this->Sistema_Subsistema_Motor_Elemento->save($data);
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}*/
		if (count($this->request->query)&&isset($this->request->query["mid"])&&$this->request->query["mid"]!="") {
			$conditions.="Motor.id=".$this->request->query["mid"]." AND ";
			$motor_id=$this->request->query["mid"];
		}
		if (count($this->request->query)&&isset($this->request->query["sid"])&&$this->request->query["sid"]!="") {
			$conditions.="Sistema.id=".$this->request->query["sid"]." AND ";
			$sistema_id=$this->request->query["sid"];
		}
		if (count($this->request->query)&&isset($this->request->query["suid"])&&$this->request->query["suid"]!="") {
			$conditions.="Subsistema.id=".$this->request->query["suid"]." AND ";
			$subsistema_id=$this->request->query["suid"];
		}
		if (count($this->request->query)&&isset($this->request->query["eid"])&&$this->request->query["eid"]!="") {
			$conditions.="Elemento.id=".$this->request->query["eid"]." AND ";
			$elemento_id=$this->request->query["eid"];
		}
		if (count($this->request->query)&&isset($this->request->query["e"])&&$this->request->query["e"]!="") {
			$conditions.="ElementoPool.e='".$this->request->query["e"]."' AND ";
			$e=$this->request->query["e"];
		}
		if (count($this->request->query)&&isset($this->request->query["codigo"])&&$this->request->query["codigo"]!="") {
			$conditions.="ElementoPool.codigo ilike '%".$this->request->query["codigo"]."%' AND ";
			$codigo=$this->request->query["codigo"];
		}
		$this->paginate = array(
			'limit' => 25,
			'conditions' => $conditions . " 1=1",
			'order' => array('Motor.nombre'=>'asc','Sistema.nombre'=>'asc','Subsistema.nombre'=>'asc','Elemento.nombre'=>'asc'),
			'recursive'=>1
		);
		$resultados = $this->paginate('ElementoPool');
		$motores = $this->Motor->find('all', array('order' => array('nombre' => 'asc')));
		$sistemas = $this->Sistema->find('all', array('order' => array('nombre' => 'asc')));
		$subsistemas = $this->Subsistema->find('all', array('order' => array('nombre' => 'asc')));
		$elementos = $this->Elemento->find('all', array('order' => array('nombre' => 'asc')));
		$this->set('resultado', $resultados);
		$this->set('motor_id', $motor_id);
		$this->set('sistema_id', $sistema_id);
		$this->set('subsistema_id', $subsistema_id);
		$this->set('elemento_id', $elemento_id);
		$this->set('motores', $motores);
		$this->set('e', $e);
		$this->set('codigo', $codigo);
		$this->set('elementos', $elementos);
		$this->set('sistemas', $sistemas);
		$this->set('subsistemas', $subsistemas);
	}
	
	public function diagnosticos($up=""){
		$this->set('titulo', 'Diagnosticos');
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('Diagnostico');
		$conditions="";
		$sistema_id="";
		$subsistema_id="";
		$motor_id="";
		$elemento_id="";
		$did="";
		$codigo="";
		$e="";
		if($up!=""){
			if (count($this->request->query)&&isset($this->request->query["reg"])&&is_array($this->request->query["reg"])) {
				if(isset($this->request->query["de"])){
					foreach($this->request->query["reg"] as $k=>$v){
						$data=array('id'=>$v,'e'=>'0','mtime'=>time());
						$this->MotorSistemaSubsistemaElementoDiagnostico->save($data);
					}
				}
				if(isset($this->request->query["ac"])){
					foreach($this->request->query["reg"] as $k=>$v){
						$data=array('id'=>$v,'e'=>'1','mtime'=>time());
						$this->MotorSistemaSubsistemaElementoDiagnostico->save($data);
					}
				}
			}
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}
		
		/*if($id!=""&&is_numeric($id)&&($state=="1"||$state=="0")){
			$data=array('id'=>$id,'e'=>$state);
			$this->Sistema_Subsistema_Motor_Elemento->save($data);
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}*/
		if (count($this->request->query)&&isset($this->request->query["mid"])&&$this->request->query["mid"]!="") {
			$conditions.="Motor.id=".$this->request->query["mid"]." AND ";
			$motor_id=$this->request->query["mid"];
		}
		if (count($this->request->query)&&isset($this->request->query["sid"])&&$this->request->query["sid"]!="") {
			$conditions.="Sistema.id=".$this->request->query["sid"]." AND ";
			$sistema_id=$this->request->query["sid"];
		}
		if (count($this->request->query)&&isset($this->request->query["suid"])&&$this->request->query["suid"]!="") {
			$conditions.="Subsistema.id=".$this->request->query["suid"]." AND ";
			$subsistema_id=$this->request->query["suid"];
		}
		if (count($this->request->query)&&isset($this->request->query["eid"])&&$this->request->query["eid"]!="") {
			$conditions.="Elemento.id=".$this->request->query["eid"]." AND ";
			$elemento_id=$this->request->query["eid"];
		}
		if (count($this->request->query)&&isset($this->request->query["eid"])&&$this->request->query["eid"]!="") {
			$conditions.="Elemento.id=".$this->request->query["eid"]." AND ";
			$elemento_id=$this->request->query["eid"];
		}
		if (count($this->request->query)&&isset($this->request->query["did"])&&$this->request->query["did"]!="") {
			$conditions.="MotorSistemaSubsistemaElementoDiagnostico.diagnostico_id='".$this->request->query["did"]."' AND ";
			$did=$this->request->query["did"];
		}
		/*if (count($this->request->query)&&isset($this->request->query["codigo"])&&$this->request->query["codigo"]!="") {
			$conditions.="MotorSistemaSubsistemaElementoDiagnostico.codigo ilike '%".$this->request->query["codigo"]."%' AND ";
			$codigo=$this->request->query["codigo"];
		}*/
		$this->paginate = array(
			'limit' => 25,
			'conditions' => $conditions . " 1=1",
			'order' => array('Motor.nombre'=>'asc','Sistema.nombre'=>'asc','Subsistema.nombre'=>'asc','Elemento.nombre'=>'asc'),
			'recursive'=>1
		);
		$resultados = $this->paginate('MotorSistemaSubsistemaElementoDiagnostico');
		//$motores = $this->Motor->find('all', array('order' => array('nombre' => 'asc')));
		//$sistemas = $this->Sistema->find('all', array('order' => array('nombre' => 'asc')));
		//$subsistemas = $this->Subsistema->find('all', array('order' => array('nombre' => 'asc')));
		//$elementos = $this->Elemento->find('all', array('order' => array('nombre' => 'asc')));
		$this->set('resultado', $resultados);
		$this->set('motor_id', $motor_id);
		$this->set('sistema_id', $sistema_id);
		$this->set('subsistema_id', $subsistema_id);
		$this->set('elemento_id', $elemento_id);
		$this->set('diagnostico_id', $did);
		//$this->set('motores', $motores);
		$this->set('e', $e);
		$this->set('codigo', $codigo);
		//$this->set('elementos', $elementos);
		//$this->set('sistemas', $sistemas);
		//$this->set('subsistemas', $subsistemas);
		
		$this->set('motores', $this->Motor->find('all', array('order' => 'nombre','conditions'=>array("Motor.e='1'"), 'recursive' => -1)));
		$this->set('sistemas', $this->Sistema->find('all', array('order' => 'nombre','conditions'=>array("Sistema.e='1'"), 'recursive' => -1)));
		$this->set('subsistemas', $this->Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Subsistema.e='1'"), 'recursive' => -1)));
		$this->set('elementos', $this->Elemento->find('all', array('order' => 'nombre','conditions'=>array("Elemento.e='1'"), 'recursive' => -1)));
		$this->set('diagnosticos', $this->Diagnostico->find('all', array('order' => 'nombre','conditions'=>array("Diagnostico.e='1'"), 'recursive' => -1)));
	}
	
	public function motor_1(){
		$this->set('titulo', 'Diagnosticos');
		$this->layout = 'metronic_principal';
		//$this->check_permissions($this);
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('Diagnostico');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('Posiciones_Elemento');
		
		$limit = 100;
		$conditions="";
		$sistema_id="";
		$subsistema_id="";
		$motor_id="";
		$elemento_id="";
		$did="";
		$codigo="";
		$e="";
		$query_string = "";
		
		if ($this->request->is('post')){
			try {
				foreach($this->request->data['registro'] as $key => $value) {
					if ($value == '0') {
						if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
							// Estaba desactivado y se activo
							$id = explode("-", $key);
							$SistemaSubsistemaMotorElemento_id = $id[0];
							$MotorSistemaSubsistemaPosicion_id = $id[1];
							$MotorSistemaSubsistemaElementoDiagnostico_id = $id[2];
							
							$data = array();
							$data["id"] = $MotorSistemaSubsistemaElementoDiagnostico_id;
							$data["e"] = "1";
							$this->MotorSistemaSubsistemaElementoDiagnostico->save($data);
							
							$data = array();
							$data["id"] = $SistemaSubsistemaMotorElemento_id;
							$data["e"] = "1";
							$this->Sistema_Subsistema_Motor_Elemento->save($data);
							
							$data = array();
							$data["id"] = $MotorSistemaSubsistemaPosicion_id;
							$data["e"] = "1";
							$this->MotorSistemaSubsistemaPosicion->save($data);
						}
					}
					if ($value == '1') {
						if (isset($this->request->data['estado']) && !isset($this->request->data['estado'][$key])) {
							// Estaba activado y se desactivo
							$id = explode("-", $key);
							$SistemaSubsistemaMotorElemento_id = $id[0];
							$MotorSistemaSubsistemaPosicion_id = $id[1];
							$MotorSistemaSubsistemaElementoDiagnostico_id = $id[2];
							
							$data = array();
							$data["id"] = $MotorSistemaSubsistemaElementoDiagnostico_id;
							$data["e"] = "0";
							$this->MotorSistemaSubsistemaElementoDiagnostico->save($data);
							
							$data = array();
							$data["id"] = $SistemaSubsistemaMotorElemento_id;
							$data["e"] = "0";
							$this->Sistema_Subsistema_Motor_Elemento->save($data);
							
							$data = array();
							$data["id"] = $MotorSistemaSubsistemaPosicion_id;
							$data["e"] = "0";
							$this->MotorSistemaSubsistemaPosicion->save($data);
						}
					}
				}
			} catch(Exception $e) {
				$this->Session->setFlash('Ocurrió un error al intentar cambiar el estado de los registros, intente nuevamente. ' . $e->getMessage(),'guardar_error');
			}
		}
		
		$conditions = array();
		if ($this->request->is('get')){
			$query_string = http_build_query($this->request->query);
			if(isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
				$limit = $this->request->query['limit'];
			}
			if(isset($this->request->query['motor_id']) && is_numeric($this->request->query['motor_id'])) {
				$conditions["Motor.id"] = $this->request->query['motor_id'];
			}
			if(isset($this->request->query['sistema_id']) && is_numeric($this->request->query['sistema_id'])) {
				$conditions["Sistema.id"] = $this->request->query['sistema_id'];
			}
			if(isset($this->request->query['subsistema_id']) && is_numeric($this->request->query['subsistema_id'])) {
				$conditions["Subsistema.id"] = $this->request->query['subsistema_id'];
			}
			if(isset($this->request->query['posicion_subsistema_id']) && is_numeric($this->request->query['posicion_subsistema_id'])) {
				$conditions["Posicion_Subsistema.id"] = $this->request->query['posicion_subsistema_id'];
			}
			if(isset($this->request->query['codigo']) && trim($this->request->query['codigo']) != '') {
				$conditions["LOWER(SistemaSubsistemaMotorElemento.codigo) LIKE"] = strtolower($this->request->query['codigo']);
			}
			if(isset($this->request->query['elemento_id']) && is_numeric($this->request->query['elemento_id'])) {
				$conditions["Elemento.id"] = $this->request->query['elemento_id'];
			}
			if(isset($this->request->query['diagnostico_id']) && is_numeric($this->request->query['diagnostico_id'])) {
				$conditions["Diagnostico.id"] = $this->request->query['diagnostico_id'];
			}
			if(isset($this->request->query['posicion_elemento_id']) && is_numeric($this->request->query['posicion_elemento_id'])) {
				$conditions["Posicion_Elemento.id"] = $this->request->query['posicion_elemento_id'];
			}
			if(isset($this->request->query['pool']) && is_numeric($this->request->query['pool'])) {
				$conditions["SistemaSubsistemaMotorElemento.pool"] = $this->request->query['pool'];
			}
			if(isset($this->request->query['estado']) && is_numeric($this->request->query['estado'])) {
				if($this->request->query['estado'] == '1') {
					$conditions["SistemaSubsistemaMotorElemento.e"] = '1';
					$conditions["MotorSistemaSubsistemaPosicion.e"] = '1';
					$conditions["MotorSistemaSubsistemaElementoDiagnostico.e"] = '1';
				}elseif($this->request->query['estado'] == '0') {
					$conditions["OR"] = array('SistemaSubsistemaMotorElemento.e' => '0', 'MotorSistemaSubsistemaPosicion.e' => '0', 'MotorSistemaSubsistemaElementoDiagnostico.e' => '0');
				}
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		$this->paginate = array(
			'fields' => array('MotorSistemaSubsistemaElementoDiagnostico.id','SistemaSubsistemaMotorElemento.id','MotorSistemaSubsistemaPosicion.id','SistemaSubsistemaMotorElemento.codigo','Motor.nombre','Sistema.nombre','Subsistema.nombre','Elemento.nombre','Diagnostico.nombre','Posicion_Subsistema.nombre','Posicion_Elemento.nombre','SistemaSubsistemaMotorElemento.pool','SistemaSubsistemaMotorElemento.e','MotorSistemaSubsistemaPosicion.e','MotorSistemaSubsistemaElementoDiagnostico.e','Motor.id','Sistema.id','Subsistema.id'),
			'limit' => $limit,
			'joins' => array(
		        array(
		            'alias' => 'SistemaSubsistemaMotorElemento',
		            'table' => 'sistema_subsistema_motor_elemento',
		            'type' => 'INNER',
		            'conditions' => 'SistemaSubsistemaMotorElemento.motor_id = MotorSistemaSubsistemaElementoDiagnostico.motor_id AND SistemaSubsistemaMotorElemento.sistema_id = MotorSistemaSubsistemaElementoDiagnostico.sistema_id AND SistemaSubsistemaMotorElemento.subsistema_id = MotorSistemaSubsistemaElementoDiagnostico.subsistema_id AND SistemaSubsistemaMotorElemento.elemento_id = MotorSistemaSubsistemaElementoDiagnostico.elemento_id '
		        ),
		        array(
		            'alias' => 'MotorSistemaSubsistemaPosicion',
		            'table' => 'motor_sistema_subsistema_posicion',
		            'type' => 'INNER',
		            'conditions' => 'MotorSistemaSubsistemaPosicion.motor_id = MotorSistemaSubsistemaElementoDiagnostico.motor_id AND MotorSistemaSubsistemaPosicion.sistema_id = MotorSistemaSubsistemaElementoDiagnostico.sistema_id AND MotorSistemaSubsistemaPosicion.subsistema_id = MotorSistemaSubsistemaElementoDiagnostico.subsistema_id '
		        ),
		        array(
		            'alias' => 'Posicion_Subsistema',
		            'table' => 'posiciones_subsistema',
		            'type' => 'INNER',
		            'conditions' => 'Posicion_Subsistema.id = MotorSistemaSubsistemaPosicion.posicion_id  '
		        ),
		        array(
		            'alias' => 'Posicion_Elemento',
		            'table' => 'posiciones_elemento',
		            'type' => 'INNER',
		            'conditions' => 'Posicion_Elemento.id = SistemaSubsistemaMotorElemento.posicion_id  '
		        )
		    ),
			'conditions' => $conditions,
			'recursive' => 1
		);
		
		$registros = $this->paginate('MotorSistemaSubsistemaElementoDiagnostico');
		$this->set('registros', $registros);
		$this->set(compact('limit'));
		$this->set(compact('query_string'));
		
		$motores = $this->Motor->find('all', array(
			'fields' => array('Motor.id','Motor.nombre','TipoEmision.nombre','TipoAdmision.nombre'),
			'recursive' => 1,
			'order' => 'Motor.nombre',
			'conditions'=>array("Motor.e='1'")
		));
		
		$this->set('motores', $motores);
		$this->set('sistemas', $this->Sistema->find('all', array('order' => 'nombre','conditions'=>array("Sistema.e='1'"), 'recursive' => -1)));
		$this->set('subsistemas', $this->Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Subsistema.e='1'"), 'recursive' => -1)));
		$this->set('elementos', $this->Elemento->find('all', array('order' => 'nombre','conditions'=>array("Elemento.e='1'"), 'recursive' => -1)));
		$this->set('diagnosticos', $this->Diagnostico->find('all', array('order' => 'nombre','conditions'=>array("Diagnostico.e='1'"), 'recursive' => -1)));
		$this->set('posiciones_elementos', $this->Posiciones_Elemento->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Elemento.e='1'"), 'recursive' => -1)));
		$this->set('posiciones_subsistemas', $this->Posiciones_Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Subsistema.e='1'"), 'recursive' => -1)));
	}
	
	public function motor(){
		$this->set('titulo', 'Matriz Motor');
		$this->set('seccion', 'MatrizMotor');
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('Diagnostico');
		$this->loadModel('ElementoPool');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('Posiciones_Elemento');
		
		$limit = 100;
		$conditions="";
		$sistema_id="";
		$subsistema_id="";
		$motor_id="";
		$elemento_id="";
		$did="";
		$codigo="";
		$e="";
		$query_string = "";
		
		if ($this->request->is('post')){
			try {
				foreach($this->request->data['registro'] as $key => $value) {
					$registro = $this->Sistema_Subsistema_Motor_Elemento->find('first', array('conditions'=>array("id" => $key), 'recursive' => -1));
					$conditions = array();
					
					if(isset($registro["Sistema_Subsistema_Motor_Elemento"])){
						$conditions["motor_id"] = $registro["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$conditions["sistema_id"] = $registro["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$conditions["subsistema_id"] = $registro["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						$conditions["elemento_id"] = $registro["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
						$conditions["codigo"] = $registro["Sistema_Subsistema_Motor_Elemento"]["codigo"];
					}
					
					if ($value == '0') {
						if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
							//$data = array();
							//$data["id"] = $key;
							//$data["e"] = "1";
							//$this->Sistema_Subsistema_Motor_Elemento->save($data);
							$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("e" => "1"), $conditions);
						}
					}
					if ($value == '1') {
						if (isset($this->request->data['estado']) && !isset($this->request->data['estado'][$key])) {
							//$data = array();
							//$data["id"] = $key;
							//$data["e"] = "0";
							//$this->Sistema_Subsistema_Motor_Elemento->save($data);
							$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("e" => "0"), $conditions);
						}
					}
				}
				foreach($this->request->data['registro_pool'] as $key => $value) {
					if ($value == '0') {
						if (isset($this->request->data['pool']) && isset($this->request->data['pool'][$key]) && $this->request->data['pool'][$key] == '1') {
							//$data = array();
							//$data["id"] = $key;
							//$data["pool"] = "1";
							//$this->Sistema_Subsistema_Motor_Elemento->save($data);
							$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("pool" => "1"), $conditions);
						}
					}
					if ($value == '1') {
						if (isset($this->request->data['pool']) && !isset($this->request->data['pool'][$key])) {
							//$data = array();
							//$data["id"] = $key;
							//$data["pool"] = "0";
							//$this->Sistema_Subsistema_Motor_Elemento->save($data);
							$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("pool" => "0"), $conditions);
						}
					}
				}
			} catch(Exception $e) {
				$this->Session->setFlash('Ocurrió un error al intentar cambiar el estado de los registros, intente nuevamente. ' . $e->getMessage(),'guardar_error');
			}
		}
		
		$conditions = array();
		if ($this->request->is('get')){
			$query_string = http_build_query($this->request->query);
			if(isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
				$limit = $this->request->query['limit'];
			}
			if(isset($this->request->query['motor_id']) && is_numeric($this->request->query['motor_id'])) {
				$conditions["Motor.id"] = $this->request->query['motor_id'];
			}
			if(isset($this->request->query['sistema_id']) && is_numeric($this->request->query['sistema_id'])) {
				$conditions["Sistema.id"] = $this->request->query['sistema_id'];
			}
			if(isset($this->request->query['subsistema_id']) && is_numeric($this->request->query['subsistema_id'])) {
				$conditions["Subsistema.id"] = $this->request->query['subsistema_id'];
			}
			/*
			if(isset($this->request->query['posicion_subsistema_id']) && is_numeric($this->request->query['posicion_subsistema_id'])) {
				$conditions["Posicion_Subsistema.id"] = $this->request->query['posicion_subsistema_id'];
			}*/
			if(isset($this->request->query['codigo']) && trim($this->request->query['codigo']) != '') {
				$conditions["LOWER(Sistema_Subsistema_Motor_Elemento.codigo) LIKE"] = strtolower($this->request->query['codigo']);
			}
			if(isset($this->request->query['elemento_id']) && is_numeric($this->request->query['elemento_id'])) {
				$conditions["Elemento.id"] = $this->request->query['elemento_id'];
			}
			/*if(isset($this->request->query['diagnostico_id']) && is_numeric($this->request->query['diagnostico_id'])) {
				$conditions["Diagnostico.id"] = $this->request->query['diagnostico_id'];
			}
			if(isset($this->request->query['posicion_elemento_id']) && is_numeric($this->request->query['posicion_elemento_id'])) {
				$conditions["Posicion_Elemento.id"] = $this->request->query['posicion_elemento_id'];
			}*/
			if(isset($this->request->query['pool']) && is_numeric($this->request->query['pool'])) {
				$conditions["Sistema_Subsistema_Motor_Elemento.pool"] = $this->request->query['pool'];
			}
			if(isset($this->request->query['estado']) && is_numeric($this->request->query['estado'])) {
				$conditions["Sistema_Subsistema_Motor_Elemento.e"] = $this->request->query['estado'];
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		try {
			$this->paginate = array(
				'fields' => array("Motor.nombre","Sistema.nombre","Subsistema.nombre","Elemento.nombre","Sistema_Subsistema_Motor_Elemento.codigo","Sistema_Subsistema_Motor_Elemento.pool","Sistema_Subsistema_Motor_Elemento.e","Sistema_Subsistema_Motor_Elemento.id","Motor.id","Sistema.id","Subsistema.id"),
				'limit' => $limit,
				'conditions' => $conditions,
				'group' => array("Motor.nombre","Sistema.nombre","Subsistema.nombre","Elemento.nombre","Sistema_Subsistema_Motor_Elemento.codigo","Sistema_Subsistema_Motor_Elemento.pool","Sistema_Subsistema_Motor_Elemento.e","Sistema_Subsistema_Motor_Elemento.id","Motor.id","Sistema.id","Subsistema.id"),
				'recursive' => 1
			);
			
			$registros = $this->paginate('Sistema_Subsistema_Motor_Elemento');
			$this->set('registros', $registros);		
		} catch(Exception $e) {
			$this->Session->setFlash('Ocurrió un error al intentar cambiar el estado de los registros, intente nuevamente. ' . $e->getMessage(),'guardar_error');
		}
		$this->set(compact('limit'));
		$this->set(compact('query_string'));
		
		$motores = $this->Motor->find('all', array(
			'fields' => array('Motor.id','Motor.nombre','TipoEmision.nombre','TipoAdmision.nombre'),
			'recursive' => 1,
			'order' => 'Motor.nombre',
			'conditions'=>array("Motor.e='1'")
		));
		
		$this->set('motores', $motores);
		$this->set('sistemas', $this->Sistema->find('all', array('order' => 'nombre','conditions'=>array("Sistema.e='1'"), 'recursive' => -1)));
		$this->set('subsistemas', $this->Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Subsistema.e='1'"), 'recursive' => -1)));
		$this->set('elementos', $this->Elemento->find('all', array('order' => 'nombre','conditions'=>array("Elemento.e='1'"), 'recursive' => -1)));
		$this->set('diagnosticos', $this->Diagnostico->find('all', array('order' => 'nombre','conditions'=>array("Diagnostico.e='1'"), 'recursive' => -1)));
		$this->set('posiciones_elementos', $this->Posiciones_Elemento->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Elemento.e='1'"), 'recursive' => -1)));
		$this->set('posiciones_subsistemas', $this->Posiciones_Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Subsistema.e='1'"), 'recursive' => -1)));
	}
	
	public function motor_xls(){
		ini_set('memory_limit', '1024M');
		$this->layout = null;
		//$this->check_permissions($this);
		
		$utilReporte = new UtilidadesReporteController();
		$util = new UtilidadesController();
		PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
		$objPHPExcel = new PHPExcel();
		header ('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header ('Cache-Control: max-age=0');
		header ('Content-Disposition: attachment;filename="Matriz-Motor-'.date("Y-m-d").'".xlsx');
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.date('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objPHPExcel->
	    getProperties()
	        ->setCreator("DBM")
	        ->setLastModifiedBy("DBM")
	        ->setTitle("Matriz Motor");
			
		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Matriz Motor');
		
		// Encabezados
		$encabezados = array("#","Motor","Sistema","Subsistema","Posición Subsistema", "ID", "Elemento", "Posición Elemento", "Diagnóstico", "Pool", "Estado");
		$count = count($encabezados);
		
		for($i=0;$i<$count;$i++){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,1,$encabezados[$i]);
		}
		
		$utilReporte->cellColor("A1:K1","FF0000","FFFFFF",$objPHPExcel);
		
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('Diagnostico');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('Posiciones_Elemento');

		$conditions = array();
		if ($this->request->is('get')){
			if(isset($this->request->query['motor_id']) && is_numeric($this->request->query['motor_id'])) {
				$conditions["Motor.id"] = $this->request->query['motor_id'];
			}
			if(isset($this->request->query['sistema_id']) && is_numeric($this->request->query['sistema_id'])) {
				$conditions["Sistema.id"] = $this->request->query['sistema_id'];
			}
			if(isset($this->request->query['subsistema_id']) && is_numeric($this->request->query['subsistema_id'])) {
				$conditions["Subsistema.id"] = $this->request->query['subsistema_id'];
			}
			if(isset($this->request->query['posicion_subsistema_id']) && is_numeric($this->request->query['posicion_subsistema_id'])) {
				$conditions["Posicion_Subsistema.id"] = $this->request->query['posicion_subsistema_id'];
			}
			if(isset($this->request->query['codigo']) && trim($this->request->query['codigo']) != '') {
				$conditions["LOWER(SistemaSubsistemaMotorElemento.codigo) LIKE"] = strtolower($this->request->query['codigo']);
			}
			if(isset($this->request->query['elemento_id']) && is_numeric($this->request->query['elemento_id'])) {
				$conditions["Elemento.id"] = $this->request->query['elemento_id'];
			}
			if(isset($this->request->query['diagnostico_id']) && is_numeric($this->request->query['diagnostico_id'])) {
				$conditions["Diagnostico.id"] = $this->request->query['diagnostico_id'];
			}
			if(isset($this->request->query['posicion_elemento_id']) && is_numeric($this->request->query['posicion_elemento_id'])) {
				$conditions["Posicion_Elemento.id"] = $this->request->query['posicion_elemento_id'];
			}
			if(isset($this->request->query['pool']) && is_numeric($this->request->query['pool'])) {
				$conditions["SistemaSubsistemaMotorElemento.pool"] = $this->request->query['pool'];
			}
			if(isset($this->request->query['estado']) && is_numeric($this->request->query['estado'])) {
				if($this->request->query['estado'] == '1') {
					$conditions["SistemaSubsistemaMotorElemento.e"] = '1';
					$conditions["MotorSistemaSubsistemaPosicion.e"] = '1';
					$conditions["MotorSistemaSubsistemaElementoDiagnostico.e"] = '1';
				}elseif($this->request->query['estado'] == '0') {
					$conditions["OR"] = array('SistemaSubsistemaMotorElemento.e' => '0', 'MotorSistemaSubsistemaPosicion.e' => '0', 'MotorSistemaSubsistemaElementoDiagnostico.e' => '0');
				}
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		$data = array(
			'fields' => array('MotorSistemaSubsistemaElementoDiagnostico.id','SistemaSubsistemaMotorElemento.id','MotorSistemaSubsistemaPosicion.id','SistemaSubsistemaMotorElemento.codigo','Motor.nombre','Sistema.nombre','Subsistema.nombre','Elemento.nombre','Diagnostico.nombre','Posicion_Subsistema.nombre','Posicion_Elemento.nombre','SistemaSubsistemaMotorElemento.pool','SistemaSubsistemaMotorElemento.e','MotorSistemaSubsistemaPosicion.e','MotorSistemaSubsistemaElementoDiagnostico.e'),
			'limit' => $limit,
			'joins' => array(
		        array(
		            'alias' => 'SistemaSubsistemaMotorElemento',
		            'table' => 'sistema_subsistema_motor_elemento',
		            'type' => 'INNER',
		            'conditions' => 'SistemaSubsistemaMotorElemento.motor_id = MotorSistemaSubsistemaElementoDiagnostico.motor_id AND SistemaSubsistemaMotorElemento.sistema_id = MotorSistemaSubsistemaElementoDiagnostico.sistema_id AND SistemaSubsistemaMotorElemento.subsistema_id = MotorSistemaSubsistemaElementoDiagnostico.subsistema_id AND SistemaSubsistemaMotorElemento.elemento_id = MotorSistemaSubsistemaElementoDiagnostico.elemento_id '
		        ),
		        array(
		            'alias' => 'MotorSistemaSubsistemaPosicion',
		            'table' => 'motor_sistema_subsistema_posicion',
		            'type' => 'INNER',
		            'conditions' => 'MotorSistemaSubsistemaPosicion.motor_id = MotorSistemaSubsistemaElementoDiagnostico.motor_id AND MotorSistemaSubsistemaPosicion.sistema_id = MotorSistemaSubsistemaElementoDiagnostico.sistema_id AND MotorSistemaSubsistemaPosicion.subsistema_id = MotorSistemaSubsistemaElementoDiagnostico.subsistema_id '
		        ),
		        array(
		            'alias' => 'Posicion_Subsistema',
		            'table' => 'posiciones_subsistema',
		            'type' => 'INNER',
		            'conditions' => 'Posicion_Subsistema.id = MotorSistemaSubsistemaPosicion.posicion_id  '
		        ),
		        array(
		            'alias' => 'Posicion_Elemento',
		            'table' => 'posiciones_elemento',
		            'type' => 'INNER',
		            'conditions' => 'Posicion_Elemento.id = SistemaSubsistemaMotorElemento.posicion_id  '
		        )
		    ),
			'conditions' => $conditions,
			'recursive' => 1
		);
		
		$dataArray = array();
		$registros = $this->MotorSistemaSubsistemaElementoDiagnostico->find('all', $data);
		$i = 1;

		foreach ($registros as $registro) {
			$row = array();
			$row[] = $i++;
			$row[] = $registro["Motor"]["nombre"];
			$row[] = $registro["Sistema"]["nombre"];
			$row[] = $registro["Subsistema"]["nombre"];
			$row[] = $registro["Posicion_Subsistema"]["nombre"];
			$row[] = $registro["SistemaSubsistemaMotorElemento"]["codigo"];
			$row[] = $registro["Elemento"]["nombre"];
			$row[] = $registro["Posicion_Elemento"]["nombre"];
			$row[] = $registro["Diagnostico"]["nombre"];
			if ($registro["SistemaSubsistemaMotorElemento"]["pool"] == '1'){
				$row[] = "SI";
			} else {
				$row[] = "NO";
			}
			if($registro['SistemaSubsistemaMotorElemento']['e'] == '1' && $registro['MotorSistemaSubsistemaPosicion']['e'] == '1' && $registro['MotorSistemaSubsistemaElementoDiagnostico']['e'] == '1') {
				$row[] = "Activo";
			} else {
				$row[] = "Inactivo";
			}
			$dataArray[] = $row;
		}
		/*
		echo "<pre>";
		print_r($dataArray);
		die;*/
		
		$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
		$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
		//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	
	public function motoragregar(){
		$this->layout = 'metronic_principal';
		//$this->check_permissions($this);
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('ElementoPool');
		$this->loadModel('Diagnostico');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('Posiciones_Elemento');
		
		$this->set('motores', $this->Motor->find('all', array('fields' => array('Motor.id',"Motor.nombre","TipoEmision.nombre"),'order' => 'Motor.nombre','conditions'=>array("Motor.e='1'"), 'recursive' => 1)));
		$this->set('sistemas', $this->Sistema->find('all', array('order' => 'nombre','conditions'=>array("Sistema.e='1'"), 'recursive' => -1)));
		$this->set('subsistemas', $this->Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Subsistema.e='1'"), 'recursive' => -1)));
		$this->set('elementos', $this->Elemento->find('all', array('order' => 'nombre','conditions'=>array("Elemento.e='1'"), 'recursive' => -1)));
		$this->set('diagnosticos', $this->Diagnostico->find('all', array('order' => 'nombre','conditions'=>array("Diagnostico.e='1'"), 'recursive' => -1)));
		$this->set('posiciones_elementos', $this->Posiciones_Elemento->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Elemento.e='1'"), 'recursive' => -1)));
		$this->set('posiciones_subsistemas', $this->Posiciones_Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Subsistema.e='1'"), 'recursive' => -1)));
		
		if ($this->request->is('post')){
			$posiciones = $this->request->data["posicion_subsistema"];
			foreach($posiciones as $key => $value){		
				try {
					$data = array();
					$data["motor_id"] = $this->request->data["motor_id"];
					$data["sistema_id"] = $this->request->data["sistema_id"];
					$data["subsistema_id"] = $this->request->data["subsistema_id"];
					$data["posicion_id"] = $value;
					$data["e"] = '1';
					$data["updated"] = date("Y-m-d H:i:s");
					$this->MotorSistemaSubsistemaPosicion->create();
					$this->MotorSistemaSubsistemaPosicion->save($data);
				} catch(Exception $e) {
				}
			}
			
			$posiciones = $this->request->data["posicion_elemento"];
			foreach($posiciones as $key => $value){	
				try
					{
					$data = array();
					$data["motor_id"] = $this->request->data["motor_id"];
					$data["sistema_id"] = $this->request->data["sistema_id"];
					$data["subsistema_id"] = $this->request->data["subsistema_id"];
					$data["elemento_id"] = $this->request->data["elemento_id"];
					$data["codigo"] = $this->request->data["codigo"];
					$data["codigo_relacion"] = $this->request->data["codigo"];
					$data["posicion_id"] = $value;
					$data["pool"] = $this->request->data["pool"];
					$data["e"] = '1';
					$data["updated"] = date("Y-m-d H:i:s");
					$this->Sistema_Subsistema_Motor_Elemento->create();
					$this->Sistema_Subsistema_Motor_Elemento->save($data);
				} catch(Exception $e) {
				}
			}
			
			$posiciones = $this->request->data["diagnostico"];
			foreach($posiciones as $key => $value){	
				try {
					$data = array();
					$data["motor_id"] = $this->request->data["motor_id"];
					$data["sistema_id"] = $this->request->data["sistema_id"];
					$data["subsistema_id"] = $this->request->data["subsistema_id"];
					$data["elemento_id"] = $this->request->data["elemento_id"];
					$data["codigo"] = $this->request->data["codigo"];
					$data["diagnostico_id"] = $value;
					$data["e"] = '1';
					$data["updated"] = date("Y-m-d H:i:s");
					$this->MotorSistemaSubsistemaElementoDiagnostico->create();
					$this->MotorSistemaSubsistemaElementoDiagnostico->save($data);
				} catch(Exception $e) {
				}
			}
			
			try {
				if($this->request->data["pool"] == "1"){
					$data = array();
					$data["motor_id"] = $this->request->data["motor_id"];
					$data["sistema_id"] = $this->request->data["sistema_id"];
					$data["subsistema_id"] = $this->request->data["subsistema_id"];
					$data["elemento_id"] = $this->request->data["elemento_id"];
					$data["codigo"] = $this->request->data["codigo"];
					$data["e"] = '1';
					$data["updated"] = date("Y-m-d H:i:s");
					$this->ElementoPool->create();
					$this->ElementoPool->save($data);
				}
			} catch(Exception $e) {
			}
			
			$this->Session->setFlash('Registro ingresado con éxito.','guardar_exito');
			$this->redirect(array('action' => 'Motor'));
		}
	}
	
	public function motoreditar($SistemaSubsistemaMotorElemento_id){
		$this->layout = 'metronic_principal';
		//$this->check_permissions($this);
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('ElementoPool');
		$this->loadModel('Diagnostico');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('Posiciones_Elemento');
		
		$this->set('motores', $this->Motor->find('all', array('fields' => array('Motor.id',"Motor.nombre","TipoEmision.nombre"),'order' => 'Motor.nombre','conditions'=>array("Motor.e='1'"), 'recursive' => 1)));
		$this->set('sistemas', $this->Sistema->find('all', array('order' => 'nombre','conditions'=>array("Sistema.e='1'"), 'recursive' => -1)));
		$this->set('subsistemas', $this->Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Subsistema.e='1'"), 'recursive' => -1)));
		$this->set('elementos', $this->Elemento->find('all', array('order' => 'nombre','conditions'=>array("Elemento.e='1'"), 'recursive' => -1)));
		$this->set('diagnosticos', $this->Diagnostico->find('all', array('order' => 'nombre','conditions'=>array("Diagnostico.e='1'"), 'recursive' => -1)));
		$this->set('posiciones_elementos', $this->Posiciones_Elemento->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Elemento.e='1'"), 'recursive' => -1)));
		$this->set('posiciones_subsistemas', $this->Posiciones_Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Subsistema.e='1'"), 'recursive' => -1)));
		
		$resultado = $this->Sistema_Subsistema_Motor_Elemento->find('first', array(
																		'conditions'=>array("id" => $SistemaSubsistemaMotorElemento_id), 
																		'recursive' => -1));
		if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])){
			$motor_id = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$sistema_id = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$subsistema_id = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			$elemento_id = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
			$codigo = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
			//$posicion_elemento_id = $elemento["Sistema_Subsistema_Motor_Elemento"]["posicion_id"];
			if ($resultado["Sistema_Subsistema_Motor_Elemento"]["pool"] == "1"){
				$pool = "1";
			} else {
				$pool = "0";
			}
			$this->set(compact('motor_id'));
			$this->set(compact('sistema_id'));
			$this->set(compact('subsistema_id'));
			$this->set(compact('elemento_id'));
			$this->set(compact('codigo'));
			//$this->set(compact('posicion_elemento_id'));
			$this->set(compact('pool'));
		}
				
		if($resultado['Sistema_Subsistema_Motor_Elemento']['e'] == '1') {
			$estado = "1";
		}else{
			$estado = "0";
		}
		$this->set(compact('estado'));
		
		if ($this->request->is('post')){
			// Actualización de registro
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
			$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
			
			$data_new = array();
			$data_new["motor_id"] = $this->request->data["motor_id"];
			$data_new["sistema_id"] = $this->request->data["sistema_id"];
			$data_new["subsistema_id"] = $this->request->data["subsistema_id"];
			$data_new["codigo"] = $this->request->data["codigo"];
			$data_new["codigo_relacion"] = $this->request->data["codigo"];
			$data_new["elemento_id"] = $this->request->data["elemento_id"];
			$data_new["pool"] = $this->request->data["pool"];;
			$data_new["e"] = $this->request->data["estado"];
			
			$this->Sistema_Subsistema_Motor_Elemento->updateAll($data_new, $data);
			$resultado["Sistema_Subsistema_Motor_Elemento"] = $data_new;

			// Actualización de posiciones subsistema
			$posiciones = $this->request->data["posicion_subsistema"];
			foreach($posiciones as $key => $value){			
				if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
					try {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						$data["posicion_id"] = $value;
						$this->MotorSistemaSubsistemaPosicion->create();
						$this->MotorSistemaSubsistemaPosicion->save($data);	
					} catch(Exception $e) {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						$data["posicion_id"] = $value;
						$this->MotorSistemaSubsistemaPosicion->updateAll(array("e" => "1"), $data);
					}					
				}
			}
			$posiciones[] = "-1";
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			$data["posicion_id NOT IN"] = $posiciones;
			$this->MotorSistemaSubsistemaPosicion->updateAll(array("e" => "0"), $data);
			
			// Actualizacion posiciones elemento
			$posiciones = $this->request->data["posicion_elemento"];
			foreach($posiciones as $key => $value){			
				if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
					try {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
						$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
						$data["codigo_relacion"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo_relacion"];
						$data["pool"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["pool"];
						$data["posicion_id"] = $value;
						$this->Sistema_Subsistema_Motor_Elemento->create();
						$this->Sistema_Subsistema_Motor_Elemento->save($data);	
					} catch(Exception $e) {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
						$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
						$data["posicion_id"] = $value;
						$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("e" => "1"), $data);
					}					
				}
			}
			$posiciones[] = "-1";
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
			$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
			$data["posicion_id NOT IN"] = $posiciones;
			$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("e" => "0"), $data);
			
			// Actualizacion diagnosticos
			$diagnosticos = $this->request->data["diagnostico"];
			foreach($diagnosticos as $key => $value){			
				if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
					try {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						//$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
						$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
						$data["diagnostico_id"] = $value;
						$this->MotorSistemaSubsistemaElementoDiagnostico->create();
						$this->MotorSistemaSubsistemaElementoDiagnostico->save($data);	
					} catch(Exception $e) {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
						//$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
						$data["diagnostico_id"] = $value;
						$this->MotorSistemaSubsistemaElementoDiagnostico->updateAll(array("e" => "1"), $data);
					}					
				}
			}
			$diagnosticos[] = "-1";
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			//$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
			$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
			$data["diagnostico_id NOT IN"] = $diagnosticos;
			$this->MotorSistemaSubsistemaElementoDiagnostico->updateAll(array("e" => "0"), $data);
		}
		
		$diagnosticos = array();
		if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			//$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];   -->> Resolver tema del código! importante
			$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
			$data["e"] = "1";
			
			$resultados_ = $this->MotorSistemaSubsistemaElementoDiagnostico->find('all', array(
										'fields' => array('diagnostico_id'),
										'conditions'=> $data, 
										'recursive' => -1));
			foreach($resultados_ as $resultado_){
				$diagnosticos[$resultado_["MotorSistemaSubsistemaElementoDiagnostico"]["diagnostico_id"]] = true;
			}
		}
		
		$posicion_elemento = array();
		if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
			$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
			$data["e"] = "1";
			
			$resultados_ = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
										'fields' => array('posicion_id'),
										'conditions'=> $data, 
										'recursive' => -1));
			foreach($resultados_ as $resultado_){
				$posicion_elemento[$resultado_["Sistema_Subsistema_Motor_Elemento"]["posicion_id"]] = true;
			}
		}
		
		$posicion_subsistema = array();
		if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			$data["e"] = "1";
			
			$resultados_ = $this->MotorSistemaSubsistemaPosicion->find('all', array(
										'fields' => array('posicion_id'),
										'conditions'=> $data, 
										'recursive' => -1));
			foreach($resultados_ as $resultado_){
				$posicion_subsistema[$resultado_["MotorSistemaSubsistemaPosicion"]["posicion_id"]] = true;
			}
		}
		
		$this->set('posicion_subsistema', $posicion_subsistema);
		$this->set('posicion_elemento', $posicion_elemento);
		$this->set('diagnostico_elemento', $diagnosticos);
		
		$resultado = $this->Sistema_Subsistema_Motor_Elemento->find('first', array(
																		'conditions'=>array("id" => $SistemaSubsistemaMotorElemento_id), 
																		'recursive' => -1));
		if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])){
			$motor_id = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$sistema_id = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$subsistema_id = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			$elemento_id = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
			$codigo = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
			//$posicion_elemento_id = $elemento["Sistema_Subsistema_Motor_Elemento"]["posicion_id"];
			if ($resultado["Sistema_Subsistema_Motor_Elemento"]["pool"] == "1"){
				$pool = "1";
			} else {
				$pool = "0";
			}
			$this->set(compact('motor_id'));
			$this->set(compact('sistema_id'));
			$this->set(compact('subsistema_id'));
			$this->set(compact('elemento_id'));
			$this->set(compact('codigo'));
			//$this->set(compact('posicion_elemento_id'));
			$this->set(compact('pool'));
		}
	}
	
	public function motorimagen($motor_id, $sistema_id, $subsistema_id){
		$this->layout = 'metronic_principal';
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		
		$resultado = $this->Motor->find('first', array(
										'conditions'=>array("Motor.id" => $motor_id), 
										'recursive' => 1));
		
		if(isset($resultado["Motor"])){
			$motor = $resultado["Motor"]["nombre"] . ' ' . $resultado["TipoEmision"]["nombre"];
		}
		
		$resultado = $this->Sistema->find('first', array(
										'conditions'=>array("id" => $sistema_id), 
										'recursive' => -1));
		
		if(isset($resultado["Sistema"])){
			$sistema = $resultado["Sistema"]["nombre"];
			$sistema = explode("_", $sistema);
			$sistema = $sistema[1];
		}
		
		$resultado = $this->Subsistema->find('first', array(
										'conditions'=>array("id" => $subsistema_id), 
										'recursive' => -1));
		
		if(isset($resultado["Subsistema"])){
			$subsistema = $resultado["Subsistema"]["nombre"];
		}
		
		$image_source = $motor . ' ' . $sistema . ' ' . $subsistema;
		$image_source = str_replace(" ", "_", $image_source);
		$image_source = strtoupper($image_source);
		$image_source = str_replace(array("Á","É","Í","Ó","Ú","Ñ"), array("A","E","I","O","U","N"), $image_source);
		$this->set(compact('image_source'));
		$this->set(compact('motor'));
		$this->set(compact('sistema'));
		$this->set(compact('subsistema'));
	}
	
	public function motor_posicion_subsistema($MotorSistemaSubsistemaElemento_id){
		$this->layout = 'metronic_principal';
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		
		$resultado = $this->Sistema_Subsistema_Motor_Elemento->find('first', array(
					'conditions'=>array("id" => $MotorSistemaSubsistemaElemento_id), 
					'recursive' => -1));
					
		if ($this->request->is('post')){
			$posiciones = $this->request->data["posicion"];
			foreach($posiciones as $key => $value){			
				if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
					try {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						$data["posicion_id"] = $value;
						$this->MotorSistemaSubsistemaPosicion->create();
						$this->MotorSistemaSubsistemaPosicion->save($data);	
					} catch(Exception $e) {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						$data["posicion_id"] = $value;
						$this->MotorSistemaSubsistemaPosicion->updateAll(array("e" => "1"), $data);
					}					
				}
			}
			$posiciones[] = "-1";
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			$data["posicion_id NOT IN"] = $posiciones;
			$this->MotorSistemaSubsistemaPosicion->updateAll(array("e" => "0"), $data);
		}
		
		$posiciones = array();
		if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			$data["e"] = "1";
			
			$resultados = $this->MotorSistemaSubsistemaPosicion->find('all', array(
										'fields' => array('posicion_id'),
										'conditions'=> $data, 
										'recursive' => -1));
			foreach($resultados as $resultado){
				$posiciones[$resultado["MotorSistemaSubsistemaPosicion"]["posicion_id"]] = true;
			}
		}
		
		$this->set('posiciones', $posiciones);
		$this->set('posiciones_subsistemas', $this->Posiciones_Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Subsistema.e='1'"), 'recursive' => -1)));
	}
	
	public function motor_posicion_elemento($MotorSistemaSubsistemaElemento_id){
		$this->layout = 'metronic_principal';
		$this->loadModel('Posiciones_Elemento');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		
		$resultado = $this->Sistema_Subsistema_Motor_Elemento->find('first', array(
										'conditions'=>array("id" => $MotorSistemaSubsistemaElemento_id), 
										'recursive' => -1));

		if ($this->request->is('post')){
			$posiciones = $this->request->data["posicion"];
			foreach($posiciones as $key => $value){			
				if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
					try {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
						$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
						$data["codigo_relacion"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo_relacion"];
						$data["pool"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["pool"];
						$data["posicion_id"] = $value;
						$this->Sistema_Subsistema_Motor_Elemento->create();
						$this->Sistema_Subsistema_Motor_Elemento->save($data);	
					} catch(Exception $e) {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
						$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
						$data["posicion_id"] = $value;
						$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("e" => "1"), $data);
					}					
				}
			}
			$posiciones[] = "-1";
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
			$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
			$data["posicion_id NOT IN"] = $posiciones;
			$this->Sistema_Subsistema_Motor_Elemento->updateAll(array("e" => "0"), $data);
		}
						
		$posiciones = array();
		if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
			$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
			$data["e"] = "1";
			
			$resultados = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
										'fields' => array('posicion_id'),
										'conditions'=> $data, 
										'recursive' => -1));
			foreach($resultados as $resultado){
				$posiciones[$resultado["Sistema_Subsistema_Motor_Elemento"]["posicion_id"]] = true;
			}
		}
		
		$this->set('posiciones', $posiciones);
		$this->set('posiciones_elementos', $this->Posiciones_Elemento->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Elemento.e='1'"), 'recursive' => -1)));
	}
	
	public function motor_diagnostico($MotorSistemaSubsistemaElemento_id){
		$this->layout = 'metronic_principal';
		$this->loadModel('Diagnostico');
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		
		$resultado = $this->Sistema_Subsistema_Motor_Elemento->find('first', array(
										'conditions'=>array("id" => $MotorSistemaSubsistemaElemento_id), 
										'recursive' => -1));
		
		
		if ($this->request->is('post')){
			$diagnosticos = $this->request->data["diagnostico"];
			foreach($diagnosticos as $key => $value){			
				if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
					try {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						//$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
						$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
						$data["diagnostico_id"] = $value;
						$this->MotorSistemaSubsistemaElementoDiagnostico->create();
						$this->MotorSistemaSubsistemaElementoDiagnostico->save($data);	
					} catch(Exception $e) {
						$data = array();
						$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
						$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
						$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
						$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
						//$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
						$data["diagnostico_id"] = $value;
						$this->MotorSistemaSubsistemaElementoDiagnostico->updateAll(array("e" => "1"), $data);
					}					
				}
			}
			$diagnosticos[] = "-1";
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			//$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];
			$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
			$data["diagnostico_id NOT IN"] = $diagnosticos;
			$this->MotorSistemaSubsistemaElementoDiagnostico->updateAll(array("e" => "0"), $data);
		}
		
		$diagnosticos = array();
		if(isset($resultado["Sistema_Subsistema_Motor_Elemento"])) {
			$data = array();
			$data["motor_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["motor_id"];
			$data["sistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["sistema_id"];
			$data["subsistema_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["subsistema_id"];
			//$data["codigo"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];   -->> Resolver tema del código! importante
			$data["elemento_id"] = $resultado["Sistema_Subsistema_Motor_Elemento"]["elemento_id"];
			$data["e"] = "1";
			
			$resultados = $this->MotorSistemaSubsistemaElementoDiagnostico->find('all', array(
										'fields' => array('diagnostico_id'),
										'conditions'=> $data, 
										'recursive' => -1));
			foreach($resultados as $resultado){
				$diagnosticos[$resultado["MotorSistemaSubsistemaElementoDiagnostico"]["diagnostico_id"]] = true;
			}
		}
		
		$this->set('diagnostico_elemento', $diagnosticos);
		$this->set('diagnosticos', $this->Diagnostico->find('all', array('order' => 'nombre','conditions'=>array("Diagnostico.e='1'"), 'recursive' => -1)));
	}
	
	public function select_solucion(){
		$this->layout = null;
		$this->loadModel('Solucion');
		$resultados = $this->Solucion->find('all', array(
						'fields' => array('Solucion.id','Solucion.nombre'),
						'conditions'=> array("Solucion.e" => '1'),
						'order' => array('Solucion.nombre' => "ASC"),
						'recursive' => -1));
		$resultados = json_encode($resultados);
		print($resultados);
		exit;
	}
	
	public function select_tipo(){
		$this->layout = null;
		$this->loadModel('TipoElemento');
		$resultados = $this->TipoElemento->find('all', array(
						'fields' => array('TipoElemento.id','TipoElemento.nombre'),
						'conditions'=> array("TipoElemento.e" => '1'),
						'order' => array('TipoElemento.nombre' => "ASC"),
						'recursive' => -1));
		$resultados = json_encode($resultados);
		print($resultados);
		exit;
	}
	
	public function select_sistema($motor_id){
		$this->layout = null;
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$resultados = $this->MotorSistemaSubsistemaPosicion->find('all', array(
						'fields' => array('Sistema.id','Sistema.nombre'),
						'conditions'=> array("MotorSistemaSubsistemaPosicion.motor_id" => $motor_id),
						'group' => array('Sistema.id','Sistema.nombre'),
						'order' => array('Sistema.nombre' => "ASC"),
						'recursive' => 1));
		$resultados = json_encode($resultados);
		print($resultados);
		exit;
	}
	
	public function select_subsistema($motor_id, $sistema_id){
		$this->layout = null;
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$resultados = $this->MotorSistemaSubsistemaPosicion->find('all', array(
						'fields' => array('Subsistema.id','Subsistema.nombre'),
						'conditions'=> array("MotorSistemaSubsistemaPosicion.motor_id" => $motor_id, 'MotorSistemaSubsistemaPosicion.sistema_id' => $sistema_id),
						'group' => array('Subsistema.id','Subsistema.nombre'),
						'order' => array('Subsistema.nombre' => "ASC"),
						'recursive' => 1));
		$resultados = json_encode($resultados);
		print($resultados);
		exit;
	}
	
	public function select_posicion_subsistema($motor_id, $sistema_id, $subsistema_id){
		$this->layout = null;
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$resultados = $this->MotorSistemaSubsistemaPosicion->find('all', array(
						'fields' => array('Posicion.id','Posicion.nombre'),
						'conditions'=> array("MotorSistemaSubsistemaPosicion.motor_id" => $motor_id, 'MotorSistemaSubsistemaPosicion.sistema_id' => $sistema_id, 'MotorSistemaSubsistemaPosicion.subsistema_id' => $subsistema_id),
						'group' => array('Posicion.id','Posicion.nombre'),
						'order' => array('Posicion.nombre' => "ASC"),
						'recursive' => 1));
		$resultados = json_encode($resultados);
		print($resultados);
		exit;
	}
	
	public function select_elemento($motor_id, $sistema_id, $subsistema_id, $unique = 0){
		$this->layout = null;
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->loadModel('Elemento');
		if($unique != 99999) {
			$resultados = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
							'fields' => array('Elemento.id','Elemento.nombre','Sistema_Subsistema_Motor_Elemento.codigo'),
							'conditions'=> array("Sistema_Subsistema_Motor_Elemento.motor_id" => $motor_id, 'Sistema_Subsistema_Motor_Elemento.sistema_id' => $sistema_id, 'Sistema_Subsistema_Motor_Elemento.subsistema_id' => $subsistema_id),
							'group' => array('Elemento.id','Elemento.nombre','Sistema_Subsistema_Motor_Elemento.codigo'),
							'order' => array('Elemento.nombre'),
							'recursive' => 1));
		} else {
			$resultados = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
							'fields' => array('Elemento.id','Elemento.nombre'),
							'conditions'=> array("Sistema_Subsistema_Motor_Elemento.motor_id" => $motor_id, 'Sistema_Subsistema_Motor_Elemento.sistema_id' => $sistema_id, 'Sistema_Subsistema_Motor_Elemento.subsistema_id' => $subsistema_id),
							'group' => array('Elemento.id','Elemento.nombre'),
							'order' => array('Elemento.nombre'),
							'recursive' => 1));
		}
		$resultados = json_encode($resultados);
		print($resultados);
		exit;
	}
	
	public function select_posicion_elemento($motor_id, $sistema_id, $subsistema_id, $elemento_id){
		$this->layout = null;
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->loadModel('Elemento');
		$resultados = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
						'fields' => array('Posicion.id','Posicion.nombre'),
						'conditions'=> array("Sistema_Subsistema_Motor_Elemento.motor_id" => $motor_id, 'Sistema_Subsistema_Motor_Elemento.sistema_id' => $sistema_id, 'Sistema_Subsistema_Motor_Elemento.subsistema_id' => $subsistema_id, 'Sistema_Subsistema_Motor_Elemento.elemento_id' => $elemento_id),
						'group' => array('Posicion.id','Posicion.nombre'),
						'order' => array('Posicion.nombre'),
						'recursive' => 1));
		$resultados = json_encode($resultados);
		print($resultados);
		exit;
	}
	
	public function select_diagnostico($motor_id, $sistema_id, $subsistema_id, $elemento_id){
		$this->layout = null;
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		$resultados = $this->MotorSistemaSubsistemaElementoDiagnostico->find('all', array(
						'fields' => array('Diagnostico.id','Diagnostico.nombre'),
						'conditions'=> array("MotorSistemaSubsistemaElementoDiagnostico.motor_id" => $motor_id, 'MotorSistemaSubsistemaElementoDiagnostico.sistema_id' => $sistema_id, 'MotorSistemaSubsistemaElementoDiagnostico.subsistema_id' => $subsistema_id , 'MotorSistemaSubsistemaElementoDiagnostico.elemento_id' => $elemento_id),
						'group' => array('Diagnostico.id','Diagnostico.nombre'),
						'order' => array('Diagnostico.nombre' => "ASC"),
						'recursive' => 1));
		$resultados = json_encode($resultados);
		print($resultados);
		exit;
	}
	
	public function selecttipotecnico(){
		$this->layout = null;
		$this->loadModel('TipoTecnico');
		$resultados = $this->TipoTecnico->find('all', array(
						'fields' => array('TipoTecnico.id','TipoTecnico.nombre','TipoTecnico.unico','TipoTecnico.requerido'),
						'order' => array('TipoTecnico.nombre'),
						'recursive' => -1));
		$resultados = json_encode($resultados);
		print($resultados);
		exit;
	}
	
	public function selecttecnico($faena_id){
		$this->layout = null;
		$this->loadModel('UsuarioFaena');
		$resultados = $this->UsuarioFaena->find('all', array(
			'conditions' => "(UsuarioFaena.faena_id = $faena_id AND nivelusuario_id = 1)",
			'fields' => array("Usuario.apellidos", "Usuario.nombres", "Usuario.id"),
			'order' => array('Usuario.apellidos', 'Usuario.nombres'),
			'recursive' => 1
		));
		$resultados = json_encode($resultados);
		print($resultados);
		exit;
	}
	
	
	
}

?>