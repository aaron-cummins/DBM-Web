<?php
	$paginator = $this->Paginator;
	$inicio = $limit * ($paginator->current() - 1) + 1;
	$termino = $inicio + $limit - 1;
	if ($termino > $paginator->params()['count']) {
		$termino = $paginator->params()['count'];
	}
	
?>
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
									<select class="form-control" name="faena"> 
										<option value="" selected="selected">Todos</option>
										<?php foreach($faenas as $key => $value) { ?>
											<option value="<?php echo $key;?>" <?php echo @$faena == $key ? "selected=\"selected\"":""; ?>><?php echo $value;?></option>
										<?php } ?>
									</select> </div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Cargo</label>
									<select class="form-control" name="cargo"> 
										<option value="" selected="selected">Todos</option>
										<?php foreach($cargos as $key => $value) { ?>
											<option value="<?php echo $key;?>" <?php echo @$cargo == $key ? "selected=\"selected\"":""; ?>><?php echo $value;?></option>
										<?php } ?>
									</select> </div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Nombre</label>
									<input class="form-control" name="nombres" type="text" value="<?php echo @$nombres;?>" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Apellido</label>
									<input class="form-control" name="apellidos" type="text" value="<?php echo @$apellidos;?>" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Rut</label>
									<input class="form-control" name="rut" type="number" value="<?php echo @$rut;?>" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>U</label>
									<input class="form-control" name="ucode" type="text" value="<?php echo @$ucode;?>" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Resultados por página</label>
									<select class="form-control" name="limit">
										<option value="10" <?php echo $limit == 10 ? "selected=\"selected\"" : ""; ?>>10</option>
										<option value="25" <?php echo $limit == 25 ? "selected=\"selected\"" : ""; ?>>25</option>
										<option value="50" <?php echo $limit == 50 ? "selected=\"selected\"" : ""; ?>>50</option>
										<option value="100" <?php echo $limit == 100 ? "selected=\"selected\"" : ""; ?>>100</option>
									</select> 
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Permiso/usuario';">Limpiar</button>
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
			<span class="caption-subject bold uppercase">Permisos</span>
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
								<a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="/Permiso/agregar"><i class="fa fa-plus"></i> <span>Nuevo</span></a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<th style="width: 50px;">Cód.</th>
										<th>Faena</th>
										<th>Cargo</th>
										<th>Nombres</th>
										<th>Apellidos</th>
										<th>Rut</th>
										<th>U</th>
										<th>&nbsp;</th>
									</tr>
								<?php
									foreach( $registros as $registro ) {
										$registro['Usuario']['nombres'] = ucwords(strtolower($registro['Usuario']['nombres']));
										$registro['Usuario']['apellidos'] = ucwords(strtolower($registro['Usuario']['apellidos']));
										echo "<tr>";
											echo "<td align=\"center\">";
											echo "{$registro['PermisoUsuario']['id']}</td>";
											echo "<td>{$registro['Faena']['nombre']}</td>";
											echo "<td>{$registro['Cargo']['nombre']}</td>";
											echo "<td>{$registro['Usuario']['nombres']}</td>";
											echo "<td>{$registro['Usuario']['apellidos']}</td>";
											echo "<td>{$registro['Usuario']['usuario']}</td>";
											echo "<td>{$registro['Usuario']['ucode']}</td>";
											echo "<td align=\"center\" style=\"width: 80px\">";
												echo $this->Html->link("Editar", array('action' => 'editar', $registro['PermisoUsuario']['id']), array('class' => 'btn btn-sm blue'));
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
				<div class="form-actions right">
					<button type="submit" class="btn blue">
						<i class="fa fa-save"></i> Guardar </button>
				</div>
			</div>
		</form>
	</div>
</div>