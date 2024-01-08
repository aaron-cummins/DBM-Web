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
						<button type="button" class="btn btn-sm green" onclick="window.location='/PautaPuestaMarcha/Ver/3/<?php echo $id;?>';">Página 3</button>
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
    <td ></td>
    <td ></td>
    <td colspan="3" align="center" style="background-color:#333;color:#fff; padding:10px;" ><strong>MEDIDA</strong></td>
    <td colspan="3" align="center" style="background-color:#333;color:#fff; padding:10px;"><strong>NO APLICA</strong></td>
  </tr>
  <tr >
    <td colspan="2" >Verificar    comunicación con ECM Cense, registrando el/los código(s) de calibración</td>
    <td colspan="3" ><input type="text" name="Ppm_03_comunicacion_ecm_cense" id="Ppm_03_comunicacion_ecm_cense"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_comunicacion_ecm_cense_na" id="Ppm_03_comunicacion_ecm_cense_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Verificar comunicación con ECM Quantum,    registrando el/los código(s) de calibración</td>
    <td colspan="3" ><input type="text" name="Ppm_03_comunicacion_ecm_quantum" id="Ppm_03_comunicacion_ecm_quantum"></td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_comunicacion_ecm_quantum_na" id="Ppm_03_comunicacion_ecm_quantum_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Juego    axial del cigüeñal</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_ciguenal" id="Ppm_03_juego_axial_ciguenal"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_ciguenal_na" id="Ppm_03_juego_axial_ciguenal_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo HP-LBF</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_hp_lbf" id="Ppm_03_juego_axial_turbo_hp_lbf"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_hp_lbf_na" id="Ppm_03_juego_axial_turbo_hp_lbf_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Juego axial del turbo HP-LBM</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_hp_lbm" id="Ppm_03_juego_axial_turbo_hp_lbm"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_hp_lbm_na" id="Ppm_03_juego_axial_turbo_hp_lbm_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo HP-LBR</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_hp_lbr" id="Ppm_03_juego_axial_turbo_hp_lbr"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_hp_lbr_na" id="Ppm_03_juego_axial_turbo_hp_lbr_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Juego axial del turbo HP-RBF</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_hp_rbf" id="Ppm_03_juego_axial_turbo_hp_rbf"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_hp_rbf_na" id="Ppm_03_juego_axial_turbo_hp_rbf_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo HP-RBM</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_hp_rbm" id="Ppm_03_juego_axial_turbo_hp_rbm"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_hp_rbm_na" id="Ppm_03_juego_axial_turbo_hp_rbm_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Juego axial del turbo HP-RBR</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_hp_rbr" id="Ppm_03_juego_axial_turbo_hp_rbr"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_hp_rbr_na" id="Ppm_03_juego_axial_turbo_hp_rb_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo LP-LBF</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_lp_lbf" id="Ppm_03_juego_axial_turbo_lp_lbf"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_lp_lbf_na" id="Ppm_03_juego_axial_turbo_lp_lb_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Juego axial del turbo LP-LBM</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_lp_lbm" id="Ppm_03_juego_axial_turbo_lp_lbm"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_lp_lbm_na" id="Ppm_03_juego_axial_turbo_lp_lbm_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo LP-LBR</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_lp_lbr" id="Ppm_03_juego_axial_turbo_lp_lbr"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_lp_lbr_na" id="Ppm_03_juego_axial_turbo_lp_lbr_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Juego axial del turbo LP-RBF</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_lp_rbf" id="Ppm_03_juego_axial_turbo_lp_rbf"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_lp_rbf_na" id="Ppm_03_juego_axial_turbo_lp_rbf_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Juego axial del turbo LP-RBM</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_lp_rbm" id="Ppm_03_juego_axial_turbo_lp_rbm"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_lp_rbm_na" id="Ppm_03_juego_axial_turbo_lp_rbm_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Juego axial del turbo LP-RBR</td>
    <td colspan="2" ><input type="text" name="Ppm_03_juego_axial_turbo_lp_rbr" id="Ppm_03_juego_axial_turbo_lp_rbr"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_juego_axial_turbo_lp_rbr_na" id="Ppm_03_juego_axial_turbo_lp_rbr_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Tensión correa bomba de refrigerante</td>
    <td colspan="2" ><input type="text" name="Ppm_03_tension_correa_bomba_refrigerante" id="Ppm_03_tension_correa_bomba_refrigerante"></td>
    <td width="95">Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_tension_correa_bomba_refrigerante_na" id="Ppm_03_tension_correa_bomba_refrigerante_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Tensión    correa de alternador</td>
    <td colspan="2" ><input type="text" name="Ppm_03_tension_correa_alternador" id="Ppm_03_tension_correa_alternador"></td>
    <td width="95">Lb    Inch</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_tension_correa_alternador_na" id="Ppm_03_tension_correa_alternador_na"></td>
  </tr>
  <tr>
    <td colspan="2" >Tensión    correa de ventilador</td>
    <td colspan="2" ><input type="text" name="Ppm_03_tension_correa_ventilador" id="Ppm_03_tension_correa_ventilador"></td>
    <td width="95">Lb    ft</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_tension_correa_ventilador_na" id="Ppm_03_tension_correa_ventilador_na"></td>
  </tr>
  <tr >
    <td colspan="2" >Presión    tapa radiador</td>
    <td colspan="2" ><input type="text" name="Ppm_03_presion_tapa_radiador" id="Ppm_03_presion_tapa_radiador"></td>
    <td width="95">???</td>
    <td colspan="3" align="center"><input type="checkbox" data-role="none" name="Ppm_03_presion_tapa_radiador_na" id="Ppm_03_presion_tapa_radiador_na"></td>
  </tr>

</table>
</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-actions right">
						<input type="hidden" name="pagina" value="03" />	
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