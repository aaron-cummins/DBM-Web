<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$paginator = $this->Paginator;
	$faenas = $this->Session->read('faenas'); 
?>
<script>
	$(document).ready(function(){
		$("#faena_id").unbind("change");
		$("#faena_id").change(function(){
			var val=$(this).val();
			if(val!=""){
				$("#uid option").hide();
				$("#uid option[value='']").show();
				$("#uid option[fid='"+val+"']").show();
			}else{
				$("#uid option").show();
			}
		});
		$("#faena_id").change();
		
		$("#fid").unbind("change");
		$("#fid").change(function(){
			var val=$(this).val();
			if(val!=""){
				$("#nuid option").hide();
				$("#nuid option[value='']").show();
				$("#nuid option[fid='"+val+"']").show();
			}else{
				$("#nuid option").hide();
			}
		});
		$("#fid").change();
		
		$(function() {
			$("#faenaid").change(function(){
				var val = $(this).val();
				$("#flotaid option").hide();
				$("#equipoid option").hide();
				$("#sistemaid option").hide();
				$("#equipoid option[value='0']").show();
				$("#flotaid option[value='0']").show();
				$("#sistemaid option[value='0']").show();
				
				$("#flotaid").val("0");
				$("#equipoid").val("0");
				$("#sistemaid").val("0");
				
				if(val!=''){
					$("#flotaid option[faena_id='"+val+"']").show();
					$("#flotaid").change();
				}
			});
			
			<?php
				if($this->Session->read('faena_id') != "" && $this->Session->read('faena_id')!= "0") { 
			?>
				$("#faenaid").val(<?php echo $this->Session->read('faena_id');?>);
				$("#faenaid").attr("disabled","disabled");
			<?php
				}
			?>
			
			$("#flotaid").change(function(){
				var motor_id = $('option:selected', this).attr('motor_id');
				var val = $("#faenaid").val()+"_"+$(this).val();
				$("#equipoid option").hide();
				$("#sistemaid option").hide();
				$("#equipoid").val("0");
				$("#sistemaid").val("0");
				if(val!=''){
					$("#equipoid option[value='0']").show();
					$("#equipoid option[ff_id='"+val+"']").show();
					$("#equipoid").change();
					$("#sistemaid option[value='0']").show();
					$("#sistemaid option[motor_id='"+motor_id+"']").show();
					$("#sistemaid").change();
				}
			});
			
			$("#sistemaid").change(function(){
				var sistema_motor = $('option:selected', this).attr('sistema_motor');
				$("#sistema_motor").val(sistema_motor);
			});
			
			$("#faenaid").change();
		});
	});
</script>
<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5>Backlogs</h5>
		</div>
	  <div class="clear"></div>
	</div>
</div>
<div class="line"></div>
<pre>
<?php 
	//print_r($backlogs);
?>
</pre>
<?php echo $this->Session->flash();?>
<!-- Ingreso de Nuevo Backlog -->
<div class="wrapper">
   <form method="POST" action="/Backlog/create">
   <input type="hidden" name="sistema_motor" id="sistema_motor" value="" />
   <div class="widget">
    <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Nuevo Backlog</h6></div>
      <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
          <tbody>
            <tr>
                <td width="20%">Faena</td>
                <td width="30%">
					<select name="faenaid" id="faenaid">
                    <option value="0" <?php echo ($faena_id == "0") ? 'selected="selected"' : ''; ?>></option>
                    <?php
                        foreach ($faenas as $faena) { 
                            echo "<option value=\"{$faena["Faena"]["id"]}\">{$faena["Faena"]["nombre"]}</option>"."\n"; 
                        }
                    ?>
                    </select>
                </td>
				 <td width="20%"> Criticidad</td>
                <td>
                    <select name="criticidad">
                    	<option value="0"></option>
                        <option value="1">Alto</option>
                        <option value="2">Medio</option>
                        <option value="3">Bajo</option>
                    </select>
                </td>
               
				</tr>
				<tr>
					<td width="15%">Flota</td>
					<td>
						<select name="flotaid" id="flotaid">
							<option value="0" <?php echo ($flota_id == "0") ? 'selected="selected"' : ''; ?>></option>
							<?php
								foreach ($flotas as $flota) { 
									echo "<option value=\"{$flota["UnidadDetalle"]["flota_id"]}\" ".(($flota_id == $flota["UnidadDetalle"]["flota_id"]) ? 'selected="selected"' : '')." faena_id=\"{$flota["UnidadDetalle"]["faena_id"]}\" motor_id=\"{$flota["UnidadDetalle"]["motor_id"]}\">{$flota["UnidadDetalle"]["flota"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
					
				 <td>Fecha</td>
                <td><input type="date" name="fecha" max="<?php echo date("Y-m-d");?>" /></td>
				</tr>
				<tr>
                <td width="10%">Equipo</td>
                <td>
                    <select name="equipoid" id="equipoid">
                    <option value="0" <?php echo ($equipo_id == "0") ? 'selected="selected"' : ''; ?>></option>
                    <?php
                        foreach ($equipos as $equipos) { 
                            echo "<option value=\"{$equipos["Unidad"]["id"]}\" ff_id=\"".$equipos["Unidad"]["faena_id"]."_".$equipos["Unidad"]["flota_id"]."\">{$equipos["Unidad"]["unidad"]}</option>"."\n"; 
                        }
                    ?>
                    </select>
                </td>
				<td>Comentario</td>
                <td><input type="text" name="comentario" id="comentario" /></td>
                
            </tr>
            <tr>   
            </tr>
            <tr>
               <td>Sistema</td>
                <td>
                    <select name="sistemaid" id="sistemaid">
                         <option value="0" <?php echo ($sistema_id == "0") ? 'selected="selected"' : ''; ?>></option>
                        <?php
                            foreach ($sistemas as $var) { 
                                echo "<option value=\"{$var["Sistema"]["id"]}\" motor_id=\"".$var["Sistema_Motor"]["motor_id"]."\" sistema_motor=\"{$var["Sistema_Motor"]["id"]}\">{$var["Sistema"]["nombre"]}</option>"."\n"; 
                            }
                        ?>
                    </select>
                </td>
				<td>Responsable</td>
                <td>
					<select name="responsable_id">
                    	<option value="0"></option>
                        <option value="1">DCC</option>
                        <option value="2">OEM</option>
                        <option value="3">MINA</option>
                    </select>
				</td>
                </tr>
				<tr>
				<td></td>
                <td></td>
                <td align="right" colspan="2">
					<input type="button" name="cancel" value="Cancelar" class="redB" onclick="window.location='/Backlog/administrar'; return false;" />
                    <input type="submit" value="Aceptar" class="greenB btnSubmit" />
                </td>
                </tr>
            </tbody>
        </table>
    </div>
    <input type="hidden" name="e" value="1" /> 
    </form>
</div>