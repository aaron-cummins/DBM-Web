<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Carga masiva</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="/CargaMasiva/EstadoMotores" class="horizontal-form" method="post" enctype="multipart/form-data" name="form-file">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="InputFile">Archivo</label>
									<input type="file" id="file" name="data[file]" class="form-control" required="required" />
									<p class="help-block"> Seleccione un archivo formato XLSX </p>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/CargaMasiva/EstadoMotores';">Limpiar</button>
						<button type="submit" class="btn blue">
							<i class="fa fa-save"></i> Guardar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
