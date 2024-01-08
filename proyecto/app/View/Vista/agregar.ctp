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
				<form action="" class="horizontal-form" method="post" role="form">
					
					<div class="form-group">
						<div class="row">
							<label for="nombre" class="col-md-2 control-label">Vista</label>
							<div class="col-md-4">
								<input name="data[nombre]" class="form-control" required="required" type="text" id="nombre">
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="controller" class="col-md-2 control-label">Controller</label>
							<div class="col-md-4">
								<input name="data[controller]" class="form-control" required="required" type="text" id="controller">
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="action" class="col-md-2 control-label">Actions</label>
							<div class="col-md-4">
								<input name="data[action]" class="form-control" required="required" type="text" id="action">
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
								<button type="button" class="btn default" onclick="window.location='/Vista';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>