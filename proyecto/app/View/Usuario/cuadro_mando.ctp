<div class="row">
	<div class="col-md-12">
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Filtro</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post" role="form">
					
					<div class="form-group">
						<div class="row">
							<label for="faena" class="col-md-2 control-label">Faena</label>
							<div class="col-md-4">
								<select class="btn btn-default form-control" name="faena" required="required">
									<option value="">Seleccione una opción</option>
									<?php foreach($faenas as $key => $value) { ?>
									<option value="<?php echo $key;?>" <?php echo $faena_id == $key ? 'selected="selected"':"";?>><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-2">
						</div>
						<div class="col-md-4">
							<div class="form-actions">
								<button type="submit" class="btn blue">
									<i class="fa fa-check"></i> Aceptar</button>
								<button type="button" class="btn default" onclick="window.location='/Usuario';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
<?php
	if (isset($faena_id) && is_numeric($faena_id)) {
?>
<div class="row">
	<div class="col-md-12">
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Cuadro Mando</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<form action="" class="horizontal-form" method="post">
					<input name="cuadro_mando" type="hidden" value="1" />
					<input name="faena" type="hidden" value="<?php echo $faena_id;?>" />
					<div id="table_1_wrapper" class="dataTables_wrapper">
						<div class="form-actions right">
							<button type="submit" class="btn blue">
								<i class="fa fa-save"></i> Guardar </button>
							<button type="button" class="btn default" onclick="window.location='/Usuario/CuadroMando/<?php echo $usuario_id; ?>';">Cancelar</button>
						</div>
						<div class="form-body">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover">
											<tr>
												<th rowspan="2"></th>
												<?php foreach($cargos as $key => $value) { ?>
												<th style="text-align: center;"><div class="rotateNO" ><?php echo $value;?></div></th>
												<?php } ?>
											</tr>
											<tr>
												<?php foreach($cargos as $key => $value) { ?>
												<th style="text-align: center;">
												<?php
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													if(in_array($key,$cargos_permisos)) {
														echo "<input type=\"checkbox\" name=\"cargo[$key]\" value=\"1\" checked=\"checked\" class=\"cm_cargos\" cargo=\"$key\" />";
													} else {
														echo "<input type=\"checkbox\" name=\"cargo[$key]\" value=\"1\" class=\"cm_cargos\" cargo=\"$key\" />";
													}
													echo "<span></span>";
													echo "</label>";
												?>
												</th>
												<?php } ?>
											</tr>
											<?php 
											if (count($vistas) > 0) {
											?>	
												<tr>
													<th colspan="<?php echo count($cargos) + 1;?>">Vistas</th>
												</tr>
											<?php
											}
											?>
											<?php foreach($vistas as $key => $value) { ?>
												<tr>
													<td><?php echo $value;?></td>
													<?php 
													foreach($cargos as $key2 => $value2) {
														echo '<td align="center">';
														echo '<div class="form-group" style="margin-bottom: 0;">';
														echo '<div class="mt-checkbox-inline">';
														echo '<label class="mt-checkbox mt-checkbox-outline">';
														
														$check = false;
														foreach($permisos_global as $key3 => $value3){
															if($value3["PermisoGlobal"]["cargo_id"] == $key2 && $value3["PermisoGlobal"]["vista_id"] == $key) {
																$check = true;
																echo "<input type=\"checkbox\" name=\"vista[$key2][$key]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
															}
														}
														if(!$check) {
															echo "<input type=\"checkbox\" name=\"vista[$key2][$key]\" value=\"1\" class=\"cargo_$key2\" />";
														}
														
														echo "<span></span>";
														echo "</label>";
														echo '</td>';
													} 
													?>
												</tr>
												<?php } ?>
											<tr>
												<th colspan="<?php echo count($cargos) + 1;?>">Roles</th>
											</tr>
											<tr>
												<td>Aprobación DCC</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["rol_id"] == 1) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"rol[$key2][1]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"rol[$key2][1]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>
											<tr>
												<td>Aprobación cliente</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["rol_id"] == 2) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"rol[$key2][2]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"rol[$key2][2]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>
											<tr>
												<td>Validación técnica</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["rol_id"] == 3) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"rol[$key2][3]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"rol[$key2][3]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>
											<tr>
												<td>Auditor NC</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["rol_id"] == 4) {
															$check = true; 
															echo "<input type=\"checkbox\" name=\"rol[$key2][4]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"rol[$key2][4]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>

                                            <tr>
                                                <td>Visualizador</td>
                                                <?php
                                                foreach($cargos as $key2 => $value2) {
                                                    echo '<td align="center">';
                                                    echo '<div class="form-group" style="margin-bottom: 0;">';
                                                    echo '<div class="mt-checkbox-inline">';
                                                    echo '<label class="mt-checkbox mt-checkbox-outline">';

                                                    $check = false;
                                                    foreach($permisos_global as $key => $value){
                                                        if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["rol_id"] == 6) {
                                                            $check = true;
                                                            echo "<input type=\"checkbox\" name=\"rol[$key2][6]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\"/>";
                                                        }
                                                    }
                                                    if(!$check) {
                                                        echo "<input type=\"checkbox\" name=\"rol[$key2][6]\" value=\"1\" class=\"cargo_$key2\"/>";
                                                    }

                                                    echo "<span></span>";
                                                    echo "</label>";
                                                    echo '</td>';
                                                }
                                                ?>
                                            </tr>

											<tr>
												<th colspan="<?php echo count($cargos) + 1;?>">Notificaciones</th>
											</tr>
											<tr>
												<td>Sincronización</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 1) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][1]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][1]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>
											<tr>
												<td>Duplicado</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 2) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][2]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][2]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>
											<!--
											<tr>
												<td>NC</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 3) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][3]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][3]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>
											-->
											<tr>
												<td>Creación Unidad (Nivel usuario)</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 4) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][4]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][4]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>
											<tr>
												<td>Desmontaje (Nivel usuario)</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 5) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][5]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][5]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>
											<tr>
												<td>Montaje (Nivel usuario)</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 6) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][6]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][6]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>
											<tr>
												<td>Aprobación Intervención</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 7) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][7]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][7]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>
											<tr>
												<td>Reporte diario intervenciones pendientes</td>
												<?php 
												foreach($cargos as $key2 => $value2) {
													echo '<td align="center">';
													echo '<div class="form-group" style="margin-bottom: 0;">';
													echo '<div class="mt-checkbox-inline">';
													echo '<label class="mt-checkbox mt-checkbox-outline">';
													
													$check = false;
													foreach($permisos_global as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 8) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][8]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][8]\" value=\"1\" class=\"cargo_$key2\" />";
													}
													
													echo "<span></span>";
													echo "</label>";
													echo '</td>';
												} 
												?>
											</tr>
											<tr>
												<th colspan="<?php echo count($cargos) + 1;?>">DBM Software</th>
											</tr>
											<tr>
												<td>Acceso a DBM Software</td>
												<?php 
													foreach($cargos as $key2 => $value2) {
														echo '<td align="center">';
														echo '<div class="form-group" style="margin-bottom: 0;">';
														echo '<div class="mt-checkbox-inline">';
														echo '<label class="mt-checkbox mt-checkbox-outline">';
														
														$check = false;
														foreach($permisos_global as $key3 => $value3){
															//print_r($value3);
															if($value3["PermisoGlobal"]["cargo_id"] == $key2 && $value3["PermisoGlobal"]["vista_id"] == 9999) {
																//echo "OK";
																$check = true;
																echo "<input type=\"checkbox\" name=\"vista[$key2][9999]\" value=\"1\" checked=\"checked\" class=\"cargo_$key2\" />";
															}
														}
														if(!$check) {
															echo "<input type=\"checkbox\" name=\"vista[$key2][9999]\" value=\"1\" class=\"cargo_$key2\" />";
														}
														
														echo "<span></span>";
														echo "</label>";
														echo '</td>';
													} 
													?>
											
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="form-actions right">
							<button type="submit" class="btn blue">
								<i class="fa fa-save"></i> Guardar </button>
							<button type="button" class="btn default" onclick="window.location='/Usuario/CuadroMando/<?php echo $usuario_id; ?>';">Cancelar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
	}
?>