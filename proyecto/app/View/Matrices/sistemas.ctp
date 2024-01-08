<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$paginator = $this->Paginator;
?>
<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5>Sistemas</h5>
		</div>
	  <div class="clear"></div>
	</div>
</div>
<div class="line"></div>
<?php echo $this->Session->flash();?>
<div class="wrapper">
       <form method="GET" action="/matrices/sistemas">
       <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Filtro</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <tbody>
				<tr>
					<td>Motor</td>
					<td>
						<select name="mid" id="mid">
							<option value="" <?php echo ($motor_id == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<?php
								foreach ($motores as $motor) { 
									echo "<option value=\"{$motor["Motor"]["id"]}\" ".(($motor_id==$motor["Motor"]["id"]) ? 'selected="selected"' : '').">{$motor["Motor"]["nombre"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
					<td>Sistema</td>
					<td>
						<select name="sid" id="sid">
							<option value="" <?php echo ($sistema_id == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<?php
								foreach ($sistemas as $sistema) { 
									echo "<option value=\"{$sistema["Sistema"]["id"]}\" ".(($sistema_id==$sistema["Sistema"]["id"]) ? 'selected="selected"' : '').">{$sistema["Sistema"]["nombre"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
					</tr>
					<tr>
					<td>Subsistema</td>
					<td>
						<select name="suid" id="suid">
							<option value="" <?php echo ($subsistema_id == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<?php
								foreach ($subsistemas as $subsistema) { 
									echo "<option value=\"{$subsistema["Subsistema"]["id"]}\" ".(($subsistema_id==$subsistema["Subsistema"]["id"]) ? 'selected="selected"' : '').">{$subsistema["Subsistema"]["nombre"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
					<td>Posición</td>
					<td>
						<select name="pid" id="pid">
							<option value="" <?php echo ($posicion_id=="")?'selected="selected"':''; ?>>Todos</option>
							<?php
								foreach ($posiciones as $posicion) { 
									echo "<option value=\"{$posicion["Posiciones_Subsistema"]["id"]}\" ".(($posicion_id==$posicion["Posiciones_Subsistema"]["id"]) ? 'selected="selected"' : '').">{$posicion["Posiciones_Subsistema"]["nombre"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
					</tr>
					<tr>
				<td>Estado</td>
                <td>
					<select id="e" name="e">
						<option value="">Todos</option>
						<option value="1" <?php if($e=="1"){echo "selected=\"selected\"";} ?>>Activo</option>
						<option value="0" <?php if($e=="0"){echo "selected=\"selected\"";} ?>>Inactivo</option>
					</select>
				</td>
				<td></td>
				<td></td>
			</tr>
					<tr>
					<td align="right" colspan="4">
						<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/matrices/sistemas'; return false;" />
						<input type="submit" name="filtro_aceptar" value="Aceptar" class="greenB" />
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		</form>
	</div>
	<form method="GET" action="/matrices/sistemas/up">
<div class="wrapper">
	<div class="widget">
	<?php
		if (count($resultado)>0) { 
	?>
	
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>SISTEMAS</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
					  <?php echo "<td  style=\"width: 30px;\">" . $paginator->sort('MotorSistemaSubsistemaPosicion.id', 'Código') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Motor.nombre', 'Motor') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Sistema.nombre', 'Sistema') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Subsistema.nombre', 'Subsistema') . "</td>";?>
					   <?php echo "<td>" . $paginator->sort('Posicion.nombre', 'Posición') . "</td>";?>
					  <?php echo "<td width=\"30\">" . $paginator->sort('MotorSistemaSubsistemaPosicion.e', 'Estado') . "</td>";?>
					  <td style="width:30px;">Seleccionar</td>
                  </tr>
              </thead>
              <tbody>
				<?php 
				$i = 1;
				foreach ($resultado as $v) {
				?>
					<tr>
						<td><?php echo $v["MotorSistemaSubsistemaPosicion"]["id"];?></td>
						<td><?php echo $v["Motor"]["nombre"];?></td>
						<td><?php echo $v["Sistema"]["nombre"];?></td>
						<td><?php echo $v["Subsistema"]["nombre"];?></td>
						<td><?php echo $v["Posicion"]["nombre"];?></td>
						<td align="center">
							<?php if($v["MotorSistemaSubsistemaPosicion"]["e"]=='1'){?>
							<!--<a href="/Matrices/sistemas/<?php echo $v["MotorSistemaSubsistemaPosicion"]["id"];?>/0">-->
								<img src="/images/icons/control/32/check.png" width="20" />
							<!--</a>-->
							<?php }?>
							<?php if($v["MotorSistemaSubsistemaPosicion"]["e"]=='0'){?>
							<!--<a href="/Matrices/sistemas/<?php echo $v["MotorSistemaSubsistemaPosicion"]["id"];?>/1">-->
								<img src="/images/icons/control/32/check.png" width="20" style="opacity:0.2;" />
							<!--</a>-->
							<?php }?>
						</td>
						<td>
							<input type="checkbox" name="reg[]" value="<?php echo $v["MotorSistemaSubsistemaPosicion"]["id"];?>" />
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
	<p align="right" style="margin-right: 10px;">
		<input type="submit" name="ac" value="Activar" class="greenB" />
		<input type="submit" name="de" value="Desactivar" class="redB" />
	</p>
	</form>
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