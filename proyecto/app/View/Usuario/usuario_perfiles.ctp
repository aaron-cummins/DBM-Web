<script type="text/javascript">
$(document).ready(function(){
	$("#tecnico").change(function(){
		if($(this).is(":checked")){
			$("#administrador").prop('checked',false);
			$("#administrador_correo").prop('checked',false);
			$("#supervisor").prop('checked',false);
			$("#supervisor_correo").prop('checked',false);
			$("#cliente").prop('checked',false);
			$("#cliente_correo").prop('checked',false);
			$("#gestion").prop('checked',false);
			$("#asesortecnico").prop('checked',false);
			$("#planificador").prop('checked',false);
			$("#planificador_correo").prop('checked',false);
		}else{
			$("#tecnico_correo").prop('checked',false);
		}
	});
	
	$("#administrador").change(function(){
		if($(this).is(":checked")){
			$("#tecnico").prop('checked',false);
			$("#tecnico_correo").prop('checked',false);
			$("#supervisor").prop('checked',false);
			$("#supervisor_correo").prop('checked',false);
			$("#cliente").prop('checked',false);
			$("#cliente_correo").prop('checked',false);
			$("#gestion").prop('checked',false);
		}else{
			$("#administrador_correo").prop('checked',false);
		}
	});
	
	$("#supervisor").change(function(){
		if(!$(this).is(":checked")){
			$("#supervisor_correo").prop('checked',false);
		}else{
			$("#administrador").prop('checked',false);
			$("#administrador_correo").prop('checked',false);
			$("#tecnico").prop('checked',false);
			$("#tecnico_correo").prop('checked',false);
			$("#planificador").prop('checked',false);
			$("#planificador_correo").prop('checked',false);
			$("#asesortecnico").prop('checked',false);
			$("#cliente").prop('checked',false);
			$("#cliente_correo").prop('checked',false);
		}
	});
	
	$("#cliente").change(function(){
		if(!$(this).is(":checked")){
			$("#cliente_correo").prop('checked',false);
		}else{
			$("#administrador").prop('checked',false);
			$("#administrador_correo").prop('checked',false);
			$("#tecnico").prop('checked',false);
			$("#tecnico_correo").prop('checked',false);
			$("#asesortecnico").prop('checked',false);
			$("#supervisor").prop('checked',false);
			$("#supervisor_correo").prop('checked',false);
			$("#planificador").prop('checked',false);
			$("#planificador_correo").prop('checked',false);
			$("#gestion").prop('checked',false);
		}
	});
	
	$("#gestion").change(function(){
		if($(this).is(":checked")){
			$("#administrador").prop('checked',false);
			$("#administrador_correo").prop('checked',false);
			$("#tecnico").prop('checked',false);
			$("#tecnico_correo").prop('checked',false);
			$("#asesortecnico").prop('checked',false);
			$("#cliente").prop('checked',false);
			$("#cliente_correo").prop('checked',false);
		}
	});
	
	$("#asesortecnico").change(function(){
		if($(this).is(":checked")){
			$("#administrador").prop('checked',false);
			$("#administrador_correo").prop('checked',false);
			$("#tecnico").prop('checked',false);
			$("#tecnico_correo").prop('checked',false);
			$("#gestion").prop('checked',false);
			$("#cliente").prop('checked',false);
			$("#cliente_correo").prop('checked',false);
			$("#supervisor").prop('checked',false);
			$("#supervisor_correo").prop('checked',false);
		}
	});
	
	$("#planificador").change(function(){
		if($(this).is(":checked")){
			$("#administrador").prop('checked',false);
			$("#administrador_correo").prop('checked',false);
			$("#tecnico").prop('checked',false);
			$("#tecnico_correo").prop('checked',false);
			$("#cliente").prop('checked',false);
			$("#cliente_correo").prop('checked',false);
			$("#supervisor").prop('checked',false);
			$("#supervisor_correo").prop('checked',false);
		}
	});
	
	$("#supervisor_correo").change(function(){
		if(!$("#supervisor").is(":checked")){
			$("#supervisor_correo").prop('checked',false);
		}
	});
	
	$("#cliente_correo").change(function(){
		if(!$("#cliente").is(":checked")){
			$("#cliente_correo").prop('checked',false);
		}
	});
	
	$("#administrador_correo").change(function(){
		if(!$("#administrador").is(":checked")){
			$("#administrador_correo").prop('checked',false);
		}
	});
	
	$("#tecnico_correo").change(function(){
		if(!$("#tecnico").is(":checked")){
			$("#tecnico_correo").prop('checked',false);
		}
	});
	
	$("#planificador_correo").change(function(){
		if(!$("#planificador").is(":checked")){
			$("#planificador_correo").prop('checked',false);
		}
	});
});
</script>
<?php
	foreach($resultado as $usuario){
		//print_r($usuario['UsuarioNivel']);
		$correo='0';
		if($usuario['UsuarioNivel']['correo']=='1'){
			$correo='1';
		}
		switch($usuario['UsuarioNivel']['nivel_id']){
			case 1:
				if($usuario['UsuarioNivel']['e']=='1'){
					$t="1";
					$tc=$correo;
				}else{
					$t="0";
					$tc="0";
				}
			break;
			case 2:
				if($usuario['UsuarioNivel']['e']=='1'){
					$sd="1";
					$sdc=$correo;
				}else{
					$sd="0";
					$sdc="0";
				}
			break;
			case 3:
				if($usuario['UsuarioNivel']['e']=='1'){
					$sc="1";
					$scc=$correo;
				}else{
					$sc="0";
					$scc="0";
				}
			break;
			case 4:
				if($usuario['UsuarioNivel']['e']=='1'){
					$a="1";
					$ac=$correo;
				}else{
					$a="0";
					$ac="0";
				}
			break;
			case 5:
				if($usuario['UsuarioNivel']['e']=='1'){
					$g="1";
					$gc=$correo;
				}else{
					$g="0";
					$gc="0";
				}
			break;
			case 6:
				if($usuario['UsuarioNivel']['e']=='1'){
					$at="1";
					$atc=$correo;
				}else{
					$at="0";
					$atc="0";
				}
			break;
			case 7:
				if($usuario['UsuarioNivel']['e']=='1'){
					$p="1";
					$pc=$correo;
				}else{
					$p="0";
					$pc="0";
				}
			break;
		}
	}
	//print_r(@$resultado[0]['UsuarioNivel']);
	//print_r(@$resultado[1]['UsuarioNivel']);
	//Array ( [0] => Array ( [UsuarioNivel] => Array ( [id] => 9 [usuario_id] => 706 [nivel_id] => 1 [e] => 1 [correo] => 0 ) ) )
	//if($resultado["UsuarioNivel"][]){echo 'checked="checked"';}
	
