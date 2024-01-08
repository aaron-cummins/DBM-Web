<?php
	$pdf->SetFont('Times','',11);
	$pdf->Cell(50,12,utf8_decode('Faena:'),0,0,'L');
	$pdf->SetFont('Times','B',11);
	$pdf->Cell(0,12,strtoupper(utf8_decode(@$intervencion["Faena"]["nombre"])),0,0,'L');
	$pdf->SetFont('Times','',11);
	$pdf->Ln($salto_simple);
	$pdf->Cell(50,12,utf8_decode('Flota:'),0,0,'L');
	$pdf->SetFont('Times','B',11);
	$pdf->Cell(0,12,strtoupper(utf8_decode(@$intervencion["Flota"]["nombre"])),0,0,'L');
	$pdf->SetFont('Times','',11);
	$pdf->Ln($salto_simple);
	$pdf->Cell(50,12,utf8_decode('Equipo:'),0,0,'L');
	$pdf->SetFont('Times','B',11);
	$pdf->Cell(0,12,strtoupper(utf8_decode(@$intervencion["Unidad"]["unidad"])),0,0,'L');
	$pdf->SetFont('Times','',11);
	$pdf->Ln($salto_simple);
	$pdf->Cell(50,12,utf8_decode('Tipo Intervención:'),0,0,'L');
	$pdf->SetFont('Times','B',11);
	$pdf->Cell(0,12,@$intervencion["Planificacion"]["tipointervencion"],0,0,'L');
	$pdf->SetFont('Times','',11);
	$pdf->Ln($salto_simple);
	$pdf->Cell(50,12,utf8_decode('Correlativo:'),0,0,'L');
	$pdf->SetFont('Times','B',11);
	$pdf->Cell(0,12,@$intervencion["Planificacion"]["correlativo"],0,0,'L');
	$pdf->SetFont('Times','',11);
	$pdf->Ln($salto_simple);
	$pdf->Cell(50,12,utf8_decode('Folio:'),0,0,'L');
	$pdf->SetFont('Times','B',11);
	$pdf->Cell(0,12,@$intervencion["Planificacion"]["id"],0,0,'L');
	$pdf->SetFont('Times','',11);
	$pdf->Ln($salto_simple);
	$pdf->Cell(50,12,utf8_decode('Estado Intervención:'),0,0,'L');
	$pdf->SetFont('Times','B',11);
	$pdf->Cell(0,12,utf8_decode(strtoupper(@$intervencion["Estado"]["nombre"])),0,0,'L');
	$pdf->SetFont('Times','',11);
	$pdf->Ln($salto_simple);
	if($intervencion["Planificacion"]['estado']==2){
		$pdf->Image("http://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=http://cummins.sisrai.cl/qr/ck/".sha1(md5($intervencion["Planificacion"]["id"]))."/".sha1($intervencion["Planificacion"]["id"])."&.png",$pdf->GetX()+148.5,$pdf->GetY()+130,40,40);
		$pdf->SetCreator("DCC SIS-RAI",true);
		$pdf->SetSubject("Folio " . (@$row["id"]),true);
		$pdf->Output();
		exit;
	}
	$pdf->Cell(0,6,utf8_decode('_______________________________________________________________________________________________'),0,0,'C');
	$pdf->Ln(4);
	$pdf->Cell(50,12,utf8_decode('Técnico Principal:'),0,0,'L');
	$pdf->SetFont('Times','B',11);
	$pdf->Cell(0,12,utf8_decode(@$json["tecnico_principal"]),0,0,'L');
	$pdf->SetFont('Times','',11);
	$pdf->Ln($salto_simple);
	for($i=2;$i<7;$i++){
		if(is_numeric(@$json["tecnico_$i"])){
			$pdf->Cell(50,12,utf8_decode('Técnico N°$i:'),0,0,'L');
			$pdf->SetFont('Times','B',11);
			$pdf->Cell(0,12,utf8_decode(@$json["tecnico_$i"]),0,0,'L');
			$pdf->SetFont('Times','',11);
			$pdf->Ln($salto_simple);
		}
	}
	$pdf->Cell(0,5,utf8_decode('_______________________________________________________________________________________________'),0,0,'C');
	$pdf->Ln(6);
	$separador = false;
	if(@$json["supervisor_d"]!=''){
		$pdf->Cell(50,12,utf8_decode('Supervisor Responsable:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["supervisor_d"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$separador = true;
	}
	if(@$json["turno"]!=''){
		$pdf->Cell(50,12,utf8_decode('Turno:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["turno"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$separador = true;
	}
	if(@$json["periodo"]!=''){
		$pdf->Cell(50,12,utf8_decode('Período:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["periodo"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$separador = true;
	}
	if($separador){
		$pdf->Cell(0,5,utf8_decode('_______________________________________________________________________________________________'),0,0,'C');
		$pdf->Ln(6);
	}
	
	$seccion = false;
	if(isset($json["cambio_modulo"])&&$json["cambio_modulo"]!=""){
		$pdf->Cell(50,12,utf8_decode('Cambio de Módulo:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["cambio_modulo"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["intervencion_terminada"])&&$json["intervencion_terminada"]!=""){
		$pdf->Cell(50,12,utf8_decode('Intervención Terminada:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["intervencion_terminada"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["prueba_potencia_realizada"])&&$json["prueba_potencia_realizada"]!=""){
		$pdf->Cell(50,12,utf8_decode('Prueba de Potencia Realizada:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["prueba_potencia_realizada"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["prueba_potencia_exitosa"])&&$json["prueba_potencia_exitosa"]!=""){
		$pdf->Cell(50,12,utf8_decode('Prueba de Potencia Exitosa:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["prueba_potencia_exitosa"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["desconexion_realizada"])&&$json["desconexion_realizada"]!=""){
		$pdf->Cell(50,12,utf8_decode('Desconexión Realizada:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["desconexion_realizada"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["desconexion_exitosa"])&&$json["desconexion_exitosa"]!=""){
		$pdf->Cell(50,12,utf8_decode('Desconexión Exitosa:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["desconexion_exitosa"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["desconexion_terminada"])&&$json["desconexion_terminada"]!=""){
		$pdf->Cell(50,12,utf8_decode('Desconexión Terminada:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["desconexion_terminada"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["conexion_realizada"])&&$json["conexion_realizada"]!=""){
		$pdf->Cell(50,12,utf8_decode('Conexión Realizada:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["conexion_realizada"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["conexion_terminada"])&&$json["conexion_terminada"]!=""){
		$pdf->Cell(50,12,utf8_decode('Conexión Terminada:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["conexion_terminada"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["puesta_marcha_realizada"])&&$json["puesta_marcha_realizada"]!=""){
		$pdf->Cell(50,12,utf8_decode('Puesta en Marcha Realizada:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["puesta_marcha_realizada"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["trabajo_finalizado"])&&$json["trabajo_finalizado"]!=""){
		$pdf->Cell(50,12,utf8_decode('Trabajo Finalizado:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["trabajo_finalizado"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["resultado_pp"])&&$json["resultado_pp"]!=""){
		$pdf->Cell(50,12,utf8_decode('Resultado PP:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["resultado_pp"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["cambio_modulo_r"])&&$json["cambio_modulo_r"]!=""){
		$pdf->Cell(50,12,utf8_decode('Cambio Módulo Reproceso:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["cambio_modulo_r"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
		$seccion = true;
	}
	if(isset($json["estado_evento"])&&$json["estado_evento"]!=""){
		$pdf->Cell(50,12,utf8_decode('Estado Evento:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["estado_evento"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$seccion = true;
	}
	
	$pdf->Cell(50,12,utf8_decode('Detalle de Fechas'),0,0,'L');
	$pdf->Ln($salto_simple);
	if(@$json["llamado_fecha"]!=""){
		$pdf->Cell(50,12,utf8_decode('Llamado:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["llamado_fecha"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["llegada_fecha"]!=""){
		$pdf->Cell(50,12,utf8_decode('Llegada:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["llegada_fecha"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["i_i_f"]!=""){
		$pdf->Cell(50,12,utf8_decode('Inicio Intervención:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["i_i_f"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["i_t_f"]!=""){
		$pdf->Cell(50,12,utf8_decode('Término Intervención:'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["i_t_f"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["desc_f"]!=""){
		$pdf->Cell(50,12,utf8_decode('Inicio Desconexión'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["desc_f"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["desct_f"]!=""){
		$pdf->Cell(50,12,utf8_decode('Término Desconexión'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["desct_f"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["con_f"]!=""){
		$pdf->Cell(50,12,utf8_decode('Inicio Conexión'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["con_f"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["cont_f"]!=""){
		$pdf->Cell(50,12,utf8_decode('Término Conexión'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["cont_f"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["pm_i_f"]!=""){
		$pdf->Cell(50,12,utf8_decode('Inicio Puesta en Marcha'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["pm_i_f"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["pm_t_f"]!=""){
		$pdf->Cell(50,12,utf8_decode('Término Puesta en Marcha'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["pm_t_f"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["pp_i_f"]){
		$pdf->Cell(50,12,utf8_decode('Inicio Prueba Potencia'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["pp_i_f"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["pp_t_f"]!=""){
		$pdf->Cell(50,12,utf8_decode('Término Prueba Potencia'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["pp_t_f"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	if(@$json["i_t_r_f"]!=""){
		$pdf->Cell(50,12,utf8_decode('Término Reproceso'),0,0,'L');
		$pdf->SetFont('Times','B',11);
		$pdf->Cell(0,12,utf8_decode(@$json["i_t_r_f"]),0,0,'L');
		$pdf->SetFont('Times','',11);
		$pdf->Ln($salto_simple);
	}
	$pdf->Cell(0,5,utf8_decode('_______________________________________________________________________________________________'),0,0,'C');
	$pdf->Ln(10);



	$pdf->SetCreator("DCC SIS-RAI",true);
	$pdf->SetSubject("Folio " . ($folio),true);
	$pdf->Output();
?>