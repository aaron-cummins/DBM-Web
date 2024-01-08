 <?php
	ini_set('memory_limit','1024M');
	App::import('Controller', 'Utilidades');
	$usuario = $this->Session->read('Usuario');
	$util = new UtilidadesController();
	$paginator = $this->Paginator;
?>   
<?php if ($usuario["nivelusuario_id"] == '4') { ?>
<script type="text/javascript">
$(document).ready(function(){
	$(".imprimir").click(function(){
		var f = $(this).attr("f");
		window.open('/Planificacion/p/'+f, "Imprimir Folio", 'width=800,height=600,scrollbars=yes,menubar=no,toolbars=no');
		return false;
	});
	
	$(".elint").click(function(){
		var f = $(this).attr("f");
		var e = $(this).attr("e"); 
		var h = $(this).attr("h");
		var p = $(this).attr("p");
		var mensaje = "";
		if (h != "0") {
			mensaje = " Además se eliminarán las intervenciones con folio " + h + ".";
		}
		
		if (e == "2" && p != "") {
			alert("Esta intervención no puede ser eliminada.");
			return false;	
		}
		
		if (e == "2") {
			if (confirm("La intervención con folio "+f+" será eliminada, ¿Confirma esta operación?" + mensaje)) {
				window.location = '/Planificacion/elint/'+f;
				return false;
			} else {
				return false;
			}
		} else {
			if (confirm("La intervención con folio "+f+" será eliminada, ¿Confirma esta operación?"+mensaje)) {
				window.location = '/Planificacion/elint/'+f;
				return false;
			} else {
				return false;
			}
		}
	});
	
	$("#estado").change(function(){
		if($(this).val()=='4'){
			$("#aprobador_id").show();
		}else{
			$("#aprobador_id").hide();
			$("#aprobador_id").val("");
		}
	});
});
</script>
<?php } ?>
    <div class="titleArea" style="padding-top: 0;">
      <div class="wrapper">
            <div class="pageTitle">
                <h5>Planificaciones</h5>
                <span>Historial</span>
            </div>
          <div class="clear"></div>
        </div>
    </div>
    
    
    <!-- Page statistics area -->
    
    
    <div class="line"></div>
    <?php echo $this->Session->flash(); ?>
	<div class="wrapper">
       <form method="GET" action="/Planificacion/historial">
		<input type="hidden" name="faena_id" id="faena_id" value="<?php echo $this->Session->read('faena_id');?>" />
       <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Filtro</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <tbody>
                <tr>
					<td width="10%">Flota</td>
					<td>
						<select name="flota_id_h" id="flota_id_h">
							<option value="" <?php echo ($flota_id == "") ? 'selected="selected"' : ''; ?>>Todas</option>
							<?php
								foreach ($flotas as $flota) { 
									echo "<option value=\"{$flota["UnidadDetalle"]["flota_id"]}\" ".(($flota_id == $flota["UnidadDetalle"]["flota_id"]) ? 'selected="selected"' : '').">{$flota["UnidadDetalle"]["flota"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
					<td width="10%">Equipo</td>
					<td>
						<select name="unidad_id_h" id="unidad_id_h" unidad_id="<?php echo $unidad_id;?>">
							<option value="">Todos</option>
						</select>
					</td>
					<td width="10%">Evento</td>
					<td>
						<select name="tipo_evento" id="tipo_evento">
							<option value="" <?php echo ($tipo_evento == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<option value="PR" <?php echo ($tipo_evento == "PR") ? 'selected="selected"' : ''; ?>>Programado</option>
							<option value="NP" <?php echo ($tipo_evento == "NP") ? 'selected="selected"' : ''; ?>>No Programado</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Correlativo</td>
					<td><input name="correlativo" value="<?php echo $correlativo; ?>" type="text" /></td>
					<td>Estado</td>
					<td>
						<select name="estado" id="estado">
							<option value="0" <?php echo ($estado == "0") ? 'selected="selected"' : ''; ?>>Todos</option>
                            <option value="1" <?php echo ($estado == "1") ? 'selected="selected"' : ''; ?>>Borrador</option>
							<option value="2" <?php echo ($estado == "2") ? 'selected="selected"' : ''; ?>>Planificado</option>
							<option value="7" <?php echo ($estado == "7") ? 'selected="selected"' : ''; ?>>Sin Revisar</option>
							<option value="4" <?php echo ($estado == "4") ? 'selected="selected"' : ''; ?>>Aprobado DCC</option>
							<option value="6" <?php echo ($estado == "6") ? 'selected="selected"' : ''; ?>>Rechazado Cliente</option>
							<option value="5" <?php echo ($estado == "5") ? 'selected="selected"' : ''; ?>>Aprobado Cliente</option>
							<?php 
								if($this->Session->read('esAdmin')=='1'){
									?>
									<option value="10" <?php echo ($estado == "10") ? 'selected="selected"' : ''; ?>>Eliminado</option>
									<?php
								}
							?>
						</select>
						<?php
						if(isset($this->request->query['aprobador_id'])&&$this->request->query['aprobador_id']!=""){
							$display="inline-block;";
						}else{
							$display="none;";
							$aprobador_id="";
						}
						?>
						<select name="aprobador_id" id="aprobador_id" style="display:<?php echo $display;?>">
							<option value="" <?php echo ($aprobador_id == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<?php
								foreach ($supervisores as $v) { 
									echo "<option value=\"{$v["Usuario"]["id"]}\" ".(($aprobador_id == $v["Usuario"]["id"]) ? 'selected="selected"' : '').">{$v["Usuario"]["nombres"]} {$v["Usuario"]["apellidos"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
					<td width="10%">Tipo</td>
					<td>
						<select name="tipo_intervencion" id="tipo_intervencion" tipo_intervencion="<?php echo $tipo_intervencion;?>">
							<option value="" <?php echo ($tipo_intervencion == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<option value="MP" <?php echo ($tipo_intervencion == "MP") ? 'selected="selected"' : ''; ?>>MP</option>
							<option value="RP" <?php echo ($tipo_intervencion == "RP") ? 'selected="selected"' : ''; ?>>RP</option>
							<option value="OP" <?php echo ($tipo_intervencion == "OP") ? 'selected="selected"' : ''; ?>>OP</option>
							<option value="EX" <?php echo ($tipo_intervencion == "EX") ? 'selected="selected"' : ''; ?>>EX</option>
							<option value="RI" <?php echo ($tipo_intervencion == "RI") ? 'selected="selected"' : ''; ?>>RI</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Folio</td>
					<td><input name="codigo" value="<?php echo $codigo; ?>" type="text" /></td>
					<td>Fecha Inicio</td>
					<td><input type="date" name="fecha" value="<?php echo $fecha;?>" style="width: 115px;" /> a <input type="date" name="fecha_termino" value="<?php echo $fecha_termino;?>" style="width: 115px;" /></td>
					<td align="right" colspan="2">
						<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/Planificacion/historial'; return false;" />
						<input type="submit" name="filtro_aceptar" value="Aceptar" class="greenB" />
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		</form>
	</div>
	
    <!-- Main content wrapper -->
	<div class="wrapper">
        
        <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Historial</h6><!--<a href="./export_xls?<?php echo $_SERVER['QUERY_STRING'];?>" style="float: right;margin-right: 8px;margin-top: 8px;">Exportar XLS</a>--></div>
		<?php 
			
			if (count($intervenciones) > 0) {
		?>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable dsp_resultados">
              <thead>
                  <tr>
                      <td width="20">#</td>
                      <?php if (intval($this->Session->read('faena_id')) == 0) { ?>
                      <td  style="min-width: 50px;"><a href="/Planificacion/historial?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "faena:asc") {
						echo "&order=faena:desc";
					  } else {
						echo "&order=faena:asc";
					  }
					  if(@$this->request->query["order"] == "faena:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "faena:desc"){
						$o="d";
					  }
					  
					  ?>" style="display: block;">Faena<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
                      <?php } ?>
					  <td  style="min-width: 50px;"><a href="/Planificacion/historial?<?php echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "correlativo_final:asc") {
						echo "&order=correlativo:desc";
					  } else {
						echo "&order=correlativo:asc";
					  }
					  $o="";
					  if(@$this->request->query["order"] == "correlativo_final:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "correlativo_final:desc"){
						$o="d";
					  }
					  ?>" style="display: block;">Correlativo<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
                      <td  style="min-width: 50px;"><a href="/Planificacion/historial?<?php
					  echo $url; 
					  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "id:asc") {
						echo "&order=id:desc";
					  } else {
						echo "&order=id:asc";
					  }
					  $o="";
					  if(@$this->request->query["order"] == "id:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "id:desc"){
						$o="d";
					  }
					  ?>" style="display: block;">Folio<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
                      <td  style="min-width: 50px;"><a href="/Planificacion/historial?<?php echo $url; 
					 if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "fecha:asc") {
						echo "&order=fecha:desc";
					  } else {
						echo "&order=fecha:asc";
					  }
					 $o="";
					  if(@$this->request->query["order"] == "fecha:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "fecha:desc"){
						$o="d";
					  }
					  ?>" style="display: block;">Inicio<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
                      <td  style="min-width: 50px;"><a href="/Planificacion/historial?<?php echo $url; 
					  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "flota_id:asc") {
						echo "&order=flota_id:desc";
					  } else {
						echo "&order=flota_id:asc";
					  }
					  $o="";
					  if(@$this->request->query["order"] == "flota_id:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "flota_id:desc"){
						$o="d";
					  }
					  ?>" style="display: block;">Flota<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
                      <td  style="min-width: 50px;"><a href="/Planificacion/historial?<?php echo $url; 
					  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "unidad_id:asc") {
						echo "&order=unidad_id:desc";
					  } else {
						echo "&order=unidad_id:asc";
					  }
					  $o="";
					  if(@$this->request->query["order"] == "unidad_id:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "unidad_id:desc"){
						$o="d";
					  }
					  ?>" style="display: block;">Equipo<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
                      <td  style="min-width: 50px;">ESN</td>
                      <td  style="min-width: 50px;" nowrap><a href="/Planificacion/historial?<?php echo $url; 
					  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "tipointervencion:asc") {
						echo "&order=tipointervencion:desc";
					  } else {
						echo "&order=tipointervencion:asc";
					  }
					  $o="";
					  if(@$this->request->query["order"] == "tipointervencion:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "tipointervencion:desc"){
						$o="d";
					  }
					  ?>" style="display: block;min-width:50px;">Tipo<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
					  
                      <td>Descripción</td>
					  <td  style="min-width: 50px;"><a href="/Planificacion/historial?<?php echo $url; 
					if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "duracion:asc") {
						echo "&order=duracion:desc";
					  } else {
						echo "&order=duracion:asc";
					  }
					$o="";
					  if(@$this->request->query["order"] == "duracion:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "duracion:desc"){
						$o="d";
					  }
					  ?>" style="display: block;">Duración<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
                      <td nowrap style="width: 70px;">Comentario</td>
                      <!--<td nowrap style="width: 70px;">C. Técnico</td>-->
                      <td style="min-width: 50px;"><a href="/Planificacion/historial?<?php echo $url; 
					if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "estado:asc") {
						echo "&order=estado:desc";
					  } else {
						echo "&order=estado:asc";
					  }
					$o="";
					  if(@$this->request->query["order"] == "estado:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "estado:desc"){
						$o="d";
					  }
					  ?>" style="display: block;">Estado<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
					  <?php if (isset($this->request->query) && isset($this->request->query["estado"]) && $this->request->query["estado"]=='4') { ?>
					  <td style="min-width: 50px;"><a href="/Planificacion/historial?<?php echo $url; 
					if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "supervisor:asc") {
						echo "&order=supervisor:desc";
					  } else {
						echo "&order=supervisor:asc";
					  }
					$o="";
					  if(@$this->request->query["order"] == "supervisor:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "supervisor:desc"){
						$o="d";
					  }
					  ?>" style="display: block;">Supervisor DCC<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
					<?php } ?>
					<!-- <td width="50" align="center">Sinc</td>-->
					  <td width="60">Acción</td>
                </tr>
              </thead>
              <tbody>
			  <?php 
				$i = 25*($paginator->current()-1)+1;
				foreach ($intervenciones as $intervencion) {
					if ($intervencion['Planificacion']['estado'] == null && !is_numeric($intervencion['Planificacion']['estado'])) {
						continue;
					}
					
					$json = json_decode($intervencion["Planificacion"]["json"], true);
					$fecha_sincronizacion = new DateTime($intervencion['Planificacion']['fecha_sincronizacion']);

					$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					$fecha_termino = $date;
					if ($intervencion['Planificacion']['fecha_termino'] != null && $intervencion['Planificacion']['hora_termino'] != null) {
						$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
					}
					$diff = date_diff($fecha_termino, $date);
					$total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60) * 60;
					$h = floor($total / 60);
					$m = $total - $h * 60;
					$h = str_pad($h, 2, "0", STR_PAD_LEFT);
					$m = str_pad($m, 2, "0", STR_PAD_LEFT);
					$total = $h.":".$m;
			  ?>
                  <tr>
				  <td nowrap><?php echo $i++;?></td>
				  <?php if (intval($this->Session->read('faena_id')) == 0) { ?>
					<td nowrap><?php echo $intervencion['Faena']['nombre'];?></td>
					<?php } ?>
					<td><?php echo $intervencion['Planificacion']['correlativo_final'];?></td>
					<td><?php echo $intervencion['Planificacion']['id'];?></td>
					<td nowrap><?php echo $date->format('d-m-Y h:i A'); ?></td>
					<td nowrap><?php echo $intervencion['Flota']['nombre']; ?></td>
					<td nowrap><?php echo $intervencion['Unidad']['unidad']; ?></td>
						<td><?php
						$esn_ = $util->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$util->getMotor($intervencion['Planificacion']['unidad_id']),$util->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
						echo $esn_;
						$esn = "";
						if($esn_==''){
							if (@$json["esn_conexion"] != "") {
								$esn =  @$json["esn_conexion"];
							} elseif (@$json["esn_nuevo"] != "") {
								$esn = @$json["esn_nuevo"];
							} elseif (@$json["esn"] != "") {
								$esn =  @$json["esn"];
							} else {
								$esn =  $intervencion['Planificacion']['esn'];
							}
							
							if (is_numeric($esn)) {
								echo $esn;
							} else {
								// Buscamos ESN del padre
								echo $util->esnPadre($intervencion['Planificacion']['padre']);
							}
						}
						//echo $esn;
						?></td>
                      <td align="center">
					  <?php 
						if ($intervencion['Planificacion']['padre'] != NULL && $intervencion['Planificacion']['padre'] != '') {
							if($intervencion['Planificacion']['tipointervencion']=="BL"){
								echo "cOP";
							}else{
								echo "c".strtoupper($intervencion['Planificacion']['tipointervencion']);
							}
						} else {
							if($intervencion['Planificacion']['tipointervencion']=="BL"){
								echo "OP";
							}else{
								echo strtoupper($intervencion['Planificacion']['tipointervencion']);
							}
						}
					  ?>
					  </td>
                     <td nowrap>
					<?php 
					//var_dump($intervencion['Planificacion']);
					if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'MP') {
						if (isset($json["tipo_programado"])) {
							if ($json["tipo_programado"] == "1500") {
								echo "Overhaul";
							} else {
								echo $json["tipo_programado"];
							}	
						} else {					
							if ($intervencion['Planificacion']['tipomantencion'] == "1500") {
								echo "Overhaul";
							} else {
								echo $intervencion['Planificacion']['tipomantencion'];
							}
						}
					} elseif ((strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL' || strtoupper($intervencion['Planificacion']['tipointervencion']) == 'OP') && $intervencion['Planificacion']['backlog_id'] != null) {
						echo $util->getBacklogInfo($intervencion['Planificacion']['backlog_id']);
					} else {
						echo $util->getSintoma($intervencion['Planificacion']['sintoma_id']);
					}
					?>
					 </td>
                      <!--<td><?php
						
					  ?></td>-->
					  <td align="center"><?php echo $total; ?></td>
					  <td align="center"><?php 
							$hay_comentarios = false;
							echo "<div id=\"comentario_s_{$intervencion['Planificacion']['id']}\" class=\"box_comentario\"><h4>Comentario</h4>";
							if ($intervencion['Planificacion']['estado'] > 2) {
								$comentario = $util->getComentario($intervencion['Planificacion']['folio']);
								//if (@$json["Comentarios"] != "") {
								//	$comentario = @$json["Comentarios"];
								//}
								if (strlen($comentario) > 0) {
									echo "<p align=\"left\">".$util->getTecnicoLider($json)." (Técnico lider):</p>";
									echo "<p align=\"justify\">$comentario</p>";
									$hay_comentarios = true;
								}
							}
							
							if (strlen($intervencion['Planificacion']['observacion']) > 0) {
								echo "<p align=\"left\">".$util->getUsuarioInfo($intervencion["Planificacion"]["usuario_id"])." (Supervisor):</p>";
								echo "<p align=\"justify\">{$intervencion['Planificacion']['observacion']}</p>";
								$hay_comentarios = true;
							}
							if (!$hay_comentarios) {
								echo "<p align=\"center\">No hay comentarios ingresados.</p>";
							}
							echo "<p align=\"right\"><span class=\"cerrar_comentario\" style=\"cursor: pointer;\">Cerrar</span></p>
								</div>";
							
							
							if ($hay_comentarios) {
								echo "<img src=\"/images/icon-comentario.png\" style=\"width: 16px; cursor: pointer;\" class=\"mostrar_comentario\" comentario=\"comentario_s_{$intervencion['Planificacion']['id']}\" title=\"Ver comentarios\" />";
							} else {
								echo "<img src=\"/images/icon-comentario.png\" style=\"width: 16px; cursor: pointer; opacity: 0.25;\" class=\"mostrar_comentario\" comentario=\"comentario_s_{$intervencion['Planificacion']['id']}\" title=\"Ver comentarios\" />";
							}
						?></td>
                       <td nowrap>
						<?php
						echo $intervencion['Estado']['nombre'];
						?>
					   </td>
					   <?php if (isset($this->request->query) && isset($this->request->query["estado"]) && $this->request->query["estado"]=='4') { ?>
					   <td nowrap style="min-width: 50px;"><?php echo $util->getSupervisorDCC($intervencion['Planificacion']['aprobador_id']); ?></td>
					   <?php } ?>
					   <!--<td align="center">
					 <?php 
					 if ($intervencion['Planificacion']['sinc'] != NULL) {
					 ?>
							<img src="/images/icons/color/tick.png" alt="">
					<?php
						} else {
						?>
							<img src="/images/icons/color/cross.png" alt="">
					 <?php
					 }
					 ?>
					 </td>-->
					 <td align="center">
					 <?php
						$url = strtolower($intervencion['Planificacion']['tipointervencion']);
					  ?>
					 <form action="/Trabajo/index/<?php echo $intervencion['Planificacion']['id']; ?>" method="POST">
					 <?php if ($usuario["nivelusuario_id"] == '4') { ?>
					 <!--<img src="/images/icons/color/cross.png" alt="" title="Eliminar Folio <?php echo $intervencion['Planificacion']['id'];?>" style="width: 20px;height:20px;cursor:pointer;" h="<?php echo $util->folioHijos($intervencion['Planificacion']['hijo']);?>" e="<?php echo $intervencion['Planificacion']['estado'];?>" p="<?php echo @$intervencion['Planificacion']['padre'];?>" f="<?php echo $intervencion['Planificacion']['id'];?>" class="elint" />-->
					 <?php } ?>
					  
					  
							<input type="submit" name="detalle" value="Detalle" class="blueB"  />
					
					<!-- <img src="/images/icons/control/32/print.png" alt="" title="Imprimir Folio <?php echo $intervencion['Planificacion']['id'];?>" style="width: 20px;height:20px;cursor:pointer;" class="imprimir" f="<?php echo $intervencion['Planificacion']['id'];?>" />-->
					 </form>
					 </td>
					 
					 
                  </tr>
                  <?php } ?>
              </tbody>
          </table>
		  <?php
		}
		?>
        </div>
		 <?php if (count($intervenciones) == 0 || $i == 1) { ?>
			<script type="text/javascript">
			$(document).ready(function(){
				$(".dsp_resultados").hide();
			});
			</script>
			<div class="mensaje_sin_registros">No hay registros para mostrar.</div>
		<?php
		}
		?>
   	</div>   
	
	    
    <?php
if (count($intervenciones)>0) { 
	echo "<div class='paging'>";
	echo $paginator->first("Primero");
	if($paginator->hasPrev()){
		echo $paginator->prev("Anterior");
	}
	echo $paginator->numbers(array('modulus' => 5));
	if($paginator->hasNext()){
		echo $paginator->next("Siguiente");
	}
	echo $paginator->last("Último");
	echo "</div>";
}
?>
