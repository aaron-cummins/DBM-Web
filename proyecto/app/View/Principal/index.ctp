<?php
	ini_set('memory_limit','256M');
?>
<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5>Resumen Intervenciones <?php echo $this->Session->read('faena');?></h5>
		</div>
	  <div class="clear"></div>
	</div>
</div>
<!-- Page statistics area -->
<div class="line"></div>
<?php echo $this->Session->flash();?> 

<!-- Main content wrapper -->
<div class="wrapper">
<div class="widgets">
<div class="oneTwo">
<div class="widget">
  <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Intervenciones Terminadas</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
                      <td nowrap width="150">Tipo Intervención</td>
                      <td># Trabajos</td>
                      <td>%</td>
                      <td>Tiempo Promedio Ejecución</td>
                  </tr>
              </thead>
			 <?php 
				$datos = array();
				$tiempos = array();
				foreach ($intervenciones_terminadas as $intervencion) { 
					//$json = json_decode($intervencion["Planificacion"]["json"], true);
					//$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					$tiempo_trabajo = split(":", $intervencion['Planificacion']['tiempo_trabajo']);
					$tiempo = abs($tiempo_trabajo[0])*60 + $tiempo_trabajo[1];

					
					$tipo_intervencion = strtoupper($intervencion["Planificacion"]["tipointervencion"]);
					$tipo_mantencion = strtoupper($intervencion["Planificacion"]["tipomantencion"]);
					
					
					
					if ($tipo_intervencion != "MP") {
						if (@$datos[$tipo_intervencion] == "") {
							$datos[$tipo_intervencion] = 1;
							$tiempos[$tipo_intervencion] = $tiempo;
						} else {
							$datos[$tipo_intervencion]++;
							$tiempos[$tipo_intervencion] += $tiempo;
						}
					} else {
						if (@$datos[$tipo_intervencion.$tipo_mantencion] == "") {
							$datos[$tipo_intervencion.$tipo_mantencion] = 1;
							$tiempos[$tipo_intervencion.$tipo_mantencion] = $tiempo;
						} else {
							$datos[$tipo_intervencion.$tipo_mantencion]++;
							$tiempos[$tipo_intervencion.$tipo_mantencion] += $tiempo;
						}
					}
				}
			  ?>
              <tbody>
			   <tr>
                      <td nowrap width="150">MP 250</td>
                      <td><?php echo @$datos["MP250"] == "" ? "0" : @$datos["MP250"];?></td>
                      <td><?php echo @$datos["MP250"] == "" ? "0" : number_format(@$datos["MP250"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["MP250"]=="") @$datos["MP250"] = "1";
						$prom = floor(@$tiempos["MP250"]/@$datos["MP250"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
				   <tr>
                      <td nowrap width="150">MP 500</td>
                      <td><?php echo @$datos["MP500"] == "" ? "0" : @$datos["MP500"];?></td>
                      <td><?php echo @$datos["MP500"] == "" ? "0" : number_format(@$datos["MP500"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["MP500"]=="") @$datos["MP500"] = "1";
						$prom = floor(@$tiempos["MP500"]/@$datos["MP500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP 1000</td>
                      <td><?php echo @$datos["MP1000"] == "" ? "0" : @$datos["MP1000"];?></td>
                      <td><?php echo @$datos["MP1000"] == "" ? "0" : number_format(@$datos["MP1000"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["MP1000"]=="") @$datos["MP1000"] = "1";
						$prom = floor(@$tiempos["MP1000"]/@$datos["MP1000"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP Overhaul</td>
                      <td><?php echo @$datos["MP1500"] == "" ? "0" : @$datos["MP1500"];?></td>
                      <td><?php echo @$datos["MP1500"] == "" ? "0" : number_format(@$datos["MP1500"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["MP1500"]=="") @$datos["MP1500"] = "1";
						$prom = floor(@$tiempos["MP1500"]/@$datos["MP1500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RP</td>
                      <td><?php echo @$datos["RP"] == "" ? "0" : @$datos["RP"];?></td>
                      <td><?php echo @$datos["RP"] == "" ? "0" : number_format(@$datos["RP"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["RP"]=="") @$datos["RP"] = "1";
						$prom = floor(@$tiempos["RP"]/@$datos["RP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">OP</td>
                      <td><?php echo @$datos["OP"] == "" ? "0" : @$datos["OP"];?></td>
                      <td><?php echo @$datos["OP"] == "" ? "0" : number_format(@$datos["OP"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["OP"]=="") @$datos["OP"] = "1";
						$prom = floor(@$tiempos["OP"]/@$datos["OP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RI</td>
                      <td><?php echo @$datos["RI"] == "" ? "0" : @$datos["RI"];?></td>
                      <td><?php echo @$datos["RI"] == "" ? "0" : number_format(@$datos["RI"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
						if (@$datos["RI"]=="") @$datos["RI"] = "1";
						$prom = floor(@$tiempos["RI"]/@$datos["RI"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">EX</td>
                      <td><?php echo @$datos["EX"] == "" ? "0" : @$datos["EX"];?></td>
                      <td><?php echo @$datos["EX"] == "" ? "0" : number_format(@$datos["EX"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["EX"]=="") @$datos["EX"] = "1";
						$prom = floor(@$tiempos["EX"]/@$datos["EX"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
				  <tr>
                      <td nowrap width="150" style="font-weight: bold;">Total Intervenciones</td>
                      <td style="font-weight: bold;"><?php echo count($intervenciones_terminadas);?></td>
                      <td style="font-weight: bold;">100%</td>
                      <td></td>
                  </tr>
			  </tbody>
			  
			  </table>
			  
	</div>
	<!--
	<div class="widget">
  <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Intervenciones con Continuación</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
                      <td nowrap width="150">Tipo Intervención</td>
                      <td># Continuaciones</td>
                      <td>Tiempo Promedio Ejecución</td>
					  <td>Tiempo Promedio Espera</td>
                  </tr>
              </thead>
			 <?php 
				$datos = array();
				$tiempos = array();
				$espera = array();
				foreach ($intervenciones_continuacion as $intervencion) { 
					//$json = json_decode($intervencion["Planificacion"]["json"], true);
					//$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					$tiempo_trabajo = split(":", $intervencion['Planificacion']['tiempo_trabajo']);
					$tiempo = abs($tiempo_trabajo[0])*60 + $tiempo_trabajo[1];
					$tipo_intervencion = strtoupper($intervencion["Planificacion"]["tipointervencion"]);
					$tipo_mantencion = strtoupper($intervencion["Planificacion"]["tipomantencion"]);
					$tiempo_espera = 0;
					
					if ($intervencion["Planificacion"]["json"] != null && $intervencion["Planificacion"]["json"] != "") {
						$json = json_decode($intervencion["Planificacion"]["json"], true);
						if (isset($json["tiempo_espera"]) && $json["tiempo_espera"] != null && $json["tiempo_espera"] != '') {
							$tiempo_espera = abs($json["tiempo_espera"]);
						}
					}
					
					if ($tipo_intervencion != "MP") {
						if (@$datos[$tipo_intervencion] == "") {
							$datos[$tipo_intervencion] = 1;
							$tiempos[$tipo_intervencion] = $tiempo;
							$espera[$tipo_intervencion] = $tiempo_espera;
						} else {
							$datos[$tipo_intervencion]++;
							$tiempos[$tipo_intervencion] += $tiempo;
							$espera[$tipo_intervencion] += $tiempo_espera;
						}
					} else {
						if (@$datos[$tipo_intervencion.$tipo_mantencion] == "") {
							$datos[$tipo_intervencion.$tipo_mantencion] = 1;
							$tiempos[$tipo_intervencion.$tipo_mantencion] = $tiempo;
							$espera[$tipo_intervencion.$tipo_mantencion] = $tiempo_espera;
						} else {
							$datos[$tipo_intervencion.$tipo_mantencion]++;
							$tiempos[$tipo_intervencion.$tipo_mantencion] += $tiempo;
							$espera[$tipo_intervencion.$tipo_mantencion] += $tiempo_espera;
						}
					}
				}
			  ?>
              <tbody>
			   <tr>
                      <td nowrap width="150">MP 250</td>
                      <td><?php echo @$datos["MP250"] == "" ? "0" : @$datos["MP250"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP250"]=="") @$datos["MP250"] = "1";
						$prom = floor(@$tiempos["MP250"]/@$datos["MP250"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
					  if (@$datos["MP250"]=="") @$datos["MP250"] = "1";
						$prom = floor(@$espera["MP250"]/@$datos["MP250"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
				   <tr>
                      <td nowrap width="150">MP 500</td>
                      <td><?php echo @$datos["MP500"] == "" ? "0" : @$datos["MP500"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP500"]=="") @$datos["MP500"] = "1";
						$prom = floor(@$tiempos["MP500"]/@$datos["MP500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					  <td><?php 
					  try {
					  if (@$datos["MP500"]=="") @$datos["MP500"] = "1";
						$prom = floor(@$espera["MP500"]/@$datos["MP500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP 1000</td>
                      <td><?php echo @$datos["MP1000"] == "" ? "0" : @$datos["MP1000"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP1000"]=="") @$datos["MP1000"] = "1";
						$prom = floor(@$tiempos["MP1000"]/@$datos["MP1000"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
					  if (@$datos["MP1000"]=="") @$datos["MP1000"] = "1";
						$prom = floor(@$espera["MP1000"]/@$datos["MP1000"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP Overhaul</td>
                      <td><?php echo @$datos["MP1500"] == "" ? "0" : @$datos["MP1500"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP1500"]=="") @$datos["MP1500"] = "1";
						$prom = floor(@$tiempos["MP1500"]/@$datos["MP1500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
					  if (@$datos["MP1500"]=="") @$datos["MP1500"] = "1";
						$prom = floor(@$espera["MP1500"]/@$datos["MP1500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RP</td>
                      <td><?php echo @$datos["RP"] == "" ? "0" : @$datos["RP"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["RP"]=="") @$datos["RP"] = "1";
						$prom = floor(@$tiempos["RP"]/@$datos["RP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
					  if (@$datos["RP"]=="") @$datos["RP"] = "1";
						$prom = floor(@$espera["RP"]/@$datos["RP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">OP</td>
                      <td><?php echo @$datos["OP"] == "" ? "0" : @$datos["OP"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["OP"]=="") @$datos["OP"] = "1";
						$prom = floor(@$tiempos["OP"]/@$datos["OP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					  <td><?php 
					  try {
					  if (@$datos["OP"]=="") @$datos["OP"] = "1";
						$prom = floor(@$espera["OP"]/@$datos["OP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RI</td>
                      <td><?php echo @$datos["RI"] == "" ? "0" : @$datos["RI"];?></td>
                      <td><?php 
					  try {
						if (@$datos["RI"]=="") @$datos["RI"] = "1";
						$prom = floor(@$tiempos["RI"]/@$datos["RI"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
						if (@$datos["RI"]=="") @$datos["RI"] = "1";
						$prom = floor(@$espera["RI"]/@$datos["RI"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">EX</td>
                      <td><?php echo @$datos["EX"] == "" ? "0" : @$datos["EX"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["EX"]=="") @$datos["EX"] = "1";
						$prom = floor(@$tiempos["EX"]/@$datos["EX"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
					  if (@$datos["EX"]=="") @$datos["EX"] = "1";
						$prom = floor(@$espera["EX"]/@$datos["EX"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
				  <tr>
                      <td nowrap width="150" style="font-weight: bold;">Total Intervenciones</td>
                      <td style="font-weight: bold;"><?php echo count($intervenciones_continuacion);?></td>
                      <td></td>
					  <td></td>
                  </tr>
			  </tbody>
			  </table>
			  
	</div>
	-->
</div>

<div class="oneTwo">
	<div class="widget">
	<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Intervenciones en Ejecución</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
                      <td nowrap width="150">Tipo Intervención</td>
                      <td># Trabajos</td>
                      <td>Tiempo Estimado Promedio</td>
                  </tr>
              </thead>
			 <?php 
				$datos = array();
				$tiempos = array();
				foreach ($intervenciones_ejecucion as $intervencion) { 
					//$json = json_decode($intervencion["Planificacion"]["json"], true);
					//$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					$tiempo_trabajo = split(":", $intervencion['Planificacion']['tiempo_trabajo']);
					if (@$tiempo_trabajo[0] != "" && @$tiempo_trabajo[1] != "") {
						$tiempo = abs($tiempo_trabajo[0])*60 + $tiempo_trabajo[1];
					}

					
					$tipo_intervencion = strtoupper($intervencion["Planificacion"]["tipointervencion"]);
					$tipo_mantencion = strtoupper($intervencion["Planificacion"]["tipomantencion"]);
					
					if ($tipo_intervencion != "MP") {
						if (@$datos[$tipo_intervencion] == "") {
							$datos[$tipo_intervencion] = 1;
							$tiempos[$tipo_intervencion] = $tiempo;
						} else {
							$datos[$tipo_intervencion]++;
							$tiempos[$tipo_intervencion] += $tiempo;
						}
					} else {
						if (@$datos[$tipo_intervencion.$tipo_mantencion] == "") {
							$datos[$tipo_intervencion.$tipo_mantencion] = 1;
							$tiempos[$tipo_intervencion.$tipo_mantencion] = $tiempo;
						} else {
							$datos[$tipo_intervencion.$tipo_mantencion]++;
							$tiempos[$tipo_intervencion.$tipo_mantencion] += $tiempo;
						}
					}
				}
			  ?>
              <tbody>
			   <tr>
                      <td nowrap width="150">MP 250</td>
                      <td><?php echo @$datos["MP250"] == "" ? "0" : @$datos["MP250"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP250"]=="") @$datos["MP250"] = "1";
						$prom = floor(@$tiempos["MP250"]/@$datos["MP250"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
				   <tr>
                      <td nowrap width="150">MP 500</td>
                      <td><?php echo @$datos["MP500"] == "" ? "0" : @$datos["MP500"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP500"]=="") @$datos["MP500"] = "1";
						$prom = floor(@$tiempos["MP500"]/@$datos["MP500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP 1000</td>
                      <td><?php echo @$datos["MP1000"] == "" ? "0" : @$datos["MP1000"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP1000"]=="") @$datos["MP1000"] = "1";
						$prom = floor(@$tiempos["MP1000"]/@$datos["MP1000"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP Overhaul</td>
                      <td><?php echo @$datos["MP1500"] == "" ? "0" : @$datos["MP1500"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP1500"]=="") @$datos["MP1500"] = "1";
						$prom = floor(@$tiempos["MP1500"]/@$datos["MP1500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RP</td>
                      <td><?php echo @$datos["RP"] == "" ? "0" : @$datos["RP"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["RP"]=="") @$datos["RP"] = "1";
						$prom = floor(@$tiempos["RP"]/@$datos["RP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">OP</td>
                      <td><?php echo @$datos["OP"] == "" ? "0" : @$datos["OP"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["OP"]=="") @$datos["OP"] = "1";
						$prom = floor(@$tiempos["OP"]/@$datos["OP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RI</td>
                      <td><?php echo @$datos["RI"] == "" ? "0" : @$datos["RI"];?></td>
                      <td><?php 
					  try {
						if (@$datos["RI"]=="") @$datos["RI"] = "1";
						$prom = floor(@$tiempos["RI"]/@$datos["RI"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">EX</td>
                      <td><?php echo @$datos["EX"] == "" ? "0" : @$datos["EX"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["EX"]=="") @$datos["EX"] = "1";
						$prom = floor(@$tiempos["EX"]/@$datos["EX"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
				  <tr>
                      <td nowrap width="150" style="font-weight: bold;">Total Intervenciones</td>
                      <td style="font-weight: bold;"><?php echo count($intervenciones_ejecucion);?></td>
                      <td></td>
                  </tr>
			  </tbody>
			  </table>
	</div>
</div>
</div>
<!--
<div class="widgets">
<div class="oneTwo">
<div class="widget">
  <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Intervenciones Terminadas</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
                      <td nowrap width="150">Tipo Intervención</td>
                      <td># Trabajos</td>
                      <td>%</td>
                      <td>Tiempo Promedio Ejecución</td>
                  </tr>
              </thead>
			 <?php 
				$datos = array();
				$tiempos = array();
				foreach ($intervenciones_terminadas as $intervencion) { 
					//$json = json_decode($intervencion["Planificacion"]["json"], true);
					//$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					$tiempo_trabajo = split(":", $intervencion['Planificacion']['tiempo_trabajo']);
					$tiempo = abs($tiempo_trabajo[0])*60 + $tiempo_trabajo[1];

					
					$tipo_intervencion = strtoupper($intervencion["Planificacion"]["tipointervencion"]);
					$tipo_mantencion = strtoupper($intervencion["Planificacion"]["tipomantencion"]);
					
					
					
					if ($tipo_intervencion != "MP") {
						if (@$datos[$tipo_intervencion] == "") {
							$datos[$tipo_intervencion] = 1;
							$tiempos[$tipo_intervencion] = $tiempo;
						} else {
							$datos[$tipo_intervencion]++;
							$tiempos[$tipo_intervencion] += $tiempo;
						}
					} else {
						if (@$datos[$tipo_intervencion.$tipo_mantencion] == "") {
							$datos[$tipo_intervencion.$tipo_mantencion] = 1;
							$tiempos[$tipo_intervencion.$tipo_mantencion] = $tiempo;
						} else {
							$datos[$tipo_intervencion.$tipo_mantencion]++;
							$tiempos[$tipo_intervencion.$tipo_mantencion] += $tiempo;
						}
					}
				}
			  ?>
              <tbody>
			   <tr>
                      <td nowrap width="150">MP 250</td>
                      <td><?php echo @$datos["MP250"] == "" ? "0" : @$datos["MP250"];?></td>
                      <td><?php echo @$datos["MP250"] == "" ? "0" : number_format(@$datos["MP250"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["MP250"]=="") @$datos["MP250"] = "1";
						$prom = floor(@$tiempos["MP250"]/@$datos["MP250"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
				   <tr>
                      <td nowrap width="150">MP 500</td>
                      <td><?php echo @$datos["MP500"] == "" ? "0" : @$datos["MP500"];?></td>
                      <td><?php echo @$datos["MP500"] == "" ? "0" : number_format(@$datos["MP500"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["MP500"]=="") @$datos["MP500"] = "1";
						$prom = floor(@$tiempos["MP500"]/@$datos["MP500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP 1000</td>
                      <td><?php echo @$datos["MP1000"] == "" ? "0" : @$datos["MP1000"];?></td>
                      <td><?php echo @$datos["MP1000"] == "" ? "0" : number_format(@$datos["MP1000"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["MP1000"]=="") @$datos["MP1000"] = "1";
						$prom = floor(@$tiempos["MP1000"]/@$datos["MP1000"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP Overhaul</td>
                      <td><?php echo @$datos["MP1500"] == "" ? "0" : @$datos["MP1500"];?></td>
                      <td><?php echo @$datos["MP1500"] == "" ? "0" : number_format(@$datos["MP1500"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["MP1500"]=="") @$datos["MP1500"] = "1";
						$prom = floor(@$tiempos["MP1500"]/@$datos["MP1500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RP</td>
                      <td><?php echo @$datos["RP"] == "" ? "0" : @$datos["RP"];?></td>
                      <td><?php echo @$datos["RP"] == "" ? "0" : number_format(@$datos["RP"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["RP"]=="") @$datos["RP"] = "1";
						$prom = floor(@$tiempos["RP"]/@$datos["RP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">OP</td>
                      <td><?php echo @$datos["OP"] == "" ? "0" : @$datos["OP"];?></td>
                      <td><?php echo @$datos["OP"] == "" ? "0" : number_format(@$datos["OP"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["OP"]=="") @$datos["OP"] = "1";
						$prom = floor(@$tiempos["OP"]/@$datos["OP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RI</td>
                      <td><?php echo @$datos["RI"] == "" ? "0" : @$datos["RI"];?></td>
                      <td><?php echo @$datos["RI"] == "" ? "0" : number_format(@$datos["RI"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
						if (@$datos["RI"]=="") @$datos["RI"] = "1";
						$prom = floor(@$tiempos["RI"]/@$datos["RI"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">EX</td>
                      <td><?php echo @$datos["EX"] == "" ? "0" : @$datos["EX"];?></td>
                      <td><?php echo @$datos["EX"] == "" ? "0" : number_format(@$datos["EX"]/count($intervenciones_terminadas)*100,1);?>%</td>
                      <td><?php 
					  try {
					  if (@$datos["EX"]=="") @$datos["EX"] = "1";
						$prom = floor(@$tiempos["EX"]/@$datos["EX"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
				  <tr>
                      <td nowrap width="150" style="font-weight: bold;">Total Intervenciones</td>
                      <td style="font-weight: bold;"><?php echo count($intervenciones_terminadas);?></td>
                      <td style="font-weight: bold;">100%</td>
                      <td></td>
                  </tr>
			  </tbody>
			  
			  </table>
			  
	</div>
	<!--
	<div class="widget">
  <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Intervenciones con Continuación</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
                      <td nowrap width="150">Tipo Intervención</td>
                      <td># Continuaciones</td>
                      <td>Tiempo Promedio Ejecución</td>
					  <td>Tiempo Promedio Espera</td>
                  </tr>
              </thead>
			 <?php 
				$datos = array();
				$tiempos = array();
				$espera = array();
				foreach ($intervenciones_continuacion as $intervencion) { 
					//$json = json_decode($intervencion["Planificacion"]["json"], true);
					//$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					$tiempo_trabajo = split(":", $intervencion['Planificacion']['tiempo_trabajo']);
					$tiempo = abs($tiempo_trabajo[0])*60 + $tiempo_trabajo[1];
					$tipo_intervencion = strtoupper($intervencion["Planificacion"]["tipointervencion"]);
					$tipo_mantencion = strtoupper($intervencion["Planificacion"]["tipomantencion"]);
					$tiempo_espera = 0;
					
					if ($intervencion["Planificacion"]["json"] != null && $intervencion["Planificacion"]["json"] != "") {
						$json = json_decode($intervencion["Planificacion"]["json"], true);
						if (isset($json["tiempo_espera"]) && $json["tiempo_espera"] != null && $json["tiempo_espera"] != '') {
							$tiempo_espera = abs($json["tiempo_espera"]);
						}
					}
					
					if ($tipo_intervencion != "MP") {
						if (@$datos[$tipo_intervencion] == "") {
							$datos[$tipo_intervencion] = 1;
							$tiempos[$tipo_intervencion] = $tiempo;
							$espera[$tipo_intervencion] = $tiempo_espera;
						} else {
							$datos[$tipo_intervencion]++;
							$tiempos[$tipo_intervencion] += $tiempo;
							$espera[$tipo_intervencion] += $tiempo_espera;
						}
					} else {
						if (@$datos[$tipo_intervencion.$tipo_mantencion] == "") {
							$datos[$tipo_intervencion.$tipo_mantencion] = 1;
							$tiempos[$tipo_intervencion.$tipo_mantencion] = $tiempo;
							$espera[$tipo_intervencion.$tipo_mantencion] = $tiempo_espera;
						} else {
							$datos[$tipo_intervencion.$tipo_mantencion]++;
							$tiempos[$tipo_intervencion.$tipo_mantencion] += $tiempo;
							$espera[$tipo_intervencion.$tipo_mantencion] += $tiempo_espera;
						}
					}
				}
			  ?>
              <tbody>
			   <tr>
                      <td nowrap width="150">MP 250</td>
                      <td><?php echo @$datos["MP250"] == "" ? "0" : @$datos["MP250"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP250"]=="") @$datos["MP250"] = "1";
						$prom = floor(@$tiempos["MP250"]/@$datos["MP250"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
					  if (@$datos["MP250"]=="") @$datos["MP250"] = "1";
						$prom = floor(@$espera["MP250"]/@$datos["MP250"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
				   <tr>
                      <td nowrap width="150">MP 500</td>
                      <td><?php echo @$datos["MP500"] == "" ? "0" : @$datos["MP500"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP500"]=="") @$datos["MP500"] = "1";
						$prom = floor(@$tiempos["MP500"]/@$datos["MP500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					  <td><?php 
					  try {
					  if (@$datos["MP500"]=="") @$datos["MP500"] = "1";
						$prom = floor(@$espera["MP500"]/@$datos["MP500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP 1000</td>
                      <td><?php echo @$datos["MP1000"] == "" ? "0" : @$datos["MP1000"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP1000"]=="") @$datos["MP1000"] = "1";
						$prom = floor(@$tiempos["MP1000"]/@$datos["MP1000"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
					  if (@$datos["MP1000"]=="") @$datos["MP1000"] = "1";
						$prom = floor(@$espera["MP1000"]/@$datos["MP1000"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP Overhaul</td>
                      <td><?php echo @$datos["MP1500"] == "" ? "0" : @$datos["MP1500"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP1500"]=="") @$datos["MP1500"] = "1";
						$prom = floor(@$tiempos["MP1500"]/@$datos["MP1500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
					  if (@$datos["MP1500"]=="") @$datos["MP1500"] = "1";
						$prom = floor(@$espera["MP1500"]/@$datos["MP1500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RP</td>
                      <td><?php echo @$datos["RP"] == "" ? "0" : @$datos["RP"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["RP"]=="") @$datos["RP"] = "1";
						$prom = floor(@$tiempos["RP"]/@$datos["RP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
					  if (@$datos["RP"]=="") @$datos["RP"] = "1";
						$prom = floor(@$espera["RP"]/@$datos["RP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">OP</td>
                      <td><?php echo @$datos["OP"] == "" ? "0" : @$datos["OP"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["OP"]=="") @$datos["OP"] = "1";
						$prom = floor(@$tiempos["OP"]/@$datos["OP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					  <td><?php 
					  try {
					  if (@$datos["OP"]=="") @$datos["OP"] = "1";
						$prom = floor(@$espera["OP"]/@$datos["OP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RI</td>
                      <td><?php echo @$datos["RI"] == "" ? "0" : @$datos["RI"];?></td>
                      <td><?php 
					  try {
						if (@$datos["RI"]=="") @$datos["RI"] = "1";
						$prom = floor(@$tiempos["RI"]/@$datos["RI"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
						if (@$datos["RI"]=="") @$datos["RI"] = "1";
						$prom = floor(@$espera["RI"]/@$datos["RI"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">EX</td>
                      <td><?php echo @$datos["EX"] == "" ? "0" : @$datos["EX"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["EX"]=="") @$datos["EX"] = "1";
						$prom = floor(@$tiempos["EX"]/@$datos["EX"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
					   <td><?php 
					  try {
					  if (@$datos["EX"]=="") @$datos["EX"] = "1";
						$prom = floor(@$espera["EX"]/@$datos["EX"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "-";
					  }
					  ?></td>
                  </tr>
				  <tr>
                      <td nowrap width="150" style="font-weight: bold;">Total Intervenciones</td>
                      <td style="font-weight: bold;"><?php echo count($intervenciones_continuacion);?></td>
                      <td></td>
					  <td></td>
                  </tr>
			  </tbody>
			  </table>
			  
	</div>
	
</div>

<div class="oneTwo">
	<div class="widget">
	<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Intervenciones en Ejecución</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
                      <td nowrap width="150">Tipo Intervención</td>
                      <td># Trabajos</td>
                      <td>Tiempo Estimado Promedio</td>
                  </tr>
              </thead>
			 <?php 
				$datos = array();
				$tiempos = array();
				foreach ($intervenciones_ejecucion as $intervencion) { 
					//$json = json_decode($intervencion["Planificacion"]["json"], true);
					//$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
					$tiempo_trabajo = split(":", $intervencion['Planificacion']['tiempo_trabajo']);
					if (@$tiempo_trabajo[0] != "" && @$tiempo_trabajo[1] != "") {
						$tiempo = abs($tiempo_trabajo[0])*60 + $tiempo_trabajo[1];
					}

					
					$tipo_intervencion = strtoupper($intervencion["Planificacion"]["tipointervencion"]);
					$tipo_mantencion = strtoupper($intervencion["Planificacion"]["tipomantencion"]);
					
					if ($tipo_intervencion != "MP") {
						if (@$datos[$tipo_intervencion] == "") {
							$datos[$tipo_intervencion] = 1;
							$tiempos[$tipo_intervencion] = $tiempo;
						} else {
							$datos[$tipo_intervencion]++;
							$tiempos[$tipo_intervencion] += $tiempo;
						}
					} else {
						if (@$datos[$tipo_intervencion.$tipo_mantencion] == "") {
							$datos[$tipo_intervencion.$tipo_mantencion] = 1;
							$tiempos[$tipo_intervencion.$tipo_mantencion] = $tiempo;
						} else {
							$datos[$tipo_intervencion.$tipo_mantencion]++;
							$tiempos[$tipo_intervencion.$tipo_mantencion] += $tiempo;
						}
					}
				}
			  ?>
              <tbody>
			   <tr>
                      <td nowrap width="150">MP 250</td>
                      <td><?php echo @$datos["MP250"] == "" ? "0" : @$datos["MP250"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP250"]=="") @$datos["MP250"] = "1";
						$prom = floor(@$tiempos["MP250"]/@$datos["MP250"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
				   <tr>
                      <td nowrap width="150">MP 500</td>
                      <td><?php echo @$datos["MP500"] == "" ? "0" : @$datos["MP500"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP500"]=="") @$datos["MP500"] = "1";
						$prom = floor(@$tiempos["MP500"]/@$datos["MP500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP 1000</td>
                      <td><?php echo @$datos["MP1000"] == "" ? "0" : @$datos["MP1000"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP1000"]=="") @$datos["MP1000"] = "1";
						$prom = floor(@$tiempos["MP1000"]/@$datos["MP1000"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">MP Overhaul</td>
                      <td><?php echo @$datos["MP1500"] == "" ? "0" : @$datos["MP1500"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["MP1500"]=="") @$datos["MP1500"] = "1";
						$prom = floor(@$tiempos["MP1500"]/@$datos["MP1500"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RP</td>
                      <td><?php echo @$datos["RP"] == "" ? "0" : @$datos["RP"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["RP"]=="") @$datos["RP"] = "1";
						$prom = floor(@$tiempos["RP"]/@$datos["RP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">OP</td>
                      <td><?php echo @$datos["OP"] == "" ? "0" : @$datos["OP"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["OP"]=="") @$datos["OP"] = "1";
						$prom = floor(@$tiempos["OP"]/@$datos["OP"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">RI</td>
                      <td><?php echo @$datos["RI"] == "" ? "0" : @$datos["RI"];?></td>
                      <td><?php 
					  try {
						if (@$datos["RI"]=="") @$datos["RI"] = "1";
						$prom = floor(@$tiempos["RI"]/@$datos["RI"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
                      <td nowrap width="150">EX</td>
                      <td><?php echo @$datos["EX"] == "" ? "0" : @$datos["EX"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["EX"]=="") @$datos["EX"] = "1";
						$prom = floor(@$tiempos["EX"]/@$datos["EX"]);
						$h = floor(($prom)/60);
						$m = $prom - $h * 60;
						echo $h."h " .$m."m";
					  } catch (Exception $e) {
						echo "0";
					  }
					  ?></td>
                  </tr>
				  <tr>
                      <td nowrap width="150" style="font-weight: bold;">Total Intervenciones</td>
                      <td style="font-weight: bold;"><?php echo count($intervenciones_ejecucion);?></td>
                      <td></td>
                  </tr>
			  </tbody>
			  </table>
	</div>
</div>
</div>
-->
</div>