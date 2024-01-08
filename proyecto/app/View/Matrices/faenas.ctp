<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$paginator = $this->Paginator;
?>
<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5>Faenas</h5>
		</div>
	  <div class="clear"></div>
	</div>
</div>
<div class="line"></div>
<?php echo $this->Session->flash();?>
<div class="wrapper">
	<div class="widget">
	<?php
		if (count($resultado)>0) { 
	?>
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>FAENAS existentes en el sistema</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
					  <?php echo "<td  style=\"width: 30px;\">" . $paginator->sort('Faena.id', 'Código') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Faena.nombre', 'Nombre') . "</td>";?>
					  <td style="width: 120px;">Técnicos</td>
					  <td style="width: 120px;">Supervisores DCC</td>
					  <td style="width: 120px;">Supervisores Cliente</td>
					  <td style="width: 120px;">Gestión</td>
					  <td style="width: 120px;">Flotas</td>
					  <td style="width: 120px;">Equipos</td>
					  <td style="width: 120px;">Intervenciones</td>
					  <?php echo "<td width=\"30\">" . $paginator->sort('Faena.e', 'Estado') . "</td>";?>
                  </tr>
              </thead>
              <tbody>
				<?php 
				$i = 1;
				foreach ($resultado as $faena) {
				?>
					<tr>
						<td><?php echo $faena["Faena"]["id"];?></td>
						<td><?php echo $faena["Faena"]["nombre"];?></td>
						<td align="center"><?php echo $util->getNumeroTecnicos($faena["Faena"]["id"]);?></td>
						<td align="center"><?php echo $util->getNumeroSupervisorDCC($faena["Faena"]["id"]);?></td>
						<td align="center"><?php echo $util->getNumeroSupervisorCliente($faena["Faena"]["id"]);?></td>
						<td align="center"><?php echo $util->getNumeroSupervisorGestion($faena["Faena"]["id"]);?></td>
						<td align="center"><?php echo $util->getNumeroFlotas($faena["Faena"]["id"]);?></td>
						<td align="center"><?php echo $util->getNumeroEquipos($faena["Faena"]["id"]);?></td>
						<td align="center"><?php echo $util->getNumeroIntervenciones($faena["Faena"]["id"]);?></td>
						<td align="center">
							<?php if($faena["Faena"]["e"]=='1'){?>
							<a href="/Matrices/faenas/<?php echo $faena["Faena"]["id"];?>/0">
								<img src="/images/icons/control/32/check.png" width="20" title="Click para desactivar" />
							</a>
							<?php }?>
							<?php if($faena["Faena"]["e"]=='0'){?>
							<a href="/Matrices/faenas/<?php echo $faena["Faena"]["id"];?>/1">
								<img src="/images/icons/control/32/check.png" width="20" style="opacity:0.2;" title="Click para activar" />
							</a>
							<?php }?>
						</td>
					</tr>
				<?php
				}
				?>
			  </tbody>
		  </table>
	<?php
		}		
	?>
</div>
</div>
<?php
if (count($resultado)>0) { 
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
<br />
<div class="line"></div>
<div class="wrapper">
	<div class="widget">
		<form method="post">
		<input type="hidden" name="id" id="id" value="" />
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Ingresar Nueva Faena</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td width="200">Nombre:</td>
				<td><input type="text" name="nombre" id="nombre" pattern="[a-zA-Z\s]+" /></td>
			</tr>
			<tr>
				<td colspan="2" align="right"><?php
					echo $this->Form->button('Guardar', array('class' => 'greenB'));
				?></td>
		  </tr>
		</table>
		</fieldset>
		</form>
	</div>
</div>