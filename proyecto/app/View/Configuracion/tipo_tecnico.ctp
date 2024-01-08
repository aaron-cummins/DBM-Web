<div class="portlet light">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase">Tipo Técnico</span>
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
										<th>Tipo</th>
										<th>Obligatorio</th>
										<th>Único</th>
									</tr>
								<?php
									foreach( $registros as $registro ) {
										echo "<tr>";
											echo "<td align=\"center\"><input type=\"hidden\" name=\"registro[{$registro['TipoTecnico']['id']}]\" value=\"{$registro['TipoTecnico']['e']}\">";
											echo "{$registro['TipoTecnico']['id']}</td>";
											echo "<td>{$registro['TipoTecnico']['nombre']}</td>";
											echo "<td><input type=\"checkbox\" name=\"obligatorio[{$registro['TipoTecnico']['id']}]\" class=\"form-control\" /></td>";
											echo "<td><input type=\"checkbox\" name=\"unico[{$registro['TipoTecnico']['id']}]\" class=\"form-control\" /></td>";
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