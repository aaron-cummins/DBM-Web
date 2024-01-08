<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Carga masiva backlog Web (con elementos)</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="/CargaMasiva/BacklogWeb" class="horizontal-form" method="post" enctype="multipart/form-data" name="form-file">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="InputFile">Archivo</label>
									<input type="file" id="file" name="data[file]" class="form-control" required="required" />
									<p class="help-block"> Seleccione un archivo formato XLSX con los siguientes campos: Faena, Flota, Equipo, Criticidad, Responsable, Categoría Síntoma, Síntoma, Sistema, Subsistema, Pos. subsistema, ID elemento, Elemento, Pos. elemento, Diagnóstico, Part number, Comentario, Tiempo Estimado en minutos, Lugar de creación </p>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Backlog/';">Volver a Administración de Backlogs</button>
						<button type="button" class="btn default" onclick="window.location='/CargaMasiva/BacklogWeb';">Cancelar</button>
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
			<span class="caption-subject bold uppercase">Backlogs Web (con elementos)</span>
		</div>
		<div class="tools"></div>
	</div>
	<div class="portlet-body form">
		<form id="cmbacklogs" action="/CargaMasiva/BacklogWeb" class="horizontal-form" method="post">
			<input type="hidden" name="form-save" value="1" />
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-actions right">
					<button type="button" class="btn default" onclick="window.location='/CargaMasiva/BacklogWeb';">Cargar nuevo archivo</button>
					<a class="btn blue tooltips" data-toggle="modal" href="#envio_registro" data-container="body" data-placement="top" data-original-title="Carga backlogs"><i class="fa fa-save"></i> Guardar </a>
                                        
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
										<th>Categoría Síntoma</th>
										<th>Síntoma</th>
										<th>Sistema</th>
										<th>Subsistema</th>
										<th>Pos. subsistema </th>
										<th>ID elemento</th>
										<th>Elemento</th>
										<th>Pos. elemento</th>
										<th>Diagnostico</th>
										<th>Part number</th>
										<th>Tiempo Estimado</th>
										<th>Comentario</th>
                                                                                <th>Creación</th>
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
											echo "<input type=\"hidden\" name=\"categoria_id[$i]\" value=\"{$registro['categoria_id']}\" />";
											echo "<input type=\"hidden\" name=\"sintoma_id[$i]\" value=\"{$registro['sintoma_id']}\" />";
                                                                                        echo "<input type=\"hidden\" name=\"sintoma[$i]\" value=\"{$registro['sintoma']}\" />";
											
											echo "<input type=\"hidden\" name=\"sistema_id[$i]\" value=\"{$registro['sistema_id']}\" />";
                                                                                        echo "<input type=\"hidden\" name=\"sistema[$i]\" value=\"{$registro['sistema']}\" />";
											echo "<input type=\"hidden\" name=\"subsistema_id[$i]\" value=\"{$registro['subsistema_id']}\" />";
                                                                                        echo "<input type=\"hidden\" name=\"subsistema[$i]\" value=\"{$registro['subsistema']}\" />";
											echo "<input type=\"hidden\" name=\"pos_subsistema_id[$i]\" value=\"{$registro['pos_subsistema_id']}\" />";
											echo "<input type=\"hidden\" name=\"id_elemento[$i]\" value=\"{$registro['id_elemento']}\" />";
											echo "<input type=\"hidden\" name=\"elemento_id[$i]\" value=\"{$registro['elemento_id']}\" />";
                                                                                        echo "<input type=\"hidden\" name=\"elemento[$i]\" value=\"{$registro['elemento']}\" />";
											echo "<input type=\"hidden\" name=\"pos_elemento_id[$i]\" value=\"{$registro['pos_elemento_id']}\" />";
											echo "<input type=\"hidden\" name=\"diagnostico_id[$i]\" value=\"{$registro['diagnostico_id']}\" />";
											echo "<input type=\"hidden\" name=\"part_number[$i]\" value=\"{$registro['part_number']}\" />";
											
											echo "<input type=\"hidden\" name=\"tiempo[$i]\" value=\"{$registro['tiempo']}\" />";
											echo "<input type=\"hidden\" name=\"comentario[$i]\" value=\"{$registro['comentario']}\" />";
                                                                                        echo "<input type=\"hidden\" name=\"id_creacion[$i]\" value=\"{$registro['creacion_id']}\" />";
											echo "</td>";
											
											echo "<td>{$registro['faena']}</td>";
											echo "<td>{$registro['flota']}</td>";
											echo "<td>{$registro['equipo']}</td>";
											echo "<td>{$registro['criticidad']}</td>";
											echo "<td>{$registro['responsable']}</td>";
											echo "<td>{$registro['categoria']}</td>";
											echo "<td>{$registro['sintoma']}</td>";

											echo "<td>{$registro['sistema']}</td>";
											echo "<td>{$registro['subsistema']}</td>";
											echo "<td>{$registro['pos_subsistema']}</td>";
											echo "<td>{$registro['id_elemento']}</td>";
											echo "<td>{$registro['elemento']}</td>";
											echo "<td>{$registro['pos_elemento']}</td>";
											echo "<td>{$registro['diagnostico']}</td>";
											echo "<td>{$registro['part_number']}</td>";

											echo "<td>{$registro['tiempo']}</td>";
											echo "<td>{$registro['comentario']}</td>";
											echo "<td>{$registro['creacion']}</td>";

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
                                        <input type="hidden" name="envia_correo" class="form-control" id="envia_correo" value=""/>
                                        <input type="hidden" name="destinatarios" class="form-control" id="destinatarios" value=""/>

					<button type="button" class="btn default" onclick="window.location='/CargaMasiva/BacklogWeb';">Cargar nuevo archivo</button>
					
                                        <a class="btn blue tooltips" data-toggle="modal" href="#envio_registro" data-container="body" data-placement="top" data-original-title="Carga backlogs"><i class="fa fa-save"></i> Guardar </a>
                                        
    
                                        <div id="envio_registro" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
                                                <div class="modal-body">
                                                <p> Desea enviar email con registro de backlogs ingresados?</p>
                                                <p> <select name="envia_correo_" class="form-control" id="envia_correo_">
                                                        <option value="0">No</option>
                                                        <option value="1">Si</option>
                                                    </select>
                                                </p>
                                                <p> Ingrese emails separados por "coma" (,)</p>
                                                <p> <input type="text" name="destinatarios_" class="form-control" id="destinatarios_" /> </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" data-dismiss="modal" onclick="" class="btn btn-outline red">Cancelar</button>
                                                <button type="submit" class="btn green btn-submitbklg">Guardar</button>
                                            </div>
                                        </div>
                                        <!--<button type="submit" class="btn blue"><i class="fa fa-save"></i> Guardar </button>-->
				</div>

                                
                                
			</div>
		</form>
	</div>
</div>

<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script typr="text/javascript">

$(".btn-submitbklg").click(function(){
    if($("#envia_correo_").val() != ''){
        var ec = $("#envia_correo_").val();
        var des = $("#destinatarios_").val();
        
        var destinatarios = document.getElementById("destinatarios");
        destinatarios.value = des;
        
        $("#envia_correo").val(ec);
        //$("#destinatarios").val(des);
        $("#cmbacklogs").submit();
    }else{
        $("#cmbacklogs").submit();
    }
});

</script>

<?php } ?>