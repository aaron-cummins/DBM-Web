<?php
	set_time_limit(1000);
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
		echo $this->Form->create('carga_archivo', array('type' => 'file'));
	?>
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Archivo</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>Seleccione archivo:</td>
				<td><?php echo $this->Form->file('archivo'); ?></td>
			</tr>
             <tr>
				<td></td>
                <td>El archivo debe tener formato CSV con las siguientes columnas: Arreglo Motor, Sección, Sistema, Subsistema, N° ID, Elemento, Pool componentes.</td>
				<td></td>
				<td></td>
		  </tr>
			<tr>
				<td></td>
				<td></td>
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
	if (count($this->request->data) && count($data) > 0) {
		echo $this->Form->create('Herramientas', array('action' => 'ElementoPoolProcess'));
?>
	<div class="widget">
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Filtros</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
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
				<td>Sistema</td>
				<td>
                	<select class="flCargas" id="aSistema">
					<option value="">Mostrar Todo</option>
					<?php
						foreach ($sistemas as $var) { 
							echo "<option value=\"{$var["Sistema"]["id"]}\">{$var["Sistema"]["nombre"]}</option>"."\n"; 
						}
					?>
					</select>
                </td>
			  </tr>
			<tr>
				<td>Subsistema</td>
				<td>
                	<select class="flCargas" id="aSubsistema">
					<option value="">Mostrar Todo</option>
					<?php
						foreach ($subsistemas as $var) { 
							echo "<option value=\"{$var["Subsistema"]["id"]}\">{$var["Subsistema"]["nombre"]}</option>"."\n"; 
						}
					?>
					</select>
                </td>
				<td>Elemento</td>
				<td>
					<select id="aElemento" class="flCargas">
						<option value="">Mostrar Todo</option>
						<?php
							foreach ($elementos as $var) { 
								echo "<option value=\"{$var["Elemento"]["id"]}\">{$var["Elemento"]["nombre"]}</option>"."\n"; 
							}
						?>
					</select>
				</td>
			</tr>
			<tr>	
				<td>N° ID</td>
                <td><input id="aIdElemento" type="text" class="flTexto" /></td>
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
		</table>
		</fieldset>
	</div>
	
	<div class="widget">
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Archivo Pool de Elementos</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable tbResultados">
              <thead>
                  <tr>
					  <td width="20">#</td>
					  <td>Motor</td>
					  <td>Sistema</td>
					  <td>Subsistema</td>
					  <td>N° ID</td>
					  <td>Elemento</td>
					  <td>Estado</td>
					  <td class="hr_check"><input type="checkbox" title="Marcar todos" class="marcar_todos_file" /></td>
                  </tr>
              </thead>
              <tbody>
				<?php 
				$i = 0;
				foreach ($data as $fila) {
					$i++;
					echo "<tr style=\"{$fila["td"]}\">";
					echo "<td>$i</td>";
					echo "<td class=\"motor\">{$fila["Motor"]}</td>";
					echo "<td class=\"sistema\">{$fila["Sistema"]}</td>";
					echo "<td class=\"subsistema\">{$fila["Subsistema"]}</td>";
					echo "<td class=\"idelemento\">{$fila["Codigo"]}</td>";
					echo "<td class=\"elemento\">{$fila["Elemento"]}</td>";
					if ($fila["Accion"] == '') {
						echo "<td class=\"e\">Ambos</td>";
					} else {
						echo "<td class=\"e\">Nuevo</td>";
					}
					echo "<td>{$fila["Accion"]}</td>";
					echo "</tr>";
				}
				foreach ($existentes as $fila) {
					$i++;
					echo "<tr>";
					echo "<td>$i</td>";
					echo "<td class=\"motor\">".@$fila["Motor"]["nombre"]."</td>";
					echo "<td class=\"sistema\">".@$fila["Sistema"]["nombre"]."</td>";
					echo "<td class=\"subsistema\">".@$fila["Subsistema"]["nombre"]."</td>";
					echo "<td class=\"idelemento\">".@$fila["Sistema_Subsistema_Motor_Elemento"]["codigo"]."</td>";
					echo "<td class=\"elemento\">".@$fila["Elemento"]["nombre"]."</td>";
					if (@$fila["ElementoPool"]["e"] != '1') {
						echo "<td class=\"e\">Inactivo</td>";
						echo "<td><input type=\"checkbox\" name=\"deshacer[]\" class=\"matriz_opciones\" value=\"".@$fila["ElementoPool"]["id"]."\" title=\"Marcar para deshacer eliminación\" /></td>";
					} else {
						echo "<td class=\"e\">Activo</td>";
						echo "<td><input type=\"checkbox\" name=\"quitar[]\" class=\"matriz_opciones\" value=\"".@$fila["ElementoPool"]["id"]."\" title=\"Marcar para eliminar\" /></td>";
					}
					echo "</tr>";
				}
				?>
			  </tbody>
		  </table>
		</div>
<?php
	echo $this->Form->hidden('hash', array('value' => time()));
	echo $this->Form->hidden('hash_file', array('value' => @$archivo));
	echo '<p align="right">';
	echo $this->Form->button('Guardar Datos', array('class' => 'greenB', 'align' => 'right'));
	echo '</p>';
	echo $this->Form->end();
?>
<?php
	}		
?>
</div>