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
		echo $this->Form->create();
	?>
		<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Configuración Días de Espera</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>Primer Corte</td>
				<td><input type="numer" name="g_1_c" id="g_1_c" value="<?php echo $g_1_c;?>" /></td>
			</tr>
			<tr>
				<td>Segundo Corte:</td>
				<td><input type="number" name="g_2_c" id="g_2_c" value="<?php echo $g_2_c;?>" /></td>
			</tr>
			<tr>
				<td>Tercer Corte:</td>
				<td><input type="numer" name="g_3_c" id="g_3_c" value="<?php echo $g_3_c;?>" /></td>
			</tr>
			<tr>
				<td colspan="2" align="right"><?php
					echo $this->Form->button('Aceptar', array('class' => 'greenB'));
				?></td>
		  </tr>
		</table>
		</fieldset>
	<?php
		echo $this->Form->end();
	?>
	</div>
</div>