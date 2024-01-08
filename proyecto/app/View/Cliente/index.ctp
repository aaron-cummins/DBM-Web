<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$paginator = $this->Paginator;
?>    <!-- Title area -->
   <div class="titleArea" style="padding-top: 0;">
		<div class="wrapper">
            <div class="pageTitle">
                <h5>Intervenciones</h5>
                <span>Revisión Cliente</span>
            </div>
			<div class="clear"></div>
        </div>
    </div>
    <!-- Page statistics area -->
    <div class="line"></div>
	<?php echo $this->Session->flash();?>
    <div class="wrapper">
       <form method="GET">
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
						<select name="tipo_terminado" id="tipo_terminado">
							<option value="" <?php echo ($tipo_terminado == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<option value="T" <?php echo ($tipo_terminado == "T") ? 'selected="selected"' : ''; ?>>Terminados</option>
							<option value="P" <?php echo ($tipo_terminado == "P") ? 'selected="selected"' : ''; ?>>Pendientes</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Correlativo</td>
					<td><input name="correlativo" value="<?php echo $correlativo; ?>" type="text" /></td>
					<td>Estado</td>
					<td>
						<select name="estado" id="estado">
							<option value="4" selected="selected">Todos</option>
							<option value="4" selected="selected">Sin Revisar</option>
						</select>
					</td>
					<td width="10%">Tipo</td>
					<td>
						<select name="tipo_intervencion" id="tipo_intervencion" tipo_intervencion="<?php echo $tipo_intervencion;?>">
							<option value="" <?php echo ($tipo_intervencion == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<option value="MP" <?php echo ($tipo_intervencion == "MP") ? 'selected="selected"' : ''; ?>>MP</option>
							<option value="RP" <?php echo ($tipo_intervencion == "RP") ? 'selected="selected"' : ''; ?>>RP</option>
							<option value="BL" <?php echo ($tipo_intervencion == "BL") ? 'selected="selected"' : ''; ?>>BL</option>
							<option value="EX" <?php echo ($tipo_intervencion == "EX") ? 'selected="selected"' : ''; ?>>EX</option>
							<option value="RI" <?php echo ($tipo_intervencion == "RI") ? 'selected="selected"' : ''; ?>>RI</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Folio</td>
					<td><input name="codigo" value="<?php echo $codigo; ?>" type="text" /></td>
					<td>Fecha Inicio</td>
					<td><input type="date" name="fecha_inicio" value="<?php echo $fecha_inicio;?>" style="width: 115px;" /> a <input type="date" name="fecha_inicio_termino" value="<?php echo $fecha_inicio_termino;?>" style="width: 115px;" /></td>
					<td>Fecha Aprobación DCC</td>
					<td><input type="date" name="fecha_termino" value="<?php echo $fecha_termino;?>" style="width: 115px;" /> a <input type="date" name="fecha_termino_termino" value="<?php echo $fecha_termino_termino;?>" style="width: 115px;" /></td>
				</tr>
				<tr>
					<td>Supervisor DCC</td>
					<td>
						<select name="sid" id="sid">
							<option value="" <?php echo ($sid == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<?php
								foreach ($supervisores as $v) { 
									echo "<option value=\"{$v["Usuario"]["id"]}\" ".(($sid == $v["Usuario"]["id"]) ? 'selected="selected"' : '').">{$v["Usuario"]["nombres"]} {$v["Usuario"]["apellidos"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
					<td></td>
					<td></td>
					
					<td align="right" colspan="2">
						<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/Cliente'; return false;" />
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
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Intervenciones finalizadas</h6></div>
          <?php
			$i = 1;
			if (count($intervenciones) > 0) {
			/*
			Flota, equipo, Tipo intervencion, Primera fecha (Segun flujo), Tiempo DCC,  Tiempo KCH, Tiempo MINA, Sintoma , Sistema , Nombre Supervisor DCC aprobación
			*/
		?>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable dsp_resultados">
              <thead>
                <tr>
                  <td width="20">#</td>
				  <?php if (intval($this->Session->read('faena_id')) == 0) { ?>
						 <td width="20"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "faena_id:asc") {
						echo "&order=faena_id:desc";
					  } else {
						echo "&order=faena_id:asc";
					  }
					  if(@$this->request->query["order"] == "faena_id:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "faena_id:desc"){
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
					 <td  style="min-width: 50px;"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "correlativo:asc") {
						echo "&order=correlativo:desc";
					  } else {
						echo "&order=correlativo:asc";
					  }
					  if(@$this->request->query["order"] == "correlativo:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "correlativo:desc"){
						$o="d";
					  }
					  
					  ?>" style="display: block;">Correlativo<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
				 <td style="min-width: 50px;"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "id:asc") {
						echo "&order=id:desc";
					  } else {
						echo "&order=id:asc";
					  }
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
				  <td style="min-width: 50px;"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "flota_id:asc") {
						echo "&order=flota_id:desc";
					  } else {
						echo "&order=flota_id:asc";
					  }
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
				  <td  style="min-width: 50px;"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "unidad_id:asc") {
						echo "&order=unidad_id:desc";
					  } else {
						echo "&order=unidad_id:asc";
					  }
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
				   <td style="min-width: 50px;"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "tipointervencion:asc") {
						echo "&order=tipointervencion:desc";
					  } else {
						echo "&order=tipointervencion:asc";
					  }
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
				  <td style="min-width: 50px;"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "fecha:asc") {
						echo "&order=fecha:desc";
					  } else {
						echo "&order=fecha:asc";
					  }
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
				  <td width="100"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "dcc:asc") {
						echo "&order=dcc:desc";
					  } else {
						echo "&order=dcc:asc";
					  }
					  if(@$this->request->query["order"] == "dcc:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "dcc:desc"){
						$o="d";
					  }
					  
					  ?>" style="display: block;">DCC<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
				  <td width="100"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "oem:asc") {
						echo "&order=oem:desc";
					  } else {
						echo "&order=oem:asc";
					  }
					  if(@$this->request->query["order"] == "oem:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "oem:desc"){
						$o="d";
					  }
					  
					  ?>" style="display: block;">KCH<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
				   <td width="100"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "mina:asc") {
						echo "&order=mina:desc";
					  } else {
						echo "&order=mina:asc";
					  }
					  if(@$this->request->query["order"] == "mina:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "mina:desc"){
						$o="d";
					  }
					  
					  ?>" style="display: block;">MINA<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
				  <td style="min-width: 50px;"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "duracion:asc") {
						echo "&order=duracion:desc";
					  } else {
						echo "&order=duracion:asc";
					  }
					  if(@$this->request->query["order"] == "duracion:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "duracion:desc"){
						$o="d";
					  }
					  
					  ?>" style="display: block;">Total<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
					  <td style="min-width: 50px;"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "aprobacion:asc") {
						echo "&order=aprobacion:desc";
					  } else {
						echo "&order=aprobacion:asc";
					  }
					  if(@$this->request->query["order"] == "aprobacion:asc"){
						$o="a";
					  }elseif(@$this->request->query["order"] == "aprobacion:desc"){
						$o="d";
					  }
					  
					  ?>" style="display: block;">Fecha Aprobación DCC<?php 
					  if($o=="a"){
						echo '<img src="/images/icons/dark/arrowUp.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}elseif($o=="d"){
						echo '<img src="/images/icons/dark/arrowDown.png" style="padding-top: 3px;float: right;opacity: .7;" />';
						}
					  ?></a></td>
				  <td>Descripción</td>
				  <td style="min-width: 50px;"><a href="/Cliente?<?php 
						$o="";
						echo $url;  if (isset($this->request->query) && isset($this->request->query["order"]) && $this->request->query["order"] == "supervisor:asc") {
						echo "&order=supervisor:desc";
					  } else {
						echo "&order=supervisor:asc";
					  }
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
					  ?></a>
					  </td>
				  <td width="200">Acciones</td>
                </tr>
              </thead>
              <tbody>
			  <?php 
				$i = 25*($paginator->current()-1)+1;
				foreach ($intervenciones as $intervencion) {
					$date = new DateTime($intervencion['Planificacion']['fecha']);
					$hora = substr($intervencion['Planificacion']['hora'], 0, 5);
					
					$fecha_inicio=strtotime($intervencion['Planificacion']['fecha'] . ' ' .$intervencion['Planificacion']['hora']);
					$fecha_termino=strtotime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
					//$diff = date_diff($fecha_termino, $fecha_inicio);
					//$total = round(($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60, 1);
					//$diff=($fecha_termino-$fecha_inicio)/60;
					//$total=sprintf("%02d", floor($diff/ 60)) . ":" . sprintf("%02d", $diff% 60);
					
					
					
					$json = json_decode($intervencion["Planificacion"]["json"], true);
					$fi=strtotime($intervencion['Planificacion']['fecha'] . ' ' .$intervencion['Planificacion']['hora']);
					$ft=strtotime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
					$df=($ft-$fi)/60;
					//$total=sprintf("%02d",floor($df/60)).":".sprintf("%02d",$df%60);
					$dcc=sprintf("%02d",floor($intervencion['Planificacion']['tiempo_dcc']/60)).":".sprintf("%02d",$intervencion['Planificacion']['tiempo_dcc']%60);
					$oem=sprintf("%02d",floor($intervencion['Planificacion']['tiempo_oem']/60)).":".sprintf("%02d",$intervencion['Planificacion']['tiempo_oem']%60);
					$mina=sprintf("%02d",floor($intervencion['Planificacion']['tiempo_mina']/60)).":".sprintf("%02d",$intervencion['Planificacion']['tiempo_mina']%60);
					$total=sprintf("%02d",floor(($intervencion['Planificacion']['tiempo_oem']+$intervencion['Planificacion']['tiempo_dcc']+$intervencion['Planificacion']['tiempo_mina'])/60)).":".sprintf("%02d",($intervencion['Planificacion']['tiempo_oem']+$intervencion['Planificacion']['tiempo_dcc']+$intervencion['Planificacion']['tiempo_mina'])%60);

					//$total=$df;
					//$alert="";
					/*if($df!=$intervencion['Planificacion']['tiempo_dcc']+$intervencion['Planificacion']['tiempo_oem']+$intervencion['Planificacion']['tiempo_mina']){
						$alert="style=\"background-color:yellow;\"";
					}*/
					
			  ?>
                  <tr>
                  <td nowrap><?php echo $i++;?></td>
				   <?php if (intval($this->Session->read('faena_id')) == 0) { ?>
						<td nowrap style="min-width: 50px;"><?php echo $intervencion['Faena']['nombre'];?></td>
					  <?php } ?>
				   <td style="min-width: 50px;"><?php echo $intervencion['Planificacion']['correlativo']; ?></td>
				   <td style="min-width: 50px;"><?php echo $intervencion['Planificacion']['id']; ?></td>
                      <td nowrap style="width: 50px;"><?php echo $util->getFlota($intervencion['Planificacion']['flota_id']); ?></td>
                      <td nowrap style="min-width: 50px;"><?php echo $util->getUnidad($intervencion['Planificacion']['unidad_id']); ?></td>
					   <td nowrap align="center"><?php 
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
					  ?></td>
                      <td align="center" style="min-width: 50px;">
					  <?php 
						if ($intervencion['Planificacion']['padre'] != NULL && $intervencion['Planificacion']['padre'] != '') {
							echo "c".strtoupper($intervencion['Planificacion']['tipointervencion']);
						} else {
							echo strtoupper($intervencion['Planificacion']['tipointervencion']);
						}

					  ?>
					  </td>
                     <td nowrap align="center" style="min-width: 50px;"><?php echo date('d-m-Y h:i A', $fi);?></td>
                      <td align="center" style="min-width: 50px;"><?php echo $dcc;?></td>
					  <td align="center"><?php echo $oem;?></td>
					  <td align="center"><?php echo $mina;?></td>
					  <td align="center" style="min-width: 50px;"><?php echo $total; ?></td>
					  <td align="center" style="min-width: 50px;"><?php echo date("d-m-Y h:i A", strtotime($intervencion['Planificacion']['fecha_aprobacion'])); ?></td>
                    <td nowrap>
					<?php 
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
					} elseif (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL') {
						if (@$intervencion['Planificacion']['backlog_id'] != null) {
							echo @$intervencion['Backlog']['criticidad'] ." / ". @$intervencion['Backlog']['Sistema_Motor']['Sistema']['nombre']; 
						}
					} else {
						echo $util->getSintoma($intervencion['Planificacion']['sintoma_id']);
					}				  
					?>
					</td>
					  <!--<td align="center"></td>-->
					  <td nowrap style="min-width: 50px;"><?php echo $util->getSupervisorDCC($intervencion['Planificacion']['aprobador_id']); ?></td>
					  <td align="center">
						<form action="/Cliente/procesar" method="POST">
							<input type="hidden" name="comentario" id="comentario_<?php echo $intervencion['Planificacion']['id']; ?>" value="" />
							<input type="hidden" name="id" value="<?php echo $intervencion['Planificacion']['id']; ?>" />
							<input type="submit" name="cliente_aprobar" planificacion_id="<?php echo $intervencion['Planificacion']['id']; ?>" value="Aprobar" class="greenB cliente_aprobar" />
							<input type="submit" name="cliente_rechazar" planificacion_id="<?php echo $intervencion['Planificacion']['id']; ?>" value="Rechazar" class="redB cliente_rechazar" />
						</form>
					</td>
                  </tr>
                  <?php } ?>
              </tbody>
          </table>
        </div>
		<?php
		} 
		if (count($intervenciones) == 0 || $i == 1) {
		?>
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