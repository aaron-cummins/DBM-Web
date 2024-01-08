<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Carga masiva backlog Specto</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="/CargaMasiva/BacklogSpecto" class="horizontal-form" method="post" enctype="multipart/form-data" name="form-file">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="InputFile">Archivo</label>
									<input type="file" id="file" name="data[file]" class="form-control" required="required" />
									<p class="help-block"> Seleccione un archivo formato XLSX con los siguientes campos: Faena, Flota, Equipo, Criticidad, Responsable, Sistema, Categoría Síntoma, Síntoma, Comentario, Tiempo Estimado en minutos </p>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Backlog/';">Volver a Administración de Backlogs</button>
						<button type="button" class="btn default" onclick="window.location='/CargaMasiva/BacklogSpecto';">Cancelar</button>
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
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase">Backlogs Specto</span>
		</div>
		<div class="tools"></div>
	</div>
	<div class="portlet-body form">
		<form action="/CargaMasiva/BacklogSpecto" class="horizontal-form" method="post">
			<input type="hidden" name="form-save" value="1" />
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-actions right">
					<button type="button" class="btn default" onclick="window.location='/CargaMasiva/BacklogSpecto';">Cargar nuevo archivo</button>
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
										<th>Faena</th>
										<th>Flota</th>
										<th>Equipo</th>
										<th>Criticidad</th>
										<th>Responsable</th>
										<th>Sistema</th>
										<th>Categoría Síntoma</th>
										<th>Síntoma</th>
										<th>Tiempo Estimado</th>
										<th>Comentario</th>
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
										echo "<tr class='data_row'>";
											echo "<td align=\"center\">$i";
											echo "<input type=\"hidden\" name=\"faena_id[$i]\" value=\"{$registro['faena_id']}\" />";
											echo "<input type=\"hidden\" name=\"faena[$i]\" value=\"{$registro['faena']}\" />";
											echo "<input type=\"hidden\" name=\"flota_id[$i]\" value=\"{$registro['flota_id']}\" />";
											echo "<input type=\"hidden\" name=\"flota[$i]\" value=\"{$registro['flota']}\" />";
											echo "<input type=\"hidden\" name=\"equipo_id[$i]\" value=\"{$registro['equipo_id']}\" />";
											echo "<input type=\"hidden\" name=\"equipo[$i]\" value=\"{$registro['equipo']}\" />";
											echo "<input type=\"hidden\" name=\"criticidad_id[$i]\" value=\"{$registro['criticidad_id']}\" />";
											echo "<input type=\"hidden\" name=\"criticidad[$i]\" value=\"{$registro['criticidad']}\" />";
											echo "<input type=\"hidden\" name=\"responsable_id[$i]\" value=\"{$registro['responsable_id']}\" />";
											echo "<input type=\"hidden\" name=\"responsable[$i]\" value=\"{$registro['responsable']}\" />";
											echo "<input type=\"hidden\" name=\"sistema_id[$i]\" value=\"{$registro['sistema_id']}\" />";
											echo "<input type=\"hidden\" name=\"sistema[$i]\" value=\"{$registro['sistema']}\" />";
											echo "<input type=\"hidden\" name=\"categoria[$i]\" value=\"{$registro['categoria']}\" />";
											echo "<input type=\"hidden\" name=\"categoria_id[$i]\" value=\"{$registro['categoria_id']}\" />";
											echo "<input type=\"hidden\" name=\"sintoma_id[$i]\" value=\"{$registro['sintoma_id']}\" />";
											echo "<input type=\"hidden\" name=\"sintoma[$i]\" value=\"{$registro['sintoma']}\" />";
											echo "<input type=\"hidden\" name=\"tiempo[$i]\" value=\"{$registro['tiempo']}\" />";
											echo "<input type=\"hidden\" name=\"comentario[$i]\" value=\"{$registro['comentario']}\" />";
											echo "</td>";
											echo "<td>{$registro['faena']}</td>";
											echo "<td>{$registro['flota']}</td>";
											echo "<td>{$registro['equipo']}</td>";
											echo "<td>{$registro['criticidad']}</td>";
											echo "<td>{$registro['responsable']}</td>";
											echo "<td>{$registro['sistema']}</td>";
											echo "<td>{$registro['categoria']}</td>";
											echo "<td>{$registro['sintoma']}</td>";
											echo "<td>{$registro['tiempo']}</td>";
											echo "<td>{$registro['comentario']}</td>";
											
											echo "<td align=\"center\" style=\"width: 60px;\">";
											if($registro['estado'] == 1) {
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
					<button type="button" class="btn default" onclick="window.location='/CargaMasiva/BacklogSpecto';">Cargar nuevo archivo</button>
					<button type="submit" class="btn blue">
						<i class="fa fa-save"></i> Guardar </button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php } ?>