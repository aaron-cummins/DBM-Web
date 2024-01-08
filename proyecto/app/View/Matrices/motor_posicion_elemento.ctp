<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Posiciones Elemento</span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post">
					<div class="form-body">
						<div class="row">
							<div class="mt-checkbox-inline">
							<?php foreach ($posiciones_elementos as $var) { ?>
							<div class="col-md-3">
								<label class="mt-checkbox">
									<?php if (isset($posiciones[$var["Posiciones_Elemento"]["id"]]) && $posiciones[$var["Posiciones_Elemento"]["id"]] == true) { ?>
										<input type="checkbox" name="posicion[]" id="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" value="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" checked="checked" />
									<?php } else { ?>
										<input type="checkbox" name="posicion[]" id="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" value="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" />
									<?php } ?>
                                    <?php echo $var["Posiciones_Elemento"]["nombre"]; ?>
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
