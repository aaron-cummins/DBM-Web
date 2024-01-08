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
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Archivo Diagnóstico General</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>Seleccione archivo:</td>
				<td><?php echo $this->Form->file('archivo'); ?></td>
			</tr>
             <tr>
				<td></td>
                <td>El archivo debe tener formato CSV con las siguientes columnas: Arreglo Motor, Sección, Sistema, Subsistema, N° ID, Elemento, Diagnóstico.</td>
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
			echo $this->Form->create('Herramientas', array('action' => 'verificarDiagnostico'));		
	?>
	<div class="widget">
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Archivo Diagnóstico General</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
					  <td width="20">#</td>
					  <td>Arreglo Motor</td>
					  <td>Sistema</td>
					  <td>Subsistema</td>
					  <td>N° ID</td>
					  <td>Elemento</td>
					  <td>Diagnóstico</td>
					  <td><input type="checkbox" class="marcar_todos" title="Marcar todos" /></td>
                  </tr>
              </thead>
              <tbody>
				<?php 
				$i = 0;
				foreach ($data as $fila) {
					$i++;
					echo "<tr style=\"{$fila["td"]}\">";
					echo "<td>$i</td>";
					echo "<td>{$fila["Motor"]}</td>";
					echo "<td>{$fila["Sistema"]}</td>";
					echo "<td>{$fila["Subsistema"]}</td>";
					echo "<td>{$fila["Codigo"]}</td>";
					echo "<td>{$fila["Elemento"]}</td>";
					echo "<td>{$fila["Diagnostico"]}</td>";
					echo "<td>{$fila["Accion"]}</td>";
					echo "</tr>";
				}
				
				foreach ($resultados as $fila) {
					if (@$fila["motor"] == '') 
						continue;
					$i++;
					if (@$fila["e"] != '1') {
						echo "<tr style=\"background-color: #FF8040; color: white;\" e=\"".@$fila["e"]."\">";
					} else {
						echo "<tr style=\"background-color: #58ACFA; color: white;\" e=\"".@$fila["e"]."\">";
					}
					echo "<td>$i</td>";
					echo "<td>".@$fila["motor"]."</td>";
					echo "<td>".@$fila["sistema"]."</td>";
					echo "<td>".@$fila["subsistema"]."</td>";
					echo "<td>".@$fila["codigo"]."</td>";
					echo "<td>".@$fila["elemento"]."</td>";
					echo "<td>".@$fila["diagnostico"]."</td>";
					if (@$fila["e"] != '1') {
						echo "<td>Eliminado</td>";
						echo "<td><input type=\"checkbox\" name=\"deshacer[]\" class=\"matriz_opciones\" value=\"".@$fila["id"]."\" title=\"Marcar para deshacer eliminación\" /></td>";
					} else {
						echo "<td>Activo</td>";
						echo "<td><input type=\"checkbox\" name=\"quitar[]\" class=\"matriz_opciones\" value=\"".@$fila["id"]."\" title=\"Marcar para eliminar\" /></td>";
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