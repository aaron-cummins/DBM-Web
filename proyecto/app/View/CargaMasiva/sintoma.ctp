<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Carga masiva síntoma</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="/CargaMasiva/Sintoma" class="horizontal-form" method="post" enctype="multipart/form-data" name="form-file">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="InputFile">Archivo</label>
									<input type="file" id="file" name="data[file]" class="form-control" required="required" />
									<p class="help-block"> Seleccione un archivo formato XLSX con los siguientes campos: Motivo llamado, Categoría síntoma, Síntoma </p>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/CargaMasiva/Sintoma';">Cancelar</button>
						<button type="submit" class="btn blue">
							<i class="fa fa-file"></i> Previsualizar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
<?php if ($is_post == 'true') { ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Carga masiva síntoma</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post" name="form-file">
					<div class="form-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="motor_id">Motivo de llamado</label>
									<select name="motivo_id" class="motivo_id form-control">
										<option value="">Todos</option>
										<?php foreach($motivos as $value){
											echo "<option value=\"{$value["MotivoLlamado"]["id"]}\">{$value["MotivoLlamado"]["nombre"]}</option>";	
										} ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="sistema_id">Categoría síntoma</label>
									<select name="categoria_id" class="categoria_id form-control">
										<option value="">Todos</option>
										<?php foreach($categorias as $value){
											echo "<option value=\"{$value["SintomaCategoria"]["id"]}\">{$value["SintomaCategoria"]["nombre"]}</option>";	
										} ?>
									</select> 
								</div>
							</div> 
							<div class="col-md-4">
								<div class="form-group">
									<label for="subsistema_id">Síntoma</label>
									<select name="sintoma_id" class="sintoma_id form-control">
										<option value="">Todos</option>
										<?php foreach($sintomas as $value){
											echo "<option value=\"{$value["Sintoma"]["id"]}\">{$value["Sintoma"]["nombre"]}</option>";	
										} ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="estado_id">Estado</label>
									<select name="estado_id" class="estado_id form-control">
										<option value="">Todos</option>
										<option value="1">Ambos</option>
										<option value="2">Nuevo</option>
										<option value="3">Inválido</option>
									</select>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default carga-filtrar-limpiar">
							<i class="fa fa-filter"></i> Limpiar filtros</button>
						<button type="button" class="btn blue carga-filtrar">
							<i class="fa fa-filter"></i> Filtrar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase">Síntomas</span>
		</div>
		<div class="tools"></div>
	</div>
	<div class="portlet-body form">
		<form action="/CargaMasiva/Sintoma" class="horizontal-form" method="post">
			<input type="hidden" name="form-save" value="1" />
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-actions right">
					<button type="button" class="btn default" onclick="window.location='/CargaMasiva/Sintoma';">Cargar nuevo archivo</button>
					<button type="submit" class="btn blue">
						<i class="fa fa-save"></i> Guardar </button>
				</div>
				<div class="form-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<th align="center">#</th>
										<th>Motivo de llamado</th>
										<th>Categoría síntoma</th>
										<th>Síntoma</th>
										<th>Estado</th>
										<th>
										<?php
											echo '<div class="form-group" style="margin-bottom: 0;">';
											echo '<div class="mt-checkbox-inline">';
											echo '<label class="mt-checkbox mt-checkbox-outline">';
											echo "<input type=\"checkbox\" class=\"seleccion-masiva\" />";
											echo "<span></span>";
											echo "</label>";
											echo '</div>';
											echo '</div>';
										?>
										</tr>
								<?php
									$i = 1;
									foreach($data as $registro) {
										echo "<tr estado_id='{$registro['estado_id']}' motivo_id=\"{$registro['motivo_id']}\" categoria_id=\"{$registro['categoria_id']}\" sintoma_id=\"{$registro['sintoma_id']}\" class='data_row'>";
											echo "<td align=\"center\">$i";
											echo "<input type=\"hidden\" name=\"motivo_id[$i]\" value=\"{$registro['motivo_id']}\" />";
											echo "<input type=\"hidden\" name=\"motivo[$i]\" value=\"{$registro['motivo_llamado']}\" />";
											echo "<input type=\"hidden\" name=\"categoria_id[$i]\" value=\"{$registro['categoria_id']}\" />";
											echo "<input type=\"hidden\" name=\"categoria[$i]\" value=\"{$registro['categoria_sintoma']}\" />";
											echo "<input type=\"hidden\" name=\"sintoma_id[$i]\" value=\"{$registro['sintoma_id']}\" />";
											echo "<input type=\"hidden\" name=\"sintoma[$i]\" value=\"{$registro['sintoma']}\" />";
											echo "<input type=\"hidden\" name=\"codigo[$i]\" value=\"{$registro['codigo']}\" />";
											echo "</td>";
											echo "<td>{$registro['motivo_llamado']}</td>";
											echo "<td>{$registro['categoria_sintoma']}</td>";
											if($registro['codigo'] != "") {
												echo "<td>{$registro['codigo']} - {$registro['sintoma']}</td>";
											}else{
												echo "<td>{$registro['sintoma']}</td>";
											}
											
											if($registro['estado_id']=='3'){
												echo "<td>Inválido</td>";
											} elseif($registro['estado_id']=='1') {
												echo "<td>Ambos<input type=\"hidden\" name=\"registro[$i]\" value=\"1\"/></td>";
											} elseif($registro['estado_id']=='2') {
												echo "<td>Nuevo<input type=\"hidden\" name=\"registro[$i]\" value=\"1\"/></td>";
											}
											echo "<td align=\"center\" style=\"width: 60px;\">";
											if($registro['estado_id']=='2'){
												echo '<div class="form-group" style="margin-bottom: 0;">';
												echo '<div class="mt-checkbox-inline">';
												echo '<label class="mt-checkbox mt-checkbox-outline">';
												echo "<input type=\"checkbox\" name=\"seleccion[$i]\" class=\"check-option\" value=\"1\"/>";
												echo "<span></span>";
												echo "</label>";
												echo '</div>';
												echo '</div>';
											}
											echo "</td>";
										echo "</tr>";
										$i++;
									}
									
									// Sintomas existentes
									/*foreach($matriz as $registro) {
										$style = "";
										echo "<tr style='$style' class='file_row'>";
											echo "<td align=\"center\">{$registro["MotivoCategoriaSintoma"]['id']}";
											echo "</td>";
											echo "<td>{$registro["Motivo"]['nombre']}</td>";
											echo "<td>{$registro["Categoria"]['nombre']}</td>";
											if ($registro["Sintoma"]['codigo'] != null and intval($registro["Sintoma"]['codigo']) > 0){
												echo "<td>FC {$registro["Sintoma"]['codigo']} - {$registro["Sintoma"]['nombre']}</td>";
											} else {
												echo "<td>{$registro["Sintoma"]['nombre']}</td>";
											}
											
											
											if ($registro["MotivoCategoriaSintoma"]['e'] == "0") {
												echo "<td>Inactivo</td>";
											}elseif ($registro["MotivoCategoriaSintoma"]['e'] == "1") {
												echo "<td>Activo</td>";
											}else{
												echo "<td></td>";
											}
											
											echo "<td align=\"center\" style=\"width: 60px;\">";
											echo '<div class="form-group" style="margin-bottom: 0;">';
											echo '<div class="mt-checkbox-inline">';
											echo '<label class="mt-checkbox mt-checkbox-outline">';
											//if ($estado == "4"){
												//echo "<input type=\"checkbox\" name=\"permiso_id[]\" class=\"check-option\" value=\"{$registro['PermisoUsuario']["id"]}\" checked=\"checked\" />";
										//	} else {
												//echo "<input type=\"checkbox\" name=\"permiso_id[]\" class=\"check-option\" value=\"{$registro['PermisoUsuario']["id"]}\" />";
										//	}
											echo "<span></span>";
											echo "</label>";
											echo '</div>';
											echo '</div>';
											echo "</td>";
										echo "</tr>";
										$i++;
									}*/
								?>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions right">
					<button type="button" class="btn default" onclick="window.location='/CargaMasiva/Sintoma';">Cargar nuevo archivo</button>
					<button type="submit" class="btn blue">
						<i class="fa fa-save"></i> Guardar </button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php } ?>