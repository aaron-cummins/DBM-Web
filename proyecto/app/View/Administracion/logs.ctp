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
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption font-dark">
					<i class="icon-settings font-dark"></i>
					<span class="caption-subject bold uppercase">Logs DBM</span>
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
												<?php echo "<th>" . $paginator->sort('Log.id', 'Id') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Log.fecha', 'Fecha') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Usuario.usuario', 'Usuario') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Log.controller', 'Sección') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Log.action', 'Subsección') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Log.method', 'Método') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Log.mensaje', 'Mensaje') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Log.here', 'Url') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Log.query', 'Query') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Log.post', 'Post') . "</th>"; ?>
											</tr>
										<?php
											$i = $limit*($paginator->current()-1)+1;
											foreach( $registros as $registro ) {
												echo "<tr>";
												echo "<td>{$registro['Log']['id']}</td>";
												echo "<td nowrap>{$registro['Log']['fecha']}</td>";
												if ($registro['Usuario']['usuario'] == NULL || $registro['Usuario']['usuario'] == '') {
													echo "<td>Sesión Expirada</td>";
												} else {
													echo "<td>{$registro['Usuario']['usuario']}</td>";
												}
												echo "<td>{$registro['Log']['controller']}</td>";
												echo "<td>{$registro['Log']['action']}</td>";
												echo "<td>{$registro['Log']['method']}</td>";
												echo "<td>{$registro['Log']['mensaje']}</td>";
												echo "<td>{$registro['Log']['here']}</td>";
												echo "<td>{$registro['Log']['query']}</td>";
												echo "<td>{$registro['Log']['post']}</td>";
												echo "<td>";
												echo "</tr>";
												$i++;
											}
										?>
										</table>
									</div>
									<!-- End table div -->
								</div>
								<!-- End col -->
							</div>
							<!-- End row -->
							
							<div class="row">
								<div class="col-md-5 col-sm-12">
									<div class="dataTables_info" id="table_1_info" role="status" aria-live="polite">Mostrando del <?php echo $inicio;?> a <?php echo $termino;?> de <?php echo $paginator->params()['count'];?> registros</div>
								</div>
								<!-- End col -->
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
								<!-- End col -->
							</div>
							<!-- End row -->
							
						</div>
						<!-- End div table -->
					</div>
					<!-- End div form -->
				</form>
				<!-- End form -->
			</div>
			<!-- End body -->
		</div>
		<!-- End portled -->
	</div>
	<!-- End col -->
</div>
<!-- End row -->