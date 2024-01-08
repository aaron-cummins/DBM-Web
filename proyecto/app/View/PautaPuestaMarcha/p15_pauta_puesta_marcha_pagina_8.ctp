<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption font-dark">
					<i class="icon-settings font-dark"></i>
					<span class="caption-subject bold uppercase">Pauta Puesta en Marcha</span>
				</div>
				<div class="tools">
					<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/1/<?php echo $id;?>';">Página 1</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/2/<?php echo $id;?>';">Página 2</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/3/<?php echo $id;?>';">Página 3</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/4/<?php echo $id;?>';">Página 4</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/5/<?php echo $id;?>';">Página 5</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/6/<?php echo $id;?>';">Página 6</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/7/<?php echo $id;?>';">Página 7</button>
						<button type="button" class="btn btn-sm green" onclick="window.location='/PautaPuestaMarcha/Ver/8/<?php echo $id;?>';">Página 8</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/9/<?php echo $id;?>';">Página 9</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/10/<?php echo $id;?>';">Página 10</button> </div>
			</div>
			<div class="portlet-body form">
				<form action="" class="horizontal-form" method="post" id="form-pauta-mantencion">
					
					<div id="table_1_wrapper" class="dataTables_wrapper">
						<div class="form-body">						
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										
										
										
	<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" id="tblPautaMantencion" class="table table-striped table-bordered table-hover">

  <tr>
    <td colspan="2" bgcolor="#333333" style="color: #fff; padding:10px;" ><strong>Chequear durante la operación del motor</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>SI</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>NO</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>NO    APLICA</strong></td>
  </tr>
  <tr>
    <td colspan="2"  >Ruidos    anormales</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_08_ruidos_anormales" id="Ppm_08_ruidos_anormales_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_08_ruidos_anormales" id="Ppm_08_ruidos_anormales_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_08_ruidos_anormales" id="Ppm_08_ruidos_anormales_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >Operación    del acelerador</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_08_operacion_del_acelerador" id="Ppm_08_operacion_del_acelerador_si" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_08_operacion_del_acelerador" id="Ppm_08_operacion_del_acelerador_no" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_08_operacion_del_acelerador" id="Ppm_08_operacion_del_acelerador_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2"  >Fugas    de combustible, lubricante, aceite o refrigerante</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_08_fugas" id="Ppm_08_fugas_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_08_fugas" id="Ppm_08_fugas_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_08_fugas" id="Ppm_08_fugas_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >Medidores    defectuosos o inoperativos</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_08_medidores_defectuosos" id="Ppm_08_medidores_defectuosos_si" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_08_medidores_defectuosos" id="Ppm_08_medidores_defectuosos_no" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_08_medidores_defectuosos" id="Ppm_08_medidores_defectuosos_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2"  >Fallas    en el sistema de admisión de aire</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_08_fallas_sistema_admision_aire" id="Ppm_08_fallas_sistema_admision_aire_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_08_fallas_sistema_admision_aire" id="Ppm_08_fallas_sistema_admision_aire_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_08_fallas_sistema_admision_aire" id="Ppm_08_fallas_sistema_admision_aire_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >Fallas    en el sistema de escape</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_08_fallas_sistema_escape" id="Ppm_08_fallas_sistema_escape_si" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_08_fallas_sistema_escape" id="Ppm_08_fallas_sistema_escape_no" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_08_fallas_sistema_escape" id="Ppm_08_fallas_sistema_escape_na" value="N/A"></td>
  </tr>
  <tr>
    <td ></td>
    <td ></td>
    <td ></td>
    <td width="38"></td>
    <td width="95"></td>
    <td width="114"></td>
    <td width="76"></td>
    <td width="38"></td>
  </tr>
  <tr>
    <td colspan="2" ></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>MEDIDA</strong></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>NO    APLICA</strong></td>
  </tr>
  <tr>
    <td colspan="2"  >Velocidad    motor en ralentí</td>
    <td colspan="2"  ><input type="text" name="Ppm_08_velocidad_motor_ralenti" id="Ppm_08_velocidad_motor_ralenti"></td>
    <td width="95" >RPM</td>
    <td colspan="3" align="center" ><input type="checkbox" data-role="none" name="Ppm_08_velocidad_motor_ralenti_na" id="Ppm_08_velocidad_motor_ralenti_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Presión    de aceite en ralentí</td>
    <td colspan="2" ><input type="text" name="Ppm_08_presion_aceite_ralenti" id="Ppm_08_presion_aceite_ralenti"></td>
    <td width="95">PSI</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_08_presion_aceite_ralenti_na" id="Ppm_08_presion_aceite_ralenti_na"></td>
  </tr>
  <tr>
    <td colspan="2"  >Temperatura    de aceite</td>
    <td colspan="2"  ><input type="text" name="Ppm_08_temperatura_aceite" id="Ppm_08_temperatura_aceite"></td>
    <td width="95" >°F</td>
    <td colspan="3" align="center" ><input type="checkbox" data-role="none" name="Ppm_08_temperatura_aceite_na" id="Ppm_08_temperatura_aceite_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Temperatura    de refrigerante</td>
    <td colspan="2" ><input type="text" name="Ppm_08_temperatura_refrigerante" id="Ppm_08_temperatura_refrigerante"></td>
    <td width="95">°F</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_08_temperatura_refrigerante_na" id="Ppm_08_temperatura_refrigerante_na"></td>
  </tr>
  <tr>
    <td colspan="2"  >Restricción    línea alimentación de combustible</td>
    <td colspan="2"  ><input type="text" name="Ppm_08_restriccion_alimentacion_combustible" id="Ppm_08_restriccion_alimentacion_combustible"></td>
    <td width="95" >Inch    Hg</td>
    <td colspan="3" align="center" ><input type="checkbox" data-role="none" name="Ppm_08_restriccion_alimentacion_combustible_na" id="Ppm_08_restriccion_alimentacion_combustible_is"></td>
  </tr>
  <tr>
    <td colspan="2" >Restricción    línea retorno de combustible</td>
    <td colspan="2" ><input type="text" name="Ppm_08_restriccion_retorno_combustible" id="Ppm_08_restriccion_retorno_combustible"></td>
    <td width="95">Inch    Hg</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_08_restriccion_retorno_combustible_na" id="Ppm_08_restriccion_retorno_combustible_na"></td>
  </tr>
  <tr>
    <td ></td>
    <td ></td>
    <td ></td>
    <td width="38"></td>
    <td width="95"></td>
    <td width="114" align="center"></td>
    <td width="76" align="center"></td>
    <td width="38" align="center"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>Registrar    las siguientes mediciones a través de los sistemas Quantum o Cense mientras    el motor está funcionando a máxima potencia/aceleración:</strong></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>MEDIDA</strong></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding:10px;"><strong>NO    APLICA</strong></td>
  </tr>
  <tr>
    <td colspan="2"  >Velocidad    motor</td>
    <td colspan="2"  ><input type="text" name="Ppm_08_velocidad_motor" id="Ppm_08_velocidad_motor"></td>
    <td width="95" >RPM</td>
    <td colspan="3" align="center" ><input type="checkbox" data-role="none" name="Ppm_08_velocidad_motor_na" id="Ppm_08_velocidad_motor_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Presión    aceite lubricante</td>
    <td colspan="2" ><input type="text" name="Ppm_08_presion_aceite_lubricante" id="Ppm_08_presion_aceite_lubricante"></td>
    <td width="95">PSI</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_08_presion_aceite_lubricante_na" id="Ppm_08_presion_aceite_lubricante_na"></td>
  </tr>
  <tr>
    <td colspan="2"  >Presión    combustible en el riel</td>
    <td colspan="2"  ><input type="text" name="Ppm_08_presion_combustible_riel" id="Ppm_08_presion_combustible_riel"></td>
    <td width="95" >PSI</td>
    <td colspan="3" align="center" ><input type="checkbox" data-role="none" name="Ppm_08_presion_combustible_riel_na" id="Ppm_08_presion_combustible_riel_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Temperatura    de escape</td>
    <td colspan="2" ><input type="text" name="Ppm_08_temperatura_escape" id="Ppm_08_temperatura_escape"></td>
    <td width="95">°F</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_08_temperatura_escape_na" id="Ppm_08_temperatura_escape_na"></td>
  </tr>
  <tr>
    <td colspan="2"  >Temperatura    de agua motor</td>
    <td colspan="2"  ><input type="text" name="Ppm_08_temperatura_agua_motor" id="Ppm_08_temperatura_agua_motor"></td>
    <td width="95" >°F</td>
    <td colspan="3" align="center" ><input type="checkbox" data-role="none" name="Ppm_08_temperatura_agua_motor_na" id="Ppm_08_temperatura_agua_motor_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Temperatura    en el múltiple de admisión</td>
    <td colspan="2" ><input type="text" name="Ppm_08_temperatura_multiple_admision" id="Ppm_08_temperatura_multiple_admision"></td>
    <td width="95">°F</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_08_temperatura_multiple_admision_na" id="Ppm_08_temperatura_multiple_admision_na"></td>
  </tr>
  <tr>
    <td colspan="2"  >Potencia    de salida</td>
    <td colspan="2"  ><input type="text" name="Ppm_08_potencia_salida" id="Ppm_08_potencia_salida"></td>
    <td width="95" >BHP</td>
    <td colspan="3" align="center" ><input type="checkbox" data-role="none" name="Ppm_08_potencia_salida_na" id="Ppm_08_potencia_salida_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Presión    de blowby</td>
    <td colspan="2" ><input type="text" name="Ppm_08_potencia_blowby" id="Ppm_08_potencia_blowby"></td>
    <td width="95">Inch    H2O</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_08_potencia_blowby_na" id="Ppm_08_potencia_blowby_na"></td>
  </tr>
</table>
</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-actions right">
						<input type="hidden" name="pagina" value="08" />	
						<button type="button" class="btn default" onclick="window.location='/Trabajo/Detalle2/<?php echo $id;?>';">Volver a Intervención</button>
						
						<?php if (isset($estado) && $estado != "4") { ?>
						<button type="button" class="btn blue btn-pauta-mantencion">
							<i class="fa fa-save"></i> Guardar</button>
						<?php } ?>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>

<div id="confirma_guardado" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
	<div class="modal-body">
        <p> ¿Realmente desea guardar los cambios ingresados? </p>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline red">Cancelar</button>
        <button type="button" class="btn green btn-submit-modal">Guardar</button>
    </div>
</div>

<div id="modal_mensaje_error" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
	<div class="modal-body">
        <p class="mensaje_modal"></p>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline red">Aceptar</button>
    </div>
</div>