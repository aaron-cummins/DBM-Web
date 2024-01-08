<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
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
		echo $this->Form->create('perfil');
	?>
		<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6><?php echo $titulo; ?></h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>Nombres:</td>
				<td><input type="text" name="nombres" id="nombres" pattern="[a-zA-Z\s]+" value="<?php echo $nombres;?>" /></td>
			</tr>
			<tr>
				<td>Apellidos:</td>
				<td><input type="text" name="apellidos" id="apellidos" pattern="[a-zA-Z\s]+" value="<?php echo $apellidos;?>" /></td>
			</tr>
			<tr>
				<td>Correo Electrónico:</td>
				<td><input type="email" name="correo_electronico" id="correo_electronico" value="<?php echo $correo_electronico;?>" /></td>
			</tr>
			<tr>
				<td colspan="2" align="right"><?php
					echo $this->Form->button('Guardar', array('class' => 'greenB'));
				?></td>
		  </tr>
		</table>
		</fieldset>
	<?php
		echo $this->Form->end();
	?>
	</div>
</div>