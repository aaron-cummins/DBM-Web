<?php
	ini_set('memory_limit','256M');
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$paginator = $this->Paginator;
	App::import('Model', 'Planificacion');
	$var = new Planificacion();
	$cr = "";
	if (isset($_GET["cr"]) && is_numeric($_GET["cr"])) {
		$cr = $_GET["cr"];
	}
?>   
	<!-- Title area -->
	<div class="titleArea" style="padding-top: 0;">
		<div class="wrapper">
            <div class="pageTitle">
                <h5>Intervenciones</h5>
                <span>Revisión DCC</span>
            </div>
			<div class="clear"></div>
        </div>
    </div>
    
    
    <!-- Page statistics area -->
    
    
    <div class="line"></div>
	<?php echo $this->Session->flash();?>
    <div class="wrapper">
       <form method="GET" action="/Trabajo/revisar_new">
		<input type="hidden" name="faena_id" id="faena_id" value="<?php echo $this->Session->read('faena_id');?>" />
       <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Filtro</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <tbody>
                <tr>
					<td width="10%">Flota</td>
					<td>
						<select name="flota_id_h" id="flota_id_h">
							<option value="" <?php echo ($flota_id == "") ? 'selected="selected"' : ''; ?>>Todas</option>
							<?php
								foreach ($flotas as $flota) { 
									echo "<option value=\"{$flota["UnidadDetalle"]["flota_id"]}\" ".(($flota_id == $flota["UnidadDetalle"]["flota_id"]) ? 'selected="selected"' : '').">{$flota["UnidadDetalle"]["flota"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
					<td width="10%">Equipo</td>
					<td>
						<select name="unidad_id_h" id="unidad_id_h" unidad_id="<?php echo $unidad_id;?>">
							<option value="">Todos</option>
						</select>
					</td>
					<td width="10%">Evento</td>
					<td>
						<select name="tipo_evento" id="tipo_evento">
							<option value="" <?php echo ($tipo_evento == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<option value="PR" <?php echo ($tipo_evento == "PR") ? 'selected="selected"' : ''; ?>>Programado</option>
							<option value="NP" <?php echo ($tipo_evento == "NP") ? 'selected="selected"' : ''; ?>>No Programado</option>
						</select>&nbsp;
						<select name="tipo_terminado" id="tipo_terminado">
							<option value="" <?php echo ($tipo_terminado == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<option value="T" <?php echo ($tipo_terminado == "T") ? 'selected="selected"' : ''; ?>>Terminados</option>
							<option value="P" <?php echo ($tipo_terminado == "P") ? 'selected="selected"' : ''; ?>>Pendientes</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Correlativo</td>
					<td><input name="cr" value="<?php echo $cr; ?>" type="text" /></td>
					
					<td>Estado</td>
					<td>
						<select name="estado" id="estado">
							<option value="0" <?php echo ($estado == "0") ? 'selected="selected"' : ''; ?>>Todos</option>
							<option value="7" <?php echo ($estado == "7") ? 'selected="selected"' : ''; ?>>Sin Revisar</option>
							<option value="6" <?php echo ($estado == "6") ? 'selected="selected"' : ''; ?>>Rechazado Cliente</option>
						</select>
					</td>
					<td width="10%">Tipo</td>
					<td>
						<select name="tipo_intervencion" id="tipo_intervencion" tipo_intervencion="<?php echo $tipo_intervencion;?>">
							<option value="" <?php echo ($tipo_intervencion == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<option value="MP" <?php echo ($tipo_intervencion == "MP") ? 'selected="selected"' : ''; ?>>MP</option>
							<option value="RP" <?php echo ($tipo_intervencion == "RP") ? 'selected="selected"' : ''; ?>>RP</option>
							<option value="BL" <?php echo ($tipo_intervencion == "BL") ? 'selected="selected"' : ''; ?>>BL</option>
							<option value="EX" <?php echo ($tipo_intervencion == "EX") ? 'selected="selected"' : ''; ?>>EX</option>
							<option value="RI" <?php echo ($tipo_intervencion == "RI") ? 'selected="selected"' : ''; ?>>RI</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Folio</td>
					<td><input name="codigo" value="<?php echo $codigo; ?>" type="text" /></td>
					<td>Fecha Inicio</td>
					<td><input type="date" name="fecha_inicio" value="<?php echo $fecha_inicio;?>" style="width: 115px;" /> a <input type="date" name="fecha_inicio_termino" value="<?php echo $fecha_inicio_termino;?>" style="width: 115px;" /></td>
					<td>Fecha Sincronización</td>
					<td><input type="date" name="fecha_termino" value="<?php echo $fecha_termino;?>" style="width: 115px;" /> a <input type="date" name="fecha_termino_termino" value="<?php echo $fecha_termino_termino;?>" style="width: 115px;" /></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td align="right" colspan="2">
						<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/Trabajo/revisar_new'; return false;" />
						<input type="submit" name="filtro_aceptar" value="Aceptar" class="greenB" />
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		</form>
	</div>
    <!-- Main content wrapper -->
	<div class="wrapper">
      
       
        <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon">
        	<h6>Intervenciones</h6>
            <!--<div style="float: right;margin-right: 8px;margin-top: 8px;">
            	<a href="/Trabajo/revisar_agrupado">Despliegue agrupado</a>
            </div>-->
        </div>
		 <?php
		/*echo "<pre>";
			print_r($intervenciones);
echo "</pre>";*/		
		$i = 1;
		if (count($intervenciones) > 0) {
		?>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable dsp_resultados">
              <thead>
                  <tr style="font-weight: bold;">
					  <td width="20">#</td>
					  <?php if (intval($this->Session->read('faena_id')) == 0) { ?>
						<td width="50" nowrap>Faena
						<!--<a href="/Trabajo/revisar/faena/a"><img src="/images/order_up.png" class="flecha_order" /></a>  
						<a href="/Trabajo/revisar/faena/d"><img src="/images/order_down.png" class="flecha_order"/></a> -->
						</td>
					  <?php } ?>
					  <td width="20"><a href="/Trabajo/revisar_new?<?php echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "correlativo:asc") {
						echo "&order=correlativo:desc";
					  } else {
						echo "&order=correlativo:asc";
					  }
					  ?>">Correlativo</a></td>
					  <td width="20" nowrap><a href="/Trabajo/revisar_new?<?php
					  echo $url; 
					  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "id:asc") {
						echo "&order=id:desc";
					  } else {
						echo "&order=id:asc";
					  }
					  ?>">Folio</a>
						<!--<a href="/Trabajo/revisar/codigo/a"><img src="/images/order_up.png" class="flecha_order" /></a>  
						<a href="/Trabajo/revisar/codigo/d"><img src="/images/order_down.png" class="flecha_order"/></a>  -->
					  </td>
                      <td width="20"><a href="/Trabajo/revisar_new?<?php echo $url; 
					  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "flota_id:asc") {
						echo "&order=flota_id:desc";
					  } else {
						echo "&order=flota_id:asc";
					  }
					  ?>">Flota</a></td>
                      <td width="20"><a href="/Trabajo/revisar_new?<?php echo $url; 
					  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "unidad_id:asc") {
						echo "&order=unidad_id:desc";
					  } else {
						echo "&order=unidad_id:asc";
					  }
					  ?>">Equipo</a></td>
                      <td width="20"><a href="/Trabajo/revisar_new?<?php echo $url;
					if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "esn:asc") {
						echo "&order=esn:desc";
					  } else {
						echo "&order=esn:asc";
					  }					  ?>">ESN</a></td>
                      <td width="20" nowrap><a href="/Trabajo/revisar_new?<?php echo $url; 
					  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "tipointervencion:asc") {
						echo "&order=tipointervencion:desc";
					  } else {
						echo "&order=tipointervencion:asc";
					  }
					  ?>">Tipo</a></td>
                      <td nowrap>Descripción</td>
                      <!--<td>Backlog</td>-->
					 <td width="20"><a href="/Trabajo/revisar_new?<?php echo $url; 
					 if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "fecha:asc") {
						echo "&order=fecha:desc";
					  } else {
						echo "&order=fecha:asc";
					  }
					 ?>">Inicio</a></td>
					<td width="20"><a href="/Trabajo/revisar_new?<?php echo $url; 
					if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "fecha_registro:asc") {
						echo "&order=fecha_registro:desc";
					  } else {
						echo "&order=fecha_registro:asc";
					  }
					?>">Sincronización</a></td>
						<td width="20"><a href="/Trabajo/revisar_new?<?php echo $url; 
					if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "duracion:asc") {
						echo "&order=duracion:desc";
					  } else {
						echo "&order=duracion:asc";
					  }
					?>">Duración</a></td>
					  <!--<td width="40">Continuaciones</td>-->
					  <td width="40">Comentario</td>
					  <td width="40">Estado</td>
					  <td width="30">Condición</td>
					  <td width="60">Acción</td>
                </tr>
              </thead>
              <tbody>
			  <?php 
				$i = 1;
				foreach ($intervenciones as $intervencion) { 
					if ($intervencion['Planificacion']['flota_id'] == NULL) {
						continue;	
					}
					
					if ($intervencion["Planificacion"]["padre"] != "" && $intervencion["Planificacion"]["padre"] != null && $intervencion["Planificacion"]["padre"] != "NULL") { 
						$correlativo = @$util->getCorrelativo($intervencion['Planificacion']['padre']);
					} else {
						$correlativo = $intervencion['Planificacion']['id'];
					}
					
					if ($cr != "" && is_numeric($correlativo)) {
						if (trim($correlativo) != trim($cr)) {
							continue;
						}
					}
					
					if ($correlativo == "") {
						//continue;
					}
				
					$json = json_decode($intervencion["Planificacion"]["json"], true);
					$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					//print_r($json);
					$fecha_termino = '';
					/*
					if (isset($json["fecha_termino_g"]) && $json["fecha_termino_g"] != null && $json["fecha_termino_g"] != "") {
						$fecha_termino = new DateTime($json["fecha_termino_g"]);
					} elseif ($intervencion['Planificacion']['fecha_termino'] != '') {
						$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
					}*/
					
					$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
					$diff = date_diff($fecha_termino, $date);
					
					$fecha_registro = new DateTime($intervencion['Planificacion']['fecha_registro']);
					
					$total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60) * 60;
					$h = floor($total / 60);
					$m = $total - $h * 60;
					$h = str_pad($h, 2, "0", STR_PAD_LEFT);
					$m = str_pad($m, 2, "0", STR_PAD_LEFT);
					$total = $h.":".$m;
					
					$style = "";
					if ($intervencion['Planificacion']['estado'] == 6) {
						$style = "style=\"background-color: #FA8258;\"";
					}
					
			  ?>
      
                  <tr <?php echo $style;?>>
					<td><?php echo $i++;?></td>
					<?php if (intval($this->Session->read('faena_id')) == 0) { ?>
						<td nowrap><?php echo $intervencion['Faena']['nombre'];?></td>
					  <?php } ?>
					  <td><?php echo $correlativo;?></td>
						<td><?php echo $intervencion['Planificacion']['id'];?></td>
                      <td nowrap><?php echo $util->getFlota($intervencion['Planificacion']['flota_id']); ?></td>
                      <td><?php echo $util->getUnidad($intervencion['Planificacion']['unidad_id']); ?></td>
                      <td nowrap align="center"><?php 
						$esn = "";
						if (@$json["esn_conexion"] != "") {
							$esn =  @$json["esn_conexion"];
						} elseif (@$json["esn_nuevo"] != "") {
							$esn = @$json["esn_nuevo"];
						} elseif (@$json["esn"] != "") {
							$esn =  @$json["esn"];
						} else {
							$esn =  $intervencion['Planificacion']['esn'];
						}
						
						if (is_numeric($esn)) {
							echo $esn;
						} else {
							// Buscamos ESN del padre
							echo $util->esnPadre($intervencion['Planificacion']['padre']);
						}
					  ?></td>
                      <td align="center">
						
					  <?php 
						if ($intervencion['Planificacion']['padre'] != null && $intervencion['Planificacion']['padre'] != '') {
							echo "c".strtoupper($intervencion['Planificacion']['tipointervencion']);
						} else {
							echo strtoupper($intervencion['Planificacion']['tipointervencion']);
						}
					  ?>
					  
					  </td>
                     <td nowrap style="width: 250px; overflow: hidden;">
						<?php 
					if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'MP') {
						if (isset($json["tipo_programado"])) {
							if ($json["tipo_programado"] == "1500") {
								echo "Overhaul";
							} else {
								echo $json["tipo_programado"];
							}	
						} else {					
							if ($intervencion['Planificacion']['tipomantencion'] == "1500") {
								echo "Overhaul";
							} else {
								echo $intervencion['Planificacion']['tipomantencion'];
							}
						}
					} elseif (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL') {
						if (@$intervencion['Planificacion']['backlog_id'] != null) {
							echo $util->getBacklogInfo($intervencion['Planificacion']['backlog_id']);
						} else {
							echo $util->getSistemaMotor($intervencion['Planificacion']['sintoma_id']);
						}
					} else {
						echo $util->getSintoma($intervencion['Planificacion']['sintoma_id']);
					}				  
					?>
					 </td>
                      <!--<td><?php echo $intervencion['Planificacion']['backlog_id']; ?></td>-->
					  <td nowrap>
					<?php 
					try {
						if ($date != '')
							echo $date->format('d-m-Y h:i A');
					} catch (Exception $e) {}  
					?></td>
					  <td nowrap><?php 
					try {
						if ($fecha_registro != '')
							echo $fecha_registro->format('d-m-Y h:i A');
					} catch (Exception $e) {}
					  ?></td>
					  <td align="center"><?php echo $total; ?></td>
					  <!--<td>
						<?php //echo $util->getNumHijos($intervencion['Planificacion']['id']);?>
					  </td>-->
					  <td align="center"><?php 
					if ($intervencion['Planificacion']['estado'] > 2) {
						$comentario = "";
						if (@$json["comentario"] != "") {
							$comentario = @$json["comentario"];
						} else {
							$comentario = @$json["comentarios"];
						}
						if (strlen($comentario) > 0) {
								echo "<div id=\"comentario_{$intervencion['Planificacion']['id']}\" class=\"box_comentario\">
									<h4>Comentario</h4>";
								echo "<p align=\"left\">".$util->getUsuarioInfo($json["tecnico_principal"])." (Técnico):</p>";
								echo "<p align=\"justify\">$comentario</p>";
								echo "<p align=\"right\"><span class=\"cerrar_comentario\" style=\"cursor: pointer;\">Cerrar</span></p>
								</div>";
								echo "<img src=\"/images/icon-comentario.png\" class=\"mostrar_comentario\" comentario=\"comentario_{$intervencion['Planificacion']['id']}\" style=\"width: 16px;cursor: pointer;\" title=\"Ver comentarios\" />";
							}
					}
					  ?></td>
                      <td nowrap align="center">
					  <?php 
					if ($intervencion['Planificacion']['estado'] == 6) {
						echo "Rechazado Cliente";
					} elseif ($intervencion['Planificacion']['estado'] == 3)  {
						echo "Revisado";
					} else {
						echo "Sin Revisar";
					} 
					
					
					?>
					  </td>
					  <td align="center">
						<?php
						$fd = $util->existeDuplicado($intervencion['Planificacion']['id']);
						if ($fd == '0') {
							echo '<img src="/images/icons/color/tick.png" alt="" title="Evento OK"';
						} else {
							echo '<a href="#f'.$fd.'"><img src="/images/icons/color/cross.png" alt="" title="Evento duplicado (folio '.$fd.')" border="0"></a>';
						}
						?>
					  </td>
					  <td align="center">
					  <?php
						$url = strtolower($intervencion['Planificacion']['tipointervencion']);
						//$url = $url == "rp" ? "bl" : $url;
					  ?>
					  <form action="/Trabajo/<?php echo $url;?>/<?php echo $intervencion['Planificacion']['id']; ?>" method="POST">
							<input type="submit" name="detalle" value="Detalle" class="blueB"  />
						</form>
					<?php 
						/*if ($intervencion['Planificacion']['padre'] == null || $intervencion['Planificacion']['padre'] == '') {
					?>
						<form action="/Trabajo/<?php echo $url;?>/<?php echo $intervencion['Planificacion']['id']; ?>" method="POST">
							<input type="submit" name="detalle" value="Detalle" class="blueB"  />
						</form>
					<?php } else { ?>
						<?php
							$folio_pendiente = $util->getEstadoPadre($intervencion['Planificacion']['padre']);
							if ($folio_pendiente == '') {
						?>
						<form action="/Trabajo/<?php echo $url;?>/<?php echo $intervencion['Planificacion']['id']; ?>" method="POST">
							<input type="submit" name="detalle" value="Detalle" class="blueB"  />
						</form>
						<?php } else { ?>
						<form action="" method="POST">
							<input type="submit" name="detalle" value="Detalle" disabled="disabled" class="blueB" title="Debe aprobar intervención con Folio <?php echo $folio_pendiente;?> para poder revisar esta intervención."  />
						</form>
						<?php } ?>
					<?php } */ ?>
					</td>
                  </tr>
                  <?php } ?>
              </tbody>
          </table>
        </div>
		<?php
		} 
		if (count($intervenciones) == 0 || $i == 1) {
		?>
		</div>
			<script type="text/javascript">
			$(document).ready(function(){
				$(".dsp_resultados").hide();
			});
			</script>
			<div class="mensaje_sin_registros">No hay registros para mostrar.</div>
		<?php
		}
		?> 
		
   	</div>   
    
    <?php
if (count($intervenciones)>0) { 
	echo "<div class='paging'>";
	//echo $paginator->first("Primera");
	if($paginator->hasPrev()){
		echo $paginator->prev("Anterior");
	}
	echo $paginator->numbers(array('modulus' => 5));
	if($paginator->hasNext()){
		echo $paginator->next("Siguiente");
	}
	//echo $paginator->last("Última");
	echo "</div>";
}
?>