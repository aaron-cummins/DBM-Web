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
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/8/<?php echo $id;?>';">Página 8</button>
						<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/9/<?php echo $id;?>';">Página 9</button>
						<button type="button" class="btn btn-sm green" onclick="window.location='/PautaPuestaMarcha/Ver/10/<?php echo $id;?>';">Página 10</button>
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
    <td colspan="8" ><em>Nota: Para la revisión de los demás datos    operacionales se debe enviar una Trend Data o DataLogger Cense de la prueba    de potencia y una imagen Quantum (tomada en prueba de parrilla camiones    eléctricos).</em></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#333333"  style="color:#fff;padding:10px;"><strong>INSTRUIR    AL CLIENTE</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color:#fff;padding:10px;" ><strong>SI</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color:#fff;padding:10px;"><strong>NO</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color:#fff;padding:10px;"><strong>NO    APLICA</strong></td>
  </tr>
  <tr>
    <td colspan="2"  >1.&nbsp;&nbsp;&nbsp;    Cambio de filtros de combustible y aceite lubricante</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_cambio_filtros_combustible_aceite" id="Ppm_10_cambio_filtros_combustible_aceite_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_cambio_filtros_combustible_aceite" id="Ppm_10_cambio_filtros_combustible_aceite_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_cambio_filtros_combustible_aceite" id="Ppm_10_cambio_filtros_combustible_aceite_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >2.&nbsp;&nbsp;&nbsp;    Cambio de aceite lubricante</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_cambio_aceite_lubicante" id="Ppm_10_cambio_aceite_lubicante_si" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_cambio_aceite_lubicante" id="Ppm_10_cambio_aceite_lubicante_no" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_cambio_aceite_lubicante" id="Ppm_10_cambio_aceite_lubicante_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2"  >3.&nbsp;&nbsp;&nbsp;    Uso de combustible y aceite lubricante apropiados</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_combustible_aceite_apropiado" id="Ppm_10_combustible_aceite_apropiado_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_combustible_aceite_apropiado" id="Ppm_10_combustible_aceite_apropiado_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_combustible_aceite_apropiado" id="Ppm_10_combustible_aceite_apropiado_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >4.&nbsp;&nbsp;&nbsp;    Temperatura de operación</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_temperatura_operacion" id="Ppm_10_temperatura_operacion_si" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_temperatura_operacion" id="Ppm_10_temperatura_operacion_no" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_temperatura_operacion" id="Ppm_10_temperatura_operacion_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2"  >5.&nbsp;&nbsp;&nbsp;    Procedimientos de partida y parada</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_procedimientos_partida_parada" id="Ppm_10_procedimientos_partida_parada_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_procedimientos_partida_parada" id="Ppm_10_procedimientos_partida_parada_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_procedimientos_partida_parada" id="Ppm_10_procedimientos_partida_parada_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >6.&nbsp;&nbsp;&nbsp;    Daños causados por sobre velocidad</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_danos_sobre_velocidad" id="Ppm_10_danos_sobre_velocidad_si" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_danos_sobre_velocidad" id="Ppm_10_danos_sobre_velocidad_no" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_danos_sobre_velocidad" id="Ppm_10_danos_sobre_velocidad_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2"  >7.&nbsp;&nbsp;&nbsp;    Uso de inhibidores de corrosión y anticongelante</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_inhibidores_corrocion_anticongelante" id="Ppm_10_inhibidores_corrocion_anticongelante_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_inhibidores_corrocion_anticongelante" id="Ppm_10_inhibidores_corrocion_anticongelante_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_inhibidores_corrocion_anticongelante" id="Ppm_10_inhibidores_corrocion_anticongelante_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >8.&nbsp;&nbsp;&nbsp;    Uso de aparatos para partida en frio</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_aparatos_partida_frio" id="Ppm_10_aparatos_partida_frio_si" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_aparatos_partida_frio" id="Ppm_10_aparatos_partida_frio_no" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_aparatos_partida_frio" id="Ppm_10_aparatos_partida_frio_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2"  >9.&nbsp;&nbsp;&nbsp;    Mantenimiento de filtrado de aire</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_mantenimiento_filtrado_aire" id="Ppm_10_mantenimiento_filtrado_aire_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_mantenimiento_filtrado_aire" id="Ppm_10_mantenimiento_filtrado_aire_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_mantenimiento_filtrado_aire" id="Ppm_10_mantenimiento_filtrado_aire_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >10.&nbsp;    Mantenimiento de correas</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_mantenimiento_correas" id="Ppm_10_mantenimiento_correas_si" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_mantenimiento_correas" id="Ppm_10_mantenimiento_correas_no" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_mantenimiento_correas" id="Ppm_10_mantenimiento_correas_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2"  >11.&nbsp;    Cobertura de garantía</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_cobertura_garantia" id="Ppm_10_cobertura_garantia_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_cobertura_garantia" id="Ppm_10_cobertura_garantia_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_cobertura_garantia" id="Ppm_10_cobertura_garantia_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >12.&nbsp;    Información del manual del OEM (fabricante equipo)</td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_informacion_manual" id="Ppm_10_informacion_manual_si" value="SI"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_informacion_manual" id="Ppm_10_informacion_manual_no" value="NO"></td>
    <td colspan="2" align="center"><input type="radio" data-role="none" name="Ppm_10_informacion_manual" id="Ppm_10_informacion_manual_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#333333" style="color:#fff;padding:10px;"><strong>Confirme    la siguiente certificación del cliente</strong></td>
    <td colspan="2" align="center" bgcolor="#333333"  style="color:#fff;padding:10px;"><strong>SI</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color:#fff;padding:10px;"><strong>NO</strong></td>
    <td colspan="2" align="center" bgcolor="#333333" style="color:#fff;padding:10px;"><strong>NO    APLICA</strong></td>
  </tr>
  <tr>
    <td colspan="2"  >El cliente ha sido instruido sistemáticamente en ajustes de importancia, operación segura, y cobertura de garantía del motor indicado en este reporte. La inspección detallada anteriormente ha sido realizada y el chequeo de motores marinos ha sido completado (solo en motores marinos).</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_confirma_cliente" id="Ppm_10_confirma_cliente_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_confirma_cliente" id="Ppm_10_confirma_cliente_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_10_confirma_cliente" id="Ppm_10_confirma_cliente_na" value="N/A"></td>
  </tr>
</table>
</div> 
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-actions right">
						<input type="hidden" name="pagina" value="10" />	
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