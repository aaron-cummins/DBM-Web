<?php
	set_time_limit(600);
	ini_set('memory_limit', '1024M');
	App::uses('ConnectionManager', 'Model'); 
	App::uses('CakeEmail', 'Network/Email');
	App::import('Controller', 'Utilidades');
	App::import('Controller', 'UtilidadesReporte');
	App::import('Vendor', 'Classes/PHPExcel');

	class ReporteController extends AppController {
		public $components = array('AWSSES','Session');

		public function efectividad_trabajos($faena_nombre = "", $mes = "") {
			$this->set("titulo", "Reporte Efectividad de Trabajos");
			$this->loadModel('Planificacion');
			
			if (isset($this->request->data['mes'])) {
				$date = strtotime($this->request->data['mes'] . '-01');
			} else {
				$date = strtotime(date("Y-m") . '-01');
			}
			
			if ($mes != "") {
				$date = strtotime($mes . '-01');
			}
			
			$mes = date("m", $date);
			$año = date("Y", $date);
			
			$this->set('mes', date("Y-m", $date));
			
			$intervenciones_p = $this->Planificacion->find('all', array(
								'conditions' => array(
								'UPPER(Planificacion.tipointervencion)' => array('RP', 'MP', 'BL'),
								"not" => array ("Planificacion.fecha" => null),
								"EXTRACT(MONTH FROM Planificacion.fecha) = $mes AND EXTRACT(YEAR FROM Planificacion.fecha) = $año",
								'estado >= 2'),
								'recursive' => 1));
			$intervenciones_r = $this->Planificacion->find('all', array(
								'conditions' => array(
								'UPPER(Planificacion.tipointervencion)' => array('RP', 'MP', 'BL'),
								"not" => array ("Planificacion.fecha" => null),
								"EXTRACT(MONTH FROM Planificacion.fecha) = $mes AND EXTRACT(YEAR FROM Planificacion.fecha) = $año",
								'estado > 2'),
								'recursive' => 1));
								
			$realizados = array();
			$realizados_detalle = array();
			$programados = array();
			$programados_detalle = array();
			
			foreach ($intervenciones_p as $intervencion) {
				if (!isset($programados[$intervencion["Faena"]["nombre"]])) {
					$programados[$intervencion["Faena"]["nombre"]] = 1;
				} else {
					$programados[$intervencion["Faena"]["nombre"]]++;
				}
				
				if (!isset($programados_detalle[$intervencion["Faena"]["nombre"]][strtoupper($intervencion["Planificacion"]["tipointervencion"])])) {
					$programados_detalle[$intervencion["Faena"]["nombre"]][strtoupper($intervencion["Planificacion"]["tipointervencion"])] = 1;
				} else {
					$programados_detalle[$intervencion["Faena"]["nombre"]][strtoupper($intervencion["Planificacion"]["tipointervencion"])]++;
				}
			}
			
			$this->set("programados", $programados);
			$this->set("programados_detalle", $programados_detalle);
			
			foreach ($intervenciones_r as $intervencion) {
				if (!isset($realizados[$intervencion["Faena"]["nombre"]])) {
					$realizados[$intervencion["Faena"]["nombre"]] = 1;
				} else {
					$realizados[$intervencion["Faena"]["nombre"]]++;
				}
				
				if (!isset($realizados_detalle[$intervencion["Faena"]["nombre"]][strtoupper($intervencion["Planificacion"]["tipointervencion"])])) {
					$realizados_detalle[$intervencion["Faena"]["nombre"]][strtoupper($intervencion["Planificacion"]["tipointervencion"])] = 1;
				} else {
					$realizados_detalle[$intervencion["Faena"]["nombre"]][strtoupper($intervencion["Planificacion"]["tipointervencion"])]++;
				}
			}
			
			$this->set("realizados", $realizados);
			$this->set("realizados_detalle", $realizados_detalle);
			
			// Generacion de grafico con nueva libreria 
			$grafico_data = NULL;		
			$grafico_data = array();
			$grafico_data["cols"][0]["label"] = "Faenas";
			$grafico_data["cols"][0]["type"] = "string";
			$grafico_data["cols"][1]["label"] = "Planificados";
			$grafico_data["cols"][1]["type"] = "number";
			$grafico_data["cols"][2]["label"] = "Ejecutados";
			$grafico_data["cols"][2]["type"] = "number";
			$i = 0;
			foreach ($realizados as $key => $value) { 
				$grafico_data["rows"][$i]["c"][0]["v"] = $key;
				$grafico_data["rows"][$i]["c"][1]["v"] = $programados[$key];
				$grafico_data["rows"][$i]["c"][2]["v"] = $realizados[$key];
			}
			
			$this->set("grafico_data", $grafico_data);
			
			if ($faena_nombre != "") {
				$this->set("desplegar_detalle", "1");
				$this->set("faena_nombre", $faena_nombre);
				
				$intervenciones_p = $this->Planificacion->find('all', array(
								'conditions' => array(
								'UPPER(Faena.nombre)' => strtoupper($faena_nombre),
								'UPPER(Planificacion.tipointervencion)' => array('RP', 'MP', 'BL'),
								"not" => array ("Planificacion.fecha" => null),
								"EXTRACT(MONTH FROM Planificacion.fecha) = $mes AND EXTRACT(YEAR FROM Planificacion.fecha) = $año",
								'estado >= 2'),
								'recursive' => 1));
				$intervenciones_r = $this->Planificacion->find('all', array(
								'conditions' => array(
								'UPPER(Faena.nombre)' => strtoupper($faena_nombre),
								'UPPER(Planificacion.tipointervencion)' => array('RP', 'MP', 'BL'),
								"not" => array ("Planificacion.fecha" => null),
								"EXTRACT(MONTH FROM Planificacion.fecha) = $mes AND EXTRACT(YEAR FROM Planificacion.fecha) = $año",
								'estado > 2'),
								'recursive' => 1));
								
				$mp["P"] = 0;
				$mp["R"] = 0;
				
				$bl["P"] = 0;
				$bl["R"] = 0;
				
				$rp["P"] = 0;
				$rp["R"] = 0;
				
				foreach ($intervenciones_p as $intervencion) { 
					if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "MP") {
						$mp["P"]++;
					} elseif (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "BL") {
						$bl["P"]++;
					} elseif (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "RP") {
						$rp["P"]++;
					}
				}
				
				foreach ($intervenciones_r as $intervencion) { 
					if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "MP") {
						$mp["R"]++;
					} elseif (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "BL") {
						$bl["R"]++;
					} elseif (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "RP") {
						$rp["R"]++;
					}
				}
				
				// Graf detalle planificados	
				/*
				$grafico_data = NULL;		
				$grafico_data = array();
				$grafico_data["chart"]["caption"] = "Detalle Planificados";
				$grafico_data["chart"]["showvalues"] = "1";
				$grafico_data["chart"]["legendposition"] = "bottom";
				$grafico_data["chart"]["showlegend"] = "1";

				$grafico_data["data"][0]["label"] = "MP";
				$grafico_data["data"][0]["value"] = $mp["P"];
				$grafico_data["data"][1]["label"] = "BL";
				$grafico_data["data"][1]["value"] = $bl["P"];
				$grafico_data["data"][2]["label"] = "RP";
				$grafico_data["data"][2]["value"] = $rp["P"];	
				*/
				$grafico_data = NULL;		
				$grafico_data = array();
				$grafico_data["cols"][0]["label"] = "Tipo";
				$grafico_data["cols"][0]["type"] = "string";
				$grafico_data["cols"][1]["label"] = "Trabajos";
				$grafico_data["cols"][1]["type"] = "number";
				$grafico_data["rows"][0]["c"][0]["v"] = "MP";
				$grafico_data["rows"][0]["c"][1]["v"] = $mp["P"];
				$grafico_data["rows"][1]["c"][0]["v"] = "BL";
				$grafico_data["rows"][1]["c"][1]["v"] = $bl["P"];
				$grafico_data["rows"][2]["c"][0]["v"] = "RP";
				$grafico_data["rows"][2]["c"][1]["v"] = $rp["P"];	
				
				$this->set("grafico_detalle_1", $grafico_data);	
				
				// Graf detalle realizados
/*				
				$grafico_data = NULL;		
				$grafico_data = array();
				$grafico_data["chart"]["caption"] = "Detalle Realizados";
				$grafico_data["chart"]["showvalues"] = "1";
				$grafico_data["chart"]["legendposition"] = "bottom";
				$grafico_data["chart"]["showlegend"] = "1";

				$grafico_data["data"][0]["label"] = "MP";
				$grafico_data["data"][0]["value"] = $mp["R"];
				$grafico_data["data"][1]["label"] = "BL";
				$grafico_data["data"][1]["value"] = $bl["R"];
				$grafico_data["data"][2]["label"] = "RP";
				$grafico_data["data"][2]["value"] = $rp["R"];	
				*/
				$grafico_data = NULL;		
				$grafico_data = array();
				$grafico_data["cols"][0]["label"] = "Tipo";
				$grafico_data["cols"][0]["type"] = "string";
				$grafico_data["cols"][1]["label"] = "Trabajos";
				$grafico_data["cols"][1]["type"] = "number";
				$grafico_data["rows"][0]["c"][0]["v"] = "MP";
				$grafico_data["rows"][0]["c"][1]["v"] = $mp["R"];
				$grafico_data["rows"][1]["c"][0]["v"] = "BL";
				$grafico_data["rows"][1]["c"][1]["v"] = $bl["R"];
				$grafico_data["rows"][2]["c"][0]["v"] = "RP";
				$grafico_data["rows"][2]["c"][1]["v"] = $rp["R"];	
				
				$this->set("grafico_detalle_2", $grafico_data);			
			} else {
				$this->set("desplegar_detalle", "0");	
			}
			
			/*
			{
			   "chart": {
				  "caption": "Eventos Planificados Vs Ejecutados",
				  "xAxisname": "Faenas",
				  "yAxisName": "Eventos"
			   },
			   "categories": [
				  {
					 "category": [
						{
						   "label": "Faena 1"
						},
						{
						   "label": "Faena 2"
						}
					 ]
				  }
			   ],
			   "dataset": [
				  {
					 "seriesname": "N° Eventos Planificados",
					 "data": [
						{
						   "value": "10000"
						},
						{
						   "value": "11500"
						}
					 ]
				  },
				  {
					 "seriesname": "N° Eventos Realizados",
					 "data": [
						{
						   "value": "25400"
						},
						{
						   "value": "29800"
						}
					 ]
				  }
			   ]
			}
			*/
		}
		
		public function control_detenciones(){
			$this->set("titulo", "Reporte Control de Detenciones");
			$this->loadModel('Unidad');
			$this->loadModel('Planificacion');
			$util = new UtilidadesController();
			$unidades = $this->Unidad->find('all', array('order' => 'Unidad.unidad ASC', 'recursive' => 1));
			$this->set("unidades", $unidades);
			$faena_id = @$this->request->data('faenaid');
			
			/*if (isset($this->request->data['mes'])) {
				$date = strtotime($this->request->data['mes'] . '-01');
			} else {
				$date = strtotime(date("Y-m") . '-01');
			}*/
			
			if (isset($this->request->data['mes'])) {
				$mes = $this->request->data['mes'];
			} else {
				$mes = date("m");
			}
			
			if (isset($this->request->data['anio'])) {
				$año = $this->request->data['anio'];
			} else {
				$año = date("Y");
			}
			
			if (isset($this->request->data['flotaid'])) {
				$flota_id = $this->request->data['flotaid'];
			} else {
				$flota_id = "0";
			}
			
			//$date = strtotime($anio.'-'.$mes. '-01');
			
			$faena = "Planificacion.faena_id = $faena_id";
			if ($faena_id == "") {
				$faena = "";
			}
			
			$flota = "Planificacion.flota_id = $flota_id";
			if ($flota_id == "0") {
				$flota = "";
			}
			
			$estado = @$this->request->data['estado'];
			if ($estado != "") {
				$estado = " Planificacion.estado=$estado ";
			}else{
				$estado = " Planificacion.estado IN (4,7) ";
			}
			// Toma todos!!!
			
			//$this->set('mes', date("Y-m", $date));
			
			$date = strtotime($año.'-'.$mes. '-01');
			$this->set("dias", cal_days_in_month(1, date("m", $date), date("Y", $date)));
			$label_unidad = array();
			$intervenciones = $this->Planificacion->find('all', array(
								'fields' => array('Planificacion.fecha','Planificacion.estado', 'Planificacion.unidad_id', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion', 'Planificacion.sintoma_id', 'Planificacion.padre', 'Planificacion.id', 'Unidad.unidad', 'Planificacion.json'),
								'conditions' => array(
								"not" => array ("Planificacion.fecha" => null),
								$faena,
								$flota,
								$estado,
								"EXTRACT(MONTH FROM Planificacion.fecha) = $mes AND EXTRACT(YEAR FROM Planificacion.fecha) = $año"),
								'order' => 'Planificacion.id ASC',
								'recursive' => 1));
								
			$resultados = array();
			$grafico_data2 = array();
			foreach($intervenciones as $intervencion) {
				$dia = intval(date("d", strtotime($intervencion["Planificacion"]["fecha"])));
				$mes_ = date("m", strtotime($intervencion["Planificacion"]["fecha"]));
				$año_ = date("Y", strtotime($intervencion["Planificacion"]["fecha"]));
				
				if ($mes != $mes_ || $año != $año_) {
					continue;
				}
				$comentarios = '';
				try {
					$json = json_decode($intervencion["Planificacion"]["json"], true);
					$comentarios = @$json["comentarios"].@$json["comentario"];
				} catch (Exception $e) {}
				
				if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'MP') {
					if (isset($json["tipo_programado"])) {
						if ($json["tipo_programado"] == "1500") {
							$sintoma = "Overhaul";
						} else {
							$sintoma = 'MP '.$json["tipo_programado"];
						}	
					} else {					
						if ($intervencion['Planificacion']['tipomantencion'] == "1500") {
							$sintoma = "Overhaul";
						} else {
							$sintoma = 'MP '.$intervencion['Planificacion']['tipomantencion'];
						}
					}
				} elseif (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL') {
					$sintoma = $util->getBacklogDescripcion($intervencion['Planificacion']['id']);
				} else {
					$sintoma = $util->getSintoma($intervencion['Planificacion']['sintoma_id']);
				}
				
				$label_unidad[$intervencion["Planificacion"]["unidad_id"]] = $intervencion["Unidad"]["unidad"];
				
				if ($intervencion["Planificacion"]["padre"] != NULL && $intervencion["Planificacion"]["padre"] != "" && $intervencion["Planificacion"]["padre"] != "NULL") {
					$resultados[$intervencion["Planificacion"]["unidad_id"]][$dia][] = array("c".strtoupper($intervencion["Planificacion"]["tipointervencion"]),$sintoma,$comentarios,$intervencion["Planificacion"]["id"]);
				} else {
					$resultados[$intervencion["Planificacion"]["unidad_id"]][$dia][] = array(strtoupper($intervencion["Planificacion"]["tipointervencion"]),$sintoma,$comentarios,$intervencion["Planificacion"]["id"]);
				}
				
				if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "RI") {
					if (!isset($grafico_data2[$intervencion["Planificacion"]["unidad_id"]])) {
						$grafico_data2[$intervencion["Planificacion"]["unidad_id"]] = 1;
					} else {
						$grafico_data2[$intervencion["Planificacion"]["unidad_id"]]++;
					}
				}
				
			}
			
			$this->set("resultados", $resultados);
			$this->set("intervenciones", $intervenciones);
			$this->set("faena_id", $faena_id);
			$this->set("estado", @$this->request->data['estado']);
			
			// Graficos Imprevistos por Equipo
			/*
			Gráfico N° 1: Barras ordenas desde el más crítico
			Ranking de equipos según RI
			Eje X : Nombres de Equipos 
			Eje Y: N° Imprevistos
			
			************** verificar si se deben tomar RI o RI mas cRI
			*/
			arsort($grafico_data2);
			
			/*$grafico_data = array();
			$grafico_data["chart"]["caption"] = "Imprevistos por Equipo";
			$grafico_data["chart"]["xAxisname"] = "Equipo";
			$grafico_data["chart"]["yAxisName"] = "N° Imprevistos";
			$i = 0;
			foreach ($grafico_data2 as $key => $value) {
				if ($value == 1) {
					continue;
				}
				$grafico_data["data"][$i]["label"] = $label_unidad[$key]."";
				$grafico_data["data"][$i++]["value"] = $value;
			}*/
			
			$grafico_data = NULL;		
			$grafico_data = array();
			$grafico_data["cols"][0]["label"] = "Equipo";
			$grafico_data["cols"][0]["type"] = "string";
			$grafico_data["cols"][1]["label"] = "Imprevistos";
			$grafico_data["cols"][1]["type"] = "number";
			
			$i = 0;
			foreach ($grafico_data2 as $key => $value) {
				if ($value == 1) {
					continue;
				}
				$grafico_data["rows"][$i]["c"][0]["v"] = $label_unidad[$key]."";
				$grafico_data["rows"][$i++]["c"][1]["v"] = $value;
			}
			
			$this->loadModel('UnidadDetalle');
			$flotas = $this->UnidadDetalle->find('all', array('fields'=>array('DISTINCT flota_id','flota','faena_id'), 'order' => 'flota', 'recursive' => -1));
			
			$this->set('flota_id', $flota_id);
			$this->set('flotas', $flotas);
			
			$this->set("grafico_data", $grafico_data);
			$this->set("mes", $mes);
			$this->set("anio", $año);
		}
		
		public function control_detenciones_xls(){
			//$this->set("titulo", "Reporte Control de Detenciones");
			$this->layout = null;
			$this->loadModel('Unidad');
			$this->loadModel('Planificacion');
			$util = new UtilidadesController();
			$unidades = $this->Unidad->find('all', array('order' => 'Unidad.unidad ASC', 'recursive' => 1));
			$this->set("unidades", $unidades);
			$faena_id = @$this->request->query('faenaid');
			
			/*if (isset($this->request->data['mes'])) {
				$date = strtotime($this->request->data['mes'] . '-01');
			} else {
				$date = strtotime(date("Y-m") . '-01');
			}*/
			
			if (isset($this->request->query['mes'])) {
				$mes = $this->request->query['mes'];
			} else {
				$mes = date("m");
			}
			
			if (isset($this->request->query['anio'])) {
				$año = $this->request->query['anio'];
			} else {
				$año = date("Y");
			}
			
			if (isset($this->request->query['flotaid'])) {
				$flota_id = $this->request->query['flotaid'];
			} else {
				$flota_id = "0";
			}
			
			//$date = strtotime($anio.'-'.$mes. '-01');
			
			$faena = "Planificacion.faena_id = $faena_id";
			if ($faena_id == "") {
				$faena = "";
			}
			
			$flota = "Planificacion.flota_id = $flota_id";
			if ($flota_id == "0") {
				$flota = "";
			}
			
			$estado = @$this->request->query['estado'];
			if ($estado != "") {
				$estado = "Planificacion.estado=$estado";
			}
			
			
			//$this->set('mes', date("Y-m", $date));
			
			$date = strtotime($año.'-'.$mes. '-01');
			$this->set("dias", cal_days_in_month(1, date("m", $date), date("Y", $date)));
			$label_unidad = array();
			$intervenciones = $this->Planificacion->find('all', array(
								'fields' => array('Planificacion.fecha','Planificacion.estado', 'Planificacion.unidad_id', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion', 'Planificacion.sintoma_id', 'Planificacion.padre', 'Planificacion.id', 'Unidad.unidad', 'Planificacion.json'),
								'conditions' => array(
								"not" => array ("Planificacion.fecha" => null),
								$faena,
								$flota,
								$estado,
								"EXTRACT(MONTH FROM Planificacion.fecha) = $mes AND EXTRACT(YEAR FROM Planificacion.fecha) = $año"),
								'order' => 'Planificacion.id ASC',
								'recursive' => 1));
								
			$resultados = array();
			$grafico_data2 = array();
			foreach($intervenciones as $intervencion) {
				$dia = intval(date("d", strtotime($intervencion["Planificacion"]["fecha"])));
				$mes_ = date("m", strtotime($intervencion["Planificacion"]["fecha"]));
				$año_ = date("Y", strtotime($intervencion["Planificacion"]["fecha"]));
				
				if ($mes != $mes_ || $año != $año_) {
					continue;
				}
				$comentarios = '';
				try {
					$json = json_decode($intervencion["Planificacion"]["json"], true);
					$comentarios = @$json["comentarios"].@$json["comentario"];
				} catch (Exception $e) {}
				
				if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'MP') {
					if (isset($json["tipo_programado"])) {
						if ($json["tipo_programado"] == "1500") {
							$sintoma = "Overhaul";
						} else {
							$sintoma = 'MP '.$json["tipo_programado"];
						}	
					} else {					
						if ($intervencion['Planificacion']['tipomantencion'] == "1500") {
							$sintoma = "Overhaul";
						} else {
							$sintoma = 'MP '.$intervencion['Planificacion']['tipomantencion'];
						}
					}
				} elseif (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL') {
					$sintoma = $util->getBacklogDescripcion($intervencion['Planificacion']['id']);
				} else {
					$sintoma = $util->getSintoma($intervencion['Planificacion']['sintoma_id']);
				}
				
				$label_unidad[$intervencion["Planificacion"]["unidad_id"]] = $intervencion["Unidad"]["unidad"];
				
				if ($intervencion["Planificacion"]["padre"] != NULL && $intervencion["Planificacion"]["padre"] != "" && $intervencion["Planificacion"]["padre"] != "NULL") {
					$resultados[$intervencion["Planificacion"]["unidad_id"]][$dia][] = array("c".strtoupper($intervencion["Planificacion"]["tipointervencion"]),$sintoma,$comentarios,$intervencion["Planificacion"]["id"]);
				} else {
					$resultados[$intervencion["Planificacion"]["unidad_id"]][$dia][] = array(strtoupper($intervencion["Planificacion"]["tipointervencion"]),$sintoma,$comentarios,$intervencion["Planificacion"]["id"]);
				}
				
				if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "RI") {
					if (!isset($grafico_data2[$intervencion["Planificacion"]["unidad_id"]])) {
						$grafico_data2[$intervencion["Planificacion"]["unidad_id"]] = 1;
					} else {
						$grafico_data2[$intervencion["Planificacion"]["unidad_id"]]++;
					}
				}
				
			}
			
			$this->set("resultados", $resultados);
			$this->set("intervenciones", $intervenciones);
			$this->set("faena_id", $faena_id);
			$this->set("estado", @$this->request->query['estado']);
			
			$this->loadModel('UnidadDetalle');
			$flotas = $this->UnidadDetalle->find('all', array('fields'=>array('DISTINCT flota_id','flota','faena_id'), 'order' => 'flota', 'recursive' => -1));
			
			$this->set('flota_id', $flota_id);
			$this->set('flotas', $flotas);
			
			//$this->set("grafico_data", $grafico_data);
			$this->set("mes", $mes);
			$this->set("anio", $año);
		}
		
		public function base_report() {
			$this->set("titulo", "Flash Report");
			$this->loadModel('Faena');
			$fecha_inicio = date("Y-m-d",strtotime("-7 days"));
			$fecha_termino = date("Y-m-d");
			$flota_id="";
			$unidad_id="";
			//$mes = date("Y-m");
			
			if (isset($this->request->data) && count($this->request->data) >= 2) {
				$fecha_inicio = $this->request->data["fecha_inicio"];
				$fecha_termino = $this->request->data["fecha_termino"];
				$correlativo = $this->request->data["correlativo"];
				$this->set("correlativo", $correlativo);
				$this->set("duracion_filtro", $this->request->data["duracion"]);
				$q = "";
				if ($correlativo != "") {
					$correlativo = "Planificacion.correlativo_final = $correlativo";	
				}
				if(@$this->request->data["flota_id"]!="0"&&@$this->request->data["flota_id"]!=""){
					$flota_id=$this->request->data["flota_id"];
					$q.=" Planificacion.flota_id=".$this->request->data["flota_id"]." AND ";
				}
				if(@$this->request->data["unidad_id"]!="0"&&@$this->request->data["unidad_id"]!=""){
					$unidad_id=@$this->request->data["unidad_id"];
					$q.=" Planificacion.unidad_id=".$this->request->data["unidad_id"]." AND ";
				}
				
				//if (isset($this->request->data['mes'])) {
				//	$date = strtotime($this->request->data['mes'] . '-01');
				//}
				
				//$mes = date("m", $date);
				//$año = date("Y", $date);			
				
				$this->loadModel('Planificacion');
				if($this->request->data["faena_id"]!='0'){
					$intervenciones = $this->Planificacion->find('all', array(
									'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad'),
									'order' => array('Faena.nombre' => "asc", 'Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
									'conditions' => array(
									$correlativo,
									$q . " 1=1",
									'Planificacion.faena_id' => $this->request->data["faena_id"],
									"not" => array ("Planificacion.fecha" => null),
									"(Planificacion.padre IS NULL OR Planificacion.padre = '')",
									"Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_termino' AND Planificacion.estado IN (4, 5, 6, 7)"),
									'recursive' => 1));
				}else{
					$intervenciones = $this->Planificacion->find('all', array(
									'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad'),
									'order' => array('Faena.nombre' => "asc", 'Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
									'conditions' => array(
									$correlativo,
									$q . " 1=1",
									"not" => array ("Planificacion.fecha" => null),
									"(Planificacion.padre IS NULL OR Planificacion.padre = '')",
									"Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_termino' AND Planificacion.estado IN (4, 5, 6, 7)"),
									'recursive' => 1));
				}
				$this->set("intervenciones", $intervenciones);
				$this->set("faena_id", $this->request->data["faena_id"]);
			} else {
				$this->set("intervenciones", array());
				$this->set("faena_id", "0");
				$this->set("duracion_filtro", '');
			}
			$this->set("flota_id", $flota_id);
			$this->set("unidad_id", $unidad_id);
			$this->set("fecha_inicio", $fecha_inicio);
			$this->set("fecha_termino", $fecha_termino);
			$faenas = $this->Faena->find('list', array('fields'=>array('id','nombre'), 'order' => 'nombre'));
			$this->set('faenas', $faenas);
		}
		
		public function base() {
                        
			$this->layout = 'metronic_principal';
			$this->check_permissions($this);
			$this->set("titulo", "Reporte Base");
			$this->loadModel('ReporteBase');
			$this->loadModel('Faena');
			$this->loadModel('FaenaFlota');
			$this->loadModel('Unidad');
			$this->loadModel('DeltaDetalle');
			$this->loadModel('IntervencionElementos');
			$this->loadModel('IntervencionFechas');
			$this->loadModel('Planificacion');
			$fecha_inicio = date("Y-m-d",strtotime("-7 days"));
			$fecha_termino = date("Y-m-d");
			$flota_id="";
			$unidad_id="";
			$faena_id ='';
			$duracion ='';
			$correlativo = '';
			$duracion = '';
			$limit = "";
			
			try {
				if ($this->request->is('get') && isset($this->request->query) && count($this->request->query) >= 2) {
					$fecha_inicio = $this->request->query["fecha_inicio"];
					$fecha_termino = $this->request->query["fecha_termino"];
					$this->set("duracion_filtro", $this->request->query["duracion"]);
					$conditions = array();
	
					if(@$this->request->query["correlativo"]!=""){
						$correlativo=$this->request->query["correlativo"];
						$conditions["ReporteBase.correlativo"] = $correlativo;
					}
					
					if(@$this->request->query["duracion"]!=""){
						$duracion = $this->request->query["duracion"];
						$conditions["ReporteBase.tiempo >"] = $duracion;
					}
					
					if(@$this->request->query["faena_id"]!=""){
						$faena_id=$this->request->query["faena_id"];
						$conditions["ReporteBase.faena_id"] = $faena_id;
					}
					
					if(@$this->request->query["flota_id"]!="0"&&@$this->request->query["flota_id"]!=""){
						$flota_id = explode("_", $this->request->query['flota_id']);
						$flota_id = $flota_id[1];
						$conditions["ReporteBase.flota_id"] = $flota_id;
					}
					if(@$this->request->query["unidad_id"]!="0"&&@$this->request->query["unidad_id"]!=""){
						$unidad_id = explode("_", $this->request->query['unidad_id']);
						$unidad_id = $unidad_id[2];
						$conditions["ReporteBase.unidad_id"] = $unidad_id;
					}	
					
					if(@$this->request->query["limit"]!=""){
						$limit = $this->request->query["limit"];
					}
					
					$conditions["ReporteBase.fecha_inicio BETWEEN ? AND ? "] = array($fecha_inicio, $fecha_termino);
					
					//$db = ConnectionManager::getDataSource("default"); // name of your database connection
					//$intervenciones = $db->fetchAll("\\copy (SELECT * FROM reporte_base WHERE faena_id = '$faena_id' AND fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_termino') TO '/tmp/reporte_base_andina_from_web.csv' WITH CSV;");
					//$intervenciones = $db->fetchAll("SELECT * FROM reporte_base WHERE faena_id = '$faena_id' AND fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_termino';");
					//print_r($intervenciones);
					//die;
					
					if($limit != "") {		
						$intervenciones = $this->ReporteBase->find('all', array(
										'fields' => array("correlativo", "folio", "faena", "flota", "unidad", "esn", "horometro_cabina", "horometro_motor", "tipo", "actividad", "responsable", "categoria", "fecha_inicio", "hora_inicio", "fecha_termino", "hora_termino", "tiempo", "motivo_llamado", "categoria_sintoma", "sintoma", "sistema", "subsistema", "pos_subsistema", "id_code", "elemento", "pos_elemento", "diagnostico", "solucion", "causa", "cambio_modulo", "evento_finalizado", "lugar_reparacion", "tecnico_1", "tecnico_2", "tecnico_3", "tecnico_4", "tecnico_5", "tecnico_6", "tecnico_7", "tecnico_8", "tecnico_9", "tecnico_10", "supervisor_responsable", "turno", "periodo", "supervisor_aprobador", "status_kch", "comentario_tecnico", "numero_os_sap"),
										'conditions' => $conditions,
										'order' => array('ReporteBase.faena' => "asc", 'ReporteBase.correlativo' => 'asc', 'ReporteBase.folio' => 'asc', 'ReporteBase.fecha_inicio' => 'asc', 'ReporteBase.hora_inicio' => 'asc', 'ReporteBase.id' => 'asc'),
										'limit' => $limit,
										'recursive' => -1));
					} else {
						$intervenciones = $this->ReporteBase->find('all', array(
										'fields' => array("correlativo", "folio", "faena", "flota", "unidad", "esn", "horometro_cabina", "horometro_motor", "tipo", "actividad", "responsable", "categoria", "fecha_inicio", "hora_inicio", "fecha_termino", "hora_termino", "tiempo", "motivo_llamado", "categoria_sintoma", "sintoma", "sistema", "subsistema", "pos_subsistema", "id_code", "elemento", "pos_elemento", "diagnostico", "solucion", "causa", "cambio_modulo", "evento_finalizado", "lugar_reparacion", "tecnico_1", "tecnico_2", "tecnico_3", "tecnico_4", "tecnico_5", "tecnico_6", "tecnico_7", "tecnico_8", "tecnico_9", "tecnico_10", "supervisor_responsable", "turno", "periodo", "supervisor_aprobador", "status_kch", "comentario_tecnico", "numero_os_sap"),
										'conditions' => $conditions,
										'order' => array('ReporteBase.faena' => "asc", 'ReporteBase.correlativo' => 'asc', 'ReporteBase.folio' => 'asc', 'ReporteBase.fecha_inicio' => 'asc', 'ReporteBase.hora_inicio' => 'asc', 'ReporteBase.id' => 'asc'),
										'recursive' => -1));
					}
					
					//$db = ConnectionManager::getDataSource("default"); // name of your database connection
					//$intervenciones = $db->fetchAll("SELECT * FROM reporte_base WHERE faena_id = '$faena_id' AND fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_termino' LIMIT 10000;");
					
					//print_r($intervenciones);
					//die;
					
                                        
                                        
					if(isset($this->request->query["btn-descargar"])){
                                                $this->redirect("/Reporte/Descargar/Excel?t=".time()."&fecha_inicio=$fecha_inicio&fecha_termino=$fecha_termino&faena_id=$faena_id&correlativo=$correlativo&duracion=$duracion&flota_id=$flota_id&unidad_id=$unidad_id");
					}
					
					if(isset($this->request->query["btn-descargar-csv"])){
                                            	$this->redirect("/Reporte/Descargar/CSV?t=".time()."&fecha_inicio=$fecha_inicio&fecha_termino=$fecha_termino&faena_id=$faena_id&correlativo=$correlativo&duracion=$duracion&flota_id=$flota_id&unidad_id=$unidad_id");
					}
					
					//print_r($intervenciones);
					//die;
					
					$this->set("intervenciones", $intervenciones);
					//print_r($intervenciones);
					//die;
				} else {
					$this->set("intervenciones", array());
					$this->set("duracion_filtro", '');
				}
			} catch (Exception $e) {
				$this->logger($this, $e->getMessage());
			}
			
			$this->set("faena_id", $faena_id);
			$this->set(compact("duracion"));
			$this->set(compact("limit"));
			$this->set("flota_id", $flota_id);
			$this->set("unidad_id", $unidad_id);
			$this->set("fecha_inicio", $fecha_inicio);
			$this->set("fecha_termino", $fecha_termino);
			$this->set("correlativo", $correlativo);
			$this->set("duracion", $duracion);
			
			$faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
			$this->set(compact('faenas'));
			
			$flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id","faena_id","Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
		$this->set(compact('flotas'));
			
			$unidades = $this->Unidad->find('all', array('fields' => array("id","flota_id","faena_id","Unidad.unidad"), 'conditions' => array('Unidad.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"),'recursive' => -1));
		$this->set(compact('unidades'));
		}
		
		public function get_hijo($folio_padre = null, &$hijos){
			if($folio_padre == null || $folio_padre == '') {
				return;
			}
			
			$this->loadModel('Faena');
			$this->loadModel('FaenaFlota');
			$this->loadModel('Unidad');
			$this->loadModel('DeltaDetalle');
			$this->loadModel('IntervencionElementos');
			$this->loadModel('IntervencionFechas');
			$this->loadModel('Planificacion');
			$conditions["Planificacion.padre"] = $folio_padre;
			//$conditions["Planificacion.reporte_base"] = NULL;
			$conditions["Planificacion.estado"] = array(4, 5, 6);
			
			$intervencion = $this->Planificacion->find('first', array(
									'fields' => array('Planificacion.json','Planificacion.id','Planificacion.fecha','Planificacion.hora','Planificacion.fecha_termino','Planificacion.hora_termino', 'Planificacion.tipointervencion','Planificacion.tipomantencion','Planificacion.faena_id','Planificacion.flota_id','Planificacion.unidad_id','Planificacion.esn','Planificacion.correlativo_final','Planificacion.folio','Planificacion.sintoma_id','Planificacion.backlog_id','Planificacion.estado','Planificacion.aprobador_id','Planificacion.supervisor_responsable','Faena.nombre', 'Flota.nombre', 'Unidad.unidad','Turno.nombre','Periodo.nombre', 'LugarReparacion.nombre','MotivoLlamado.nombre','Sintoma.nombre','SintomaCategoria.nombre','Planificacion.padre'),
									'conditions' => $conditions,
									'recursive' => 1));
									
			if (isset($intervencion["Planificacion"])) {
				//$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.grupo','DeltaItem.id'),
														 //'conditions' => array('DeltaDetalle.folio' => $intervencion["Planificacion"]["folio"], 'DeltaDetalle.delta_item_id NOT' => array(16, 32)),
														// 'conditions' => array('DeltaDetalle.folio' => $intervencion["Planificacion"]["folio"]),
													// 'recursive' => 1));
														 
				//$elementos = $this->IntervencionElementos->find('all', array('fields' => array('IntervencionElementos.tiempo','IntervencionElementos.id_elemento','Sistema.nombre','Subsistema.nombre','Elemento.nombre','Diagnostico.nombre','Solucion.nombre','Posiciones_Elemento.nombre','Posiciones_Subsistema.nombre','TipoElemento.nombre','IntervencionElementos.tipo_registro'),
														// 'conditions' => array('IntervencionElementos.folio' => $intervencion["Planificacion"]["folio"]),
														// 'recursive' => 1));
														 
				//$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.*'),
														// 'conditions' => array('IntervencionFechas.folio' => $intervencion["Planificacion"]["folio"]),
														 //'recursive' => -1));
														 
				$time_inicio = strtotime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
				$time_termino = strtotime($intervencion['Planificacion']['fecha_termino'] . ' ' . $intervencion['Planificacion']['hora_termino']);
				$tiempo_trabajo = ($time_termino - $time_inicio ) / 3600.0;
				$intervencion["Planificacion"]["tiempo_trabajo"] = $tiempo_trabajo;
				//$intervencion["Deltas"] = $deltas;
				//$intervencion["Elementos"] = $elementos;
				//$intervencion["Fechas"] = $fechas;
				$intervencion["Planificacion"]["json"] = json_decode($intervencion["Planificacion"]["json"], true);
				$hijos[] = $intervencion;
				$this->get_hijo($intervencion["Planificacion"]["folio"], $hijos);
			}
		}
		
		public function descargar($tipo = "Excel") {
			$this->layout = NULL;
			$this->check_permissions($this);
			if (!isset($this->request->query) || count($this->request->query) == 0) {
				return;
			}
			
			$this->loadModel('Faena');
			$this->loadModel('FaenaFlota');
			$this->loadModel('ReporteBase');
			$this->loadModel('Unidad');
			$this->loadModel('DeltaDetalle');
			$this->loadModel('IntervencionElementos');
			$this->loadModel('IntervencionFechas');
			$this->loadModel('Planificacion');

			$duracion = '';
			
			if ($this->request->is('get') && isset($this->request->query) && count($this->request->query) >= 2) {
				$fecha_inicio = $this->request->query["fecha_inicio"];
				$fecha_termino = $this->request->query["fecha_termino"];
				$this->set("duracion_filtro", $this->request->query["duracion"]);
				$conditions = array();

				if(@$this->request->query["correlativo"]!=""){
					$correlativo=$this->request->query["correlativo"];
					$conditions["ReporteBase.correlativo"] = $correlativo;
				}
				
				if(@$this->request->query["duracion"]!=""){
					$duracion = $this->request->query["duracion"];
					$conditions["ReporteBase.tiempo >"] = $duracion;
				}
				
				if(@$this->request->query["faena_id"]!=""){
					$faena_id=$this->request->query["faena_id"];
					$conditions["ReporteBase.faena_id"] = $faena_id;
				} else {
					//$conditions["ReporteBase.faena_id IN"] = $this->Session->read("FaenasFiltro");
				}
				
				if(@$this->request->query["flota_id"]!="0"&&@$this->request->query["flota_id"]!=""){
					$flota_id = $this->request->query['flota_id'];
					$conditions["ReporteBase.flota_id"] = $flota_id;
				}
				if(@$this->request->query["unidad_id"]!="0"&&@$this->request->query["unidad_id"]!=""){
					$unidad_id = $this->request->query['unidad_id'];
					$conditions["ReporteBase.unidad_id"] = $unidad_id;
				}	
				
				if(@$this->request->query["limit"]!=""){
					$limit = $this->request->query["limit"];
				}
				
				$conditions["ReporteBase.fecha_inicio BETWEEN ? AND ? "] = array($fecha_inicio, $fecha_termino);	
			}
			 
			$intervenciones = $this->ReporteBase->find('all', array(
									//'fields' => array("correlativo", "folio", "faena", "flota", "unidad", "esn",  "horometro_cabina", "horometro_motor", "tipo", "actividad", "responsable", "categoria", "fecha_inicio", "hora_inicio", "fecha_termino", "hora_termino", "tiempo", "motivo_llamado", "categoria_sintoma", "sintoma", "sistema", "subsistema", "pos_subsistema", "id_code", "elemento", "pos_elemento", "diagnostico", "solucion", "causa", "cambio_modulo_bitacora", "intervencion_terminada_bitacora", "cambio_modulo_evento", "intervencion_terminada_evento", "lugar_reparacion", "tecnico_1", "tecnico_2", "tecnico_3", "tecnico_4", "tecnico_5", "tecnico_6", "tecnico_7", "tecnico_8", "tecnico_9", "tecnico_10", "supervisor_responsable", "turno", "periodo", "supervisor_aprobador", "estado", "comentario_tecnico","numero_os_sap"),
                                                                        'fields' => array("correlativo", "folio", "faena", "flota", "unidad", "esn",  "horometro_cabina", "horometro_motor", "tipo", "actividad", "responsable", "categoria", "fecha_inicio", "hora_inicio", "fecha_termino", "hora_termino", "tiempo", "motivo_llamado", "categoria_sintoma", "sintoma", "sistema", "subsistema", "pos_subsistema", "id_code", "elemento", "pos_elemento", "diagnostico", "solucion", "causa", "cambio_modulo_evento", "intervencion_terminada_evento", "lugar_reparacion", "tecnico_1", "tecnico_2", "tecnico_3", "tecnico_4", "tecnico_5", "tecnico_6", "tecnico_7", "tecnico_8", "tecnico_9", "tecnico_10", "supervisor_responsable", "turno", "periodo", "supervisor_aprobador", "estado", "comentario_tecnico","numero_os_sap", "codigokch", "pn_entrante", "pn_saliente"),
									'order' => array('ReporteBase.faena' => "asc", 'ReporteBase.correlativo' => 'asc', 'ReporteBase.folio' => 'asc', 'ReporteBase.fecha_inicio' => 'asc', 'ReporteBase.hora_inicio' => 'asc', 'ReporteBase.id' => 'asc'),
                                                                        'conditions' => $conditions,
									'recursive' => -1));
			
                       
                        
			header ('Cache-Control: max-age=0');
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.date('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
                        
                        
                        
			if ($tipo == "Excel") {
				$utilReporte = new UtilidadesReporteController();
				$util = new UtilidadesController();
				PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
				$objPHPExcel = new PHPExcel();
				header ('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header ('Content-Disposition: attachment;filename="Reporte-Base-'.date("Y-m-d").'.xlsx"');
				$objPHPExcel->
			    getProperties()
			        ->setCreator("DBM")
			        ->setLastModifiedBy("DBM")
			        ->setTitle("Reporte Base");
					
				$objPHPExcel->setActiveSheetIndex(0)->setTitle('Reporte Base');
				
				// Encabezados				
				/*$objPHPExcel->getActiveSheet()->setCellValue('AD1', 'Estado Bitácora');
				$objPHPExcel->getActiveSheet()->setCellValue('AF1', 'Estado Evento');

				$objPHPExcel->getActiveSheet()->mergeCells('AD1:AE1');
				$objPHPExcel->getActiveSheet()->mergeCells('AF1:AG1');*/

				//$encabezados = array("Correlativo","Folio","Faena","Flota","Unidad","Esn","Horometro Cabina","Horometro Motor","Tipo","Actividad","Responsable","Categoría","Fecha Inicio","Hora Inicio","Fecha Término","Hora Término","Tiempo","Motivo Llamado","Categoría Sintoma","Sintoma","Sistema","Subsistema","Pos. Subsistema","ID","Elemento","Pos.Elemento","Diagnostico","Solución","Causa","Cambio Módulo","Interverción Terminada", "Cambio Módulo","Interverción Terminada","Lugar Reparación","Tecnico Nº1","Tecnico N°2","Tecnico N°3","Tecnico N°4","Tecnico N°5","Tecnico N°6","Tecnico N°7","Tecnico N°8","Tecnico N°9","Tecnico N°10","Supervisor Responsable","Turno","Período","Supervisor Aprobador","Estado Intervención","Comentario Técnico", "OS SAP");
                                $encabezados = array("Correlativo","Folio","Faena","Flota","Unidad","Esn","Horometro Cabina","Horometro Motor","Tipo","Actividad","Responsable","Categoría","Fecha Inicio","Hora Inicio","Fecha Término","Hora Término","Tiempo","Motivo Llamado","Categoría Sintoma","Sintoma","Sistema","Subsistema","Pos. Subsistema","ID","Elemento","Pos.Elemento","Diagnostico","Solución","Causa", "Cambio Módulo","Interverción Terminada","Lugar Reparación","Tecnico Nº1","Tecnico N°2","Tecnico N°3","Tecnico N°4","Tecnico N°5","Tecnico N°6","Tecnico N°7","Tecnico N°8","Tecnico N°9","Tecnico N°10","Supervisor Responsable","Turno","Período","Supervisor Aprobador","Estado Intervención","Comentario Técnico", "OS SAP", "Codigo KCH", "PN Entrante", "PN Saliente");
                                
				$count = count($encabezados);
				//echo "<pre>";
				//print_r($encabezados);
				//echo "</pre>";
				for($i=0;$i<$count;$i++){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,1,$encabezados[$i]);
				}
				
				$utilReporte->cellColor("A1:AZ1","FF0000","FFFFFF",$objPHPExcel);
				
				// Carga de Datos desde Array
				$dataArray = array();
				$i = 1;
				$filaInicial = 2;
				foreach ($intervenciones as $intervencion) {
					$dataLocal = array();
					unset($intervencion["ReporteBase"]["id"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace(";", ":", $intervencion["ReporteBase"]["comentario_tecnico"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace("\t", "", $intervencion["ReporteBase"]["comentario_tecnico"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace("\r\n", "", $intervencion["ReporteBase"]["comentario_tecnico"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace("\n", "", $intervencion["ReporteBase"]["comentario_tecnico"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace("\r", "", $intervencion["ReporteBase"]["comentario_tecnico"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace('"', '', $intervencion["ReporteBase"]["comentario_tecnico"]);
					$dataLocal = $intervencion["ReporteBase"];
					$dataArray[] = $dataLocal;
					$linea = i+ $filaInicial++;
					
					
					
					// Formateo de linea principal
					if($intervencion["ReporteBase"]["categoria"] == "Inicial") {
						$utilReporte->cellColor("A$linea:AZ$linea","000000","FFFFFF",$objPHPExcel);
					}
					if($intervencion["ReporteBase"]["categoria"] == "Continuación") {
						$utilReporte->cellColor("A$linea:AZ$linea","808080","FFFFFF",$objPHPExcel);
					}
					if($intervencion["ReporteBase"]["categoria"] == "Tiempo") {
						$utilReporte->cellColor("A$linea:AZ$linea","DDEBF7","000000",$objPHPExcel);
					}
					$i++;
				}
				
				$total = count($dataArray) + 1;
				
				//$this->set("intervenciones", $intervenciones);
				//$this->set("objPHPExcel", $objPHPExcel);
				//$this->set("dataArray", $dataArray);
							
				//echo "<pre>";
				//print_r($dataArray);
				//echo "</pre>";
				//exit;
				$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
				$objPHPExcel->getActiveSheet()->getStyle('A1:AZ1')->getFont()->setBold(true);
				//for ($i=1;$i<=$total;$i++){
					//$objPHPExcel->getActiveSheet()->getStyle("Q1:Q".$total)->getNumberFormat()->setFormatCode('#,##0.00');
				//}
				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(11);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(11);
				$objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setWidth(70);
				//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
				$objPHPExcel->setActiveSheetIndex(0);
				$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
				$objWriter->save('php://output');
			} elseif ($tipo == "CSV"){
				$dataArray = array();
				$dataArray[] = array("Correlativo","Folio","Faena","Flota","Unidad","Esn","Horometro Cabina","Horometro Motor","Tipo","Actividad","Responsable","Categoría","Fecha Inicio","Hora Inicio","Fecha Término","Hora Término","Tiempo","Motivo Llamado","Categoría Sintoma","Sintoma","Sistema","Subsistema","Pos. Subsistema","ID","Elemento","Pos.Elemento","Diagnostico","Solución","Causa", "Cambio Módulo","Interverción Terminada","Lugar Reparación","Tecnico Nº1","Tecnico N°2","Tecnico N°3","Tecnico N°4","Tecnico N°5","Tecnico N°6","Tecnico N°7","Tecnico N°8","Tecnico N°9","Tecnico N°10","Supervisor Responsable","Turno","Período","Supervisor Aprobador","Estado Intervención","Comentario Técnico", "OS SAP", "Codigo KCH". "PN Entrante", "PN Saliente");
				foreach ($intervenciones as $intervencion) {
					$dataLocal = array();
					unset($intervencion["ReporteBase"]["id"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace(";", ":", $intervencion["ReporteBase"]["comentario_tecnico"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace("\t", "", $intervencion["ReporteBase"]["comentario_tecnico"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace("\r\n", "", $intervencion["ReporteBase"]["comentario_tecnico"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace("\n", "", $intervencion["ReporteBase"]["comentario_tecnico"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace("\r", "", $intervencion["ReporteBase"]["comentario_tecnico"]);
					$intervencion["ReporteBase"]["comentario_tecnico"] = str_replace('"', '', $intervencion["ReporteBase"]["comentario_tecnico"]);
					$dataLocal = $intervencion["ReporteBase"];
					$dataArray[] = $dataLocal;
				}
				
				// open raw memory as file so no temp files needed, you might run out of memory though
			    $f = fopen('php://memory', 'w'); 
			    // loop over the input array
			    foreach ($dataArray as $line) { 
			        // generate csv lines from the inner arrays
			        fputcsv($f, $line, ";"); 
			    }
			    // reset the file pointer to the start of the file
			    rewind($f);
			    // tell the browser it's going to be a csv file
			    header('Content-Type: application/csv; charset=UTF-8');
			    // tell the browser we want to save it instead of displaying it
			    header('Content-Disposition: attachment;filename="Reporte-Base-'.date("Y-m-d").'.csv"');
			    // make php send the generated csv lines to the browser
			   // ob_end_clean();
			    fpassthru($f);
			}
			exit;
		}
		
		public function generar_base() {
			$this->layout = NULL;
			echo date("d-m-Y h:i:s A");
			echo "\n";
			
			$this->loadModel('Faena');
			$this->loadModel('FaenaFlota');
			$this->loadModel('ReporteBase');
			$this->loadModel('Unidad');
			$this->loadModel('DeltaDetalle');
			$this->loadModel('IntervencionElementos');
			$this->loadModel('IntervencionFechas');
			$this->loadModel('Planificacion');

			$duracion = '';
			
			//if (true) {
			print_r("<pre>");
			$conditions = array();
			$fields = array();
			$order = array();
			$limit = 1000;
			
			$conditions["Planificacion.estado"] = array(4, 5, 6);
			$conditions["Planificacion.reporte_base"] = NULL;
			$conditions["Planificacion.fecha NOT"] = NULL;
			$fields[] = "MIN(Planificacion.fecha) AS min_date";
			$fields[] = "MAX(Planificacion.fecha) AS max_date";
			
			$intervencion = $this->Planificacion->find('first', array(
							  'fields' => $fields,
							  'conditions' => $conditions,
							  'recursive' => -1));
			
			//print_r($intervencion[0]);	
			
			$intervencion = $intervencion[0];
			$conditions["Planificacion.fecha BETWEEN ? AND ? "] = array($intervencion["min_date"], $intervencion["max_date"]);
			//echo "<pre>";
			//print_r($conditions);
			$fields = array();
			$order = array();
			$order["MIN(Planificacion.fecha)"] = "ASC";
			$fields[] = "Planificacion.correlativo_final";
			$fields[] = "COUNT(*) AS trabajos";
			
			$correlativos = $this->Planificacion->find('all', array(
							  'fields' => $fields,
							  'conditions' => $conditions,
							  'order' => $order,
							  'group' => array('Planificacion.correlativo_final'),
							  'limit' => $limit,
							  'recursive' => -1));
			
			$trabajos = array();

			foreach ($correlativos as $k => $v) {
				//print_r($intervencion);
				$correlativo = $v["Planificacion"]["correlativo_final"];
				$correlativos = $v[0]["trabajos"];
				$conditions = array();
				$conditions["Planificacion.id"] = $correlativo;
				
				$intervenciones = $this->Planificacion->find('all', array(
								'fields' => array('Planificacion.horometro_cabina','Planificacion.horometro_motor','Planificacion.json','Planificacion.id','Planificacion.fecha','Planificacion.hora','Planificacion.fecha_termino','Planificacion.hora_termino', 'Planificacion.tipointervencion','Planificacion.tipomantencion','Planificacion.faena_id','Planificacion.flota_id','Planificacion.unidad_id','Planificacion.esn','Planificacion.correlativo_final','Planificacion.folio','Planificacion.sintoma_id','Planificacion.backlog_id','Planificacion.turno_id','Planificacion.periodo_id','Planificacion.estado','Planificacion.aprobador_id','Planificacion.supervisor_responsable','Faena.nombre', 'Flota.nombre', 'Unidad.unidad','Planificacion.reporte_base','Turno.nombre','Periodo.nombre', 'LugarReparacion.nombre','MotivoLlamado.nombre','Sintoma.nombre','SintomaCategoria.nombre'),
								'order' => array('Planificacion.fecha' => 'ASC', 'Planificacion.hora' => 'ASC'),
								'conditions' => $conditions,
								'limit' => $limit,
								'recursive' => 1));
								
				foreach ($intervenciones as $key => $intervencion) {
					$time_inicio = strtotime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					$time_termino = strtotime($intervencion['Planificacion']['fecha_termino'] . ' ' . $intervencion['Planificacion']['hora_termino']);
					$tiempo_trabajo = ($time_termino - $time_inicio ) / 3600.0;
					
					if($duracion != '' && $tiempo_trabajo > $duracion) {
						unset($intervenciones[$key]);
						continue;
					}
					
					$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.grupo','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $intervencion["Planificacion"]["folio"]),
															 'recursive' => 1));
															 
					$elementos = $this->IntervencionElementos->find('all', array('fields' => array('IntervencionElementos.tiempo','IntervencionElementos.id_elemento','Sistema.nombre','Subsistema.nombre','Elemento.nombre','Diagnostico.nombre','Solucion.nombre','Posiciones_Elemento.nombre','Posiciones_Subsistema.nombre','TipoElemento.nombre','IntervencionElementos.tipo_registro'),
															 'conditions' => array('IntervencionElementos.folio' => $intervencion["Planificacion"]["folio"]),
															 'recursive' => 1));
															 
					$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.*'),
															 'conditions' => array('IntervencionFechas.folio' => $intervencion["Planificacion"]["folio"]),
															 'recursive' => -1));
					
					$intervenciones[$key]["Planificacion"]["tiempo_trabajo"] = $tiempo_trabajo;
					$intervenciones[$key]["Deltas"] = $deltas;
					$intervenciones[$key]["Elementos"] = $elementos;
					$intervenciones[$key]["Fechas"] = $fechas;
					$intervenciones[$key]["Planificacion"]["json"] = json_decode($intervencion["Planificacion"]["json"], true);
					$intervenciones[$key]["Hijos"] = array();
					$this->get_hijo($intervenciones[$key]["Planificacion"]["folio"], $intervenciones[$key]["Hijos"]);
					$trabajos[] = $intervenciones[$key];
				}
			}
							
			$utilReporte = new UtilidadesReporteController();
			$util = new UtilidadesController();
			PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
			$objPHPExcel = new PHPExcel();
			/*header ('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header ('Cache-Control: max-age=0');
			header ('Content-Disposition: attachment;filename="Flash-Report-'.date("Y-m-d").'".xlsx');
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.date('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			*/
			$objPHPExcel->
		    getProperties()
		        ->setCreator("SISRAI")
		        ->setLastModifiedBy("SISRAI")
		        ->setTitle("Flash Report");
				
			$objPHPExcel->setActiveSheetIndex(0)->setTitle('Flash Report');
			$dataArray = array();
			$intervenciones = $trabajos;
			//print_r("<pre>");
			//print_r($intervenciones);
			//print_r("</pre>");
			//die();
			$i = 1;
			foreach ($intervenciones as $intervencion) {
				$faena = $intervencion["Faena"]["nombre"];
				$flota = $intervencion["Flota"]["nombre"];
				$unidad = $intervencion["Unidad"]["unidad"];
			
				$fecha = new DateTime($intervencion["Planificacion"]['fecha'] . ' ' . $intervencion["Planificacion"]['hora']);	
				$fecha_termino = new DateTime($intervencion["Planificacion"]['fecha_termino'] . ' ' . $intervencion["Planificacion"]['hora_termino']);
				$diff = $fecha_termino->diff($fecha);
				
				$json = $intervencion["Planificacion"]["json"];
				$lugar_reparacion = $intervencion["LugarReparacion"]["nombre"];
				
				$cambio_modulo = $util->existe_cambio_modulo($intervencion["Planificacion"]["id"]) == true ? "SI" : "NO";
				$evento_finalizado = $util->correlativo_terminado($intervencion["Planificacion"]["id"]) == true ? "SI" : "NO";
				//$tiempo_trabajo = number_format($tiempo_trabajo, 2, ",",".");
				
				$tecnico = array();
				$tecnico["tecnico_1"] = is_numeric(@$json["UserID"]) ? $util->getTecnicoRut($json["UserID"]) : "";
				$tecnico["tecnico_2"] = is_numeric(@$json["TecnicoApoyo02"])?$util->getTecnicoRut(@$json["TecnicoApoyo02"]):"";
				$tecnico["tecnico_3"] = is_numeric(@$json["TecnicoApoyo03"])?$util->getTecnicoRut(@$json["TecnicoApoyo03"]):"";
				$tecnico["tecnico_4"] = is_numeric(@$json["TecnicoApoyo04"])?$util->getTecnicoRut(@$json["TecnicoApoyo04"]):"";
				$tecnico["tecnico_5"] = is_numeric(@$json["TecnicoApoyo05"])?$util->getTecnicoRut(@$json["TecnicoApoyo05"]):"";
				$tecnico["tecnico_6"] = is_numeric(@$json["TecnicoApoyo06"])?$util->getTecnicoRut(@$json["TecnicoApoyo06"]):"";
				$tecnico["tecnico_7"] = is_numeric(@$json["TecnicoApoyo07"])?$util->getTecnicoRut(@$json["TecnicoApoyo07"]):"";
				$tecnico["tecnico_8"] = is_numeric(@$json["TecnicoApoyo08"])?$util->getTecnicoRut(@$json["TecnicoApoyo08"]):"";
				$tecnico["tecnico_9"] = is_numeric(@$json["TecnicoApoyo09"])?$util->getTecnicoRut(@$json["TecnicoApoyo09"]):"";
				$tecnico["tecnico_10"] = is_numeric(@$json["TecnicoApoyo10"])?$util->getTecnicoRut(@$json["TecnicoApoyo10"]):"";
				$aprobador = $util->getTecnicoRut(@$intervencion["Planificacion"]['aprobador_id']);
				$supervisor = $util->getTecnicoRut(@$intervencion["Planificacion"]['supervisor_responsable']);
				$turno = $intervencion["Turno"]["nombre"];
				$periodo = $intervencion["Periodo"]["nombre"];
				$motivo_llamado = $intervencion["MotivoLlamado"]["nombre"];
				$comentarios = "";
				if(@$json["Comentarios"]!=""){
					$comentarios = @$json["Comentarios"];
				}elseif(@$json["Comentario"]!=""){
					$comentarios = @$json["Comentario"];
				}
				
				$comentarios = str_replace(";", ":", $comentarios);
				$comentarios = str_replace("\t", "", $comentarios);
				$comentarios = str_replace("\r\n", "", $comentarios);
				$comentarios = str_replace("\n", "", $comentarios);
				$comentarios = str_replace("\r", "", $comentarios);
				$comentarios = str_replace('"', '', $comentarios);
								
				$sintoma = "";
				$categoria_sintoma = "";
				if (strtoupper($intervencion["Planificacion"]['tipointervencion']) == 'MP') {
					if (isset($json["tipo_programado"])) {
						if ($json["tipo_programado"] == "1500") {
							$sintoma =  "Overhaul";
						} else {
							$sintoma =  $json["tipo_programado"];
						}	
					} else {					
						if ($intervencion["Planificacion"]['tipomantencion'] == "1500") {
							$sintoma =  "Overhaul";
						} else {
							$sintoma =  $intervencion["Planificacion"]['tipomantencion'];
						}
					}
				} elseif (strtoupper($intervencion["Planificacion"]['tipointervencion']) == 'BL') {
					$sintoma = $util->getBacklogDescripcion($intervencion["Planificacion"]['id']);
				} else { 
					$sintoma = $intervencion["Sintoma"]['nombre'];
					$categoria_sintoma = $intervencion["SintomaCategoria"]['nombre'];
				}
				
				/*$horometro_inicial = @$json["horometro_cabina"];
				$horometro_final = 0;
				if (isset($json["horometro_pm"]))
					$horometro_final = @$json["horometro_pm"];
				elseif (isset($json["horometro_final"]))
					$horometro_final = @$json["horometro_final"];
				else 
					$horometro_final = @$json["horometro_cabina"];
				*/
                                $horometro_cabina = $intervencion["Planificacion"]['horometro_cabina'];
				$horometro_motor = $intervencion["Planificacion"]['horometro_motor'];
                                
				//LOGICA ORIGINAL CALCULO ESN
				/*$esn_ = $util->getESN($intervencion["Planificacion"]['faena_id'],$intervencion["Planificacion"]['flota_id'],$util->getMotor($intervencion["Planificacion"]['unidad_id']),$util->getUnidad($intervencion["Planificacion"]['unidad_id']),$intervencion["Planificacion"]['fecha']);
				$esn = $esn_;
				if($esn_==''){
					if (@$json["esn_conexion"] != "") {
						$esn =  @$json["esn_conexion"];
					} elseif (@$json["esn_nuevo"] != "") {
						$esn = @$json["esn_nuevo"];
					} elseif (@$json["esn"] != "") {
						$esn =  @$json["esn"];
					} else {
						$esn =  $intervencion["Planificacion"]['esn'];
					}
					
					if (is_numeric($esn)) {
						$esn = $esn;
					} else {
						if(isset($intervencion["Planificacion"]['padre'])) {
							$esn  = $util->esnPadre($intervencion["Planificacion"]['padre']);
						}
					}
				}*/
				$esn = $util->getESNFromEstadoMotor(
					$intervencion["Planificacion"]['faena_id'], $intervencion["Planificacion"]['flota_id'],
					$intervencion["Planificacion"]['unidad_id'], $intervencion["Planificacion"]['fecha']
				);

				if (is_null($esn)) {
					$this->log('No se encontró ESN en tabla estado_motor para la planificación '.$intervencion["Planificacion"]['id']);
				}
						
				$statuskcc = $util->getEstadoKCH($intervencion["Planificacion"]["estado"]);
				$dataLocal = array();
				$dataLocal["faena_id"] = $intervencion["Planificacion"]["faena_id"];
				$dataLocal["flota_id"] = $intervencion["Planificacion"]["flota_id"];
				$dataLocal["unidad_id"] = $intervencion["Planificacion"]["unidad_id"];
				$dataLocal["correlativo"] = $intervencion["Planificacion"]["id"];
				$dataLocal["folio"] = $intervencion["Planificacion"]["id"];
				$dataLocal["faena"] = $faena;
				$dataLocal["flota"] = $flota;
				$dataLocal["unidad"] = $unidad;
				$dataLocal["esn"] = $esn;
				$dataLocal["horometro_cabina"] = $horometro_cabina;
				$dataLocal["horometro_motor"] = $horometro_motor;
				$dataLocal["tipo"] = $intervencion["Planificacion"]["tipointervencion"];
				$dataLocal["actividad"] = $intervencion["Planificacion"]["tipointervencion"];
				$dataLocal["responsable"] = "";
				$dataLocal["categoria"] = "Inicial";
				$dataLocal["fecha_inicio"] = $fecha->format('Y-m-d');
				$dataLocal["hora_inicio"] = $fecha->format('H:i');
				$dataLocal["fecha_termino"] = $fecha_termino->format('Y-m-d');
				$dataLocal["hora_termino"] = $fecha_termino->format('H:i');
				$dataLocal["tiempo"] = $intervencion["Planificacion"]["tiempo_trabajo"]; // Tiempo trabajo
				$dataLocal["motivo_llamado"] = $motivo_llamado;
				$dataLocal["categoria_sintoma"] = $categoria_sintoma;
				$dataLocal["sintoma"] = $sintoma;
				$dataLocal["sistema"] = "";
				$dataLocal["subsistema"] = "";
				$dataLocal["pos_subsistema"] = "";
				$dataLocal["id_code"] = "";
				$dataLocal["elemento"] = "";
				$dataLocal["pos_elemento"] = "";
				$dataLocal["diagnostico"] = "";
				$dataLocal["solucion"] = "";
				$dataLocal["causa"] = "";
				$dataLocal["cambio_modulo"] = $cambio_modulo;
				$dataLocal["evento_finalizado"] = $evento_finalizado;
				$dataLocal["lugar_reparacion"] = $lugar_reparacion;
				foreach($tecnico as $k=>$v){
					$dataLocal[$k] = $v;
				}
				$dataLocal["supervisor_responsable"] = $supervisor;
				$dataLocal["turno"] = $turno;
				$dataLocal["periodo"] = $periodo;
				$dataLocal["supervisor_aprobador"] = $aprobador;
				$dataLocal["status_kch"] = $statuskcc;
				$dataLocal["comentario_tecnico"] = $comentarios;
				$dataArray[] = $dataLocal;
				
				$linea = count($dataArray) + 1;
				// Formateo de linea principal
				$utilReporte->cellColor("A$linea:AR$linea","000000","FFFFFF",$objPHPExcel);
				
				$time = strtotime($fecha->format('d-m-Y h:i A'));
				$time_termino = strtotime($fecha_termino->format('d-m-Y h:i A'));
				// Delta 1 y Delta 2
				
				if($intervencion["Planificacion"]["tipointervencion"]=='EX'||$intervencion["Planificacion"]["tipointervencion"]=='RI'){
					$utilReporte->generar_delta_1($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"],$intervencion);
					$utilReporte->generar_delta_2($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"],$intervencion);
				}
				
				if($intervencion["Planificacion"]["tipointervencion"]!='MP'){
					$utilReporte->generar_delta_3($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"],$intervencion);
				}
				
				if($intervencion["Planificacion"]["tipointervencion"]!='MP'){
					$utilReporte->generar_get_elementos($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"],$intervencion);
				}
				
				if($intervencion["Planificacion"]["tipointervencion"]!='EX'&&$intervencion["Planificacion"]["tipointervencion"]!='MP'){
					$utilReporte->generar_delta_4($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"],$intervencion);
				}
				
				if($intervencion["Planificacion"]["tipointervencion"]=='MP'&&$intervencion["Planificacion"]["tipomantencion"]!='1500'){
					$utilReporte->generar_delta_mp($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"],$intervencion);
				}
				
				if($intervencion["Planificacion"]["tipointervencion"]!='EX'){
					$utilReporte->generar_espera_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"]);
					$utilReporte->generar_registro_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"]);
					$utilReporte->generar_espera_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"]);
					$utilReporte->generar_registro_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"]);
					$utilReporte->generar_espera_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"]);
					$utilReporte->generar_registro_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"]);
					$utilReporte->generar_registro_prueba_potencia($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"]);
				}
				
				if($intervencion["Planificacion"]["tipointervencion"]!='EX'&&$intervencion["Planificacion"]["tipointervencion"]!='MP'){
					$utilReporte->generar_delta_5($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"],$intervencion);
					$utilReporte->generar_get_elementos_reproceso($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"],$intervencion);
				}
				
				if($intervencion["Planificacion"]["tipointervencion"]=='EX' && !isset($json["Elemento_1"])){
					$tiempo_trabajo = ($time_termino - $time)/3600;
					$dataLocal["actividad"] = "EX";
					$dataLocal["responsable"] = "OEM";
					$dataLocal["categoria"] = "Intervención";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
					$dataLocal["hora_inicio"] = date('H:i', $time);
					$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
					$dataLocal["hora_termino"] = date('H:i', $time_termino);
					$dataLocal["tiempo"] = $tiempo_trabajo;
					$dataLocal["sistema"] = "Tiempo";
					$dataLocal["subsistema"] = "Inicio Interv-Término Interv.";
					$dataLocal["pos_subsistema"] = "";
					$dataLocal["elemento"] = "Intervención";
					$dataArray[] = $dataLocal;
				}
				
				$utilReporte->generar_registro_fluidos($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["id"]);
				
				if (isset($intervencion["Planificacion"]['fecha_operacion'])) {
					$utilReporte->generar_delta_operacion($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["Planificacion"]["folio"]);
				}
				
				foreach ($intervencion["Hijos"] as $intervencion_hijo) {
					$original = $dataLocal;
					$fecha = new DateTime($intervencion_hijo["Planificacion"]['fecha'] . ' ' . $intervencion_hijo["Planificacion"]['hora']);
					$fecha_termino = new DateTime($intervencion_hijo["Planificacion"]['fecha_termino'] . ' ' . $intervencion_hijo["Planificacion"]['hora_termino']);
					$diff = $fecha_termino->diff($fecha);
					$tiempo_trabajo = $diff->h + $diff->i / 60.0;
					$tiempo_trabajo = $tiempo_trabajo + ($diff->days*24);
					$json = $intervencion_hijo["Planificacion"]["json"];
					$tecnico = array();
					$tecnico["tecnico_1"] = is_numeric(@$json["UserID"]) ? $util->getTecnicoRut($json["UserID"]) : "";
					$tecnico["tecnico_2"] = is_numeric(@$json["TecnicoApoyo02"])?$util->getTecnicoRut(@$json["TecnicoApoyo02"]):"";
					$tecnico["tecnico_3"] = is_numeric(@$json["TecnicoApoyo03"])?$util->getTecnicoRut(@$json["TecnicoApoyo03"]):"";
					$tecnico["tecnico_4"] = is_numeric(@$json["TecnicoApoyo04"])?$util->getTecnicoRut(@$json["TecnicoApoyo04"]):"";
					$tecnico["tecnico_5"] = is_numeric(@$json["TecnicoApoyo05"])?$util->getTecnicoRut(@$json["TecnicoApoyo05"]):"";
					$tecnico["tecnico_6"] = is_numeric(@$json["TecnicoApoyo06"])?$util->getTecnicoRut(@$json["TecnicoApoyo06"]):"";
					$tecnico["tecnico_7"] = is_numeric(@$json["TecnicoApoyo07"])?$util->getTecnicoRut(@$json["TecnicoApoyo07"]):"";
					$tecnico["tecnico_8"] = is_numeric(@$json["TecnicoApoyo08"])?$util->getTecnicoRut(@$json["TecnicoApoyo08"]):"";
					$tecnico["tecnico_9"] = is_numeric(@$json["TecnicoApoyo09"])?$util->getTecnicoRut(@$json["TecnicoApoyo09"]):"";
					$tecnico["tecnico_10"] = is_numeric(@$json["TecnicoApoyo10"])?$util->getTecnicoRut(@$json["TecnicoApoyo10"]):"";
					$aprobador = $util->getTecnicoRut(@$intervencion_hijo["Planificacion"]['aprobador_id']);
					$supervisor = $util->getTecnicoRut(@$intervencion_hijo["Planificacion"]['supervisor_responsable']);
					$turno = $intervencion["Turno"]["nombre"];
					$periodo = $intervencion["Periodo"]["nombre"];
					$comentarios = "";
					if(@$json["Comentarios"]!=""){
						$comentarios = @$json["Comentarios"];
					}elseif(@$json["Comentario"]!=""){
						$comentarios = @$json["Comentario"];
					}
					
					$comentarios = str_replace(";", ":", $comentarios);
					$comentarios = str_replace("\t", "", $comentarios);
					$comentarios = str_replace("\r\n", "", $comentarios);
					$comentarios = str_replace("\n", "", $comentarios);
					$comentarios = str_replace("\r", "", $comentarios);
					$comentarios = str_replace('"', '', $comentarios);
					
					/*$horometro_final = 0;
					if (isset($json["horometro_pm"]))
						$horometro_final = @$json["horometro_pm"];
					elseif (isset($json["horometro_final"]))
						$horometro_final = @$json["horometro_final"];
					elseif (isset($json["horometro_cabina"]))
						$horometro_final = @$json["horometro_cabina"];
					else
						$horometro_final = $original["horometro_final"];
					*/
					$statuskcc = $util->getEstadoKCH($intervencion_hijo["Planificacion"]["estado"]);
					$dataLocal = array();
					$dataLocal["faena_id"] = $original["faena_id"];
					$dataLocal["flota_id"] = $original["flota_id"];
					$dataLocal["unidad_id"] = $original["unidad_id"];
					$dataLocal["correlativo"] = $original["correlativo"]; //0
					$dataLocal["folio"] = $intervencion_hijo["Planificacion"]["id"];
					$dataLocal["faena"] = $original["faena"];
					$dataLocal["flota"] = $original["flota"];
					$dataLocal["unidad"] = $original["unidad"];
					$dataLocal["esn"] = $original["esn"];
					$dataLocal["horometro_cabina"] = $original["horometro_cabina"];
					$dataLocal["horometro_motor"] = $original["horometro_motor"];
					$dataLocal["tipo"] = $original["tipo"];
					$dataLocal["actividad"] = "C".$intervencion_hijo["Planificacion"]["tipointervencion"];
					$dataLocal["responsable"] = ""; // 10
					$dataLocal["categoria"] = "Continuación";
					$dataLocal["fecha_inicio"] = $fecha->format('Y-m-d');
					$dataLocal["hora_inicio"] = $fecha->format('H:i');
					$dataLocal["fecha_termino"] = $fecha_termino->format('Y-m-d');
					$dataLocal["hora_termino"] = $fecha_termino->format('H:i');
					$dataLocal["tiempo"] = $intervencion_hijo["Planificacion"]["tiempo_trabajo"]; // Tiempo trabajo
					$dataLocal["motivo_llamado"] = $original["motivo_llamado"];
					$dataLocal["categoria_sintoma"] = $original["categoria_sintoma"];
					$dataLocal["sintoma"] = $original["sintoma"];
					$dataLocal["sistema"] = ""; // 20
					$dataLocal["subsistema"] = "";
					$dataLocal["pos_subsistema"] = "";
					$dataLocal["id_code"] = "";
					$dataLocal["elemento"] = "";
					$dataLocal["pos_elemento"] = "";
					$dataLocal["diagnostico"] = "";
					$dataLocal["solucion"] = "";
					$dataLocal["causa"] = "";
					$dataLocal["cambio_modulo"] = $original["cambio_modulo"];//$cambio_modulo;
					$dataLocal["evento_finalizado"] = $original["evento_finalizado"];// 30;
					$dataLocal["lugar_reparacion"] = $original["lugar_reparacion"];
					foreach($tecnico as $k=>$v){
						$dataLocal[$k] = $v;
					}
					$dataLocal["supervisor_responsable"] = $supervisor;
					$dataLocal["turno"] = $turno;
					$dataLocal["periodo"] = $periodo;
					$dataLocal["supervisor_aprobador"] = $aprobador;
					$dataLocal["status_kch"] = $statuskcc;
					$dataLocal["comentario_tecnico"] = $comentarios;
					
					// Delta continuacion de trabajos
					//$time_anterior = strtotime($original[14]. " " .$original[15]);
					$time_anterior = strtotime($fecha->format('d-m-Y h:i A'));
					$utilReporte->generar_delta_continuacion($time_anterior,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"]);
					$dataArray[] = $dataLocal;
					
					$linea = count($dataArray) + 1;
					// Formateo de linea principal
					$utilReporte->cellColor("A$linea:AR$linea","808080","FFFFFF",$objPHPExcel);
					
					$time = strtotime($fecha->format('d-m-Y h:i A'));
					$time_termino = strtotime($fecha_termino->format('d-m-Y h:i A'));
					
					if($intervencion_hijo["Planificacion"]["tipointervencion"]=='EX'||$intervencion_hijo["Planificacion"]["tipointervencion"]=='RI'){
						$utilReporte->generar_delta_1($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"],$intervencion_hijo);
						$utilReporte->generar_delta_2($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"],$intervencion_hijo);
					}
					
					if($intervencion_hijo["Planificacion"]["tipointervencion"]!='MP'){
						$utilReporte->generar_delta_3($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"],$intervencion_hijo);
					}
					
					if($intervencion_hijo["Planificacion"]["tipointervencion"]!='MP'){
						$utilReporte->generar_get_elementos($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"],$intervencion_hijo);
					}
					
					if($intervencion_hijo["Planificacion"]["tipointervencion"]!='EX'&&$intervencion_hijo["Planificacion"]["tipointervencion"]!='MP'){
						$utilReporte->generar_delta_4($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"],$intervencion_hijo);
					}
					
					if($intervencion_hijo["Planificacion"]["tipointervencion"]=='MP'&&$intervencion_hijo["Planificacion"]["tipomantencion"]!='1500'){
						$utilReporte->generar_delta_mp($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"],$intervencion_hijo);
					}
					
					if($intervencion_hijo["Planificacion"]["tipointervencion"]!='EX'){
						$utilReporte->generar_espera_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"]);
						$utilReporte->generar_registro_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"]);
						$utilReporte->generar_espera_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"]);
						$utilReporte->generar_registro_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"]);
						$utilReporte->generar_espera_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"]);
						$utilReporte->generar_registro_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"]);
						$utilReporte->generar_registro_prueba_potencia($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"]);
					}
					
					if($intervencion_hijo["Planificacion"]["tipointervencion"]!='EX'&&$intervencion_hijo["Planificacion"]["tipointervencion"]!='MP'){
						$utilReporte->generar_delta_5($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"],$intervencion_hijo);
						$utilReporte->generar_get_elementos_reproceso($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"],$intervencion_hijo);
					}
					$utilReporte->generar_registro_fluidos($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["id"]);
					if (isset($intervencion_hijo["Planificacion"]['fecha_operacion'])) {
						$utilReporte->generar_delta_operacion($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion_hijo["Planificacion"]["folio"]);
					}
				}
				
				
			}
			
			$total = count($dataArray) + 1;
			
			$this->set("intervenciones", $intervenciones);
			$this->set("objPHPExcel", $objPHPExcel);
			$this->set("dataArray", $dataArray);
			
			//echo "<pre>";
			//print_r($dataArray);
			//die;
			
			
			foreach($dataArray as $value){
				try {
					if($value["reporte_base"] == NULL) {
						$this->ReporteBase->create();
						$this->ReporteBase->save($value);						
						$data = array();
						$data["id"] = $value["folio"];
						$data["reporte_base"] = 1;
						$this->Planificacion->save($data);
					}
					//print_r($value);
				} catch (Exception $e) {
					//print_r("Error");
					echo $e->getTraceAsString();
					echo $e->getMessage();
					//print_r($value);
				}
				//print_r("-----");
			}
			
			//echo "</pre>";
			echo date("d-m-Y h:i:s A");
			exit;
		}	
				
		public function base_report_xls___() {
			$this->layout = NULL;
			if (!isset($this->request->query) || count($this->request->query) == 0) {
				return;
			}
			$fecha_inicio = NULL;
			$fecha_termino = NULL;
			$this->loadModel('Faena');

			if (isset($this->request->query) && count($this->request->query) >= 2) {
				$fecha_inicio = $this->request->query["fecha_inicio"];
				$fecha_termino = $this->request->query["fecha_termino"];
				$faena_id = $this->request->query["faena_id"];
				$correlativo = $this->request->query["correlativo"];
				$this->set("correlativo", $correlativo);
				$this->set("duracion_filtro", $this->request->query["duracion"]);
				if ($correlativo != "") {
					$correlativo = "Planificacion.id = $correlativo";	
				}
				
				$this->loadModel('Planificacion');
				if($this->request->query["faena_id"] != "0"){
					$intervenciones = $this->Planificacion->find('all', array(
									'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad'),
									'order' => array('Faena.nombre' => "asc", 'Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
									'conditions' => array(
									$correlativo,
									'Planificacion.faena_id' => $faena_id,
									"not" => array ("Planificacion.fecha" => null),
									"(Planificacion.padre IS NULL OR Planificacion.padre = '')",
									"Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_termino'",
									'estado IN (4, 5, 6, 10,7)'),
									'recursive' => 1));
				}else{
					$intervenciones = $this->Planificacion->find('all', array(
									'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad'),
									'order' => array('Faena.nombre' => "asc", 'Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
									'conditions' => array(
									$correlativo,
									"not" => array ("Planificacion.fecha" => null),
									"(Planificacion.padre IS NULL OR Planificacion.padre = '')",
									"Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_termino'",
									'estado IN (4, 5, 6, 10, 7)'),
									'recursive' => 1));
				}
				$this->set("intervenciones", $intervenciones);
			} else {
				$this->set("intervenciones", array());
				$this->set("duracion_filtro", '');
			}
			$this->set("fecha_inicio", $fecha_inicio);
			$this->set("fecha_termino", $fecha_termino);
		}
		
		public function get_report() {
			$this->layout = NULL;
			if (!isset($this->request->query) || count($this->request->query) == 0) {
				return;
			}
			$fecha_inicio = NULL;
			$fecha_termino = NULL;
			$this->loadModel('Faena');

			if (isset($this->request->query) && count($this->request->query) >= 2 && $this->request->query["faena_id"] != "0") {
				$fecha_inicio = $this->request->query["fecha_inicio"];
				$fecha_termino = $this->request->query["fecha_termino"];
				$faena_id = $this->request->query["faena_id"];
				$correlativo = $this->request->query["correlativo"];
				$this->set("correlativo", $correlativo);
				$this->set("duracion_filtro", $this->request->query["duracion"]);
				if ($correlativo != "") {
					$correlativo = "Planificacion.id = $correlativo";	
				}
				
				$this->loadModel('Planificacion');
				$intervenciones = $this->Planificacion->find('all', array(
									'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad'),
									//'limit' => 100,
									'order' => array('Faena.nombre' => "asc", 'Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
									'conditions' => array(
									$correlativo,
									'Planificacion.faena_id' => $faena_id,
									"not" => array ("Planificacion.fecha" => null),
									"(Planificacion.padre IS NULL OR Planificacion.padre = '')",
									"Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_termino'",
									//"EXTRACT(MONTH FROM Planificacion.fecha) = $mes AND EXTRACT(YEAR FROM Planificacion.fecha) = $año",
									'estado IN (4, 5, 6, 10)'),
									//'estado IN (7, 4, 6, 5)'),
									'recursive' => 1));
				$this->set("intervenciones", $intervenciones);
			} else {
				$this->set("intervenciones", array());
				$this->set("duracion_filtro", '');
			}
			$this->set("fecha_inicio", $fecha_inicio);
			$this->set("fecha_termino", $fecha_termino);
		}
		
		public function powerbi() {
		}
		
		public function intervenciones_proceso() {
			$this->layout = null;
			$util = new UtilidadesController();
			$fecha = date("Y-m-d H:i");
			$this->loadModel('Planificacion');
			$this->loadModel('Sintoma');
			$this->loadModel('IntervencionElementos');
			
			$intervenciones = $this->Planificacion->find('all', array(
								'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Sintoma.nombre', 'Sintoma.codigo', 'Sintoma.id'),
								'order' => array('Faena.nombre' => "asc", 'Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
								'conditions' => array(
									"not" => array ("Planificacion.fecha" => null),
									"Planificacion.fecha <=" => "$fecha",
									"Planificacion.fecha >" => "2000-01-01 00:00:00",
									"Planificacion.faena_id" => 23,
									'Planificacion.id <> Planificacion.correlativo_final',
									'Planificacion.estado IN' => array(2, 0)),
								'recursive' => 1));

			$return = array();			
			foreach($intervenciones as $intervencion){
				$int = array ();
				$int["Subsistemas"] = array();
				$int["Faena"] = $intervencion["Faena"]["nombre"];
				$int["Flota"] = $intervencion["Flota"]["nombre"];
				$int["Unidad"] = $intervencion["Unidad"]["unidad"];
				$int["Folio"] = $intervencion["Planificacion"]["id"];
				
				if (is_numeric($intervencion["Sintoma"]["codigo"]) && $intervencion["Sintoma"]["codigo"] != '0') {
					$int["Sintoma"] = 'FC ' . $intervencion["Sintoma"]["codigo"] . ' ' .$intervencion["Sintoma"]["nombre"];
				} else {
					$int["Sintoma"] = $intervencion["Sintoma"]["nombre"];
				}
				
				if($intervencion["Planificacion"]["tipointervencion"] == "MP"){
					$int["Sintoma"] = $intervencion["Planificacion"]["tipomantencion"];
				}
					
				$int["Tipo"] = $intervencion["Planificacion"]["tipointervencion"];
				$int["Correlativo"] = $intervencion["Planificacion"]["correlativo_final"];
				$int["Fecha"] = $intervencion["Planificacion"]["fecha"];
				$int["Hora"] = $intervencion["Planificacion"]["hora"];
				$int["Fecha_Correlativo"] = $intervencion["Planificacion"]["fecha"];
				$int["Hora_Correlativo"] = $intervencion["Planificacion"]["hora"];
				$int["Folio_Interno"] = $intervencion["Planificacion"]["folio"];
				$int["Correlativo_Interno"] = $intervencion["Planificacion"]["correlativo"];
				
				
				$int["ESN"] = $util->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$util->getMotor($intervencion['Planificacion']['unidad_id']),$util->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
				
				$int["Comentarios"] = array();
				//if($intervencion["Planificacion"]["id"] != $intervencion["Planificacion"]["correlativo_final"]){
					// Es continuacion, se busca primera intervencion del correlativo para asignar fecha inicial
					$inicial = $this->Planificacion->find('first', array(
								'fields' => array('Planificacion.fecha', 'Planificacion.hora'),
								'conditions' => array(
									'Planificacion.id' => $intervencion["Planificacion"]["correlativo_final"]),
								'recursive' => -1));
					$int["Fecha_Correlativo"] = $inicial["Planificacion"]["fecha"];
					$int["Hora_Correlativo"] = $inicial["Planificacion"]["hora"];
					
					$comentarios = $this->Planificacion->find('all', array(
						'fields' => array('Planificacion.id','Planificacion.fecha','Planificacion.hora','Planificacion.json','Planificacion.folio'),
						'order' => array('Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
						'conditions' => array(
							'Planificacion.faena_id' => 23,
							"not" => array ("Planificacion.fecha" => null),
							"Planificacion.correlativo_final" => $intervencion["Planificacion"]["correlativo_final"],
							'Planificacion.estado NOT IN' => array(2, 0)),
						'recursive' => -1));
					foreach($comentarios as $comentario){
						$json = json_decode($comentario["Planificacion"]["json"], true);
						$int["Comentarios"][] = "- " .$comentario["Planificacion"]["id"] . ' ' . $comentario["Planificacion"]["fecha"] . ' ' . $comentario["Planificacion"]["hora"] . ': ' . $json["Comentarios"];
						
						$subsistemas = $this->IntervencionElementos->find('all', array(
							'fields' => array('Subsistema.nombre'),
							'order' => array('Subsistema.nombre' => 'asc'),
							'conditions' => array(
								'IntervencionElementos.folio' => $comentario["Planificacion"]["folio"]),
							'recursive' => 1));
						foreach($subsistemas as $subsistema){
							$int["Subsistemas"][] = "- " . $subsistema["Subsistema"]["nombre"];
						}
						
						$int["Subsistemas"] = array_unique($int["Subsistemas"]);
					}
				//}
				
				
				
				$int["Comentarios"] = implode("<br />", $int["Comentarios"]);
				$int["Subsistemas"] = implode("<br />", $int["Subsistemas"]);
				$tiempo = strtotime($int["Fecha_Correlativo"] . ' ' . $int["Hora_Correlativo"]);
				$tiempo = time() - $tiempo;
				$dtF = new \DateTime('@0');
				$dtT = new \DateTime("@$tiempo");
				$tiempo = $dtF->diff($dtT)->format('%a días, %h horas y %i minutos');
				
				$int["Fecha_Correlativo"] = strtotime($int["Fecha_Correlativo"] . ' ' . $int["Hora_Correlativo"]);
				$int["Fecha_Correlativo"] = date("d-m-Y h:i A", $int["Fecha_Correlativo"]);
				
				$int["Tiempo"] = $tiempo;// / 60;// Tiempo en minutos
				$return[] = $int;	
			}
			
			$archivo_adjunto = $this->generar_excel_pendientes(23);
			
			$destinatarios = array();
			$destinatarios[] = "daniel.fuentes.b@gmail.com";
			$destinatarios[] = "daniel@salmonsoftware.cl";
			$html="<html>";
			$html.="<body>";
			$html.= "<table width=\"100%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
			$html.= "<tr style=\"background-color: red; color: white;\">";
			$html.= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">INTERVENCIONES PENDIENTES ".strtoupper($int["Faena"])."</td>";
			$html.= "</tr>";
			$html.= "</table>";	

			$html.= '<table border="1" width="100%">';
			$html.= '	<tr>';
			$html.= '		<th>Faena</th>';
			$html.= '		<th>Flota</th>';
			$html.= '		<th>Equipo</th>';
			$html.= '		<th>ESN</th>';
			$html.= '		<th>Horas de motor</th>';
			$html.= '		<th>Tipo</th>';
			$html.= '		<th>Fecha inicio</th>';
			$html.= '		<th>Síntoma</th>';
			$html.= '		<th>Subsistema</th>';
			$html.= '		<th>Tiempo</th>';
			$html.= '		<th>Comentarios</th>';
			$html.= '	</tr>';
			foreach($return as $intervencion){
				$html.= '<tr>';
				$html.= "	<td nowrap>{$intervencion["Faena"]}</td>";
				$html.= "		<td nowrap>{$intervencion["Flota"]}</td>";
				$html.= "		<td nowrap>{$intervencion["Unidad"]}</td>";
				$html.= "		<td nowrap>{$intervencion["ESN"]}</td>";
				$html.= "		<td></td>";
				$html.= "		<td nowrap>{$intervencion["Tipo"]}</td>";
				$html.= "		<td nowrap>{$intervencion["Fecha_Correlativo"]}</td>";
				$html.= "		<td nowrap>{$intervencion["Sintoma"]}</td>";
				$html.= "		<td nowrap>{$intervencion["Subsistemas"]}</td>";
				$html.= "		<td nowrap>{$intervencion["Tiempo"]}</td>";
				$html.= "		<td>{$intervencion["Comentarios"]}</td>";
				$html.= '</tr>';
			}
			$html.= '</table>';			

			$html.="</body>";
			$html.="</html>";
			$asunto = "Intervenciones pendientes " . $int["Faena"];
			$this->AWSSES->to = $destinatarios;
			$this->AWSSES->from = 'alerta@zeke.cl';
			$this->AWSSES->attachment = $archivo_adjunto;
			$this->AWSSES->sendRaw('Alerta DBM / ' . $asunto,$html);
			$this->AWSSES->reset();
			
			$this->set("intervenciones", $return);
		}
		
		public function generar_excel_pendientes($faena_id){
			$utilReporte = new UtilidadesReporteController();
			$util = new UtilidadesController();
			PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
			$objPHPExcel = new PHPExcel();
			/*header ('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header ('Cache-Control: max-age=0');
			header ('Content-Disposition: attachment;filename="Intervenciones-Pendientes-'.date("Y-m-d").'".xlsx');
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.date('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0*/
			$objPHPExcel->
		    getProperties()
		        ->setCreator("DBM")
		        ->setLastModifiedBy("DBM")
		        ->setTitle("Int. Pendientes");
				
			$objPHPExcel->setActiveSheetIndex(0)->setTitle('Int. Pendientes');
			
			// Encabezados
			$encabezados = array("Faena","Flota","Equipo","ESN","Horas Motor","Tipo","Fecha inicio","Comentarios","Categoria","Sintoma","Sistema","Subsistema","Posición Subsistema", "ID", "Elemento", "Posición Elemento", "Diagnóstico", "Solucion", "Tiempo");
			$count = count($encabezados);
			
			for($i=0;$i<$count;$i++){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,1,$encabezados[$i]);
			}
			
			$utilReporte->cellColor("A1:S1","FF0000","FFFFFF",$objPHPExcel);
			
			$this->layout = null;
			$util = new UtilidadesController();
			$fecha = date("Y-m-d H:i");
			$this->loadModel('Planificacion');
			$this->loadModel('Sintoma');
			$this->loadModel('IntervencionElementos');
			
			$intervenciones = $this->Planificacion->find('all', array(
								'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Sintoma.nombre', 'Sintoma.codigo', 'Sintoma.id'),
								'order' => array('Faena.nombre' => "asc", 'Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
								'conditions' => array(
									"not" => array ("Planificacion.fecha" => null),
									"Planificacion.fecha <=" => "$fecha",
									"Planificacion.fecha >" => "2000-01-01 00:00:00",
									"Planificacion.faena_id" => $faena_id,
									'Planificacion.id <> Planificacion.correlativo_final',
									'Planificacion.estado IN' => array(2, 0)),
								'recursive' => 1));
								
			$return = array();
			$folios = array();			
			foreach($intervenciones as $intervencion){
				$int = array ();
				$int["Subsistemas"] = array();
				$int["Faena"] = $intervencion["Faena"]["nombre"];
				$int["Flota"] = $intervencion["Flota"]["nombre"];
				$int["Unidad"] = $intervencion["Unidad"]["unidad"];
				$int["Folio"] = $intervencion["Planificacion"]["id"];
				
				if (is_numeric($intervencion["Sintoma"]["codigo"]) && $intervencion["Sintoma"]["codigo"] != '0') {
					$int["Sintoma"] = 'FC ' . $intervencion["Sintoma"]["codigo"] . ' ' .$intervencion["Sintoma"]["nombre"];
				} else {
					$int["Sintoma"] = $intervencion["Sintoma"]["nombre"];
				}
				
				$resultado = $this->Sintoma->find('first', array(
														'conditions' => array('Sintoma.id' => $intervencion["Sintoma"]["id"]),
														'recursive' => 1
														));
				if(isset($resultado["Categoria"]["nombre"])){
					$int["Categoria_Sintoma"] = $resultado["Categoria"]["nombre"];
				}
				
				
				if($intervencion["Planificacion"]["tipointervencion"] == "MP"){
					$int["Sintoma"] = $intervencion["Planificacion"]["tipomantencion"];
				}
					
				$int["Tipo"] = $intervencion["Planificacion"]["tipointervencion"];
				$int["Correlativo"] = $intervencion["Planificacion"]["correlativo_final"];
				$int["Fecha"] = $intervencion["Planificacion"]["fecha"];
				$int["Hora"] = $intervencion["Planificacion"]["hora"];
				$int["Fecha_Correlativo"] = $intervencion["Planificacion"]["fecha"];
				$int["Hora_Correlativo"] = $intervencion["Planificacion"]["hora"];
				$int["Folio_Interno"] = $intervencion["Planificacion"]["folio"];
				$int["Correlativo_Interno"] = $intervencion["Planificacion"]["correlativo"];
				
				
				$int["ESN"] = $util->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$util->getMotor($intervencion['Planificacion']['unidad_id']),$util->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
				
				$int["Comentarios"] = array();
				//if($intervencion["Planificacion"]["id"] != $intervencion["Planificacion"]["correlativo_final"]){
					// Es continuacion, se busca primera intervencion del correlativo para asignar fecha inicial
					$inicial = $this->Planificacion->find('first', array(
								'fields' => array('Planificacion.fecha', 'Planificacion.hora'),
								'conditions' => array(
									'Planificacion.id' => $intervencion["Planificacion"]["correlativo_final"]),
								'recursive' => -1));
					$int["Fecha_Correlativo"] = $inicial["Planificacion"]["fecha"];
					$int["Hora_Correlativo"] = $inicial["Planificacion"]["hora"];
					
					$comentarios = $this->Planificacion->find('all', array(
						'fields' => array('Planificacion.id','Planificacion.fecha','Planificacion.hora','Planificacion.json','Planificacion.folio'),
						'order' => array('Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
						'conditions' => array(
							'Planificacion.faena_id' => 23,
							"not" => array ("Planificacion.fecha" => null),
							"Planificacion.correlativo_final" => $intervencion["Planificacion"]["correlativo_final"],
							'Planificacion.estado NOT IN' => array(2, 0)),
						'recursive' => -1));
					foreach($comentarios as $comentario){
						$json = json_decode($comentario["Planificacion"]["json"], true);
						$int["Comentarios"][] = "- " .$comentario["Planificacion"]["id"] . ' ' . $comentario["Planificacion"]["fecha"] . ' ' . $comentario["Planificacion"]["hora"] . ': ' . $json["Comentarios"];
						
						$subsistemas = $this->IntervencionElementos->find('all', array(
							'fields' => array('Subsistema.nombre'),
							'order' => array('Subsistema.nombre' => 'asc'),
							'conditions' => array(
								'IntervencionElementos.folio' => $comentario["Planificacion"]["folio"]),
							'recursive' => 1));
						foreach($subsistemas as $subsistema){
							$int["Subsistemas"][] = "- " . $subsistema["Subsistema"]["nombre"];
						}
						
						$int["Subsistemas"] = array_unique($int["Subsistemas"]);
					}
				//}
				
				
				
				$int["Comentarios"] = implode("<br />", $int["Comentarios"]);
				$int["Subsistemas"] = implode("<br />", $int["Subsistemas"]);
				$tiempo = strtotime($int["Fecha_Correlativo"] . ' ' . $int["Hora_Correlativo"]);
				$tiempo = time() - $tiempo;
				$dtF = new \DateTime('@0');
				$dtT = new \DateTime("@$tiempo");
				$tiempo = $dtF->diff($dtT)->format('%a días, %h horas y %i minutos');
				
				$int["Fecha_Correlativo"] = strtotime($int["Fecha_Correlativo"] . ' ' . $int["Hora_Correlativo"]);
				$int["Fecha_Correlativo"] = date("d-m-Y h:i A", $int["Fecha_Correlativo"]);
				
				$int["Tiempo"] = $tiempo;// / 60;// Tiempo en minutos
				
				// Elementos 
				$intervenciones2 = $this->Planificacion->find('all', array(
								'fields' => array('Planificacion.folio'),
								'conditions' => array(
									"Planificacion.correlativo_final" => $intervencion["Planificacion"]["correlativo_final"],
									'Planificacion.estado NOT IN' => array(2, 0)),
								'recursive' => 1));
				$num_elementos = 0;
				
				unset($int["Fecha"],$int["Folio_Interno"],$int["Folio"],$int["Correlativo"]);
				$data = array();
				//print_r($intervenciones2);
				$data["Faena"] = $int["Faena"];
				$data["Flota"] = $int["Flota"];
				$data["Equipo"] = $int["Unidad"];
				$data["ESN"] = $int["ESN"];
				$data["Horas Motor"] = "";
				$data["Tipo"] = $int["Tipo"];
				$data["Fecha Inicio"] = $int["Fecha_Correlativo"];
				$data["Comentarios"] = $int["Comentarios"];
				$data["Categoria"] = $int["Categoria_Sintoma"];
				$data["Sintoma"] = $int["Sintoma"];
				
				foreach($intervenciones2 as $intervencion2){
					$elementos = $this->IntervencionElementos->find('all', array(
						'conditions' => array(
							"IntervencionElementos.folio" => $intervencion2["Planificacion"]["folio"]),
						'recursive' => 1
					));
					foreach($elementos as $elemento){
						//print_r($elemento);
						$num_elementos++;
						$data["Sistema"] = $elemento["Sistema"]["nombre"];
						$data["Subsistema"] = $elemento["Subsistema"]["nombre"];
						$data["Pos.subsistema"] = $elemento["Posiciones_Subsistema"]["nombre"];
						$data["Codigo"] = $elemento["IntervencionElementos"]["id_elemento"];
						$data["Elemento"] = $elemento["Elemento"]["nombre"];
						$data["Pos.elemento"] = $elemento["Posiciones_Elemento"]["nombre"];
						$data["Diagnostico"] = $elemento["Diagnostico"]["nombre"];
						$data["Solucion"] = $elemento["TipoElemento"]["nombre"];
						$data["Tiempo"] = $int["Tiempo"];
						unset($int["Subsistemas"]);
						$return[] = $data;
						//print_r($int);
					}
				}
				if ($num_elementos == 0) {
					$data["Sistema"] = "";
					$data["Subsistema"] = "";
					$data["Pos.subsistema"] = "";
					$data["Codigo"] = "";
					$data["Elemento"] = "";
					$data["Pos.elemento"] = "";
					$data["Diagnostico"] = "";
					$data["Solucion"] = "";
					unset($int["Subsistemas"]);
					$data["Tiempo"] = $int["Tiempo"];
					$return[] = $data;
				}
			}
			
			$objPHPExcel->getActiveSheet()->fromArray($return, NULL, 'A2');
			$objPHPExcel->getActiveSheet()->getStyle('A1:S1')->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			$objWriter->save('/tmp/Intervenciones-Pendientes-'.$int["Faena"].".xlsx");
			return '/tmp/Intervenciones-Pendientes-'.$int["Faena"].".xlsx";
		}
	}
?>
