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
                      <td nowrap width="150">BL</td>
                      <td><?php echo @$datos["BL"] == "" ? "0" : @$datos["BL"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["BL"]=="") @$datos["BL"] = "1";
						$prom = floor(@$tiempos["BL"]/@$datos["BL"]);
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
			  </tbody>
			  </table>
			  
	</div>
	
	<div class="widget">
  <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Intervenciones con Continuación</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
                      <td nowrap width="150">Tipo Intervención</td>
                      <td># Continuaciones</td>
                      <td>Tiempo Promedio Ejecución</td>
                  </tr>
              </thead>
			 <?php 
				$datos = array();
				$tiempos = array();
				foreach ($intervenciones_continuacion as $intervencion) { 
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
                      <td nowrap width="150">BL</td>
                      <td><?php echo @$datos["BL"] == "" ? "0" : @$datos["BL"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["BL"]=="") @$datos["BL"] = "1";
						$prom = floor(@$tiempos["BL"]/@$datos["BL"]);
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
                      <td nowrap width="150">BL</td>
                      <td><?php echo @$datos["BL"] == "" ? "0" : @$datos["BL"];?></td>
                      <td><?php 
					  try {
					  if (@$datos["BL"]=="") @$datos["BL"] = "1";
						$prom = floor(@$tiempos["BL"]/@$datos["BL"]);
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
			  </tbody>
			  </table>
	</div>
</div>
</div>
</div>