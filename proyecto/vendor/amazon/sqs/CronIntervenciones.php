<?php
	error_reporting(0);
	ini_set('memory_limit','53687091');
	$minuto = intval(date("i"));
	if ($minuto % 3 == 0) {
		exit;	
	}
	include("/var/www/html/configuracion-dbm.php");
	$dbconn = pg_connect("host=".DB_HOST." dbname=".DB_BASE." user=".DB_USER." password=".DB_PASS."") or die('No se ha podido conectar: ' . pg_last_error()); 
	$log_file = "/var/www/html/vendor/amazon/sqs/logs/cron_intervencion_".date("Y-m-d_A").".log";
	$file = fopen($log_file, "a");
	fwrite($file, date("Y-m-d H:i:s") . ": Inicio de registro de mensajes SQS.\n");
	
	try {
		$query = "SELECT id, folio, faena_id, flota_id, unidad_id, tipo_intervencion, hijo, padre, json, fecha_guardado, correlativo, message_id, procesado FROM aws_sqs_message WHERE procesado = '0' ORDER BY fecha_guardado DESC;";
		$resultado = pg_query($query);
		$resultados = pg_num_rows($resultado);
		
		if($resultados < 1){
			fwrite($file, date("Y-m-d H:i:s") . ": No hay mensajes para procesar.\n");
		}
		
		while($row = pg_fetch_assoc($resultado)) {
			//pg_query("BEGIN");
			$result_query = array();
			
			$json = json_decode(base64_decode($row["json"]), true);
			$json_original = base64_decode($row["json"]);
			$json_original = str_replace(array("\r", "\n"), ' ', $json_original);
			$json_original = pg_escape_string($dbconn, $json_original);
			
			$fecha_inicio = date("Y-m-d",strtotime(str_replace("/","-",$json["FechaInicioGlobal"])));
			$hora_inicio = date("H:i:s",strtotime(str_replace("/","-",$json["FechaInicioGlobal"])));
			$fecha_termino = date("Y-m-d",strtotime(str_replace("/","-",$json["FechaTerminoGlobal"])));
			$hora_termino = date("H:i:s",strtotime(str_replace("/","-",$json["FechaTerminoGlobal"])));
			$tiempo_trabajo = (strtotime(str_replace("/","-",$json["FechaTerminoGlobal"])) - strtotime(str_replace("/","-",$json["FechaInicioGlobal"]))) / 60; 
			$hours = floor($tiempo_trabajo / 60);
			$minutes = $tiempo_trabajo % 60; 
			$tiempo_trabajo = str_pad($hours, 2, "0", STR_PAD_LEFT).':'.str_pad($minutes, 2, "0", STR_PAD_LEFT);
			
			$fecha_guardado = $row["fecha_guardado"];
			
			$equipo_id = "";
			
			$query = "SELECT id, folio, correlativo_final, backlog_id, sintoma_id, categoria_sintoma_id, motivo_llamado_id FROM planificacion WHERE folio = '{$row["folio"]}';";
			
			fwrite($file, $query."\n");
			
			$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
			$existe = pg_num_rows($result);
			
			$planificacion_id = '';
			$correlativo_final = '';
			
			if ($existe < 1) {
				// No es una intervencion programada
				$query = "INSERT INTO planificacion (folio,message_id,faena_id,flota_id,unidad_id,tipointervencion,json,supervisor_responsable,turno_id,periodo_id,lugar_reparacion_id,tecnico_principal,fecha_guardado,fecha_sincronizacion,estado,fecha,hora,fecha_termino,hora_termino,tiempo_trabajo,terminado,sintoma_id, correlativo, categoria_sintoma_id, motivo_llamado_id) "
				."VALUES ('{$row["folio"]}', '{$row["message_id"]}','{$row["faena_id"]}','{$row["flota_id"]}','{$row["unidad_id"]}','{$row["tipo_intervencion"]}','$json_original','{$json["SupervisorResponsable"]}','{$json["Turno"]}','{$json["Periodo"]}','{$json["LugarReparacion"]}','{$json["UserID"]}','$fecha_guardado','".date("Y-m-d H:i:s")."','7','".$fecha_inicio."','".$hora_inicio."','".$fecha_termino."','".$hora_termino."','$tiempo_trabajo','S','{$json["SintomaID"]}','{$row["correlativo"]}','{$json["CategoriaID"]}','{$json["MotivoID"]}') returning id;";
			} else {
				$arr = pg_fetch_assoc($result);
				$planificacion_id = $arr["id"];
				$correlativo_final = $arr["correlativo_final"];
				
				if (@$json["CategoriaID"] == '' || @$json["CategoriaID"] == NULL){
					if(isset($arr["categoria_sintoma_id"]) && is_numeric($arr["categoria_sintoma_id"])){
						$json["CategoriaID"] = $arr["categoria_sintoma_id"];
					}
				}

				if (@$json["MotivoID"] == '' || @$json["MotivoID"] == NULL){
					if(isset($arr["motivo_llamado_id"]) && is_numeric($arr["motivo_llamado_id"])){
						$json["MotivoID"] = $arr["motivo_llamado_id"];
					}
				}
				
				if(isset($arr["backlog_id"]) && $arr["backlog_id"] != NULL && is_numeric($arr["backlog_id"])){
					$backlog_id = $arr["backlog_id"];
					$query = "UPDATE backlog SET estado_id = '11' WHERE id = '$backlog_id';";
					fwrite($file, $query."\n"); 
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				}
				
				$query = "UPDATE planificacion SET message_id='{$row["message_id"]}',json='$json_original',supervisor_responsable='{$json["SupervisorResponsable"]}',turno_id='{$json["Turno"]}',periodo_id='{$json["Periodo"]}',lugar_reparacion_id='{$json["LugarReparacion"]}',tecnico_principal={$json["UserID"]},fecha_guardado='$fecha_guardado',fecha_sincronizacion='".date("Y-m-d H:i:s")."',estado='7',fecha='".$fecha_inicio."',hora='".$hora_inicio."',fecha_termino='".$fecha_termino."',hora_termino='".$hora_termino."',tiempo_trabajo='$tiempo_trabajo',terminado='S',correlativo='{$row["correlativo"]}' WHERE folio = '{$row["folio"]}';";
			}
			
			$query = str_replace("''","NULL",$query);
			fwrite($file, $query."\n");
			$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
			if (!$result) {
				$registroExitoso = false;
			}
			if($planificacion_id == ''){
				$arr = pg_fetch_assoc($result);
				$planificacion_id = $arr["id"];
			}
			
			if ($existe < 1) {
				$query = "UPDATE planificacion SET correlativo_final = $planificacion_id WHERE id = $planificacion_id;";
				$correlativo_final = $planificacion_id;
				fwrite($file, $query."\n");
				$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				if (!$result) {
					$registroExitoso = false;
				}
			}
			
			// Existe continuacion
			if(isset($json["CodigoPendiente"]) && $json["CodigoPendiente"] != '') {
				fwrite($file, "\nExiste continuacion\n");
				// La intervencion genera continuación
				// Cuantas continuaciones tiene?
				$query = "SELECT correlativo FROM planificacion WHERE correlativo = '{$row["correlativo"]}';";
				fwrite($file, $query."\n");
				$result = pg_query($query) or fwrite($file2, 'La consulta fallo: ' . pg_last_error()."\n");
				if (!$result) {
					$registroExitoso = false;
				}
				$continuaciones = pg_num_rows($result) + 1;
				$folio = $row["correlativo"]."".$continuaciones;
				$file2 = fopen("intervenciones/$folio"."C.log", "w") or print("Unable to open file!");
				$txt2 = "Folio Continuacion: $folio\n";
				fwrite($file, $txt2."\n");
				
				
				// Se verifica si existe intervencion con mismo padre. si existe no se registra la continuacion, ya que esto genera planificaciones extras
				$query_t = "SELECT id, padre FROM planificacion WHERE padre = '{$row["folio"]}';";				
				$result_t = pg_query($query_t);
				$existe_t = pg_num_rows($result_t);
				if ($existe_t < 1) {				
					$query = "INSERT INTO planificacion (folio,faena_id,flota_id,unidad_id,tipointervencion,tipomantencion,json,estado,fecha,hora,sintoma_id,padre,correlativo,terminado,backlog_id,correlativo_final,categoria_sintoma_id, motivo_llamado_id) "
					."VALUES ('$folio','{$row["faena_id"]}','{$row["flota_id"]}','{$row["unidad_id"]}','{$row["tipo_intervencion"]}','{$json["TipoMantencion"]}','$json_original','2','".$fecha_termino."','".$hora_termino."','{$json["SintomaID"]}','{$row["folio"]}','{$row["correlativo"]}','N','{$json["BacklogID"]}','$correlativo_final','{$json["CategoriaID"]}','{$json["MotivoID"]}');";
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file2, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				fclose($file2);
			}
			
			//echo "\n\nRegistro de fechas\n\n";
			
			$json["FechaPlanificada"] = str_replace("/","-",$json["FechaPlanificada"]);
			$json["FechaPlanificada"] = date("Y-m-d h:i:s A", strtotime($json["FechaPlanificada"]));
			
			$json["LlamadoFechaCompleta"] = str_replace("/","-",$json["LlamadoFechaCompleta"]);
			$json["LlamadoFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["LlamadoFechaCompleta"]));
			
			$json["LlegadaFechaCompleta"] = str_replace("/","-",$json["LlegadaFechaCompleta"]);
			$json["LlegadaFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["LlegadaFechaCompleta"]));
			
			$json["InicioIntervencionFechaCompleta"] = str_replace("/","-",$json["InicioIntervencionFechaCompleta"]);
			$json["InicioIntervencionFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["InicioIntervencionFechaCompleta"]));
			
			$json["TerminoIntervencionFechaCompleta"] = str_replace("/","-",$json["TerminoIntervencionFechaCompleta"]);
			$json["TerminoIntervencionFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["TerminoIntervencionFechaCompleta"]));
			
			$json["FechaInicioGlobal"] = str_replace("/","-",$json["FechaInicioGlobal"]);
			$json["FechaInicioGlobal"] = date("Y-m-d h:i:s A", strtotime($json["FechaInicioGlobal"]));
			
			$json["FechaTerminoGlobal"] = str_replace("/","-",$json["FechaTerminoGlobal"]);
			$json["FechaTerminoGlobal"] = date("Y-m-d h:i:s A", strtotime($json["FechaTerminoGlobal"]));
			
			$json["InicioPruebaPotenciaFechaCompleta"] = str_replace("/","-",$json["InicioPruebaPotenciaFechaCompleta"]);
			$json["InicioPruebaPotenciaFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["InicioPruebaPotenciaFechaCompleta"]));
			
			$json["TerminoPruebaPotenciaFechaCompleta"] = str_replace("/","-",$json["TerminoPruebaPotenciaFechaCompleta"]);
			$json["TerminoPruebaPotenciaFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["TerminoPruebaPotenciaFechaCompleta"]));
			
			$json["InicioDesconexionFechaCompleta"] = str_replace("/","-",$json["InicioDesconexionFechaCompleta"]);
			$json["InicioDesconexionFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["InicioDesconexionFechaCompleta"]));
			
			$json["TerminoDesconexionFechaCompleta"] = str_replace("/","-",$json["TerminoDesconexionFechaCompleta"]);
			$json["TerminoDesconexionFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["TerminoDesconexionFechaCompleta"]));
			
			$json["InicioConexionFechaCompleta"] = str_replace("/","-",$json["InicioConexionFechaCompleta"]);
			$json["InicioConexionFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["InicioConexionFechaCompleta"]));
			
			$json["TerminoConexionFechaCompleta"] = str_replace("/","-",$json["TerminoConexionFechaCompleta"]);
			$json["TerminoConexionFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["TerminoConexionFechaCompleta"]));
			
			$json["InicioPuestaMarchaFechaCompleta"] = str_replace("/","-",$json["InicioPuestaMarchaFechaCompleta"]);
			$json["InicioPuestaMarchaFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["InicioPuestaMarchaFechaCompleta"]));
			
			$json["TerminoPuestaMarchaFechaCompleta"] = str_replace("/","-",$json["TerminoPuestaMarchaFechaCompleta"]);
			$json["TerminoPuestaMarchaFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["TerminoPuestaMarchaFechaCompleta"]));
			
			$json["TerminoReprocesoFechaCompleta"] = str_replace("/","-",$json["TerminoReprocesoFechaCompleta"]);
			$json["TerminoReprocesoFechaCompleta"] = date("Y-m-d h:i:s A", strtotime($json["TerminoReprocesoFechaCompleta"]));
			
			// Fix fechas QA y DEV en servidores KCL
			if (strtotime($json["LlamadoFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["LlamadoFechaCompleta"] = ''; }
			if (strtotime($json["LlegadaFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["LlegadaFechaCompleta"] = ''; }
			if (strtotime($json["InicioIntervencionFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["InicioIntervencionFechaCompleta"] = ''; }
			if (strtotime($json["TerminoIntervencionFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["TerminoIntervencionFechaCompleta"] = ''; }
			if (strtotime($json["InicioPruebaPotenciaFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["InicioPruebaPotenciaFechaCompleta"] = ''; }
			if (strtotime($json["TerminoPruebaPotenciaFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["TerminoPruebaPotenciaFechaCompleta"] = ''; }
			if (strtotime($json["InicioDesconexionFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["InicioDesconexionFechaCompleta"] = ''; }
			if (strtotime($json["TerminoDesconexionFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["TerminoDesconexionFechaCompleta"] = ''; }
			if (strtotime($json["InicioConexionFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["InicioConexionFechaCompleta"] = ''; }
			if (strtotime($json["TerminoConexionFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["TerminoConexionFechaCompleta"] = ''; }
			if (strtotime($json["InicioPuestaMarchaFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["InicioPuestaMarchaFechaCompleta"] = ''; }
			if (strtotime($json["TerminoPuestaMarchaFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["TerminoPuestaMarchaFechaCompleta"] = ''; }
			if (strtotime($json["TerminoReprocesoFechaCompleta"]) < strtotime('2000-01-01 12:00:00 AM')) { $json["TerminoReprocesoFechaCompleta"] = ''; }
			
			$query = "INSERT INTO intervencion_fechas (folio,planificada,llamado,llegada,inicio_intervencion,termino_intervencion,fecha_inicio_global,fecha_termino_global,inicio_prueba_potencia,termino_prueba_potencia,inicio_desconexion,termino_desconexion,inicio_conexion,termino_conexion,inicio_puesta_marcha,termino_puesta_marcha,termino_reproceso) "
			."VALUES ('{$row["folio"]}','{$json["FechaPlanificada"]}','{$json["LlamadoFechaCompleta"]}','{$json["LlegadaFechaCompleta"]}','{$json["InicioIntervencionFechaCompleta"]}','{$json["TerminoIntervencionFechaCompleta"]}','{$json["FechaInicioGlobal"]}','{$json["FechaTerminoGlobal"]}','{$json["InicioPruebaPotenciaFechaCompleta"]}','{$json["TerminoPruebaPotenciaFechaCompleta"]}','{$json["InicioDesconexionFechaCompleta"]}','{$json["TerminoDesconexionFechaCompleta"]}','{$json["InicioConexionFechaCompleta"]}','{$json["TerminoConexionFechaCompleta"]}','{$json["InicioPuestaMarchaFechaCompleta"]}','{$json["TerminoPuestaMarchaFechaCompleta"]}','{$json["TerminoReprocesoFechaCompleta"]}');";
			
			$query = str_replace(",''",",NULL",$query);
			$query = str_replace(",'1969-12-31 09:00:00 PM'",",NULL",$query);
			// Fix fechas QA y DEV en servidores KCL
			$query = str_replace(",'1970-01-01 12:00:00 AM'",",NULL",$query);
			fwrite($file, $query."\n");
			$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
			if (!$result) {
				$registroExitoso = false;
			}
			
			//echo "\n\nRegistro de decisiones\n\n";
			$query = "INSERT INTO intervencion_decisiones (folio,cambio_modulo,intervencion_terminada,prueba_potencia_realizada,prueba_potencia_exitosa,siguiente_actividad,reproceso_potencia,reproceso_modulo,reproceso_evento,desconexion_realizada,desconexion_terminada,conexion_realizada,conexion_terminada,puesta_marcha_realizada,trabajo_finalizado,mantencion_terminada) VALUES ('{$row["folio"]}','{$json["CambioModulo"]}','{$json["IntervencionTerminada"]}','{$json["PruebaPotenciaRealizada"]}','{$json["PruebaPotenciaExitosa"]}','{$json["SiguienteActividad"]}','{$json["ResultadoPotencia"]}','{$json["CambioModuloReproceso"]}','{$json["EstadoEvento"]}','{$json["DesconexionRealizada"]}','{$json["DesconexionTerminada"]}','{$json["ConexionRealizada"]}','{$json["ConexionTerminada"]}','{$json["PuestaMarchaRealizada"]}','{$json["TrabajoFinalizado"]}','{$json["MantencionTerminada"]}');";
			
			$query = str_replace(",''",",NULL",$query);
			fwrite($file, $query."\n");
			$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
			if (!$result) {
				$registroExitoso = false;
			}
			$json["Comentarios"] = pg_escape_string($json["Comentarios"]);
			$json["CodigoKCH"] = pg_escape_string($json["CodigoKCH"]);
			//echo "\n\nRegistro de comentarios\n\n";
			$query = "INSERT INTO intervencion_comentarios (folio,comentario,codigo_kch) "
			."VALUES ('{$row["folio"]}','{$json["Comentarios"]}','{$json["CodigoKCH"]}');";
			$query = str_replace(",''",",NULL",$query);
			fwrite($file, $query."\n");
			$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
			if (!$result) {
				$registroExitoso = false;
			}
			
			// Registro de Elementos
			if(isset($json["CantidadElementos"])&&intval($json["CantidadElementos"])>0){
				//echo "\n\nRegistro de {$json["CantidadElementos"]} elementos\n\n";
				//echo $json["CantidadElementos"];
				$cantidad = intval($json["CantidadElementos"]);
				for($i = 1;$i <= $cantidad; $i++){
					if(isset($json["Elemento_".$i])){
						$tiempo = intval($json["Elemento_Hora_".$i]) * 60 + intval($json["Elemento_Minuto_".$i]);
						$datos = explode(",",$json["Elemento_".$i]);
						$query = "INSERT INTO intervencion_elementos (folio,sistema_id,subsistema_id,subsistema_posicion_id,elemento_id,id_elemento,elemento_posicion_id,diagnostico_id,solucion_id,tipo_id,pn_saliente,pn_entrante,tiempo,tipo_registro,faena_id,flota_id,equipo_id) "
						."VALUES ('{$row["folio"]}','{$datos[0]}','{$datos[1]}','{$datos[2]}','{$datos[3]}','{$datos[8]}','{$datos[4]}','{$datos[6]}','{$datos[7]}','{$datos[9]}','{$datos[10]}','{$datos[11]}','$tiempo','0','{$row["faena_id"]}','{$row["flota_id"]}','{$row["unidad_id"]}');";
						$query = str_replace(",''",",NULL",$query);
						fwrite($file, $query."\n");
						$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
						if (!$result) {
							$registroExitoso = false;
						}
					}
				}
			}
			
			// Registro de Elementos Reproceso
			if(isset($json["CantidadElementosReproceso"])&&intval($json["CantidadElementosReproceso"])>0){
				//echo "\n\nRegsitro de {$json["CantidadElementosReproceso"]} elementos de reproceso\n\n";
				//echo $json["CantidadElementos"];
				$cantidad = intval($json["CantidadElementosReproceso"]);
				for($i = 1;$i <= $cantidad; $i++){
					if(isset($json["Elemento_Reproceso_".$i])){
						$tiempo = intval($json["Elemento_Reproceso_Hora_".$i]) * 60 + intval($json["Elemento_Reproceso_Minuto_".$i]);
						$datos = explode(",",$json["Elemento_".$i]);
						$query = "INSERT INTO intervencion_elementos (folio,sistema_id,subsistema_id,subsistema_posicion_id,elemento_id,id_elemento,elemento_posicion_id,diagnostico_id,solucion_id,tipo_id,pn_saliente,pn_entrante,tiempo,tipo_registro,faena_id,flota_id,equipo_id) "
						."VALUES ('{$row["folio"]}','{$datos[0]}','{$datos[1]}','{$datos[2]}','{$datos[3]}','{$datos[8]}','{$datos[4]}','{$datos[6]}','{$datos[7]}','{$datos[9]}','{$datos[10]}','{$datos[11]}','$tiempo','1','{$row["faena_id"]}','{$row["flota_id"]}','{$row["unidad_id"]}');";
						$query = str_replace(",''",",NULL",$query);
						fwrite($file, $query."\n");
						$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
						if (!$result) {
							$registroExitoso = false;
						}
					}
				}
			}
			
			// Registro de Fluidos, en caso de ser formato antiguo
			if(isset($json["AceiteMotor"]) && is_numeric($json["AceiteMotor"]) && intval($json["AceiteMotor"]) > 0) {
				try {
					$tipo_ingreso_id = $json["AceiteMotorTipo"] == "C" ? 1 : 2;
					$query = "INSERT INTO intervenciones_fluidos (intervencion_id,fluido_id,tipo_ingreso_id,cantidad) VALUES ";
					$query .= "($planificacion_id,1,$tipo_ingreso_id,{$json["AceiteMotor"]})";
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				} catch(Exception $e){
				}
			}
			
			if(isset($json["AceiteReserva"]) && is_numeric($json["AceiteReserva"]) && intval($json["AceiteReserva"]) > 0) {
				try {
					$tipo_ingreso_id = $json["AceiteReservaTipo"] == "C" ? 1 : 2;
					$query = "INSERT INTO intervenciones_fluidos (intervencion_id,fluido_id,tipo_ingreso_id,cantidad) VALUES ";
					$query .= "($planificacion_id,2,$tipo_ingreso_id,{$json["AceiteReserva"]})";
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				} catch(Exception $e){
				}
			}
			
			if(isset($json["Combustible"]) && is_numeric($json["Combustible"]) && intval($json["Combustible"]) > 0) {	
				try {		
					$tipo_ingreso_id = $json["CombustibleTipo"] == "C" ? 1 : 2;
					$query = "INSERT INTO intervenciones_fluidos (intervencion_id,fluido_id,tipo_ingreso_id,cantidad) VALUES ";
					$query .= "($planificacion_id,4,$tipo_ingreso_id,{$json["Combustible"]})";
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				} catch(Exception $e){
				}
			} 
			
			if(isset($json["Refrigerante"]) && is_numeric($json["Refrigerante"]) && intval($json["Refrigerante"]) > 0) {
				try {
					$tipo_ingreso_id = $json["RefrigeranteTipo"] == "C" ? 1 : 2;
					$query = "INSERT INTO intervenciones_fluidos (intervencion_id,fluido_id,tipo_ingreso_id,cantidad) VALUES ";
					$query .= "($planificacion_id,3,$tipo_ingreso_id,{$json["Refrigerante"]})";
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				} catch(Exception $e){
				}
			}
	
			if(isset($json["Zerex"]) && is_numeric($json["Zerex"]) && intval($json["Zerex"]) > 0) {		
				try {	
					$query = "INSERT INTO intervenciones_fluidos (intervencion_id,fluido_id,cantidad) VALUES ";
					$query .= "($planificacion_id,5,{$json["Zerex"]})";
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				} catch(Exception $e){
				}
			}
			
			if(isset($json["Resurs"]) && is_numeric($json["Resurs"]) && intval($json["Resurs"]) > 0) {
				try {
					$query = "INSERT INTO intervenciones_fluidos (intervencion_id,fluido_id,cantidad) VALUES ";
					$query .= "($planificacion_id,6,{$json["Resurs"]})";
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				} catch(Exception $e){
				}
			}
			
			$query2 = "SELECT id, nombre FROM matriz_fluidos";
			$result2 = pg_query($query2) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");				
			while ($row2 = pg_fetch_assoc($result2)){
				if(isset($json["Fluido_".$row2["id"]]) && is_numeric($json["Fluido_".$row2["id"]]) && intval($json["Fluido_".$row2["id"]]) > 0) {
					try {
						if(!isset($json["Fluido_Tipo_".$row2["id"]]) || @$json["Fluido_Tipo_".$row2["id"]] == ''){
							$json["Fluido_Tipo_".$row2["id"]] = "NULL";
						}
						$query = "INSERT INTO intervencion_fluidos (intervencion_id,fluido_id,cantidad,tipo_ingreso_id) VALUES ";
						$query .= "($planificacion_id,{$row2["id"]},".$json["Fluido_".$row2["id"]].",".$json["Fluido_Tipo_".$row2["id"]].")";
						$query = str_replace(",''",",NULL",$query);
						fwrite($file, $query."\n");
						$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					} catch(Exception $e){
					}
				}
			}
			
			// Registro de Backlogs
			if(isset($json["CantidadBacklogs"])&&intval($json["CantidadBacklogs"])>0){
				//echo "\n\nRegistro de {$json["CantidadBacklogs"]} Backlogs\n\n";
				$cantidad = intval($json["CantidadBacklogs"]);
				for($i = 1;$i <= $cantidad; $i++){
					if(isset($json["Backlog_".$i])){
						$datos = explode(",",$json["Backlog_".$i]);
						$query = "INSERT INTO intervencion_elementos (folio,sistema_id,subsistema_id,subsistema_posicion_id,elemento_id,id_elemento,elemento_posicion_id,diagnostico_id,pn_saliente,tipo_registro,faena_id,flota_id,equipo_id) "
						."VALUES ('{$row["folio"]}','{$datos[2]}','{$datos[3]}','{$datos[4]}','{$datos[10]}','{$datos[5]}','{$datos[6]}','{$datos[7]}','{$datos[8]}','2','{$row["faena_id"]}','{$row["flota_id"]}','{$row["unidad_id"]}') returning id;";
						$query = str_replace(",''",",NULL",$query);
						fwrite($file, $query."\n");
						$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
						if (!$result) {
							$registroExitoso = false;
						}
						
						if(!isset($datos[11]) || !is_numeric($datos[11])){
							$datos[11] = "0";
						}
						
						$arr = pg_fetch_assoc($result) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
						$elemento_id = $arr["id"];
						$datos[9] = pg_escape_string($datos[9]);
						$query = "INSERT INTO backlog (folio,criticidad_id,responsable_id,comentario,elemento_id,equipo_id,sistema_id,subsistema_id,fecha_creacion,faena_id,flota_id,intervencion_id,creacion_id,usuario_id, tiempo_estimado) "
						."VALUES ('{$row["folio"]}','{$datos[0]}','{$datos[1]}','{$datos[9]}','$elemento_id','{$row["unidad_id"]}','{$datos[2]}','{$datos[3]}','".date("Y-m-d H:i:s")."','{$row["faena_id"]}','{$row["flota_id"]}',NULL, '3','{$json["UserID"]}','{$datos[11]}');";
						$query = str_replace(",''",",NULL",$query);
						fwrite($file, $query."\n");
						$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
						if (!$result) {
							$registroExitoso = false;
						}
					}
				}
			}
			
			// Registro de Delta 1
			if(isset($json["LlamadoFechaCompleta"]) && isset($json["LlegadaFechaCompleta"]) && $json["LlamadoFechaCompleta"] != '' && $json["LlegadaFechaCompleta"] != ''){
				//echo "\n\nRegistro de Delta 1\n\n";
				if(isset($json["Delta1TrasladoDCCResponsable"]) && $json["Delta1TrasladoDCCResponsable"] != '') {
					$tiempo = intval($json["Delta1TrasladoDCCHora"]) * 60 + intval($json["Delta1TrasladoDCCMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta1TrasladoDCCResponsable"]}','1','$tiempo','{$json["Delta1TrasladoDCCObservacion"]}');";
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta1TrasladoOEMResponsable"]) && $json["Delta1TrasladoOEMResponsable"] != '') {
					$tiempo = intval($json["Delta1TrasladoOEMHora"]) * 60 + intval($json["Delta1TrasladoOEMMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta1TrasladoOEMResponsable"]}','2','$tiempo','{$json["Delta1TrasladoOEMObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta1TronaduraResponsable"]) && $json["Delta1TronaduraResponsable"] != '') {
					$tiempo = intval($json["Delta1TronaduraHora"]) * 60 + intval($json["Delta1TronaduraMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta1TronaduraResponsable"]}','3','$tiempo','{$json["Delta1TronaduraObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta1ClimaResponsable"]) && $json["Delta1ClimaResponsable"] != '') {
					$tiempo = intval($json["Delta1ClimaHora"]) * 60 + intval($json["Delta1ClimaMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta1ClimaResponsable"]}','4','$tiempo','{$json["Delta1ClimaObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta1LogisticaDCCResponsable"]) && $json["Delta1LogisticaDCCResponsable"] != '') {
					$tiempo = intval($json["Delta1LogisticaDCCHora"]) * 60 + intval($json["Delta1LogisticaDCCMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta1LogisticaDCCResponsable"]}','5','$tiempo','{$json["Delta1LogisticaDCCObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta1LogisticaOEMResponsable"]) && $json["Delta1LogisticaOEMResponsable"] != '') {
					$tiempo = intval($json["Delta1LogisticaOEMHora"]) * 60 + intval($json["Delta1LogisticaOEMMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta1LogisticaOEMResponsable"]}','6','$tiempo','{$json["Delta1LogisticaOEMObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta1PersonalResponsable"]) && $json["Delta1PersonalResponsable"] != '') {
					$tiempo = intval($json["Delta1PersonalHora"]) * 60 + intval($json["Delta1PersonalMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta1PersonalResponsable"]}','7','$tiempo','{$json["Delta1PersonalObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
			}
			
			// Registro Delta 2
			if(isset($json["InicioIntervencionFechaCompleta"]) && isset($json["LlegadaFechaCompleta"]) && $json["InicioIntervencionFechaCompleta"] != '' && $json["LlegadaFechaCompleta"] != '') {
				//echo "\n\nRegistro de Delta 2\n\n";
				if(isset($json["Delta2TronaduraResponsable"]) && $json["Delta2TronaduraResponsable"] != '') {
					$tiempo = intval($json["Delta2TronaduraHora"]) * 60 + intval($json["Delta2TronaduraMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta2TronaduraResponsable"]}','13','$tiempo','{$json["Delta2TronaduraObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta2ClimaResponsable"]) && $json["Delta2ClimaResponsable"] != '') {
					$tiempo = intval($json["Delta2ClimaHora"]) * 60 + intval($json["Delta2ClimaMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta2ClimaResponsable"]}','11','$tiempo','{$json["Delta2ClimaObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta2LogisticaDCCResponsable"]) && $json["Delta2LogisticaDCCResponsable"] != '') {
					$tiempo = intval($json["Delta2LogisticaDCCHora"]) * 60 + intval($json["Delta2LogisticaDCCMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta2LogisticaDCCResponsable"]}','27','$tiempo','{$json["Delta2LogisticaDCCObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta2LogisticaOEMResponsable"]) && $json["Delta2LogisticaOEMResponsable"] != '') {
					$tiempo = intval($json["Delta2LogisticaOEMHora"]) * 60 + intval($json["Delta2LogisticaOEMMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta2LogisticaOEMResponsable"]}','48','$tiempo','{$json["Delta2LogisticaOEMObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta2ClienteResponsable"]) && $json["Delta2ClienteResponsable"] != '') {
					$tiempo = intval($json["Delta2ClienteHora"]) * 60 + intval($json["Delta2ClienteMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta2ClienteResponsable"]}','8','$tiempo','{$json["Delta2ClienteObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta2OEMResponsable"]) && $json["Delta2OEMResponsable"] != '') {
					$tiempo = intval($json["Delta2OEMHora"]) * 60 + intval($json["Delta2OEMMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta2OEMResponsable"]}','9','$tiempo','{$json["Delta2OEMObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta2ZonaSeguraResponsable"]) && $json["Delta2ZonaSeguraResponsable"] != '') {
					$tiempo = intval($json["Delta2ZonaSeguraHora"]) * 60 + intval($json["Delta2ZonaSeguraMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta2ZonaSeguraResponsable"]}','10','$tiempo','{$json["Delta2ZonaSeguraObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta2CharlaResponsable"]) && $json["Delta2CharlaResponsable"] != '') {
					$tiempo = intval($json["Delta2CharlaHora"]) * 60 + intval($json["Delta2CharlaMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta2CharlaResponsable"]}','12','$tiempo','{$json["Delta2CharlaObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
			}
			
			// Registro Delta 3
			if(isset($json["InicioIntervencionFechaCompleta"]) && isset($json["TerminoIntervencionFechaCompleta"]) && $json["InicioIntervencionFechaCompleta"] != '' && $json["TerminoIntervencionFechaCompleta"] != '') {
				echo "\n\nRegistro de Delta 3\n\n";
				if(isset($json["Delta3ClienteResponsable"]) && $json["Delta3ClienteResponsable"] != '') {
					$tiempo = intval($json["Delta3ClienteHora"]) * 60 + intval($json["Delta3ClienteMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta3ClienteResponsable"]}','21','$tiempo','{$json["Delta3ClienteObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta3OEMResponsable"]) && $json["Delta3OEMResponsable"] != '') {
					$tiempo = intval($json["Delta3OEMHora"]) * 60 + intval($json["Delta3OEMMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta3OEMResponsable"]}','20','$tiempo','{$json["Delta3OEMObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta3PersonalDCCResponsable"]) && $json["Delta3PersonalDCCResponsable"] != '') {
					$tiempo = intval($json["Delta3PersonalDCCHora"]) * 60 + intval($json["Delta3PersonalDCCMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta3PersonalDCCResponsable"]}','14','$tiempo','{$json["Delta3PersonalDCCObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta3FluidosResponsable"]) && $json["Delta3FluidosResponsable"] != '') {
					$tiempo = intval($json["Delta3FluidosHora"]) * 60 + intval($json["Delta3FluidosMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta3FluidosResponsable"]}','15','$tiempo','{$json["Delta3FluidosObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta3ReparacionDiagnosticoResponsable"]) && $json["Delta3ReparacionDiagnosticoResponsable"] != '') {
					$tiempo = intval($json["Delta3ReparacionDiagnosticoHora"]) * 60 + intval($json["Delta3ReparacionDiagnosticoMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta3ReparacionDiagnosticoResponsable"]}','16','$tiempo','{$json["Delta3ReparacionDiagnosticoObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta3RepuestosResponsable"]) && $json["Delta3RepuestosResponsable"] != '') {
					$tiempo = intval($json["Delta3RepuestosHora"]) * 60 + intval($json["Delta3RepuestosMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta3RepuestosResponsable"]}','17','$tiempo','{$json["Delta3RepuestosObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta3HerramientasDCCResponsable"]) && $json["Delta3HerramientasDCCResponsable"] != '') {
					$tiempo = intval($json["Delta3HerramientasDCCHora"]) * 60 + intval($json["Delta3HerramientasDCCMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta3HerramientasDCCResponsable"]}','18','$tiempo','{$json["Delta3HerramientasDCCObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta3HerramientasPanolResponsable"]) && $json["Delta3HerramientasPanolResponsable"] != '') {
					$tiempo = intval($json["Delta3HerramientasPanolHora"]) * 60 + intval($json["Delta3HerramientasPanolMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta3HerramientasPanolResponsable"]}','19','$tiempo','{$json["Delta3HerramientasPanolObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
			}
			
			// Registro Delta 4
			if(isset($json["InicioPruebaPotenciaFechaCompleta"]) && isset($json["TerminoIntervencionFechaCompleta"]) && $json["InicioPruebaPotenciaFechaCompleta"] != '' && $json["TerminoIntervencionFechaCompleta"] != '') {
				echo "\n\nRegistro de Delta 4\n\n";
				if(isset($json["Delta4TronaduraResponsable"]) && $json["Delta4TronaduraResponsable"] != '') {
					$tiempo = intval($json["Delta4TronaduraHora"]) * 60 + intval($json["Delta4TronaduraMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta4TronaduraResponsable"]}','25','$tiempo','{$json["Delta4TronaduraObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta4LogisticaDCCResponsable"]) && $json["Delta4LogisticaDCCResponsable"] != '') {
					$tiempo = intval($json["Delta4LogisticaDCCHora"]) * 60 + intval($json["Delta4LogisticaDCCMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta4LogisticaDCCResponsable"]}','28','$tiempo','{$json["Delta4LogisticaDCCObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta4ClienteResponsable"]) && $json["Delta4ClienteResponsable"] != '') {
					$tiempo = intval($json["Delta4ClienteHora"]) * 60 + intval($json["Delta4ClienteMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta4ClienteResponsable"]}','29','$tiempo','{$json["Delta4ClienteObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta4ClimaResponsable"]) && $json["Delta4ClimaResponsable"] != '') {
					$tiempo = intval($json["Delta4ClimaHora"]) * 60 + intval($json["Delta4ClimaMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta4ClimaResponsable"]}','49','$tiempo','{$json["Delta4ClimaObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta4OEMResponsable"]) && $json["Delta4OEMResponsable"] != '') {
					$tiempo = intval($json["Delta4OEMHora"]) * 60 + intval($json["Delta4OEMMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta4OEMResponsable"]}','24','$tiempo','{$json["Delta4OEMObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta4OperadorResponsable"]) && $json["Delta4OperadorResponsable"] != '') {
					$tiempo = intval($json["Delta4OperadorHora"]) * 60 + intval($json["Delta4OperadorMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta4OperadorResponsable"]}','23','$tiempo','{$json["Delta4OperadorObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta4InfraestructuraResponsable"]) && $json["Delta4InfraestructuraResponsable"] != '') {
					$tiempo = intval($json["Delta4InfraestructuraHora"]) * 60 + intval($json["Delta4InfraestructuraMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta4InfraestructuraResponsable"]}','26','$tiempo','{$json["Delta4InfraestructuraObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
			}
			
			// Registro Delta 5, Reproceso
			if(isset($json["TerminoPruebaPotenciaFechaCompleta"]) && isset($json["TerminoReprocesoFechaCompleta"]) && $json["TerminoPruebaPotenciaFechaCompleta"] != '' && $json["TerminoReprocesoFechaCompleta"] != '') {
				echo "\n\nRegistro de Delta 5, Reproceso\n\n";
				if(isset($json["Delta5ClienteResponsable"]) && $json["Delta5ClienteResponsable"] != '') {
					$tiempo = intval($json["Delta5ClienteHora"]) * 60 + intval($json["Delta5ClienteMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta5ClienteResponsable"]}','37','$tiempo','{$json["Delta5ClienteObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta5OEMResponsable"]) && $json["Delta5OEMResponsable"] != '') {
					$tiempo = intval($json["Delta5OEMHora"]) * 60 + intval($json["Delta5OEMMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta5OEMResponsable"]}','36','$tiempo','{$json["Delta5OEMObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta5PersonalDCCResponsable"]) && $json["Delta5PersonalDCCResponsable"] != '') {
					$tiempo = intval($json["Delta5PersonalDCCHora"]) * 60 + intval($json["Delta5PersonalDCCMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta5PersonalDCCResponsable"]}','30','$tiempo','{$json["Delta5PersonalDCCObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta5FluidosResponsable"]) && $json["Delta5FluidosResponsable"] != '') {
					$tiempo = intval($json["Delta5FluidosHora"]) * 60 + intval($json["Delta5FluidosMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta5FluidosResponsable"]}','31','$tiempo','{$json["Delta5FluidosObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta5ReparacionDiagnosticoResponsable"]) && $json["Delta5ReparacionDiagnosticoResponsable"] != '') {
					$tiempo = intval($json["Delta5ReparacionDiagnosticoHora"]) * 60 + intval($json["Delta5ReparacionDiagnosticoMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta5ReparacionDiagnosticoResponsable"]}','32','$tiempo','{$json["Delta5ReparacionDiagnosticoObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta5RepuestosResponsable"]) && $json["Delta5RepuestosResponsable"] != '') {
					$tiempo = intval($json["Delta5RepuestosHora"]) * 60 + intval($json["Delta5RepuestosMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta5RepuestosResponsable"]}','33','$tiempo','{$json["Delta5RepuestosObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta5HerramientasDCCResponsable"]) && $json["Delta5HerramientasDCCResponsable"] != '') {
					$tiempo = intval($json["Delta5HerramientasDCCHora"]) * 60 + intval($json["Delta5HerramientasDCCMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta5HerramientasDCCResponsable"]}','34','$tiempo','{$json["Delta5HerramientasDCCObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
				if(isset($json["Delta5HerramientasPanolResponsable"]) && $json["Delta5HerramientasPanolResponsable"] != '') {
					$tiempo = intval($json["Delta5HerramientasPanolHora"]) * 60 + intval($json["Delta5HerramientasPanolMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta5HerramientasPanolResponsable"]}','35','$tiempo','{$json["Delta5HerramientasPanolObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
					if (!$result) {
						$registroExitoso = false;
					}
				}
			}
			
			// Registro Delta 6 O Delta Mantencion
			if(isset($json["InicioIntervencionFechaCompleta"]) && isset($json["TerminoIntervencionFechaCompleta"]) && $json["InicioIntervencionFechaCompleta"] != '' && $json["TerminoIntervencionFechaCompleta"] != '' && $row["tipo_intervencion"] == 'MP') {
				echo "\n\nRegistro de Delta 6, Mantencion\n\n";
				if(isset($json["Delta6PersonalDCCResponsable"]) && $json["Delta6PersonalDCCResponsable"] != '') {
					$tiempo = intval($json["Delta6PersonalDCCHora"]) * 60 + intval($json["Delta6PersonalDCCMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta6PersonalDCCResponsable"]}','38','$tiempo','{$json["Delta6PersonalDCCObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result_query[] = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				}
				if(isset($json["Delta6ClienteResponsable"]) && $json["Delta6ClienteResponsable"] != '') {
					$tiempo = intval($json["Delta6ClienteHora"]) * 60 + intval($json["Delta6ClienteMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta6ClienteResponsable"]}','39','$tiempo','{$json["Delta6ClienteObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result_query[] = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				}
				if(isset($json["Delta6OEMResponsable"]) && $json["Delta6OEMResponsable"] != '') {
					$tiempo = intval($json["Delta6OEMHora"]) * 60 + intval($json["Delta6OEMMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta6OEMResponsable"]}','40','$tiempo','{$json["Delta6OEMObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result_query[] = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				}
				if(isset($json["Delta6CharlaResponsable"]) && $json["Delta6CharlaResponsable"] != '') {
					$tiempo = intval($json["Delta6CharlaHora"]) * 60 + intval($json["Delta6CharlaMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta6CharlaResponsable"]}','41','$tiempo','{$json["Delta6CharlaObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result_query[] = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				}
				if(isset($json["Delta6FluidosResponsable"]) && $json["Delta6FluidosResponsable"] != '') {
					$tiempo = intval($json["Delta6FluidosHora"]) * 60 + intval($json["Delta6FluidosMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta6FluidosResponsable"]}','42','$tiempo','{$json["Delta6FluidosObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result_query[] = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				}
				if(isset($json["Delta6MantencionResponsable"]) && $json["Delta6MantencionResponsable"] != '') {
					$tiempo = intval($json["Delta6MantencionHora"]) * 60 + intval($json["Delta6MantencionMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta6MantencionResponsable"]}','43','$tiempo','{$json["Delta6MantencionObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result_query[] = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				}
				if(isset($json["Delta6RepuestosResponsable"]) && $json["Delta6RepuestosResponsable"] != '') {
					$tiempo = intval($json["Delta6RepuestosHora"]) * 60 + intval($json["Delta6RepuestosMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta6RepuestosResponsable"]}','44','$tiempo','{$json["Delta6RepuestosObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result_query[] = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				}
				if(isset($json["Delta6InfraestructuraResponsable"]) && $json["Delta6InfraestructuraResponsable"] != '') {
					$tiempo = intval($json["Delta6InfraestructuraHora"]) * 60 + intval($json["Delta6InfraestructuraMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta6InfraestructuraResponsable"]}','45','$tiempo','{$json["Delta6InfraestructuraObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result_query[] = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				}
				if(isset($json["Delta6OperadorResponsable"]) && $json["Delta6OperadorResponsable"] != '') {
					$tiempo = intval($json["Delta6OperadorHora"]) * 60 + intval($json["Delta6OperadorMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta6OperadorResponsable"]}','46','$tiempo','{$json["Delta6OperadorObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result_query[] = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				}
				if(isset($json["Delta6HerramientasPanolResponsable"]) && $json["Delta6HerramientasPanolResponsable"] != '') {
					$tiempo = intval($json["Delta6HerramientasPanolHora"]) * 60 + intval($json["Delta6HerramientasPanolMinuto"]);
					$query = "INSERT INTO delta_detalle (folio,delta_responsable_id,delta_item_id,tiempo,observacion) "
					."VALUES ('{$row["folio"]}','{$json["Delta6HerramientasPanolResponsable"]}','47','$tiempo','{$json["Delta6HerramientasPanolObservacion"]}');";
					
					$query = str_replace(",''",",NULL",$query);
					fwrite($file, $query."\n");
					$result_query[] = pg_query($query) or fwrite($file, 'La consulta fallo: ' . pg_last_error()."\n");
				}
			}
			
			$query = "UPDATE aws_sqs_message SET procesado = '1' WHERE id = {$row["id"]};";
			pg_query($query);
		}
	} catch (Exception $e) {
		fwrite($file, date("Y-m-d H:i:s") . ": Ocurrió una excepción ".($e->getMessage()).".\n");
	}
	pg_close($dbconn);
	fwrite($file, date("Y-m-d H:i:s") . ": Término de registro de mensajes SQS.\n");
	fclose($file);
?>
