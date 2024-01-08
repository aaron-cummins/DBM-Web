<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$usuario = $this->Session->read('Usuario');
	$faena_id = $this->Session->read('faena_id');
	$fecha_max = date("Y-m-d", time() + 365 * 24 * 60 *60);
	//print_r(@$planificacion["Planificacion"]);////
	$tiempo_estimado = explode(":",  @$planificacion["Planificacion"]["tiempo_trabajo"]);
	$hora_planificado = explode(":", @$planificacion["Planificacion"]["hora_planificacion"]);
	if(is_numeric(@$hora_planificado[0])&&intval(@$hora_planificado[0])>12){
		@$hora_planificado[0]=@$hora_planificado[0]-12;
		$hora_planificado[2]="PM";
	}
	if(is_numeric(@$hora_planificado[0])&&intval(@$hora_planificado[0])<12){
		$hora_planificado[2]="AM";
	}
?>
<script type="text/javascript">
$(document).ready(function(){
	<?php if ($planificacion!=null&&$planificacion["Planificacion"]!=null&&is_array($planificacion["Planificacion"])){?>
		$("#faena_id").val('<?php echo @$planificacion["Planificacion"]["faena_id"];?>');
		//$("#faena_id").change();
		/*$("#flota_id").change(function(){
			console.log($(this).val());
			$("#unidad_id").val('<?php echo @$planificacion["Planificacion"]["unidad_id"];?>');
			$("#unidad_id").change();
		});*/
		//$("#flota_id").val('<?php echo @$planificacion["Planificacion"]["flota_id"];?>');
		//$("#flota_id").change();
		$("#esn").val('<?php echo @$planificacion["Planificacion"]["esn"];?>');
		$("#esn").change();
		//$("#tipointervencion").val('<?php echo @$planificacion["Planificacion"]["tipointervencion"];?>');
		//$("#tipointervencion").change();
		$("#tipomantencion").val('<?php echo @$planificacion["Planificacion"]["tipomantencion"];?>');
		$("#tipomantencion").change();
		$("#sintoma_id").val('<?php echo @$planificacion["Planificacion"]["sintoma_id"];?>');
		$("#sintoma_id").change();
		/*$("#backlog_id").val('<?php echo @$planificacion["Planificacion"]["backlog_id"];?>');
		$("#backlog_id").change();*/
	<?php }?>
});
</script>
    <div class="titleArea" style="padding-top: 0;">
      <div class="wrapper">
            <div class="pageTitle">
                <h5>Planificaciones</h5>
                <span>Planificar</span>
            </div>
          <div class="clear"></div>
        </div>
    </div>
    <div class="line"></div>
    <?php echo $this->Session->flash(); ?>
    <div class="wrapper">
    <div class="widget">
	<form action="/Planificacion/agregar" method="POST">
		<input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>" />
		<input type="hidden" name="estado" value="1" />
		<input type="hidden" name="id" value="<?php echo @$planificacion["Planificacion"]["id"];?>" />
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Planificar</h6></div>
        <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
	<tr>
    <td>Faena</td>
    <td>
	<?php if ($faena_id == "0") { ?>
	<select name="faena_id" id="faena_id" style="width: 250px; overflow: hidden;">
		<?php foreach ($faenas as $key => $value) { ?>
		<option value="<?php echo $key; ?>"<?php echo $faena_id == $key ? ' selected="selected"':'';?>><?php echo $value; ?></option>
		<?php } ?>
	</select>
	<?php } else { ?>
	<input type="hidden" name="faena_id" id="faena_id" value="<?php echo $faena_id;?>" />
	<?php echo $faena; ?>
	<?php } ?>
	</td>
    <td>Tipo Intervención</td>
    <td>
	
	<select name="tipointervencion" id="tipointervencion" style="width: 250px; overflow: hidden;" v="<?php echo strtoupper($planificacion["Planificacion"]["tipointervencion"]);?>">
	<option value=""></option>
      <option value="MP">MP</option>
      <option value="RP">RP</option>
      <option value="BL">OP</option>
    </select></td>
  </tr>
  <tr>
    <td>Flota</td>
    <td>
		<select name="flota_id" id="flota_id" flota_id="<?php echo @$planificacion["Planificacion"]["flota_id"]; ?>" style="width: 250px; overflow: hidden;">
		</select>
	</td>
	<td class="bl">Backlog</td>
    <td class="bl"><select name="backlog_id" id="backlog_id" backlog_id="<?php echo @$planificacion["Planificacion"]["backlog_id"]; ?>" style="width: 250px; overflow: hidden;">
	<!--<option value=""></option>-->
    </select></td>
    <td class="nomp">Categoría Síntoma</td>
    <td class="nomp">
		<select name="categoria_id" id="categoria_id" style="width: 250px; overflow: hidden;">
			<option value=""></option>
			<?php foreach ($sintomaCategoria as $key => $value) { ?>
			<option value="<?php echo $key; ?>"<?php echo $planificacion["Sintoma"]["sintoma_categoria_id"] == $key ? ' selected="selected"':'';?>><?php echo $value; ?></option>
			<?php } ?>
		</select>	
	</td>
	<td class="mp">Tipo Mantencion</td>
    <td class="mp">
		<select name="tipomantencion" id="tipomantencion" style="width: 250px; overflow: hidden;">
			<option value=""></option>
			<option value="250"<?php echo $planificacion["Planificacion"]["tipomantencion"] == '250' ? ' selected="selected"':'';?>>250</option>
			<option value="500"<?php echo $planificacion["Planificacion"]["tipomantencion"] == '500' ? ' selected="selected"':'';?>>500</option>
			<option value="1000"<?php echo $planificacion["Planificacion"]["tipomantencion"] == '1000' ? ' selected="selected"':'';?>>1000</option>
			<option value="1500"<?php echo $planificacion["Planificacion"]["tipomantencion"] == '1500' ? ' selected="selected"':'';?>>Overhaul</option>
		</select>	
	</td>
    
  </tr>
    <tr>
    <td>Equipo</td>
    <td>
		<select name="unidad_id" id="unidad_id" unidad_id="<?php echo @$planificacion["Planificacion"]["unidad_id"]; ?>" style="width: 250px; overflow: hidden;">
		</select>
	</td>
	<td class="nomp">Síntoma</td>
    <td class="nomp">
		<select name="sintoma_id" id="sintoma_id" sintoma_id="<?php echo @$planificacion["Planificacion"]["sintoma_id"]; ?>" style="width: 250px; overflow: hidden;">
		</select>	
	</td>
	<td class="mp"></td>
	<td class="mp"></td>
	<td class="bl"><span class="info_backlog" style="display: none;">Información Backlog<span></td>
	<td class="bl"><span class="info_backlog_comentario" style="display: none;"><span></td>
  </tr>
  <tr>
     <td>ESN</td>
    <td><input type="text" name="esn" id="esn" pattern="[a-zA-Z0-9]+" value="<?php echo $planificacion["Planificacion"]["esn"];?>" /></td>
	<td></td>
	<td></td>
  </tr>
  <tr>
   <td>Fecha Programada</td>
    <td>
	<input type="date" name="programacion_fecha" id="programacion_fecha" value="<?php echo $planificacion["Planificacion"]["fecha"];?>" maxlength="10" max="<?php echo $fecha_max;?>" />
	</td>
    <td>Tiempo Estimado de trabajo</td>
    <td>
	
	<input type="text" name="estimado_hora" id="estimado_hora" size="2"	value="<?php echo @$tiempo_estimado[0];?>" /> h 
	<select name="estimado_minuto" id="estimado_minuto">
	  <option value=""></option>
	  <option value="00"<?php echo @$tiempo_estimado[1] == '00' ? ' selected="selected"':'';?>>00</option>
	  <option value="15"<?php echo @$tiempo_estimado[1] == '15' ? ' selected="selected"':'';?>>15</option>
	  <option value="30"<?php echo @$tiempo_estimado[1] == '30' ? ' selected="selected"':'';?>>30</option>
	  <option value="45"<?php echo @$tiempo_estimado[1] == '45' ? ' selected="selected"':'';?>>45</option>
	</select> m
	</td>
  </tr>
  <tr>
   <td>Hora Programada</td>
    <td>
	<select name="programacion_hora" id="programacion_hora">
	  <option value=""></option>
	  <option value="01"<?php echo @$hora_planificado[0]==1?' selected="selected"':'';?>>01</option>
	  <option value="02"<?php echo @$hora_planificado[0]==2?' selected="selected"':'';?>>02</option>
	  <option value="03"<?php echo @$hora_planificado[0]==3?' selected="selected"':'';?>>03</option>
	  <option value="04"<?php echo @$hora_planificado[0]==4?' selected="selected"':'';?>>04</option>
	  <option value="05"<?php echo @$hora_planificado[0]==5?' selected="selected"':'';?>>05</option>
	  <option value="06"<?php echo @$hora_planificado[0]==6?' selected="selected"':'';?>>06</option>
	  <option value="07"<?php echo @$hora_planificado[0]==7?' selected="selected"':'';?>>07</option>
	  <option value="08"<?php echo @$hora_planificado[0]==8?' selected="selected"':'';?>>08</option>
	  <option value="09"<?php echo @$hora_planificado[0]==9?' selected="selected"':'';?>>09</option>
	  <option value="10"<?php echo @$hora_planificado[0]==10?' selected="selected"':'';?>>10</option>
	  <option value="11"<?php echo @$hora_planificado[0]==11?' selected="selected"':'';?>>11</option>
	  <option value="12"<?php echo @$hora_planificado[0]==12||@$hora_planificado[0]=='00'?' selected="selected"':'';?>>12</option>
	</select> h 
	
	<select name="programacion_minuto" id="programacion_minuto">
	  <option value=""></option>
	  <option value="00"<?php echo @$hora_planificado[1] == '00' ? ' selected="selected"':'';?>>00</option>
	  <option value="15"<?php echo @$hora_planificado[1] == '15' ? ' selected="selected"':'';?>>15</option>
	  <option value="30"<?php echo @$hora_planificado[1] == '30' ? ' selected="selected"':'';?>>30</option>
	  <option value="45"<?php echo @$hora_planificado[1] == '45' ? ' selected="selected"':'';?>>45</option>
	</select> m 
	
	<select name="programacion_periodo" id="programacion_periodo">
	  <option value=""></option>
	  <option value="AM"<?php echo @$hora_planificado[2]=='AM'?' selected="selected"':'';?>>AM</option>
	  <option value="PM"<?php echo @$hora_planificado[2]=='PM'?' selected="selected"':'';?>>PM</option>
	</select>
	</td>
    <td>Observación Supervisor</td>
    <td><textarea rows="2" name="observacion"><?php echo $planificacion["Planificacion"]["observacion"];?></textarea></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td><input type="button" name="" value="Cancelar" class="basic" onclick="window.location='/Planificacion/';" />
	<input type="submit" value="Aceptar" class="greenB" id="btnGuadarBorrador" /></td>
  </tr>
