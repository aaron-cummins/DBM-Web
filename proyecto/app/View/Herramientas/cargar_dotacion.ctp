<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
?>
<style>
	.esc{
		display: none;
	}
	.mos {
		display: block;
	}
</style>
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
		
		echo $this->Form->create('CargaArchivoUsuarios', array('type' => 'file'));
	?>
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Archivo Dotación</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>Seleccione archivo:</td>
				<td><?php echo $this->Form->file('ArchivoUsuarios'); ?></td>
			</tr>
             <tr>
				<td></td>
                <td>El archivo debe tener formato CSV con las siguientes columnas: Rut, Nombres, Apellidos, Faena, Correo, Cargo.</td>
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
			echo $this->Form->create('Herramientas', array('action' => 'verificarEstadoDotacion'));
	?>
	
	<div class="widget">
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Filtros</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>Usuario</td>
				<td>
					<input id="aRut" type="text" class="flTexto" />
				</td>
				<td>Faena</td>
				<td>
                	<select class="flCargas" id="aFaenas">
					<option value="">Mostrar Todo</option>
					<?php
						foreach ($faenas as $faena) { 
							echo "<option value=\"{$faena["Faena"]["id"]}\">{$faena["Faena"]["nombre"]}</option>"."\n"; 
						}
					?>
					</select>
                </td>
			  </tr>
			<tr>
				<td>Nombre</td>
				<td>
					<input id="aNombre" type="text" class="flTexto" />
				</td>
				<td>Perfil</td>
				<td>
					<select id="aNiveles" class="flCargas">
						<option value="">Mostrar Todo</option>
						<option value="1">Técnico</option>
						<option value="6">Asesor Técnico</option>
						<option value="7">Planificador</option>
						<option value="2">Supervisor DCC</option>
						<option value="3">Supervisor Cliente</option>
						<option value="5">Gestión</option>
						<option value="4">Administrador</option>
					</select>
				</td>
			</tr>
             <tr>
				<td>Apellido</td>
				<td>
					<input id="aApellido" type="text" class="flTexto" />
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
		</table>
		</fieldset>
	</div>
	<div class="widget">
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Archivo Dotación</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable tbResultados">
              <thead>
                  <tr class="th">
					  <td width="20">#</td>
					  <td>Usuario</td>
					  <td>Nombres</td>
					  <td>Apellidos</td>
					  <td>Faena</td>
					  <td>Correo</td>
					  <td>Perfil</td>
					  <td>Estado</td>
					  <td class="hr_check"><input type="checkbox" title="Marcar todos" class="marcar_todos_file" /></td>
                  </tr>
              </thead>
              <tbody>
				<?php 
				$i = 1;
				foreach ($data as $fila) { 
				?>
					<tr style="<?php echo $fila["td"]; ?>" class="<?php echo @$fila["class"]; ?>">
						<td><?php echo $i++;?></td>
						<td class="u"><?php echo @$fila["Rut"];?></td>
						<td class="n"><?php echo @$fila["Nombres"];?></td>
						<td class="a"><?php echo @$fila["Apellidos"];?></td>
						<td class="f"><?php echo @$fila["Faena"];?></td>
						<td><?php echo @$fila["Correo"];?></td>
						<td class="c"><?php echo @$fila["Cargo"];?></td>
						<td class="e"><?php echo @$fila["Estado"];?></td>
						<td><?php echo @$fila["Accion"];?></td>
					</tr>
				<?php
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