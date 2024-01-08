<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$json = json_decode($intervencion["Planificacion"]["json"], true);
	$sintoma = "";
	
	/*if($intervencion['Planificacion']["tipointervencion"]=="MP"){
		$sintoma = "_".$intervencion['Planificacion']["tipomantencion"];
		if($intervencion['Planificacion']["tipomantencion"]=="1500"){
			$sintoma = "_OVERHAUL";
		}
	}*/
	
	if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'MP') {
		if (isset($json["tipo_programado"])) {
			if ($json["tipo_programado"] == "1500") {
				$sintoma = "Overhaul";
			} else {
				$sintoma = $json["tipo_programado"];
			}	
		} else {					
			if ($intervencion['Planificacion']['tipomantencion'] == "1500") {
				$sintoma = "Overhaul";
			} else {
				$sintoma = $intervencion['Planificacion']['tipomantencion'];
			}
		}
	} elseif (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL') {
		$sintoma = $util->getBacklogDescripcion($intervencion['Planificacion']['id']);
	} else {
		$sintoma = $util->getSintoma($intervencion['Planificacion']['sintoma_id']);
	}

	if($sintoma!=""){
		$sintoma = "_".str_replace("  "," ",$sintoma);
		//$sintoma = strtoupper($sintoma);
	}	
	
	if(!isset($json["fecha_inicio_g"])){
		$json["fecha_inicio_g"] = $intervencion['Planificacion']["fecha"].' '.$intervencion['Planificacion']["hora"];
	}
	//$json["fecha_inicio_g"] = str_replace(":","",$json["fecha_inicio_g"]);
	$json["fecha_inicio_g"] = str_replace("  "," ",$json["fecha_inicio_g"]);
	$json["fecha_inicio_g"] = strtotime($json["fecha_inicio_g"]);
	$json["fecha_inicio_g"] = date("Y-m-d h:i A",$json["fecha_inicio_g"]);
	//Abrev Faena_Unidad_Fecha Falla_Tipoevento_Sintoma
	$archivo = $intervencion["Faena"]["codigo"]."_".$intervencion['Unidad']["unidad"]."_".$json["fecha_inicio_g"]."_".$intervencion['Planificacion']["tipointervencion"]."$sintoma";
	$archivo = str_replace(" ","_",$archivo);
	$archivo = strtoupper($archivo);
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $archivo;?></title>
<script language="javascript" type="text/javascript">
   <!--
	function printThis() {
		window.print();
		setTimeout('window.close()', 10);
	}
	//-->
</script>
<style type="text/css" media="print">
    @page { 
        size: landscape;
		transform: rotate(90deg);
		-webkit-transform: rotate(-90deg); 
		 -moz-transform:rotate(-90deg);
		 filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }
</style>
</head>
<body onLoad="printThis();">
<?php
	//print_r($intervencion);
?>
<h2 align="center">Intervención  <?php 
if ($intervencion['Planificacion']['padre'] != NULL && $intervencion['Planificacion']['padre'] != '') {
	echo "c".strtoupper($intervencion['Planificacion']['tipointervencion']);
} else {
	echo strtoupper($intervencion['Planificacion']['tipointervencion']);
}

					  ?> Folio <?php echo $intervencion["Planificacion"]["id"];?></h2>
<h3 style="clear:both;">Información Base</h3>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Correlativo</div>
	<div style="float:left;"><?php echo $intervencion['Planificacion']['correlativo'];?>
	</div>
</div>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Folio</div>
	<div style="float:left;"><?php echo $intervencion['Planificacion']['id'];?>
	</div>
</div>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Tipo Intervención</div>
	<div style="float:left;"><?php 
		if ($intervencion['Planificacion']['padre'] != NULL && $intervencion['Planificacion']['padre'] != '') {
			echo "c".strtoupper($intervencion['Planificacion']['tipointervencion']);
		} else {
			echo strtoupper($intervencion['Planificacion']['tipointervencion']);
		}

	  ?>
		<?php if (isset($intervencion['Planificacion']['tipomantencion'])&&@$intervencion['Planificacion']['tipomantencion']!=''){?>
		<?php echo is_numeric($intervencion['Planificacion']['tipomantencion'])?$intervencion['Planificacion']['tipomantencion']:"OVERHAUL";?>
		<?php }?>
	</div>