</table>
</form>
        </div>
<?php
		if (count($intervenciones) > 0) {
		?>		
		<div class="widget">
			<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Borradores</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
					<td width="20">Folio</td>
                      <td width="70px">Programación</td>
					  <td width="40px">Estimado</td>
                      <td width="60px">Flota</td>
                      <td width="60px">Equipo</td>
                      <td width="50px">ESN</td>
                      <td width="100px">Tipo</td>
                      <td>Síntoma / Tipo Mantención / Backlog</td>
                      <td>Observación</td>
                      <td width="190"></td>
                  </tr>
              </thead>
              <tbody>
			  <?php 
				//$i = 1;
				foreach ($intervenciones as $intervencion) { 
					$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					//$hora = substr($intervencion['Planificacion']['hora'], 0, 5);
			  ?>
                  <tr>
					<td><?php echo $intervencion['Planificacion']['id'];?></td>
					<td nowrap align="center"><?php echo $date->format('d-m-Y h:i A');?></td>
					<td nowrap align="center"><?php echo substr($intervencion['Planificacion']['tiempo_trabajo'], 0, 5); ?></td>
                      <td nowrap><?php echo $util->getFlota($intervencion['Planificacion']['flota_id']); ?></td>
                      <td nowrap><?php echo $util->getUnidad($intervencion['Planificacion']['unidad_id']); ?></td>
                      <td><?php echo $intervencion['Planificacion']['esn']; ?></td>
                      <td align="center"><?php 
					  if($intervencion['Planificacion']['tipointervencion']=="BL"){
						echo "OP";
					  }else{
						echo strtoupper($intervencion['Planificacion']['tipointervencion']);
					  }
					  ?></td>
                     <td>
						<?php 
					if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'MP') {
						if ($intervencion['Planificacion']['tipomantencion'] == "1500") {
							echo "Overhaul";
						} else {
							echo $intervencion['Planificacion']['tipomantencion'];
						}
					} elseif (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL') {
						if ($intervencion['Planificacion']['backlog_id'] != null) {
							echo $util->getBacklogInfo($intervencion['Planificacion']['backlog_id']);
						}
					} else {
						echo @$util->getSintoma(@$intervencion['Planificacion']['sintoma_id']);
					}				  
					?>
					 </td>
                      <!--<td><?php //echo $intervencion['Planificacion']['backlog_id']; ?>-->
						<?php //echo $intervencion['Backlog']['criticidad'] ." / ". $intervencion['Backlog']['Sistema']['nombre']; ?>
					  </td>
                      <td><?php echo $intervencion['Planificacion']['observacion']; ?></td>
                      <td align="center">
						<form action="/Planificacion/quitar" method="POST">
							<input type="hidden" name="id" value="<?php echo $intervencion['Planificacion']['id']; ?>" />
							<input type="submit" value="Modificar" name="modificar" class="blueB" />
							<input type="submit" value="Quitar" name="quitar" class="redB" onclick="return confirm('Realmente desea quitar esta intervención?');" />
						</form>
					  
					  </td>
                  </tr>
				 <?php } ?>
              </tbody>
          </table>
<?php
///} else {
?>
<!--
<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
	<tbody>
		<tr>
			<td>Sin intervenciones programadas</td>
		</tr>
	</tbody>
</table>
-->
		  </div>
		  <br />
		  
		  <?php
}
?>
<?php
if (count($intervenciones) > 0) {
?>
<form action="/Planificacion/guardar" method="POST">
	<p align="left">
		<input type="submit" value="Guardar Planificación" class="blueB" />
	</p>
</form>
<?php
}
?>
     </div>