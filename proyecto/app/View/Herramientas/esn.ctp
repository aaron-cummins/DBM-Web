<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	ini_set('memory_limit','512M');
?>
<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5><?php echo $titulo; ?></h5>
		</div>
	  <div class="clear"></div>
	</div>
</div>
<div class="line"></div>
<?php echo $this->Session->flash();?>
<div class="wrapper">
	<div class="widget">
	<?php
		echo $this->Form->create('CargaEstadoMotores', array('type' => 'file'));
	?>
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Archivo Estado Motores</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>Seleccione archivo:</td>
				<td><?php echo $this->Form->file('EstadoMotores'); ?></td>
			</tr>
            <tr>
				<td></td>
                <td>El archivo debe tener formato CSV y ser el la hoja Modelo del archivo Estado Motores.</td>
		  </tr>
			<tr>
				<td colspan="2" align="right"><?php
					echo $this->Form->button('Vista Previa', array('class' => 'greenB'));
				?></td>
		  </tr>
		</table>
		</fieldset>
	<?php
		echo $this->Form->end();
	?>
	</div>
	<?php 
		if (count($this->request->data)) { 
		//	echo "Se encontraron " .count($data). " motores en operación.<br />";
			//echo $this->Form->create('Herramientas', array('action' => 'verificarEstado'));
	?>
    <div class="widget">
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Filtros</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>Faena</td>
				<td><select class="flCargas" id="aFaenas">
					<option value="">Mostrar Todo</option>
					<?php
						foreach ($faenas as $faena) { 
							echo "<option value=\"{$faena["Faena"]["id"]}\">{$faena["Faena"]["nombre"]}</option>"."\n"; 
						}
					?>
				</select></td>
                <td>Motor</td>
				<td>
                	<select class="flCargas" id="aMotor">
					<option value="">Mostrar Todo</option>
					<?php
						foreach ($motores as $motor) { 
							echo "<option value=\"{$motor["Motor"]["id"]}\">{$motor["Motor"]["nombre"]}</option>"."\n"; 
						}
					?>
					</select>
                </td>
			  </tr>
			<tr>
				<td>Flota</td>
				<td>
                	<select class="flCargas" id="aFlota">
					<option value="">Mostrar Todo</option>
					<?php
						foreach ($flotas as $flota) { 
							echo "<option value=\"{$flota["Flota"]["id"]}\">{$flota["Flota"]["nombre"]}</option>"."\n"; 
						}
					?>
					</select>
                </td>
				<td>Estado</td>
                <td>
					<select id="aEstados" class="flCargas">
						<option value="">Mostrar Todo</option>
						<option value="3">Ambos</option>
						<option value="2">Nuevo</option>
						<option value="1">Activo</option>
						<option value="0">Inactivo</option>
					</select>
				</td>
			</tr>
             <tr>
				<td>Serie</td>
				<td>
					<input id="aSerie" type="text" class="flTexto" />
				</td>
				<td>Tipo</td>
                <td>
					<select id="aEMTipo" class="flCargas">
						<option value="">Mostrar Todo</option>
						<option value="Camión">Camión</option>
						<option value="Pala">Pala</option>
						<option value="Cargador">Cargador</option>
						<option value="Apoyo">Apoyo</option>
                        <option value="Regador">Regador</option>
					</select>
				</td>
		  </tr>
          <tr>
				<td>ESN</td>
				<td><input id="aEsn" type="text" class="flTexto" /></td>
				<td>Unidad</td>
				<td><input id="aUnidad" type="text" class="flTexto" /></td>
		  </tr>
          <tr>
				<td>Horómetro</td>
				<td><input id="aHorometro" type="text" class="flTexto" /></td>
				<td>Fabricante</td>
				<td><input id="aFabricante" type="text" class="flTexto" /></td>
		  </tr>
          <tr>
				<td>Modelo Motor</td>
				<td><input id="aModeloMotor" type="text" class="flTexto" /></td>
				<td>Modelo Equipo</td>
				<td><input id="aModeloEquipo" type="text" class="flTexto" /></td>
		  </tr>
		</table>
		</fieldset>
	</div>
	<div class="widget">
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Archivo Estado Motores</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable tbResultados">
              <thead>
                  <tr>
					  <td width="20">#</td>
					  <td>Faena</td>
					  <td>Motor</td>
					  <td>Flota</td>
					  <td>Unidad</td>
					  <td>ESN</td>
					  <td>Fecha Inicio</td>
					  <td>Fecha Término</td>
                  </tr>
              </thead>
              <tbody>
				<?php 
				$i = 1;
				foreach ($data as $fila) { 
				?>
					<tr style="<?php echo $fila["td"]; ?>">
						<td><?php echo $i++;?></td>
						<td class="f"><?php echo $fila["Faena"];?></td>
						<td class="motor"><?php echo $fila["Motor"];?></td>
						<td class="flota"><?php echo $fila["Flota"];?></td>
						<td class="unidad"><?php echo $fila["Unidad"];?></td>
						<td class="esn"><?php echo $fila["ESN"];?></td>
						<td class="modelomotor"><?php echo $fila["FechaInicio"];?></td>
						<td class="fabricante"><?php echo $fila["FechaTermino"];?></td>
						</tr>
				<?php
				}
				?>
			  </tbody>
		  </table>
		</div>
		
		<?php
		echo $this->Form->create('Herramientas', array('action' => 'actualizar_esn'));
		echo $this->Form->hidden('hash', array('value' => time()));
		echo $this->Form->hidden('hash_file', array('value' => @$archivo));
		echo '<p align="right">';
		echo $this->Form->button('Actualizar Datos', array('class' => 'greenB', 'align' => 'right'));
		echo '</p>';
		echo $this->Form->end();
	?>
	<?php
		}		
	?>
</div>