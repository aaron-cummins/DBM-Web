<?php

ini_set('memory_limit','1024M');
set_time_limit(1000000);

App::uses('ConnectionManager', 'Model'); 
App::uses('File', 'Utility');
App::import('Vendor', 'Classes/PHPExcel');

/*
	Esta clase define el funcionalidades para cargar y procesas archivos de matrices en el perfil de Administracion
*/
class HerramientasController extends AppController {
	public function estado() {
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		$this->loadModel('Motor');
		$this->loadModel('Unidad');
		$this->set('titulo', 'Cargar Estado Motores');
		$filas = array();
		if (count($this->request->data)) {
			$idexistentes = array();
			$esn_valido = 0;
			$data = $this->request->data;
			$data = $data["CargaEstadoMotores"]["EstadoMotores"];
			if ($data["error"] == 0 && $data["size"] > 0) {
				$new_file = "/tmp/sg_" . time();
				if (copy($data["tmp_name"], $new_file)) {
					$this->set('archivo', $new_file);
				}
				$objPHPExcel = new PHPExcel();
				$inputFileName = $data["tmp_name"];
				try {
					$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($inputFileName);
				} catch(Exception $e) {
					die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
				}
				// ESN
				$sheet = $objPHPExcel->getSheet(0); 
				$highestRow = $sheet->getHighestRow(); 
				$highestColumn = $sheet->getHighestColumn();
				//$highestRow = 11;
				for ($row = 2; $row <= $highestRow; $row++){
					//$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row);
					$rowData = array();
					$cell = $sheet->getCell('B' . $row);
					$rowData["Faena"] = $cell->getCalculatedValue();
					$cell = $sheet->getCell('J' . $row);
					$rowData["Motor"] = $cell->getOldCalculatedValue();
					$cell = $sheet->getCell('D' . $row);
					$rowData["Flota"] = $cell->getOldCalculatedValue();
					$cell = $sheet->getCell('G' . $row);
					$rowData["Unidad"] = $cell->getCalculatedValue();
					$cell = $sheet->getCell('P' . $row);
					$rowData["FechaInicio"] = $cell->getFormattedValue();
					$cell = $sheet->getCell('V' . $row);
					$rowData["FechaTermino"] = $cell->getFormattedValue();
					$cell = $sheet->getCell('H' . $row);
					$rowData["ESN"] = $cell->getCalculatedValue();
					
					$meses = array(".","Jan","Feb","Mar","Apr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dec");
					$meses1 = array(".","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
					$meses2 = array(".","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
					$meses0 = array("","01","02","03","04","05","06","07","08","09","10","11","12");
					
					if($rowData["Faena"]==''||$rowData["Motor"]==''||$rowData["Flota"]==''||$rowData["Unidad"]==''||$rowData["FechaInicio"]==''||$rowData["ESN"]==''){
						continue;
					}
					if($rowData["Faena"]=='S/I'||$rowData["Motor"]=='S/I'||$rowData["Flota"]=='S/I'||$rowData["Unidad"]=='S/I'||$rowData["FechaInicio"]=='S/I'||$rowData["FechaInicio"]=='Sin P.S.'||$rowData["ESN"]=='S/I'){
						continue;
					}
					//die($rowData["FechaInicio"]);
					$rowData["FechaInicio"] = str_replace($meses,$meses0,$rowData["FechaInicio"]);
					$rowData["FechaInicio"] = str_replace($meses1,$meses0,$rowData["FechaInicio"]);
					$rowData["FechaInicio"] = str_replace($meses2,$meses0,$rowData["FechaInicio"]);
					//print_r($rowData["FechaInicio"]);
					$rowData["FechaInicio"] = explode("/",$rowData["FechaInicio"]);
					$rowData["FechaInicio"] = "20".$rowData["FechaInicio"][2]."-".$rowData["FechaInicio"][1]."-".$rowData["FechaInicio"][0];
					if($rowData["FechaTermino"]!=''){
						$rowData["FechaTermino"] = str_replace($meses,$meses0,$rowData["FechaTermino"]);
						$rowData["FechaTermino"] = str_replace($meses1,$meses0,$rowData["FechaTermino"]);
						$rowData["FechaTermino"] = str_replace($meses2,$meses0,$rowData["FechaTermino"]);
						$rowData["FechaTermino"] = explode("/",$rowData["FechaTermino"]);
						$rowData["FechaTermino"] = "20".$rowData["FechaTermino"][2]."-".$rowData["FechaTermino"][1]."-".$rowData["FechaTermino"][0];
						$rowData["FechaTermino"] = "'".$rowData["FechaTermino"]."'";
					}else{
						$rowData["FechaTermino"] = "NULL";
					}
					
					//print_r($rowData);
					//echo "<br />";
				}
				//exit;
				//echo "<hr />";
				// Estado Motor
				$sheet = $objPHPExcel->getSheet(1); 
				$highestRow = $sheet->getHighestRow(); 
				$highestColumn = $sheet->getHighestColumn();
				//$highestRow = 11;
				for ($row2 = 2; $row2 <= $highestRow; $row2++){
					$rowData = array();
					$cell = $sheet->getCell('I' . $row2);
					$rowData["EstadoMotor"] = $cell->getOldCalculatedValue();
					$cell = $sheet->getCell('D' . $row2);
					$rowData["Faena"] = $cell->getCalculatedValue();
					$cell = $sheet->getCell('V' . $row2);
					$rowData["Motor"] = $cell->getCalculatedValue();
					$cell = $sheet->getCell('F' . $row2);
					$rowData["Flota"] = $cell->getCalculatedValue();
					$cell = $sheet->getCell('G' . $row2);
					$rowData["Unidad"] = $cell->getCalculatedValue();
					$cell = $sheet->getCell('M' . $row2);
					$rowData["Horometro"] = $cell->getCalculatedValue();
					//$rowData["Horometro"] = str_replace(".","", $rowData[12]);
					//$rowData["Horometro"] = str_replace(",",".", $rowData["Horometro"]);
					$cell = $sheet->getCell('H' . $row2);
					$rowData["ESN"] = $cell->getCalculatedValue();
					$cell = $sheet->getCell('W' . $row2);
					$rowData["ModeloMotor"] = $cell->getOldCalculatedValue();
					$cell = $sheet->getCell('P' . $row2);
					$rowData["FabricanteEquipo"] = $cell->getCalculatedValue();
					$cell = $sheet->getCell('Q' . $row2);
					$rowData["ModeloEquipo"] = $cell->getCalculatedValue();
					$cell = $sheet->getCell('R' . $row2);
					$rowData["SerieEquipo"] = $cell->getCalculatedValue();
					$cell = $sheet->getCell('E' . $row2);
					$rowData["TipoEquipo"] = $cell->getCalculatedValue();
					
					$rowData["td"] = '';
					$rowData["Accion"] = "";
					
					$r = $this->Unidad->find('first', array(
						'fields' => array('Unidad.*'),
						'conditions' => array('LOWER(TRIM(Faena.nombre))' => strtolower(trim($rowData["Faena"])),
											  'LOWER(TRIM(Motor.nombre))' => strtolower(trim($rowData["Motor"])),
											  'LOWER(TRIM(Flota.nombre))' => strtolower(trim($rowData["Flota"])),
											   'LOWER(TRIM(Unidad.unidad))' => strtolower(trim($rowData["Unidad"]))),
						'recursive'=>1
					));
					if(!isset($r["Unidad"])){
						$rowData["Accion"] = "<input type=\"checkbox\" name=\"agregar[]\" class=\"matriz_opciones\" value=\"$row2\" title=\"Marcar para agregar\" />";
						$rowData["Estado"]="Nuevo";
					}else{
						$idexistentes[]=$r["Unidad"]["id"];
						//$rowData["Estado"]="Ambos";
						if(!is_numeric($rowData["Horometro"])){
							$rowData["Horometro"]="0";
						}
						$rowData["Horometro"]=number_format($rowData["Horometro"],2,".","");
						if($r["Unidad"]["horometro"]!=$rowData["Horometro"]||$r["Unidad"]["esn"]!=$rowData["ESN"]||$r["Unidad"]["modelo_equipo"]!=$rowData["ModeloEquipo"]||$r["Unidad"]["modelo_motor"]!=$rowData["ModeloMotor"]||$r["Unidad"]["nserie"]!=$rowData["SerieEquipo"]||$r["Unidad"]["aplicacion"]!=$rowData["TipoEquipo"]||$r["Unidad"]["fabricante"]!=$rowData["FabricanteEquipo"]){
							$rowData["Accion"] = "<input type=\"checkbox\" name=\"actualizar[]\" class=\"matriz_opciones\" value=\"$row2\" title=\"Marcar para actualizar\" />";
							$rowData["Estado"]="Nuevo";
						}else{
							$rowData["Estado"]="Ambos";	
						}
					}
					
					//print_r($rowData);
					$filas[] = $rowData;
				}
			}
			$idexistentes[]="0";
			$idexi=implode(",",$idexistentes);
			$resultado = $this->Unidad->find('all', array(
				'conditions' => array("Unidad.id NOT IN ($idexi)"),
				'fields'=>array('Unidad.*','Faena.nombre','Motor.nombre','Flota.nombre'),
				'recursive'=>1
			));
			foreach($resultado as $unidad){
				if(isset($unidad["Unidad"]["unidad"])&&$unidad["Unidad"]["unidad"]!=""){
					$rowData = array();
					$rowData["td"] = '';
					$rowData["class"]="";
					$rowData["Faena"] = $unidad["Faena"]["nombre"];
					$rowData["Motor"] = $unidad["Motor"]["nombre"];
					$rowData["Flota"] = $unidad["Flota"]["nombre"];
					$rowData["Unidad"] = $unidad["Unidad"]["unidad"];
					$rowData["Horometro"] = $unidad["Unidad"]["horometro"];
					$rowData["ESN"] = $unidad["Unidad"]["esn"];
					$rowData["ModeloMotor"] = $unidad["Unidad"]["modelo_motor"];
					$rowData["FabricanteEquipo"] = $unidad["Unidad"]["fabricante"];
					$rowData["ModeloEquipo"] = $unidad["Unidad"]["modelo_equipo"];
					$rowData["SerieEquipo"] = $unidad["Unidad"]["nserie"];
					$rowData["TipoEquipo"] = $unidad["Unidad"]["aplicacion"];
					$rowData["Estado"] = $unidad["Unidad"]["e"]==1?"Activo":"Inactivo";
					$rowData["Accion"] = "<input type=\"checkbox\" name=\"quitar[]\" class=\"matriz_opciones\" value=\"".$unidad["Unidad"]["id"]."\" />";
					$filas[] = $rowData;
				}
			}
		}
		$this->set('faenas', $this->Faena->find('all', array('order' => 'nombre','conditions'=>array("Faena.e='1'"), 'recursive' => -1)));
		$this->set('flotas', $this->Flota->find('all', array('order' => 'nombre','conditions'=>array("Flota.e='1'"), 'recursive' => -1)));
		$this->set('motores', $this->Motor->find('all', array('order' => 'nombre','conditions'=>array("Motor.e='1'"), 'recursive' => -1)));
		$this->set('data', $filas);
	}
	
	public function estadoprocesar() {
		$this->loadModel('Unidad');
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		$this->loadModel('Motor');
		$this->layout=null;
		$db = ConnectionManager::getDataSource('default');
		$objPHPExcel = new PHPExcel();
		$inputFileName = $this->request->data["Herramientas"]["hash_file"];
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
			//die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}
		//$file = new File($this->request->data["Herramientas"]["hash_file"], false, 0644);
		//$data = $file->read(true, 'r');
		//$tmp = explode("\n", $data);
		//$i = 0;
		//unset($tmp[0]);
		
		if ((isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"]))||(isset($this->request->data["actualizar"]) && is_array($this->request->data["actualizar"]))) {
			// Estado Motor
			$sheet = $objPHPExcel->getSheet(1); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();
			//$highestRow = 11;
			for ($row2 = 2; $row2 <= $highestRow; $row2++){
				$rowData = array();
				$cell = $sheet->getCell('I' . $row2);
				$rowData["EstadoMotor"] = $cell->getOldCalculatedValue();
				$cell = $sheet->getCell('D' . $row2);
				$rowData["Faena"] = $cell->getCalculatedValue();
				$cell = $sheet->getCell('V' . $row2);
				$rowData["Motor"] = $cell->getCalculatedValue();
				$cell = $sheet->getCell('F' . $row2);
				$rowData["Flota"] = $cell->getCalculatedValue();
				$cell = $sheet->getCell('G' . $row2);
				$rowData["Unidad"] = $cell->getCalculatedValue();
				$cell = $sheet->getCell('M' . $row2);
				$rowData["Horometro"] = $cell->getCalculatedValue();
				//$rowData["Horometro"] = str_replace(".","", $rowData[12]);
				//$rowData["Horometro"] = str_replace(",",".", $rowData["Horometro"]);
				$cell = $sheet->getCell('H' . $row2);
				$rowData["ESN"] = $cell->getCalculatedValue();
				$cell = $sheet->getCell('W' . $row2);
				$rowData["ModeloMotor"] = $cell->getOldCalculatedValue();
				$cell = $sheet->getCell('P' . $row2);
				$rowData["FabricanteEquipo"] = $cell->getCalculatedValue();
				$cell = $sheet->getCell('Q' . $row2);
				$rowData["ModeloEquipo"] = $cell->getCalculatedValue();
				$cell = $sheet->getCell('R' . $row2);
				$rowData["SerieEquipo"] = $cell->getCalculatedValue();
				$cell = $sheet->getCell('E' . $row2);
				$rowData["TipoEquipo"] = $cell->getCalculatedValue();
				$i=$row2;
				if (isset($this->request->data["agregar"])&&is_array($this->request->data["agregar"])&&array_search($i, $this->request->data["agregar"]) !== FALSE) {
					$temp = array();
					$temp["faena_id"] = -1; 
					$temp["motor_id"] = -1;
					$temp["flota_id"] = -1;
					if($rowData["Faena"] == null || $rowData["Faena"] == ''){
						continue;
					}
					if($rowData["Flota"] == null || $rowData["Flota"] == ''){
						continue;
					}
					if($rowData["Motor"] == null || $rowData["Motor"] == ''){
						continue;
					}
					$r=$this->Faena->find('first', array('conditions' => array('TRIM(LOWER(nombre))' => trim(strtolower($rowData["Faena"]))),'recursive' => -1));
					if(isset($r["Faena"])){
						$temp["faena_id"] = $r["Faena"]["id"];
					}else{
						$data=array(
							'nombre'=>$rowData["Faena"],
							'e'=>'1'
						);
						$this->Faena->create();
						$this->Faena->save($data);
						//print_r($data);
						$temp["faena_id"]=$this->Faena->id;
					}
					$r=$this->Motor->find('first', array('conditions' => array('TRIM(LOWER(nombre))' => trim(strtolower($rowData["Motor"]))),'recursive' => -1));
					if(isset($r["Motor"])){
						$temp["motor_id"] = $r["Motor"]["id"];
					}else{
						$data=array(
							'nombre'=>$rowData["Motor"],
							'e'=>'1'
						);
						$this->Motor->create();
						$this->Motor->save($data);
						//print_r($data);
						$temp["motor_id"]=$this->Motor->id;
					}
					$r=$this->Flota->find('first', array('conditions' => array('TRIM(LOWER(nombre))' => trim(strtolower($rowData["Flota"]))),'recursive' => -1));
					if(isset($r["Flota"])){
						$temp["flota_id"] = $r["Flota"]["id"];
					}else{
						$data=array(
							'nombre'=>$rowData["Flota"],
							'e'=>'1'
						);
						$this->Flota->create();
						$this->Flota->save($data);
						//print_r($data);
						$temp["flota_id"]=$this->Flota->id;
					}
					$temp["unidad"] = $rowData["Unidad"];
					$temp["horometro"] = $rowData["Horometro"];
					$temp["esn"] = $rowData["ESN"];
					$temp["modelo_motor"] = $rowData["ModeloMotor"];
					$temp["fabricante"] = $rowData["FabricanteEquipo"];
					$temp["modelo_equipo"] = $rowData["ModeloEquipo"];
					$temp["nserie"] = $rowData["SerieEquipo"];
					$temp["aplicacion"] = $rowData["TipoEquipo"];
					if(!is_numeric($temp["horometro"])){
						$temp["horometro"]="0";
					}
					$temp["horometro"]=number_format($temp["horometro"],2,".","");
					try {
						//print_r($temp);
						$this->Unidad->create();
						$this->Unidad->save($temp);
					}catch(Exception $e){}
				}elseif(isset($this->request->data["actualizar"])&&is_array($this->request->data["actualizar"])&&array_search($i, $this->request->data["actualizar"]) !== FALSE){
					if(!is_numeric($rowData["Horometro"])){
						$rowData["Horometro"]="0";
					}
					$rowData["Horometro"]=number_format($rowData["Horometro"],2,".","");
					$r = $this->Unidad->find('first', array(
						'fields' => array('Unidad.id'),
						'conditions' => array('LOWER(TRIM(Faena.nombre))' => strtolower(trim($rowData["Faena"])),
											  'LOWER(TRIM(Motor.nombre))' => strtolower(trim($rowData["Motor"])),
											  'LOWER(TRIM(Flota.nombre))' => strtolower(trim($rowData["Flota"])),
											  'LOWER(TRIM(Unidad.unidad))' => strtolower(trim($rowData["Unidad"]))),
						'recursive'=>1
					));
					if(isset($r["Unidad"])){
						$data=array(
							'id'=>$r["Unidad"]["id"],
							'horometro'=>$rowData["Horometro"],
							'esn'=>$rowData["ESN"],
							'modelo_motor'=>$rowData["ModeloMotor"],
							'fabricante'=>$rowData["FabricanteEquipo"],
							'modelo_equipo'=>$rowData["ModeloEquipo"],
							'nserie'=>$rowData["SerieEquipo"],
							'aplicacion'=>$rowData["TipoEquipo"],
						);
						try {
							$this->Unidad->save($data);
							//print_r($data);
						}catch(Exception $e){}
					}
				}
			}
		}
		
		if(isset($this->request->data["quitar"])&&is_array($this->request->data["quitar"])&&count($this->request->data["quitar"])>0){
			foreach($this->request->data["quitar"] as $key=>$value){
				try{
					$resultado=$this->Unidad->find('first',array(
						'fields'=>array('Unidad.id','Unidad.e'),
						'conditions'=>array('Unidad.id'=>$value),
						'recursive'=>-1
					));
					if(isset($resultado["Unidad"])&&isset($resultado["Unidad"]["id"])){
						$data=array();
						$data["id"]=$resultado["Unidad"]["id"];
						if($resultado["Unidad"]["e"]=="1"){
							$data["e"]="0";
						}else{
							$data["e"]="1";
						}
						//print_r($data);
						$this->Unidad->save($data);
					}
				}catch(Exception $e){}
			}
		}
		
		// Actualizar ESN
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();
		for ($row = 2; $row <= $highestRow; $row++){
			$rowData = array();
			$cell = $sheet->getCell('B' . $row);
			$rowData["Faena"] = $cell->getCalculatedValue();
			$cell = $sheet->getCell('J' . $row);
			$rowData["Motor"] = $cell->getOldCalculatedValue();
			$cell = $sheet->getCell('D' . $row);
			$rowData["Flota"] = $cell->getOldCalculatedValue();
			$cell = $sheet->getCell('G' . $row);
			$rowData["Unidad"] = $cell->getCalculatedValue();
			$cell = $sheet->getCell('P' . $row);
			$rowData["FechaInicio"] = $cell->getFormattedValue();
			$cell = $sheet->getCell('V' . $row);
			$rowData["FechaTermino"] = $cell->getFormattedValue();
			$cell = $sheet->getCell('H' . $row);
			$rowData["ESN"] = $cell->getCalculatedValue();
			
			$meses = array(".","Jan","Feb","Mar","Apr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dec");
			$meses0 = array("","01","02","03","04","05","06","07","08","09","10","11","12");
			$meses1 = array(".","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
			$meses2 = array(".","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
			
			if($rowData["Faena"]==''||$rowData["Motor"]==''||$rowData["Flota"]==''||$rowData["Unidad"]==''||$rowData["FechaInicio"]==''||$rowData["ESN"]==''){
				continue;
			}
			if($rowData["Faena"]=='S/I'||$rowData["Motor"]=='S/I'||$rowData["Flota"]=='S/I'||$rowData["Unidad"]=='S/I'||$rowData["FechaInicio"]=='S/I'||$rowData["FechaInicio"]=='Sin P.S.'||$rowData["ESN"]=='S/I'){
				continue;
			}
			
			$rowData["FechaInicio"] = str_replace($meses,$meses0,$rowData["FechaInicio"]);
			$rowData["FechaInicio"] = str_replace($meses1,$meses0,$rowData["FechaInicio"]);
			$rowData["FechaInicio"] = str_replace($meses2,$meses0,$rowData["FechaInicio"]);			
			$rowData["FechaInicio"] = explode("/",$rowData["FechaInicio"]);
			$rowData["FechaInicio"] = "20".$rowData["FechaInicio"][2]."-".$rowData["FechaInicio"][1]."-".$rowData["FechaInicio"][0];
			if($rowData["FechaTermino"]!=''){
				$rowData["FechaTermino"] = str_replace($meses,$meses0,$rowData["FechaTermino"]);
				$rowData["FechaTermino"] = str_replace($meses1,$meses0,$rowData["FechaTermino"]);
				$rowData["FechaTermino"] = str_replace($meses2,$meses0,$rowData["FechaTermino"]);
				$rowData["FechaTermino"] = explode("/",$rowData["FechaTermino"]);
				$rowData["FechaTermino"] = "20".$rowData["FechaTermino"][2]."-".$rowData["FechaTermino"][1]."-".$rowData["FechaTermino"][0];
				$rowData["FechaTermino"] = "'".$rowData["FechaTermino"]."'";
			}else{
				$rowData["FechaTermino"] = "NULL";
			}
			
			$r = $this->Faena->find('first', array(
					'fields' => array('Faena.id'),
					'conditions' => array('LOWER(TRIM(Faena.nombre))' => strtolower(trim($rowData["Faena"]))),
					'recursive'=>-1
				));
			if(isset($r["Faena"]["id"])){
				$faena_id = $r["Faena"]["id"];
			}
			$r = $this->Flota->find('first', array(
					'fields' => array('Flota.id'),
					'conditions' => array('LOWER(TRIM(Flota.nombre))' => strtolower(trim($rowData["Flota"]))),
					'recursive'=>-1
				));
			if(isset($r["Flota"]["id"])){
				$flota_id = $r["Flota"]["id"];
			}
			$r = $this->Motor->find('first', array(
					'fields' => array('Motor.id'),
					'conditions' => array('LOWER(TRIM(Motor.nombre))' => strtolower(trim($rowData["Motor"]))),
					'recursive'=>-1
				));
			if(isset($r["Motor"]["id"])){
				$motor_id = $r["Motor"]["id"];
			}
			$sql = "INSERT INTO esn (faena_id,flota_id,motor_id,unidad,esn,fecha_inicio,fecha_termino,e) VALUES ($faena_id,$flota_id,$motor_id,'{$rowData["Unidad"]}','{$rowData["ESN"]}','{$rowData["FechaInicio"]}',{$rowData["FechaTermino"]},'1');";
			//echo $sql;
			
			try {
				@$db->query($sql);
			} catch (Exception $e) {
			}
		}
		
		//die("DDD");
		
		$this->Session->setFlash('Estado de Motores actualizado de éxito!', 'guardar_exito');
		//echo "redirect?";
		$this->redirect('/Herramientas/Estado/');
	}

	public function actualizar_esn() {
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		$this->loadModel('Motor');
		$this->layout = null;
		$db = ConnectionManager::getDataSource('default');
		if (count($this->request->data)) {
			$fh = fopen($this->request->data["Herramientas"]["hash_file"],'r');
			while ($fila = fgets($fh)) {
				$tmp2 = explode(";", $fila);
				$temp["Accion"] = "";
				$temp["Faena"] = @$tmp2[1]; 
				$temp["Motor"] = @$tmp2[9];
				$temp["Flota"] = @$tmp2[3];
				$temp["Unidad"] = @$tmp2[6];
				$temp["FechaInicio"] = @$tmp2[15];
				$temp["FechaTermino"] = @$tmp2[21];
				$temp["ESN"] = @$tmp2[7];
				
				$meses = array(".","ene","feb","mar","abr","may","jun","jul","ago","sep","oct","nov","dic");
				$meses0 = array("","01","02","03","04","05","06","07","08","09","10","11","12");
				$meses1 = array(".","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
				$meses2 = array(".","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
				
				if($temp["Faena"]==''||$temp["Motor"]==''||$temp["Flota"]==''||$temp["Unidad"]==''||$temp["FechaInicio"]==''||$temp["ESN"]==''){
					continue;
				}
				if($temp["Faena"]=='S/I'||$temp["Motor"]=='S/I'||$temp["Flota"]=='S/I'||$temp["Unidad"]=='S/I'||$temp["FechaInicio"]=='S/I'||$temp["ESN"]=='S/I'){
					continue;
				}
				
				$temp["FechaInicio"] = str_replace($meses,$meses0,$temp["FechaInicio"]);
				$temp["FechaInicio"] = str_replace($meses1,$meses0,$temp["FechaInicio"]);
				$temp["FechaInicio"] = str_replace($meses2,$meses0,$temp["FechaInicio"]);
				$temp["FechaInicio"] = explode("-",$temp["FechaInicio"]);
				$temp["FechaInicio"] = "20".$temp["FechaInicio"][2]."-".$temp["FechaInicio"][1]."-".$temp["FechaInicio"][0];
				//$temp["FechaInicio"] = strtotime($temp["FechaInicio"]);
				//$temp["FechaInicio"] = date("Y-m-d",$temp["FechaInicio"]);
				
				if($temp["FechaTermino"]!=''){
					$temp["FechaTermino"] = str_replace($meses,$meses0,$temp["FechaTermino"]);
					$temp["FechaTermino"] = str_replace($meses1,$meses0,$temp["FechaTermino"]);
					$temp["FechaTermino"] = str_replace($meses2,$meses0,$temp["FechaTermino"]);
					$temp["FechaTermino"] = explode("-",$temp["FechaTermino"]);
					$temp["FechaTermino"] = "20".$temp["FechaTermino"][2]."-".$temp["FechaTermino"][1]."-".$temp["FechaTermino"][0];
					//$temp["FechaTermino"] = strtotime($temp["FechaTermino"]);
					//$temp["FechaTermino"] = date("Y-m-d",$temp["FechaTermino"]);
					$temp["FechaTermino"] = "'".$temp["FechaTermino"]."'";
				}else{
					$temp["FechaTermino"] = "NULL";
				}
				
				$r = $this->Faena->find('first', array(
						'fields' => array('Faena.id'),
						'conditions' => array('LOWER(TRIM(Faena.nombre))' => strtolower(trim($temp["Faena"]))),
						'recursive'=>-1
					));
				if(isset($r["Faena"]["id"])){
					$faena_id = $r["Faena"]["id"];
				}
				$r = $this->Flota->find('first', array(
						'fields' => array('Flota.id'),
						'conditions' => array('LOWER(TRIM(Flota.nombre))' => strtolower(trim($temp["Flota"]))),
						'recursive'=>-1
					));
				if(isset($r["Flota"]["id"])){
					$flota_id = $r["Flota"]["id"];
				}
				$r = $this->Motor->find('first', array(
						'fields' => array('Motor.id'),
						'conditions' => array('LOWER(TRIM(Motor.nombre))' => strtolower(trim($temp["Motor"]))),
						'recursive'=>-1
					));
				if(isset($r["Motor"]["id"])){
					$motor_id = $r["Motor"]["id"];
				}
				$sql = "INSERT INTO esn (faena_id,flota_id,motor_id,unidad,esn,fecha_inicio,fecha_termino,e) VALUES ($faena_id,$flota_id,$motor_id,'{$temp["Unidad"]}','{$temp["ESN"]}','{$temp["FechaInicio"]}',{$temp["FechaTermino"]},'1');";
				//echo $sql;
				
				try {
					@$db->query($sql);
				} catch (Exception $e) {
					//echo 'Excepción capturada: ',  $e->getMessage(), "\n";
				}
			}
		}
		$this->redirect('/Herramientas/esn/');
	}
	public function esn() {
		$this->set('titulo', 'Cargar Archivo Estado Motores');
		$data = array();
		$this->loadModel('Unidad');
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		$this->loadModel('Motor');
		if (count($this->request->data)) {
			$esn_valido = 0;
			$data = $this->request->data;
			$data = $data["CargaEstadoMotores"]["EstadoMotores"];
			if ($data["error"] == 0 && $data["size"] > 0) {
				//$file = new File($data["tmp_name"], false, 0644);
				$new_file = "/tmp/sg_" . time();
				if (copy($data["tmp_name"], $new_file)) {
					$this->set('archivo', $new_file);
				}
				$this->Session->write('data_form', NULL);
				$fh = fopen($data["tmp_name"],'r');
				//$data = $file->read(true, 'r');
				//$data = iconv('ISO-8859-1','UTF-8', $data);
				//$tmp = explode("\n", $data);
				$data = array();
				$data_form = array();
				
				$faena = '';
				$faena_id = 0;
				//unset($tmp[0]);
				$i=0;
				
				$meses = array(".","ene","feb","mar","abr","may","jun","jul","ago","sep","oct","nov","dic");
				$meses0 = array("","01","02","03","04","05","06","07","08","09","10","11","12");
				$meses1 = array(".","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
				$meses2 = array(".","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
				
				while ($fila = fgets($fh)) {
					$tmp2 = explode(";", $fila);
					/*if(trim($tmp2[1])==''){
						continue;
					}*/
					$i++;
					$temp["td"] = '';
					$temp["Accion"] = "";
					$temp["Faena"] = @$tmp2[1]; 
					$temp["Motor"] = @$tmp2[9];
					$temp["Flota"] = @$tmp2[3];
					$temp["Unidad"] = @$tmp2[6];
					$temp["FechaInicio"] = @$tmp2[15];
					$temp["FechaTermino"] = @$tmp2[21];
					$temp["ESN"] = @$tmp2[7];
					
					if($temp["Faena"]==''||$temp["Motor"]==''||$temp["Flota"]==''||$temp["Unidad"]==''||$temp["FechaInicio"]==''||$temp["ESN"]==''){
						continue;
					}
					if($temp["Faena"]=='S/I'||$temp["Motor"]=='S/I'||$temp["Flota"]=='S/I'||$temp["Unidad"]=='S/I'||$temp["FechaInicio"]=='S/I'||$temp["ESN"]=='S/I'){
						continue;
					}
					
					$temp["FechaInicio"] = str_replace($meses,$meses0,$temp["FechaInicio"]);
					$temp["FechaInicio"] = str_replace($meses1,$meses0,$temp["FechaInicio"]);
					$temp["FechaInicio"] = str_replace($meses2,$meses0,$temp["FechaInicio"]);
					if($temp["FechaTermino"]!=''){
						$temp["FechaTermino"] = str_replace($meses,$meses0,$temp["FechaTermino"]);
						$temp["FechaTermino"] = str_replace($meses1,$meses0,$temp["FechaTermino"]);
						$temp["FechaTermino"] = str_replace($meses2,$meses0,$temp["FechaTermino"]);
					}
					//$temp["FechaInicio"] = strtotime($temp["FechaInicio"]);
					//$temp["FechaInicio"] = date("d-m-Y", $temp["FechaInicio"]);
					
					/*
					$r = $this->Unidad->find('first', array(
						'fields' => array('Unidad.*'),
						'conditions' => array('LOWER(TRIM(Faena.nombre))' => strtolower(trim($temp["Faena"])),
											  'LOWER(TRIM(Motor.nombre))' => strtolower(trim($temp["Motor"])),
											  'LOWER(TRIM(Flota.nombre))' => strtolower(trim($temp["Flota"])),
											   'LOWER(TRIM(Unidad.unidad))' => strtolower(trim($temp["Unidad"]))),
						'recursive'=>1
					));
					if(!isset($r["Unidad"])){
						$temp["Estado"]="No existe";
					}else{
						$temp["Accion"] = "<input type=\"checkbox\" name=\"actualizar[]\" class=\"matriz_opciones\" value=\"$i\" title=\"Marcar para actualizar\" />";
						$temp["Estado"]="Actualizar";
					}*/
					
					$data[] = $temp;
				}
				fclose($fh);

			}
		}
		
		$this->set('faenas', $this->Faena->find('all', array('order' => 'nombre','conditions'=>array("Faena.e='1'"), 'recursive' => -1)));
		$this->set('flotas', $this->Flota->find('all', array('order' => 'nombre','conditions'=>array("Flota.e='1'"), 'recursive' => -1)));
		$this->set('motores', $this->Motor->find('all', array('order' => 'nombre','conditions'=>array("Motor.e='1'"), 'recursive' => -1)));
		$this->set('data', $data);
	}
	/* Este metodo define el procesamiento para manipular el archivo estado de motores */
	public function estadomotores() {
		$this->set('titulo', 'Cargar Archivo Estado Motores');
		$data = array();
		$this->loadModel('Unidad');
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		$this->loadModel('Motor');
		if (count($this->request->data)) {
			$esn_valido = 0;
			$data = $this->request->data;
			$data = $data["CargaEstadoMotores"]["EstadoMotores"];
			if ($data["error"] == 0 && $data["size"] > 0) {
				$file = new File($data["tmp_name"], false, 0644);
				$new_file = "/tmp/sg_" . time();
				if (copy($data["tmp_name"], $new_file)) {
					$this->set('archivo', $new_file);
				}
				$this->Session->write('data_form', NULL);
				$data = $file->read(true, 'r');
				$data = iconv('ISO-8859-1','UTF-8', $data);
				$tmp = explode("\n", $data);
				$data = array();
				$data_form = array();
				
				$faena = '';
				$faena_id = 0;
				unset($tmp[0]);
				$i=0;
				$idexistentes=array();
				foreach($tmp as $fila) {
					$tmp2 = explode(";", $fila);
					if(trim($tmp2[2])==''){
						continue;
					}
					if(strtoupper(trim($tmp2[8]))!='OPERANDO'){
						continue;
					}
					$i++;
					$temp["td"] = '';
					$temp["Accion"] = "";
					/*$temp["Faena"] = $tmp2[2]; 
					$temp["Motor"] = $tmp2[19];
					$temp["Flota"] = $tmp2[13];
					$temp["Unidad"] = $tmp2[4];
					$temp["Horometro"] = str_replace(".","", $tmp2[9]);
					$temp["Horometro"] = str_replace(",",".", $tmp2[9]);
					$temp["ESN"] = $tmp2[5];
					$temp["ModeloMotor"] = $tmp2[25];
					$temp["FabricanteEquipo"] = $tmp2[24];
					$temp["ModeloEquipo"] = $tmp2[14];
					$temp["SerieEquipo"] = $tmp2[15];
					$temp["TipoEquipo"] = $tmp2[3];
					*/
					$temp["Faena"] = $tmp2[3]; 
					$temp["Motor"] = $tmp2[21];
					$temp["Flota"] = $tmp2[5];
					$temp["Unidad"] = $tmp2[6];
					$temp["Horometro"] = str_replace(".","", $tmp2[12]);
					$temp["Horometro"] = str_replace(",",".", $temp["Horometro"] );
					//$temp["Horometro"] = $tmp2[12];
					$temp["ESN"] = $tmp2[7];
					$temp["ModeloMotor"] = $tmp2[22];
					$temp["FabricanteEquipo"] = $tmp2[15];
					$temp["ModeloEquipo"] = $tmp2[16];
					$temp["SerieEquipo"] = $tmp2[17];
					$temp["TipoEquipo"] = $tmp2[4];
					$r = $this->Unidad->find('first', array(
						'fields' => array('Unidad.*'),
						'conditions' => array('LOWER(TRIM(Faena.nombre))' => strtolower(trim($temp["Faena"])),
											  'LOWER(TRIM(Motor.nombre))' => strtolower(trim($temp["Motor"])),
											  'LOWER(TRIM(Flota.nombre))' => strtolower(trim($temp["Flota"])),
											   'LOWER(TRIM(Unidad.unidad))' => strtolower(trim($temp["Unidad"]))),
						'recursive'=>1
					));
					if(!isset($r["Unidad"])){
						//$temp["td"] = 'background-color: yellow; color: black;';
						$temp["Accion"] = "<input type=\"checkbox\" name=\"agregar[]\" class=\"matriz_opciones\" value=\"$i\" title=\"Marcar para agregar\" />";
						$temp["Estado"]="Nuevo";
					}else{
						$idexistentes[]=$r["Unidad"]["id"];
						//$temp["Estado"]="Ambos";
						if(!is_numeric($temp["Horometro"])){
							$temp["Horometro"]="0";
						}
						$temp["Horometro"]=number_format($temp["Horometro"],2,".","");
						if($r["Unidad"]["horometro"]!=$temp["Horometro"]||$r["Unidad"]["esn"]!=$temp["ESN"]||$r["Unidad"]["modelo_equipo"]!=$temp["ModeloEquipo"]||$r["Unidad"]["modelo_motor"]!=$temp["ModeloMotor"]||$r["Unidad"]["nserie"]!=$temp["SerieEquipo"]||$r["Unidad"]["aplicacion"]!=$temp["TipoEquipo"]||$r["Unidad"]["fabricante"]!=$temp["FabricanteEquipo"]){
							$temp["Accion"] = "<input type=\"checkbox\" name=\"actualizar[]\" class=\"matriz_opciones\" value=\"$i\" title=\"Marcar para actualizar\" />";
							$temp["Estado"]="Nuevo";
						}else{
							$temp["Estado"]="Ambos";	
						}
					}
					
					$data[] = $temp;
				}
				
				$idexistentes[]="0";
				$idexi=implode(",",$idexistentes);
				$resultado = $this->Unidad->find('all', array(
					'conditions' => array("Unidad.id NOT IN ($idexi)"),
					'fields'=>array('Unidad.*','Faena.nombre','Motor.nombre','Flota.nombre'),
					'recursive'=>1
				));
				foreach($resultado as $unidad){
					if(isset($unidad["Unidad"]["unidad"])&&$unidad["Unidad"]["unidad"]!=""){
						$temp = array();
						$temp["td"] = '';
						$temp["class"]="";
						$temp["Faena"] = $unidad["Faena"]["nombre"];
						$temp["Motor"] = $unidad["Motor"]["nombre"];
						$temp["Flota"] = $unidad["Flota"]["nombre"];
						$temp["Unidad"] = $unidad["Unidad"]["unidad"];
						$temp["Horometro"] = $unidad["Unidad"]["horometro"];
						$temp["ESN"] = $unidad["Unidad"]["esn"];
						$temp["ModeloMotor"] = $unidad["Unidad"]["modelo_motor"];
						$temp["FabricanteEquipo"] = $unidad["Unidad"]["fabricante"];
						$temp["ModeloEquipo"] = $unidad["Unidad"]["modelo_equipo"];
						$temp["SerieEquipo"] = $unidad["Unidad"]["nserie"];
						$temp["TipoEquipo"] = $unidad["Unidad"]["aplicacion"];
						/*
						$temp["Rut"] = $usuario["Usuario"]["usuario"];
						$temp["Nombres"] = $usuario["Usuario"]["nombres"];
						$temp["Apellidos"] = $usuario["Usuario"]["apellidos"];
						$temp["Faena"] = $usuario["Faena"]["nombre"];
						$temp["Correo"] = $usuario["Usuario"]["correo_electronico"];
						$temp["Cargo"] = $usuario["Nivel"]["nombre"];
						*/
						$temp["Estado"] = $unidad["Unidad"]["e"]==1?"Activo":"Inactivo";
						$temp["Accion"] = "<input type=\"checkbox\" name=\"quitar[]\" class=\"matriz_opciones\" value=\"".$unidad["Unidad"]["id"]."\" />";
						$data[] = $temp;
					}
				}
			}
		}
		
		$this->set('faenas', $this->Faena->find('all', array('order' => 'nombre','conditions'=>array("Faena.e='1'"), 'recursive' => -1)));
		$this->set('flotas', $this->Flota->find('all', array('order' => 'nombre','conditions'=>array("Flota.e='1'"), 'recursive' => -1)));
		$this->set('motores', $this->Motor->find('all', array('order' => 'nombre','conditions'=>array("Motor.e='1'"), 'recursive' => -1)));
		$this->set('data', $data);
	}
	
	public function verificarEstado() {
		$this->loadModel('Unidad');
		$this->loadModel('Faena');
		$this->loadModel('Flota');
		$this->loadModel('Motor');
		$file = new File($this->request->data["Herramientas"]["hash_file"], false, 0644);
		$data = $file->read(true, 'r');
		$tmp = explode("\n", $data);
		$this->layout=null;
		$i = 0;
		unset($tmp[0]);
		if ((isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"]))||(isset($this->request->data["actualizar"]) && is_array($this->request->data["actualizar"]))) {
			foreach($tmp as $fila) {
				$tmp2 = explode(";", utf8_encode($fila));
				if(trim($tmp2[2])==''){
					continue;
				}
				if(strtoupper(trim($tmp2[8]))!='OPERANDO'){
					continue;
				}
				$i++;
				if (isset($this->request->data["agregar"])&&is_array($this->request->data["agregar"])&&array_search($i, $this->request->data["agregar"]) !== FALSE) {
					$temp["faena_id"] = -1; 
					$temp["motor_id"] = -1;
					$temp["flota_id"] = -1;
					$r=$this->Faena->find('first', array('conditions' => array('TRIM(LOWER(nombre))' => trim(strtolower($tmp2[3])))));
					if(isset($r["Faena"])){
						$temp["faena_id"] = $r["Faena"]["id"];
					}else{
						$data=array(
							'nombre'=>$tmp2[3],
							'e'=>'1'
						);
						$this->Faena->create();
						$this->Faena->save($data);
						$temp["faena_id"]=$this->Faena->id;
					}
					$r=$this->Motor->find('first', array('conditions' => array('TRIM(LOWER(nombre))' => trim(strtolower($tmp2[21])))));
					if(isset($r["Motor"])){
						$temp["motor_id"] = $r["Motor"]["id"];
					}else{
						$data=array(
							'nombre'=>$tmp2[21],
							'e'=>'1'
						);
						$this->Motor->create();
						$this->Motor->save($data);
						$temp["motor_id"]=$this->Motor->id;
					}
					$r=$this->Flota->find('first', array('conditions' => array('TRIM(LOWER(nombre))' => trim(strtolower($tmp2[5])))));
					if(isset($r["Flota"])){
						$temp["flota_id"] = $r["Flota"]["id"];
					}else{
						$data=array(
							'nombre'=>$tmp2[5],
							'e'=>'1'
						);
						$this->Flota->create();
						$this->Flota->save($data);
						$temp["flota_id"]=$this->Flota->id;
					}
					
					
					$temp["unidad"] = $tmp2[6];
					$temp["horometro"] = str_replace(",",".", $tmp2[12]);
					$temp["esn"] = $tmp2[7];
					$temp["modelo_motor"] = $tmp2[22];
					$temp["fabricante"] = $tmp2[15];
					$temp["modelo_equipo"] = $tmp2[16];
					$temp["nserie"] = $tmp2[17];
					$temp["aplicacion"] = $tmp2[4];
					
					if(!is_numeric($temp["horometro"])){
						$temp["horometro"]="0";
					}
					$temp["horometro"]=number_format($temp["horometro"],2,".","");
					try {
						$this->Unidad->create();
						$this->Unidad->save($temp);
					}catch(Exception $e){}
				}elseif(isset($this->request->data["actualizar"])&&is_array($this->request->data["actualizar"])&&array_search($i, $this->request->data["actualizar"]) !== FALSE){
					$temp["Faena"] = $tmp2[3]; 
					$temp["Motor"] = $tmp2[21];
					$temp["Flota"] = $tmp2[5];
					$temp["Unidad"] = $tmp2[6];
					$temp["Horometro"] = str_replace(".","", $tmp2[12]);
					$temp["Horometro"] = str_replace(",",".", $temp["Horometro"] );
					//$temp["Horometro"] = str_replace(",",".", $tmp2[12]);
					//$temp["Horometro"] = $tmp2[12];
					$temp["ESN"] = $tmp2[7];
					$temp["ModeloMotor"] = $tmp2[22];
					$temp["FabricanteEquipo"] = $tmp2[15];
					$temp["ModeloEquipo"] = $tmp2[16];
					$temp["SerieEquipo"] = $tmp2[17];
					$temp["TipoEquipo"] = $tmp2[4];
					
					/*$temp["Faena"] = $tmp2[2]; 
					$temp["Motor"] = $tmp2[19];
					$temp["Flota"] = $tmp2[13];
					$temp["Unidad"] = $tmp2[4];
					$temp["Horometro"] = str_replace(",",".", $tmp2[9]);
					$temp["ESN"] = $tmp2[5];
					$temp["ModeloMotor"] = $tmp2[25];
					$temp["FabricanteEquipo"] = $tmp2[24];
					$temp["ModeloEquipo"] = $tmp2[14];
					$temp["SerieEquipo"] = $tmp2[15];
					$temp["TipoEquipo"] = $tmp2[3];
					*/
					if(!is_numeric($temp["Horometro"])){
						$temp["Horometro"]="0";
					}
					$temp["Horometro"]=number_format($temp["Horometro"],2,".","");
					$r = $this->Unidad->find('first', array(
						'fields' => array('Unidad.id'),
						'conditions' => array('LOWER(TRIM(Faena.nombre))' => strtolower(trim($temp["Faena"])),
											  'LOWER(TRIM(Motor.nombre))' => strtolower(trim($temp["Motor"])),
											  'LOWER(TRIM(Flota.nombre))' => strtolower(trim($temp["Flota"])),
											  'LOWER(TRIM(Unidad.unidad))' => strtolower(trim($temp["Unidad"]))),
						'recursive'=>1
					));
					if(isset($r["Unidad"])){
						$data=array(
							'id'=>$r["Unidad"]["id"],
							'horometro'=>$temp["Horometro"],
							'esn'=>$temp["ESN"],
							'modelo_motor'=>$temp["ModeloMotor"],
							'fabricante'=>$temp["FabricanteEquipo"],
							'modelo_equipo'=>$temp["ModeloEquipo"],
							'nserie'=>$temp["SerieEquipo"],
							'aplicacion'=>$temp["TipoEquipo"],
						);
						try {
							$this->Unidad->save($data);
						}catch(Exception $e){}
					}
				}
			}
		}
		
		if(isset($this->request->data["quitar"])&&is_array($this->request->data["quitar"])&&count($this->request->data["quitar"])>0){
			foreach($this->request->data["quitar"] as $key=>$value){
				try{
					$resultado=$this->Unidad->find('first',array(
						'fields'=>array('Unidad.id','Unidad.e'),
						'conditions'=>array('Unidad.id'=>$value),
						'recursive'=>-1
					));
					if(isset($resultado["Unidad"])&&isset($resultado["Unidad"]["id"])){
						$data=array();
						$data["id"]=$resultado["Unidad"]["id"];
						if($resultado["Unidad"]["e"]=="1"){
							$data["e"]="0";
						}else{
							$data["e"]="1";
						}
						$this->Unidad->save($data);
					}
				}catch(Exception $e){}
			}
		}
		
		$this->Session->setFlash('Estado de Motores actualizado de éxito!', 'guardar_exito');
		$this->redirect('/Herramientas/EstadoMotores/');
	}
	
	public function basedatos() {
		$this->set('titulo', 'Base de Datos');
		$faena_id = $this->Session->read('faena_id');
		$this->set('faena_id', $faena_id);
		if (count($this->request->data)) {
			$this->loadModel('Planificacion');
			$this->loadModel('Trabajo');
			$data = $this->request->data;
			$this->Planificacion->deleteAll(array('Planificacion.faena_id' => $faena_id), false);
			$this->Trabajo->deleteAll(array('Trabajo.faena_id' => $faena_id), false);
			$this->Session->setFlash('Base de datos vaciada con exito!', 'guardar_exito');
		}
	}
	/* Este metodo define el procesamiento para manipular el archivo de dotacion */
	public function cargardotacion() {
		$this->set('titulo', 'Cargar Dotación');
		$this->loadModel('Usuario');
		$this->loadModel('UsuarioNivel');
		$this->loadModel('Faena');
		$this->loadModel('UsuarioFaena');
		$data = array();
		if (count($this->request->data)) {
			$data = $this->request->data;
			$data = $data["CargaArchivoUsuarios"]["ArchivoUsuarios"];
			if ($data["error"] == 0 && $data["size"] > 0) {
				$this->Session->write('data_form', NULL);
				$file = new File($data["tmp_name"], false, 0644);
				$new_file = "/tmp/sg_" . time();
				if (copy($data["tmp_name"], $new_file)) {
					$this->set('archivo', $new_file);
				}
				$data = $file->read(true, 'r');
				//$data = iconv('ISO-8859-1','UTF-8', $data);
				$tmp = explode("\n", $data);
				$data = array();
				$data_form = array();
				
				$faena = '';
				$faena_id = 0;
				$i = 0;
				$idexistentes=array();
				$idadmins=array();
				unset($tmp[0]);
				//print_r($tmp);
				//exit;
				foreach($tmp as $fila) {
					$i++;
					$datos = explode(';', $fila);
					if(count($datos) < 3){
						$datos = explode(',', $fila);
					}
					if(trim($datos[0])==''){continue;}
					//print_r($datos);
					//continue;
					$temp = array();
					//RUT;NOMBRES;APELLIDOS;FAENA;CORREO;PERFIL
					$temp["td"] = '';
					$temp["class"] = '';
					$temp["Rut"] = $datos[0];
					$temp["Nombres"] = $datos[1];
					$temp["Apellidos"] = $datos[2];
					$temp["Faena"] = $datos[3];
					$temp["Correo"] = $datos[4];
					$temp["Cargo"] = $datos[5];
					$x=implode(" x",explode(" ",strtoupper($temp["Nombres"])));
					$y=implode(" y",explode(" ",strtoupper($temp["Apellidos"])));
					
					$temp["class"] .= ' x'.$x.' ';
					$temp["class"] .= ' y'.$y.' ';
					$nivel_id = -1;
					if (strpos(strtolower($datos[5]),'te') !== false) {
						$temp["Cargo"] = "Técnico";
						$nivel_id = 1;
					} elseif (strpos(strtolower($datos[5]),'sd') !== false) {
						$temp["Cargo"] = "Supervisor DCC";
						$nivel_id = 2;
					} elseif (strpos(strtolower($datos[5]),'ad') !== false) {
						$temp["Cargo"] = "Administrador";
						$nivel_id = 4;
					} elseif (strpos(strtolower($datos[5]),'ge') !== false) {
						$temp["Cargo"] = "Gestión";
						$nivel_id = 5;
					} elseif (strpos(strtolower($datos[5]),'sc') !== false) {
						$temp["Cargo"] = "Supervisor Cliente";
						$nivel_id = 3;
					} elseif (strpos(strtolower($datos[5]),'at') !== false) {
						$temp["Cargo"] = "Asesor Técnico";
						$nivel_id = 6;
					} elseif (strpos(strtolower($datos[5]),'pl') !== false) {
						$temp["Cargo"] = "Planificador";
						$nivel_id = 7;
					}
					$temp["class"].=" n$nivel_id"."n ";
					
					//Se verifica existencia del usuario, sin perfiles ni faenas
					
					$usuario = split("-", $temp["Rut"]);
					$resultado = $this->Usuario->find('first', array(
						'conditions' => array('usuario' => $usuario[0]),
						'recursive' => -1
					));				
					$temp["Rut"]=$usuario[0];					
					$usuario_id = -1;
					$temp["class"].=" u{$usuario[0]}"."u ";
					//$temp["class"].=" e2e ";
					if (isset($resultado["Usuario"])) {
						$usuario_id = $resultado["Usuario"]["id"];
						$temp["Ubicacion"] = '';
						$temp["Accion"] = '';
						//echo $usuario[0]." usu";
					} else {
						$temp["td"] = 'background-color: yellow; color: black;';
						$temp["Ubicacion"] = '';
						$temp["Accion"] = "<input type=\"checkbox\" name=\"agregar[]\" class=\"matriz_opciones estado2\" value=\"$i\" title=\"Marcar para agregar\" />";
						//echo $usuario[0]." no usu";
					}
					
					//if($nivel_id!=1){
						$resultado = $this->UsuarioNivel->find('first', array(
							'fields'=>array('UsuarioNivel.*','Usuario.usuario'),
							'conditions' => array('nivel_id' => $nivel_id, 'usuario_id' => $usuario_id, 'UsuarioNivel.e'=>'1'),
							'recursive' => 1
						));
						if (!isset($resultado["UsuarioNivel"])) {
							$temp["td"] = 'background-color: yellow; color: black;';
							$temp["Accion"] = "<input type=\"checkbox\" name=\"agregar[]\" class=\"matriz_opciones estado2\" value=\"$i\" title=\"Marcar para agregar\" />";
							//echo $usuario[0]." no nivel";
							if($nivel_id==4){
								$temp["td"] = 'background-color: yellow; color: black;';
								$temp["Accion"] = "<input type=\"checkbox\" name=\"agregar[]\" class=\"matriz_opciones estado2\" value=\"$i\" title=\"Marcar para agregar\" />";
								$temp["class"].=" e2e ";
								$temp["Estado"]="Nuevo";
								$temp["Faena"] = "Todas";
							}
						}else{
							if($nivel_id==4){
								$temp["td"] = '';
								$temp["Accion"] = "";
								$temp["class"].=" e3e ";
								$temp["Estado"]="Ambos";
								$temp["Faena"] = "Todas";
								$idadmins[]=$resultado["UsuarioNivel"]["id"];
								$temp["class"].=" ff ";
								$temp["class"].=" n4n  ";
								$temp["class"].=" u{$resultado["Usuario"]["usuario"]}u ";
							}
						}
					//}
					
					if($nivel_id!=4){
						$resultado = $this->Faena->find('first', array(
							'fields'=>array('Faena.id'),
							'conditions' => array('TRIM(LOWER(nombre))' => trim(strtolower($temp["Faena"]))),
							'recursive'=>-1
						));
						if (isset($resultado['Faena'])) {
							$faena_id=$resultado['Faena']["id"];
							$resultado = $this->UsuarioFaena->find('first', array(
								'conditions' => array('faena_id' => $resultado['Faena']["id"],'usuario_id'=>$usuario_id,'nivel_id'=>$nivel_id)
							));
							$temp["class"].=" f$faena_id"."f ";
							if (!isset($resultado['UsuarioFaena'])||(isset($resultado['UsuarioFaena'])&&$resultado['UsuarioFaena']["e"]=='0')) {
								$temp["td"] = 'background-color: yellow; color: black;';
								$temp["Accion"] = "<input type=\"checkbox\" name=\"agregar[]\" class=\"matriz_opciones estado2\" value=\"$i\" title=\"Marcar para agregar\" />";
								//echo $usuario[0]." no faena";
								$temp["class"].=" e2e ";
								$temp["Estado"]="Nuevo";
							}else{
								$temp["td"] = '';
								$temp["Accion"] = "";
								$temp["class"].=" e3e ";
								$temp["Estado"]="Ambos";
								$idexistentes[]=$resultado['UsuarioFaena']["id"];
								//continue;
							}
						} else {
							$temp["td"] = 'background-color: yellow; color: black;';
						}
					}else{
						/*$temp["td"] = '';
						$temp["class"]="";
						$temp["class"].=" e{$resultado["UsuarioNivel"]["e"]}e ";
						$temp["class"].=" n{$resultado["UsuarioNivel"]["nivel_id"]}n  ";
						$temp["class"].=" ff ";
						$temp["class"].=" u{$resultado["Usuario"]["usuario"]}u ";*/
					}
					//$temp["Estado"]="Nuevo";
					$data[] = $temp;
				}
				$idexistentes[]="0";
				//print_r($idexistentes);  
				$idexi=implode(",",$idexistentes);
				$resultado = $this->UsuarioFaena->find('all', array(
					'conditions' => array("UsuarioFaena.id NOT IN ($idexi) AND UsuarioFaena.nivel_id<>4"),
					'fields'=>array('UsuarioFaena.id','UsuarioFaena.e','Faena.nombre','Faena.id','Nivel.nombre','Nivel.id','Usuario.usuario','Usuario.nombres','Usuario.apellidos','Usuario.correo_electronico'),
					'recursive'=>1
				));
				foreach($resultado as $usuario){
					if(isset($usuario["Usuario"]["usuario"])&&$usuario["Usuario"]["usuario"]!=""){
						$temp = array();
						$temp["td"] = '';
						$temp["class"]="";
						$temp["class"].=" e{$usuario["UsuarioFaena"]["e"]}e ";
						$temp["class"].=" n{$usuario["Nivel"]["id"]}n  ";
						$temp["class"].=" f{$usuario["Faena"]["id"]}f ";
						$temp["class"].=" u{$usuario["Usuario"]["usuario"]}u ";
						$temp["Rut"] = $usuario["Usuario"]["usuario"];
						$temp["Nombres"] = $usuario["Usuario"]["nombres"];
						$temp["Apellidos"] = $usuario["Usuario"]["apellidos"];
						$temp["Faena"] = $usuario["Faena"]["nombre"];
						$temp["Correo"] = $usuario["Usuario"]["correo_electronico"];
						$temp["Cargo"] = $usuario["Nivel"]["nombre"];
						$temp["Estado"] = $usuario["UsuarioFaena"]["e"]==1?"Activo":"Inactivo";
						$temp["Accion"] = "<input type=\"checkbox\" name=\"quitar_uf[]\" class=\"matriz_opciones estado{$usuario["UsuarioFaena"]["e"]}\" value=\"".$usuario["UsuarioFaena"]["id"]."\" title=\"Marcar para quitar\" />";
						$data[] = $temp;
					}
				}
				
				$idadmins[]="0";
				//print_r($idexistentes);  
				$idexi=implode(",",$idadmins);
				$resultado = $this->UsuarioNivel->find('all', array(
					'conditions' => array("UsuarioNivel.id NOT IN ($idexi) AND UsuarioNivel.nivel_id=4"),
					'fields'=>array('UsuarioNivel.id','UsuarioNivel.e','Nivel.nombre','Nivel.id','Usuario.usuario','Usuario.nombres','Usuario.apellidos','Usuario.correo_electronico'),
					'recursive'=>1
				));
				foreach($resultado as $usuario){
					if(isset($usuario["Usuario"]["usuario"])&&$usuario["Usuario"]["usuario"]!=""){
						$temp = array();
						$temp["td"] = '';
						$temp["class"]="";
						$temp["class"].=" e{$usuario["UsuarioNivel"]["e"]}e ";
						$temp["class"].=" n{$usuario["Nivel"]["id"]}n  ";
						$temp["class"].=" ff ";
						$temp["class"].=" u{$usuario["Usuario"]["usuario"]}u ";
						$temp["Rut"] = $usuario["Usuario"]["usuario"];
						$temp["Nombres"] = $usuario["Usuario"]["nombres"];
						$temp["Apellidos"] = $usuario["Usuario"]["apellidos"];
						$temp["Faena"] = "Todas";
						$temp["Correo"] = $usuario["Usuario"]["correo_electronico"];
						$temp["Cargo"] = $usuario["Nivel"]["nombre"];
						$temp["Estado"] = $usuario["UsuarioNivel"]["e"]==1?"Activo":"Inactivo";
						$temp["Accion"] = "<input type=\"checkbox\" name=\"quitar_un[]\" class=\"matriz_opciones estado{$usuario["UsuarioNivel"]["e"]}\" value=\"".$usuario["UsuarioNivel"]["id"]."\" title=\"Marcar para quitar\" />";
						$data[] = $temp;
					}
				}
			}
		}
		$this->set('faenas', $this->Faena->find('all', array('order' => 'nombre','conditions'=>array("Faena.e='1'"), 'recursive' => -1)));
		$this->set('data', $data);
	}
	
	public function verificarEstadoDotacion() {
		$this->loadModel('Usuario');
		$this->loadModel('Faena');
		$this->loadModel('UsuarioFaena');
		$this->loadModel('UsuarioNivel');
		$this->layout = null;
		if (count($this->request->data)) {
			$db = ConnectionManager::getDataSource('default');
			$file = new File($this->request->data["Herramientas"]["hash_file"], false, 0644);
			$data = $file->read(true, 'r');
			$tmp = explode("\n", $data);
			$i = 0;
			unset($tmp[0]);
			foreach($tmp as $fila) {
				$i++;
				if(true){
					$datos = explode(';', $fila);
					if(count($datos) < 3){
						$datos = explode(',', $fila);
					}
					if(trim($datos[0])==''){continue;}
					$temp["Rut"]=$datos[0];
					$temp["Correo"]=trim($datos[4]);
					if($temp["Correo"]!=""){
						$usuario=split("-", $temp["Rut"]);
						$resultado = $this->Usuario->find('first', array(
							'fields'=>array('Usuario.id'),
							'conditions' => array("Usuario.usuario={$usuario[0]}"),
							'recursive' => -1
						));
						if(isset($resultado["Usuario"])&&isset($resultado["Usuario"]["id"])){
							$data=array();
							$data["id"]=$resultado["Usuario"]["id"];
							$data["correo_electronico"]=$temp["Correo"];
							$this->Usuario->save($data);
						}
					}
				}
				if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"]) && count($this->request->data["agregar"]) > 0) {
					if (array_search($i, $this->request->data["agregar"]) !== FALSE) {
						$datos = explode(';', $fila);
						if(count($datos) < 3){
							$datos = explode(',', $fila);
						}
						$temp = array();
						$temp["td"] = '';
						$temp["Rut"] = $datos[0];
						$temp["Nombres"] = $datos[1];
						$temp["Apellidos"] = $datos[2];
						$temp["Faena"] = $datos[3];
						$temp["Correo"] = $datos[4];
						$temp["Cargo"] = $datos[5];
						$usuario = split("-", $temp["Rut"]);
						
						// Se obtiene ID de nivel
						$nivel_id = -1;
						if (strpos(strtolower($datos[5]),'te') !== false) {
							$nivel_id = 1;
						} elseif (strpos(strtolower($datos[5]),'sd') !== false) {
							$nivel_id = 2;
						} elseif (strpos(strtolower($datos[5]),'ad') !== false) {
							$nivel_id = 4;
						} elseif (strpos(strtolower($datos[5]),'ge') !== false) {
							$nivel_id = 5;
						} elseif (strpos(strtolower($datos[5]),'sc') !== false) {
							$nivel_id = 3;
						} elseif (strpos(strtolower($datos[5]),'at') !== false) {
							$temp["Cargo"] = "Asesor Técnico";
							$nivel_id = 6;
						} elseif (strpos(strtolower($datos[5]),'pl') !== false) {
							$temp["Cargo"] = "Planificador";
							$nivel_id = 7;
						}
						
						$resultado = $this->Faena->find('first', array(
							'fields'=>array('Faena.id'),
							'conditions' => array('TRIM(LOWER(nombre))' => trim(strtolower($temp["Faena"]))),
							'recursive'=>-1
						));
						
						if (isset($resultado["Faena"])) {
							$faena_id = $resultado["Faena"]["id"];
						} else {
							$faena_id = -1;
						}
						
						$resultado = $this->Usuario->find('first', array(
							'fields'=>array('Usuario.id'),
							'conditions' => array('usuario' => $usuario[0]),
							'recursive' => -1
						));
						
						//Se obtiene la data del usuario, en caso de no existir, se crea para obtener usuario_id
						if(isset($resultado["Usuario"])){
							$usuario_id = $resultado["Usuario"]["id"];
						}else{
							$pin = md5(substr($usuario[0], -4));
							$data = array('usuario' => $usuario[0], 'pin' => $pin, 'nivelusuario_id' => $nivel_id, 'apellidos' => $temp["Apellidos"], 'nombres' => $temp["Nombres"], 'correo_electronico' => $temp["Correo"], 'e' => '1');
							$this->Usuario->create();
							$this->Usuario->save($data);
							$usuario_id = $this->Usuario->id;
						}
						
						// Actualizamos o insertamos la relacion usuario-nivel
						try{
							//Si el nivel nuevo es administrador, se quitan todos los otros permisos, si es técnico, tambien se le quitan todos los otros permisos.
							if($nivel_id==4||$nivel_id==1||$nivel_id==6||$nivel_id==3){
								$sql="UPDATE usuario_nivel SET e='0',correo='0' WHERE usuario_id=$usuario_id;";
								$db->query($sql);
							}
							//Si el nivel es supervisor dcc, supervisor cliente o gestion, se quitan los permisos de tecnico y administrador de forma automatica.
							if($nivel_id==2||$nivel_id==3||$nivel_id==5||$nivel_id==6){
								$sql="UPDATE usuario_nivel SET e='0',correo='0' WHERE usuario_id=$usuario_id AND nivel_id=4;";
								$db->query($sql);
								$sql="UPDATE usuario_nivel SET e='0',correo='0' WHERE usuario_id=$usuario_id AND nivel_id=1;";
								$db->query($sql);
							}
							
							// Planificador puede ser solo gestion
							if($nivel_id==7){
								$sql="UPDATE usuario_nivel SET e='0',correo='0' WHERE usuario_id=$usuario_id AND nivel_id NOT IN (5,6,7);";
								$db->query($sql);
							}
							
							if($nivel_id==2){
								$sql="UPDATE usuario_nivel SET e='0',correo='0' WHERE usuario_id=$usuario_id AND nivel_id NOT IN (5,2);";
								$db->query($sql);
							}
							
							if($nivel_id==5){
								$sql="UPDATE usuario_nivel SET e='0' WHERE usuario_id=$usuario_id AND nivel_id NOT IN (7,2,5);";
								$db->query($sql);
							}
							
							$sql="UPDATE usuario_nivel SET e='1',correo='1' WHERE usuario_id=$usuario_id AND nivel_id=$nivel_id;";
							$db->query($sql);
							$sql="INSERT INTO usuario_nivel (usuario_id,nivel_id,e,correo) VALUES ($usuario_id,$nivel_id,'1','1');";
							$db->query($sql);
						}catch(Exception $e){
							//echo "<p>".$e->getMessage() . " SQL: ".@$sql ."</p>";
						}
						
						// Actualizamos o insertamos la relacion usuario-nivel-faena
						try{
							//Si el nivel nuevo es administrador, se quitan todos los otros permisos, si es técnico, tambien se le quitan todos los otros permisos.
							if($nivel_id==4||$nivel_id==1||$nivel_id==3){
								$sql="UPDATE usuario_faena SET e='0' WHERE usuario_id=$usuario_id;";
								$db->query($sql);
							}
							//Si el nivel es supervisor dcc, supervisor cliente o gestion, se quitan los permisos de tecnico y administrador de forma automatica.
							if($nivel_id==2||$nivel_id==3||$nivel_id==5){
								$sql="UPDATE usuario_faena SET e='0' WHERE usuario_id=$usuario_id AND nivel_id=4;";
								$db->query($sql);
								$sql="UPDATE usuario_faena SET e='0' WHERE usuario_id=$usuario_id AND nivel_id=1;";
								$db->query($sql);
							}
							
							if($nivel_id==7){
								$sql="UPDATE usuario_faena SET e='0' WHERE usuario_id=$usuario_id AND nivel_id NOT IN (5,6,7);";
								$db->query($sql);
							}
							
							if($nivel_id==5){
								$sql="UPDATE usuario_faena SET e='0' WHERE usuario_id=$usuario_id AND nivel_id NOT IN (7,2,5);";
								$db->query($sql);
							}
							
							if($nivel_id==6){
								$sql="UPDATE usuario_faena SET e='0' WHERE usuario_id=$usuario_id AND nivel_id NOT IN (6,7);";
								$db->query($sql);
							}
							
							// Se asigna faena solo si el nivel es distinto a administrador
							if($nivel_id!=4){
								$sql="UPDATE usuario_faena SET e='1' WHERE usuario_id=$usuario_id AND nivel_id=$nivel_id AND faena_id=$faena_id;";
								$db->query($sql);
								$sql="INSERT INTO usuario_faena (usuario_id,nivel_id,faena_id,e) VALUES ($usuario_id,$nivel_id,$faena_id,'1');";
								$db->query($sql);
							}
						}catch(Exception $e){
							//echo "<p>".$e->getMessage() . " SQL: ".@$sql ."</p>";
						}
					}
				}	
			}
			if (isset($this->request->data["quitar_uf"]) && is_array($this->request->data["quitar_uf"]) && count($this->request->data["quitar_uf"]) > 0) {
				foreach($this->request->data["quitar_uf"] as $key => $value) {
					try{
						$resultado = $this->UsuarioFaena->find('first', array(
							'fields'=>array('UsuarioFaena.id','UsuarioFaena.e','UsuarioFaena.nivel_id','UsuarioFaena.usuario_id'),
							'conditions' => array('UsuarioFaena.id' => $value),
							'recursive' => -1
						));
						if(isset($resultado["UsuarioFaena"])){
							$data=array();
							$data["id"]=$resultado["UsuarioFaena"]["id"];
							if($resultado["UsuarioFaena"]["e"]=="1"){
								$data["e"]="0";
							}else{
								$data["e"]="1";
							}
							$this->UsuarioFaena->save($data);	
						}
						
						//$sql="UPDATE usuario_faena SET e='0' WHERE id=$value;";
						//$db->query($sql);
						// Validamos que si al usuario no le quedan faenas asignadas, se le quita el perfil
						$resultado2 = $this->UsuarioFaena->find('count', array(
							//'fields'=>array('UsuarioFaena.id'),
							'conditions' => array('UsuarioFaena.nivel_id' => $resultado["UsuarioFaena"]["nivel_id"], 'UsuarioFaena.usuario_id'=>$resultado["UsuarioFaena"]["usuario_id"],'UsuarioFaena.e'=>'1'),
							'recursive' => -1
						));
						if($resultado2==0){
							$sql="UPDATE usuario_nivel SET e='0',correo='0' WHERE usuario_id={$resultado["UsuarioFaena"]["usuario_id"]} AND nivel_id={$resultado["UsuarioFaena"]["nivel_id"]};";
							$db->query($sql);
						}else{
							$sql="UPDATE usuario_nivel SET e='1',correo='1' WHERE usuario_id={$resultado["UsuarioFaena"]["usuario_id"]} AND nivel_id={$resultado["UsuarioFaena"]["nivel_id"]};";
							$db->query($sql);
						}
						
						$resultado3 = $this->UsuarioNivel->find('count', array(
							'conditions' => array('UsuarioNivel.nivel_id' => $resultado["UsuarioFaena"]["nivel_id"], 'UsuarioNivel.usuario_id'=>$resultado["UsuarioFaena"]["usuario_id"],'UsuarioNivel.e'=>'1'),
							'recursive' => -1
						));
						if($resultado3==0){
							$sql="UPDATE usuario SET e='0' WHERE id={$resultado["UsuarioFaena"]["usuario_id"]};";
							//$db->query($sql);
						}else{
							$sql="UPDATE usuario SET e='1' WHERE id={$resultado["UsuarioFaena"]["usuario_id"]};";
							$db->query($sql);
						}
					}catch(Exception $e){
						
					}
				}
			}
			
			if (isset($this->request->data["quitar_un"]) && is_array($this->request->data["quitar_un"]) && count($this->request->data["quitar_un"]) > 0) {
				foreach($this->request->data["quitar_un"] as $key => $value) {
					try{
						$resultado = $this->UsuarioNivel->find('first', array(
							'fields'=>array('UsuarioNivel.id','UsuarioNivel.e','UsuarioNivel.nivel_id','UsuarioNivel.usuario_id'),
							'conditions' => array('UsuarioNivel.id' => $value),
							'recursive' => -1
						));
						$data=array();
						if(isset($resultado["UsuarioNivel"])){
							$data["id"]=$resultado["UsuarioNivel"]["id"];
							if($resultado["UsuarioNivel"]["e"]=="1"){
								$data["e"]="0";
								$data["correo"]='0';
							}else{
								$data["e"]="1";
								$data["correo"]='1';
							}
						}
						$this->UsuarioNivel->save($data);
						//$sql="UPDATE usuario_nivel SET e='0',correo='0' WHERE id=$value;";
						//$db->query($sql);
						$resultado3 = $this->UsuarioNivel->find('count', array(
							//'fields'=>array('UsuarioNivel.id'),
							'conditions' => array('UsuarioNivel.nivel_id' => $resultado["UsuarioNivel"]["nivel_id"], 'UsuarioNivel.usuario_id'=>$resultado["UsuarioNivel"]["usuario_id"],'UsuarioNivel.e'=>'1'),
							'recursive' => -1
						));
						if($resultado3==0){
							$sql="UPDATE usuario SET e='0' WHERE id={$resultado["UsuarioNivel"]["usuario_id"]};";
							$db->query($sql);
						}else{
							$sql="UPDATE usuario SET e='1' WHERE id={$resultado["UsuarioNivel"]["usuario_id"]};";
							$db->query($sql);
						}
					}catch(Exception $e){}
				}
			}
			// Validar si hay usuarios sin asignacion de faena para quitar asignacion de perfil completamente
			$this->Session->setFlash('Archivo cargado con exito!', 'guardar_exito');
		}
		$this->redirect('/Herramientas/CargarDotacion/');
	}
	/* Este metodo define el procesamiento para manipular el archivo matriz sistema subsistema  */
	public function subsistemageneral() {
		$this->set('titulo', 'Cargar Sistema-Subsistema');
		$data = array();
		$this->loadModel('Sistema');
		$this->loadModel('Motor');
		$this->loadModel('Subsistema');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('Subsistema_Posicion');
		$this->loadModel('Sistema_Subsistema_Motor');
		$this->set('filtro', '1');
		if (count($this->request->data)) {
			$data = $this->request->data;
			$data = $data["carga_archivo"]["archivo"];
			if ($data["error"] == 0 && $data["size"] > 0) {
				$file = new File($data["tmp_name"], false, 0644);
				$new_file = "/tmp/sg_" . time();
				if (copy($data["tmp_name"], $new_file)) {
					$this->set('archivo', $new_file);
				}
				$data = $file->read(true, 'r');
				$tmp = explode("\n", $data);
				$data = array();
				$i = 1;
				unset($tmp[0]);
				$motor = '';
				$motor_c = '';
				$sistema = '';
				$sistema_c = '';
				$subsistema = '';
				$subsistema_c = '';
				$posicion = '';
				$posicion_c = '';
				$elemento = '';
				$elemento_c = '';
				$id_ssm = array();
				$id_sp = array();
				$resultado = array();
				foreach($tmp as $fila) {
					$datos = explode(";", utf8_encode($fila));
					if ($datos[0] == '') {
						continue;
					}
					$temp["td"] = '';
					$temp["Motor"] = $datos[0];
					$resultado = $this->Motor->find('first', array(
						'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[0]))),
						'recursive' => -1
					));
					if (!isset($resultado['Motor'])) {
						$temp["td"] = 'background-color: #FFDB58; color: black;';
					}
					$temp["Sistema"] = $datos[2];
					$resultado = $this->Sistema->find('first', array(
						'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[2]))),
						'recursive' => -1
					));
					if (!isset($resultado['Sistema'])) {
						$temp["td"] = 'background-color: #FFDB58; color: black;';
					}
					$temp["Subsistema"] = $datos[3];
					$resultado = $this->Subsistema->find('first', array(
						'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[3]))),
						'recursive' => -1
					));
					if (!isset($resultado['Subsistema'])) {
						$temp["td"] = 'background-color: #FFDB58; color: black;';
					}
					$temp["Posicion"] = $datos[4];
					$resultado = $this->Posiciones_Subsistema->find('first', array(
						'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[4]))),
						'recursive' => -1
					));
					$posicion = trim($datos[4]);
					if (!isset($resultado['Posiciones_Subsistema'])) {
						$temp["td"] = 'background-color: #FFDB58; color: black;';
					}
					$resultado = $this->Sistema_Subsistema_Motor->find('first', array(
						'conditions' => array('LOWER(TRIM(Motor.nombre))'=>strtolower(trim($datos[0])),'LOWER(TRIM(Sistema.nombre))' => strtolower(trim($datos[2])),'LOWER(TRIM(Subsistema.nombre))'=>strtolower(trim($datos[3]))),
						'recursive' => 1
					));
					$resultado2 = $this->Subsistema_Posicion->find('first', array(
						'conditions' => array('LOWER(TRIM(Posicion.nombre))'=>strtolower(trim($datos[4])),'LOWER(TRIM(Subsistema.nombre))'=>strtolower(trim($datos[3]))),
						'recursive' => 1
					));
					if (!isset($resultado['Sistema_Subsistema_Motor']) || !isset($resultado2['Subsistema_Posicion'])) {
						$temp["td"] = 'background-color: #FFDB58; color: black;';
					}
					if (isset($resultado['Sistema_Subsistema_Motor']) && isset($resultado2['Subsistema_Posicion'])) {
						$id_ssm[] = $resultado['Sistema_Subsistema_Motor']["id"];
						$id_sp[] = $resultado2['Subsistema_Posicion']["id"];
					}
					$temp["Accion"] = "";
					if ($temp["td"] != '') {
						$temp["Accion"] = "<input type=\"checkbox\" name=\"agregar[]\" class=\"matriz_opciones\" value=\"$i\" title=\"Marcar para agregar\" />";
					}
					if (is_array($temp))
						$data[] = $temp;
					$i++;
				}
				// Datos existentes
				$db = ConnectionManager::getDataSource('default');
				$query = "select sistema_subsistema_motor.id as id_ssm, subsistema_posicion.id as id_sp, motor.nombre as motor, sistema.nombre as sistema, subsistema.nombre as subsistema, posiciones_subsistema.nombre as posicion, subsistema_posicion.e as e from sistema, motor, subsistema, sistema_subsistema_motor, subsistema_posicion, posiciones_subsistema where sistema_subsistema_motor.subsistema_id = subsistema_posicion.subsistema_id and sistema.id = sistema_subsistema_motor.sistema_id and sistema_subsistema_motor.motor_id = motor.id and sistema_subsistema_motor.subsistema_id = subsistema.id and subsistema_posicion.posicion_id = posiciones_subsistema.id order by motor.nombre, sistema.nombre, subsistema.nombre, posiciones_subsistema.nombre";
				$result = $db->fetchAll($query);
				$existentes = count($id_ssm);
				foreach ($result as $data2) {
					$existe = false;
					for($i = 0; $i < $existentes; $i++) {
						if ($data2[0]["id_ssm"] == $id_ssm[$i] && $data2[0]["id_sp"] == $id_sp[$i]) {
							$existe = true;
							break;
						}
					}
					if (!$existe) {
						$resultado[] = $data2[0];
					}
				}
			} else {
				$data = array();
			}
			$this->set('resultado', $resultado);
		}
		$this->set('data', $data);
	}
	
	public function verificar_subsistema_general() {
		$this->layout = null;
		$data = array();
		$this->loadModel('Sistema');
		$this->loadModel('Motor');
		$this->loadModel('Subsistema');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('Subsistema_Posicion');
		$this->loadModel('Sistema_Subsistema_Motor');
		$this->set('filtro', '1');
		if (count($this->request->data)) {
			if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"]) && count($this->request->data["agregar"]) > 0) {
				$file = new File($this->request->data["Herramientas"]["hash_file"], false, 0644);
				$data = $file->read(true, 'r');
				$tmp = explode("\n", $data);
				$data = array();
				$i = 0;
				unset($tmp[0]);
				foreach($tmp as $fila) {
					$i++;
					if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"])) {
						if (array_search($i, $this->request->data["agregar"]) !== FALSE) {
							$data = array();
							$data["motor_id"] = -1;
							$data["sistema_id"] = -1;
							$data["subsistema_id"] = -1;
							$data["posicion_id"] = -1;
							$datos = explode(";", utf8_encode($fila));
							$temp["td"] = '';
							$temp["Motor"] = $datos[0];
							$resultado = $this->Motor->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[0]))),
								'recursive' => -1
							));
							if (isset($resultado['Motor'])) {
								$data["motor_id"] = $resultado['Motor']["id"];
							} else {
								$this->Motor->create();
								$this->Motor->save(array("nombre" => trim($datos[0])));
								if (isset($this->Motor->id)) {
									$data["motor_id"] = $this->Motor->id;
								}
							}
							$temp["Sistema"] = $datos[2];
							$resultado = $this->Sistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[2]))),
								'recursive' => -1
							));
							if (isset($resultado['Sistema'])) {
								$data["sistema_id"] = $resultado['Sistema']["id"];
							} else {
								$this->Sistema->create();
								$this->Sistema->save(array("nombre" => trim($datos[2])));
								if (isset($this->Sistema->id)) {
									$data["sistema_id"] = $this->Sistema->id;
								}
							}
							$temp["Subsistema"] = $datos[3];
							$resultado = $this->Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[3]))),
								'recursive' => -1
							));
							if (isset($resultado['Subsistema'])) {
								$data["subsistema_id"] = $resultado['Subsistema']["id"];;
							} else {
								$this->Subsistema->create();
								$this->Subsistema->save(array("nombre" => trim($datos[3])));
								if (isset($this->Subsistema->id)) {
									$data["subsistema_id"] = $this->Subsistema->id;
								}
							}
							$temp["Posicion"] = $datos[4];
							$resultado = $this->Posiciones_Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[4]))),
								'recursive' => -1
							));
							if (isset($resultado['Posiciones_Subsistema'])) {
								$data["posicion_id"] = $resultado['Posiciones_Subsistema']["id"];
							} else {
								$this->Posiciones_Subsistema->create();
								$this->Posiciones_Subsistema->save(array("nombre" => trim($datos[4])));
								if (isset($this->Posiciones_Subsistema->id)) {
									$data["posicion_id"] = $this->Posiciones_Subsistema->id;
								}
							}
							$resultado = $this->Sistema_Subsistema_Motor->find('first', array(
								'conditions' => array('LOWER(TRIM(Motor.nombre))'=>strtolower(trim($datos[0])),'LOWER(TRIM(Sistema.nombre))' => strtolower(trim($datos[2])),'LOWER(TRIM(Subsistema.nombre))'=>strtolower(trim($datos[3]))),
								'recursive' => 1
							));
							if (!isset($resultado['Sistema_Subsistema_Motor'])) {
								$this->Sistema_Subsistema_Motor->create();
								$this->Sistema_Subsistema_Motor->save(array("motor_id" => $data["motor_id"], "sistema_id" => $data["sistema_id"], "subsistema_id" => $data["subsistema_id"]));
								//print_r(array("motor_id" => $data["motor_id"], "sistema_id" => $data["sistema_id"], "subsistema_id" => $data["subsistema_id"]));
							}
							$resultado = $this->Subsistema_Posicion->find('first', array(
								'conditions' => array('LOWER(TRIM(Posicion.nombre))'=>strtolower(trim($datos[4])),'LOWER(TRIM(Subsistema.nombre))'=>strtolower(trim($datos[3]))),
								'recursive' => 1
							));
							if (!isset($resultado['Subsistema_Posicion'])) {
								$this->Subsistema_Posicion->create();
								$this->Subsistema_Posicion->save(array("subsistema_id" => $data["subsistema_id"], "posicion_id" => $data["posicion_id"]));
								//print_r(array("subsistema_id" => $data["subsistema_id"], "posicion_id" => $data["posicion_id"]));
							}
						}
					}
				}
				
			}
			$db = ConnectionManager::getDataSource('default');
			if (isset($this->request->data["quitar"]) && is_array($this->request->data["quitar"]) && count($this->request->data["quitar"]) > 0) {
				foreach($this->request->data["quitar"] as $key => $value) {
					$tmp = split("_", $value);
					//$data = array("id" => $value[1], "e" => "0");
					$query = "update subsistema_posicion set e = '0' where id = " . $tmp[1];
					$result = $db->query($query);
					//$this->Subsistema_Posicion->save($data);
				}
			}
			if (isset($this->request->data["deshacer"]) && is_array($this->request->data["deshacer"]) && count($this->request->data["deshacer"]) > 0) {
				foreach($this->request->data["deshacer"] as $key => $value) {
					$tmp = split("_", $value);
					//$data = array("id" => $value[1], "e" => "0");
					$query = "update subsistema_posicion set e = '1' where id = " . $tmp[1];
					$result = $db->query($query);
					//$this->Subsistema_Posicion->save($data);
				}
			}
			$this->Session->setFlash('Datos de archivo cargado con éxito!!', 'guardar_exito');
			$this->redirect('/Herramientas/SubsistemaGeneral');
		}
	}
	
	public function verificar_elemento_general() {
		/*$db = ConnectionManager::getDataSource('default');
		if (!$db->isConnected()) {
			die;
		}*/
		$this->layout = null;
		if (count($this->request->data)) {
			$db = ConnectionManager::getDataSource('default');
			if (isset($this->request->data["quitar"]) && is_array($this->request->data["quitar"]) && count($this->request->data["quitar"]) > 0) {
				foreach($this->request->data["quitar"] as $key => $value) {
					$query = "update sistema_subsistema_motor_elemento set e = '0', mtime=".time()." where id = " . $value;
					$result = $db->query($query);
				}
			}
			if (isset($this->request->data["deshacer"]) && is_array($this->request->data["deshacer"]) && count($this->request->data["deshacer"]) > 0) {
				foreach($this->request->data["deshacer"] as $key => $value) {
					$query = "update sistema_subsistema_motor_elemento set e = '1', mtime=".time()." where id = " . $value;
					$result = $db->query($query);
				}
			}
			if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"]) && count($this->request->data["agregar"]) > 0) {
				$this->loadModel('Motor');
				$this->loadModel('Sistema');
				$this->loadModel('Subsistema');
				$this->loadModel('Elemento');
				$this->loadModel('Posiciones_Elemento');
				$this->loadModel('Sistema_Subsistema_Motor_Elemento');
				$file = new File($this->request->data["Herramientas"]["hash_file"], false, 0644);
				$data = $file->read(true, 'r');
				$tmp = explode("\n", $data);
				$i = 0;
				unset($tmp[0]);
				foreach($tmp as $fila) {
					$i++;
					if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"])) {
						if (array_search($i, $this->request->data["agregar"]) !== FALSE) {
							$datos = explode(";", utf8_encode($fila));
							$add = array();
							if ($datos[0] == '') {
								continue;
							}
							$resultado = $this->Motor->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[0]))),
								'recursive' => -1
							));
							if (!isset($resultado["Motor"])) {
								$this->Motor->create();
								$this->Motor->save(array("nombre" => trim($datos[0])));
								if (isset($this->Motor->id)) {
									$add["motor_id"] = $this->Motor->id;
								}
							} else {
								$add["motor_id"] = $resultado["Motor"]["id"];
							}
							$resultado = $this->Sistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[2]))),
								'recursive' => -1
							));
							if (!isset($resultado["Sistema"])) {
								$this->Sistema->create();
								$this->Sistema->save(array("nombre" => trim($datos[2])));
								if (isset($this->Sistema->id)) {
									$add["sistema_id"] = $this->Sistema->id;
								}
							} else {
								$add["sistema_id"] = $resultado["Sistema"]["id"];
							}
							$resultado = $this->Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[3]))),
								'recursive' => -1
							));
							if (!isset($resultado["Subsistema"])) {
								$this->Subsistema->create();
								$this->Subsistema->save(array("nombre" => trim($datos[3])));
								if (isset($this->Subsistema->id)) {
									$add["subsistema_id"] = $this->Subsistema->id;
								}
							} else {
								$add["subsistema_id"] = $resultado["Subsistema"]["id"];
							}
							$resultado = $this->Elemento->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[5]))),
								'recursive' => -1
							));
							if (!isset($resultado["Elemento"])) {
								$this->Elemento->create();
								$this->Elemento->save(array("nombre" => trim($datos[5])));
								if (isset($this->Elemento->id)) {
									$add["elemento_id"] = $this->Elemento->id;
								}
							} else {
								$add["elemento_id"] = $resultado["Elemento"]["id"];
							}
							$resultado = $this->Posiciones_Elemento->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[6]))),
								'recursive' => -1
							));
							if (!isset($resultado["Posiciones_Elemento"])) {
								$this->Posiciones_Elemento->create();
								$this->Posiciones_Elemento->save(array("nombre" => trim($datos[6])));
								if (isset($this->Posiciones_Elemento->id)) {
									$add["posicion_id"] = $this->Posiciones_Elemento->id;
								}
							} else {
								$add["posicion_id"] = $resultado["Posiciones_Elemento"]["id"];
							}
							$add["codigo"] = trim($datos[4]);
							$add["e"] = '1';
							$add["mtime"] = time();
							
							$resultado = $this->Sistema_Subsistema_Motor_Elemento->find('first', array(
								'fields' => array('Sistema_Subsistema_Motor_Elemento.id'),
								'conditions' => array('motor_id'=>$add["motor_id"],'sistema_id' => $add["sistema_id"],'subsistema_id'=>$add["subsistema_id"],'LOWER(TRIM(codigo))' => strtolower(trim($datos[4])), 'elemento_id' =>$add["elemento_id"], 'posicion_id' =>$add["posicion_id"]),
								'recursive' => -1
							));
							if (!isset($resultado['Sistema_Subsistema_Motor_Elemento'])) {
								$this->Sistema_Subsistema_Motor_Elemento->create();
								$this->Sistema_Subsistema_Motor_Elemento->save($add);
							}
						}
					}
				}
			}
		}
		$this->Session->setFlash('Datos de archivo cargado con éxito!!', 'guardar_exito');
		$this->redirect('/Herramientas/ElementoGeneral');
	}
	/* Este metodo define el procesamiento para manipular el archivo elementos */
	public function elementogeneral() {
		$this->set('titulo', 'Cargar Elemento-General');
		$data = array();
		$this->loadModel('Sistema');
		$this->loadModel('Motor');
		$this->loadModel('Subsistema');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('Posiciones_Elemento');
		$this->loadModel('Subsistema_Posicion');
		$this->loadModel('Sistema_Subsistema_Motor');
		$this->loadModel('Elemento');
		$this->loadModel('Sistema_Subsistema_Motor_Elemento');
		$this->set('filtro', '1');
		if (count($this->request->data)) {
			//$this->set('filtro', $this->request->data["filtro"]);
			$filtro = -1;
			$ingresados = array();
			$ingresados[] = '0';
			$data = $this->request->data;
			$data = $data["carga_archivo"]["archivo"];
			if ($data["error"] == 0 && $data["size"] > 0) {
				$anterior_1 = "";
				$file = new File($data["tmp_name"], false, 0644);
				$new_file = "/tmp/sg_" . time();
				if (copy($data["tmp_name"], $new_file)) {
					$this->set('archivo', $new_file);
				}
				$data = $file->read(true, 'r');
				//$data = iconv('ISO-8859-1','UTF-8', $data);
				$tmp = explode("\n", $data);
				$data = array();
				$i = 1;
				unset($tmp[0]);
				$resultados = array();
				$id_e = array();
				$id_e[] = '0';
				foreach($tmp as $fila) {
					$datos = explode(";", utf8_encode($fila));
					if ($datos[0] == '') {
						continue;
					}
					$temp["td"] = '';
					$temp["Motor"] = $datos[0];
					$temp["Sistema"] = $datos[2];
					$temp["Subsistema"] = $datos[3];
					$temp["Elemento"] = $datos[5];
					$temp["Codigo"] = $datos[4];
					$temp["Posicion"] = $datos[6];
					$resultado = $this->Sistema_Subsistema_Motor_Elemento->find('first', array(
						'fields' => array('Sistema_Subsistema_Motor_Elemento.id', 'Motor.nombre','Sistema.nombre','Subsistema.nombre','codigo','Elemento.nombre','Posicion.nombre'),
						'conditions' => array('LOWER(TRIM(Motor.nombre))'=>strtolower(trim($datos[0])),'LOWER(TRIM(Sistema.nombre))' => strtolower(trim($datos[2])),'LOWER(TRIM(Subsistema.nombre))'=>strtolower(trim($datos[3])),'LOWER(TRIM(codigo))' => strtolower(trim($datos[4])), 'LOWER(TRIM(Elemento.nombre))' => strtolower(trim($datos[5])), 'LOWER(TRIM(Posicion.nombre))' => strtolower(trim($datos[6]))),
						'recursive' => 1
					));
					$temp["Accion"] = '';
					if (!isset($resultado['Sistema_Subsistema_Motor_Elemento'])) {
						$temp["td"] = 'background-color: yellow; color: black;';
						$temp["Accion"] = "<input type=\"checkbox\" class=\"matriz_opciones\" name=\"agregar[]\" value=\"$i\" title=\"Marcar para agregar\" />";
					} else {
						$id_e[] = $resultado['Sistema_Subsistema_Motor_Elemento']["id"];
					}
					//$temp["Accion"] = '';
					/*if ($temp["td"] != '') {
						$temp["Accion"] = "<input type=\"checkbox\" class=\"matriz_opciones\" name=\"agregar[]\" value=\"$i\" title=\"Marcar para agregar\" />";
					}*/
					
					if (is_array($temp))
						$data[] = $temp;
					$i++;
				} 
				$id_e = implode(",", $id_e);
				$resultados = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
					'fields' => array('Sistema_Subsistema_Motor_Elemento.id', 'Motor.nombre', 'Sistema.nombre','Subsistema.nombre', 'Sistema_Subsistema_Motor_Elemento.codigo', 'Elemento.nombre', 'Posicion.nombre', 'Sistema_Subsistema_Motor_Elemento.e'),
					'order' => array('Motor.nombre', 'Sistema.nombre', 'Subsistema.nombre', 'Elemento.nombre'),
					'conditions' => array("Sistema_Subsistema_Motor_Elemento.id NOT IN ($id_e)"),
					'recursive' => 1
				));
				$this->set('existentes', $resultados);
			} else {
				$data = array();
			}
		}
		
		$this->set('motores', $this->Motor->find('all', array('order' => 'nombre','conditions'=>array("Motor.e='1'"), 'recursive' => -1)));
		$this->set('elementos', $this->Elemento->find('all', array('order' => 'nombre','conditions'=>array("Elemento.e='1'"), 'recursive' => -1)));
		$this->set('sistemas', $this->Sistema->find('all', array('order' => 'nombre','conditions'=>array("Sistema.e='1'"), 'recursive' => -1)));
		$this->set('subsistemas', $this->Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Subsistema.e='1'"), 'recursive' => -1)));
		$this->set('posicioneselementos', $this->Posiciones_Elemento->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Elemento.e='1'"), 'recursive' => -1)));
		$this->set('data', $data);
	}
	/* Este metodo define el procesamiento para manipular el archivo diagnosticos */
	public function diagnosticogeneral() {
		$this->set('titulo', 'Cargar Diagnostico General');
		$data = array();
		$this->loadModel('Diagnostico');
		$this->loadModel('Elemento');
		$this->loadModel('Elemento_Diagnostico');
		if (count($this->request->data)) {
			$data = $this->request->data;
			$data = $data["carga_archivo"]["archivo"];
			if ($data["error"] == 0 && $data["size"] > 0) {
				$anterior_1 = "";
				$file = new File($data["tmp_name"], false, 0644);
				$new_file = "/tmp/sg_" . time();
				if (copy($data["tmp_name"], $new_file)) {
					$this->set('archivo', $new_file);
				}
				$data = $file->read(true, 'r');
				$tmp = explode("\n", $data);
				$data = array();
				$i = 0;
				unset($tmp[0]);
				$id_d = array();
				$resultados = array();
				$diagnosticos = array();
				$elementos = array();
				foreach($tmp as $fila) {
					$datos = explode(";", utf8_encode($fila));
					if ($datos[0] == '') {
						continue;
					}
					$i++;
					$temp["td"] = '';
					$temp["Motor"] = $datos[0];
					$temp["Sistema"] = $datos[2];
					$temp["Subsistema"] = $datos[3];
					$temp["Codigo"] = $datos[4];
					
					$temp["Elemento"] = $datos[5];
					if (!isset($elementos[$datos[5]])) {
						$resultado = $this->Elemento->find('first', array(
							'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[5]))),
							'recursive' => -1
						));
						if (!isset($resultado['Elemento'])) {
							$temp["td"] = 'background-color: yellow; color: black;';
						} else {
							$elementos[$datos[5]] = $resultado['Elemento']["id"];
						}
					}
					$temp["Diagnostico"] = $datos[6];
					if (!isset($diagnosticos[$datos[6]])) {
						$resultado = $this->Diagnostico->find('first', array(
							'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[6]))),
							'recursive' => -1
						));
						if (!isset($resultado['Diagnostico'])) {
							$temp["td"] = 'background-color: yellow; color: black;';
						} else {
							$diagnosticos[$datos[6]] = $resultado['Diagnostico']["id"];
						}
					}
					$resultado = $this->Elemento_Diagnostico->find('first', array(
						'fields' => array('Elemento_Diagnostico.id', 'Elemento.nombre', 'Diagnostico.nombre'),
						'conditions' => array('LOWER(TRIM(Elemento.nombre))' => strtolower(trim($datos[5])), 'LOWER(TRIM(Diagnostico.nombre))' => strtolower(trim($datos[6]))),
						'recursive' => 1
					));
					if (!isset($resultado['Elemento_Diagnostico'])) {
						$temp["td"] = 'background-color: yellow; color: black;';
					} else {
						$id_d[] = $resultado['Elemento_Diagnostico']["id"];
					}
					$temp["Accion"] = '';
					if ($temp["td"] != '') {
						$temp["Accion"] = "<input class=\"matriz_opciones\" type=\"checkbox\" name=\"agregar[]\" value=\"$i\" title=\"Marcar para agregar\" />";
					}
					$data[] = $temp;
				}
				// Datos existentes
				/*$db = ConnectionManager::getDataSource('default');
				$query = "select motor.nombre as motor, sistema.nombre as sistema, subsistema.nombre as subsistema, sistema_subsistema_motor_elemento.codigo as codigo, elemento.nombre as elemento, diagnostico.nombre as diagnostico, elemento_diagnostico.id
				from sistema, motor, subsistema, elemento, diagnostico, sistema_subsistema_motor_elemento, elemento_diagnostico 
				where sistema.id = sistema_subsistema_motor_elemento.sistema_id 
					and sistema_subsistema_motor_elemento.motor_id = motor.id 
					and sistema_subsistema_motor_elemento.subsistema_id = subsistema.id 
					and sistema_subsistema_motor_elemento.elemento_id = elemento.id 
					and sistema_subsistema_motor_elemento.elemento_id = elemento_diagnostico.elemento_id 
					and elemento_diagnostico.elemento_id = elemento.id
					and elemento_diagnostico.diagnostico_id = diagnostico.id
					and elemento_diagnostico.id NOT IN (0,".implode(",",$id_d).")
				order by motor.nombre, sistema.nombre, subsistema.nombre, elemento.nombre, diagnostico.nombre";
				$result = $db->fetchAll($query);
				foreach ($result as $data2) {
					$resultados[] = $data2[0];
				}*/
				$this->set('resultados', $resultados);
			}
		}
		$this->set('data', $data);
	}
	
	public function verificardiagnostico() {
		$this->layout = null;
		$data = array();
		$this->loadModel('Diagnostico');
		$this->loadModel('Elemento');
		$this->loadModel('Elemento_Diagnostico');
		if (count($this->request->data)) {
			if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"]) && count($this->request->data["agregar"]) > 0) {
				$file = new File($this->request->data["Herramientas"]["hash_file"], false, 0644);
				$data = $file->read(true, 'r');
				$tmp = explode("\n", $data);
				$data = array();
				$i = 0;
				unset($tmp[0]);
				foreach($tmp as $fila) {
					$i++;
					if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"])) {
						if (array_search($i, $this->request->data["agregar"]) !== FALSE) {
							$datos = explode(";", utf8_encode($fila));
							$data = array();
							$data["elemento_id"] = -1;
							$resultado = $this->Elemento->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[5]))),
								'recursive' => -1
							));
							if (isset($resultado['Elemento'])) {
								$data["elemento_id"] = $resultado['Elemento']["id"];
							} else {
								$this->Elemento->create();
								$this->Elemento->save(array("nombre" => trim($datos[5])));
								if (isset($this->Elemento->id)) {
									$data["elemento_id"] = $this->Elemento->id;
								}
							}
							$resultado = $this->Diagnostico->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[6]))),
								'recursive' => -1
							));
							if (isset($resultado['Diagnostico'])) {
								$data["diagnostico_id"] = $resultado['Diagnostico']["id"];
							} else {
								$this->Diagnostico->create();
								$this->Diagnostico->save(array("nombre" => trim($datos[6])));
								if (isset($this->Diagnostico->id)) {
									$data["diagnostico_id"] = $this->Diagnostico->id;
								}
							}
							$resultado = $this->Elemento_Diagnostico->find('first', array(
								'fields' => array('Elemento_Diagnostico.id', 'Elemento.nombre', 'Diagnostico.nombre'),
								'conditions' => array('LOWER(TRIM(Elemento.nombre))' => strtolower(trim($datos[5])), 'LOWER(TRIM(Diagnostico.nombre))' => strtolower(trim($datos[6]))),
								'recursive' => 1
							));
							if (!isset($resultado['Elemento_Diagnostico'])) {
								$this->Elemento_Diagnostico->create();
								$this->Elemento_Diagnostico->save($data);
							}
						}
					}
				}
			}
			$this->Session->setFlash('Datos de archivo cargado con éxito!!', 'guardar_exito');
			$this->redirect('/Herramientas/DiagnosticoGeneral');
		}
	}
	
	/*NEW*/
	public function SistemaSubsistema() {
		$this->set('titulo', 'Cargar Motor-Sistema-Subsistema-Posicion');
		$data = array();
		$this->loadModel('Sistema');
		$this->loadModel('Motor');
		$this->loadModel('Subsistema');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$this->set('filtro', '1');
		if (count($this->request->data)) {
			$data = $this->request->data;
			$data = $data["carga_archivo"]["archivo"];
			if ($data["error"] == 0 && $data["size"] > 0) {
				$file = new File($data["tmp_name"], false, 0644);
				$new_file = "/tmp/sg_" . time();
				if (copy($data["tmp_name"], $new_file)) {
					$this->set('archivo', $new_file);
				}
				$data = $file->read(true, 'r');
				$tmp = explode("\n", $data);
				$data = array();
				$i = 1;
				unset($tmp[0]);
				$id_ = array();
				$id_[] = "0";
				$resultados = array();
				foreach($tmp as $fila) {
					$datos = explode(";", utf8_encode($fila));
					if ($datos[0] == '') {
						continue;
					}
					$temp["td"] = '';
					$temp["Motor"] = $datos[0];
					/*$resultado = $this->Motor->find('first', array(
						'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[0]))),
						'recursive' => -1
					));*/
					/*if (!isset($resultado['Motor'])) {
						$temp["td"] = 'background-color: #FFDB58; color: black;';
					}*/
					$temp["Sistema"] = $datos[2];
					/*$resultado = $this->Sistema->find('first', array(
						'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[2]))),
						'recursive' => -1
					));*/
					/*if (!isset($resultado['Sistema'])) {
						$temp["td"] = 'background-color: #FFDB58; color: black;';
					}*/
					$temp["Subsistema"] = $datos[3];
					/*$resultado = $this->Subsistema->find('first', array(
						'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[3]))),
						'recursive' => -1
					));*/
					/*if (!isset($resultado['Subsistema'])) {
						$temp["td"] = 'background-color: #FFDB58; color: black;';
					}*/
					$temp["Posicion"] = $datos[4];
					/*$resultado = $this->Posiciones_Subsistema->find('first', array(
						'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[4]))),
						'recursive' => -1
					));*/
					$posicion = trim($datos[4]);
					/*if (!isset($resultado['Posiciones_Subsistema'])) {
						$temp["td"] = 'background-color: #FFDB58; color: black;';
					}*/
					$resultado = $this->MotorSistemaSubsistemaPosicion->find('first', array(
						'conditions' => array('LOWER(TRIM(Motor.nombre))'=>strtolower(trim($datos[0])),'LOWER(TRIM(Sistema.nombre))' => strtolower(trim($datos[2])),'LOWER(TRIM(Subsistema.nombre))'=>strtolower(trim($datos[3])),'LOWER(TRIM(Posicion.nombre))'=>strtolower(trim($datos[4]))),
						'recursive' => 1
					));
					//$temp["Accion"] = "";
					if (!isset($resultado['MotorSistemaSubsistemaPosicion'])) {
						$temp["Accion"] = "<input type=\"checkbox\" name=\"agregar[]\" class=\"matriz_opciones\" value=\"$i\" title=\"Marcar para agregar\" />";
					} else {
						$temp["Accion"] = "";
						$id_[] = $resultado['MotorSistemaSubsistemaPosicion']["id"];
					}
					if (is_array($temp))
						$data[] = $temp;
					$i++;
				}
				$resultados = $this->MotorSistemaSubsistemaPosicion->find('all', array(
					'conditions' => array("MotorSistemaSubsistemaPosicion.id NOT IN (".implode(",",$id_).")"),
					'recursive' => 1
				));
			} else {
				$data = array();
			}
			$this->set('resultado', $resultados);
		}
		
		$this->set('motores', $this->Motor->find('all', array('order' => 'nombre','conditions'=>array("Motor.e='1'"), 'recursive' => -1)));
		$this->set('sistemas', $this->Sistema->find('all', array('order' => 'nombre','conditions'=>array("Sistema.e='1'"), 'recursive' => -1)));
		$this->set('subsistemas', $this->Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Subsistema.e='1'"), 'recursive' => -1)));
		$this->set('posicionessubsistemas', $this->Posiciones_Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Posiciones_Subsistema.e='1'"), 'recursive' => -1)));
		$this->set('data', $data);
	}
	
	
	public function SistemaSubsistemaProcess() {
		$this->layout = null;
		$data = array();
		$this->loadModel('Sistema');
		$this->loadModel('Motor');
		$this->loadModel('Subsistema');
		$this->loadModel('Posiciones_Subsistema');
		$this->loadModel('MotorSistemaSubsistemaPosicion');
		$this->set('filtro', '1');
		if (count($this->request->data)) {
			if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"]) && count($this->request->data["agregar"]) > 0) {
				$file = new File($this->request->data["Herramientas"]["hash_file"], false, 0644);
				$data = $file->read(true, 'r');
				$tmp = explode("\n", $data);
				$data = array();
				$i = 0;
				unset($tmp[0]);
				foreach($tmp as $fila) {
					$i++;
					if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"])) {
						if (array_search($i, $this->request->data["agregar"]) !== FALSE) {
							$data = array();
							$data["motor_id"] = -1;
							$data["sistema_id"] = -1;
							$data["subsistema_id"] = -1;
							$data["posicion_id"] = -1;
							$datos = explode(";", utf8_encode($fila));
							$temp["td"] = '';
							$temp["Motor"] = $datos[0];
							$resultado = $this->Motor->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[0]))),
								'recursive' => -1
							));
							if (isset($resultado['Motor'])) {
								$data["motor_id"] = $resultado['Motor']["id"];
							} else {
								$this->Motor->create();
								$this->Motor->save(array("nombre" => trim($datos[0])));
								if (isset($this->Motor->id)) {
									$data["motor_id"] = $this->Motor->id;
								}
							}
							$temp["Sistema"] = $datos[2];
							$resultado = $this->Sistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[2]))),
								'recursive' => -1
							));
							if (isset($resultado['Sistema'])) {
								$data["sistema_id"] = $resultado['Sistema']["id"];
							} else {
								$this->Sistema->create();
								$this->Sistema->save(array("nombre" => trim($datos[2])));
								if (isset($this->Sistema->id)) {
									$data["sistema_id"] = $this->Sistema->id;
								}
							}
							$temp["Subsistema"] = $datos[3];
							$resultado = $this->Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[3]))),
								'recursive' => -1
							));
							if (isset($resultado['Subsistema'])) {
								$data["subsistema_id"] = $resultado['Subsistema']["id"];;
							} else {
								$this->Subsistema->create();
								$this->Subsistema->save(array("nombre" => trim($datos[3])));
								if (isset($this->Subsistema->id)) {
									$data["subsistema_id"] = $this->Subsistema->id;
								}
							}
							$temp["Posicion"] = $datos[4];
							$resultado = $this->Posiciones_Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[4]))),
								'recursive' => -1
							));
							if (isset($resultado['Posiciones_Subsistema'])) {
								$data["posicion_id"] = $resultado['Posiciones_Subsistema']["id"];
							} else {
								$this->Posiciones_Subsistema->create();
								$this->Posiciones_Subsistema->save(array("nombre" => trim($datos[4])));
								if (isset($this->Posiciones_Subsistema->id)) {
									$data["posicion_id"] = $this->Posiciones_Subsistema->id;
								}
							}
							$resultado = $this->MotorSistemaSubsistemaPosicion->find('first', array(
								'conditions' => array('motor_id' => $data["motor_id"],'sistema_id' => $data["sistema_id"],'subsistema_id' => $data["subsistema_id"],'posicion_id' => $data["posicion_id"]),
								'recursive' => -1
							));
							if (!isset($resultado['MotorSistemaSubsistemaPosicion'])) {
								$this->MotorSistemaSubsistemaPosicion->create();
								$this->MotorSistemaSubsistemaPosicion->save(array("motor_id" => $data["motor_id"], "sistema_id" => $data["sistema_id"], "subsistema_id" => $data["subsistema_id"], "posicion_id" => $data["posicion_id"], "e" => "1", 'mtime' => time()));
							}
						}
					}
				}
				
			}
			$db = ConnectionManager::getDataSource('default');
			if (isset($this->request->data["quitar"]) && is_array($this->request->data["quitar"]) && count($this->request->data["quitar"]) > 0) {
				foreach($this->request->data["quitar"] as $key => $value) {
					$query = "update motor_sistema_subsistema_posicion set e = '0' where id = " . $value;
					$result = $db->query($query);
				}
			}
			if (isset($this->request->data["deshacer"]) && is_array($this->request->data["deshacer"]) && count($this->request->data["deshacer"]) > 0) {
				foreach($this->request->data["deshacer"] as $key => $value) {
					$query = "update motor_sistema_subsistema_posicion set e = '1' where id = " . $value;
					$result = $db->query($query);
				}
			}
			$this->Session->setFlash('Datos de archivo cargado con éxito!!', 'guardar_exito');
			$this->redirect('/Herramientas/SistemaSubsistema');
		}
	}
	
	public function elementodiagnostico() {
		$this->set('titulo', 'Cargar Elemento-Diagnostico');
		$data = array();
		
		$this->loadModel('Diagnostico');
		$this->loadModel('Elemento');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		
		//$motor = '';
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		if (count($this->request->data)) {
			/*if ($this->request->data["fm"] != "0") {
				$this->loadModel('Motor');
				$resultado = $this->Motor->find('first', array(
					'fields' => array('id', 'nombre'),
					'conditions' => array("id" => $this->request->data["fm"]),
					'recursive' => -1
				));
				$motor = $resultado["Motor"]["nombre"];
			}*/
			$data = $this->request->data;
			$data = $data["carga_archivo"]["archivo"];
			if ($data["error"] == 0 && $data["size"] > 0) {
				$anterior_1 = "";
				$file = new File($data["tmp_name"], false, 0644);
				$new_file = "/tmp/sg_" . time();
				if (copy($data["tmp_name"], $new_file)) {
					$this->set('archivo', $new_file);
				}
				$data = $file->read(true, 'r');
				$tmp = explode("\n", $data);
				$data = array();
				$i = 0;
				unset($tmp[0]);
				$id_d = array();
				$id_d[] = '0';
				$resultados = array();
				$diagnosticos = array();
				$elementos = array();
				foreach($tmp as $fila) {
					$datos = explode(";", utf8_encode($fila));
					if ($datos[0] == '') {
						break;
					}
					//if ($motor != '' && strtolower(trim($motor)) != strtolower(trim($datos[0]))) {
					//	continue;
					//} else {
						//echo "motor ".strtolower(trim($motor))." -> " . strtolower(trim($datos[0]));
						$i++;
						$temp["td"] = '';
						$temp["Motor"] = $datos[0];
						$temp["Sistema"] = $datos[2];
						$temp["Subsistema"] = $datos[3];
						$temp["Codigo"] = $datos[4];
						$temp["Elemento"] = $datos[5];
						$temp["Diagnostico"] = $datos[6];
						$resultado = $this->MotorSistemaSubsistemaElementoDiagnostico->find('first', array(
							'fields' => array('MotorSistemaSubsistemaElementoDiagnostico.id', 'Motor.nombre', 'Sistema.nombre', 'Subsistema.nombre', 'Elemento.nombre', 'Diagnostico.nombre'),
							'conditions' => array('LOWER(TRIM(Motor.nombre))' => strtolower(trim($datos[0])),'LOWER(TRIM(Sistema.nombre))' => strtolower(trim($datos[2])),'LOWER(TRIM(Subsistema.nombre))' => strtolower(trim($datos[3])),'LOWER(TRIM(Elemento.nombre))' => strtolower(trim($datos[5])), 'LOWER(TRIM(Diagnostico.nombre))' => strtolower(trim($datos[6]))),
							'recursive' => 1
						));
						if (!isset($resultado['MotorSistemaSubsistemaElementoDiagnostico'])) {
							$temp["td"] = 'background-color: yellow; color: black;';
							$temp["Accion"] = "<input class=\"matriz_opciones\" type=\"checkbox\" name=\"agregar[]\" value=\"$i\" title=\"Marcar para agregar\" />";
						} else {
							$id_d[] = $resultado['MotorSistemaSubsistemaElementoDiagnostico']["id"];
							$temp["Accion"] = '';
						}
						$data[] = $temp;
					//}
				}
				//if ($motor != '' && $this->request->data["fm"] != '0') {
				/*	$resultados = $this->MotorSistemaSubsistemaElementoDiagnostico->find('all', array(
						'fields' => array('MotorSistemaSubsistemaElementoDiagnostico.id', 'MotorSistemaSubsistemaElementoDiagnostico.e', 'Motor.nombre', 'Sistema.nombre', 'Subsistema.nombre', 'Elemento.nombre', 'Diagnostico.nombre'),
						'conditions' => array("MotorSistemaSubsistemaElementoDiagnostico.id NOT IN (".implode(",",$id_d).")", "MotorSistemaSubsistemaElementoDiagnostico.motor_id = " . $this->request->data["fm"]),
						'recursive' => 1
					));
					$this->set('fm', $this->request->data["fm"]);*/
				//} else {
					$resultados = $this->MotorSistemaSubsistemaElementoDiagnostico->find('all', array(
						'fields' => array('MotorSistemaSubsistemaElementoDiagnostico.id', 'MotorSistemaSubsistemaElementoDiagnostico.e', 'Motor.nombre', 'Sistema.nombre', 'Subsistema.nombre', 'Elemento.nombre', 'Diagnostico.nombre'),
						'conditions' => array("MotorSistemaSubsistemaElementoDiagnostico.id NOT IN (".implode(",",$id_d).")"),
						'recursive' => 1
					));
					//$this->set('fm', '0');
				//}
				
				$this->set('resultados', $resultados);
			}
		}
		$this->set('motores', $this->Motor->find('all', array('order' => 'nombre','conditions'=>array("Motor.e='1'"), 'recursive' => -1)));
		$this->set('sistemas', $this->Sistema->find('all', array('order' => 'nombre','conditions'=>array("Sistema.e='1'"), 'recursive' => -1)));
		$this->set('subsistemas', $this->Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Subsistema.e='1'"), 'recursive' => -1)));
		$this->set('elementos', $this->Elemento->find('all', array('order' => 'nombre','conditions'=>array("Elemento.e='1'"), 'recursive' => -1)));
		$this->set('diagnosticos', $this->Diagnostico->find('all', array('order' => 'nombre','conditions'=>array("Diagnostico.e='1'"), 'recursive' => -1)));
		$this->set('data', $data);
	}
	
	public function ElementoDiagnosticoProcess() {
		$this->layout = null;
		$data = array();
		$this->loadModel('Sistema');
		$this->loadModel('Motor');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('Diagnostico');
		$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
		$this->set('filtro', '1');
		if (count($this->request->data)) {
			if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"]) && count($this->request->data["agregar"]) > 0) {
				$file = new File($this->request->data["Herramientas"]["hash_file"], false, 0644);
				$data = $file->read(true, 'r');
				$tmp = explode("\n", $data);
				$data = array();
				$i = 0;
				unset($tmp[0]);
				$motor = '';
				/*if ($this->request->data["Herramientas"]["fm"] != '0') {
					$this->loadModel('Motor');
					$resultado = $this->Motor->find('first', array(
						'fields' => array('id', 'nombre'),
						'conditions' => array("id" => $this->request->data["Herramientas"]["fm"]),
						'recursive' => -1
					));
					$motor = $resultado["Motor"]["nombre"];
				}*/
				foreach($tmp as $fila) {
					$datos = explode(";", utf8_encode($fila));
					//if ($motor != '' && strtolower(trim($motor)) != strtolower(trim($datos[0]))) {
					//	continue;
					//} else {
						$i++;
						if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"])) {
							if (array_search($i, $this->request->data["agregar"]) !== FALSE) {
								$data = array();
								$data["motor_id"] = -1;
								$data["sistema_id"] = -1;
								$data["subsistema_id"] = -1;
								$data["elemento_id"] = -1;
								$data["diagnostico_id"] = -1;
								//$datos = explode(";", utf8_encode($fila));
								$temp["td"] = '';
								$temp["Motor"] = $datos[0];
								$resultado = $this->Motor->find('first', array(
									'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[0]))),
									'recursive' => -1
								));
								if (isset($resultado['Motor'])) {
									$data["motor_id"] = $resultado['Motor']["id"];
								} else {
									$this->Motor->create();
									$this->Motor->save(array("nombre" => trim($datos[0])));
									if (isset($this->Motor->id)) {
										$data["motor_id"] = $this->Motor->id;
									}
								}
								$temp["Sistema"] = $datos[2];
								$resultado = $this->Sistema->find('first', array(
									'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[2]))),
									'recursive' => -1
								));
								if (isset($resultado['Sistema'])) {
									$data["sistema_id"] = $resultado['Sistema']["id"];
								} else {
									$this->Sistema->create();
									$this->Sistema->save(array("nombre" => trim($datos[2])));
									if (isset($this->Sistema->id)) {
										$data["sistema_id"] = $this->Sistema->id;
									}
								}
								$temp["Subsistema"] = $datos[3];
								$resultado = $this->Subsistema->find('first', array(
									'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[3]))),
									'recursive' => -1
								));
								if (isset($resultado['Subsistema'])) {
									$data["subsistema_id"] = $resultado['Subsistema']["id"];;
								} else {
									$this->Subsistema->create();
									$this->Subsistema->save(array("nombre" => trim($datos[3])));
									if (isset($this->Subsistema->id)) {
										$data["subsistema_id"] = $this->Subsistema->id;
									}
								}
								$temp["Elemento"] = $datos[5];
								$resultado = $this->Elemento->find('first', array(
									'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[5]))),
									'recursive' => -1
								));
								if (isset($resultado['Elemento'])) {
									$data["elemento_id"] = $resultado['Elemento']["id"];
								} else {
									$this->Elemento->create();
									$this->Elemento->save(array("nombre" => trim($datos[5])));
									if (isset($this->Elemento->id)) {
										$data["elemento_id"] = $this->Elemento->id;
									}
								}
								$temp["Diagnostico"] = $datos[6];
								$resultado = $this->Diagnostico->find('first', array(
									'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[6]))),
									'recursive' => -1
								));
								if (isset($resultado['Diagnostico'])) {
									$data["diagnostico_id"] = $resultado['Diagnostico']["id"];
								} else {
									$this->Diagnostico->create();
									$this->Diagnostico->save(array("nombre" => trim($datos[6])));
									if (isset($this->Diagnostico->id)) {
										$data["diagnostico_id"] = $this->Diagnostico->id;
									}
								}
								$resultado = $this->MotorSistemaSubsistemaElementoDiagnostico->find('first', array(
									'conditions' => array('motor_id' => $data["motor_id"],'sistema_id' => $data["sistema_id"],'subsistema_id' => $data["subsistema_id"],'elemento_id' => $data["elemento_id"],'diagnostico_id' => $data["diagnostico_id"]),
									'recursive' => -1
								));
								if (!isset($resultado['MotorSistemaSubsistemaElementoDiagnostico'])) {
									$this->MotorSistemaSubsistemaElementoDiagnostico->create();
									$this->MotorSistemaSubsistemaElementoDiagnostico->save(array("motor_id" => $data["motor_id"], "sistema_id" => $data["sistema_id"], "subsistema_id" => $data["subsistema_id"], "elemento_id" => $data["elemento_id"],'diagnostico_id' => $data["diagnostico_id"], "e" => "1", 'mtime' => time()));
								}
							}
						}
					//}
				}
				
			}
			$db = ConnectionManager::getDataSource('default');
			if (isset($this->request->data["quitar"]) && is_array($this->request->data["quitar"]) && count($this->request->data["quitar"]) > 0) {
				foreach($this->request->data["quitar"] as $key => $value) {
					$query = "update motor_sistema_subsistema_elemento_diagnostico set e = '0' where id = " . $value;
					$result = $db->query($query); 
				}
			}
			if (isset($this->request->data["deshacer"]) && is_array($this->request->data["deshacer"]) && count($this->request->data["deshacer"]) > 0) {
				foreach($this->request->data["deshacer"] as $key => $value) {
					$query = "update motor_sistema_subsistema_elemento_diagnostico set e = '1' where id = " . $value;
					$result = $db->query($query);
				}
			}
			$this->Session->setFlash('Datos de archivo cargado con éxito!!', 'guardar_exito');
			$this->redirect('/Herramientas/ElementoDiagnostico');
		}
	}
	
	public function elementopool() {
		$this->set('titulo', 'Cargar Elemento-Pool');
		$data = array();
		$this->loadModel('ElementoPool');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->loadModel('Motor');
		if (count($this->request->data)) {
			$ingresados = array();
			$ingresados[] = '0';
			$data = $this->request->data;
			$data = $data["carga_archivo"]["archivo"];
			if ($data["error"] == 0 && $data["size"] > 0) {
				$anterior_1 = "";
				$file = new File($data["tmp_name"], false, 0644);
				$new_file = "/tmp/sg_" . time();
				if (copy($data["tmp_name"], $new_file)) {
					$this->set('archivo', $new_file);
				}
				$data = $file->read(true, 'r');
				//$data = iconv('ISO-8859-1','UTF-8', $data);
				$tmp = explode("\n", $data);
				$data = array();
				$i = 1;
				unset($tmp[0]);
				$resultados = array();
				$id_e = array();
				$id_e[] = '0';
				foreach($tmp as $fila) {
					$datos = explode(";", utf8_encode($fila));
					if ($datos[0] == '') {
						break;
					}
					if (strtolower(trim($datos[6])) != 'x') {
						continue;
					}
					$temp["td"] = '';
					$temp["Motor"] = $datos[0];
					$temp["Sistema"] = $datos[2];
					$temp["Subsistema"] = $datos[3];
					$temp["Elemento"] = $datos[5];
					$temp["Codigo"] = $datos[4];
					
					$resultado = $this->ElementoPool->find('first', array(
						'fields' => array('ElementoPool.id', 'Motor.nombre','Sistema.nombre','Subsistema.nombre','ElementoPool.codigo','Elemento.nombre'),
						'conditions' => array('LOWER(TRIM(Motor.nombre))'=>strtolower(trim($datos[0])),'LOWER(TRIM(Sistema.nombre))' => strtolower(trim($datos[2])),'LOWER(TRIM(Subsistema.nombre))'=>strtolower(trim($datos[3])),'LOWER(TRIM(codigo))' => strtolower(trim($datos[4])), 'LOWER(TRIM(Elemento.nombre))' => strtolower(trim($datos[5]))),
						'recursive' => 1
					));
					if (!isset($resultado['ElementoPool'])) {
						$temp["td"] = 'background-color: yellow; color: black;';
					} else {
						$id_e[] = $resultado['ElementoPool']["id"];
					}
					$temp["Accion"] = '';
					if ($temp["td"] != '') {
						$temp["Accion"] = "<input type=\"checkbox\" class=\"matriz_opciones\" name=\"agregar[]\" value=\"$i\" title=\"Marcar para agregar\" />";
					}
					
					if (is_array($temp))
						$data[] = $temp;
					$i++;
				} 
				$id_e = implode(",", $id_e);
				$resultados = $this->ElementoPool->find('all', array(
					'fields' => array('ElementoPool.id', 'Motor.nombre', 'Sistema.nombre','Subsistema.nombre', 'ElementoPool.codigo', 'Elemento.nombre', 'ElementoPool.e'),
					'order' => array('Motor.nombre', 'Sistema.nombre', 'Subsistema.nombre', 'Elemento.nombre'),
					'conditions' => array("ElementoPool.id NOT IN ($id_e)"),
					'recursive' => 1
				));
				$this->set('existentes', $resultados);
			} else {
				$data = array();
			}
		}
		
		$this->set('motores', $this->Motor->find('all', array('order' => 'nombre','conditions'=>array("Motor.e='1'"), 'recursive' => -1)));
		$this->set('elementos', $this->Elemento->find('all', array('order' => 'nombre','conditions'=>array("Elemento.e='1'"), 'recursive' => -1)));
		$this->set('sistemas', $this->Sistema->find('all', array('order' => 'nombre','conditions'=>array("Sistema.e='1'"), 'recursive' => -1)));
		$this->set('subsistemas', $this->Subsistema->find('all', array('order' => 'nombre','conditions'=>array("Subsistema.e='1'"), 'recursive' => -1)));
		$this->set('data', $data);
	}
	
	public function elementopoolprocess() {
		$this->layout = null;
		$data = array();
		$this->loadModel('ElementoPool');
		$this->loadModel('Motor');
		$this->loadModel('Sistema');
		$this->loadModel('Subsistema');
		$this->loadModel('Elemento');
		$this->set('filtro', '1');
		if (count($this->request->data)) {
			if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"]) && count($this->request->data["agregar"]) > 0) {
				$file = new File($this->request->data["Herramientas"]["hash_file"], false, 0644);
				$data = $file->read(true, 'r');
				$tmp = explode("\n", $data);
				$data = array();
				$i = 0;
				unset($tmp[0]);
				foreach($tmp as $fila) {
					$datos = explode(";", utf8_encode($fila));
					if (strtolower(trim($datos[6])) != 'x') {
						continue;
					}
					$i++;
					if (isset($this->request->data["agregar"]) && is_array($this->request->data["agregar"])) {
						if (array_search($i, $this->request->data["agregar"]) !== FALSE) {
							$data = array();
							$data["motor_id"] = -1;
							$data["sistema_id"] = -1;
							$data["subsistema_id"] = -1;
							$data["elemento_id"] = -1;
							$data["diagnostico_id"] = -1;
							//$datos = explode(";", utf8_encode($fila));
							//$temp["td"] = '';
							$data["Motor"] = $datos[0];
							$data["Codigo"] = $datos[4];
							$resultado = $this->Motor->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[0]))),
								'recursive' => -1
							));
							if (isset($resultado['Motor'])) {
								$data["motor_id"] = $resultado['Motor']["id"];
							} else {
								$this->Motor->create();
								$this->Motor->save(array("nombre" => trim($datos[0])));
								if (isset($this->Motor->id)) {
									$data["motor_id"] = $this->Motor->id;
								}
							}
							$data["Sistema"] = $datos[2];
							$resultado = $this->Sistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[2]))),
								'recursive' => -1
							));
							if (isset($resultado['Sistema'])) {
								$data["sistema_id"] = $resultado['Sistema']["id"];
							} else {
								$this->Sistema->create();
								$this->Sistema->save(array("nombre" => trim($datos[2])));
								if (isset($this->Sistema->id)) {
									$data["sistema_id"] = $this->Sistema->id;
								}
							}
							$temp["Subsistema"] = $datos[3];
							$resultado = $this->Subsistema->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[3]))),
								'recursive' => -1
							));
							if (isset($resultado['Subsistema'])) {
								$data["subsistema_id"] = $resultado['Subsistema']["id"];;
							} else {
								$this->Subsistema->create();
								$this->Subsistema->save(array("nombre" => trim($datos[3])));
								if (isset($this->Subsistema->id)) {
									$data["subsistema_id"] = $this->Subsistema->id;
								}
							}
							$temp["Elemento"] = $datos[5];
							$resultado = $this->Elemento->find('first', array(
								'conditions' => array('LOWER(TRIM(nombre))' => strtolower(trim($datos[5]))),
								'recursive' => -1
							));
							if (isset($resultado['Elemento'])) {
								$data["elemento_id"] = $resultado['Elemento']["id"];
							} else {
								$this->Elemento->create();
								$this->Elemento->save(array("nombre" => trim($datos[5])));
								if (isset($this->Elemento->id)) {
									$data["elemento_id"] = $this->Elemento->id;
								}
							}
							$resultado = $this->ElementoPool->find('first', array(
								'conditions' => array('motor_id' => $data["motor_id"],'sistema_id' => $data["sistema_id"],'subsistema_id' => $data["subsistema_id"],'elemento_id' => $data["elemento_id"],'LOWER(TRIM(codigo))' => strtolower(trim($datos[4]))),
								'recursive' => -1
							));
							if (!isset($resultado['ElementoPool'])) {
								$this->ElementoPool->create();
								$this->ElementoPool->save(array("motor_id" => $data["motor_id"], "sistema_id" => $data["sistema_id"], "subsistema_id" => $data["subsistema_id"], "elemento_id" => $data["elemento_id"],'codigo' => $datos[4], "e" => "1"));
							}
						}
					}
				}
			}
			$db = ConnectionManager::getDataSource('default');
			if (isset($this->request->data["quitar"]) && is_array($this->request->data["quitar"]) && count($this->request->data["quitar"]) > 0) {
				foreach($this->request->data["quitar"] as $key => $value) {
					$query = "update elemento_pool set e = '0' where id = " . $value;
					$result = $db->query($query); 
				}
			}
			if (isset($this->request->data["deshacer"]) && is_array($this->request->data["deshacer"]) && count($this->request->data["deshacer"]) > 0) {
				foreach($this->request->data["deshacer"] as $key => $value) {
					$query = "update elemento_pool set e = '1' where id = " . $value;
					$result = $db->query($query);
				}
			}
			$this->Session->setFlash('Datos de archivo cargado con éxito!!', 'guardar_exito');
			$this->redirect('/Herramientas/ElementoPool');
		}
	}
	
	
	public function clavetemporal(){
		$this->set('titulo', 'Clave Temporal');
	}
}

?>