</div>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Flota</div>
	<div style="float:left;"><?php echo strtoupper($intervencion['Flota']['nombre']);?>
	</div>
</div>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Equipo</div>
	<div style="float:left;"><?php echo strtoupper($intervencion['Unidad']['unidad']);?>
	</div>
</div>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">ESN</div>
	<div style="float:left;"><?php 
		$esn_ = $util->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$util->getMotor($intervencion['Planificacion']['unidad_id']),$util->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
		echo $esn_;
		$esn = "";
		if($esn_==''){
			if (@$json["esn_conexion"] != "") {
				$esn =  @$json["esn_conexion"];
			} elseif (@$json["esn_nuevo"] != "") {
				$esn = @$json["esn_nuevo"];
			} elseif (@$json["esn"] != "") {
				$esn =  @$json["esn"];
			} else {
				$esn =  $intervencion['Planificacion']['esn'];
			}
			
			if (is_numeric($esn)) {
				echo $esn;
			} else {
				echo $util->esnPadre($intervencion['Planificacion']['padre']);
			}
		}
	?></div>
</div>
<?php if (isset($json['motivo_llamado'])&&@$json['motivo_llamado']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Motivo Llamado</div>
	<div style="float:left;"><?php switch (strtoupper($json['motivo_llamado'])) {
		case 'FC':
			echo "CÓDIGO FALLA";
			break;
		case 'OP':
			echo "OPERADOR";
			break;
		case 'OT': 
			echo "OPORTUNIDAD";
			break;
	   } ?></div>
</div>
<?php }?>
<?php if (isset($json['sintoma'])&&@$json['sintoma']!=''&&@$json['sintoma']!='0'){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Categoría</div>
	<div style="float:left;"><?php echo $util->getCategoria($json['sintoma']);?>
	</div>
</div>
<?php }?>
<?php if (isset($json['sintoma'])&&@$json['sintoma']!=''&&@$json['sintoma']!='0'){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Síntoma</div>
	<div style="float:left;"><?php echo $util->getSintoma($json['sintoma']);?>
	</div>
</div>
<?php }?>
<?php if (isset($json['horometro_cabina'])&&@$json['horometro_cabina']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Horómetro Cabina</div>
	<div style="float:left;"><?php echo strtoupper($json['horometro_cabina']);?>
	</div>
</div>
<?php }?>
<?php if (isset($json['horometro_final'])&&@$json['horometro_final']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Horómetro Final</div>
	<div style="float:left;"><?php echo strtoupper($json['horometro_final']);?></div>
</div>
<?php }?>
<?php if (isset($json['lugar_reparacion'])&&@$json['lugar_reparacion']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Lugar Reparación</div>
	<div style="float:left;"><?php echo strtoupper($json['lugar_reparacion'])=="TA"?"TALLER":"TERRENO";?></div>
</div>
<?php }?>
<?php if (isset($json['fecha_inicio_g'])&&@$json['fecha_inicio_g']!=''&&isset($json['fecha_termino_g'])&&@$json['fecha_termino_g']!=''){?>
<?php
	$init=strtotime($json['fecha_termino_g'])-strtotime($json['fecha_inicio_g']);
	$hours=str_pad(floor($init / 3600),2,'0',STR_PAD_LEFT);
	$minutes=str_pad(floor($init/60)-$hours*60,2,'0',STR_PAD_LEFT);
?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Duración Intervención</div>
	<div style="float:left;"><?php echo "$hours:$minutes";?></div>
</div>
<?php }?>
<?php if (isset($json['cambio_modulo'])&&@$json['cambio_modulo']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Cambio de Módulo</div>
	<div style="float:left;"><?php echo strtoupper($json['cambio_modulo'])=="S"?"SI":"NO";?></div>
</div>
<?php }?>
<?php if (isset($json['intervencion_terminada'])&&@$json['intervencion_terminada']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Intervención Terminada  </div>
	<div style="float:left;"><?php echo strtoupper($json['intervencion_terminada'])=="S"?"SI":"NO";?></div>
</div>
<?php }?>
<?php if (isset($json['realiza_prueba_potencia'])&&@$json['realiza_prueba_potencia']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Prueba Potencia Realizada  </div>
	<div style="float:left;"><?php echo strtoupper($json['realiza_prueba_potencia'])=="S"?"SI":"NO";?></div>
</div>
<?php }?>
<?php if (isset($json['pp_exitosa'])&&@$json['pp_exitosa']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Prueba Potencia Exitosa  </div>
	<div style="float:left;"><?php echo strtoupper($json['pp_exitosa'])=="S"?"SI":"NO";?></div>
</div>
<?php }?>
<?php if (isset($json['desconexion_realizada'])&&@$json['desconexion_realizada']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Desconexión Realizada  </div>
	<div style="float:left;"><?php echo strtoupper($json['desconexion_realizada'])=="S"?"SI":"NO";?></div>
</div>
<?php }?>
<?php if (isset($json['desconexion_terminada'])&&@$json['desconexion_terminada']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Desconexión Terminada </div>
	<div style="float:left;"><?php echo strtoupper($json['desconexion_terminada'])=="S"?"SI":"NO";?></div>
</div>
<?php }?>   
 

 
<?php if (isset($json['conexion_realizada'])&&@$json['conexion_realizada']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Conexión Realizada   </div>
	<div style="float:left;"><?php echo strtoupper($json['conexion_realizada'])=="S"?"SI":"NO";?></div>
</div>
<?php }?>
<?php if (isset($json['conexion_terminada'])&&@$json['conexion_terminada']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Conexión Terminada  </div>
	<div style="float:left;"><?php echo strtoupper($json['conexion_terminada'])=="S"?"SI":"NO";?></div>
</div>
<?php }?>
<?php if (isset($json['puesta_marcha_realizada'])&&@$json['puesta_marcha_realizada']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Puesta en Marcha Realizada   </div>
	<div style="float:left;"><?php echo strtoupper($json['puesta_marcha_realizada'])=="S"?"SI":"NO";?></div>
</div>
<?php }?>
<?php if (isset($json['trabajo_finalizado'])&&@$json['trabajo_finalizado']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Trabajo Finalizado  </div>
	<div style="float:left;"><?php echo strtoupper($json['trabajo_finalizado'])=="S"?"SI":"NO";?></div>
</div>
<?php }?>


  





