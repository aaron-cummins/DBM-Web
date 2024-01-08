<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Diagnósticos</span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post">
					<div class="form-body">
						<div class="row">
							<div class="mt-checkbox-inline">
							<?php foreach ($diagnosticos as $var) { ?>
								<div class="col-md-3">
									<label class="mt-checkbox">
	                                    <?php if (isset($diagnostico_elemento[$var["Diagnostico"]["id"]]) && $diagnostico_elemento[$var["Diagnostico"]["id"]] == true) { ?>
											<input type="checkbox" name="diagnostico[]" id="<?php echo $var["Diagnostico"]["id"]; ?>" value="<?php echo $var["Diagnostico"]["id"]; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="diagnostico[]" id="<?php echo $var["Diagnostico"]["id"]; ?>" value="<?php echo $var["Diagnostico"]["id"]; ?>" />
										<?php } ?>
	                                    <?php echo $var["Diagnostico"]["nombre"]; ?>
	                                    <span></span>
	                                </label>
	                            </div>
							<?php } ?>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Matrices/Motor';">Cancelar</button>
						<button type="submit" class="btn blue">
							<i class="fa fa-filter"></i> Guardar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
