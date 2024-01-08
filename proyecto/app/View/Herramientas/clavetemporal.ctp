<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
?>
<div class="titleArea" style="padding-top: 0;">
  <div class="wrapper">
		<div class="pageTitle">
			<h5><?php echo $titulo; ?></h5>
		</div>
	  <div class="clear"></div>
	</div>
</div>
<div class="line"></div>
<div class="wrapper">
	<div class="widget">
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Clave Temporal</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>La clave temporal es válida hasta el 08/Nov/2015</td>
				<td align="center" style="letter-spacing: 2px;"><strong><?php echo $util->getClaveTemporal();?></strong></td>
		  </tr>
		</table>
		</fieldset>
	</div>
</div>