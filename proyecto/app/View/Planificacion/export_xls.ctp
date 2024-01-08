<?php
	ini_set('memory_limit','128M');
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	
	header("Content-Type: application/vnd.ms-excel");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=Historial-Intervenciones.xls");
?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type=”text/css”>
<!–
body {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
table thead tr td {
	font-weight: bold;
}
–>
</style>
</head>
<body>
<?php
	if (count($intervenciones) > 0) {
?>
<table cellpadding="0" cellspacing="0" width="100%" border="1">
  <thead>
      <tr>
          <td style="font-size: 14px; font-weight: bold;">#</td>
          <?php if (intval($this->Session->read('faena_id')) == 0) { ?>
          <td style="font-size: 14px; font-weight: bold;">Faena</td>
          <?php } ?>
          <td style="font-size: 14px; font-weight: bold;">Código</td>
          <td style="font-size: 14px; font-weight: bold;">Fecha</td>
          <td style="font-size: 14px; font-weight: bold;">Flota</td>
          <td style="font-size: 14px; font-weight: bold;">Equipo</td>
          <td style="font-size: 14px; font-weight: bold;">ESN</td>
          <td style="font-size: 14px; font-weight: bold;">Tipo</td>
          <td style="font-size: 14px; font-weight: bold;">Descripción</td>
          <td style="font-size: 14px; font-weight: bold;">Estado</td>
          <td style="font-size: 14px; font-weight: bold;">Comentario</td>
    </tr>
  </thead>
  <tbody>
  <?php 
    $i = 1;
    foreach ($intervenciones as $intervencion) { 
        $json = json_decode($intervencion["Planificacion"]["json"], true);
        
        if (isset($json["fecha_inicio_g"]) && $json["fecha_inicio_g"] != NULL && $json["fecha_inicio_g"] != "") {
            $date = new DateTime($json["fecha_inicio_g"]);
        } elseif ($intervencion['Planificacion']['fecha'] != '') {
            $date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
        }
  ?>
      <tr>
      <td nowrap><?php echo $i++;?></td>
      <?php if (intval($this->Session->read('faena_id')) == 0) { ?>
            <td nowrap style="font-size: 12px;"><?php echo $intervencion['Faena']['nombre'];?></td>
          <?php } ?>
        <td style="font-size: 12px;"><?php echo $intervencion['Planificacion']['id'];?></td>
        <td nowrap style="font-size: 12px;"><?php echo $date->format('d-m-Y h:i A'); ?></td>
          <td nowrap style="font-size: 12px;"><?php echo $intervencion['Flota']['nombre']; ?></td>
          <td nowrap style="font-size: 12px;"><?php echo $intervencion['Unidad']['unidad']; ?></td>
            <td style="font-size: 12px;"><?php
            $esn = "";
            if (@$json["esn_nuevo"] != "") {
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
            ?></td>
          <td align="center" style="font-size: 12px;">
          <?php 
            if ($intervencion['Planificacion']['padre'] != NULL && $intervencion['Planificacion']['padre'] != '') {
                echo "c".strtoupper($intervencion['Planificacion']['tipointervencion']);
            } else {
                echo strtoupper($intervencion['Planificacion']['tipointervencion']);
            }

          ?>
          </td>
         <td nowrap style="font-size: 12px;">
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
                echo $util->getBacklogInfo($intervencion['Planificacion']['backlog_id']);
            }
        } else {
            echo $util->getSintoma($intervencion['Planificacion']['sintoma_id']);
        }
        ?>
         </td>
          <td align="left" style="font-size: 12px;"><?php 
                $hay_comentarios = false;
                if ($intervencion['Planificacion']['estado'] > 2) {
                    $comentario = "";
                    if (@$json["comentario"] != "") {
                        $comentario = @$json["comentario"];
                    } else {
                        $comentario = @$json["comentarios"];
                    }
                    if (strlen($comentario) > 0) {
                        echo "".$util->getUsuarioInfo($json["tecnico_principal"])." (Técnico):\n";
                        echo "$comentario";
                        $hay_comentarios = true;
                        if (strlen($intervencion['Planificacion']['observacion']) > 0) {
                            echo "\n";
                        }
                    }
                }
                
                if (strlen($intervencion['Planificacion']['observacion']) > 0) {
                    echo "".$util->getUsuarioInfo($intervencion["Planificacion"]["usuario_id"])." (Supervisor):\n";
                    echo "{$intervencion['Planificacion']['observacion']}";
                    $hay_comentarios = true;
                }
                if (!$hay_comentarios) {
                    echo "No hay comentarios.";
                }		
            ?></td>
           <td nowrap style="font-size: 12px;"><?php switch ($intervencion['Planificacion']['estado']) {
            case 4:
                echo "Aprobado DCC";
                break;
            case 5:
                echo "Aprobado Cliente";
                break;
            case 6: 
                echo "Rechazado Cliente";
                break;
            case 2:
                echo "Planificado";
                break;
            case 7:
                echo "Sin Revisar";
                break;
            case 1:
                echo "Borrador";
                break;
            default:
                echo "Sin Información";
                break;
           } ?></td>
           
      </tr>
      <?php } ?>
  </tbody>
</table>
<?php
}
?>
 <?php if (count($intervenciones) == 0) { ?>
	<div>No hay registros para mostrar.</div>
<?php
}
?>
</body>
</html>