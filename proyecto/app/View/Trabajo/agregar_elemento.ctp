<script type="text/javascript">
	$(document).ready(function(){
		$("#ele_sistema_id").change(function(){
			if ($(this).val() == '') {
				return false;
			}
			var sistema_id = $(this).val();
			$.get("/Utilidades/sel_subsistema/<?php echo $motor_id; ?>/"+sistema_id, function(data) {
				var obj = $.parseJSON(data);
				var html = "<option value=\"\"></opcion>\n";
				$.each(obj, function(i, item) {
					html += "<option value=\""+item.Subsistema.id+"\">"+item.Subsistema.nombre+"</opcion>\n";
				});
				$('#ele_subsistema_id').removeAttr("disabled")
				$('#ele_subsistema_id').html(html);
			});
		});
		
		$("#ele_subsistema_id").change(function(){
			if ($(this).val() == '') {
				return false;
			}
			var subsistema_id = $(this).val();
			$.get("/Utilidades/sel_posicion/<?php echo $motor_id; ?>/"+$("#ele_sistema_id").val()+"/"+subsistema_id, function(data) {
				var obj = $.parseJSON(data);
				var html = "<option value=\"\"></opcion>\n";
				$.each(obj, function(i, item) {
					html += "<option value=\""+item.Posicion.id+"\">"+item.Posicion.nombre+"</opcion>\n";
				});
				$('#ele_posicion_subsistema_id').removeAttr("disabled")
				$('#ele_posicion_subsistema_id').html(html);
			});
			
			$.get("/Utilidades/sel_elemento/<?php echo $motor_id; ?>/"+$("#ele_sistema_id").val()+"/"+subsistema_id, function(data) {
				var obj = $.parseJSON(data);
				var html = "<option value=\"\"></opcion>\n";
				$.each(obj, function(i, item) {
					html += "<option value=\""+item.Elemento.id+"\" codigo=\""+item.Sistema_Subsistema_Motor_Elemento.codigo+"\">"+item.Sistema_Subsistema_Motor_Elemento.codigo+ " " +item.Elemento.nombre+"</opcion>\n";
				});
				$('#ele_elemento_id').removeAttr("disabled")
				$('#ele_elemento_id').html(html);
			});
		});
		
		$("#ele_elemento_id").change(function(){
			if ($(this).val() == '') {
				return false;
			}
			var codigo = $('option:selected', this).attr('codigo');
			$.get("/Utilidades/sel_posicion_elemento/<?php echo $motor_id; ?>/"+$("#ele_sistema_id").val()+"/"+$("#ele_subsistema_id").val()+"/"+codigo+"/"+$("#ele_elemento_id").val(), function(data) {
				var obj = $.parseJSON(data);
				var html = "<option value=\"\"></opcion>\n";
				$.each(obj, function(i, item) {
					html += "<option value=\""+item.Posicion.id+"\">"+item.Posicion.nombre+"</opcion>\n";
				});
				$('#ele_posicion_elemento_id').removeAttr("disabled")
				$('#ele_posicion_elemento_id').html(html);
			});
		});
		$("#ele_posicion_elemento_id").change(function(){
			if ($(this).val() == '') {
				return false;
			}
			var codigo = $('option:selected', this).attr('codigo');
			$.get("/Utilidades/sel_diagnostico/<?php echo $motor_id; ?>/"+$("#ele_sistema_id").val()+"/"+$("#ele_subsistema_id").val()+"/"+$("#ele_elemento_id").val(), function(data) {
				var obj = $.parseJSON(data);
				var html = "<option value=\"\"></opcion>\n";
				$.each(obj, function(i, item) {
					html += "<option value=\""+item.Diagnostico.id+"\">"+item.Diagnostico.nombre+"</opcion>\n";
				});
				$('#ele_diagnostico_id').removeAttr("disabled")
				$('#ele_diagnostico_id').html(html);
			});
		});
	});
</script>
<div style="widht:100%;margin:3%;">
	<h3 style="padding-left:10px;">Agregar Elemento</h3>
	<br />
	<table border="0" width="100%" cellpadding="5" cellspacing="0">
		<tr>
			<td width="50%" style="font-size: 1.5em;padding:10px;">Sistema</td>
			<td>
				<select id="ele_sistema_id" name="ele_sistema_id">
					<option value=""></option>
					<?php foreach ($sistemas as $sistema) { ?>
					<option value="<?php echo $sistema["Sistema"]["id"];?>"><?php echo $sistema["Sistema"]["nombre"];?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="font-size: 1.5em;padding:10px;">Subsistema</td>
			<td>
				<select id="ele_subsistema_id" name="ele_subsistema_id" disabled="disabled">
				</select>
			</td>
		</tr>
		<tr>
			<td style="font-size: 1.5em;padding:10px;">Posición Subsistema</td>
			<td>
				<select id="ele_posicion_subsistema_id" name="ele_posicion_subsistema_id" disabled="disabled">
				</select>
			</td>
		</tr>
		<tr>
			<td style="font-size: 1.5em;padding:10px;">Elemento</td>
			<td>
				<select id="ele_elemento_id" name="ele_elemento_id" disabled="disabled">
				</select>
			</td>
		</tr>
		<tr>
			<td style="font-size: 1.5em;padding:10px;">Posición Elemento</td>
			<td>
				<select id="ele_posicion_elemento_id" name="ele_posicion_elemento_id" disabled="disabled">
				</select>
			</td>
		</tr>
		<tr>
			<td style="font-size: 1.5em;padding:10px;">Diagnóstico</td>
			<td>
				<select id="ele_diagnostico_id" name="ele_diagnostico_id" disabled="disabled">
				</select>
			</td>
		</tr>
		<tr>
			<td style="font-size: 1.5em;padding:10px;">Solución</td>
			<td>
				<select id="ele_solucion_id" name="ele_solucion_id">
					<option value=""></option>
					<?php foreach ($soluciones as $solucion) { ?>
					<option value="<?php echo $solucion["Solucion"]["id"];?>"><?php echo $solucion["Solucion"]["nombre"];?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="font-size: 1.5em;padding:10px;">Tipo</td>
			<td>
				<select id="ele_tipo_id" name="ele_tipo_id">
					<option value=""></option>
					<option value="1">Falla</option>
					<option value="2">Consecuencia</option>
					<option value="3">Oportunidad</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="font-size: 1.5em;padding:10px;">Duración</td>
			<td>
				<input name="d3_elemento_h" type="text" id="d3_elemento_h" pattern="[0-9]*" min="0" style="width: 30px !important;" />:<select name="d3_elemento_m" id="d3_elemento_m"><?php
					for ($j = 0; $j < 60; $j++) {
						$value = sprintf("%02d", $j);
						echo "<option value=\"$value\">$value</option>"."\n";
					}
				?></select>
			</td>
		</tr>
	</table>
	<p style="margin-top: 30px; clear: both; margin-bottom:20px;height:70px;">
		<input type="button" name="cancelar" value="Cancelar" class="basic" style="float: left;" onclick="window.close();" />
		<input type="submit" name="guardar_elemento" value="Guardar" style="float: right; margin-right: 3px;" class="greenB" />
	</p>
<div>