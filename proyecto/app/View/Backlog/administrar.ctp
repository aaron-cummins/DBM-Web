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
			
			$("#flotaid").change(function(){
				var motor_id = $('option:selected', this).attr('motor_id');
				$("#motor_id").val(motor_id);
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
			
			/*$("#sistemaid").change(function(){
				var sistema_motor = $('option:selected', this).attr('sistema_motor');
				$("#sistema_motor").val(sistema_motor);
			});*/
			
			//$("#faenaid").change();
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
<?php echo $this->Session->flash();?>
<div class="wrapper">
       <form method="GET" action="/Backlog/administrar">
	   <input type="hidden" name="motor_id" id="motor_id" value="<?php echo @$motor_id;?>" />
       <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Filtros</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <tbody>
                <tr>
					<td>Faena</td>
					<td>
						<select name="faenaid" id="faenaid">
							<?php if (is_array($faenas)){ ?>
							<option value="0" <?php echo ($faena_id == "0") ? 'selected="selected"' : ''; ?>>Todas</option>
							<?php foreach ($faenas as $key => $value) { ?> 
                            <option value="<?php echo $value["Faena"]["id"];?>" <?php echo ($faena_id == $value["Faena"]["id"]) ? 'selected="selected"' : ''; ?>><?php echo $value["Faena"]["nombre"];?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
					<td>Criticidad</td>
					<td>
						<select name="criticidad" id="criticidad">
							<option value="" <?php echo ($criticidad == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<option value="1" <?php echo ($criticidad == "1") ? 'selected="selected"' : ''; ?>>Alto</option>
							<option value="2" <?php echo ($criticidad == "2") ? 'selected="selected"' : ''; ?>>Medio</option>
							<option value="3" <?php echo ($criticidad == "3") ? 'selected="selected"' : ''; ?>>Bajo</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="15%">Flota</td>
					<td>
						<select name="flotaid" id="flotaid">
							<option value="0" <?php echo ($flota_id == "0") ? 'selected="selected"' : ''; ?>>Todas</option>
							<?php
								foreach ($flotas as $flota) {
									if($faena_id=='0'){
										echo "<option value=\"{$flota["UnidadDetalle"]["flota_id"]}\" faena_id=\"{$flota["UnidadDetalle"]["faena_id"]}\"  motor_id=\"{$flota["UnidadDetalle"]["motor_id"]}\" style=\"display: none;\">{$flota["UnidadDetalle"]["flota"]}</option>"."\n"; 
									}else{
										if($faena_id==$flota["UnidadDetalle"]["faena_id"]){
											echo "<option value=\"{$flota["UnidadDetalle"]["flota_id"]}\" ".(($flota_id == $flota["UnidadDetalle"]["flota_id"]) ? 'selected="selected"' : '')."  motor_id=\"{$flota["UnidadDetalle"]["motor_id"]}\" faena_id=\"{$flota["UnidadDetalle"]["faena_id"]}\">{$flota["UnidadDetalle"]["flota"]}</option>"."\n"; 
										}else{
											echo "<option value=\"{$flota["UnidadDetalle"]["flota_id"]}\" faena_id=\"{$flota["UnidadDetalle"]["faena_id"]}\"  motor_id=\"{$flota["UnidadDetalle"]["motor_id"]}\" style=\"display: none;\">{$flota["UnidadDetalle"]["flota"]}</option>"."\n"; 
										}
									}
								}
							?>
						</select>
					</td>
					<td>Fecha</td>
					<td><input type="date" name="fecha" value="<?php echo $fecha;?>" style="width: 115px;" /> a <input type="date" name="fecha_termino" value="<?php echo $fecha_termino;?>" style="width: 115px;" /></td>
				</tr>
				<tr>
					<td width="10%">Equipo</td>
					<td>
						<select name="equipoid" id="equipoid">
							<option value="0" <?php echo ($equipo_id == "0") ? 'selected="selected"' : ''; ?>>Todas</option>
							<?php
								foreach ($equipos as $equipos) { 
									if($flota_id=='0'){
										echo "<option value=\"{$equipos["Unidad"]["id"]}\" ff_id=\"".$equipos["Unidad"]["faena_id"]."_".$equipos["Unidad"]["flota_id"]."\" style=\"display: none;\">{$equipos["Unidad"]["unidad"]}</option>"."\n"; 
									}else{
										if($faena_id==$equipos["Unidad"]["faena_id"]&&$flota_id==$equipos["Unidad"]["flota_id"]){
											echo "<option value=\"{$equipos["Unidad"]["id"]}\" ff_id=\"".$equipos["Unidad"]["faena_id"]."_".$equipos["Unidad"]["flota_id"]."\" ".(($equipo_id == $equipos["Unidad"]["id"]) ? 'selected="selected"' : '').">{$equipos["Unidad"]["unidad"]}</option>"."\n"; 
										}else{
											echo "<option value=\"{$equipos["Unidad"]["id"]}\" ff_id=\"".$equipos["Unidad"]["faena_id"]."_".$equipos["Unidad"]["flota_id"]."\" style=\"display: none;\">{$equipos["Unidad"]["unidad"]}</option>"."\n"; 
										}
									}
								}
							?>
						</select>
					</td>
					<td>Folio</td>
					<td><input name="folio" value="<?php echo $folio; ?>" type="text" /></td>
				</tr>
				<tr>
                    <td>Sistema</td>
                    <td>
                        <select name="sistemaid" id="sistemaid">
                         <option value="0" <?php echo ($sistema_id == "0") ? 'selected="selected"' : ''; ?>>Todas</option>
                        <?php
                            foreach ($sistemas as $var) { 
								if($flota_id=='0'){
									echo "<option value=\"{$var["Sistema"]["id"]}\" motor_id=\"".$var["Sistema_Motor"]["motor_id"]."\" sistema_motor=\"{$var["Sistema_Motor"]["id"]}\" style=\"display: none;\">{$var["Sistema"]["nombre"]}</option>"."\n"; 
								}else{
									if($motor_id==$var["Sistema_Motor"]["motor_id"]){
										echo "<option value=\"{$var["Sistema"]["id"]}\" motor_id=\"".$var["Sistema_Motor"]["motor_id"]."\" sistema_motor=\"{$var["Sistema_Motor"]["id"]}\" ".(($sistema_id == $var["Sistema"]["id"]) ? 'selected="selected"' : '').">{$var["Sistema"]["nombre"]}</option>"."\n"; 
									}else{
										echo "<option value=\"{$var["Sistema"]["id"]}\" motor_id=\"".$var["Sistema_Motor"]["motor_id"]."\" sistema_motor=\"{$var["Sistema_Motor"]["id"]}\" style=\"display: none;\">{$var["Sistema"]["nombre"]}</option>"."\n"; 
									}
								}
                            }
                        ?>
                    </select>
                    </td>
					<td width="15%">Estado</td>
					<td>
						<select name="estado_id" id="estado_id">
							<option value="0" <?php echo ($estado_id == "0") ? 'selected="selected"' : ''; ?>>Todas</option>
							<option value="8" <?php echo ($estado_id == "8") ? 'selected="selected"' : ''; ?>>Sin Planificar</option>
							<option value="2" <?php echo ($estado_id == "2") ? 'selected="selected"' : ''; ?>>Planificado</option>
							<option value="7" <?php echo ($estado_id == "7") ? 'selected="selected"' : ''; ?>>Sin Revisar</option>
							<option value="4" <?php echo ($estado_id == "4") ? 'selected="selected"' : ''; ?>>Aprobado DCC</option>
							<option value="10" <?php echo ($estado_id == "10") ? 'selected="selected"' : ''; ?>>Eliminado</option>
						</select>
					</td>
					
				</tr>
				<!--
					<tr>
					<td>Folio Gen.</td>
					<td><input name="folio_creador" value="<?php echo $folio_creador; ?>" type="text" /></td>
					<td>Folio Prog.</td>
					<td><input name="folio_programador" value="<?php echo $folio_programador; ?>" type="text" /></td>
					</tr>
					-->
				<tr>
					<td align="right" colspan="4">
						<input type="button" name="create" value="Nuevo" class="greyishB" onclick="window.location='/Backlog/create'; return false;" />
						<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/Backlog/administrar'; return false;" />
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
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Backlogs</h6>
			<div style="float: right;margin: 7px 9px;">
				<a href="/Backlog/xls?<?php echo $_SERVER["QUERY_STRING"];?>"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar</a>
			</div>
		</div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
					  <?php echo "<td width=\"50\">" . $paginator->sort('Backlog.id', 'Folio') . "</td>";?>
					  <?php //echo "<td width=\"50\">" . $paginator->sort('Faena.nombre', 'Faena') . "</td>";?>
					  <?php //echo "<td width=\"50\">" . $paginator->sort('Flota.nombre', 'Flota') . "</td>";?>
					  <?php echo "<td width=\"50\">" . $paginator->sort('Unidad.unidad', 'Equipo') . "</td>";?>
					  <?php echo "<td width=\"50\">" . $paginator->sort('Sistema.nombre', 'Sistema') . "</td>";?>
					  <?php echo "<td width=\"30\">" . $paginator->sort('Backlog.fecha', 'Fecha') . "</td>";?>
					  <?php echo "<td width=\"30\">" . $paginator->sort('Backlog.criticidad', 'Criticidad') . "</td>";?>
					  <?php echo "<td width=\"30\">" . $paginator->sort('Backlog.responsable_id', 'Responsable') . "</td>";?>
					   <?php //echo "<td width=\"30\" style=\"white-space:nowrap;\">" . $paginator->sort('Backlog.folio_creador', 'Folio Gen.') . "</td>";?>
					    <?php //echo "<td width=\"30\" style=\"white-space:nowrap;\">" . $paginator->sort('Backlog.folio_programador', 'Folio Prog.') . "</td>";?>
					 <?php echo "<td width=\"30\">" . $paginator->sort('Backlog.comentario', 'Comentario') . "</td>";?>
					  <?php //echo "<td width=\"30\">" . $paginator->sort('Estado.nombre', 'Estado') . "</td>";?>
					  <?php //echo "<td width=\"30\">" . $paginator->sort('Backlog.e', 'Estado') . "</td>";?>
                  </tr>
              </thead>
              <tbody>
				<?php 
				$i = 1;
				foreach ($resultado as $backlog) {
				?>
					<tr>
						<td style="white-space:nowrap;"><?php print_r($backlog["Backlog"]["id"]);?></td>
						<td style="white-space:nowrap;"><?php print_r($backlog["Unidad"]["unidad"]);?></td>
						<td style="white-space:nowrap;"><?php echo $backlog["Sistema"]["nombre"];?></td>
						<td style="white-space:nowrap;"><?php echo date("d-m-Y",strtotime($backlog["Backlog"]["fecha"]));?></td>
						<td><?php
						switch($backlog["Backlog"]["criticidad"]){
							case '1': echo "Alto"; break;
							case '2': echo "Medio"; break;
							case '3': echo "Bajo"; break;
						}
						?></td>
						<td><?php
						switch($backlog["Backlog"]["responsable_id"]){
							case '1': echo "DCC"; break;
							case '2': echo "OEM"; break;
							case '3': echo "MINA"; break;
						}
						?></td>
						<td><?php print_r($backlog["Backlog"]["comentario"]);?></td>		

						
						
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
	echo $paginator->first("Primera");
	if($paginator->hasPrev()){
		echo $paginator->prev("Anterior");
	}
	echo $paginator->numbers(array('modulus' => 5));
	if($paginator->hasNext()){
		echo $paginator->next("Siguiente");
	}
	echo $paginator->last("Última");
	echo "</div>";
}
?>