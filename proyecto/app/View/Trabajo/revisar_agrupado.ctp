<?php
	ini_set('memory_limit','128M');
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	
	App::import('Model', 'Planificacion');
	$var = new Planificacion();
	/*
	App::import('Model', 'Trabajo');
	$trabajo = new Trabajo();*/
?>   
	<!-- Title area -->
	<div class="titleArea">
		<div class="wrapper">
            <div class="pageTitle">
                <h5>Intervenciones</h5>
                <span>Revisión Agrupada por Continuaciones</span>
            </div>
			<div class="clear"></div>
        </div>
    </div>
    
    
    <!-- Page statistics area -->
    
    
    <div class="line"></div>
	<?php echo $this->Session->flash();?>
    
    <!-- Main content wrapper -->
	<div class="wrapper">
      
        <?php
		/*echo "<pre>";
			print_r($intervenciones);
echo "</pre>";*/		
		if (count($intervenciones) > 0) {
		?>
        <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Intervenciones para revisión</h6>
        	<div style="float: right;margin-right: 8px;margin-top: 8px;">
            	<a href="/Trabajo/revisar">Despliegue normal</a>
            </div>
        </div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr style="font-weight: bold;">
					  <td width="20">#</td>
					  <?php if (intval($this->Session->read('faena_id')) == 0) { ?>
						<td width="10">Faena</td>
					  <?php } ?>
					  <td width="20">Cód.</td>
                      <td nowrap width="50">Flota</td>
                      <td nowrap width="50">Equipo</td>
                      <td nowrap width="40">ESN</td>
                      <td nowrap width="50">Tipo</td>
                      <td nowrap>Síntoma / Tipo Mantención / Backlog</td>
                      <!--<td>Backlog</td>-->
					  <td nowrap width="30">Inicio</td>
					  <td nowrap width="30">Término</td>
					  <!--<td width="40">Continuaciones</td>-->
					  <td width="40">Comentarios</td>
					  <td width="40">Estado</td>
					  <td width="60" nowrap></td>
                </tr>
              </thead>
              <tbody>
			  <?php 
				$i = 1;
				foreach ($intervenciones as $intervencion) { 
					if ($intervencion['Planificacion']['flota_id'] == NULL) {
						continue;	
					}
				
					$json = json_decode($intervencion["Planificacion"]["json"], true);
					$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					
					$fecha_termino = '';
					
					if (isset($json["fecha_termino_g"]) && $json["fecha_termino_g"] != null && $json["fecha_termino_g"] != "") {
						$fecha_termino = new DateTime($json["fecha_termino_g"]);
					} elseif ($intervencion['Planificacion']['fecha_termino'] != '') {
						$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
					}
					
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
                  <td nowrap><?php echo $intervencion['Planificacion']['id'];?></td>
                      <td nowrap><?php echo $util->getFlota($intervencion['Planificacion']['flota_id']); ?></td>
                      <td><?php echo $util->getUnidad($intervencion['Planificacion']['unidad_id']); ?></td>
                      <td nowrap  align="center"><?php 
						if (@$json["esn_nuevo"] != "") {
							echo @$json["esn_nuevo"];
						} elseif (@$json["esn"] != "") {
							echo @$json["esn"];
						} else {
							echo $intervencion['Planificacion']['esn'];
						}
					  //echo $intervencion['Planificacion']['esn']; 
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
						if (@$intervencion['Planificacion']['backlog_id'] != NULL) {
							echo @$intervencion['Backlog']['criticidad'] ." / ". @$intervencion['Backlog']['Sistema_Motor']['Sistema']['nombre']; 
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
						if ($fecha_termino != '')
							echo $fecha_termino->format('d-m-Y h:i A');
					} catch (Exception $e) {}
					  ?></td>
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
								<h4>Comentario</h4>
								<p align=\"justify\">$comentario</p>
								<p align=\"right\"><span class=\"cerrar_comentario\" style=\"cursor: pointer;\">Cerrar</span></p>
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
						$url = strtolower($intervencion['Planificacion']['tipointervencion']);
						$url = $url == "rp" ? "bl" : $url;
					  ?>
						<form action="/Trabajo/<?php echo $url;?>/<?php echo $intervencion['Planificacion']['id']; ?>" method="GET">
							<input type="submit" name="detalle" value="Detalle" class="blueB"  />
						</form>
					</td>
                  </tr>
                  <?php 
					// Verificamos si tiene hijo
					if ($intervencion['Planificacion']["hijo"] == "") {
						continue;
					} else {
						// Tiene hijo, los desplegamos recursivamente
						echo $util->desplegar_hijo(intval($this->Session->read('faena_id')), $intervencion['Planificacion']["hijo"], $i - 1);
						//echo "<tr><td>Tiene hijo</td></tr>";
					}
				  } ?>
              </tbody>
          </table>
        </div>
		<?php
		} else {
		?>
			<div class="mensaje_sin_registros">No hay registros para mostrar.</div>
		<?php
		}
		?> 
   	</div>   