<?php
	$paginator = $this->Paginator;
	$inicio = $limit * ($paginator->current() - 1) + 1;
	$termino = $inicio + $limit - 1;
	if ($termino > $paginator->params()['count']) {
		$termino = $paginator->params()['count'];
	}
	
?>
<?php if(empty($registros)) { ?>
<div class="alert alert-warning">
	<strong>Aviso!</strong> No hay unidades pendientes de aprobación
</div>

<?php } else { ?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase">Unidades Pendientes</span>
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
										<th>Flota</th>
										<th>Unidad</th>
										<th>Aplicación</th>
										<th>OEM</th>
										<th>Modelo equipo</th>
										<th>Nª serie equipo</th>
										<th>Estado equipo</th>
										<th>Motor</th>
										<th>Tipo admisión</th>
										<th>Tipo emisión</th>
										<th>Modelo motor</th>
										<th>Tipo contrato</th>
										<th>Avance teórico</th>
										<th>&nbsp;</th>
									</tr>
								<?php
								//echo "<pre>";
								//print_r($registros);
								//echo "<pre>";
									foreach( $registros as $registro ) {
										echo "<tr>";
											echo "<td align=\"center\"><input type=\"hidden\" name=\"registro[{$registro['Faena']['id']}]\" value=\"{$registro['Faena']['e']}\">";
											echo "{$registro['Unidad']['id']}</td>";
											echo "<td>{$registro['Faena']['nombre']}</td>";
											echo "<td>{$registro['Flota']['nombre']}</td>";
											echo "<td>{$registro['Unidad']['unidad']}</td>";
											echo "<td>{$registro['Flota']['aplicacion']}</td>";
											echo "<td>{$registro['Flota']['oem']}</td>";
											echo "<td>{$registro['Unidad']['modelo_equipo']}</td>";
											echo "<td>{$registro['Unidad']['nserie']}</td>";
											echo "<td>{$registro['EstadoEquipo']['nombre']}</td>";
											echo "<td>{$registro['Motor']['nombre']}</td>";
											echo "<td>{$registro['Motor']["TipoAdmision"]['nombre']}</td>";
											echo "<td>{$registro['Motor']["TipoEmision"]["nombre"]}</td>";
											echo "<td>{$registro['Motor']['nombre']} {$registro['Motor']["TipoAdmision"]['nombre']} {$registro['Motor']["TipoEmision"]["nombre"]}</td>";
											echo "<td>{$registro['TipoContrato']['nombre']}</td>";
											echo "<td>{$registro['Unidad']['avance_teorico']}</td>";
											
																						
											
											echo "<td align=\"center\" style=\"width: 80px\">";
												if($registro['Unidad']['e'] == '2') {
													echo $this->Html->link("Aprobar", array('action' => 'Aprobar', $registro['Unidad']['id']), array('class' => 'btn btn-sm blue'));
												} else {
													echo $this->Html->link("Editar", array('action' => 'Editar', $registro['Unidad']['id']), array('class' => 'btn btn-sm blue'));
												}
											echo "</td>";
											
										echo "</tr>";
									}
								?>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5 col-sm-12">
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
			</div>
		</form>
	</div>
</div>
<?php } ?>