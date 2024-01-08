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
<?php echo $this->Session->flash(); ?>
<div class="wrapper">
	<div class="widget">
	<?php
		echo $this->Form->create('basedatos');
	?>
		<input type="hidden" id="faena_id" name="faena_id" value="<?php echo $faena_id;?>"		/>
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Vaciar Base de Datos Planificación <?php echo $this->Session->read('faena');?></h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td colspan="2" align="center"><?php
					echo $this->Form->button('Vaciar Base de Datos Planificación ' . $this->Session->read('faena'), array('class' => 'greenB', 'id' => 'confimar_vaciar'));
				?></td>
		  </tr>
		</table>
		</fieldset>
	<?php
		echo $this->Form->end();
	?>
	</div>
</div>