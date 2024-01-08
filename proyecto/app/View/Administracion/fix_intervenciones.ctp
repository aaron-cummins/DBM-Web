<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5>Fix intervenciones</h5>
		</div>
	  <div class="clear"></div>
	</div>
</div>
<div class="line"></div>
<?php echo $this->Session->flash();?>
<div class="wrapper">
	<div class="widget">
	<?php
		echo $this->Form->create(null, ['url' => ['controller' => 'Administracion', 'action' => 'eliminarfolio']]);
	?>
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Ingrese folios de intervenciones que quiera eliminar, si son mas de una separar con comas. Al eliminar una intervención se eliminarán todos los hijos generados.</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>Folios</td>
				<td><input type="text" name="folios" id="folios" /></td>
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
	<!--
	<div class="widget">
	<?php
		echo $this->Form->create(null, ['url' => ['controller' => 'Administracion', 'action' => 'reiniciarfolio']]);
	?>
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Ingrese folios de intervenciones que quiera arreglar (recuperar elementos perdidos), si son mas de una separar con comas.</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>Folios</td>
				<td><input type="text" name="folios" id="folios" /></td>
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
	</div>-->
</div>