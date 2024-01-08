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
						<button type="button" class="btn btn-sm green" onclick="window.location='/PautaPuestaMarcha/Ver/7/<?php echo $id;?>';">Página 7</button>
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
    <td align="center" bgcolor="#333333" style="color: #fff; padding: 10px;" ><strong>DATOS</strong></td>
    <td  align="center" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>NO APLICA</strong></td>
    </tr> 
  <tr>
    <td rowspan="4"  >LP-RBF</td>
    <td width="1100"  >Fabricante</td>
    <td   ><input type="text" name="Ppm_07_lp_rbf_cpl" id="Ppm_07_lp_rbf_cpl"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_07_lp_rbf_cpl_na" id="Ppm_07_lp_rbf_cpl_na"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="Ppm_07_lp_rbf_cpl" id="Ppm_07_lp_rbf_cpl"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_07_lp_rbf_cpl_na" id="Ppm_07_lp_rbf_cpl_na"></td>
  </tr>
  <tr>
    <td  >N/P</td>
    <td  ><input type="text" name="Ppm_07_lp_rbf_np" id="Ppm_07_lp_rbf_np"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_07_lp_rbf_np_na" id="Ppm_07_lp_rbf_np_na"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="Ppm_07_lp_rbf_serie" id="Ppm_07_lp_rbf_serie"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_07_lp_rbf_serie_na" id="Ppm_07_lp_rbf_serie_na"></td>
  </tr>
  <tr>
    <td rowspan="4" >LP-RBM</td>
    <td  >Fabricante</td>
    <td  ><input type="text" name="Ppm_07_lp_rbm_fabricante" id="Ppm_07_lp_rbm_fabricante"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_07_lp_rbm_fabricante_na" id="Ppm_07_lp_rbm_fabricante_na"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="Ppm_07_lp_rbm_modelo" id="Ppm_07_lp_rbm_modelo"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_07_lp_rbm_modelo_na" id="Ppm_07_lp_rbm_modelo_na"></td>
  </tr>
  <tr>
    <td  >N/P</td>
    <td  ><input type="text" name="Ppm_07_lp_rbm_np" id="Ppm_07_lp_rbm_np"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_07_lp_rbm_np_na" id="Ppm_07_lp_rbm_np_na"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="Ppm_07_lp_rbm_serie" id="Ppm_07_lp_rbm_serie"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_07_lp_rbm_serie_na" id="Ppm_07_lp_rbm_serie_na"></td>
  </tr>
  <tr>
    <td rowspan="4"  >LP-RBR</td>
    <td  >Fabricante</td>
    <td  ><input type="text" name="Ppm_07_lp_rbr_fabricante" id="Ppm_07_lp_rbr_fabricante"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_07_lp_rbr_fabricante_na" id="Ppm_07_lp_rbr_fabricante_na"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="Ppm_07_lp_rbr_modelo" id="Ppm_07_lp_rbr_modelo"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_07_lp_rbr_modelo_na" id="Ppm_07_lp_rbr_modelo_na"></td>
  </tr>
  <tr>
    <td  >N/P</td>
    <td  ><input type="text" name="Ppm_07_lp_rbr_np" id="Ppm_07_lp_rbr_np"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_07_lp_rbr_np_na" id="Ppm_07_lp_rbr_np_na"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="Ppm_07_lp_rbr_serie" id="Ppm_07_lp_rbr_serie"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_07_lp_rbr_serie_na" id="Ppm_07_lp_rbr_serie_na"></td>
  </tr>
  <tr>
    <td rowspan="6" >Sistema Eléctrico</td>
    <td  >N/P    ECM QUANTUM</td>
    <td  ><input type="text" name="Ppm_07_sistema_electrico_np_ecm" id="Ppm_07_sistema_electrico_np_ecm"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_07_sistema_electrico_np_ecm_na" id="Ppm_07_sistema_electrico_np_ecm_na"></td>
  </tr>
  <tr>
    <td >N/S ECM Quantum</td>
    <td ><input type="text" name="Ppm_07_sistema_electrico_ns_ecm" id="Ppm_07_sistema_electrico_ns_ecm"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_07_sistema_electrico_ns_ecm_na" id="Ppm_07_sistema_electrico_ns_ecm_na"></td>
  </tr>
  <tr>
    <td  >Código de calibración</td>
    <td  ><input type="text" name="Ppm_07_sistema_electrico_cod_calibracion_quantum" id="Ppm_07_sistema_electrico_cod_calibracion_quantum"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_07_sistema_electrico_cod_calibracion_quantum_na" id="Ppm_07_sistema_electrico_cod_calibracion_quantum_na"></td>
  </tr>
  <tr>
    <td >N/P ECM Cense</td>
    <td ><input type="text" name="Ppm_07_sistema_electrico_np_cense" id="Ppm_07_sistema_electrico_np_cense"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_07_sistema_electrico_np_cense_na" id="Ppm_07_sistema_electrico_np_cense_na"></td>
  </tr>
  <tr>
    <td  >N/S ECM Cense</td>
    <td  ><input type="text" name="Ppm_07_sistema_electrico_ns_cense" id="Ppm_07_sistema_electrico_ns_cense"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_07_sistema_electrico_ns_cense_na" id="Ppm_07_sistema_electrico_ns_cense_na"></td>
  </tr>
  <tr>
    <td >Código de calibración</td>
    <td ><input type="text" name="Ppm_07_sistema_electrico_cod_calibracion_cense" id="Ppm_07_sistema_electrico_cod_calibracion_cense"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_07_sistema_electrico_cod_calibracion_cense_na" id="Ppm_07_sistema_electrico_cod_calibracion_cense_na"></td>
  </tr>
</table>
</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-actions right">
						<input type="hidden" name="pagina" value="07" />	
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