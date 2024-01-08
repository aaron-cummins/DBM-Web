<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Carga masiva diagnóstico</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="/CargaMasiva/Diagnostico" class="horizontal-form" method="post" enctype="multipart/form-data" name="form-file">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="InputFile">Archivo</label>
									<input type="file" id="file" name="data[file]" class="form-control" required="required" />
									<p class="help-block"> Seleccione un archivo formato XLSX con los siguientes campos: Motor, Sección, Sistema, Subsistema, Nº ID, Elemento, Diagnóstico, Condición </p>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/CargaMasiva/Diagnostico';">Cancelar</button>
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
					<span class="caption-subject font-blue-hoki bold uppercase">Carga masiva diagnóstico</span>
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
									<label for="motor_id">Motor</label>
									<select name="motor_id" class="motor_id form-control">
										<option value="">Todos</option>
										<?php foreach($motores as $value){
											echo "<option value=\"{$value["Motor"]["id"]}\">{$value["Motor"]["nombre"]} {$value["TipoEmision"]["nombre"]}</option>";	
										} ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="sistema_id">Sistema</label>
									<select name="sistema_id" class="sistema_id form-control">
										<option value="">Todos</option>
										<?php foreach($sistemas as $value){
											echo "<option value=\"{$value["Sistema"]["id"]}\">{$value["Sistema"]["nombre"]}</option>";	
										} ?>
									</select> 
								</div>
							</div> 
							<div class="col-md-4">
								<div class="form-group">
									<label for="subsistema_id">Subsistema</label>
									<select name="subsistema_id" class="subsistema_id form-control">
										<option value="">Todos</option>
										<?php foreach($subsistemas as $value){
											echo "<option value=\"{$value["Subsistema"]["id"]}\">{$value["Subsistema"]["nombre"]}</option>";	
										} ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="n_id">Nº ID</label>
									<input type="text" name="n_id" class="n_id form-control" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="elemento_id">Elemento</label>
									<select name="elemento_id" class="elemento_id form-control">
										<option value="">Todos</option>
										<?php foreach($elementos as $value){
											echo "<option value=\"{$value["Elemento"]["id"]}\">{$value["Elemento"]["nombre"]}</option>";	
										} ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="diagnostico_id">Diagnóstico</label>
									<select name="diagnostico_id" class="diagnostico_id form-control">
										<option value="">Todos</option>
										<?php foreach($diagnosticos as $value){
											echo "<option value=\"{$value["Diagnostico"]["id"]}\">{$value["Diagnostico"]["nombre"]}</option>";	
										} ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="condicion_id">Condición</label>
									<select name="condicion_id" class="condicion_id form-control">
										<option value="">Todos</option>
										<option value="existe">Existe</option>
										<option value="nuevo">Nuevo</option>
										<option value="actualizar">Actualizar</option>
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
			<span class="caption-subject bold uppercase">Diagnósticos</span>
		</div>
		<div class="tools"></div>
	</div>
	<div class="portlet-body form">
		<form action="/CargaMasiva/Diagnostico" class="horizontal-form" method="post">
			<input type="hidden" name="form-save" value="1" />
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-actions right">
					<button type="button" class="btn default" onclick="window.location='/CargaMasiva/Diagnostico';">Cargar nuevo archivo</button>
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
										<th>Motor</th>
										<th>Sección</th>
										<th>Sistema</th>
										<th>Subsistema</th>
										<th>Nº ID</th>
										<th>Elemento</th>
										<th>Diagnóstico</th>
										<th>Condición</th>
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
										echo "<tr estado_id='{$registro['estado_id']}' motor_id=\"{$registro['motor_id']}\" sistema_id=\"{$registro['sistema_id']}\" subsistema_id=\"{$registro['subsistema_id']}\" diagnostico_id=\"{$registro['diagnostico_id']}\" elemento_id=\"{$registro['elemento_id']}\" condicion_id='".strtolower($registro['condicion'])."' class='data_row'>";
											echo "<td align=\"center\">$i";
											echo "<input type=\"hidden\" name=\"motor_id[$i]\" value=\"{$registro['motor_id']}\" />";
											echo "<input type=\"hidden\" name=\"sistema_id[$i]\" value=\"{$registro['sistema_id']}\" />";
											echo "<input type=\"hidden\" name=\"subsistema_id[$i]\" value=\"{$registro['subsistema_id']}\" />";
											echo "<input type=\"hidden\" name=\"elemento_id[$i]\" value=\"{$registro['elemento_id']}\" />";
											echo "<input type=\"hidden\" name=\"n_id[$i]\" value=\"{$registro['n_id']}\" />";
											echo "<input type=\"hidden\" name=\"diagnostico_id[$i]\" value=\"{$registro['diagnostico_id']}\" />";
											echo "<input type=\"hidden\" name=\"condicion_id[$i]\" value=\"{$registro['condicion']}\" />";
											echo "<input type=\"hidden\" name=\"motor[$i]\" value=\"{$registro['motor']}\" />";
											echo "<input type=\"hidden\" name=\"sistema[$i]\" value=\"{$registro['sistema']}\" />";
											echo "<input type=\"hidden\" name=\"subsistema[$i]\" value=\"{$registro['subsistema']}\" />";
											echo "<input type=\"hidden\" name=\"elemento[$i]\" value=\"{$registro['elemento']}\" />";
											echo "<input type=\"hidden\" name=\"diagnostico[$i]\" value=\"{$registro['diagnostico']}\" />";
											echo "<input type=\"hidden\" name=\"condicion[$i]\" value=\"{$registro['condicion']}\" />";
											echo "</td>";
											echo "<td>{$registro['motor']}</td>";
											echo "<td>{$registro['seccion']}</td>";
											echo "<td>{$registro['sistema']}</td>";
											echo "<td>{$registro['subsistema']}</td>";
											echo "<td>{$registro['n_id']}</td>";
											echo "<td>{$registro['elemento']}</td>";
											echo "<td>{$registro['diagnostico']}</td>";
											echo "<td>{$registro['condicion']}</td>";
											
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
								?>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions right">
					<button type="button" class="btn default" onclick="window.location='/CargaMasiva/Diagnostico';">Cargar nuevo archivo</button>
					<button type="submit" class="btn blue">
						<i class="fa fa-save"></i> Guardar </button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php } ?>