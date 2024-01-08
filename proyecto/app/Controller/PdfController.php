<?php
	App::uses('ConnectionManager', 'Model');
	App::import('Vendor', 'Fpdf', array('file' => 'fpdf/fpdf.php'));
	App::import('Controller', 'Qr');	

	class PdfController extends AppController {
		public function certificado($folio) {
			$this->loadModel('Planificacion');
			$this->loadModel('Usuario');
			$this->loadModel('TipoTecnico');
			$folio = base64_decode($folio);
			if(is_numeric($folio)) {
				$intervencion = $this->Planificacion->find('first', array('conditions' => array('Planificacion.id' => $folio), 'recursive' => 1));
			} else {
				$intervencion = $this->Planificacion->find('first', array('conditions' => array('Planificacion.folio' => $folio), 'recursive' => 1));
			}
			$height = 7;
			if(!isset($intervencion["Planificacion"])){
				exit;
			}
			
			$json = json_decode($intervencion["Planificacion"]["json"], true);
			$tecnicos = array();
			$tipos = array();

			if(isset($json["UserID"]) && is_numeric($json["UserID"])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($json["UserID"])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$tecnicos[] = preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))));
					if(isset($json["TipoTecnicoApoyo01"]) && is_numeric($json["TipoTecnicoApoyo01"])){
						$tipo = $this->TipoTecnico->find('first', array('conditions' => array('TipoTecnico.id' => intval($json["TipoTecnicoApoyo01"])), 'recursive' => -1));
						if(isset($tipo["TipoTecnico"]["nombre"])) {
							$tipos[] = trim(strtolower($tipo["TipoTecnico"]["nombre"]));
						}
					}
				}
			}
			
			for($i = 2; $i < 9; $i++){
				if(isset($json["TecnicoApoyo0".$i]) && is_numeric($json["TecnicoApoyo0".$i])){
					$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($json["TecnicoApoyo0".$i])), 'recursive' => -1));
					if(isset($usuario["Usuario"]["nombres"])) {
						$tecnicos[] = preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))));
						if(isset($json["TipoTecnicoApoyo0".$i]) && is_numeric($json["TipoTecnicoApoyo0".$i])){
							$tipo = $this->TipoTecnico->find('first', array('conditions' => array('TipoTecnico.id' => intval($json["TipoTecnicoApoyo0".$i])), 'recursive' => -1));
							if(isset($tipo["TipoTecnico"]["nombre"])) {
								$tipos[] = trim(strtolower($tipo["TipoTecnico"]["nombre"]));
							}
						}
					}
				}
			}
			
			$i = 10;
			if(isset($json["TecnicoApoyo".$i]) && is_numeric($json["TecnicoApoyo".$i])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($json["TecnicoApoyo".$i])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$tecnicos[] = preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))));
					if(isset($json["TipoTecnicoApoyo".$i]) && is_numeric($json["TipoTecnicoApoyo".$i])){
						$tipo = $this->TipoTecnico->find('first', array('conditions' => array('TipoTecnico.id' => intval($json["TipoTecnicoApoyo".$i])), 'recursive' => -1));
						if(isset($tipo["TipoTecnico"]["nombre"])) {
							$tipos[] = trim(strtolower($tipo["TipoTecnico"]["nombre"]));
						}
					}
				}
			}
			
			//TecnicoApoyo07
			
			$fecha_inicio = strtotime($intervencion["Planificacion"]["fecha"] . " " . $intervencion["Planificacion"]["hora"]);
			$fecha_termino = strtotime($intervencion["Planificacion"]["fecha_termino"] . " " . $intervencion["Planificacion"]["hora_termino"]);
			$fecha_aprobacion = strtotime($intervencion["Planificacion"]["fecha_aprobacion"]);
			$fecha_registro = strtotime($intervencion["Planificacion"]["fecha_guardado"]);
			
			
			if ($fecha_termino < $fecha_inicio) {
				$tmp = $fecha_termino;
				$fecha_termino = $fecha_inicio;
				$fecha_inicio = $tmp;
			}
			
			$ln = 7;
			$tab = 5;
			$this->layout = null;
			
			$pdf = new FPDF('P','mm','Letter', 'Certificado');
			$pdf->AddPage();
			$pdf->SetFont('Times','',12);
			$pdf->SetFillColor(255,255,255);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab,0,"",0);
			$pdf->Cell(100,0,utf8_decode("DBM certifica que la intervención fue registrada correctamente de acuerdo a la siguiente información:"),0);
			$pdf->Ln($ln);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Folio",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Planificacion"]["id"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Correlativo",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Planificacion"]["correlativo_final"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Tipo",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Planificacion"]["tipointervencion"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Faena",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Faena"]["nombre"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Flota",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Flota"]["nombre"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Unidad",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Unidad"]["unidad"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Fecha inicio",0);
			$pdf->Cell(100,0,date("d-m-Y", $fecha_inicio) ." a las ". date("h:i A", $fecha_inicio),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,utf8_decode("Fecha término"),0);
			$pdf->Cell(100,0,date("d-m-Y", $fecha_termino) ." a las ". date("h:i A", $fecha_termino),0);
			$pdf->Ln($ln);
			
			if(isset($intervencion["Planificacion"]["fecha_guardado"]) && $intervencion["Planificacion"]["fecha_guardado"] != null && $intervencion["Planificacion"]["fecha_guardado"] != ''){
				$pdf->Cell($tab * 3,0,"",0);
				$pdf->Cell(50,0,"Fecha registro",0);
				$pdf->Cell(100,0,date("d-m-Y", $fecha_registro) ." a las ". date("h:i A", $fecha_registro),0);
				$pdf->Ln($ln);
			}
					
			$pdf->Ln($ln);			
			$pdf->SetFont('Times','B',12);
			$pdf->Cell($tab,0,"",0);
			$pdf->Cell(50,0,"Participantes",0);
			$pdf->Cell(100,0,utf8_decode($correlativo),0);
			$pdf->Ln($ln);
			$pdf->Ln($ln);
			$pdf->SetFont('Times','',12);
			
			foreach($tecnicos as $key => $value){
				$pdf->Cell($tab * 3,0,"",0);
				$pdf->Cell(50,0,utf8_decode("Técnico " . $tipos[$key]),0);
				$pdf->Cell(100,0,utf8_decode($value),0);
				$pdf->Ln($ln);
			}
			
			if(isset($intervencion["Planificacion"]["supervisor_responsable"]) && is_numeric($intervencion["Planificacion"]["supervisor_responsable"])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($intervencion["Planificacion"]["supervisor_responsable"])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$pdf->Cell($tab * 3,0,"",0);
					$pdf->Cell(50,0,"Supervisor responsable",0);
					$pdf->Cell(100,0,utf8_decode(preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))))),0);
					$pdf->Ln($ln);
				}
			}
			
			$pdf->Ln($ln);
			$pdf->Cell(190,10,"Certificado de registro generado el ".date("d-m-Y")." a las ".date("h:i A"),0,0,'R');			
			$pdf->Output();
			exit;
		}
		
		public function aprobado($folio) {
			$this->loadModel('Planificacion');
			$this->loadModel('Usuario');
			$this->loadModel('TipoTecnico');
			$folio = base64_decode($folio);
			if(is_numeric($folio)) {
				$intervencion = $this->Planificacion->find('first', array('conditions' => array('Planificacion.id' => $folio), 'recursive' => 1));
			} else {
				$intervencion = $this->Planificacion->find('first', array('conditions' => array('Planificacion.folio' => $folio), 'recursive' => 1));
			}
			$height = 7;
			if(!isset($intervencion["Planificacion"])){
				exit;
			}
			
			$json = json_decode($intervencion["Planificacion"]["json"], true);
			$tecnicos = array();
			$tipos = array();

			if(isset($json["UserID"]) && is_numeric($json["UserID"])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($json["UserID"])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$tecnicos[] = preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))));
					if(isset($json["TipoTecnicoApoyo01"]) && is_numeric($json["TipoTecnicoApoyo01"])){
						$tipo = $this->TipoTecnico->find('first', array('conditions' => array('TipoTecnico.id' => intval($json["TipoTecnicoApoyo01"]) + 1), 'recursive' => -1));
						if(isset($tipo["TipoTecnico"]["nombre"])) {
							$tipos[] = trim(strtolower($tipo["TipoTecnico"]["nombre"]));
						}
					}
				}
			}
			
			for($i = 2; $i < 9; $i++){
				if(isset($json["TecnicoApoyo0".$i]) && is_numeric($json["TecnicoApoyo0".$i])){
					$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($json["TecnicoApoyo0".$i])), 'recursive' => -1));
					if(isset($usuario["Usuario"]["nombres"])) {
						$tecnicos[] = preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))));
						if(isset($json["TipoTecnicoApoyo0".$i]) && is_numeric($json["TipoTecnicoApoyo0".$i])){
							$tipo = $this->TipoTecnico->find('first', array('conditions' => array('TipoTecnico.id' => intval($json["TipoTecnicoApoyo0".$i]) + 1), 'recursive' => -1));
							if(isset($tipo["TipoTecnico"]["nombre"])) {
								$tipos[] = trim(strtolower($tipo["TipoTecnico"]["nombre"]));
							}
						}
					}
				}
			}
			
			$i = 10;
			if(isset($json["TecnicoApoyo".$i]) && is_numeric($json["TecnicoApoyo".$i])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($json["TecnicoApoyo".$i])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$tecnicos[] = preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))));
					if(isset($json["TipoTecnicoApoyo".$i]) && is_numeric($json["TipoTecnicoApoyo".$i])){
						$tipo = $this->TipoTecnico->find('first', array('conditions' => array('TipoTecnico.id' => intval($json["TipoTecnicoApoyo".$i]) + 1), 'recursive' => -1));
						if(isset($tipo["TipoTecnico"]["nombre"])) {
							$tipos[] = trim(strtolower($tipo["TipoTecnico"]["nombre"]));
						}
					}
				}
			}
			
			//TecnicoApoyo07
			
			$fecha_inicio = strtotime($intervencion["Planificacion"]["fecha"] . " " . $intervencion["Planificacion"]["hora"]);
			$fecha_termino = strtotime($intervencion["Planificacion"]["fecha_termino"] . " " . $intervencion["Planificacion"]["hora_termino"]);
			$fecha_aprobacion = strtotime($intervencion["Planificacion"]["fecha_aprobacion"]);
			$fecha_registro = strtotime($intervencion["Planificacion"]["fecha_guardado"]);
			
			$ln = 7;
			$tab = 5;
			$this->layout = null;
			
			$pdf = new FPDF('P','mm','Letter', 'Certificado');
			$pdf->AddPage();
			$pdf->SetFont('Times','',12);
			$pdf->SetFillColor(255,255,255);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab,0,"",0);
			$pdf->Cell(100,0,utf8_decode("DBM certifica que la intervención fue aprobada correctamente de acuerdo a la siguiente información:"),0);
			$pdf->Ln($ln);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Folio",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Planificacion"]["id"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Correlativo",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Planificacion"]["correlativo_final"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Tipo",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Planificacion"]["tipointervencion"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Faena",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Faena"]["nombre"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Flota",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Flota"]["nombre"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Unidad",0);
			$pdf->Cell(100,0,utf8_decode($intervencion["Unidad"]["unidad"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,"Fecha inicio",0);
			$pdf->Cell(100,0,date("d-m-Y", $fecha_inicio) ." a las ". date("h:i A", $fecha_inicio),0);
			$pdf->Ln($ln);
			
			$pdf->Cell($tab * 3,0,"",0);
			$pdf->Cell(50,0,utf8_decode("Fecha término"),0);
			$pdf->Cell(100,0,date("d-m-Y", $fecha_termino) ." a las ". date("h:i A", $fecha_termino),0);
			$pdf->Ln($ln);
			
			if(isset($intervencion["Planificacion"]["fecha_guardado"]) && $intervencion["Planificacion"]["fecha_guardado"] != null && $intervencion["Planificacion"]["fecha_guardado"] != ''){
				$pdf->Cell($tab * 3,0,"",0);
				$pdf->Cell(50,0,"Fecha registro",0);
				$pdf->Cell(100,0,date("d-m-Y", $fecha_registro) ." a las ". date("h:i A", $fecha_registro),0);
				$pdf->Ln($ln);
			}
			
			if(isset($intervencion["Planificacion"]["fecha_aprobacion"]) && $intervencion["Planificacion"]["fecha_aprobacion"] != null && $intervencion["Planificacion"]["fecha_aprobacion"] != ''){
				$pdf->Cell($tab * 3,0,"",0);
				$pdf->Cell(50,0,utf8_decode("Fecha aprobación"),0);
				$pdf->Cell(100,0,date("d-m-Y", $fecha_aprobacion) ." a las ". date("h:i A", $fecha_aprobacion),0);
				$pdf->Ln($ln);
			}
			
			$pdf->Ln($ln);			
			$pdf->SetFont('Times','B',12);
			$pdf->Cell($tab,0,"",0);
			$pdf->Cell(50,0,"Participantes",0);
			$pdf->Cell(100,0,utf8_decode($correlativo),0);
			$pdf->Ln($ln);
			$pdf->Ln($ln);
			$pdf->SetFont('Times','',12);
			
			foreach($tecnicos as $key => $value){
				$pdf->Cell($tab * 3,0,"",0);
				$pdf->Cell(50,0,utf8_decode("Técnico " . $tipos[$key]),0);
				$pdf->Cell(100,0,utf8_decode($value),0);
				$pdf->Ln($ln);
			}
			
			if(isset($intervencion["Planificacion"]["supervisor_responsable"]) && is_numeric($intervencion["Planificacion"]["supervisor_responsable"])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($intervencion["Planificacion"]["supervisor_responsable"])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$pdf->Cell($tab * 3,0,"",0);
					$pdf->Cell(50,0,"Supervisor responsable",0);
					$pdf->Cell(100,0,utf8_decode(preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))))),0);
					$pdf->Ln($ln);
				}
			}
			
			if(isset($intervencion["Planificacion"]["aprobador_id"]) && is_numeric($intervencion["Planificacion"]["aprobador_id"])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($intervencion["Planificacion"]["aprobador_id"])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$pdf->Cell($tab * 3,0,"",0);
					$pdf->Cell(50,0,"Supervisor aprobador",0);
					$pdf->Cell(100,0,utf8_decode(preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))))),0);
					$pdf->Ln($ln);
				}
			}
			
			$pdf->Ln($ln);
			$pdf->Cell(190,10,utf8_decode("Certificado de aprobación generado el ").date("d-m-Y")." a las ".date("h:i A"),0,0,'R');			
			$pdf->Output();
			exit;
		}

		
		public function resumen($folio) {
			$this->loadModel('Planificacion');
			$this->loadModel('Unidad');
			$this->loadModel('Usuario');
			$this->loadModel('TipoTecnico');
			$this->loadModel('IntervencionFechas');
			$this->loadModel('DeltaDetalle');
			$this->loadModel('IntervencionComentarios');
			$this->loadModel('IntervencionElementos');
			$this->loadModel('IntervencionDecisiones');
			$this->loadModel('IntervencionFluido');
			$this->loadModel('Fluido');
			$this->loadModel('FluidoTipoIngreso');
			$this->loadModel('Backlog');
                        $this->loadModel('EstadosMotores');
                        
			$intervencion = $this->Planificacion->find('first', array(
                            'conditions' => array('Planificacion.id' => $folio),
                            'joins' => array(
                                        array(
                                            'table' => 'estado_motor',
                                            'alias' => 'est_mot',
                                            'type' => 'INNER',
                                            'conditions' => array(
                                                'est_mot.unidad_id = "Planificacion".unidad_id',
                                                'est_mot.fecha_ps = (SELECT max(fecha_ps) 
                                                                    FROM estado_motor WHERE unidad_id = "Planificacion".unidad_id AND fecha_ps <= "Planificacion".fecha)'
                                            )
                                        )
                                    ),
                            'recursive' => 2));
                        
                        //Obtengo el ESN dependiendo de la fecha de intervencion
                        $eemm = $this->EstadosMotores->find('first', array(
                                    'conditions' => array(
                                        'EstadosMotores.unidad_id' => $intervencion["Planificacion"]['unidad_id'],
                                        'EstadosMotores.fecha_ps <=' =>  $intervencion["Planificacion"]['fecha']
                                    ),
                                    'order' => array('EstadosMotores.fecha_ps DESC')
                                ));
                        
                        $height = 7;
			if(!isset($intervencion["Planificacion"])){
				exit;
			}
			
                        $contratiempo = array();
			$contratiempo["DCC"] = 0;
			$contratiempo["OEM"] = 0;
			$contratiempo["MINA"] = 0;
			
			$tintervencion = array();
			$tintervencion["DCC"] = 0;
			$tintervencion["OEM"] = 0;
			$tintervencion["MINA"] = 0;
			
			$json = json_decode($intervencion["Planificacion"]["json"], true);
			$tecnicos = array();
			$tipos = array();

			if(isset($json["UserID"]) && is_numeric($json["UserID"])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($json["UserID"])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$tecnicos[] = preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))));
					if(isset($json["TipoTecnicoApoyo01"]) && is_numeric($json["TipoTecnicoApoyo01"])){
						$tipo = $this->TipoTecnico->find('first', array('conditions' => array('TipoTecnico.id' => intval($json["TipoTecnicoApoyo01"])), 'recursive' => -1));
						if(isset($tipo["TipoTecnico"]["nombre"])) {
							$tipos[] = ucwords(trim(strtolower($tipo["TipoTecnico"]["nombre"])));
						}
					}
				}
			}
			
			for($i = 2; $i < 9; $i++){
				if(isset($json["TecnicoApoyo0".$i]) && is_numeric($json["TecnicoApoyo0".$i])){
					$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($json["TecnicoApoyo0".$i])), 'recursive' => -1));
					if(isset($usuario["Usuario"]["nombres"])) {
						$tecnicos[] = preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))));
						if(isset($json["TipoTecnicoApoyo0".$i]) && is_numeric($json["TipoTecnicoApoyo0".$i])){
							$tipo = $this->TipoTecnico->find('first', array('conditions' => array('TipoTecnico.id' => intval($json["TipoTecnicoApoyo0".$i])), 'recursive' => -1));
							if(isset($tipo["TipoTecnico"]["nombre"])) {
								$tipos[] = ucwords(trim(strtolower($tipo["TipoTecnico"]["nombre"])));
							}
						}
					}
				}
			}
			
			$i = 10;
			if(isset($json["TecnicoApoyo".$i]) && is_numeric($json["TecnicoApoyo".$i])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($json["TecnicoApoyo".$i])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$tecnicos[] = preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))));
					if(isset($json["TipoTecnicoApoyo".$i]) && is_numeric($json["TipoTecnicoApoyo".$i])){
						$tipo = $this->TipoTecnico->find('first', array('conditions' => array('TipoTecnico.id' => intval($json["TipoTecnicoApoyo".$i])), 'recursive' => -1));
						if(isset($tipo["TipoTecnico"]["nombre"])) {
							$tipos[] = ucwords(trim(strtolower($tipo["TipoTecnico"]["nombre"])));
						}
					}
				}
			}
			
			//TecnicoApoyo07
			
			$fecha_inicio = strtotime($intervencion["Planificacion"]["fecha"] . " " . $intervencion["Planificacion"]["hora"]);
			$fecha_termino = strtotime($intervencion["Planificacion"]["fecha_termino"] . " " . $intervencion["Planificacion"]["hora_termino"]);
			$fecha_aprobacion = strtotime($intervencion["Planificacion"]["fecha_aprobacion"]);
			$fecha_registro = strtotime($intervencion["Planificacion"]["fecha_guardado"]);
			
			if ($fecha_termino < $fecha_inicio) {
				$tmp = $fecha_termino;
				$fecha_termino = $fecha_inicio;
				$fecha_inicio = $tmp;
			}
			
			$ln = 7;
			$tab = 5;
			$this->layout = null;
			
			$pdf = new FPDF('P','mm','Letter', 'Resumen');
			$pdf->AddPage();
			$pdf->SetFont('Times','',12);
			$pdf->SetFillColor(255,255,255);
			$pdf->Ln($ln);
			
			$pdf->Cell(196*1.5/5,0,"Folio",0);
			$pdf->Cell(196*3.5/5,0,utf8_decode($folio),0);
			$pdf->Ln($ln);

			$pdf->Cell(196*1.5/5,0,"Faena",0);
			$pdf->Cell(196*3.5/5,0,utf8_decode($intervencion["Faena"]["nombre"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell(196*1.5/5,0,"Flota",0);
			$pdf->Cell(196*3.5/5,0,utf8_decode($intervencion["Flota"]["nombre"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell(196*1.5/5,0,"Equipo",0);
			$pdf->Cell(196*3.5/5,0,utf8_decode($intervencion["Unidad"]["unidad"]),0);
			$pdf->Ln($ln);
                        
                        $pdf->Cell(196*1.5/5,0,"Esn",0);
			$pdf->Cell(196*3.5/5,0,utf8_decode($eemm['EstadosMotores']['esn_placa']),0);
			$pdf->Ln($ln);
			
			$motor = $this->Unidad->find('first', array('conditions' => array('Unidad.id' => $intervencion["Unidad"]["id"]), 'recursive' => 1));
			if(isset($motor["Motor"]["nombre"])){
				$pdf->Cell(196*1.5/5,0,"Motor",0);
				$pdf->Cell(196*3.5/5,0,utf8_decode($motor["Motor"]["nombre"]),0);
				$pdf->Ln($ln);
			}
			$pdf->Cell(196*1.5/5,0,utf8_decode("Tipo Interveción"),0);
			if($intervencion['Planificacion']['tipointervencion']=="BL"){
				$intervencion['Planificacion']['tipointervencion']="OP";
			}
			if ($intervencion['Planificacion']['padre'] != NULL && $intervencion['Planificacion']['padre'] != '') {
				$pdf->Cell(196*3.5/5,0,utf8_decode("c".$intervencion["Planificacion"]["tipointervencion"]),0);
			}else{
				$pdf->Cell(196*3.5/5,0,utf8_decode($intervencion["Planificacion"]["tipointervencion"]),0);
			}
			$pdf->Ln($ln);
			
			$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
			$pdf->Ln($ln);
			$pdf->Ln($ln);
			
			$pdf->Cell(196*1.5/5,0,"Registrado por",0);
			$pdf->Cell(196*3.0/5,0,utf8_decode($tecnicos[0]),0);
			$pdf->Cell(196*0.5/5,0,utf8_decode($tipos[0]),0);
			$pdf->Ln($ln);
			
			unset($tecnicos[0]);
			
			if(!empty($tecnicos)) {
				$pdf->Cell(196*1.5/5,0,"Participantes",0);
				
				foreach($tecnicos as $key => $value){
					if($key > 1) {
						$pdf->Cell(196*1.5/5,0,"",0);
					}
					$pdf->Cell(196*3.0/5,0,utf8_decode($value),0);
					$pdf->Cell(196*0.5/5,0,utf8_decode($tipos[$key]),0);
					$pdf->Ln($ln);
				}
			}
			
			$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
			$pdf->Ln($ln);
			$pdf->Ln($ln);
			
			if(isset($intervencion["Planificacion"]["supervisor_responsable"]) && is_numeric($intervencion["Planificacion"]["supervisor_responsable"])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($intervencion["Planificacion"]["supervisor_responsable"])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$pdf->Cell(196*1.5/5,0,"Supervisor responsable",0);
					$pdf->Cell(196*3.5/5,0,utf8_decode(preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))))),0);
					$pdf->Ln($ln);
				}
			}
			
			$pdf->Cell(196*1.5/5,0,"Turno",0);
			$pdf->Cell(196*3.5/5,0,utf8_decode($intervencion["Turno"]["nombre"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell(196*1.5/5,0,utf8_decode("Período"),0);
			$pdf->Cell(196*3.5/5,0,utf8_decode($intervencion["Periodo"]["nombre"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell(196*1.5/5,0,utf8_decode("Lugar de Reparación"),0);
			$pdf->Cell(196*3.5/5,0,utf8_decode($intervencion["LugarReparacion"]["nombre"]),0);
			$pdf->Ln($ln);
			
			$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
			$pdf->Ln($ln);
			$pdf->Ln($ln);

			if(isset($intervencion["MotivoLlamado"]["nombre"])) {
				$pdf->Cell(196*1.5/5,0,"Motivo de Llamado",0);
				$pdf->Cell(196*3.5/5,0,utf8_decode($intervencion["MotivoLlamado"]["nombre"]),0);
				$pdf->Ln($ln);
				
				$pdf->Cell(196*1.5/5,0,utf8_decode("Categoría"),0);
				$pdf->Cell(196*3.5/5,0,utf8_decode($intervencion["SintomaCategoria"]["nombre"]),0);
				$pdf->Ln($ln);
				
				$pdf->Cell(196*1.5/5,0,utf8_decode("Síntoma"),0);
				if(isset($intervencion["Sintoma"]["codigo"]) && is_numeric($intervencion["Sintoma"]["codigo"]) && $intervencion["Sintoma"]["codigo"] != "0") {
					$pdf->Cell(196*3.5/5,0,utf8_decode("FC " . $intervencion["Sintoma"]["codigo"] . " ".$intervencion["Sintoma"]["nombre"]),0);
				}else{
					$pdf->Cell(196*3.5/5,0,utf8_decode($intervencion["Sintoma"]["nombre"]),0);
				}
				$pdf->Ln($ln);
				
			}
			if(isset($json["ESNNuevo"])) {
				$pdf->Cell(196*1.5/5,0,utf8_decode("ESN Nuevo"),0);
				$pdf->Cell(196*3.5/5,0,utf8_decode($json["ESNNuevo"]),0);
				$pdf->Ln($ln);
			}

			if(isset($intervencion["MotivoLlamado"]["nombre"]) || isset($json["ESNNuevo"])) {
				$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
				$pdf->Ln($ln);
				$pdf->Ln($ln);
			}
			
			$decisiones = $this->IntervencionDecisiones->find('first', array('conditions' => array("IntervencionDecisiones.folio" => $intervencion["Planificacion"]["folio"]), 'recursive' => -1));
		
			if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) != "EX") {
				if($decisiones["IntervencionDecisiones"]["cambio_modulo"] != null && $decisiones["IntervencionDecisiones"]["cambio_modulo"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Cambio de Módulo"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["cambio_modulo"] == "S" ? "SI" : "NO"),0);
					$pdf->Ln($ln);
				}
			}
			
			if($decisiones["IntervencionDecisiones"]["intervencion_terminada"] != null && $decisiones["IntervencionDecisiones"]["intervencion_terminada"] != '') {
				$pdf->Cell(196*1.5/5,0,utf8_decode("Intervención Terminada"),0);
				$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["intervencion_terminada"] == "S" ? "SI" : "NO"),0);
				$pdf->Ln($ln);
			}
			if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) != "EX") {
				if($decisiones["IntervencionDecisiones"]["prueba_potencia_realizada"] != null && $decisiones["IntervencionDecisiones"]["prueba_potencia_realizada"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Prueba de Potencia Realizada"),0);
					if($decisiones["IntervencionDecisiones"]["prueba_potencia_realizada"] == "P") {
						$pdf->Cell(196*3.5/5,0,utf8_decode("PENDIENTE"),0);
					}elseif($decisiones["IntervencionDecisiones"]["prueba_potencia_realizada"] == "S") {
						$pdf->Cell(196*3.5/5,0,utf8_decode("REALIZADA"),0);
					}else{
						$pdf->Cell(196*3.5/5,0,utf8_decode("NO APLICA"),0);
					}
					$pdf->Ln($ln);
				}
				
				if($decisiones["IntervencionDecisiones"]["prueba_potencia_exitosa"] != null && $decisiones["IntervencionDecisiones"]["prueba_potencia_exitosa"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Prueba de Potencia Exitosa"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["prueba_potencia_exitosa"] == "S" ? "SI" : "NO"),0);
					$pdf->Ln($ln);
				}
				
				if($decisiones["IntervencionDecisiones"]["siguiente_actividad"] != null && $decisiones["IntervencionDecisiones"]["siguiente_actividad"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Siguiente Actividad"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["siguiente_actividad"] == "P" ? "DEJAR PENDIENTE" : ""),0);
					$pdf->Ln($ln);
				}
				
				if($decisiones["IntervencionDecisiones"]["reproceso_potencia"] != null && $decisiones["IntervencionDecisiones"]["reproceso_potencia"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Reproceso Prueba de Potencia"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["reproceso_potencia"] == "E" ? "EXITOSA" : "FALLIDA"),0);
					$pdf->Ln($ln);
				}
				
				
				if($decisiones["IntervencionDecisiones"]["reproceso_modulo"] != null && $decisiones["IntervencionDecisiones"]["reproceso_modulo"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Reproceso Cambio de Módulo"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["reproceso_modulo"] == "S" ? "SI" : "NO"),0);
					$pdf->Ln($ln);
				}
				
				if($decisiones["IntervencionDecisiones"]["reproceso_evento"] != null && $decisiones["IntervencionDecisiones"]["reproceso_evento"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Reproceso Estado de Evento"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["reproceso_evento"] == "F" ? "FINALIZADO" : "PENDIENTE"),0);
					$pdf->Ln($ln);
				}
				
				if($decisiones["IntervencionDecisiones"]["desconexion_realizada"] != null && $decisiones["IntervencionDecisiones"]["desconexion_realizada"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Desconexión Realizada"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["desconexion_realizada"] == "S" ? "SI" : "NO"),0);
					$pdf->Ln($ln);
				}
				
				if($decisiones["IntervencionDecisiones"]["desconexion_terminada"] != null && $decisiones["IntervencionDecisiones"]["desconexion_terminada"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Desconexión Terminada"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["desconexion_terminada"] == "S" ? "SI" : "NO"),0);
					$pdf->Ln($ln);
				}
				
				if($decisiones["IntervencionDecisiones"]["conexion_realizada"] != null && $decisiones["IntervencionDecisiones"]["conexion_realizada"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Conexión Realizada"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["conexion_realizada"] == "S" ? "SI" : "NO"),0);
					$pdf->Ln($ln);
				}
	
				if($decisiones["IntervencionDecisiones"]["conexion_terminada"] != null && $decisiones["IntervencionDecisiones"]["conexion_terminada"] != '') {			
					$pdf->Cell(196*1.5/5,0,utf8_decode("Conexión Terminada"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["conexion_terminada"] == "S" ? "SI" : "NO"),0);
					$pdf->Ln($ln);
				}
				
				if($decisiones["IntervencionDecisiones"]["puesta_marcha_realizada"] != null && $decisiones["IntervencionDecisiones"]["puesta_marcha_realizada"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Puesta en Marcha Realizada"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["puesta_marcha_realizada"] == "S" ? "SI" : "NO"),0);
					$pdf->Ln($ln);
				}
				
				if($decisiones["IntervencionDecisiones"]["trabajo_finalizado"] != null && $decisiones["IntervencionDecisiones"]["trabajo_finalizado"] != '') {
					$pdf->Cell(196*1.5/5,0,utf8_decode("Trabajo Finalizado"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["trabajo_finalizado"] == "S" ? "SI" : "NO"),0);
					$pdf->Ln($ln);
				}
	
				if($decisiones["IntervencionDecisiones"]["mantencion_terminada"] != null && $decisiones["IntervencionDecisiones"]["mantencion_terminada"] != '') {			
					$pdf->Cell(196*1.5/5,0,utf8_decode("Mantención Terminada"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($decisiones["IntervencionDecisiones"]["mantencion_terminada"] == "S" ? "SI" : "NO"),0);
					$pdf->Ln($ln);
				}
			}
			
			$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
			$pdf->Ln($ln);
			$pdf->Ln($ln);
		
			$elementos = $this->IntervencionElementos->find('all',  array(
				'conditions' => array("IntervencionElementos.folio" => $intervencion["Planificacion"]["folio"], 'IntervencionElementos.tipo_registro' => '0'), 
				'recursive' => 1
			));
	
			if (!empty($elementos)){
				$pdf->AddPage("L");
				$pdf->SetFillColor(160, 160, 160);
				$pdf->Cell(259,$height,utf8_decode("Elementos"),1,0,'C',true);	
				$pdf->Ln($ln);
				$pdf->Cell(259*0.5/13,$height,"#",1,0,'C');
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Sistema"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Subsistema"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Posición"),1,0,'C');	
				$pdf->Cell(259*0.5/13,$height,utf8_decode("ID"),1,0,'C');	 
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Elemento"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Posición"),1,0,'C');	
				$pdf->Cell(259*2.0/13,$height,utf8_decode("Diagnóstico"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Solución"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Tipo"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("NP Sale"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("NP Entra"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Tiempo"),1,0,'C');	
				$pdf->Ln($ln);
				$i = 1;
				$total_elementos = 0;
				foreach($elementos as $elemento){
					$pdf->SetWidths(array(259*0.5/13,259*1.0/13,259*1.0/13,259*1.0/13,259*0.5/13,259*1.0/13,259*1.0/13,259*2.0/13,259*1.0/13,259*1.0/13,259*1.0/13,259*1.0/13,259*1.0/13));
					$pdf->Row(array($i++,$elemento["Sistema"]["nombre"],$elemento["Subsistema"]["nombre"],$elemento["PosicionSubsistema"]["nombre"],$elemento["IntervencionElementos"]["id_elemento"],$elemento["Elemento"]["nombre"],$elemento["Posiciones_Elemento"]["nombre"],$elemento["Diagnostico"]["nombre"],$elemento["Solucion"]["nombre"],$elemento["TipoElemento"]["nombre"],$elemento["IntervencionElementos"]["pn_saliente"],$elemento["IntervencionElementos"]["pn_entrante"],str_pad(floor($elemento["IntervencionElementos"]["tiempo"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($elemento["IntervencionElementos"]["tiempo"] % 60),2,"0",STR_PAD_LEFT)));
					
					/*
					$pdf->Cell(259*0.5/13,$height,$i++,1,0,'C');
					$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["Sistema"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["Subsistema"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["PosicionSubsistema"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*0.5/13,$height,utf8_decode($elemento["IntervencionElementos"]["id_elemento"]),1,0,'C');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["Elemento"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["Posiciones_Elemento"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*2.0/13,$height,utf8_decode($elemento["Diagnostico"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["Solucion"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["TipoElemento"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["IntervencionElementos"]["pn_saliente"]),1,0,'L');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["IntervencionElementos"]["pn_entrante"]),1,0,'L');	
					$pdf->Cell(259*1.0/13,$height,str_pad(floor($elemento["IntervencionElementos"]["tiempo"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($elemento["IntervencionElementos"]["tiempo"] % 60),2,"0",STR_PAD_LEFT),1,0,'C');	
					*/
					//$pdf->Ln($ln);
					$total_elementos += $elemento["IntervencionElementos"]["tiempo"];
					if(strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "EX"){
						$tintervencion["OEM"] += $elemento["IntervencionElementos"]["tiempo"];
					} else {
						$tintervencion["DCC"] += $elemento["IntervencionElementos"]["tiempo"];
					}	
				} 
				$pdf->SetFillColor(160, 160, 160); 
				$pdf->Cell(259*12.0/13,$height,"",1,0,'C',true);	
				$pdf->Cell(259*1.0/13,$height,str_pad(floor($total_elementos / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($total_elementos % 60),2,"0",STR_PAD_LEFT),1,0,'C',true);	
				$pdf->Ln($ln);
			}
			
			$elementos_reproceso = $this->IntervencionElementos->find('all',  array(
				'conditions' => array("IntervencionElementos.folio" => $intervencion["Planificacion"]["folio"], 'IntervencionElementos.tipo_registro' => '1'), 
				'recursive' => 1
			));
			if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) != "EX") {
				if (!empty($elementos_reproceso)){
					if (empty($elementos)){
						$pdf->AddPage("L");
					}else{
						$pdf->Ln($ln);
					}
					$pdf->SetFillColor(160, 160, 160);
					$pdf->Cell(259,$height,utf8_decode("Reproceso de Elementos"),1,0,'C',true);	
					$pdf->Ln($ln);
					$pdf->Cell(259*0.5/13,$height,"#",1,0,'C');
					$pdf->Cell(259*1.0/13,$height,utf8_decode("Sistema"),1,0,'C');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode("Subsistema"),1,0,'C');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode("Posición"),1,0,'C');	
					$pdf->Cell(259*0.5/13,$height,utf8_decode("ID"),1,0,'C');	 
					$pdf->Cell(259*1.0/13,$height,utf8_decode("Elemento"),1,0,'C');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode("Posición"),1,0,'C');	
					$pdf->Cell(259*2.0/13,$height,utf8_decode("Diagnóstico"),1,0,'C');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode("Solución"),1,0,'C');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode("Tipo"),1,0,'C');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode("NP Sale"),1,0,'C');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode("NP Entra"),1,0,'C');	
					$pdf->Cell(259*1.0/13,$height,utf8_decode("Tiempo"),1,0,'C');	
					$pdf->Ln($ln);
					$i = 1;
					$total_elementos = 0;
					foreach($elementos_reproceso as $elemento){
						$pdf->SetWidths(array(259*0.5/13,259*1.0/13,259*1.0/13,259*1.0/13,259*0.5/13,259*1.0/13,259*1.0/13,259*2.0/13,259*1.0/13,259*1.0/13,259*1.0/13,259*1.0/13,259*1.0/13));
						$pdf->Row(array($i++,$elemento["Sistema"]["nombre"],$elemento["Subsistema"]["nombre"],$elemento["PosicionSubsistema"]["nombre"],$elemento["IntervencionElementos"]["id_elemento"],$elemento["Elemento"]["nombre"],$elemento["Posiciones_Elemento"]["nombre"],$elemento["Diagnostico"]["nombre"],$elemento["Solucion"]["nombre"],$elemento["TipoElemento"]["nombre"],$elemento["IntervencionElementos"]["pn_saliente"],$elemento["IntervencionElementos"]["pn_entrante"],str_pad(floor($elemento["IntervencionElementos"]["tiempo"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($elemento["IntervencionElementos"]["tiempo"] % 60),2,"0",STR_PAD_LEFT)));
						/*
						$pdf->Cell(259*0.5/13,$height,$i++,1,0,'C');
						$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["Sistema"]["nombre"]),1,0,'L');	
						$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["Subsistema"]["nombre"]),1,0,'L');	
						$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["PosicionSubsistema"]["nombre"]),1,0,'L');	
						$pdf->Cell(259*0.5/13,$height,utf8_decode($elemento["IntervencionElementos"]["id_elemento"]),1,0,'C');	
						$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["Elemento"]["nombre"]),1,0,'L');	
						$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["Posiciones_Elemento"]["nombre"]),1,0,'L');	
						$pdf->Cell(259*2.0/13,$height,utf8_decode($elemento["Diagnostico"]["nombre"]),1,0,'L');	
						$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["Solucion"]["nombre"]),1,0,'L');	
						$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["TipoElemento"]["nombre"]),1,0,'L');	
						$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["IntervencionElementos"]["pn_saliente"]),1,0,'L');	
						$pdf->Cell(259*1.0/13,$height,utf8_decode($elemento["IntervencionElementos"]["pn_entrante"]),1,0,'L');	
						$pdf->Cell(259*1.0/13,$height,str_pad(floor($elemento["IntervencionElementos"]["tiempo"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($elemento["IntervencionElementos"]["tiempo"] % 60),2,"0",STR_PAD_LEFT),1,0,'C');	
						$pdf->Ln($ln);
						*/
						$total_elementos += $elemento["IntervencionElementos"]["tiempo"];
						$tintervencion["DCC"] += $elemento["IntervencionElementos"]["tiempo"];
					}
					$pdf->SetFillColor(160, 160, 160); 
					$pdf->Cell(259*12.0/13,$height,"",1,0,'C',true);	
					$pdf->Cell(259*1.0/13,$height,str_pad(floor($total_elementos / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($total_elementos % 60),2,"0",STR_PAD_LEFT),1,0,'C',true);	
					$pdf->Ln($ln);
				}
			}

			// Detalle de fechas
			$pdf->AddPage("P");	
			$pdf->Ln($ln);		
			$pdf->Cell(196,0,"Detalle de Fechas",0);
			$pdf->Ln($ln);
			$pdf->Ln($ln);
			
			$fechas = $this->IntervencionFechas->find('first', array('conditions' => array("IntervencionFechas.folio" => $intervencion["Planificacion"]["folio"]), 'recursive' => -1));

			if($fechas["IntervencionFechas"]["llamado"] != null && $fechas["IntervencionFechas"]["llamado"] != '') {
				if($fechas["IntervencionFechas"]["llegada"] != null && $fechas["IntervencionFechas"]["llegada"] != '') {
					if($fechas["IntervencionFechas"]["llamado"] != null && $fechas["IntervencionFechas"]["llamado"] != '') {
						$fechas["IntervencionFechas"]["llamado"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["llamado"]));
						$pdf->Cell(196*1.5/5,0,utf8_decode("Llamado"),0);
						$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["llamado"]),0);
						$pdf->Ln($ln);
					}
					if($fechas["IntervencionFechas"]["llegada"] != null && $fechas["IntervencionFechas"]["llegada"] != '') {
						$fechas["IntervencionFechas"]["llegada"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["llegada"]));
						$pdf->Cell(196*1.5/5,0,utf8_decode("Llegada"),0);
						$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["llegada"]),0);
						$pdf->Ln($ln);
					}
				}
			}
			
			if($fechas["IntervencionFechas"]["inicio_intervencion"] != null && $fechas["IntervencionFechas"]["inicio_intervencion"] != '') {
				if($fechas["IntervencionFechas"]["termino_intervencion"] != null && $fechas["IntervencionFechas"]["termino_intervencion"] != '') {	
					if($fechas["IntervencionFechas"]["inicio_intervencion"] != null && $fechas["IntervencionFechas"]["inicio_intervencion"] != '') {
						$fechas["IntervencionFechas"]["inicio_intervencion"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["inicio_intervencion"]));
						$pdf->Cell(196*1.5/5,0,utf8_decode("Inicio Intervención"),0);
						$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["inicio_intervencion"]),0);
						$pdf->Ln($ln);
					}
					if($fechas["IntervencionFechas"]["termino_intervencion"] != null && $fechas["IntervencionFechas"]["termino_intervencion"] != '') {	
						$fechas["IntervencionFechas"]["termino_intervencion"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["termino_intervencion"]));		
						$pdf->Cell(196*1.5/5,0,utf8_decode("Término Intervención"),0);
						$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["termino_intervencion"]),0);
						$pdf->Ln($ln);
					}
				}
			}
			
			if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) != "EX") {
				if($fechas["IntervencionFechas"]["inicio_desconexion"] != null && $fechas["IntervencionFechas"]["inicio_desconexion"] != '') {
					if($fechas["IntervencionFechas"]["inicio_desconexion"] != null && $fechas["IntervencionFechas"]["inicio_desconexion"] != '') {
						if($fechas["IntervencionFechas"]["inicio_desconexion"] != null && $fechas["IntervencionFechas"]["inicio_desconexion"] != '') {	
							$fechas["IntervencionFechas"]["inicio_desconexion"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["inicio_desconexion"]));		
							$pdf->Cell(196*1.5/5,0,utf8_decode("Inicio Desconexión"),0);
							$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["inicio_desconexion"]),0);
							$pdf->Ln($ln);
						}
						if($fechas["IntervencionFechas"]["termino_desconexion"] != null && $fechas["IntervencionFechas"]["termino_desconexion"] != '') {
							$fechas["IntervencionFechas"]["termino_desconexion"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["termino_desconexion"]));
							$pdf->Cell(196*1.5/5,0,utf8_decode("Término Desconexión"),0);
							$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["termino_desconexion"]),0);
							$pdf->Ln($ln);
						}
					}
				}
				
				if($fechas["IntervencionFechas"]["inicio_conexion"] != null && $fechas["IntervencionFechas"]["inicio_conexion"] != '') {
					if($fechas["IntervencionFechas"]["termino_conexion"] != null && $fechas["IntervencionFechas"]["termino_conexion"] != '') {
						if($fechas["IntervencionFechas"]["inicio_conexion"] != null && $fechas["IntervencionFechas"]["inicio_conexion"] != '') {
							$fechas["IntervencionFechas"]["inicio_conexion"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["inicio_conexion"]));
							$pdf->Cell(196*1.5/5,0,utf8_decode("Inicio Conexión"),0);
							$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["inicio_conexion"]),0);
							$pdf->Ln($ln);
						}
						if($fechas["IntervencionFechas"]["termino_conexion"] != null && $fechas["IntervencionFechas"]["termino_conexion"] != '') {
							$fechas["IntervencionFechas"]["termino_conexion"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["termino_conexion"]));
							$pdf->Cell(196*1.5/5,0,utf8_decode("Término Conexión"),0);
							$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["termino_conexion"]),0);
							$pdf->Ln($ln);
						}
					}
				}
				
				if($fechas["IntervencionFechas"]["inicio_puesta_marcha"] != null && $fechas["IntervencionFechas"]["inicio_puesta_marcha"] != '') {
					if($fechas["IntervencionFechas"]["termino_puesta_marcha"] != null && $fechas["IntervencionFechas"]["termino_puesta_marcha"] != '') {
						if($fechas["IntervencionFechas"]["inicio_puesta_marcha"] != null && $fechas["IntervencionFechas"]["inicio_puesta_marcha"] != '') {
							$fechas["IntervencionFechas"]["inicio_puesta_marcha"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["inicio_puesta_marcha"]));
							$pdf->Cell(196*1.5/5,0,utf8_decode("Inicio Puesta en Marcha"),0);
							$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["inicio_puesta_marcha"]),0);
							$pdf->Ln($ln);
						}
						if($fechas["IntervencionFechas"]["termino_puesta_marcha"] != null && $fechas["IntervencionFechas"]["termino_puesta_marcha"] != '') {
							$fechas["IntervencionFechas"]["termino_puesta_marcha"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["termino_puesta_marcha"]));
							$pdf->Cell(196*1.5/5,0,utf8_decode("Término Puesta en Marcha"),0);
							$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["termino_puesta_marcha"]),0);
							$pdf->Ln($ln);
						}
					}
				}
	
				if($fechas["IntervencionFechas"]["inicio_prueba_potencia"] != null && $fechas["IntervencionFechas"]["inicio_prueba_potencia"] != '') {
					if($fechas["IntervencionFechas"]["termino_prueba_potencia"] != null && $fechas["IntervencionFechas"]["termino_prueba_potencia"] != '') {
						if($fechas["IntervencionFechas"]["inicio_prueba_potencia"] != null && $fechas["IntervencionFechas"]["inicio_prueba_potencia"] != '') {
							$fechas["IntervencionFechas"]["inicio_prueba_potencia"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["inicio_prueba_potencia"]));
							$pdf->Cell(196*1.5/5,0,utf8_decode("Inicio Prueba de Potencia"),0);
							$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["inicio_prueba_potencia"]),0);
							$pdf->Ln($ln);
						}
						if($fechas["IntervencionFechas"]["termino_prueba_potencia"] != null && $fechas["IntervencionFechas"]["termino_prueba_potencia"] != '') {
							$fechas["IntervencionFechas"]["termino_prueba_potencia"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["termino_prueba_potencia"]));
							$pdf->Cell(196*1.5/5,0,utf8_decode("Término Prueba de Potencia"),0);
							$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["termino_prueba_potencia"]),0);
							$pdf->Ln($ln);
						}
					}
				}
				
				if($fechas["IntervencionFechas"]["termino_reproceso"] != null && $fechas["IntervencionFechas"]["termino_reproceso"] != '') {
					$fechas["IntervencionFechas"]["termino_reproceso"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["termino_reproceso"]));
					$pdf->Cell(196*1.5/5,0,utf8_decode("Término Reproceso"),0);
					$pdf->Cell(196*3.5/5,0,utf8_decode($fechas["IntervencionFechas"]["termino_reproceso"]),0);
					$pdf->Ln($ln);
				}
			}
			
			// Calculos tiempos de intervención y esperas 
			// $tintervencion["DCC"] son ejecuciones
			if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) != "EX") {
				if($fechas["IntervencionFechas"]["termino_desconexion"] != null && $fechas["IntervencionFechas"]["termino_desconexion"] != '') {	
					if($fechas["IntervencionFechas"]["inicio_desconexion"] != null && $fechas["IntervencionFechas"]["inicio_desconexion"] != '') {
						$tintervencion["DCC"] += (strtotime($fechas["IntervencionFechas"]["termino_desconexion"]) - strtotime($fechas["IntervencionFechas"]["inicio_desconexion"])) / 60;
					}
				}
				
				if($fechas["IntervencionFechas"]["termino_conexion"] != null && $fechas["IntervencionFechas"]["termino_conexion"] != '') {	
					if($fechas["IntervencionFechas"]["inicio_conexion"] != null && $fechas["IntervencionFechas"]["inicio_conexion"] != '') {
						$tintervencion["DCC"] += (strtotime($fechas["IntervencionFechas"]["termino_conexion"]) - strtotime($fechas["IntervencionFechas"]["inicio_conexion"])) / 60;
					}
				}
				
				if($fechas["IntervencionFechas"]["termino_prueba_potencia"] != null && $fechas["IntervencionFechas"]["termino_prueba_potencia"] != '') {	
					if($fechas["IntervencionFechas"]["inicio_prueba_potencia"] != null && $fechas["IntervencionFechas"]["inicio_prueba_potencia"] != '') {
						$tintervencion["DCC"] += (strtotime($fechas["IntervencionFechas"]["termino_prueba_potencia"]) - strtotime($fechas["IntervencionFechas"]["inicio_prueba_potencia"])) / 60;
					}
				}
				
				if($fechas["IntervencionFechas"]["termino_puesta_marcha"] != null && $fechas["IntervencionFechas"]["termino_puesta_marcha"] != '') {	
					if($fechas["IntervencionFechas"]["inicio_puesta_marcha"] != null && $fechas["IntervencionFechas"]["inicio_puesta_marcha"] != '') {
						$tintervencion["DCC"] += (strtotime($fechas["IntervencionFechas"]["inicio_puesta_marcha"]) - strtotime($fechas["IntervencionFechas"]["termino_puesta_marcha"])) / 60;
					}
				}
			}
			
			if(empty($elementos)) {
				if(strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "EX"){
					if($fechas["IntervencionFechas"]["termino_intervencion"] != null && $fechas["IntervencionFechas"]["termino_intervencion"] != '') {	
						if($fechas["IntervencionFechas"]["inicio_intervencion"] != null && $fechas["IntervencionFechas"]["inicio_intervencion"] != '') {
							$tintervencion["OEM"] += (strtotime($fechas["IntervencionFechas"]["termino_intervencion"]) - strtotime($fechas["IntervencionFechas"]["inicio_intervencion"])) / 60;
						}
					}
				}
			}
			
			// $tintervencion["OEM"] son esperas
			if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) != "EX") {
				if($fechas["IntervencionFechas"]["inicio_intervencion"] != null && $fechas["IntervencionFechas"]["inicio_intervencion"] != '') {	
					if($fechas["IntervencionFechas"]["termino_intervencion"] != null && $fechas["IntervencionFechas"]["termino_intervencion"] != '') {	
						if($fechas["IntervencionFechas"]["inicio_desconexion"] != null && $fechas["IntervencionFechas"]["inicio_desconexion"] != '') {
							$tintervencion["OEM"] += (strtotime($fechas["IntervencionFechas"]["termino_intervencion"]) - strtotime($fechas["IntervencionFechas"]["inicio_desconexion"])) / 60;
						}
					}
				}
				
				if($fechas["IntervencionFechas"]["inicio_desconexion"] != null && $fechas["IntervencionFechas"]["inicio_desconexion"] != '') {
					if($fechas["IntervencionFechas"]["termino_desconexion"] != null && $fechas["IntervencionFechas"]["termino_desconexion"] != '') {	
						if($fechas["IntervencionFechas"]["inicio_conexion"] != null && $fechas["IntervencionFechas"]["inicio_conexion"] != '') {
							$tintervencion["OEM"] += (strtotime($fechas["IntervencionFechas"]["inicio_conexion"]) - strtotime($fechas["IntervencionFechas"]["termino_desconexion"])) / 60;
						}
					}
				}
				
				if($fechas["IntervencionFechas"]["inicio_conexion"] != null && $fechas["IntervencionFechas"]["inicio_conexion"] != '') {	
					if($fechas["IntervencionFechas"]["termino_conexion"] != null && $fechas["IntervencionFechas"]["termino_conexion"] != '') {	
						if($fechas["IntervencionFechas"]["inicio_puesta_marcha"] != null && $fechas["IntervencionFechas"]["inicio_puesta_marcha"] != '') {
							$tintervencion["OEM"] += (strtotime($fechas["IntervencionFechas"]["termino_conexion"]) - strtotime($fechas["IntervencionFechas"]["inicio_puesta_marcha"])) / 60;
						}
					}
				}
			}
			
			$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
			$pdf->Ln($ln);
			
			// Deltas
			$deltas_ = $this->DeltaDetalle->find('all', array('conditions' => array("DeltaDetalle.folio" => $intervencion["Planificacion"]["folio"]), 'recursive' => 1));
			
			unset($json["ExisteDelta1"],$json["ExisteDelta2"],$json["ExisteDelta3"],$json["ExisteDelta4"],$json["ExisteDelta5"],$json["ExisteDelta6"]);
			
			
			foreach ($deltas_ as $key => $value) {
				if(!is_numeric($value["DeltaDetalle"]["tiempo"])){
					unset($deltas_[$key]);
					continue;
				}
				if(is_numeric($value["DeltaDetalle"]["tiempo"]) && intval($value["DeltaDetalle"]["tiempo"]) < 1){
					unset($deltas_[$key]);
					continue;
				}
				if($value["DeltaItem"]["nombre"] == "Reparación & Diagnóstico"){
					continue;
				}
				if($value["DeltaItem"]["nombre"] == "Mantención"){
					continue;
				}
				if($value["DeltaItem"]["grupo"] == 1) {
					$json["ExisteDelta1"] = 1;
				}
				if($value["DeltaItem"]["grupo"] == 2) {
					$json["ExisteDelta2"] = 1;
				}
				if($value["DeltaItem"]["grupo"] == 3) {
					$json["ExisteDelta3"] = 1;
				}
				if($value["DeltaItem"]["grupo"] == 4) {
					$json["ExisteDelta4"] = 1;
				}
				if($value["DeltaItem"]["grupo"] == 5) {
					$json["ExisteDelta5"] = 1;
				}
				if($value["DeltaItem"]["grupo"] == 6) {
					$json["ExisteDelta6"] = 1;
				}
			}
			
			if(strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "EX"){
				unset($json["ExisteDelta4"],$json["ExisteDelta5"],$json["ExisteDelta6"]);	
			}
			
			if(strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "MP"){
				unset($json["ExisteDelta1"],$json["ExisteDelta2"],$json["ExisteDelta3"],$json["ExisteDelta4"],$json["ExisteDelta5"]);
				$json["ExisteDelta6"] = 1;
			}
			
			if(strtoupper($intervencion["Planificacion"]["tipointervencion"]) != "MP"){
				unset($json["ExisteDelta6"]);	
			}
						
			$deltas = 0;
			
			if(isset($json["ExisteDelta1"]) && $json["ExisteDelta1"] == "1") {
				$deltas++;
			}
			if(isset($json["ExisteDelta2"]) && $json["ExisteDelta2"] == "1") {
				$deltas++;
			}
			if(isset($json["ExisteDelta3"]) && $json["ExisteDelta3"] == "1") {
				$deltas++;
			}
			if(isset($json["ExisteDelta4"]) && $json["ExisteDelta4"] == "1") {
				$deltas++;
			}
			if(isset($json["ExisteDelta5"]) && $json["ExisteDelta5"] == "1") {
				$deltas++;
			}
			if(isset($json["ExisteDelta6"]) && $json["ExisteDelta6"] == "1") {
				$deltas++;
			}
			
			$deltas3 = array();
			foreach ($deltas_ as $key => $value) {
				$deltas3[$value["DeltaItem"]["nombre"]][$value["DeltaItem"]["grupo"]] = array($value["DeltaDetalle"]["tiempo"],$value["DeltaDetalle"]["delta_responsable_id"]);
			}
			
			foreach($deltas3["Mantención"] as $key2 => $value2) {
				$tiempo = $value2[0];
				$tintervencion["DCC"] += $tiempo;
			}
			unset($deltas3["Mantención"]);			
			unset($deltas3["Reparación & Diagnóstico"]);
			
			$sumas = array(0,0,0,0,0,0,0);
			
			if(!empty($deltas3)) {
				$pdf->SetFillColor(160, 160, 160);
				$pdf->Cell(196*1.5/5,$height,utf8_decode("Categoría Delta"),1,0,'C',true);
				if(isset($json["ExisteDelta1"]) && $json["ExisteDelta1"] == "1") {
					$pdf->Cell(196*(3.5/$deltas)/5,$height,"Llamado Llegada",1,0,'C',true);
				}
				if(isset($json["ExisteDelta2"]) && $json["ExisteDelta2"] == "1") { 
					$pdf->Cell(196*(3.5/$deltas)/5,$height,utf8_decode("Llegada Intervención"),1,0,'C',true);
				}	
				if(isset($json["ExisteDelta3"]) && $json["ExisteDelta3"] == "1") {
					$pdf->Cell(196*(3.5/$deltas)/5,$height,utf8_decode("Inicio Término"),1,0,'C',true);
				}
				if(isset($json["ExisteDelta4"]) && $json["ExisteDelta4"] == "1") {
					$pdf->Cell(196*(3.5/$deltas)/5,$height,utf8_decode("Término PP"),1,0,'C',true);
				}
				if(isset($json["ExisteDelta5"]) && $json["ExisteDelta5"] == "1") {
					$pdf->Cell(196*(3.5/$deltas)/5,$height,"PP Reproceso",1,0,'C',true);
				}
				if(isset($json["ExisteDelta6"]) && $json["ExisteDelta6"] == "1") {
					$pdf->Cell(196*(3.5/$deltas)/5,$height,utf8_decode("Mantención"),1,0,'C',true);
				}		
				$pdf->Ln($ln);
				$pdf->SetFillColor(255, 255, 255);
			}
			
			foreach ($deltas3 as $key => $value) {
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(196*1.5/5,$height,utf8_decode($key),1,0,'L',false);
				for($i = 1;$i < 7; $i++){
					if(isset($json["ExisteDelta".$i]) && $json["ExisteDelta".$i] == "1") {
						$grupo = $i;
						if(isset($value[$grupo])) {
							$tiempo = $value[$grupo][0];
							$responsable = $value[$grupo][1];
							$sumas[$grupo] += $tiempo;
							if($responsable == 1){
								$contratiempo["DCC"] += intval($tiempo);
								$pdf->SetTextColor(255,255,255);
								$pdf->SetFillColor(255, 0, 0);
							}elseif($responsable == 2){
								$contratiempo["OEM"] += intval($tiempo);
								$pdf->SetTextColor(255,255,255);
								$pdf->SetFillColor(0, 0, 255);
							}elseif($responsable == 3){
								$contratiempo["MINA"] += intval($tiempo);
								$pdf->SetFillColor(255, 255, 0);
							}
							$pdf->Cell(196*(3.5/$deltas)/5,$height,str_pad(floor($tiempo / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($tiempo % 60),2,"0",STR_PAD_LEFT),1,0,'C',true);
						} else {
							$pdf->Cell(196*(3.5/$deltas)/5,$height,"",1,0,'C',false);
						}
						$pdf->SetFillColor(255, 255, 255);
					}
				}
				$pdf->Ln($ln);
			}
			$pdf->SetTextColor(0,0,0);
			if(!empty($deltas3)) {
				$pdf->SetFillColor(160, 160, 160);
				$pdf->Cell(196*1.5/5,$height,utf8_decode("Totales"),1,0,'C',true);
				
				for($i = 1;$i < 7; $i++){
					if(isset($json["ExisteDelta".$i]) && $json["ExisteDelta".$i] == "1") {
						$pdf->Cell(196*(3.5/$deltas)/5,$height,str_pad(floor($sumas[$i] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($sumas[$i] % 60),2,"0",STR_PAD_LEFT),1,0,'C',true);
					}
				}
				
				$pdf->Ln($ln);
				$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
				$pdf->Ln($ln);
			}
			
			// Detalles totales deltas	
			$pdf->SetFillColor(160, 160, 160);
			$pdf->Cell(58.8,$height,"Responsable",1,0,'L',true);
			$pdf->Cell(45.472,$height,utf8_decode("Intervención"),1,0,'C',true);
			$pdf->Cell(45.472,$height,"Contratiempo",1,0,'C',true);
			$pdf->Cell(46.256,$height,"Total",1,0,'C',true);
			$pdf->Ln($ln);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFillColor(255, 0, 0);
			$pdf->Cell(58.8,$height,"DCC",1,0,'L',true);
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(45.472,$height,str_pad(floor($tintervencion["DCC"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($tintervencion["DCC"] % 60),2,"0",STR_PAD_LEFT),1,0,'C',false);
			$pdf->Cell(45.472,$height,str_pad(floor($contratiempo["DCC"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($contratiempo["DCC"] % 60),2,"0",STR_PAD_LEFT),1,0,'C',false);
			$pdf->Cell(46.256,$height,str_pad(floor(($contratiempo["DCC"]+$tintervencion["DCC"]) / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad((($contratiempo["DCC"]+$tintervencion["DCC"]) % 60),2,"0",STR_PAD_LEFT),1,0,'C',false);  
			$pdf->Ln($ln);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFillColor(0, 0, 255);
			$pdf->Cell(58.8,$height,"OEM",1,0,'L',true);
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(45.472,$height,str_pad(floor($tintervencion["OEM"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($tintervencion["OEM"] % 60),2,"0",STR_PAD_LEFT),1,0,'C',false);
			$pdf->Cell(45.472,$height,str_pad(floor($contratiempo["OEM"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($contratiempo["OEM"] % 60),2,"0",STR_PAD_LEFT),1,0,'C',false);
			$pdf->Cell(46.256,$height,str_pad(floor(($contratiempo["OEM"]+$tintervencion["OEM"]) / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad((($contratiempo["OEM"]+$tintervencion["OEM"]) % 60),2,"0",STR_PAD_LEFT),1,0,'C',false);  
			$pdf->Ln($ln);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFillColor(255, 255, 0);
			$pdf->Cell(58.8,$height,"MINA",1,0,'L',true);
			$pdf->Cell(45.472,$height,str_pad(floor($tintervencion["MINA"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($tintervencion["MINA"] % 60),2,"0",STR_PAD_LEFT),1,0,'C',false);
			$pdf->Cell(45.472,$height,str_pad(floor($contratiempo["MINA"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($contratiempo["MINA"] % 60),2,"0",STR_PAD_LEFT),1,0,'C',false);
			$pdf->Cell(46.256,$height,str_pad(floor(($contratiempo["MINA"]+$tintervencion["MINA"]) / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad((($contratiempo["MINA"]+$tintervencion["MINA"]) % 60),2,"0",STR_PAD_LEFT),1,0,'C',false);  
			$pdf->Ln($ln);
			
			$tintervencion["DCC"] = $tintervencion["DCC"] + $tintervencion["OEM"] + $tintervencion["MINA"];
			$contratiempo["DCC"] = $contratiempo["DCC"] + $contratiempo["OEM"] + $contratiempo["MINA"];
			
			$pdf->SetFillColor(160, 160, 160);
			$pdf->Cell(58.8,$height,"Total",1,0,'L',true);
			$pdf->Cell(45.472,$height,str_pad(floor($tintervencion["DCC"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($tintervencion["DCC"] % 60),2,"0",STR_PAD_LEFT),1,0,'C',true);
			$pdf->Cell(45.472,$height,str_pad(floor($contratiempo["DCC"] / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($contratiempo["DCC"] % 60),2,"0",STR_PAD_LEFT),1,0,'C',true);
			$pdf->Cell(46.256,$height,str_pad(floor(($contratiempo["DCC"]+$tintervencion["DCC"]) / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad((($contratiempo["DCC"]+$tintervencion["DCC"]) % 60),2,"0",STR_PAD_LEFT),1,0,'C',true);  
			$pdf->Ln($ln);

			$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
			$pdf->Ln($ln);
			
			$pdf->AddPage("P");
			$pdf->Ln($ln);
			
			
			
			$fluidos = $this->Fluido->find('all', array('order' => array("Fluido.nombre" => "ASC"), 'recursive' => 1));
			$tmp = array();
			
			$ingreso_fluidos = $this->IntervencionFluido->find('all', array('conditions'=>array('intervencion_id'=>$intervencion["Planificacion"]["id"]),'recursive' => -1));
			$tmp = array();
			foreach ($ingreso_fluidos as $fluido) {
				$tmp[$fluido["IntervencionFluido"]["fluido_id"]] = array($fluido["IntervencionFluido"]["cantidad"],$fluido["IntervencionFluido"]["tipo_ingreso_id"]);
			}
			$ingreso_fluidos = $tmp;
			$tmp = array();
			foreach ($fluidos as $fluido) {
				if(!isset($ingreso_fluidos[$fluido["Fluido"]["id"]])) {
					$ingreso_fluidos[$fluido["Fluido"]["id"]] = array(0,"");
				}
				if(isset($fluido["Fluido"]["tipo_ingreso"]) && $fluido["Fluido"]["tipo_ingreso"] != null && count(explode(",", $fluido["Fluido"]["tipo_ingreso"])) > 1) {
					$tipos_ingreso = $this->FluidoTipoIngreso->find('list', array('conditions'=>array('id IN' => explode(",", $fluido["Fluido"]["tipo_ingreso"])),'order' => 'nombre'));
				} elseif(isset($fluido["Fluido"]["tipo_ingreso"]) && $fluido["Fluido"]["tipo_ingreso"] != null && count(explode(",", $fluido["Fluido"]["tipo_ingreso"])) == 1) {
					$tipos_ingreso = $this->FluidoTipoIngreso->find('list', array('conditions'=>array('id' => $fluido["Fluido"]["tipo_ingreso"]),'order' => 'nombre'));
				} else {
					$tipos_ingreso = array();
				}
				$tmp[$fluido["Fluido"]["id"]] = array($fluido["Fluido"]["nombre"],$fluido["FluidoUnidad"]["nombre"],$tipos_ingreso,$ingreso_fluidos[$fluido["Fluido"]["id"]][0],$ingreso_fluidos[$fluido["Fluido"]["id"]][1]);
			}
			$fluidos = $tmp;
			
			$existe_fluidos = false;
			foreach($fluidos as $key => $value) { 
				if(is_numeric($value[3]) && intval($value[3]) > 0){
					$existe_fluidos = true;
				}
			}
			
			if($existe_fluidos) {
				$pdf->Cell(50,0,"Fluidos",0);
				$pdf->Ln($ln);
				$pdf->Ln($ln);
					
				foreach($fluidos as $key => $value) { 
					if(is_numeric($value[3]) && intval($value[3]) == 0){
						continue;
					}
					
					$tipo_ingreso = "";
					if (!empty($value[2])) {
						foreach($value[2] as $key2 => $value2) {
							if($value[4] == $key2){
								$tipo_ingreso = $value2;
								break;
							}
						}
					}
					$pdf->Cell(196*1.5/5 ,0,utf8_decode($value[0]),0);
					$pdf->Cell(196*1.16/5,0,utf8_decode($value[3]),0);
					$pdf->Cell(196*1.16/5,0,utf8_decode($value[1]),0);
					$pdf->Cell(196*1.18/5,0,utf8_decode($tipo_ingreso),0);
					$pdf->Ln($ln);
				}
				
				$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
				$pdf->Ln($ln);
			}
			
			if ((isset($json["AvanceHoraPotenciaTotal"]) && $json["AvanceHoraPotenciaTotal"] != "0" && $json["AvanceHoraPotenciaTotal"] != "")
				|| (isset($json["AvanceHoraPotenciaDCC"]) && $json["AvanceHoraPotenciaDCC"] != "0" && $json["AvanceHoraPotenciaDCC"] != "")
				|| (isset($json["AvanceHoraPotenciaOEM"]) && $json["AvanceHoraPotenciaOEM"] != "0" && $json["AvanceHoraPotenciaOEM"] != "")
				|| (isset($json["AvanceHoraPotenciaMINA"]) && $json["AvanceHoraPotenciaMINA"] != "0" && $json["AvanceHoraPotenciaMINA"] != "")) {
				$pdf->Cell(50,0,"Avance Horas",0);
				$pdf->Ln($ln);
	
				if(isset($json["AvanceHoraPotenciaTotal"]) && $json["AvanceHoraPotenciaTotal"] != "0" && $json["AvanceHoraPotenciaTotal"] != "") {
					$pdf->Cell(196*1.5/5,0,"Potencia",0);
					$pdf->Cell(196*1.16/5,0,$json["AvanceHoraPotenciaTotal"],0);
					$pdf->Cell(196*2.34/5,0,utf8_decode($json["AvanceHoraPotenciaTotalObservacion"]),0);
					$pdf->Ln($ln);
				}
				
				if(isset($json["AvanceHoraPotenciaDCC"]) && $json["AvanceHoraPotenciaDCC"] != "0" && $json["AvanceHoraPotenciaDCC"] != "") {
					$pdf->Cell(196*1.5/5,0,"DCC",0);
					$pdf->Cell(196*1.16/5,0,$json["AvanceHoraPotenciaDCC"],0);
					$pdf->Cell(196*2.34/5,0,utf8_decode($json["AvanceHoraPotenciaDCCObservacion"]),0);
					$pdf->Ln($ln);
				}
				
				if(isset($json["AvanceHoraPotenciaOEM"]) && $json["AvanceHoraPotenciaOEM"] != "0" && $json["AvanceHoraPotenciaOEM"] != "") {
					$pdf->Cell(196*1.5/5,0,"OEM",0);
					$pdf->Cell(196*1.16/5,0,$json["AvanceHoraPotenciaOEM"],0);
					$pdf->Cell(196*2.34/5,0,utf8_decode($json["AvanceHoraPotenciaOEMObservacion"]),0);
					$pdf->Ln($ln);
				}
				
				if(isset($json["AvanceHoraPotenciaMINA"]) && $json["AvanceHoraPotenciaMINA"] != "0" && $json["AvanceHoraPotenciaMINA"] != "") {
					$pdf->Cell(196*1.5/5,0,"MINA",0);
					$pdf->Cell(196*1.16/5,0,$json["AvanceHoraPotenciaMINA"],0);
					$pdf->Cell(196*2.34/5,0,utf8_decode($json["AvanceHoraPotenciaMINAObservacion"]),0);
					$pdf->Ln($ln);
				}
				
				$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
				$pdf->Ln($ln);
			}
			
			$comentarios = $this->IntervencionComentarios->find('first', array('conditions' => array("IntervencionComentarios.folio" => $intervencion["Planificacion"]["folio"]), 'recursive' => -1));
			$pdf->Cell(0,0,"Comentarios",0);
			$pdf->Ln($ln);
			
			$pdf->MultiCell(196,5,utf8_decode($comentarios["IntervencionComentarios"]["comentario"]),0);
			$pdf->Ln($ln);
			$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
			$pdf->Ln($ln);
			
			$pdf->Cell(196,0,utf8_decode("Códigos KCH"),0);
			$pdf->Ln($ln);
			
			$pdf->MultiCell(196,5,utf8_decode($comentarios["IntervencionComentarios"]["codigo_kch"]),0);
			$pdf->Ln($ln);
			$pdf->Cell(196,0,utf8_decode("______________________________________________________________________________"),0,0,'C');	
			$pdf->Ln($ln);
			
			$backlogs = $this->Backlog->find('all',  array(
				'conditions' => array("Backlog.folio" => $intervencion["Planificacion"]["folio"]), 
				'recursive' => 1
			));
			
			
			/* Agregar tiempo estimado */
			if (!empty($backlogs)){
				$pdf->AddPage("L");
				$pdf->SetFillColor(160, 160, 160);
				$pdf->Cell(259,$height,utf8_decode("Backlogs"),1,0,'C',true);	
				$pdf->Ln($ln);
				$pdf->Cell(259*0.5/13,$height,"#",1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Criticidad"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Responsable"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Sistema"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Subsistema"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Posición"),1,0,'C');	
				$pdf->Cell(259*0.5/13,$height,utf8_decode("ID"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Elemento"),1,0,'C');	
				$pdf->Cell(259*1.0/13,$height,utf8_decode("Posición"),1,0,'C');	
				$pdf->Cell(259*2.0/13,$height,utf8_decode("Diagnóstico"),1,0,'C');	
				$pdf->Cell(259*0.9/13,$height,utf8_decode("NP"),1,0,'C');	
				$pdf->Cell(259*1.6/13,$height,utf8_decode("Comentarios"),1,0,'C');	
				$pdf->Cell(259*0.5/13,$height,utf8_decode("Estimado"),1,0,'C');	
				$pdf->Ln($ln);
				
				
				$i = 1;
				foreach($backlogs as $backlog){
					$tiempo_estimado = $backlog["Backlog"]["tiempo_estimado"];			
					$backlog["Backlog"]["tiempo_estimado_hora"] = str_pad(floor($tiempo_estimado / 60), 2, "0", STR_PAD_LEFT);
					$backlog["Backlog"]["tiempo_estimado_minuto"] = str_pad(($tiempo_estimado % 60), 2, "0", STR_PAD_LEFT);
					$elemento = $this->IntervencionElementos->find('first',  array(
						'conditions' => array("IntervencionElementos.id" => $backlog["Backlog"]["elemento_id"]), 
						'recursive' => 1
					));
					
					if($backlog["Backlog"]["criticidad_id"] == "1") {$backlog["Backlog"]["criticidad_id"] = "Alto";}
					if($backlog["Backlog"]["criticidad_id"] == "2") {$backlog["Backlog"]["criticidad_id"] = "Medio";}
					if($backlog["Backlog"]["criticidad_id"] == "3") {$backlog["Backlog"]["criticidad_id"] = "Bajo";}
					
					if($backlog["Backlog"]["responsable_id"] == "1") {$backlog["Backlog"]["responsable_id"] = "DCC";}
					if($backlog["Backlog"]["responsable_id"] == "2") {$backlog["Backlog"]["responsable_id"] = "OEM";}
					if($backlog["Backlog"]["responsable_id"] == "3") {$backlog["Backlog"]["responsable_id"] = "MINA";}
					
					
					$pdf->SetWidths(array(259*0.5/13,259*1.0/13,259*1.0/13,259*1.0/13,259*1.0/13,259*1.0/13,259*0.5/13,259*1.0/13,259*1.0/13,259*2.0/13,259*0.9/13,259*1.6/13,259*0.5/13));
					
					
					$pdf->Row(array($i++,$backlog["Backlog"]["criticidad_id"],$backlog["Backlog"]["responsable_id"],$elemento["Sistema"]["nombre"],$elemento["Subsistema"]["nombre"],$elemento["PosicionSubsistema"]["nombre"],$elemento["IntervencionElementos"]["id_elemento"],$elemento["Elemento"]["nombre"],$elemento["Posiciones_Elemento"]["nombre"],$elemento["Diagnostico"]["nombre"],$elemento["IntervencionElementos"]["pn_saliente"],$backlog["Backlog"]["comentario"],$backlog["Backlog"]["tiempo_estimado_hora"].':'.$backlog["Backlog"]["tiempo_estimado_minuto"]));
					
					/*$pdf->Cell(259*0.5/12,$height,$i++,1,0,'C');
					$pdf->Cell(259*1.0/12,$height,utf8_decode($backlog["Backlog"]["criticidad_id"]),1,0,'C');	
					$pdf->Cell(259*1.0/12,$height,utf8_decode($backlog["Backlog"]["responsable_id"]),1,0,'C');	
					$pdf->Cell(259*1.0/12,$height,utf8_decode($elemento["Sistema"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*1.0/12,$height,utf8_decode($elemento["Subsistema"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*1.0/12,$height,utf8_decode($elemento["PosicionSubsistema"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*0.5/12,$height,utf8_decode($elemento["IntervencionElementos"]["id_elemento"]),1,0,'C');	
					$pdf->Cell(259*1.0/12,$height,utf8_decode($elemento["Elemento"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*1.0/12,$height,utf8_decode($elemento["Posiciones_Elemento"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*2.0/12,$height,utf8_decode($elemento["Diagnostico"]["nombre"]),1,0,'L');	
					$pdf->Cell(259*1.0/12,$height,utf8_decode($elemento["IntervencionElementos"]["pn_saliente"]),1,0,'L');	
					$pdf->Cell(259*1.0/12,$height,utf8_decode($backlog["Backlog"]["comentario"]),1,0,'C');	
					$pdf->Ln($ln);*/
				}
			}

			
			if (strtotime($fechas["IntervencionFechas"]["fecha_termino_global"]) < strtotime($fechas["IntervencionFechas"]["fecha_inicio_global"])) {
				$tmp = $fechas["IntervencionFechas"]["fecha_termino_global"];
				$fechas["IntervencionFechas"]["fecha_termino_global"] = $fechas["IntervencionFechas"]["fecha_inicio_global"];
				$fechas["IntervencionFechas"]["fecha_inicio_global"] = $tmp;
			}
			
			$pdf->AddPage("P");
			$pdf->SetFillColor(160, 160, 160);
			$pdf->Cell(196,$height,utf8_decode("Tiempo Global de la Intervención"),1,0,'C',true);	
			$pdf->Ln($ln);
			$pdf->Cell(65,$height,"Fecha Inicio",1,0,'C');	
			$pdf->Cell(66,$height,utf8_decode("Fecha Término"),1,0,'C');	
			$pdf->Cell(65,$height,utf8_decode("Duración Total"),1,0,'C');	
			$pdf->Ln($ln);
			$duracion = strtotime($fechas["IntervencionFechas"]["fecha_termino_global"]) - strtotime($fechas["IntervencionFechas"]["fecha_inicio_global"]);
			if($duracion < 0) {
				$duracion = 0;
			}
			
			$duracion = $duracion / 60;
			$duracion = str_pad(floor($duracion / 60),2,"0",STR_PAD_LEFT) . ":" . str_pad(($duracion % 60),2,"0",STR_PAD_LEFT);
			
			$fechas["IntervencionFechas"]["fecha_inicio_global"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["fecha_inicio_global"]));
			$fechas["IntervencionFechas"]["fecha_termino_global"] = date("d-m-Y h:i A",strtotime($fechas["IntervencionFechas"]["fecha_termino_global"]));
			$pdf->Cell(65,$height,$fechas["IntervencionFechas"]["fecha_inicio_global"],1,0,'C');	
			$pdf->Cell(66,$height,$fechas["IntervencionFechas"]["fecha_termino_global"],1,0,'C');	
			$pdf->Cell(65,$height,$duracion,1,0,'C');	
			$pdf->Ln($ln);
			$pdf->Ln($ln);
			
			$pdf->SetFillColor(160, 160, 160);
			$pdf->Cell(196,$height,utf8_decode("Firma Digital"),1,0,'C',true);	
			$pdf->Ln($ln);
			$pdf->Cell(65,$height,utf8_decode("Técnico"),1,0,'C');	
			$pdf->Cell(66,$height,utf8_decode("Supervisor DCC"),1,0,'C');	
			$pdf->Cell(65,$height,utf8_decode("Supervisor Cliente"),1,0,'C');	
			$pdf->Ln($ln);
			
			if(isset($json["UserID"]) && is_numeric($json["UserID"])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($json["UserID"])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$pdf->Cell(65,$height,utf8_decode(preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))))),1,0,'C');
				}
			}
			
			if(isset($intervencion["Planificacion"]["aprobador_id"]) && is_numeric($intervencion["Planificacion"]["aprobador_id"])){
				$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.id' => intval($intervencion["Planificacion"]["aprobador_id"])), 'recursive' => -1));
				if(isset($usuario["Usuario"]["nombres"])) {
					$pdf->Cell(66,$height,utf8_decode(preg_replace('/\s+/', ' ', ucwords(trim(strtolower($usuario["Usuario"]["nombres"]))) . " " .ucwords(trim(strtolower($usuario["Usuario"]["apellidos"]))))),1,0,'C');
				} else {
					$pdf->Cell(66,$height,utf8_decode(""),1,0,'C');	
				}
			}else{
				$pdf->Cell(66,$height,utf8_decode(""),1,0,'C');	
			}
			$pdf->Cell(65,$height,utf8_decode(""),1,0,'C');	
			$pdf->Ln($ln);
			
			
			$intervencion["Planificacion"]["fecha_guardado"] = date("d-m-Y h:i A",strtotime($intervencion["Planificacion"]["fecha_guardado"]));
			$intervencion["Planificacion"]["fecha_aprobacion"] = date("d-m-Y h:i A",strtotime($intervencion["Planificacion"]["fecha_aprobacion"]));
			$pdf->Cell(65,$height,utf8_decode($intervencion["Planificacion"]["fecha_guardado"]),1,0,'C');	
			
			if($intervencion["Planificacion"]["fecha_aprobacion"] != null && $intervencion["Planificacion"]["aprobador_id"] != null) {
				$pdf->Cell(66,$height,utf8_decode($intervencion["Planificacion"]["fecha_aprobacion"]),1,0,'C');	
			} else {
				$pdf->Cell(66,$height,utf8_decode(""),1,0,'C');	
			}
			
			
			
			$pdf->Cell(65,$height,utf8_decode(""),1,0,'C');	
			$pdf->Ln($ln);

			$qr = new QrController();
			
			//Base URL
			$baseUrl = (IS_HTTPS ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];
			
			$image_tecnico = $qr->generate_internal($baseUrl."/Pdf/Certificado/".base64_encode($intervencion["Planificacion"]["folio"]));
			$pdf->Image($image_tecnico,13,88,60,60,'PNG');
			
			if($intervencion["Planificacion"]["fecha_aprobacion"] != null && $intervencion["Planificacion"]["aprobador_id"] != null) {
				$image_tecnico = $qr->generate_internal($baseUrl."/Pdf/Aprobado/".base64_encode($intervencion["Planificacion"]["folio"]));
				$pdf->Image($image_tecnico,77,88,60,60,'PNG');
			}
			
			//$pdf->Cell(65,$height,,1,0,'C');	
			$pdf->Cell(65,60,utf8_decode(""),1,0,'C');	
			$pdf->Cell(66,60,utf8_decode(""),1,0,'C');	
			$pdf->Cell(65,60,utf8_decode(""),1,0,'C');	
			$pdf->Ln($ln);
			
			$pdf->Output();
			exit;
		}
	}
		
?>