<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Comentarios</div>
	<div style="float:left;"><?php echo @$json['comentarios'];?></div>
</div>

<?php if (isset($json['ele_cantidad']) && is_numeric($json['ele_cantidad']) && intval($json['ele_cantidad']) > 0&& ($intervencion['Planificacion']['estado']!=2&&$intervencion['Planificacion']['estado']!=1)) { ?>
<h3 style="clear:both;">Elementos</h3>
<table cellpadding="1" cellspacing="0" width="100%" border="1">
  <thead>
	  <tr style="font-weight:bold;">
		<td width="20">#</td>
		<td>Sistema</td>
		<td>Subsistema</td>
		<td>Posición</td>
		<td>ID</td>
		<td>Elemento</td>
		<td>Posición</td>
		<td>Diagnóstico</td>
		<td style="100px">Solución</td>
		<td style="140px">Tipo</td>
        <td width="40">Duración</td>
	  </tr>
  </thead>
  <tbody>
<?php 
	for ($i = 1; $i <= intval($json['ele_cantidad']); $i++) { 
		$elemento = split(',', @$json["elemento_".$i]); 
		if (@$elemento[8] == "") {
			continue;
		}
?>
	<tr>
		<td width="20"><?php echo $i;?></td>
		<td><?php echo $util->getSistema(@$elemento[0]);?></td>
		<td><?php echo $util->getSubsistema(@$elemento[1]);?></td>
		<td><?php echo $util->getSubsistemaPosicion(@$elemento[2]);?></td>
		<td><?php echo @$elemento[9];?></td>
		<td><?php echo $util->getElemento(@$elemento[3]);?></td>
		<td><?php echo $util->getElementoPosicion(@$elemento[4]);?></td>
		<td><?php echo $util->getDiagnostico(@$elemento[6]);?></td>
		<td><?php echo $util->getSolucion(@$elemento[7]);?></td>
		<td>
		<?php switch (@$elemento[8]) {
		case 1:
			echo "FALLA";
			break;
		case 2:
			echo "CONSECUENCIA";
			break;
		case 3: 
			echo "OPORTUNIDAD";
			break;
	   }
		?>
		</td>
        <td><?php echo @$json["d3_elemento_h_".$i].":".@$json["d3_elemento_m_".$i];?></td>
	</tr>
<?php
	}
?>
  </tbody>
</table>
<?php } ?>
<div style="page-break-after: always;"></div>
<h3 style="clear:both;">Detalle Fechas</h3>
<?php if (isset($json['llamado_fecha'])&&@$json['llamado_fecha']!=''&&isset($json['llamado_hora'])&&@$json['llamado_hora']!=''&&isset($json['llamado_min'])&&@$json['llamado_min']!=''&&isset($json['llamado_periodo'])&&@$json['llamado_periodo']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Llamado</div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['llamado_fecha']." ".$json['llamado_hora'].":".$json['llamado_min']." ".$json['llamado_periodo']));?></div>
</div>
<?php }?>
<?php if (isset($json['llegada_fecha'])&&@$json['llegada_fecha']!=''&&isset($json['llegada_hora'])&&@$json['llegada_hora']!=''&&isset($json['llegada_min'])&&@$json['llegada_min']!=''&&isset($json['llegada_periodo'])&&@$json['llegada_periodo']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Llegada</div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['llegada_fecha']." ".$json['llegada_hora'].":".$json['llegada_min']." ".$json['llegada_periodo']));?></div>
</div>
<?php }?>
<?php if (isset($json['i_i_f'])&&@$json['i_i_f']!=''&&isset($json['i_i_h'])&&@$json['i_i_h']!=''&&isset($json['i_i_m'])&&@$json['i_i_m']!=''&&isset($json['i_i_p'])&&@$json['i_i_p']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Inicio Intervención</div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['i_i_f']." ".$json['i_i_h'].":".$json['i_i_m']." ".$json['i_i_p']));?></div>
</div>
<?php }?>
<?php if (isset($json['i_t_f'])&&@$json['i_t_f']!=''&&isset($json['i_t_h'])&&@$json['i_t_h']!=''&&isset($json['i_t_m'])&&@$json['i_t_m']!=''&&isset($json['i_t_p'])&&@$json['i_t_p']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Término Intervención</div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['i_t_f']." ".$json['i_t_h'].":".$json['i_t_m']." ".$json['i_t_p']));?></div>
</div>
<?php }?>
<?php if (isset($json['desc_f'])&&@$json['desc_f']!=''&&isset($json['desc_h'])&&@$json['desc_h']!=''&&isset($json['desc_m'])&&@$json['desc_m']!=''&&isset($json['desc_p'])&&@$json['desc_p']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Inicio Desconexión</div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['desc_f']." ".$json['desc_h'].":".$json['desc_m']." ".$json['desc_p']));?></div>
</div>
<?php }?>
<?php if (isset($json['desct_f'])&&@$json['desct_f']!=''&&isset($json['desct_h'])&&@$json['desct_h']!=''&&isset($json['desct_m'])&&@$json['desct_m']!=''&&isset($json['desct_p'])&&@$json['desct_p']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Término Desconexión</div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['desct_f']." ".$json['desct_h'].":".$json['desct_m']." ".$json['desct_p']));?></div>
</div>
<?php }?>
<?php if (isset($json['con_f'])&&@$json['con_f']!=''&&isset($json['con_h'])&&@$json['con_h']!=''&&isset($json['con_m'])&&@$json['con_m']!=''&&isset($json['con_p'])&&@$json['con_p']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Inicio Conexión </div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['con_f']." ".$json['con_h'].":".$json['con_m']." ".$json['con_p']));?></div>
</div>
<?php }?>
<?php if (isset($json['cont_f'])&&@$json['cont_f']!=''&&isset($json['cont_h'])&&@$json['cont_h']!=''&&isset($json['cont_m'])&&@$json['cont_m']!=''&&isset($json['cont_p'])&&@$json['cont_p']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Término Conexión </div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['cont_f']." ".$json['cont_h'].":".$json['cont_m']." ".$json['cont_p']));?></div>
</div>
<?php }?>
<?php if (isset($json['pm_i_f'])&&@$json['pm_i_f']!=''&&isset($json['pm_i_h'])&&@$json['pm_i_h']!=''&&isset($json['pm_i_m'])&&@$json['pm_i_m']!=''&&isset($json['pm_i_p'])&&@$json['pm_i_p']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Inicio Puesta en Marcha </div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['pm_i_f']." ".$json['pm_i_h'].":".$json['pm_i_m']." ".$json['pm_i_p']));?></div>
</div>
<?php }?>
<?php if (isset($json['pm_t_f'])&&@$json['pm_t_f']!=''&&isset($json['pm_t_h'])&&@$json['pm_t_h']!=''&&isset($json['pm_t_m'])&&@$json['pm_t_m']!=''&&isset($json['pm_t_p'])&&@$json['pm_t_p']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Término Puesta en Marcha </div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['pm_t_f']." ".$json['pm_t_h'].":".$json['pm_t_m']." ".$json['pm_t_p']));?></div>
</div>
<?php }?>
<?php if (isset($json['pp_i_f'])&&@$json['pp_i_f']!=''&&isset($json['pp_i_h'])&&@$json['pp_i_h']!=''&&isset($json['pp_i_m'])&&@$json['pp_i_m']!=''&&isset($json['pp_i_p'])&&@$json['pp_i_p']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Inicio Prueba Potencia</div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['pp_i_f']." ".$json['pp_i_h'].":".$json['pp_i_m']." ".$json['pp_i_p']));?></div>
</div>
<?php }?>
<?php if (isset($json['pp_t_f'])&&@$json['pp_t_f']!=''&&isset($json['pp_t_h'])&&@$json['pp_t_h']!=''&&isset($json['pp_t_m'])&&@$json['pp_t_m']!=''&&isset($json['pp_t_p'])&&@$json['pp_t_p']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Término Prueba Potencia</div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['pp_t_f']." ".$json['pp_t_h'].":".$json['pp_t_m']." ".$json['pp_t_p']));?></div>
</div>
<?php }?>

