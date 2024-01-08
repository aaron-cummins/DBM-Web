<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
?>    <!-- Title area -->
   <div class="titleArea">
      <div class="wrapper">
            <div class="pageTitle">
                <h5>Trabajos</h5>
				<span>Aprobados</span>
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
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Intervenciones Aprobadas por Supervisor DCC</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                <tr>
					<td width="20">#</td>
					<?php if (intval($this->Session->read('faena_id')) == 0) { ?>
						<td nowrap>Faena</td>
					  <?php } ?>
					<td width="20">Cód.</td>
                      <td>Flota</td>
                      <td>Equipo</td>
                      <td>ESN</td>
                      <td>Tipo Intervención</td>
                      <td nowrap>Síntoma / Tipo Mantención / Backlog</td>
                      <td>Observación</td>
					  <td>Inicio</td>
					  <td>Término</td>
					  <td>Observación Supervisor</td>
                </tr>
              </thead>
              <tbody>
			  <?php 
				$i = 1;
				foreach ($intervenciones as $intervencion) { 
					//$date = new DateTime($intervencion['Planificacion']['fecha']);
					//$hora = substr($intervencion['Planificacion']['hora'], 0, 5);
					//$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
					$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					$fecha_termino = '';
					if (isset($json["fecha_termino_g"]) && $json["fecha_termino_g"] != null && $json["fecha_termino_g"] != "") {
						$fecha_termino = new DateTime($json["fecha_termino_g"]);
					} elseif ($intervencion['Planificacion']['fecha_termino'] != '') {
						$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
					}
			  ?>
                  <tr>
                  <td nowrap><?php echo $i++;?></td>
				  <?php if (intval($this->Session->read('faena_id')) == 0) { ?>
						<td nowrap><?php echo $intervencion['Faena']['nombre'];?></td>
					  <?php } ?>
					<td><?php echo $intervencion['Planificacion']['id']; ?></td>
                      <td><?php echo $util->getFlota($intervencion['Planificacion']['flota_id']); ?></td>
                      <td><?php echo $util->getUnidad($intervencion['Planificacion']['unidad_id']); ?></td>
                      <td><?php echo $intervencion['Planificacion']['esn']; ?></td>
                      <td align="center">
						<?php 
						if ($intervencion['Planificacion']['padre'] != null && $intervencion['Planificacion']['padre'] != '') {
							echo "c".strtoupper($intervencion['Planificacion']['tipointervencion']);
						} else {
							echo strtoupper($intervencion['Planificacion']['tipointervencion']);
						}

					  ?>
					  </td>
                     <td><?php 
					if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'MP') {
						if ($intervencion['Planificacion']['tipomantencion'] == "1500") {
							echo "Overhaul";
						} else {
							echo $intervencion['Planificacion']['tipomantencion'];
						}
					} elseif (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL') {
						if ($intervencion['Planificacion']['backlog_id'] != null) {
							echo $intervencion['Backlog']['criticidad'] ." / ". $intervencion['Backlog']['Sistema_Motor']['Sistema']['nombre']; 
						}
					} else {
						echo $util->getSintoma($intervencion['Planificacion']['sintoma_id']);
					}				  
					?></td>
                      <td><?php echo $intervencion['Planificacion']['observacion']; ?></td>
					  <td nowrap>
					  <?php
						try {
						if ($date != '')
							echo $date->format('d-m-Y h:i A');
					} catch (Exception $e) {}
					  ?>
					  </td>
					  <td nowrap>
					  <?php 
					try {
						if ($fecha_termino != '')
							echo $fecha_termino->format('d-m-Y h:i A');
					} catch (Exception $e) {}
					  ?>
					  </td>
					  <td nowrap></td>
                  </tr>
                  <?php } ?>
              </tbody>
          </table>
        </div>
		<?php
		} else {
		?>
		<div class="mensaje_sin_registros">No hay registros para mostrar.</div>
		<?php } ?>
   	</div>   