?>
<form method="post">
<div style="width:80%;margin: 20px auto;">
	<h3>Perfil usuario</h3>
	<br />
	<input type="hidden" name="id" id="id" value="<?php echo $uid;?>">
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td>&nbsp;</td>
    <td align="center">Aplica</td>
    <td align="center">Correo</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Administrador</td>
    <td align="center"><input type="checkbox" name="administrador" id="administrador" value="1"  <?php if(@$a=='1'){echo 'checked="checked"';}?>/></td>
    <td align="center"><input type="checkbox" name="administrador_correo" id="administrador_correo" value="1" <?php if(@$ac=='1'){echo 'checked="checked"';}?>></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Supervisor DCC</td>
    <td align="center"><input type="checkbox" name="supervisor" id="supervisor" value="1"  <?php if(@$sd=='1'){echo 'checked="checked"';}?>></td>
    <td align="center"><input type="checkbox" name="supervisor_correo" id="supervisor_correo" value="1" <?php if(@$sdc=='1'){echo 'checked="checked"';}?>></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Supervisor Cliente</td>
    <td align="center"><input type="checkbox" name="cliente" id="cliente" value="1" <?php if(@$sc=='1'){echo 'checked="checked"';}?>></td>
    <td align="center"><input type="checkbox" name="cliente_correo" id="cliente_correo" value="1" <?php if(@$scc=='1'){echo 'checked="checked"';}?>></td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Planificador</td>
    <td align="center"><input type="checkbox" name="planificador" id="planificador" value="1" <?php if(@$p=='1'){echo 'checked="checked"';}?>></td>
    <td align="center"><input type="checkbox" name="planificador_correo" id="planificador_correo" value="1" <?php if(@$pc=='1'){echo 'checked="checked"';}?>></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Gestión</td>
    <td align="center"><input type="checkbox" name="gestion" id="gestion" value="1" <?php if(@$g=='1'){echo 'checked="checked"';}?>></td>
    <td align="center">&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Asesor Técnico</td>
    <td align="center"><input type="checkbox" name="asesortecnico" id="asesortecnico" value="1" <?php if(@$at=='1'){echo 'checked="checked"';}?>></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>Técnico</td>
    <td align="center"><input type="checkbox" name="tecnico" id="tecnico" value="1" <?php if(@$t=='1'){echo 'checked="checked"';}?>></td>
   <td align="center"><input type="checkbox" name="tecnico_correo" id="tecnico_correo" value="1" <?php if(@$tc=='1'){echo 'checked="checked"';}?>></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
 <tr>
		<td colspan="3" align="right">
			<input type="button" value="Cerrar" class="redB" onclick="window.close();" />
			<input type="submit" value="Guardar" name="btnsubmit" class="greenB" />
		</td>
	  </tr>
</table>
</form>
</div>