<?php if (isset($json['i_t_r_f'])&&@$json['i_t_r_f']!=''&&isset($json['i_t_r_h'])&&@$json['i_t_r_h']!=''&&isset($json['i_t_r_m'])&&@$json['i_t_r_m']!=''&&isset($json['i_t_r_p'])&&@$json['i_t_r_p']!=''){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Término Reproceso</div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($json['i_t_r_f']." ".$json['i_t_r_h'].":".$json['i_t_r_m']." ".$json['i_t_r_p']));?></div>
</div>
<?php }?>
<!--
  "f_a_motor": "0",
  "f_a_motor_s": "0",
  "f_a_reserva": "35",
  "f_a_reserva_s": "R",
  "f_combustible": "0",
  "f_combustible_s": "0",
  "f_refrigerante": "0",
  "f_refrigerante_s": "0",
  "f_resurs": "0",
  "f_zerex": "0",
  -->
<?php if(@$json["f_a_motor"]!=''&&@$json["f_a_motor"]!='0'||@$json["f_a_reserva"]!=''&&@$json["f_a_reserva"]!='0'||@$json["f_combustible"]!=''&&@$json["f_combustible"]!='0'||@$json["f_refrigerante"]!=''&&@$json["f_refrigerante"]!='0'||@$json["f_resurs"]!=''&&@$json["f_resurs"]!='0'||@$json["f_zerex"]!=''&&@$json["f_zerex"]!='0'){ ?>
<h3 style="clear:both;">Fluídos</h3>
<?php if(@$json["f_a_motor"]!=''&&@$json["f_a_motor"]!='0'){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Aceite Motor</div>
	<div style="float:left;"><?php echo @$json["f_a_motor"];?> litros</div>
</div>
<?php } ?>
<?php if(@$json["f_a_reserva"]!=''&&@$json["f_a_reserva"]!='0'){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Aceite Reserva</div>
	<div style="float:left;"><?php echo @$json["f_a_reserva"];?> litros</div>
</div>
<?php } ?>
<?php if(@$json["f_combustible"]!=''&&@$json["f_combustible"]!='0'){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Combustible</div>
	<div style="float:left;"><?php echo @$json["f_combustible"];?> litros</div>
</div>
<?php } ?>
<?php if(@$json["f_refrigerante"]!=''&&@$json["f_refrigerante"]!='0'){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Refrigerante</div>
	<div style="float:left;"><?php echo @$json["f_refrigerante"];?> litros</div>
</div>
<?php } ?>
<?php if(@$json["f_resurs"]!=''&&@$json["f_resurs"]!='0'){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Resurs</div>
	<div style="float:left;"><?php echo @$json["f_resurs"];?> litros</div>
</div>
<?php } ?>
<?php if(@$json["f_zerex"]!=''&&@$json["f_zerex"]!='0'){?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Zerex</div>
	<div style="float:left;"><?php echo @$json["f_zerex"];?> tarros</div>
</div>
<?php } ?>
<?php } ?>
<h3 style="clear:both;">Estado Intervención</h3>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Estado</div>
	<div style="float:left;">
	<?php switch ($intervencion['Planificacion']['estado']) {
		case 4:
			echo "APROBADO DCC";
			break;
		case 5:
			echo "APROBADO CLIENTE";
			break;
		case 6: 
			echo "RECHAZADO CLIENTE";
			break;
		case 2:
			echo "PLANIFICADO";
			break;
		case 7:
			echo "SIN REVISAR";
			break;
		case 1:
			echo "BORRADOR";
			break;
		default:
			echo "SIN INFORMACIÓN";
			break;
	   } ?>
	</div>
