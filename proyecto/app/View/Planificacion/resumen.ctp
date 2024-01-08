<?php
?>    <!-- Title area -->
    <div class="titleArea">
      <div class="wrapper">
            <div class="pageTitle">
                <h5>Planificaciones</h5>
                <span>Resumen</span>
            </div>
          <div class="clear"></div>
        </div>
    </div>
    
    
    <!-- Page statistics area -->
    
    
    <div class="line"></div>
    
    <!-- Main content wrapper -->
	<div class="wrapper">
	<?php 
		if (count($trabajos) == 0) {
	?>
		<div class="mensaje_sin_registros">No hay registros para mostrar.</div>
	<?php
		} else {
	?>
      <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Resumen Planificaciones</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
					<td width="20">#</td>
					<td width="150">Fecha</td>
					<td>Planificados</td>
					<td>Ejecutados</td>
					<td nowrap>Aprobados DCC</td>
					<td nowrap>Aprobados Cliente</td>
					<td nowrap>Rechazados Cliente</td>
					<td width="100"></td>
                  </tr>
              </thead>
              <tbody>
			  <?php 
				$i = 1;
				foreach ($trabajos as $trabajo) { 
					$date = new DateTime($trabajo['Planificacion']['fecha']);
			  ?>
                  <tr>
                   <td><?php echo $i++;?></td>
                      <td nowrap align="center"><?php echo $date->format('d-m-Y'); ?></td>
                      <td><?php echo $trabajo[0]["trabajos"];?></td>
                      <td><?php echo $trabajo[0]["ejecucion"];?></td>
                      <td><?php echo $trabajo[0]["aprobadodcc"];?></td>
					  <td><?php echo $trabajo[0]["aprobadocliente"];?></td>
					  <td><?php echo $trabajo[0]["rechazadocliente"];?></td>
                      <td align="center">
						<form action="/Planificacion/historial" method="GET">
							<input type="hidden" name="fecha" value="<?php echo $trabajo['Planificacion']['fecha']; ?>" />
							<input type="submit" name="detalle" value="Detalle" class="blueB" />
						</form>
					</td>
                  </tr>
                  <?php
				  }
				  ?>
              </tbody>
          </table>
        </div>
		<?php
		}
		?>
	</div>