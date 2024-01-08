<?php

$paginator = $this->Paginator;
$inicio = $limit * ($paginator->current() - 1) + 1;
$termino = $inicio + $limit - 1;
if ($termino > $paginator->params()['count']) {
	$termino = $paginator->params()['count'];
}

App::import('Controller', 'Utilidades');
$app = new UtilidadesController();
?>


<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Filtros  </span>
					<span class="caption-helper">Aplique uno o más filtros </span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">

							<div class="col-md-2">
								<div class="form-group">
									<label>Motor</label>
									<select class="form-control" name="motor_id" id="motor_id_">
										<option value="" selected="selected">Todos</option>
										<?php foreach ($motores as $key => $value) { ?>
											<option value="<?php echo $value["Motor"]["id"]; ?>" <?php echo $motor == $value["Motor"]["id"] ? 'selected="selected"':''; ?> ><?php echo $value["Motor"]["nombre"]; ?> <?php echo $value["TipoAdmision"]["nombre"]; ?> <?php echo $value["TipoEmision"]["nombre"]; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Sistema</label>
									<select class="form-control" name="sistema_id" id="sistema_id" >
										<option value="">Todos</option>
										<?php foreach($sistemas as $key => $value) { ?>
											<option value="<?php echo $key;?>" <?php echo $sistema == $key ? "selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
										<?php }?>
									</select>
								</div>
							</div>


							<div class="col-md-2">
								<div class="form-group">
									<label>Categoría síntoma</label>
									<select class="form-control" name="categoria_sintoma_id" id="categoria_sintoma_id">
										<option value="">Seleccione una opción</option>
										<?php foreach ($categoria_sintoma as $key => $value) { ?>
											<option value="<?php echo $key; ?>" <?php echo $cat_sintoma == $key ? "selected=\"selected\"" : ""; ?>><?php echo $value; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<!--<div class="col-md-2 ">
								<div class="form-group">
									<label>Síntoma</label>
									<select class="form-control" name="sintoma_id" id="sintoma_id">
										<option value="">Seleccione una opción</option>
										<?php foreach ($sintomas as $key => $value) { ?>
											<?php if ($value['Sintoma']["codigo"] != "0" && $value['Sintoma']["codigo"] != "") { ?>
												<option value="<?php echo $value['Sintoma']["id"]; ?>" <?php echo $sintoma == $key ? "selected=\"selected\"" : ""; ?> sintoma_categoria_id="<?php echo $value['Sintoma']["sintoma_categoria_id"]; ?>" style="display: none;"><?php echo $value['Sintoma']["codigo"]; ?> - <?php echo $value['Sintoma']["nombre"]; ?></option>
											<?php } else { ?>
												<option value="<?php echo $value['Sintoma']["id"]; ?>" <?php echo $sintoma == $key ? "selected=\"selected\"" : ""; ?> sintoma_categoria_id="<?php echo $value['Sintoma']["sintoma_categoria_id"]; ?>" style="display: none;"><?php echo $value['Sintoma']["nombre"]; ?></option>

											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>-->
							<div class="col-md-2 ">
								<div class="form-group">
									<label>Folio</label>
									<input type="text" name="folio" id="folio" class="form-control" value="<?php echo @$folio;?>"> </div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Resultados por página</label>
									<select class="form-control" name="limit">
										<option value="10" <?php echo @$limit == 10 ? "selected=\"selected\"" : ""; ?>>10</option>
										<option value="25" <?php echo @$limit == 25 ? "selected=\"selected\"" : ""; ?>>25</option>
										<option value="50" <?php echo @$limit == 50 ? "selected=\"selected\"" : ""; ?>>50</option>
										<option value="100" <?php echo @$limit == 100 ? "selected=\"selected\"" : ""; ?>>100</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/PlanAccion';">Limpiar</button>
						<button type="submit" class="btn blue">
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
			<span class="caption-subject bold uppercase">Planes de acción</span>
		</div>
		<div class="tools"> </div>
	</div>
	<div class="portlet-body form">
		<form action="" class="horizontal-form" method="post">
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-body">
					<div class="row">
						<div class="col-md-12">
							<div class="dt-buttons">
								<a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="/PlanAccion/crear"><i class="fa fa-plus"></i> <span>Nuevo Plan de acción</span></a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<?php echo "<th>" . $paginator->sort('PlanAccion.id', 'Folio') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('PlanAccion.nombre', 'Nombre') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Motor.nombre', 'Motor') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Sistema.nombre', 'Sistema') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Subsistema.nombre', 'Subsistema') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Elemento.nombre', 'Elemento') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('SintomaCategoria.nombre', 'Categoría Sintoma') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Sintoma.nombre', 'Sintoma') . "</th>"; ?>
										<td></td>
										<th>Acciones</th>

									</tr>
									<?php
									$i = 1;
									foreach( $registros as $registro ) {
										echo "<tr>";
										echo "<td align=\"center\"><input type=\"hidden\" name=\"registro[{$registro['PlanAccion']['id']}]\" value=\"{$registro['PlanAccion']['e']}\">";
										echo "{$registro['PlanAccion']['id']}</td>";
										echo "<td>{$registro['PlanAccion']['nombre']}</td>";
										echo "<td>" . $registro['Motor']['nombre'] . ' ' . $registro['TipoAdmision']['nombre']. ' ' . $registro['TipoEmision']['nombre'] . "</td>";
										echo "<td>{$registro['Sistema']['nombre']}</td>";
										echo "<td>{$registro['Subsistema']['nombre']}</td>";
										echo "<td>{$registro['Elemento']['nombre']}</td>";
										echo "<td>{$registro['SintomaCategoria']['nombre']}</td>";
										echo "<td>{$registro['Sintoma']['nombre']}</td>";

										echo "<td align=\"center\" style=\"width: 60px;\">";
										echo '<div class="form-group" style="margin-bottom: 0;">';
										echo '<div class="mt-checkbox-inline">';
										echo '<label class="mt-checkbox mt-checkbox-outline">';
										if($registro['PlanAccion']['e'] == '1') {
											echo "<input type=\"checkbox\" class=\"check-option\" name=\"estado[{$registro['PlanAccion']['id']}]\" value=\"1\" checked=\"checked\" />";
										} else {
											echo "<input type=\"checkbox\" class=\"check-option\" name=\"estado[{$registro['PlanAccion']['id']}]\" value=\"1\" />";
										}
										echo "<span></span>";
										echo "</label>";
										echo '</div>';
										echo '</div>';
										echo "</td>";

										echo "<td>";
										echo '<a class="btn btn-sm blue tooltips" href="/PlanAccion/editar/'.$registro['PlanAccion']['id'].'" data-container="body" data-placement="top" data-original-title="Editar Plan acción"><i class="fa fa-edit"></i>  </a>';
										echo "</td>";

										$i++;
										echo "</tr>";
									}
									?>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5 col-sm-12">
							<input type="hidden" class="trabajos" value="<?php echo ($i - 1);?>" />
							<div class="dataTables_info" id="table_1_info" role="status" aria-live="polite">Mostrando del <?php echo $inicio;?> a <?php echo $termino;?> de <?php echo $paginator->params()['count'];?> registros</div>
						</div>
						<div class="col-md-7 col-sm-12">
							<?php
							if (count($registros) > 0) {
								echo '<div class="dataTables_paginate paging_bootstrap_number" id="table_1_paginate">
										<ul class="pagination pull-right" style="visibility: visible;">';
								echo $paginator->first("<i class=\"fa fa-angle-double-left\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "prev", 'tag' => 'li', 'escape' => false));
								if($paginator->hasPrev()){
									echo $paginator->prev("<i class=\"fa fa-angle-left\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "prev", 'tag' => 'li', 'escape' => false));
								}
								echo $paginator->numbers(array('modulus' => 5, "separator" => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'a'));
								if($paginator->hasNext()){
									echo $paginator->next("<i class=\"fa fa-angle-right\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "next", 'tag' => 'li', 'escape' => false));
								}
								echo $paginator->last("<i class=\"fa fa-angle-double-right\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "next", 'tag' => 'li', 'escape' => false));
								echo "</ul></div>";
							}
							?>
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