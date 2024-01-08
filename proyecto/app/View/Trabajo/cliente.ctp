<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
?>    <!-- Title area -->
   <div class="titleArea" style="padding-top: 0;">
      <div class="wrapper">
            <div class="pageTitle">
                <h5>Trabajos</h5>
				<span>Revisión Cliente</span>
            </div>
          <div class="clear"></div>
        </div>
    </div>
    
    
    <!-- Page statistics area -->
    
    
    <div class="line"></div>
    
    <!-- Main content wrapper -->
	<div class="wrapper">
      
        <?php
			if (count($intervenciones) > 0) {
				
		?>
        <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Intervenciones pendientes de revisión por Cliente</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
                <tr>
					<td width="20">#</td>
					<?php if (intval($this->Session->read('faena_id')) == 0) { ?>
						<td nowrap>Faena</td>
					  <?php } ?>
					<td width="20">Folio</td>
                      <td>Flota</td>
                      <td>Equipo</td>
					   <td>ESN</td>
                      <td>Tipo</td>
					  <td>Inicio</td>
					  <td>Duración</td>
                      <td>Descripción</td>
					 <!-- <td width="40">Cliente</td>-->
					  <!--<td width="50"></td>-->
                </tr>
                  </tr>
              </thead>
              <tbody>
			  <?php 
				$i = 1;
				foreach ($intervenciones as $intervencion) { 
					//$date = new DateTime($intervencion['Planificacion']['fecha']);
					//$hora = substr($intervencion['Planificacion']['hora'], 0, 5);
					
					$fecha_inicio = new DateTime($intervencion['Planificacion']['fecha'] . ' ' .$intervencion['Planificacion']['hora']);
					$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
					$diff = date_diff($fecha_termino, $fecha_inicio);
					//$total = round(($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60, 1);
					
					$total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60) * 60;
					$h = floor($total / 60);
					$m = $total - $h * 60;
					$h = str_pad($h, 2, "0", STR_PAD_LEFT);
					$m = str_pad($m, 2, "0", STR_PAD_LEFT);
					$total = $h.":".$m;
			  ?>
                  <tr>
                  <td nowrap><?php echo $i++;?></td>
				  <?php if (intval($this->Session->read('faena_id')) == 0) { ?>
						<td nowrap align="center"><?php echo $intervencion['Faena']['nombre'];?></td>
					  <?php } ?>
					 <td><?php echo $intervencion['Planificacion']['id']; ?></td>
                      <td align="center"><?php echo $util->getFlota($intervencion['Planificacion']['flota_id']); ?></td>
                      <td align="center"><?php echo $util->getUnidad($intervencion['Planificacion']['unidad_id']); ?></td>
					  <td nowrap align="center"><?php 
						$esn_ = $util->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$util->getMotor($intervencion['Planificacion']['unidad_id']),$util->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
						echo $esn_;
						$esn = "";
						if($esn_==''){
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
						}
					  ?></td>
                      <td align="center"><?php echo strtoupper($intervencion['Planificacion']['tipointervencion']); ?></td>
                     <td nowrap align="center"><?php echo $fecha_inicio->format('d-m-Y h:i A');?></td>
                      <td align="center"><?php echo $total; ?></td>
                      <td>
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
						}
					} else {
						echo $util->getSintoma($intervencion['Planificacion']['sintoma_id']);
					}
					?>
					  </td>
					  <!--<td nowrap align="center">
						<?php 
					 if ($intervencion['Planificacion']['estado'] == 5) {
					 ?>
							<img src="/images/icons/color/tick.png" alt="" title="Intervención aprobada" />
					<?php
						} elseif ($intervencion['Planificacion']['estado'] == 6){
						?>
							<img src="/images/icons/color/cross.png" alt="" title="Intervención rechazada" />
					 <?php
					 } else {
					 ?>
					 <img src="/images/icons/color/question.png" alt="" title="Intervención sin revisar" />
							<?php
					 } 
					 ?>
					 </td>-->
                     <!--
					  <td align="center">
						<form action="/Trabajo/cliente" method="POST">
							<input type="hidden" name="id" value="<?php echo $intervencion['Planificacion']['id']; ?>" />
							<input type="submit" name="revisar" value="Revisar" class="blueB" />
						</form>
					</td>-->
                  </tr>
                  <?php } ?>
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