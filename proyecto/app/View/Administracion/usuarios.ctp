<?php
	//print_r($usuarios);
	//print_r($faenas);
?>
<div class="wrapper">
   <form method="POST">
	<div class="widget">
	<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Filtro</h6></div>
	  <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
		  <tbody>
			<tr>
				<td width="10%">Faena</td>
				<td>
					<select name="faena_id" id="faena_id">
						<option value="" <?php echo ($flota_id == "") ? 'selected="selected"' : ''; ?>>Todas</option>
						<?php
							foreach ($select_faena as $key => $val) { 
								echo "<option value=\"$key\" ".(($flota_id == $key) ? 'selected="selected"' : '').">$val</option>"."\n"; 
							}
						?>
					</select>
				</td>
				<td width="10%">Perfil</td>
				<td>
					<select name="perfil_id" id="perfil_id">
						<option value="" <?php echo ($perfil_id == "") ? 'selected="selected"' : ''; ?>>Todos</option>
						<option value="1" <?php echo ($perfil_id == "1") ? 'selected="selected"' : ''; ?>>Técnico</option>
						<option value="2" <?php echo ($perfil_id == "2") ? 'selected="selected"' : ''; ?>>Supervisor DCC</option>
						<option value="3" <?php echo ($perfil_id == "3") ? 'selected="selected"' : ''; ?>>Supervisor Cliente</option>
						<option value="4" <?php echo ($perfil_id == "4") ? 'selected="selected"' : ''; ?>>Administrador</option>
						<option value="5" <?php echo ($perfil_id == "5") ? 'selected="selected"' : ''; ?>>Gestión</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td align="right" colspan="2">
					<input type="button" value="Limpiar" class="greyishB" onclick="window.location='/Administracion/usuarios'; return false;" />
					<input type="submit" value="Aceptar" class="greenB" />
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	</form>
</div>
	
<div class="wrapper">
<?php echo $this->Form->create("Datos"); ?>
<div class="widget">
<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Archivo Dotación</h6></div>
<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
	<thead>
		  <tr>
			  <td width="20">#</td>
			  <td>Usuario</td>
			  <td>Nombres</td>
			  <td>Apellidos</td>
			  <td>Correo</td>
			  <td>Faena</td>
			  <td>Perfil</td>
			  <td></td>
		  </tr>
	  </thead>
	<?php $i = 1; ?> 
	<?php foreach ($usuarios as $usuario) { ?>
		<?php if (isset($faenas[$usuario["Usuario"]["id"]]) && is_array($faenas[$usuario["Usuario"]["id"]]) && count($faenas[$usuario["Usuario"]["id"]]) == 1) { ?>
		<tr style="<?php if ($usuario["Usuario"]["e"] != '1') { echo 'opacity: .3;';}?>">
			<td><?php echo $i++;?></td>
			<td><?php echo $usuario["Usuario"]["usuario"];?></td>
			<td><?php echo $usuario["Usuario"]["nombres"];?></td>
			<td><?php echo $usuario["Usuario"]["apellidos"];?></td>
			<td><?php echo $usuario["Usuario"]["correo_electronico"];?></td>
			<td><?php echo $faenas[$usuario["Usuario"]["id"]][0];?></td>
			<td><?php echo $usuario["NivelUsuario"]["nombre"];?></td>
			<?php if ($usuario["Usuario"]["e"] == '1') { ?>
			<td><input type="checkbox" name="s[]" value="<?php echo $usuario["Usuario"]["id"];?>_0" title="Desactivar" /></td>
			<?php } else { ?>
			<td><input type="checkbox" name="r[]" value="<?php echo $usuario["Usuario"]["id"];?>_0" title="Reactivar" /></td>
			<?php } ?>
		</tr>
		<?php } elseif (isset($faenas[$usuario["Usuario"]["id"]]) && is_array($faenas[$usuario["Usuario"]["id"]]) && count($faenas[$usuario["Usuario"]["id"]]) > 1) { 
			$j = 1;
		?>
			<?php foreach ($faenas[$usuario["Usuario"]["id"]] as $key => $value) { ?>
			<tr style="<?php if ($usuario["Usuario"]["e"] != '1') { echo 'opacity: .3;';}?>">
				<td style="<?php if ($usuario["Usuario"]["e"] != '1') { echo 'opacity: .3;';}?>"><?php echo $i++;?></td>
				<td><?php echo $usuario["Usuario"]["usuario"];?></td>
				<td><?php echo $usuario["Usuario"]["nombres"];?></td>
				<td><?php echo $usuario["Usuario"]["apellidos"];?></td>
				<td><?php echo $usuario["Usuario"]["correo_electronico"];?></td>
				<td><?php echo $value;?></td>
				<td><?php echo $usuario["NivelUsuario"]["nombre"];?></td>
				<?php if ($usuario["Usuario"]["e"] == '1') { ?>
				<td><input type="checkbox" name="s[]" value="<?php echo $usuario["Usuario"]["id"];?>_0" title="Desactivar" /></td>
				<?php } else { ?>
				<td><input type="checkbox" name="r[]" value="<?php echo $usuario["Usuario"]["id"];?>_0" title="Reactivar" /></td>
				<?php } ?>
			</tr>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</table>
</div>
</div>
<p align="right" style="margin-right: 10px;">
<?php echo $this->Form->button('Aceptar', array('class' => 'greenB'));?>
</p>
<?php echo $this->Form->end(); ?>
</div>