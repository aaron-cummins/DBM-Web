<div class="portlet light">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase">Configuración reporte diario intervenciones pendientes</span>
		</div>
		<div class="tools"> </div>
	</div>
	<div class="portlet-body form">
		<form action="" class="horizontal-form" method="post">
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<th style="width: 50px;">Cód.</th>
										<th>Faena</th>
										<th>Hora</th>
										<th>Minuto</th>
										<th>Período</th>
									</tr>
								<?php
									foreach( $registros as $registro ) {
										$faena_id = $registro['Faena']['id'];
										echo "<tr>";
											echo "<td align=\"center\">";
											echo "\t"."<input type=\"hidden\" name=\"registro[{$registro['Faena']['id']}]\" value=\"{$registro['Faena']['e']}\">";
											echo "\t"."<input type=\"hidden\" name=\"ultimo_envio[{$registro['Faena']['id']}]\" value=\"{$configuraciones[$faena_id]["ultimo_envio"]}\">";
											echo "\t"."{$registro['Faena']['id']}";
											echo "</td>";
											echo "<td>{$registro['Faena']['nombre']}</td>";
											echo "<td>";
											echo "\t"."<select name=\"hora[{$registro['Faena']['id']}]\" class=\"form-control\">";
											echo "\t\t"."<option></option>";
											echo "\t\t"."<option value=\"01\" ".($configuraciones[$faena_id]["hora"] == '01' ? "selected=\"selected\"" : "").">01</option>";
											echo "\t\t"."<option value=\"02\" ".($configuraciones[$faena_id]["hora"] == '02' ? "selected=\"selected\"" : "").">02</option>";
											echo "\t\t"."<option value=\"03\" ".($configuraciones[$faena_id]["hora"] == '03' ? "selected=\"selected\"" : "").">03</option>";
											echo "\t\t"."<option value=\"04\" ".($configuraciones[$faena_id]["hora"] == '04' ? "selected=\"selected\"" : "").">04</option>";
											echo "\t\t"."<option value=\"05\" ".($configuraciones[$faena_id]["hora"] == '05' ? "selected=\"selected\"" : "").">05</option>";
											echo "\t\t"."<option value=\"06\" ".($configuraciones[$faena_id]["hora"] == '06' ? "selected=\"selected\"" : "").">06</option>";
											echo "\t\t"."<option value=\"07\" ".($configuraciones[$faena_id]["hora"] == '07' ? "selected=\"selected\"" : "").">07</option>";
											echo "\t\t"."<option value=\"08\" ".($configuraciones[$faena_id]["hora"] == '08' ? "selected=\"selected\"" : "").">08</option>";
											echo "\t\t"."<option value=\"09\" ".($configuraciones[$faena_id]["hora"] == '09' ? "selected=\"selected\"" : "").">09</option>";
											echo "\t\t"."<option value=\"10\" ".($configuraciones[$faena_id]["hora"] == '10' ? "selected=\"selected\"" : "").">10</option>";
											echo "\t\t"."<option value=\"11\" ".($configuraciones[$faena_id]["hora"] == '11' ? "selected=\"selected\"" : "").">11</option>";
											echo "\t\t"."<option value=\"12\" ".($configuraciones[$faena_id]["hora"] == '12' ? "selected=\"selected\"" : "").">12</option>";
											echo "\t"."</select>";
											echo "</td>";
											echo "<td>";
											echo "\t"."<select name=\"minuto[{$registro['Faena']['id']}]\" class=\"form-control\">";
											echo "\t\t"."<option></option>";
											
											for ($i = 0; $i < 61; $i++){
												$minuto = str_pad($i, 2, "0", STR_PAD_LEFT);
												echo "\t\t"."<option value=\"$minuto\" ".($configuraciones[$faena_id]["minuto"] == $minuto ? "selected=\"selected\"" : "").">$minuto</option>";
											}
											
											echo "\t"."</select>";
											echo "</td>";
											echo "<td>";
											echo "\t"."<select name=\"periodo[{$registro['Faena']['id']}]\" class=\"form-control\">";
											echo "\t\t"."<option></option>";
											echo "\t\t"."<option value=\"AM\" ".($configuraciones[$faena_id]["periodo"] == 'AM' ? "selected=\"selected\"" : "").">AM</option>";
											echo "\t\t"."<option value=\"PM\" ".($configuraciones[$faena_id]["periodo"] == 'PM' ? "selected=\"selected\"" : "").">PM</option>";
											echo "\t"."</select>";
											echo "</td>";
											
										echo "</tr>";
									}
								?>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions right">
					<button type="submit" class="btn blue">
						<i class="fa fa-save"></i> Guardar </button>
				</div>
			</div>
		</form>
	</div>
</div>