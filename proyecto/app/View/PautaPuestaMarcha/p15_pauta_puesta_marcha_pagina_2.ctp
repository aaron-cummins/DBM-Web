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
					<button type="button" class="btn btn-sm green" onclick="window.location='/PautaPuestaMarcha/Ver/2/<?php echo $id;?>';">Página 2</button>
					<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/3/<?php echo $id;?>';">Página 3</button>
					<button type="button" class="btn btn-sm default" onclick="window.location='/PautaPuestaMarcha/Ver/4/<?php echo $id;?>';">Página 4</button>
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
    <td width="254" ></td>
    <td width="1100" ></td>
    <td width="126" ></td>
    <td width="38"></td>
    <td width="95"></td>
    <td width="114"></td>
    <td width="76"></td>
    <td width="38"></td>
  </tr>
  <tr>
    <td colspan="8" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>INSPECCIÓN DEL MOTOR</strong></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#CCCCCC" ><strong>Chequear lo siguiente antes de echar a correr el motor, ajuste si es necesario</strong></td>
    <td colspan="2" align="center" bgcolor="#CCCCCC" ><strong>SI</strong></td>
    <td colspan="2" align="center" bgcolor="#CCCCCC"><strong>NO</strong></td>
    <td colspan="2" align="center" bgcolor="#CCCCCC"><strong>NO    APLICA</strong></td>
  </tr>
  <tr>
    <td colspan="2"  >Marca de comienzo de garantía en placa de datos motor</td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_marca_comienzo_garantia" id="Ppm_02_marca_comienzo_garantia_si" value="SI"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_marca_comienzo_garantia" id="Ppm_02_marca_comienzo_garantia_no" value="NO"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_marca_comienzo_garantia" id="Ppm_02_marca_comienzo_garantia_na" value="N/A"></td>
  </tr>
  <tr>
    <td colspan="2" >Montaje de motor y accesorios</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_montaje_motor_accesorios" id="Ppm_02_montaje_motor_accesorios_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_montaje_motor_accesorios" id="Ppm_02_montaje_motor_accesorios_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_montaje_motor_accesorios" id="Ppm_02_montaje_motor_accesorios_na" value="N/A"></td>
    </tr>
  <tr >
    <td colspan="2" >Fugas de combustible, lubricante, aceite o refrigerante</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_fugas" id="Ppm_02_fugas_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_fugas" id="Ppm_02_fugas_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_fugas" id="Ppm_02_fugas_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2" >Instalación de sistema de combustible y nivel de combustible</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_combustible" id="Ppm_02_instalacion_sistema_combustible_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_combustible" id="Ppm_02_instalacion_sistema_combustible_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_combustible" id="Ppm_02_instalacion_sistema_combustible_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2"  >Instalación de sistema de lubricación y nivel de aceite</td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_lubricacion" id="Ppm_02_instalacion_sistema_lubricacion_si" value="SI"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_lubricacion" id="Ppm_02_instalacion_sistema_lubricacion_no" value="NO"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_lubricacion" id="Ppm_02_instalacion_sistema_lubricacion_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2" >Instalación de sistema de pre-lubricación</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_prelubricacion" id="Ppm_02_instalacion_sistema_prelubricacion_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_prelubricacion" id="Ppm_02_instalacion_sistema_prelubricacion_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_prelubricacion" id="Ppm_02_instalacion_sistema_prelubricacion_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2"  >Instalación de sistema Centinel y nivel de estanque Centinel</td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_centinel" id="Ppm_02_instalacion_sistema_centinel_si" value="SI"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_centinel" id="Ppm_02_instalacion_sistema_centinel_no" value="NO"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_centinel" id="Ppm_02_instalacion_sistema_centinel_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2" >Instalación de sistema de refrigeración y nivel de refrigerante</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_refrigeracion" id="Ppm_02_instalacion_sistema_refrigeracion_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_refrigeracion" id="Ppm_02_instalacion_sistema_refrigeracion_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_refrigeracion" id="Ppm_02_instalacion_sistema_refrigeracion_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2"  >Nivel de concentración de DCA y anticongelante en refrigerante</td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_nivel_concentracion_dca" id="Ppm_02_nivel_concentracion_dca_si" value="SI"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_nivel_concentracion_dca" id="Ppm_02_nivel_concentracion_dca_no" value="NO"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_nivel_concentracion_dca" id="Ppm_02_nivel_concentracion_dca_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2" >Instalación de sistema eléctrico y nivel de electrolito de batería</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_electrico" id="Ppm_02_instalacion_sistema_electrico_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_electrico" id="Ppm_02_instalacion_sistema_electrico_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_instalacion_sistema_electrico" id="Ppm_02_instalacion_sistema_electrico_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2"  >Filtros de aire</td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_filtros_aire" id="Ppm_02_filtros_aire_si" value="SI"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_filtros_aire" id="Ppm_02_filtros_aire_no" value="NO"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_filtros_aire" id="Ppm_02_filtros_aire_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2" >Respiraderos de motor</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_respiraderos_motor" id="Ppm_02_respiraderos_motor_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_respiraderos_motor" id="Ppm_02_respiraderos_motor_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_respiraderos_motor" id="Ppm_02_respiraderos_motor_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2"  >Tensión de correas (todas)</td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_tension_correas" id="Ppm_02_tension_correas_si" value="SI"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_tension_correas" id="Ppm_02_tension_correas_no" value="NO"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_tension_correas" id="Ppm_02_tension_correas_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2" >Luces de cabina del ECM funcionan apropiadamente</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_luces_cabina_ecm" id="Ppm_02_luces_cabina_ecm_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_luces_cabina_ecm" id="Ppm_02_luces_cabina_ecm_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_luces_cabina_ecm" id="Ppm_02_luces_cabina_ecm_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2"  >No hay códigos de falla activos (Quantum o Cense)</td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_codigo_falla_activo" id="Ppm_02_codigo_falla_activo_si" value="SI"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_codigo_falla_activo" id="Ppm_02_codigo_falla_activo_no" value="NO"></td>
    <td colspan="2" align="center"  ><input type="radio" data-role="none" name="Ppm_02_codigo_falla_activo" id="Ppm_02_codigo_falla_activo_na" value="N/A"></td>
    </tr>
  <tr>
    <td colspan="2" >Limpiar códigos de falla inactivos (Quantum o Cense)</td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_limpiar_codigo_falla_inactivo" id="Ppm_02_limpiar_codigo_falla_inactivo_si" value="SI"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_limpiar_codigo_falla_inactivo" id="Ppm_02_limpiar_codigo_falla_inactivo_no" value="NO"></td>
    <td colspan="2" align="center" ><input type="radio" data-role="none" name="Ppm_02_limpiar_codigo_falla_inactivo" id="Ppm_02_limpiar_codigo_falla_inactivo_na" value="N/A"></td>
    </tr>
</table>
</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-actions right">
						<input type="hidden" name="pagina" value="02" />
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