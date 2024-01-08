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
						<button type="button" class="btn btn-sm green" onclick="window.location='/PautaPuestaMarcha/Ver/6/<?php echo $id;?>';">Página 6</button>
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
    <td align="center" bgcolor="#333333" style="color: #fff; padding: 10px;" ><strong>DATOS</strong></td>
    <td align="center" bgcolor="#333333" style="color: #fff; padding: 10px;"><strong>NO APLICA</strong></td>
  </tr>  <tr>
    <td rowspan="4"  >HP-RBM</td>
    <td width="1100"  >Fabricante</td>
    <td width="259"  ><input type="text" name="Ppm_06_hp_rbm_cpl" id="Ppm_06_hp_rbm_cpl"></td>
    <td width="228" align="center" ><input type="checkbox" data-role="none" name="Ppm_06_hp_rbm_cpl_na" id="Ppm_06_hp_rbm_cpl_na"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="Ppm_06_hp_rbm_cod" id="Ppm_06_hp_rbm_cod"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_06_hp_rbm_cod_na" id="Ppm_06_hp_rbm_cod_na"></td>
  </tr>
  <tr>
    <td  >N/P</td>
    <td  ><input type="text" name="Ppm_06_hp_rbm_np" id="Ppm_06_hp_rbm_np"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_06_hp_rbm_np_na" id="Ppm_06_hp_rbm_np_na"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="Ppm_06_hp_rbm_serie" id="Ppm_06_hp_rbm_serie"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_06_hp_rbm_serie_na" id="Ppm_06_hp_rbm_serie_na"></td>
  </tr>
  <tr>
    <td rowspan="4" >HP-RBR</td>
    <td  >Fabricante</td>
    <td  ><input type="text" name="Ppm_06_hp_rbr_fabricante" id="Ppm_06_hp_rbr_fabricante"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_06_hp_rbr_fabricante_na" id="Ppm_06_hp_rbr_fabricante_na"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="Ppm_06_hp_rbr_modelo" id="Ppm_06_hp_rbr_modelo"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_06_hp_rbr_modelo_na" id="Ppm_06_hp_rbr_modelo_na"></td>
  </tr>
  <tr>
    <td  >N/P</td>
    <td  ><input type="text" name="Ppm_06_hp_rbr_np" id="Ppm_06_hp_rbr_np"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_06_hp_rbr_np_na" id="Ppm_06_hp_rbr_np_na"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="Ppm_06_hp_rbr_serie" id="Ppm_06_hp_rbr_serie"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_06_hp_rbr_serie_na" id="Ppm_06_hp_rbr_serie_na"></td>
  </tr>
  <tr>
    <td rowspan="4"  >LP-LBF</td>
    <td  >Fabricante</td>
    <td  ><input type="text" name="Ppm_06_lp_lbf_fabricante" id="Ppm_06_lp_lbf_fabricante"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_06_lp_lbf_fabricante_na" id="Ppm_06_lp_lbf_fabricante_na"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="Ppm_06_lp_lbf_modelo" id="Ppm_06_lp_lbf_modelo"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_06_lp_lbf_modelo_na" id="Ppm_06_lp_lbf_modelo_na"></td>
  </tr>
  <tr>
    <td  >N/P</td>
    <td  ><input type="text" name="Ppm_06_lp_lbf_np" id="Ppm_06_lp_lbf_np"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_06_lp_lbf_np_na" id="Ppm_06_lp_lbf_np_na"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="Ppm_06_lp_lbf_serie" id="Ppm_06_lp_lbf_serie"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_06_lp_lbf_serie_na" id="Ppm_06_lp_lbf_serie_na"></td>
  </tr>
  <tr>
    <td rowspan="4" >LP-LBM</td>
    <td  >Fabricante</td>
    <td  ><input type="text" name="Ppm_06_lp_lbm_fabricante" id="Ppm_06_lp_lbm_fabricante"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_06_lp_lbm_fabricante_na" id="Ppm_06_lp_lbm_fabricante_na"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="Ppm_06_lp_lbm_modelo" id="Ppm_06_lp_lbm_modelo"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_06_lp_lbm_modelo_na" id="Ppm_06_lp_lbm_modelo_na"></td>
  </tr>
  <tr>
    <td  >N/P</td>
    <td  ><input type="text" name="Ppm_06_lp_lbm_np" id="Ppm_06_lp_lbm_np"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_06_lp_lbm_np_na" id="Ppm_06_lp_lbm_np_na"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="Ppm_06_lp_lbm_serie" id="Ppm_06_lp_lbm_serie"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_06_lp_lbm_serie_na" id="Ppm_06_lp_lbm_serie_na"></td>
  </tr>
  <tr>
    <td rowspan="4"  >LP-LBR</td>
    <td  >Fabricante</td>
    <td  ><input type="text" name="Ppm_06_lp_lbr_fabricante" id="Ppm_06_lp_lbr_fabricante"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_06_lp_lbr_fabricante_na" id="Ppm_06_lp_lbr_fabricante_na"></td>
  </tr>
  <tr>
    <td >Modelo</td>
    <td ><input type="text" name="Ppm_06_lp_lbr_modelo" id="Ppm_06_lp_lbr_modelo"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_06_lp_lbr_modelo_na" id="Ppm_06_lp_lbr_modelo_na"></td>
  </tr>
  <tr>
    <td  >N/P</td>
    <td  ><input type="text" name="Ppm_06_lp_lbr_np" id="Ppm_06_lp_lbr_np"></td>
    <td align="center" ><input type="checkbox" data-role="none" name="Ppm_06_lp_lbr_np_na" id="Ppm_06_lp_lbr_np_na"></td>
  </tr>
  <tr>
    <td >Serie</td>
    <td ><input type="text" name="Ppm_06_lp_lbr_serie" id="Ppm_06_lp_lbr_serie"></td>
    <td align="center"><input type="checkbox" data-role="none" name="Ppm_06_lp_lbr_serie_na" id="Ppm_06_lp_lbr_serie_na"></td>
  </tr>
</table>
</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-actions right">
						<input type="hidden" name="pagina" value="06" />	
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