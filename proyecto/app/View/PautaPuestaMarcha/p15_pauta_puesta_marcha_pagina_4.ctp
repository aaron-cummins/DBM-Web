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
						<button type="button" class="btn btn-sm green" onclick="window.location='/PautaPuestaMarcha/Ver/4/<?php echo $id;?>';">Página 4</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/5/<?php echo $id;?>';">Página 5</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/6/<?php echo $id;?>';">Página 6</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/7/<?php echo $id;?>';">Página 7</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/8/<?php echo $id;?>';">Página 8</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/9/<?php echo $id;?>';">Página 9</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/10/<?php echo $id;?>';">Página 10</button>
				</div>
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
    <td colspan="2" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>COMPONENTES</strong></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding: 10px;" ><strong>DATOS</strong></td>
    <td colspan="3" align="center" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>NO APLICA</strong></td>
  </tr>
  <tr>
    <td colspan="2" >N/P    correa bomba de agua</td>
    <td colspan="3" ><input type="text" name="Ppm_04_np_correa_bomba_agua" id="Ppm_04_np_correa_bomba_agua"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_np_correa_bomba_agua_na" id="Ppm_04_np_correa_bomba_agua_na"></td>
  </tr>
  <tr >
    <td colspan="2" >N/P    correa ventilador</td>
    <td colspan="3" ><input type="text" name="Ppm_04_np_correa_ventilador" id="Ppm_04_np_correa_ventilador"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_np_correa_ventilador_na" id="Ppm_04_np_correa_ventilador_na"></td>
  </tr>
  <tr>
    <td colspan="2" >N/P    correa alternador</td>
    <td colspan="3" ><input type="text" name="Ppm_04_np_correa_alternador" id="Ppm_04_np_correa_alternador"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_np_correa_alternador_na" id="Ppm_04_np_correa_alternador_na"></td>
  </tr>
  <tr >
    <td colspan="2" >N/P    filtro de Aceite</td>
    <td colspan="3" ><input type="text" name="Ppm_04_np_filtro_aceite" id="Ppm_04_np_filtro_aceite"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_np_filtro_aceite_na" id="Ppm_04_np_filtro_aceite_na"></td>
  </tr>
  <tr>
    <td colspan="2" >N/P    filtro de combustible</td>
    <td colspan="3" ><input type="text" name="Ppm_04_np_filtro_combustible" id="Ppm_04_np_filtro_combustible"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_np_filtro_combustible_na" id="Ppm_04_np_filtro_combustible_na"></td>
  </tr>
  <tr >
    <td colspan="2" >N/P  filtro de agua</td>
    <td colspan="3" ><input type="text" name="Ppm_04_np_filtro_agua" id="Ppm_04_np_filtro_agua"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_np_filtro_agua_na" id="Ppm_04_np_filtro_agua_na"></td>
  </tr>
  <tr>
    <td colspan="2" >N/P    filtro de aire primario</td>
    <td colspan="3" ><input type="text" name="Ppm_04_np_filtro_aire_primario" id="Ppm_04_np_filtro_aire_primario"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_np_filtro_aire_primario_na" id="Ppm_04_np_filtro_aire_primario_na"></td>
  </tr>
  <tr >
    <td colspan="2" >N/P    filtro de aire secundario</td>
    <td colspan="3" ><input type="text" name="Ppm_04_np_filtro_aire_secundario" id="Ppm_04_np_filtro_aire_secundario"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_np_filtro_aire_secundario_na" id="Ppm_04_np_filtro_aire_secundario_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Fabricante    del Prelub</td>
    <td colspan="3" ><input type="text" name="Ppm_04_fabricante_prelub" id="Ppm_04_fabricante_prelub"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_fabricante_prelub_na" id="Ppm_04_fabricante_prelub_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Prelub</td>
    <td colspan="3" ><input type="text" name="Ppm_04_prelub" id="Ppm_04_prelub"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_prelub_na" id="Ppm_04_prelub_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Fabricante    del Alternador</td>
    <td colspan="3" ><input type="text" name="Ppm_04_fabricante_alternador" id="Ppm_04_fabricante_alternador"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_fabricante_alternador_na" id="Ppm_04_fabricante_alternador_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Modelo    Alternador</td>
    <td colspan="3" ><input type="text" name="Ppm_04_modelo_alternador" id="Ppm_04_modelo_alternador"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_modelo_alternador_na" id="Ppm_04_modelo_alternador_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Serie    Alternador</td>
    <td colspan="3" ><input type="text" name="Ppm_04_serie_alternador" id="Ppm_04_serie_alternador"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_serie_alternador_na" id="Ppm_04_serie_alternador_na"></td>
  </tr>
  <tr >
    <td colspan="2" >N°    especificación alternador</td>
    <td colspan="3" ><input type="text" name="Ppm_04_n_especificacion_alternador" id="Ppm_04_n_especificacion_alternador"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_n_especificacion_alternador_na" id="Ppm_04_n_especificacion_alternador_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Fabricante    motor partida</td>
    <td colspan="3" ><input type="text" name="Ppm_04_fabricante_motor_partida" id="Ppm_04_fabricante_motor_partida"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_fabricante_motor_partida_na" id="Ppm_04_fabricante_motor_partida_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Modelo    motor partida</td>
    <td colspan="3" ><input type="text" name="Ppm_04_modelo_motor_partida" id="Ppm_04_modelo_motor_partida"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_modelo_motor_partida_na" id="Ppm_04_modelo_motor_partida_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Serie    Motor de Partida</td>
    <td colspan="3" ><input type="text" name="Ppm_04_serie_motor_partida" id="Ppm_04_serie_motor_partida"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_serie_motor_partida_na" id="Ppm_04_serie_motor_partida_na"></td>
  </tr>
  <tr >
    <td colspan="2" >N/P    Motor de Partida</td>
    <td colspan="3" ><input type="text" name="Ppm_04_np_motor_partida" id="Ppm_04_np_motor_partida"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_04_np_motor_partida_na" id="Ppm_04_np_motor_partida_na"></td>
  </tr>
</table>
</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-actions right">
						<input type="hidden" name="pagina" value="04" />	
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