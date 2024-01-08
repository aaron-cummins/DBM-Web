<form method="post">
<div style="width:80%;margin: 20px auto;">
	<h3>Editar usuario</h3>
	<br />
	<input type="hidden" name="id" id="id" value="<?php echo $resultado["Usuario"]["id"];?>">
	<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
	  <tr>
		<td width="25%">Nombres</td>
		<td><input type="text" name="nombres" id="nombres" value="<?php echo $resultado["Usuario"]["nombres"];?>"></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>Apellidos</td>
		<td><input type="text" name="apellidos" id="apellidos" value="<?php echo $resultado["Usuario"]["apellidos"];?>"></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>Rut</td>
		<td><input type="text" readonly="readonly" name="usuario" id="usuario" value="<?php echo $resultado["Usuario"]["usuario"];?>"></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>Correo</td>
		<td><input type="text" name="correo_electronico" id="correo_electronico" value="<?php echo $resultado["Usuario"]["correo_electronico"];?>"></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td colspan="2" align="right">
			<input type="button" value="Cerrar" class="redB" onclick="window.close();" />
			<input type="submit" value="Guardar" name="btnsubmit" class="greenB" />
		</td>
	  </tr>
	</table>
</div>
</form>