</div>
<?php if($json["sync_inte"]!=null&&$json["sync_inte"]){ ?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Fecha Registro</div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', $json["sync_inte"]/1000);?></div>
</div>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Dispositivo Registro</div>
	<div style="float:left;"><?php
	if (preg_match('/iphone/i', $intervencion["Planificacion"]["log"])) {
        echo "Móvil";
    } elseif (preg_match('/android/i', $intervencion["Planificacion"]["log"])) {
        echo "Móvil";
    } elseif (preg_match('/ipad/i', $intervencion["Planificacion"]["log"])) {
        echo "Móvil";
    } elseif (preg_match('/linux/i', $intervencion["Planificacion"]["log"])) {
        echo "Computador";
    } elseif (preg_match('/macintosh|mac os x/i', $intervencion["Planificacion"]["log"])) {
        echo "Computador";
    } elseif (preg_match('/windows|win32/i', $intervencion["Planificacion"]["log"])) {
        echo "Computador";
    } else {
		echo "Computador";
	} 
?>
	</div>
</div>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Técnico</div>
	<div style="float:left;"><?php echo $util->getUsuarioInfo($json['tecnico_principal']);?></div>
</div>
<?php } ?>
<?php if($intervencion['Planificacion']['fecha_aprobacion']!=null&&$intervencion['Planificacion']['fecha_aprobacion']!=''){ ?>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Fecha Aprobación</div>
	<div style="float:left;"><?php echo date('d-m-Y h:i A', strtotime($intervencion['Planificacion']['fecha_aprobacion']));?></div>
</div>
<div style="clear:both;width:100%;">
	<div style="float:left;width:200px;margin-bottom:15px;">Supervisor DCC</div>
	<div style="float:left;"><?php echo $util->getUsuarioInfo($intervencion['Planificacion']['aprobador_id']);?></div>
</div>
<?php } ?>
</body>
</html>