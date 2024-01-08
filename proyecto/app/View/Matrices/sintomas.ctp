<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$paginator = $this->Paginator;
?>
<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5>Sintomas</h5>
		</div>
	  <div class="clear"></div>
	</div>
</div>
<div class="line"></div>
<?php echo $this->Session->flash();?>
<div class="wrapper">
       <form method="GET" action="/matrices/sintomas">
       <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Filtros</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <tbody>
                <tr>
					<td width="10%">Categoria</td>
					<td>
						<select name="categoria_id" id="categoria_id">
							<option value="" <?php echo ($categoria_id == "") ? 'selected="selected"' : ''; ?>>Todas</option>
						<?php
							foreach ($categorias as $var){
								echo "<option value=\"{$var["SintomaCategoria"]["id"]}\" ".(($categoria_id==$var["SintomaCategoria"]["id"]) ? 'selected="selected"' : '').">{$var["SintomaCategoria"]["nombre"]}</option>"."\n"; 
							}
						?>
						</select>
					</td>
					<td>Codigo</td>
                    <td><input name="codigo" value="<?php echo $codigo; ?>" type="text" /></td>
				</tr>
				<tr>
                    
					<td>Nombre</td>
					<td><input name="nombre" value="<?php echo $nombre; ?>" type="text" /></td>
					<td>Estado</td>
					<td>
						<select id="e" name="e">
						<option value="">Todos</option>
						<option value="1" <?php echo ($e == "1") ? 'selected="selected"' : ''; ?>>Activo</option>
						<option value="0" <?php echo ($e == "0") ? 'selected="selected"' : ''; ?>>Inactivo</option>
					</select>
					</td>
				</tr>
				<tr>
					<td align="right" colspan="4">
						<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/matrices/sintomas'; return false;" />
						<input type="submit" name="filtro_aceptar" value="Aceptar" class="greenB" />
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		</form>
	</div>
<div class="wrapper">
	<div class="widget">
	<?php
		if (count($resultado)>0) { 
	?>
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Sintomas</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
					  <?php echo "<td  style=\"width: 30px;\">" . $paginator->sort('Sintoma.id', 'Código') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Categoria.nombre', 'Catergoria') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Sintoma.codigo', 'FC') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Sintoma.nombre', 'Sintoma') . "</td>";?>
					  <?php echo "<td width=\"30\">" . $paginator->sort('Sintoma.e', 'Estado') . "</td>";?>
                  </tr>
              </thead>
              <tbody>
				<?php 
				$i = 1;
				foreach ($resultado as $v) {
				?>
					<tr>
						<td><?php echo $v["Sintoma"]["id"];?></td>
						<td><?php echo $v["Categoria"]["nombre"];?></td>
						<td>
							<?php 
								if($v["Sintoma"]["codigo"]!=""&&$v["Sintoma"]["codigo"]!="0"){
									echo $v["Sintoma"]["codigo"];
								}else{
									echo "";
								}
							?>
						</td>
						<td><?php echo $v["Sintoma"]["nombre"];?></td>
						<td align="center">
							<?php if($v["Sintoma"]["e"]=='1'){?>
							<a href="/Matrices/sintomas/<?php echo $v["Sintoma"]["id"];?>/0">
								<img src="/images/icons/control/32/check.png" width="20" title="Click para desactivar" />
							</a>
							<?php }?>
							<?php if($v["Sintoma"]["e"]=='0'){?>
							<a href="/Matrices/sintomas/<?php echo $v["Sintoma"]["id"];?>/1">
								<img src="/images/icons/control/32/check.png" width="20" style="opacity:0.2;" title="Click para activar" />
							</a>
							<?php }?>
						</td>
					</tr>
				<?php
				}
				?>
			  </tbody>
		  </table>
	<?php
		}		
	?>
</div>
</div>
<?php
if (count($resultado)>0) { 
	echo "<div class='paging'>";
	//echo $paginator->first("Primera");
	if($paginator->hasPrev()){
		echo $paginator->prev("Anterior");
	}
	echo $paginator->numbers(array('modulus' => 5));
	if($paginator->hasNext()){
		echo $paginator->next("Siguiente");
	}
	//echo $paginator->last("Última");
	echo "</div>";
}
?>

<br />
<div class="line"></div>
<div class="wrapper">
	<div class="widget">
		<form method="post">
		<input type="hidden" name="id" id="id" value="" />
		<fieldset>
		<div class="title"><img src="/images/icons/dark/list.png" alt="" class="titleIcon"><h6>Nuevo Sintoma</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
			<tr>
				<td>Categoria</td>
				<td>
					<select name="sintoma_categoria_id">
						<option value=""></option>
					<?php
						foreach ($categorias as $var){
							echo "<option value=\"{$var["SintomaCategoria"]["id"]}\">{$var["SintomaCategoria"]["nombre"]}</option>"."\n"; 
						}
					?>
					</select>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td width="200">Codigo</td>
				<td><input type="number" name="codigo" /></td>
				<td width="200">Nombre</td>
				<td><input type="text" name="nombre" /></td>
			</tr>
			 <tr>
                <td align="right" colspan="4">
                    <input type="reset" value="Cancelar" class="redB" />
                    <input type="submit" value="Aceptar" name="btnNuevo" class="greenB" />
                </td>
            </tr>
		</table>
		</fieldset>
		</form>
	</div>
</div>