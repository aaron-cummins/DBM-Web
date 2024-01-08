<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$paginator = $this->Paginator;
?>
<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5>Flotas</h5>
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
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>FLOTAS existentes en el sistema</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
					  <?php echo "<td  style=\"width: 30px;\">" . $paginator->sort('Flota.id', 'Código') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Flota.nombre', 'Flota') . "</td>";?>
					  <?php echo "<td width=\"30\">" . $paginator->sort('Flota.e', 'Estado') . "</td>";?>
                  </tr>
              </thead>
              <tbody>
				<?php 
				$i = 1;
				foreach ($resultado as $v) {
				?>
					<tr>
						<td><?php echo $v["Flota"]["id"];?></td>
						<td><?php echo $v["Flota"]["nombre"];?></td>
						<td align="center">
							<?php if($v["Flota"]["e"]=='1'){?>
							<a href="/Matrices/flotas/<?php echo $v["Flota"]["id"];?>/0">
								<img src="/images/icons/control/32/check.png" width="20" title="Click para desactivar" />
							</a>
							<?php }?>
							<?php if($v["Flota"]["e"]=='0'){?>
							<a href="/Matrices/flotas/<?php echo $v["Flota"]["id"];?>/1">
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