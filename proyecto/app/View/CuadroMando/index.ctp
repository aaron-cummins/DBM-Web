<div class="row">
	<div class="col-md-12">
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Permisos globales</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<form action="" class="horizontal-form" method="post">
					<div id="table_1_wrapper" class="dataTables_wrapper">
						<div class="form-actions right">
							<button type="submit" class="btn blue">
								<i class="fa fa-save"></i> Guardar </button>
							<button type="button" class="btn default" onclick="window.location='/CuadroMando';">Cancelar</button>
						</div>
						<div class="form-body">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover">
											<tr>
												<th></th>
												<?php foreach($cargos as $key => $value) { ?>
												<th style="text-align: center;"><div class="rotateNO" ><?php echo $value;?></div></th>
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
														foreach($permisos as $key3 => $value3){
															if($value3["PermisoGlobal"]["cargo_id"] == $key2 && $value3["PermisoGlobal"]["vista_id"] == $key) {
																$check = true;
																echo "<input type=\"checkbox\" name=\"vista[$key2][$key]\" value=\"1\" checked=\"checked\" />";
															}
														}
														if(!$check) {
															echo "<input type=\"checkbox\" name=\"vista[$key2][$key]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["rol_id"] == 1) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"rol[$key2][1]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"rol[$key2][1]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["rol_id"] == 2) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"rol[$key2][2]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"rol[$key2][2]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["rol_id"] == 3) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"rol[$key2][3]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"rol[$key2][3]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["rol_id"] == 4) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"rol[$key2][4]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"rol[$key2][4]\" value=\"1\" />";
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
                                                    foreach($permisos as $key => $value){
                                                        if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["rol_id"] == 6) {
                                                            $check = true;
                                                            echo "<input type=\"checkbox\" name=\"rol[$key2][6]\" value=\"1\" checked=\"checked\" />";
                                                        }
                                                    }
                                                    if(!$check) {
                                                        echo "<input type=\"checkbox\" name=\"rol[$key2][6]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 1) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][1]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][1]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 2) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][2]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][2]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 3) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][3]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][3]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 4) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][4]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][4]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 5) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][5]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][5]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 6) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][6]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][6]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 7) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][7]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][7]\" value=\"1\" />";
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
													foreach($permisos as $key => $value){
														if($value["PermisoGlobal"]["cargo_id"] == $key2 && $value["PermisoGlobal"]["correo_id"] == 8) {
															$check = true;
															echo "<input type=\"checkbox\" name=\"correo[$key2][8]\" value=\"1\" checked=\"checked\" />";
														}
													}
													if(!$check) {
														echo "<input type=\"checkbox\" name=\"correo[$key2][8]\" value=\"1\" />";
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
														foreach($permisos as $key3 => $value3){
															if($value3["PermisoGlobal"]["cargo_id"] == $key2 && $value3["PermisoGlobal"]["vista_id"] == 9999) {
																$check = true;
																echo "<input type=\"checkbox\" name=\"vista[$key2][9999]\" value=\"1\" checked=\"checked\" />";
															}
														}
														if(!$check) {
															echo "<input type=\"checkbox\" name=\"vista[$key2][9999]\" value=\"1\" />";
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
							<button type="button" class="btn default" onclick="window.location='/CuadroMando';">Cancelar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>