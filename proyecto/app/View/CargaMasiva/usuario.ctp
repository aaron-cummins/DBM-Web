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
				<form action="/CargaMasiva/Usuario" class="horizontal-form" method="post" enctype="multipart/form-data" name="form-file">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="InputFile">Archivo</label>
									<input type="file" id="file" name="data[file]" class="form-control" required="required" />
									<p class="help-block"> Seleccione un archivo formato XLSX con los siguientes campos: ID Usuario, Rut (clave única), Nombres, Apellidos, Correo, Faena, Cargo, Contraseña </p>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/CargaMasiva/Usuario';">Limpiar</button>
						<button type="submit" class="btn blue">
							<i class="fa fa-filter"></i> Previsualizar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>

<?php if (isset($data) && is_array($data)) { ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Filtros</span>
					<span class="caption-helper">Aplique uno o más filtros</span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="get">
					<div class="form-body">
					<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Faena</label>
									<select class="form-control" name="faena_id" id="faena_id"> 
										<option value="">Todos</option>
										<?php foreach($faenas as $key => $value) { ?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
										<?php }?>
									</select>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Cargo</label>
									<select class="form-control" name="cargo_id" id="cargo_id"> 
										<option value="">Todos</option>
										<?php foreach($cargos as $key => $value) { ?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
										<?php }?>
									</select>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Estado</label>
										<select class="form-control" name="estado_id" id="estado_id" > 
										<option value="">Todos</option>
										<option value="1">Nuevo</option>
										<option value="2">Ambos</option>
										<option value="3">Inválido</option>
										<option value="4">Activo</option>
										<option value="5">Inactivo</option>
									</select>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default filtro-carga-masiva-usuario-limpiar">Limpiar</button>
						<button type="button" class="btn blue filtro-carga-masiva-usuario">
							<i class="fa fa-filter"></i> Aplicar</button>
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
			<span class="caption-subject bold uppercase">Usuarios</span>
		</div>
		<div class="tools"> </div>
	</div>
	<div class="portlet-body form">
		<form action="/CargaMasiva/Usuario" class="horizontal-form" method="post">
			<input type="hidden" name="form-save" value="1" />
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<th align="center">#</th>
										<th>Nombres</th>
										<th>Apellidos</th>
										<th>Rut</th>
										<th>U</th>
										<th>Correo electrónico</th>
										<th>Contraseña</th>
										<th>Faena</th>
										<th>Cargo</th>
										<th>Tipo</th>
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
										$style = "";
										if($registro['permiso_id'] == '-1') {
											if($registro['valido']=='1'){
												$style = "background-color: yellow;";
											}
										}
										$estado = 0;
										if($registro['permiso_id'] == '-1') {
											if($registro['valido']=='1'){
												$estado = 1;
											} else {
												$estado = 3;
											}
										} else {
											if($registro['valido']=='1'){
												$estado = 2;
											} else {
												$estado = 3;
											}
										}
										echo "<tr style='$style' estado_id='$estado' cargo_id='{$registro['cargo_id']}' faena_id='{$registro['faena_id']}' class='file_row'>";
											echo "<td align=\"center\">$i";
											echo "<input type=\"hidden\" name=\"nombres[$i]\" value=\"{$registro['nombres']}\" />";
											echo "<input type=\"hidden\" name=\"apellidos[$i]\" value=\"{$registro['apellidos']}\" />";
											echo "<input type=\"hidden\" name=\"usuario[$i]\" value=\"{$registro['usuario']}\" />";
											echo "<input type=\"hidden\" name=\"ucode[$i]\" value=\"{$registro['ucode']}\" />";
											echo "<input type=\"hidden\" name=\"correo_electronico[$i]\" value=\"{$registro['correo_electronico']}\" />";
											echo "<input type=\"hidden\" name=\"pin[$i]\" value=\"{$registro['pin']}\" />";
											echo "<input type=\"hidden\" name=\"faena_id[$i]\" value=\"{$registro['faena_id']}\" />";
											echo "<input type=\"hidden\" name=\"faena[$i]\" value=\"{$registro['faena']}\" />";
											echo "<input type=\"hidden\" name=\"usuario_id[$i]\" value=\"{$registro['usuario_id']}\" />";
											echo "<input type=\"hidden\" name=\"cargo_id[$i]\" value=\"{$registro['cargo_id']}\" />";
											echo "<input type=\"hidden\" name=\"cargo[$i]\" value=\"{$registro['cargo']}\" />";
											echo "<input type=\"hidden\" name=\"cargo[$i]\" value=\"{$registro['cargo']}\" />";
											echo "</td>";
											echo "<td>{$registro['nombres']}</td>";
											echo "<td>{$registro['apellidos']}</td>";
											echo "<td>{$registro['usuario']}</td>";
											echo "<td>{$registro['ucode']}</td>";
											echo "<td>{$registro['correo_electronico']}</td>";
											echo "<td>{$registro['pin']}</td>";
											echo "<td>{$registro['faena']}</td>";
											echo "<td>{$registro['cargo']}</td>";
											if($registro['permiso_id'] == '-1') {
												if($registro['valido']=='1'){
													echo "<td>Nuevo</td>";
												} else {
													echo "<td>Inválido</td>";
												}
											} else {
												if($registro['valido']=='1'){
													echo "<td>Ambos<input type=\"hidden\" name=\"registro_[$i]\" value=\"1\"/>";
													echo "<input type=\"hidden\" name=\"permiso_id[]\" value=\"{$registro['permiso_id']}\"/></td>";
												} else {
													echo "<td>Inválido</td>";
												}
											}
											echo "<td align=\"center\" style=\"width: 60px;\">";
											if($registro['valido']=='1' && $registro['permiso_id'] == '-1'){
												echo '<div class="form-group" style="margin-bottom: 0;">';
												echo '<div class="mt-checkbox-inline">';
												echo '<label class="mt-checkbox mt-checkbox-outline">';
												echo "<input type=\"checkbox\" name=\"registro[$i]\" class=\"check-option\" value=\"1\"/>";
												echo "<span></span>";
												echo "</label>";
												echo '</div>';
												echo '</div>';
											}
											echo "</td>";
										echo "</tr>";
										$i++;
									}
									
									// Permisos existentes
									foreach($permisos as $registro) {
										$style = "";
										/*if($registro['permiso_id'] == '-1') {
											if($registro['valido']=='1'){
												$style = "background-color: yellow;";
											}
										}
										$estado = 0;
										if($registro['permiso_id'] == '-1') {
											if($registro['valido']=='1'){
												$estado = 1;
											} else {
												$estado = 3;
											}
										} else {
											if($registro['valido']=='1'){
												$estado = 2;
											} else {
												$estado = 3;
											}
										}*/
										$estado = "-1";
										
										if ($registro["Usuario"]['e'] == "0" || $registro["PermisoUsuario"]['e'] == "0") {
											$estado = "5";
										}
										
										if ($registro["Usuario"]['e'] == "1" && $registro["PermisoUsuario"]['e'] == "1") {
											$estado = "4";
										}
										
										$registro["Cargo"]['nombre'] = strtoupper($registro["Cargo"]['nombre']);
										$registro["Faena"]['nombre'] = strtoupper($registro["Faena"]['nombre']);
										echo "<tr style='$style' estado_id='$estado' cargo_id='{$registro["Cargo"]['id']}' faena_id='{$registro["Faena"]['id']}' class='file_row'>";
											echo "<td align=\"center\">$i";
											echo "</td>";
											echo "<td>{$registro["Usuario"]['nombres']}</td>";
											echo "<td>{$registro["Usuario"]['apellidos']}</td>";
											echo "<td>{$registro["Usuario"]['usuario']}</td>";
											echo "<td>{$registro["Usuario"]['u']}</td>";
											echo "<td>{$registro["Usuario"]['correo_electronico']}</td>";
											echo "<td></td>";
											echo "<td>{$registro["Faena"]['nombre']}</td>";
											echo "<td>{$registro["Cargo"]['nombre']}</td>";
											/*if($registro['permiso_id'] == '-1') {
												if($registro['valido']=='1'){
													echo "<td>Nuevo</td>";
												} else {
													echo "<td>Inválido</td>";
												}
											} else { 
												if($registro['valido']=='1'){
													echo "<td>Ambos<input type=\"hidden\" name=\"registro_[$i]\" value=\"1\"/></td>";
												} else {
													echo "<td>Inválido</td>";
												}
											}*/
											
											if ($registro["Usuario"]['e'] == "0" || $registro["PermisoUsuario"]['e'] == "0") {
												echo "<td>Inactivo</td>";
											}elseif ($registro["Usuario"]['e'] == "1" && $registro["PermisoUsuario"]['e'] == "1") {
												echo "<td>Activo</td>";
											}else{
												echo "<td></td>";
											}
											
											echo "<td align=\"center\" style=\"width: 60px;\">";
											echo '<div class="form-group" style="margin-bottom: 0;">';
											echo '<div class="mt-checkbox-inline">';
											echo '<label class="mt-checkbox mt-checkbox-outline">';
											if ($estado == "4"){
												echo "<input type=\"checkbox\" name=\"permiso_id[]\" class=\"check-option\" value=\"{$registro['PermisoUsuario']["id"]}\" checked=\"checked\" />";
											} else {
												echo "<input type=\"checkbox\" name=\"permiso_id[]\" class=\"check-option\" value=\"{$registro['PermisoUsuario']["id"]}\" />";
											}
											echo "<span></span>";
											echo "</label>";
											echo '</div>';
											echo '</div>';
											echo "</td>";
										echo "</tr>";
										$i++;
									}
								?>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions right">
					<input type="hidden" name="permiso_id[]" value="-1" />
					<input type="hidden" name="permiso_id[]" value="-2" />
					<button type="submit" class="btn blue">
						<i class="fa fa-save"></i> Guardar </button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php } ?>