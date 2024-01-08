<?php
	ini_set('memory_limit', '1024M');
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$paginator = $this->Paginator;
?>
<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5>Estado Motor</h5>
		</div>
	  <div class="clear"></div>
	</div>
</div>
<div class="line"></div>
<?php echo $this->Session->flash();?>
<div class="wrapper">
       <form method="GET" action="/matrices/equipos">
       <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Filtro</h6></div>
			<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
				<tr>
					<td>Faena</td>
					<td>
					<select name="fid" id="fid">
							<option value="" <?php echo ($faena_id == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<?php
								foreach ($faenas as $var) { 
									echo "<option value=\"{$var["Faena"]["id"]}\" ".(($faena_id==$var["Faena"]["id"]) ? 'selected="selected"' : '').">{$var["Faena"]["nombre"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
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
				  </tr>
				<tr>
					<td>Flota</td>
					<td>
						<select name="flid" id="flid">
							<option value="" <?php echo ($flota_id == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<?php
								foreach ($flotas as $var) { 
									echo "<option value=\"{$var["Flota"]["id"]}\" ".(($flota_id==$var["Flota"]["id"]) ? 'selected="selected"' : '').">{$var["Flota"]["nombre"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
					<td>Estado</td>
					<td>
						<select id="e" name="e">
							<option value="">Mostrar Todo</option>
							<option value="1" <?php if($e=="1"){echo "selected=\"selected\"";} ?>>Activo</option>
							<option value="0" <?php if($e=="0"){echo "selected=\"selected\"";} ?>>Inactivo</option>
						</select>
					</td>
				</tr>
				 <tr>
					<td>Serie</td>
					<td>
						<input id="nserie" name="nserie" type="text" value="<?php echo $nserie;?>" />
					</td>
					<td>Tipo</td>
					<td>
						<select id="aplicacion" name="aplicacion">
							<option value="">Mostrar Todo</option>
							<option value="Camión" <?php if($aplicacion=="Camión"){echo "selected=\"selected\"";} ?>>Camión</option>
							<option value="Pala" <?php if($aplicacion=="Pala"){echo "selected=\"selected\"";} ?>>Pala</option>
							<option value="Cargador" <?php if($aplicacion=="Cargador"){echo "selected=\"selected\"";} ?>>Cargador</option>
							<option value="Apoyo" <?php if($aplicacion=="Apoyo"){echo "selected=\"selected\"";} ?>>Apoyo</option>
							<option value="Regador" <?php if($aplicacion=="Regador"){echo "selected=\"selected\"";} ?>>Regador</option>
						</select>
					</td>
			  </tr>
			  <tr>
					<td>ESN</td>
					<td><input id="esn" name="esn" type="text" value="<?php echo $esn;?>" /></td>
					<td>Unidad</td>
					<td><input id="unidad" name="unidad" type="text" value="<?php echo $unidad;?>"  /></td>
			  </tr>
			  <tr>
					<td>Horómetro</td>
					<td><input id="horometro" name="horometro" type="text" value="<?php echo $horometro;?>" /></td>
					<td>Fabricante</td>
					<td><input id="fabricante" name="fabricante" type="text" value="<?php echo $fabricante;?>" /></td>
			  </tr>
			  <tr>
					<td>Modelo Motor</td>
					<td><input id="modelo_motor" name="modelo_motor" type="text"  value="<?php echo $modelo_motor;?>" /></td>
					<td>Modelo Equipo</td>
					<td><input id="modelo_equipo" name="modelo_equipo" type="text"  value="<?php echo $modelo_equipo;?>"  /></td>
			  </tr>
			  <tr>
				<td align="right" colspan="4">
					<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/matrices/equipos'; return false;" />
					<input type="submit" name="filtro_aceptar" value="Aceptar" class="greenB" />
				</td>
			  </tr>
			</table>
		</div>
		</form>
	</div>
	<form method="GET" action="/matrices/equipos/up">
<div class="wrapper">
	<div class="widget">
	<?php
		if (count($resultado)>0) { 
	?>
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Estado Motor</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
					  <?php echo "<td  style=\"width: 30px;\">" . $paginator->sort('Unidad.id', 'Código') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Faena.nombre', 'Faena') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Motor.nombre', 'Motor') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Flota.nombre', 'Flota') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Unidad.unidad', 'Equipo') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Unidad.horometro', 'Horometro') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Unidad.esn', 'ESN') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Unidad.modelo_motor', 'Modelo Motor') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Unidad.fabricante', 'Fabricante') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Unidad.modelo_equipo', 'Modelo Equipo') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Unidad.nserie', 'Serie') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Unidad.aplicacion', 'Tipo') . "</td>";?>
					  <?php echo "<td width=\"30\">" . $paginator->sort('Unidad.e', 'Estado') . "</td>";?>
					  <td style="width:30px;">Seleccionar</td>
                  </tr>
              </thead>
              <tbody>
				<?php 
				$i = 1;
				foreach ($resultado as $unidad) {
					/*
					(
						[Unidad] => Array
							(
								[id] => 515
								[faena_id] => 1
								[flota_id] => 1
								[motor_id] => 1
								[unidad] => 147
								[horometro] => 14380.00
								[esn] => 33192636
								[aplicacion] => Camión
								[nserie] => A31380
								[modelo_equipo] => 930E-4
								[modelo_motor] => QSK60 TS Tier I
								[ninterno] => 
								[fabricante] => Komatsu
								[e] => 1
							)

						[Motor] => Array
							(
								[id] => 1
								[nombre] => QSK60 Tier I
								[e] => 1
							)

						[Flota] => Array
							(
								[id] => 1
								[nombre] => 930 E
								[e] => 1
							)

						[Faena] => Array
							(
								[id] => 1
								[nombre] => Andina
								[e] => 1
							)

					)
					*/	
				?>
					<tr>
						<td><?php echo $unidad["Unidad"]["id"];?></td>
						<td><?php echo $unidad["Faena"]["nombre"];?></td>
						<td><?php echo $unidad["Motor"]["nombre"];?></td>
						<td><?php echo $unidad["Flota"]["nombre"];?></td>
						<td><?php echo $unidad["Unidad"]["unidad"];?></td>
						<td><?php echo $unidad["Unidad"]["horometro"];?></td>
						<td><?php echo $unidad["Unidad"]["esn"];?></td>
						<td><?php echo $unidad["Unidad"]["modelo_motor"];?></td>
						<td><?php echo $unidad["Unidad"]["fabricante"];?></td>
						<td><?php echo $unidad["Unidad"]["modelo_equipo"];?></td>
						<td><?php echo $unidad["Unidad"]["nserie"];?></td>
						<td><?php echo $unidad["Unidad"]["aplicacion"];?></td>
						<td align="center">
							<?php if($unidad["Unidad"]["e"]=='1'){?>
							<!--<a href="/Matrices/equipos/<?php echo $unidad["Unidad"]["id"];?>/0">-->
								<img src="/images/icons/control/32/check.png" width="20" />
							<!--</a>-->
							<?php }?>
							<?php if($unidad["Unidad"]["e"]=='0'){?>
							<!--<a href="/Matrices/equipos/<?php echo $unidad["Unidad"]["id"];?>/1">-->
								<img src="/images/icons/control/32/check.png" width="20" style="opacity:0.2;" />
							<!--</a>-->
							<?php }?>
						</td>
						<td>
							<input type="checkbox" name="reg[]" value="<?php echo $unidad["Unidad"]["id"];?>" />
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