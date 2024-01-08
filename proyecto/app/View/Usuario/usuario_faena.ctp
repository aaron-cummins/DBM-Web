<script type="text/javascript">
$(document).ready(function(){
	$("#perfil_cambio").change(function(){
		window.location='/Usuario/usuario_faena/'+$("#id").val()+'/'+$(this).val();
		return false;
	});
	
	if($("#perfil_cambio").val()=="1"||$("#perfil_cambio").val()=="2"){
		$("input[type=checkbox]").change(function(){
			if($(this).is(":checked")){
				$("input[type=checkbox]").prop('checked',false);
				$(this).prop('checked',true);
			}
		});
	}
	
	<?php
		if(isset($usuario_faenas)){
			foreach(@$usuario_faenas as $u){
				echo "$('input[id=f{$u["UsuarioFaena"]["faena_id"]}]').prop('checked',true);\n";
			}
		}
	?>
});
</script>
<style>
	#footer{
		display:none;
	}
</style>
<?php
	//@print_r($usuario_faenas);
?>
<form method="post">
<div style="width:80%;margin: 20px auto;clear:both;">
	<h3>Faena usuario</h3>
	<br />
	<input type="hidden" name="id" id="id" value="<?php echo $uid;?>">
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td>Cargo</td>
    <td align="center">
		<select name="perfil_cambio" id="perfil_cambio">
			<option value="0">Seleccione cargo</option>
		<?php
			foreach($niveles as $nivel){
				echo "<option value=\"{$nivel["Nivel"]["id"]}\"".(@$pid==$nivel["Nivel"]["id"]?' selected="selected"':'').">{$nivel["Nivel"]["nombre"]}</option>";
			}
		?>
		</select>
	</td>
  </tr>
  </table>
  <br />
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td align="center">Aplica</td>
    <td align="center">Faena</td>
	<td align="center">Aplica</td>
    <td align="center">Faena</td>
  </tr>
  <tr>
    <td colspan="4" align="right">&nbsp;</td>
  </tr>
  <?php
	for($i=0;$i<count($faenas);$i++){
	//foreach($faenas as $k=>$faena){
  ?>
  <tr>
	<?php if(@$faenas[$i]["Faena"]["id"]!=''){ ?>
    <td align="center"><input type="checkbox" name="faena[]" id="f<?php echo @$faenas[$i]["Faena"]["id"];?>" value="<?php echo @$faenas[$i]["Faena"]["id"];?>" /></td>
	<td center> <label for="f<?php echo @$faenas[$i]["Faena"]["id"];?>"><?php echo @$faenas[$i]["Faena"]["nombre"];?></label></td>
	<?php } ?>
	<?php if(@$faenas[$i+1]["Faena"]["id"]!=''){ ?>
	<td align="center"><input type="checkbox" name="faena[]" id="f<?php echo @$faenas[$i+1]["Faena"]["id"];?>" value="<?php echo @$faenas[$i+1]["Faena"]["id"];?>" /></td>
	<td center> <label for="f<?php echo @$faenas[$i+1]["Faena"]["id"];?>"><?php echo @$faenas[$i+1]["Faena"]["nombre"];?></label></td>
	<?php } ?>
  </tr>
  <?php
	$i++;
  } ?>
  <tr>
    <td colspan="4" align="right">&nbsp;</td>
  </tr>
 <tr>
		<td colspan="4" align="right">
			<input type="button" value="Cerrar" class="redB" onclick="window.close();" />
			<input type="submit" value="Guardar" name="btnsubmit" class="greenB" />
		</td>
	  </tr>
	  </table>
</div>
</form>