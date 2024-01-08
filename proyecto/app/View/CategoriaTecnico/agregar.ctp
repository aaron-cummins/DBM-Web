<div class="row">
	<div class="col-md-12">
	
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Agregar registro</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body">
				<!-- BEGIN FORM-->
				<form action="/CategoriaTecnico/Agregar" class="horizontal-form" method="post" role="form">
					
					<div class="form-group">
						<div class="row">
							<label for="nombre" class="col-md-2 control-label">Categoría Técnico</label>
							<div class="col-md-4">
								<input name="data[nombre]" class="form-control" required="required" type="text" id="nombre">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<label for="requerido" class="col-md-2 control-label">Requerido</label>
							<div class="col-md-4">
								<div class="mt-checkbox-inline">
                                    <label class="mt-checkbox">
                                        <input type="checkbox" id="requerido" name="data[requerido]" value="1">
                                        <span></span>
                                    </label>
                                </div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<label for="unico" class="col-md-2 control-label">Único</label>
							<div class="col-md-4">
								<div class="mt-checkbox-inline">
                                    <label class="mt-checkbox">
                                        <input type="checkbox" id="unico" name="data[unico]" value="1">
                                        <span></span>
                                    </label>
                                </div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<label for="unico" class="col-md-2 control-label">Firma</label>
							<div class="col-md-4">
								<div class="mt-checkbox-inline">
                                    <label class="mt-checkbox">
                                        <input type="checkbox" id="firma" name="data[firma]" value="1">
                                        <span></span>
                                    </label>
                                </div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						</div>
						<div class="col-md-4">
							<div class="form-actions">
								<button type="submit" class="btn blue">
									<i class="fa fa-save"></i> Guardar</button>
								<button type="button" class="btn default" onclick="window.location='/CategoriaTecnico';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>