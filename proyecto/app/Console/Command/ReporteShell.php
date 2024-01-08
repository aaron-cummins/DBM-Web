<?php

App::import('Controller', 'Utilidades');
App::import('Controller', 'UtilidadesReporte');
App::import('Vendor', 'Classes/PHPExcel');

/**
 * Description of newReport
 *
 * @author AZUNIGA
 */
//cd /var/www/html/app/ && Console/cake new_report generar -app /var/www/html/app/
//cd /var/www/html/app/ && Console/cake reporte generar_base -app /var/www/html/app/
//cd /var/www/html/app/ && Console/cake new_report generar -app /var/www/html/app/ && Console/cake new_report generar -app /var/www/html/app/ && Console/cake new_report generar -app /var/www/html/app/ && Console/cake new_report generar -app /var/www/html/app/
//for i in `seq 70` ; do Console/cake reporte generar_base -app /var/www/html/app/ | tee -a /tmp/outputscript.txt; done
class ReporteShell extends AppShell {

    public $uses = array('Faena', 'FaenaFlota', 'ReporteBase', 'Unidad', 'DeltaDetalle', 'IntervencionElementos', 'IntervencionFechas', 'IntervencionComentarios', 'Planificacion', 'IntervencionFluido', 'Fluido', 'FluidoUnidad', 'FluidoTipoIngreso', 'Usuario');
    
    public function generar_base() {
        $util = new UtilidadesController();
        $count_intervenciones = 0;
        $count_lineas = 0;
        $duracion = '';
        $corr_ant = 0;

        $this->out("generar_base");
        echo date("d-m-Y h:i:s A");
        echo "\n";

        $HST = new DateTimeZone('America/Santiago');
        $conditions = array();
        $fields = array();
        $order = array();
        $limit = 500;

        $conditions["Planificacion.estado"] = array(4, 5, 6);
        $conditions["Planificacion.reporte_base"] = NULL;
        $conditions["Planificacion.fecha NOT"] = NULL;
        $fields[] = "MIN(Planificacion.fecha) AS min_date";
        $fields[] = "MAX(Planificacion.fecha) AS max_date";

        $intervencion = $this->Planificacion->find('first', array(
            'fields' => $fields,
            'conditions' => $conditions,
            'recursive' => -1));

        $intervencion = $intervencion[0];

        $conditions["Planificacion.fecha BETWEEN ? AND ? "] = array($intervencion["min_date"], $intervencion["max_date"]);
        //$conditions["Planificacion.fecha BETWEEN ? AND ? "] = array('2019-01-01', '2020-12-31');
        
        $fields = array();
        $order = array();
        $order["MIN(Planificacion.fecha)"] = "ASC";
        $fields[] = "Planificacion.correlativo_final";
        $fields[] = "COUNT(*) AS trabajos";

        $correlativos = $this->Planificacion->find('all', array(
            'fields' => $fields,
            'conditions' => $conditions,
            'order' => $order,
            'group' => array('Planificacion.correlativo_final'),
            'limit' => $limit,
            'recursive' => -1));


        $trabajos = array();
        foreach ($correlativos as $k => $v) {
            $correlativo = $v["Planificacion"]["correlativo_final"];
            $conditions = array();
            $conditions["Planificacion.estado IN"] = array(4, 5, 6);
            $conditions["Planificacion.id"] = $correlativo;

            $intervenciones = $this->Planificacion->find('all', array(
                'fields' => array('Planificacion.tiempo_trabajo', 'Planificacion.horometro_cabina', 'Planificacion.horometro_motor', 'Planificacion.json', 'Planificacion.id', 'Planificacion.fecha', 'Planificacion.hora', 'Planificacion.fecha_termino', 'Planificacion.hora_termino', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion', 'Planificacion.faena_id', 'Planificacion.flota_id', 'Planificacion.unidad_id', 'Planificacion.esn', 'Planificacion.correlativo_final', 'Planificacion.folio', 'Planificacion.sintoma_id', 'Planificacion.backlog_id', 'Planificacion.estado', 'Planificacion.aprobador_id', 'Planificacion.supervisor_responsable', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Planificacion.reporte_base', 'Turno.nombre', 'Periodo.nombre', 'LugarReparacion.nombre', 'MotivoLlamado.nombre', 'Sintoma.nombre', 'SintomaCategoria.nombre', 'Planificacion.padre', 'Planificacion.os_sap', 'Planificacion.fecha_operacion'),
                'order' => array('Planificacion.fecha' => 'ASC', 'Planificacion.hora' => 'ASC'),
                'conditions' => $conditions,
                'limit' => $limit,
                'recursive' => 1));

            foreach ($intervenciones as $key => $intervencion) {
                
                $fecha = ($intervencion["Planificacion"]['fecha'] . ' ' . $intervencion["Planificacion"]['hora']);
                $fecha_termino = ($intervencion["Planificacion"]['fecha_termino'] . ' ' . $intervencion["Planificacion"]['hora_termino']);
                $tiempo_trabajo = $this->get_tiempo_trabajo($fecha, $fecha_termino);
                    
                if ($duracion != '' && $tiempo_trabajo > $duracion) {
                    unset($intervenciones[$key]);
                    continue;
                }
                
                $intervenciones[$key]["Planificacion"]["tiempo_trabajo"] = $tiempo_trabajo;
                $intervenciones[$key]["Planificacion"]["json"] = json_decode($intervencion["Planificacion"]["json"], true);
                $intervenciones[$key]["Hijos"] = array();
                //$this->get_hijo($intervenciones[$key]["Planificacion"]["folio"], $intervenciones[$key]["Hijos"]);
                $this->get_hijo_v2($intervenciones[$key]["Planificacion"]["id"], $intervenciones[$key]["Hijos"]);
                $trabajos[] = $intervenciones[$key];
            }
        }

        $dataArray = array();
        $intervenciones = $trabajos;

        $i = 1;
        foreach ($intervenciones as $intervencion) {
            $count_intervenciones++;
            $faena = $intervencion["Faena"]["nombre"];
            $flota = $intervencion["Flota"]["nombre"];
            $unidad = $intervencion["Unidad"]["unidad"];

            $json = $intervencion["Planificacion"]["json"];
            $lugar_reparacion = $intervencion["LugarReparacion"]["nombre"];
            
            
            $tecnico = array();
            $tecnico["tecnico_1"] = is_numeric(@$json["UserID"]) ? $this->get_tecnico_rut($json["UserID"]) : "";
            $tecnico["tecnico_2"] = is_numeric(@$json["TecnicoApoyo02"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo02"]) : "";
            $tecnico["tecnico_3"] = is_numeric(@$json["TecnicoApoyo03"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo03"]) : "";
            $tecnico["tecnico_4"] = is_numeric(@$json["TecnicoApoyo04"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo04"]) : "";
            $tecnico["tecnico_5"] = is_numeric(@$json["TecnicoApoyo05"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo05"]) : "";
            $tecnico["tecnico_6"] = is_numeric(@$json["TecnicoApoyo06"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo06"]) : "";
            $tecnico["tecnico_7"] = is_numeric(@$json["TecnicoApoyo07"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo07"]) : "";
            $tecnico["tecnico_8"] = is_numeric(@$json["TecnicoApoyo08"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo08"]) : "";
            $tecnico["tecnico_9"] = is_numeric(@$json["TecnicoApoyo09"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo09"]) : "";
            $tecnico["tecnico_10"] = is_numeric(@$json["TecnicoApoyo10"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo10"]) : "";
            $aprobador = $this->get_tecnico_rut(@$intervencion["Planificacion"]['aprobador_id']);
            $supervisor = $this->get_tecnico_rut(@$intervencion["Planificacion"]['supervisor_responsable']);
            $turno = $intervencion["Turno"]["nombre"];
            $periodo = $intervencion["Periodo"]["nombre"];
            $motivo_llamado = $intervencion["MotivoLlamado"]["nombre"];
            $comentarios = "";
            $numero_os = $intervencion["Planificacion"]['os_sap'];
            if (@$json["Comentarios"] != "") {
                $comentarios = @$json["Comentarios"];
            } elseif (@$json["Comentario"] != "") {
                $comentarios = @$json["Comentario"];
            }

            $codigoKCH = "";
            if (@$json["codigoKCH"] != "") {
                $codigoKCH = @$json["codigoKCH"];
            } else {
                $int_comentarios = $this->IntervencionComentarios->find('first', array('fields' => array('IntervencionComentarios.*'),
                    'conditions' => array('IntervencionComentarios.folio' => $intervencion["Planificacion"]["folio"]),
                    'recursive' => -1));
                
                if(count($int_comentarios)>0){
                        $codigoKCH = $int_comentarios['IntervencionComentarios']['codigo_kch'];
                }else{
                    $codigoKCH = '';
                }
                //$codigoKCH = $int_comentarios['IntervencionComentarios']['codigo_kch'];
            }

            $sintoma = "";
            $categoria_sintoma = "";
            if (strtoupper($intervencion["Planificacion"]['tipointervencion']) == 'MP') {
                if (isset($json["tipo_programado"])) {
                    if ($json["tipo_programado"] == "1500") {
                        $sintoma = "Overhaul";
                    } else {
                        $sintoma = $json["tipo_programado"];
                    }
                } else {
                    if ($intervencion["Planificacion"]['tipomantencion'] == "1500") {
                        $sintoma = "Overhaul";
                    } else {
                        $sintoma = $intervencion["Planificacion"]['tipomantencion'];
                    }
                }
            } elseif (strtoupper($intervencion["Planificacion"]['tipointervencion']) == 'BL') {
                $sintoma = $util->getBacklogDescripcion($intervencion["Planificacion"]['id']);
            } else {
                $sintoma = $intervencion["Sintoma"]['nombre'];
                $categoria_sintoma = $intervencion["SintomaCategoria"]['nombre'];
            }
            
            /*** CAMBIO MODULO **/
            if(strtoupper($intervencion["Planificacion"]['tipointervencion']) == 'MP'){
                if($sintoma == 'Overhaul'){
                    $cambio_modulo = "SI";   
                    $cambio_modulo_bitacora = "SI"; 
                }else{
                    $cambio_modulo = "NO";   
                    $cambio_modulo_bitacora = "NO";
                }
            }else{
                $cambio_modulo = isset($json["CambioModulo"]) ? (@$json["CambioModulo"] == "S" ? "SI" : "NO") : "NO";   /* $this->existe_cambio_modulo($intervencion["Planificacion"]["correlativo_final"]) == true ? "SI" : "NO"; */
                $cambio_modulo_bitacora = isset($json["CambioModulo"]) ? (@$json["CambioModulo"] == "S" ? "SI" : "NO") : "NO"; /* $this -> existe_cambio_modulo_bitacora($intervencion["Planificacion"]["id"]) == true ? "SI" : "NO"; */
            }
            
            $evento_finalizado = $this->correlativo_terminado($intervencion["Planificacion"]["correlativo_final"]) == "1" ? "SI" : "NO";
            $bitacora_finalizada = $this->intervencion_terminada($intervencion["Planificacion"]["id"]) == "1" ? "SI" : "NO";


            $horometro_cabina = $intervencion["Planificacion"]['horometro_cabina'];
            $horometro_motor = $intervencion["Planificacion"]['horometro_motor'];


            $esn_ = $util->getESNFromEstadoMotor($intervencion["Planificacion"]['faena_id'], $intervencion["Planificacion"]['flota_id'], $intervencion["Planificacion"]['unidad_id'], $intervencion["Planificacion"]['fecha']);
            $esn = $esn_;
            if ($esn_ == '') {
                if (@$json["esn_conexion"] != "") {
                    $esn = @$json["esn_conexion"];
                } elseif (@$json["esn_nuevo"] != "") {
                    $esn = @$json["esn_nuevo"];
                } elseif (@$json["esn"] != "") {
                    $esn = @$json["esn"];
                } else {
                    $esn = $intervencion["Planificacion"]['esn'];
                }

                if (is_numeric($esn)) {
                    $esn = $esn;
                } else {
                    if (isset($intervencion["Planificacion"]['padre'])) {
                        $esn = $util->esnPadre($intervencion["Planificacion"]['padre']);
                    }
                }
            }

            $statuskcc = $util->getEstado($intervencion["Planificacion"]["estado"]);
            $dataLocal = array();
            $dataLocal["faena_id"] = $intervencion["Planificacion"]["faena_id"];
            $dataLocal["flota_id"] = $intervencion["Planificacion"]["flota_id"];
            $dataLocal["unidad_id"] = $intervencion["Planificacion"]["unidad_id"];
            $dataLocal["correlativo"] = $intervencion["Planificacion"]["correlativo_final"];
            $dataLocal["folio"] = $intervencion["Planificacion"]["id"];
            $dataLocal["estado"] = $util->getEstado($intervencion["Planificacion"]["estado"]);
            $dataLocal["faena"] = $faena;
            $dataLocal["flota"] = $flota;
            $dataLocal["unidad"] = $unidad;
            $dataLocal["esn"] = $esn;
            $dataLocal["horometro_cabina"] = $horometro_cabina;
            $dataLocal["horometro_motor"] = $horometro_motor;
            $dataLocal["tipo"] = $intervencion["Planificacion"]["tipointervencion"];
            $dataLocal["actividad"] = $intervencion["Planificacion"]["tipointervencion"];
            $dataLocal["responsable"] = "";
            if ($intervencion["Planificacion"]["padre"] != null && $intervencion["Planificacion"]["padre"] != "") {
                $dataLocal["categoria"] = "Continuación";
            } else {
                $dataLocal["categoria"] = "Inicial";
            }
                        
            $fecha = new DateTime($intervencion["Planificacion"]['fecha'] . ' ' . $intervencion["Planificacion"]['hora']);
            $fecha_termino = new DateTime($intervencion["Planificacion"]['fecha_termino'] . ' ' . $intervencion["Planificacion"]['hora_termino']);
            
            $f_i = ($intervencion["Planificacion"]['fecha'] . ' ' . $intervencion["Planificacion"]['hora']);
            $f_t = ($intervencion["Planificacion"]['fecha_termino'] . ' ' . $intervencion["Planificacion"]['hora_termino']);
            $tmp_trab = $this->get_tiempo_trabajo($f_i, $f_t);
            
            $dataLocal["fecha_inicio"] = $fecha->format('Y-m-d');
            $dataLocal["hora_inicio"] = $fecha->format('H:i');
            $dataLocal["fecha_termino"] = $fecha_termino->format('Y-m-d');
            $dataLocal["hora_termino"] = $fecha_termino->format('H:i');
            $dataLocal["tiempo"] = $tmp_trab; //($ti_t - $ti_i) / 3600; // Tiempo trabajo
            
            $dataLocal["motivo_llamado"] = $motivo_llamado;
            $dataLocal["categoria_sintoma"] = $categoria_sintoma;
            $dataLocal["sintoma"] = $sintoma;
            $dataLocal["sistema"] = "";
            $dataLocal["subsistema"] = "";
            $dataLocal["pos_subsistema"] = "";
            $dataLocal["id_code"] = "";
            $dataLocal["elemento"] = "";
            $dataLocal["pos_elemento"] = "";
            $dataLocal["diagnostico"] = "";
            $dataLocal["solucion"] = "";
            $dataLocal["causa"] = "";
            $dataLocal["cambio_modulo"] = $cambio_modulo;
            $dataLocal["evento_finalizado"] = $evento_finalizado;
            $dataLocal["cambio_modulo_bitacora"] = $cambio_modulo_bitacora;
            $dataLocal["intervencion_terminada_bitacora"] = $bitacora_finalizada;
            $dataLocal["cambio_modulo_evento"] = $cambio_modulo;
            $dataLocal["intervencion_terminada_evento"] = $evento_finalizado;
            $dataLocal["numero_os_sap"] = $numero_os;

            $dataLocal["lugar_reparacion"] = $lugar_reparacion;

            foreach ($tecnico as $k => $v) {
                $dataLocal[$k] = $v;
            }
            $dataLocal["supervisor_responsable"] = $supervisor;
            $dataLocal["turno"] = $turno;
            $dataLocal["periodo"] = $periodo;
            $dataLocal["supervisor_aprobador"] = $aprobador;
            $dataLocal["status_kch"] = $statuskcc;

            $comentarios = preg_replace('[\n|\r|\n\r]', ' ', $comentarios); //str_replace("\n", ' ', $comentarios);
            $dataLocal["comentario_tecnico"] = $comentarios;

            $codigoKCH = preg_replace('[\n|\r|\n\r]', ' ', $codigoKCH);
            $dataLocal["codigokch"] = $codigoKCH;
            $dataArray[] = $dataLocal;

            $linea = count($dataArray) + 1;

            $time = strtotime($fecha->format('d-m-Y h:i A'));
            $time_termino = strtotime($fecha_termino->format('d-m-Y h:i A'));

            if ($intervencion["Planificacion"]["tipointervencion"] != 'MP' && $intervencion["Planificacion"]["tipointervencion"] != 'RP') {
                $this->generar_delta_1($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_delta_2($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] != 'MP') {
                $this->generar_delta_3($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] != 'MP') {
                $this->generar_get_elementos($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] != 'EX' && $intervencion["Planificacion"]["tipointervencion"] != 'MP') {
                $this->generar_delta_4($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] == 'MP' && $intervencion["Planificacion"]["tipomantencion"] != '1500') {
                $this->generar_delta_mp($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] != 'EX') {
                $this->generar_espera_desconexion($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_registro_desconexion($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_espera_conexion($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_registro_conexion($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_espera_puesta_marcha($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_registro_puesta_marcha($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_registro_prueba_potencia($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] != 'EX' && $intervencion["Planificacion"]["tipointervencion"] != 'MP') {
                $this->generar_delta_5($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_get_elementos_reproceso($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] == 'EX' && !isset($json["Elemento_1"])) {
                //$tiempo_trabajoex = $this->get_tiempo_trabajo(date('Y-m-d H:i', $time), date('Y-m-d H:i', $time_termino));
                $tiempo_trabajo = ($time_termino - $time) / 3600;
                $dataLocal["actividad"] = "EX";
                $dataLocal["responsable"] = "OEM";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
                $dataLocal["hora_termino"] = date('H:i', $time_termino);
                $dataLocal["tiempo"] = $tiempo_trabajo;
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Inicio Interv-Término Interv.";
                $dataLocal["pos_subsistema"] = "";
                $dataLocal["elemento"] = "Intervención";
                $dataArray[] = $dataLocal;
            }

            if ($intervencion["Planificacion"]["tipointervencion"] == "OP" && substr($intervencion["Planificacion"]["folio"], 0, 2) == "EX" && !isset($json["Elemento_1"])) {
                //$tiempo_trabajoop = $this->get_tiempo_trabajo(date('Y-m-d H:i', $time), date('Y-m-d H:i', $time_termino));
                $tiempo_trabajo = ($time_termino - $time) / 3600;
                $dataLocal["actividad"] = "OP";
                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
                $dataLocal["hora_termino"] = date('H:i', $time_termino);
                $dataLocal["tiempo"] = $tiempo_trabajo;
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Inicio Interv-Término Interv.";
                $dataLocal["pos_subsistema"] = "";
                $dataLocal["elemento"] = "Intervención";
                $dataArray[] = $dataLocal;
            }

            if ($intervencion["Planificacion"]["tipointervencion"] == "RI" && substr($intervencion["Planificacion"]["folio"], 0, 2) == "EX" && !isset($json["Elemento_1"])) {
                //$tiempo_trabajori = $this->get_tiempo_trabajo(date('Y-m-d H:i', $time), date('Y-m-d H:i', $time_termino));
                $tiempo_trabajo = ($time_termino - $time) / 3600;
                $dataLocal["actividad"] = "RI";
                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
                $dataLocal["hora_termino"] = date('H:i', $time_termino);
                $dataLocal["tiempo"] = $tiempo_trabajo;
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Inicio Interv-Término Interv.";
                $dataLocal["pos_subsistema"] = "";
                $dataLocal["elemento"] = "Intervención";
                $dataArray[] = $dataLocal;
            }

            $this->generar_registro_fluidos($time_termino, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["id"]);

            if (isset($intervencion["Planificacion"]['fecha_operacion'])) {
                $this->generar_delta_operacion($time_termino, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            $this->generar_delta_tiempo_adicional_bitacora($time_termino, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);


            foreach ($intervencion["Hijos"] as $intervencion_hijo) {
                $count_intervenciones++;
                $original = $dataLocal;
                
                $json = $intervencion_hijo["Planificacion"]["json"];
                $tecnico = array();
                $tecnico["tecnico_1"] = is_numeric(@$json["UserID"]) ? $this->get_tecnico_rut($json["UserID"]) : "";
                $tecnico["tecnico_2"] = is_numeric(@$json["TecnicoApoyo02"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo02"]) : "";
                $tecnico["tecnico_3"] = is_numeric(@$json["TecnicoApoyo03"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo03"]) : "";
                $tecnico["tecnico_4"] = is_numeric(@$json["TecnicoApoyo04"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo04"]) : "";
                $tecnico["tecnico_5"] = is_numeric(@$json["TecnicoApoyo05"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo05"]) : "";
                $tecnico["tecnico_6"] = is_numeric(@$json["TecnicoApoyo06"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo06"]) : "";
                $tecnico["tecnico_7"] = is_numeric(@$json["TecnicoApoyo07"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo07"]) : "";
                $tecnico["tecnico_8"] = is_numeric(@$json["TecnicoApoyo08"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo08"]) : "";
                $tecnico["tecnico_9"] = is_numeric(@$json["TecnicoApoyo09"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo09"]) : "";
                $tecnico["tecnico_10"] = is_numeric(@$json["TecnicoApoyo10"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo10"]) : "";
                $aprobador = $this->get_tecnico_rut(@$intervencion_hijo["Planificacion"]['aprobador_id']);
                $supervisor = $this->get_tecnico_rut(@$intervencion_hijo["Planificacion"]['supervisor_responsable']);
                $turno = $intervencion["Turno"]["nombre"];
                $periodo = $intervencion["Periodo"]["nombre"];

                $comentarios = "";
                if (@$json["Comentarios"] != "") {
                    $comentarios = @$json["Comentarios"];
                } elseif (@$json["Comentario"] != "") {
                    $comentarios = @$json["Comentario"];
                }

                /* COMENTARIO KCH */
                $codigoKCH = "";
                if (@$json["codigoKCH"] != "") {
                    $codigoKCH = @$json["codigoKCH"];
                } else {
                    $int_comentarios = $this->IntervencionComentarios->find('first', array('fields' => array('IntervencionComentarios.*'),
                        'conditions' => array('IntervencionComentarios.folio' => $intervencion["Planificacion"]["folio"]),
                        'recursive' => -1));
                    
                    if(count($int_comentarios)>0){
                        $codigoKCH = $int_comentarios['IntervencionComentarios']['codigo_kch'];
                    }else{
                        $codigoKCH = '';
                    }
                    //$codigoKCH = $int_comentarios['IntervencionComentarios']['codigo_kch'];
                }

                $statuskcc = $util->getEstado($intervencion_hijo["Planificacion"]["estado"]);
                $dataLocal = array();
                $dataLocal["estado"] = $statuskcc;
                $dataLocal["faena_id"] = $original["faena_id"];
                $dataLocal["flota_id"] = $original["flota_id"];
                $dataLocal["unidad_id"] = $original["unidad_id"];
                $dataLocal["correlativo"] = $intervencion_hijo["Planificacion"]["correlativo_final"];
                $dataLocal["folio"] = $intervencion_hijo["Planificacion"]["id"];
                $dataLocal["faena"] = $original["faena"];
                $dataLocal["flota"] = $original["flota"];
                $dataLocal["unidad"] = $original["unidad"];
                $dataLocal["esn"] = $original["esn"];
                $dataLocal["horometro_cabina"] = $original["horometro_cabina"];
                $dataLocal["horometro_motor"] = $original["horometro_motor"];
                $dataLocal["tipo"] = $original["tipo"];
                $dataLocal["actividad"] = "C" . $intervencion_hijo["Planificacion"]["tipointervencion"];
                $dataLocal["responsable"] = ""; // 10
                $dataLocal["categoria"] = "Continuación";
                
                $fechaH = new DateTime($intervencion_hijo['Planificacion']['fecha'] . ' ' . $intervencion_hijo['Planificacion']['hora']);
                $fecha_terminoH = new DateTime($intervencion_hijo['Planificacion']['fecha_termino'] . ' ' . $intervencion_hijo['Planificacion']['hora_termino']);
                
                $fH_i = ($intervencion_hijo['Planificacion']['fecha'] . ' ' . $intervencion_hijo['Planificacion']['hora']);
                $fH_t = ($intervencion_hijo['Planificacion']['fecha_termino'] . ' ' . $intervencion_hijo['Planificacion']['hora_termino']);
                
                $fecha_termino_padre = $this->get_fecha_termino_intervencion_padre($intervencion_hijo, $intervencion["Hijos"]);
                $tie_trabajo = $this->get_tiempo_trabajo($fH_i, $fH_t);
                
                $dataLocal["fecha_inicio"] = $fechaH->format('Y-m-d');
                $dataLocal["hora_inicio"] = $fechaH->format('H:i');
                $dataLocal["fecha_termino"] = $fecha_terminoH->format('Y-m-d');
                $dataLocal["hora_termino"] = $fecha_terminoH->format('H:i');
                
                $dataLocal["tiempo"] = $tie_trabajo; //($th_t - $th_i) / 3600; //$tiempo_trabajo; //
                //echo $fechaH->format('Y-m-d H:i:s A'). '-'.$fecha_terminoH->format('Y-m-d H:i:s A'). ' --- '. $intervencion_hijo["Planificacion"]["id"]. '=>' . $tie_trabajo . ' -- '.$th_trabajo;
                
                $dataLocal["motivo_llamado"] = $original["motivo_llamado"];
                $dataLocal["categoria_sintoma"] = $original["categoria_sintoma"];
                $dataLocal["sintoma"] = $original["sintoma"];
                $dataLocal["sistema"] = ""; // 20
                $dataLocal["subsistema"] = "";
                $dataLocal["pos_subsistema"] = "";
                $dataLocal["id_code"] = "";
                $dataLocal["elemento"] = "";
                $dataLocal["pos_elemento"] = "";
                $dataLocal["diagnostico"] = "";
                $dataLocal["solucion"] = "";
                $dataLocal["causa"] = "";

                /*** CAMBIO MODULO **/
                if(strtoupper($intervencion_hijo["Planificacion"]['tipointervencion']) == 'MP'){
                    if($original["sintoma"] == 'Overhaul'){
                        $cambio_modulo = "SI";   
                    }else{
                        $cambio_modulo = "NO";   
                    }
                }else{
                    $cambio_modulo = isset($json["CambioModulo"]) ? (@$json["CambioModulo"] == "S" ? "SI" : "NO") : "NO";   /* $this->existe_cambio_modulo($intervencion["Planificacion"]["correlativo_final"]) == true ? "SI" : "NO"; */
                }

                $dataLocal["cambio_modulo"] = $cambio_modulo;
                $dataLocal["evento_finalizado"] = $original["evento_finalizado"]; // 30;
                $dataLocal["cambio_modulo_bitacora"] = isset($json["CambioModulo"]) ? (@$json["CambioModulo"] == "S" ? "SI" : "NO") : "NO"; /* $this -> existe_cambio_modulo_bitacora($intervencion_hijo["Planificacion"]["id"]) == "1" ? "SI" : "NO"; */
                $dataLocal["intervencion_terminada_bitacora"] = $this->intervencion_terminada($intervencion_hijo["Planificacion"]["id"]) == "1" ? "SI" : "NO";
                $dataLocal["cambio_modulo_evento"] = $cambio_modulo;
                $dataLocal["intervencion_terminada_evento"] = $original["intervencion_terminada_evento"];
                $dataLocal["numero_os_sap"] = $intervencion_hijo["Planificacion"]["os_sap"];
                $dataLocal["lugar_reparacion"] = $original["lugar_reparacion"];
                foreach ($tecnico as $k => $v) {
                    $dataLocal[$k] = $v;
                }

                $dataLocal["supervisor_responsable"] = $supervisor;
                $dataLocal["turno"] = $turno;
                $dataLocal["periodo"] = $periodo;
                $dataLocal["supervisor_aprobador"] = $aprobador;
                $dataLocal["status_kch"] = $statuskcc;
                $comentarios = preg_replace('[\n|\r|\n\r]', ' ', $comentarios);
                $dataLocal["comentario_tecnico"] = $comentarios;

                $codigoKCH = preg_replace('[\n|\r|\n\r]', ' ', $codigoKCH);
                $dataLocal["codigokch"] = $codigoKCH;

                if (isset($fecha_termino_padre)) {
                    $time_anterior = strtotime((new DateTime($fecha_termino_padre))->format('d-m-Y h:i A'));
                } else {
                    $time_anterior = strtotime($fechaH->format('d-m-Y h:i A'));
                }

                $this->generar_delta_continuacion($time_anterior, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                $dataArray[] = $dataLocal;

                $linea = count($dataArray) + 1;

                $time = strtotime($fechaH->format('d-m-Y h:i A'));
                $time_termino = strtotime($fecha_terminoH->format('d-m-Y h:i A'));
                
                if ($intervencion["Planificacion"]["tipointervencion"] != 'MP' && $intervencion["Planificacion"]["tipointervencion"] != 'RP') {
                    $this->generar_delta_1($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_delta_2($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                if ($intervencion_hijo["Planificacion"]["tipointervencion"] != 'MP') {
                    $this->generar_delta_3($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                if ($intervencion_hijo["Planificacion"]["tipointervencion"] != 'MP') {
                    $this->generar_get_elementos($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                if ($intervencion_hijo["Planificacion"]["tipointervencion"] != 'EX' && $intervencion_hijo["Planificacion"]["tipointervencion"] != 'MP') {
                    $this->generar_delta_4($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                if ($intervencion_hijo["Planificacion"]["tipointervencion"] == 'MP' && $intervencion_hijo["Planificacion"]["tipomantencion"] != '1500') {
                    $this->generar_delta_mp($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }
                if ($intervencion_hijo["Planificacion"]["tipointervencion"] != 'EX') {
                    $this->generar_espera_desconexion($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_registro_desconexion($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_espera_conexion($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_registro_conexion($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_espera_puesta_marcha($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_registro_puesta_marcha($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_registro_prueba_potencia($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                if ($intervencion_hijo["Planificacion"]["tipointervencion"] != 'EX' && $intervencion_hijo["Planificacion"]["tipointervencion"] != 'MP') {
                    $this->generar_delta_5($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_get_elementos_reproceso($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }
                $this->generar_registro_fluidos($time_termino, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["id"]);
                if (isset($intervencion_hijo["Planificacion"]['fecha_operacion'])) {
                    $this->generar_delta_operacion($time_termino, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                $this->generar_delta_tiempo_adicional_bitacora($time_termino, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
            }
        }

        $total = count($dataArray) + 1;
        foreach ($dataArray as $value) {
            $count_lineas++;
            try {
                if (!isset($value["reporte_base"]) || $value["reporte_base"] == NULL) {
                    $new_value = $value;
                    unset($new_value["motivo_llamado"], $new_value["categoria_sintoma"], $new_value["sintoma"], $new_value["lugar_reparacion"], $new_value["supervisor_responsable"], $new_value["turno"], $new_value["periodo"], $new_value["supervisor_aprobador"], $new_value["status_kch"], $new_value["comentario_tecnico"], $new_value["reporte_base"]);
                    if (isset($new_value)) {
                        $new_value["tiempo"] = !is_null($new_value["tiempo"]) && !empty($new_value["tiempo"]) && is_numeric($new_value["tiempo"]) ? str_replace(',', '.', $new_value["tiempo"]) : null;
                    }
                    if (isset($value["tiempo"])) {
                        $value["tiempo"] = !is_null($value["tiempo"]) && !empty($value["tiempo"]) && is_numeric($value["tiempo"]) ? str_replace(',', '.', $value["tiempo"]) : null;
                    }
                    $reporte_base = $this->ReporteBase->find('first', array('fields' => array('ReporteBase.id'), 'conditions' => $new_value, 'recursive' => -1));
                    if (isset($reporte_base["ReporteBase"]) && isset($reporte_base["ReporteBase"]["id"])) {
                        
                    } else {
                        try {

                            $this->ReporteBase->create();
                            $this->ReporteBase->save($value);

                            $correlativo_final_reporte = $value["correlativo"];
                            $estadoEvento = $value["intervencion_terminada_evento"];
                            $cambioModulo = $value["cambio_modulo_evento"];

                            if ($estadoEvento != null && $estadoEvento == 'SI') {

                                $this->ReporteBase->updateAll(
                                        array('ReporteBase.intervencion_terminada_evento' => "'SI'"),
                                        array('ReporteBase.correlativo' => $correlativo_final_reporte));
                            }

                            if ($cambioModulo != null && $cambioModulo == 'SI') {
                                $corr_ant = $correlativo_final_reporte;
                                $camb_mod = "SI";
                                $this->ReporteBase->updateAll(
                                        array('ReporteBase.cambio_modulo_evento' => "'SI'"),
                                        array('ReporteBase.correlativo' => $correlativo_final_reporte));
                            } else {
                                if ($corr_ant == $correlativo_final_reporte && $camb_mod == "SI") {
                                    $this->ReporteBase->updateAll(
                                            array('ReporteBase.cambio_modulo_evento' => "'SI'"),
                                            array('ReporteBase.correlativo' => $correlativo_final_reporte));
                                } else {
                                    $corr_ant = $correlativo_final_reporte;
                                    $camb_mod = "NO";
                                }
                            }
                        } catch (Exception $e) {
                            echo $e->getTraceAsString();
                            echo $e->getMessage();
                            print_r("Entro en excepcion");
                            print_r($reporte_base);
                        }
                    }
                    $data = array();
                    $data["id"] = $value["folio"];
                    $data["reporte_base"] = 1;
                    $this->Planificacion->save($data);
                    //var_dump($data);
                }
            } catch (Exception $e) {
                print_r("Error: " . ($value["folio"]) . " - " . $e->getMessage());
            }
        }

        print_r("\n");
        print_r("$count_intervenciones intervenciones\n");
        print_r("$count_lineas lineas\n");
        echo date("d-m-Y h:i:s A");
        print_r("\n");
    }

    public function generar_base_manual() {
        //$faena_id, $fecha_ini, $hora_ini, $fecha_fin, $hora_fin
        $util = new UtilidadesController();
        $count_intervenciones = 0;
        $count_lineas = 0;
        $duracion = '';
        $corr_ant = 0;
    
        $HST = new DateTimeZone('America/Santiago');
        $conditions = array();
        

        $conditions["Planificacion.estado"] = array(4, 5, 6, 7);
        $conditions["Planificacion.fecha NOT"] = NULL;
        $conditions["Planificacion.faena_id"] = $this->args[0];
        
        $faenas = $this->args[0];
        $fecha_ini = $this->args[1].'T'. $this->args[2];
        $fecha_fin = $this->args[3].'T'. $this->args[4];
        $conditions["CONCAT(Planificacion.fecha,'T',Planificacion.hora) BETWEEN ? AND ? "] = array($fecha_ini, $fecha_fin);
       
        
        $correlativos = array();
        
        //periodo
        $correlativo = $this->Planificacion->find('all', array(
                        'fields' => array("Planificacion.correlativo_final", "COUNT(*) AS trabajos"),
                        'conditions' => $conditions,
                        'group' => array('Planificacion.correlativo_final'),
                        'recursive' => 1));
        //ANTERIOR
        $correlativo2 = $this->Planificacion->find('all', array(
                        'fields' => array("Planificacion.correlativo_final", "COUNT(*) AS trabajos"),
                        'conditions' => array("Planificacion.fecha is NOT NULL", "Planificacion.faena_id = $faenas",
                                              "Planificacion.terminado = 'N'", "CONCAT(Planificacion.fecha,'T',Planificacion.hora) <= '$fecha_ini'"),
                        'group' => array('Planificacion.correlativo_final'),
                        'recursive' => 1));
        
        $correlativos = array_merge($correlativo, $correlativo2);
        
        $trabajos = array();
        
        
        foreach ($correlativos as $k => $v) {
            $corr = $v["Planificacion"]["correlativo_final"];
            $conditions = array();
            $conditions["Planificacion.estado IN"] = array(4, 5, 6, 7);
            $conditions["Planificacion.id"] = $corr;

            $intervenciones = $this->Planificacion->find('all', array(
                'fields' => array('Planificacion.tiempo_trabajo', 'Planificacion.horometro_cabina', 'Planificacion.horometro_motor', 'Planificacion.json', 'Planificacion.id', 'Planificacion.fecha', 'Planificacion.hora', 'Planificacion.fecha_termino', 'Planificacion.hora_termino', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion', 'Planificacion.faena_id', 'Planificacion.flota_id', 'Planificacion.unidad_id', 'Planificacion.esn', 'Planificacion.correlativo_final', 'Planificacion.folio', 'Planificacion.sintoma_id', 'Planificacion.backlog_id', 'Planificacion.estado', 'Planificacion.aprobador_id', 'Planificacion.supervisor_responsable', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Planificacion.reporte_base', 'Turno.nombre', 'Periodo.nombre', 'LugarReparacion.nombre', 'MotivoLlamado.nombre', 'Sintoma.nombre', 'SintomaCategoria.nombre', 'Planificacion.padre', 'Planificacion.os_sap', 'Planificacion.fecha_operacion'),
                'order' => array('Planificacion.fecha' => 'ASC', 'Planificacion.hora' => 'ASC'),
                'conditions' => $conditions,
                'recursive' => 1));
                        
                foreach ($intervenciones as $key => $intervencion) {
                    $fecha = ($intervencion["Planificacion"]['fecha'] . ' ' . $intervencion["Planificacion"]['hora']);
                    $fecha_termino = ($intervencion["Planificacion"]['fecha_termino'] . ' ' . $intervencion["Planificacion"]['hora_termino']);
                    $tiempo_trabajo = $this->get_tiempo_trabajo($fecha, $fecha_termino);

                    if ($duracion != '' && $tiempo_trabajo > $duracion) {
                        unset($intervenciones[$key]);
                        continue;
                    }

                    $intervenciones[$key]["Planificacion"]["tiempo_trabajo"] = $tiempo_trabajo;
                    $intervenciones[$key]["Planificacion"]["json"] = json_decode($intervencion["Planificacion"]["json"], true);
                    $intervenciones[$key]["Hijos"] = array();
                    //$this->get_hijo($intervenciones[$key]["Planificacion"]["folio"], $intervenciones[$key]["Hijos"]);
                    $this->get_hijo_v2_manual($intervenciones[$key]["Planificacion"]["id"], $intervenciones[$key]["Hijos"], $fecha_ini, $fecha_fin);
                    $trabajos[] = $intervenciones[$key];

                }
        }
        
        $dataArray = array();
        $interv = $trabajos;
        $i = 1;
        
        foreach ($interv as $intervencion) {
            
            $count_intervenciones++;            
            $faena = $intervencion["Faena"]["nombre"];
            $flota = $intervencion["Flota"]["nombre"];
            $unidad = $intervencion["Unidad"]["unidad"];

            $json = $intervencion["Planificacion"]["json"];
            $lugar_reparacion = $intervencion["LugarReparacion"]["nombre"];
            
            
            $tecnico = array();
            $tecnico["tecnico_1"] = is_numeric(@$json["UserID"]) ? $this->get_tecnico_rut($json["UserID"]) : "";
            $tecnico["tecnico_2"] = is_numeric(@$json["TecnicoApoyo02"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo02"]) : "";
            $tecnico["tecnico_3"] = is_numeric(@$json["TecnicoApoyo03"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo03"]) : "";
            $tecnico["tecnico_4"] = is_numeric(@$json["TecnicoApoyo04"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo04"]) : "";
            $tecnico["tecnico_5"] = is_numeric(@$json["TecnicoApoyo05"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo05"]) : "";
            $tecnico["tecnico_6"] = is_numeric(@$json["TecnicoApoyo06"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo06"]) : "";
            $tecnico["tecnico_7"] = is_numeric(@$json["TecnicoApoyo07"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo07"]) : "";
            $tecnico["tecnico_8"] = is_numeric(@$json["TecnicoApoyo08"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo08"]) : "";
            $tecnico["tecnico_9"] = is_numeric(@$json["TecnicoApoyo09"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo09"]) : "";
            $tecnico["tecnico_10"] = is_numeric(@$json["TecnicoApoyo10"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo10"]) : "";
            $aprobador = $this->get_tecnico_rut(@$intervencion["Planificacion"]['aprobador_id']);
            $supervisor = $this->get_tecnico_rut(@$intervencion["Planificacion"]['supervisor_responsable']);
            $turno = $intervencion["Turno"]["nombre"];
            $periodo = $intervencion["Periodo"]["nombre"];
            $motivo_llamado = $intervencion["MotivoLlamado"]["nombre"];
            $comentarios = "";
            $numero_os = $intervencion["Planificacion"]['os_sap'];
            if (@$json["Comentarios"] != "") {
                $comentarios = @$json["Comentarios"];
            } elseif (@$json["Comentario"] != "") {
                $comentarios = @$json["Comentario"];
            }

            $codigoKCH = "";
            if (@$json["codigoKCH"] != "") {
                $codigoKCH = @$json["codigoKCH"];
            } else {
                $int_comentarios = $this->IntervencionComentarios->find('first', array('fields' => array('IntervencionComentarios.*'),
                    'conditions' => array('IntervencionComentarios.folio' => $intervencion["Planificacion"]["folio"]),
                    'recursive' => -1));
                if(count($int_comentarios)>0){
                    $codigoKCH = $int_comentarios['IntervencionComentarios']['codigo_kch'];
                }else{
                    $codigoKCH = '';
                }
            }

            $sintoma = "";
            $categoria_sintoma = "";
            if (strtoupper($intervencion["Planificacion"]['tipointervencion']) == 'MP') {
                if (isset($json["tipo_programado"])) {
                    if ($json["tipo_programado"] == "1500") {
                        $sintoma = "Overhaul";
                    } else {
                        $sintoma = $json["tipo_programado"];
                    }
                } else {
                    if ($intervencion["Planificacion"]['tipomantencion'] == "1500") {
                        $sintoma = "Overhaul";
                    } else {
                        $sintoma = $intervencion["Planificacion"]['tipomantencion'];
                    }
                }
            } elseif (strtoupper($intervencion["Planificacion"]['tipointervencion']) == 'BL') {
                $sintoma = $util->getBacklogDescripcion($intervencion["Planificacion"]['id']);
            } else {
                $sintoma = $intervencion["Sintoma"]['nombre'];
                $categoria_sintoma = $intervencion["SintomaCategoria"]['nombre'];
            }
            
            /*** CAMBIO MODULO **/
            if(strtoupper($intervencion["Planificacion"]['tipointervencion']) == 'MP'){
                if($sintoma == 'Overhaul'){
                    $cambio_modulo = "SI";   
                    $cambio_modulo_bitacora = "SI"; 
                }else{
                    $cambio_modulo = "NO";   
                    $cambio_modulo_bitacora = "NO";
                }
            }else{
                $cambio_modulo = isset($json["CambioModulo"]) ? (@$json["CambioModulo"] == "S" ? "SI" : "NO") : "NO";   /* $this->existe_cambio_modulo($intervencion["Planificacion"]["correlativo_final"]) == true ? "SI" : "NO"; */
                $cambio_modulo_bitacora = isset($json["CambioModulo"]) ? (@$json["CambioModulo"] == "S" ? "SI" : "NO") : "NO"; /* $this -> existe_cambio_modulo_bitacora($intervencion["Planificacion"]["id"]) == true ? "SI" : "NO"; */
            }
            
            $evento_finalizado = $this->correlativo_terminado($intervencion["Planificacion"]["correlativo_final"]) == "1" ? "SI" : "NO";
            $bitacora_finalizada = $this->intervencion_terminada($intervencion["Planificacion"]["id"]) == "1" ? "SI" : "NO";


            $horometro_cabina = $intervencion["Planificacion"]['horometro_cabina'];
            $horometro_motor = $intervencion["Planificacion"]['horometro_motor'];


            $esn_ = $util->getESNFromEstadoMotor($intervencion["Planificacion"]['faena_id'], $intervencion["Planificacion"]['flota_id'], $intervencion["Planificacion"]['unidad_id'], $intervencion["Planificacion"]['fecha']);
            $esn = $esn_;
            if ($esn_ == '') {
                if (@$json["esn_conexion"] != "") {
                    $esn = @$json["esn_conexion"];
                } elseif (@$json["esn_nuevo"] != "") {
                    $esn = @$json["esn_nuevo"];
                } elseif (@$json["esn"] != "") {
                    $esn = @$json["esn"];
                } else {
                    $esn = $intervencion["Planificacion"]['esn'];
                }

                if (is_numeric($esn)) {
                    $esn = $esn;
                } else {
                    if (isset($intervencion["Planificacion"]['padre'])) {
                        $esn = $util->esnPadre($intervencion["Planificacion"]['padre']);
                    }
                }
            }

            $statuskcc = $util->getEstado($intervencion["Planificacion"]["estado"]);
            $dataLocal = array();
            $dataLocal["faena_id"] = $intervencion["Planificacion"]["faena_id"];
            $dataLocal["flota_id"] = $intervencion["Planificacion"]["flota_id"];
            $dataLocal["unidad_id"] = $intervencion["Planificacion"]["unidad_id"];
            $dataLocal["correlativo"] = $intervencion["Planificacion"]["correlativo_final"];
            $dataLocal["folio"] = $intervencion["Planificacion"]["id"];
            $dataLocal["folio_unico"] = $intervencion["Planificacion"]["folio"];
            $dataLocal["estado"] = $util->getEstado($intervencion["Planificacion"]["estado"]);
            $dataLocal["faena"] = $faena;
            $dataLocal["flota"] = $flota;
            $dataLocal["unidad"] = $unidad;
            $dataLocal["esn"] = $esn;
            $dataLocal["horometro_cabina"] = $horometro_cabina;
            $dataLocal["horometro_motor"] = $horometro_motor;
            $dataLocal["tipo"] = $intervencion["Planificacion"]["tipointervencion"];
            $dataLocal["actividad"] = $intervencion["Planificacion"]["tipointervencion"];
            $dataLocal["responsable"] = "";
            if ($intervencion["Planificacion"]["padre"] != null && $intervencion["Planificacion"]["padre"] != "") {
                $dataLocal["categoria"] = "Continuación";
            } else {
                $dataLocal["categoria"] = "Inicial";
            }
                        
            $fecha = new DateTime($intervencion["Planificacion"]['fecha'] . ' ' . $intervencion["Planificacion"]['hora']);
            $fecha_termino = new DateTime($intervencion["Planificacion"]['fecha_termino'] . ' ' . $intervencion["Planificacion"]['hora_termino']);
            
            $f_i = ($intervencion["Planificacion"]['fecha'] . ' ' . $intervencion["Planificacion"]['hora']);
            $f_t = ($intervencion["Planificacion"]['fecha_termino'] . ' ' . $intervencion["Planificacion"]['hora_termino']);
            $tmp_trab = $this->get_tiempo_trabajo($f_i, $f_t);
            
            $dataLocal["fecha_inicio"] = $fecha->format('Y-m-d');
            $dataLocal["hora_inicio"] = $fecha->format('H:i');
            $dataLocal["fecha_termino"] = $fecha_termino->format('Y-m-d');
            $dataLocal["hora_termino"] = $fecha_termino->format('H:i');
            $dataLocal["tiempo"] = $tmp_trab; //($ti_t - $ti_i) / 3600; // Tiempo trabajo
            
            $dataLocal["motivo_llamado"] = $motivo_llamado;
            $dataLocal["categoria_sintoma"] = $categoria_sintoma;
            $dataLocal["sintoma"] = $sintoma;
            $dataLocal["sistema"] = "";
            $dataLocal["subsistema"] = "";
            $dataLocal["pos_subsistema"] = "";
            $dataLocal["id_code"] = "";
            $dataLocal["elemento"] = "";
            $dataLocal["pos_elemento"] = "";
            $dataLocal["diagnostico"] = "";
            $dataLocal["solucion"] = "";
            $dataLocal["causa"] = "";
            $dataLocal["cambio_modulo"] = $cambio_modulo;
            $dataLocal["evento_finalizado"] = $evento_finalizado;
            $dataLocal["cambio_modulo_bitacora"] = $cambio_modulo_bitacora;
            $dataLocal["intervencion_terminada_bitacora"] = $bitacora_finalizada;
            $dataLocal["cambio_modulo_evento"] = $cambio_modulo;
            $dataLocal["intervencion_terminada_evento"] = $evento_finalizado;
            $dataLocal["numero_os_sap"] = $numero_os;

            $dataLocal["lugar_reparacion"] = $lugar_reparacion;

            foreach ($tecnico as $k => $v) {
                $dataLocal[$k] = $v;
            }
            $dataLocal["supervisor_responsable"] = $supervisor;
            $dataLocal["turno"] = $turno;
            $dataLocal["periodo"] = $periodo;
            $dataLocal["supervisor_aprobador"] = $aprobador;
            $dataLocal["status_kch"] = $statuskcc;

            $comentarios = preg_replace('[\n|\r|\n\r]', ' ', $comentarios); //str_replace("\n", ' ', $comentarios);
            $dataLocal["comentario_tecnico"] = $comentarios;
            
            $codigoKCH = preg_replace('[\n|\r|\n\r]', ' ', $codigoKCH);
            $dataLocal["codigokch"] = $codigoKCH;
            $dataArray[] = $dataLocal;

            $linea = count($dataArray) + 1;

            $time = strtotime($fecha->format('d-m-Y h:i A'));
            $time_termino = strtotime($fecha_termino->format('d-m-Y h:i A'));

            if ($intervencion["Planificacion"]["tipointervencion"] != 'MP' && $intervencion["Planificacion"]["tipointervencion"] != 'RP') {
                $this->generar_delta_1($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_delta_2($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] != 'MP') {
                $this->generar_delta_3($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] != 'MP') {
                $this->generar_get_elementos($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] != 'EX' && $intervencion["Planificacion"]["tipointervencion"] != 'MP') {
                $this->generar_delta_4($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] == 'MP' && $intervencion["Planificacion"]["tipomantencion"] != '1500') {
                $this->generar_delta_mp($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] != 'EX') {
                $this->generar_espera_desconexion($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_registro_desconexion($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_espera_conexion($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_registro_conexion($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_espera_puesta_marcha($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_registro_puesta_marcha($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_registro_prueba_potencia($time, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] != 'EX' && $intervencion["Planificacion"]["tipointervencion"] != 'MP') {
                $this->generar_delta_5($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
                $this->generar_get_elementos_reproceso($time, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            if ($intervencion["Planificacion"]["tipointervencion"] == 'EX' && !isset($json["Elemento_1"])) {
                //$tiempo_trabajoex = $this->get_tiempo_trabajo(date('Y-m-d H:i', $time), date('Y-m-d H:i', $time_termino));
                $tiempo_trabajo = ($time_termino - $time) / 3600;
                $dataLocal["actividad"] = "EX";
                $dataLocal["responsable"] = "OEM";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
                $dataLocal["hora_termino"] = date('H:i', $time_termino);
                $dataLocal["tiempo"] = $tiempo_trabajo;
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Inicio Interv-Término Interv.";
                $dataLocal["pos_subsistema"] = "";
                $dataLocal["elemento"] = "Intervención";
                $dataArray[] = $dataLocal;
            }

            if ($intervencion["Planificacion"]["tipointervencion"] == "OP" && substr($intervencion["Planificacion"]["folio"], 0, 2) == "EX" && !isset($json["Elemento_1"])) {
                //$tiempo_trabajoop = $this->get_tiempo_trabajo(date('Y-m-d H:i', $time), date('Y-m-d H:i', $time_termino));
                $tiempo_trabajo = ($time_termino - $time) / 3600;
                $dataLocal["actividad"] = "OP";
                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
                $dataLocal["hora_termino"] = date('H:i', $time_termino);
                $dataLocal["tiempo"] = $tiempo_trabajo;
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Inicio Interv-Término Interv.";
                $dataLocal["pos_subsistema"] = "";
                $dataLocal["elemento"] = "Intervención";
                $dataArray[] = $dataLocal;
            }

            if ($intervencion["Planificacion"]["tipointervencion"] == "RI" && substr($intervencion["Planificacion"]["folio"], 0, 2) == "EX" && !isset($json["Elemento_1"])) {
                //$tiempo_trabajori = $this->get_tiempo_trabajo(date('Y-m-d H:i', $time), date('Y-m-d H:i', $time_termino));
                $tiempo_trabajo = ($time_termino - $time) / 3600;
                $dataLocal["actividad"] = "RI";
                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
                $dataLocal["hora_termino"] = date('H:i', $time_termino);
                $dataLocal["tiempo"] = $tiempo_trabajo;
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Inicio Interv-Término Interv.";
                $dataLocal["pos_subsistema"] = "";
                $dataLocal["elemento"] = "Intervención";
                $dataArray[] = $dataLocal;
            }

            $this->generar_registro_fluidos($time_termino, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["id"]);

            if (isset($intervencion["Planificacion"]['fecha_operacion'])) {
                $this->generar_delta_operacion($time_termino, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);
            }

            $this->generar_delta_tiempo_adicional_bitacora($time_termino, $json, $dataLocal, $dataArray, $intervencion["Planificacion"]["folio"]);


            foreach ($intervencion["Hijos"] as $intervencion_hijo) {
                $count_intervenciones++;
                $original = $dataLocal;
                
                $json = $intervencion_hijo["Planificacion"]["json"];
                $tecnico = array();
                $tecnico["tecnico_1"] = is_numeric(@$json["UserID"]) ? $this->get_tecnico_rut($json["UserID"]) : "";
                $tecnico["tecnico_2"] = is_numeric(@$json["TecnicoApoyo02"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo02"]) : "";
                $tecnico["tecnico_3"] = is_numeric(@$json["TecnicoApoyo03"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo03"]) : "";
                $tecnico["tecnico_4"] = is_numeric(@$json["TecnicoApoyo04"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo04"]) : "";
                $tecnico["tecnico_5"] = is_numeric(@$json["TecnicoApoyo05"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo05"]) : "";
                $tecnico["tecnico_6"] = is_numeric(@$json["TecnicoApoyo06"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo06"]) : "";
                $tecnico["tecnico_7"] = is_numeric(@$json["TecnicoApoyo07"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo07"]) : "";
                $tecnico["tecnico_8"] = is_numeric(@$json["TecnicoApoyo08"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo08"]) : "";
                $tecnico["tecnico_9"] = is_numeric(@$json["TecnicoApoyo09"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo09"]) : "";
                $tecnico["tecnico_10"] = is_numeric(@$json["TecnicoApoyo10"]) ? $this->get_tecnico_rut(@$json["TecnicoApoyo10"]) : "";
                $aprobador = $this->get_tecnico_rut(@$intervencion_hijo["Planificacion"]['aprobador_id']);
                $supervisor = $this->get_tecnico_rut(@$intervencion_hijo["Planificacion"]['supervisor_responsable']);
                $turno = $intervencion["Turno"]["nombre"];
                $periodo = $intervencion["Periodo"]["nombre"];

                $comentarios = "";
                if (@$json["Comentarios"] != "") {
                    $comentarios = @$json["Comentarios"];
                } elseif (@$json["Comentario"] != "") {
                    $comentarios = @$json["Comentario"];
                }

                /* COMENTARIO KCH */
                $codigoKCH = "";
                if (@$json["codigoKCH"] != "") {
                    $codigoKCH = @$json["codigoKCH"];
                } else {
                    $int_comentarios = $this->IntervencionComentarios->find('first', array('fields' => array('IntervencionComentarios.*'),
                        'conditions' => array('IntervencionComentarios.folio' => $intervencion["Planificacion"]["folio"]),
                        'recursive' => -1));
                    if(count($int_comentarios)>0){
                        $codigoKCH = $int_comentarios['IntervencionComentarios']['codigo_kch'];
                    }else{
                        $codigoKCH = '';
                    }
                }

                $statuskcc = $util->getEstado($intervencion_hijo["Planificacion"]["estado"]);
                $dataLocal = array();
                $dataLocal["estado"] = $statuskcc;
                $dataLocal["faena_id"] = $original["faena_id"];
                $dataLocal["flota_id"] = $original["flota_id"];
                $dataLocal["unidad_id"] = $original["unidad_id"];
                $dataLocal["correlativo"] = $intervencion_hijo["Planificacion"]["correlativo_final"];
                $dataLocal["folio"] = $intervencion_hijo["Planificacion"]["id"];
                $dataLocal["faena"] = $original["faena"];
                $dataLocal["flota"] = $original["flota"];
                $dataLocal["unidad"] = $original["unidad"];
                $dataLocal["esn"] = $original["esn"];
                $dataLocal["horometro_cabina"] = $original["horometro_cabina"];
                $dataLocal["horometro_motor"] = $original["horometro_motor"];
                $dataLocal["tipo"] = $original["tipo"];
                $dataLocal["actividad"] = "C" . $intervencion_hijo["Planificacion"]["tipointervencion"];
                $dataLocal["responsable"] = ""; // 10
                $dataLocal["categoria"] = "Continuación";
                
                $fechaH = new DateTime($intervencion_hijo['Planificacion']['fecha'] . ' ' . $intervencion_hijo['Planificacion']['hora']);
                $fecha_terminoH = new DateTime($intervencion_hijo['Planificacion']['fecha_termino'] . ' ' . $intervencion_hijo['Planificacion']['hora_termino']);
                
                $fH_i = ($intervencion_hijo['Planificacion']['fecha'] . ' ' . $intervencion_hijo['Planificacion']['hora']);
                $fH_t = ($intervencion_hijo['Planificacion']['fecha_termino'] . ' ' . $intervencion_hijo['Planificacion']['hora_termino']);
                
                $fecha_termino_padre = $this->get_fecha_termino_intervencion_padre($intervencion_hijo, $intervencion["Hijos"]);
                $tie_trabajo = $this->get_tiempo_trabajo($fH_i, $fH_t);
                
                $dataLocal["fecha_inicio"] = $fechaH->format('Y-m-d');
                $dataLocal["hora_inicio"] = $fechaH->format('H:i');
                $dataLocal["fecha_termino"] = $fecha_terminoH->format('Y-m-d');
                $dataLocal["hora_termino"] = $fecha_terminoH->format('H:i');
                
                $dataLocal["tiempo"] = $tie_trabajo; //($th_t - $th_i) / 3600; //$tiempo_trabajo; //
                //echo $fechaH->format('Y-m-d H:i:s A'). '-'.$fecha_terminoH->format('Y-m-d H:i:s A'). ' --- '. $intervencion_hijo["Planificacion"]["id"]. '=>' . $tie_trabajo . ' -- '.$th_trabajo;
                
                $dataLocal["motivo_llamado"] = $original["motivo_llamado"];
                $dataLocal["categoria_sintoma"] = $original["categoria_sintoma"];
                $dataLocal["sintoma"] = $original["sintoma"];
                $dataLocal["sistema"] = ""; // 20
                $dataLocal["subsistema"] = "";
                $dataLocal["pos_subsistema"] = "";
                $dataLocal["id_code"] = "";
                $dataLocal["elemento"] = "";
                $dataLocal["pos_elemento"] = "";
                $dataLocal["diagnostico"] = "";
                $dataLocal["solucion"] = "";
                $dataLocal["causa"] = "";

                /*** CAMBIO MODULO **/
                if(strtoupper($intervencion_hijo["Planificacion"]['tipointervencion']) == 'MP'){
                    if($original["sintoma"] == 'Overhaul'){
                        $cambio_modulo = "SI";   
                    }else{
                        $cambio_modulo = "NO";   
                    }
                }else{
                    $cambio_modulo = isset($json["CambioModulo"]) ? (@$json["CambioModulo"] == "S" ? "SI" : "NO") : "NO";   /* $this->existe_cambio_modulo($intervencion["Planificacion"]["correlativo_final"]) == true ? "SI" : "NO"; */
                }

                $dataLocal["cambio_modulo"] = $cambio_modulo;
                $dataLocal["evento_finalizado"] = $original["evento_finalizado"]; // 30;
                $dataLocal["cambio_modulo_bitacora"] = isset($json["CambioModulo"]) ? (@$json["CambioModulo"] == "S" ? "SI" : "NO") : "NO"; /* $this -> existe_cambio_modulo_bitacora($intervencion_hijo["Planificacion"]["id"]) == "1" ? "SI" : "NO"; */
                $dataLocal["intervencion_terminada_bitacora"] = $this->intervencion_terminada($intervencion_hijo["Planificacion"]["id"]) == "1" ? "SI" : "NO";
                $dataLocal["cambio_modulo_evento"] = $cambio_modulo;
                $dataLocal["intervencion_terminada_evento"] = $original["intervencion_terminada_evento"];
                $dataLocal["numero_os_sap"] = $intervencion_hijo["Planificacion"]["os_sap"];
                $dataLocal["lugar_reparacion"] = $original["lugar_reparacion"];
                foreach ($tecnico as $k => $v) {
                    $dataLocal[$k] = $v;
                }

                $dataLocal["supervisor_responsable"] = $supervisor;
                $dataLocal["turno"] = $turno;
                $dataLocal["periodo"] = $periodo;
                $dataLocal["supervisor_aprobador"] = $aprobador;
                $dataLocal["status_kch"] = $statuskcc;
                $comentarios = preg_replace('[\n|\r|\n\r]', ' ', $comentarios);
                $dataLocal["comentario_tecnico"] = $comentarios;

                $codigoKCH = preg_replace('[\n|\r|\n\r]', ' ', $codigoKCH);
                $dataLocal["codigokch"] = $codigoKCH;

                if (isset($fecha_termino_padre)) {
                    $time_anterior = strtotime((new DateTime($fecha_termino_padre))->format('d-m-Y h:i A'));
                } else {
                    $time_anterior = strtotime($fechaH->format('d-m-Y h:i A'));
                }

                $this->generar_delta_continuacion($time_anterior, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                $dataArray[] = $dataLocal;

                $linea = count($dataArray) + 1;

                $time = strtotime($fechaH->format('d-m-Y h:i A'));
                $time_termino = strtotime($fecha_terminoH->format('d-m-Y h:i A'));

                if ($intervencion["Planificacion"]["tipointervencion"] != 'MP' && $intervencion["Planificacion"]["tipointervencion"] != 'RP') {
                    $this->generar_delta_1($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_delta_2($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                if ($intervencion_hijo["Planificacion"]["tipointervencion"] != 'MP') {
                    $this->generar_delta_3($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                if ($intervencion_hijo["Planificacion"]["tipointervencion"] != 'MP') {
                    $this->generar_get_elementos($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                if ($intervencion_hijo["Planificacion"]["tipointervencion"] != 'EX' && $intervencion_hijo["Planificacion"]["tipointervencion"] != 'MP') {
                    $this->generar_delta_4($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                if ($intervencion_hijo["Planificacion"]["tipointervencion"] == 'MP' && $intervencion_hijo["Planificacion"]["tipomantencion"] != '1500') {
                    $this->generar_delta_mp($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }
                if ($intervencion_hijo["Planificacion"]["tipointervencion"] != 'EX') {
                    $this->generar_espera_desconexion($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_registro_desconexion($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_espera_conexion($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_registro_conexion($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_espera_puesta_marcha($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_registro_puesta_marcha($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_registro_prueba_potencia($time, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                if ($intervencion_hijo["Planificacion"]["tipointervencion"] != 'EX' && $intervencion_hijo["Planificacion"]["tipointervencion"] != 'MP') {
                    $this->generar_delta_5($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                    $this->generar_get_elementos_reproceso($time, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }
                $this->generar_registro_fluidos($time_termino, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["id"]);
                if (isset($intervencion_hijo["Planificacion"]['fecha_operacion'])) {
                    $this->generar_delta_operacion($time_termino, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
                }

                $this->generar_delta_tiempo_adicional_bitacora($time_termino, $json, $dataLocal, $dataArray, $intervencion_hijo["Planificacion"]["folio"]);
            }
        }

        echo json_encode($dataArray);
    }
    
    public function get_tiempo_trabajo($fecha_inicio, $fecha_termino){
        $f_inicio = strtotime($fecha_inicio);
        $f_termino = strtotime($fecha_termino);
        $t_trabajo = (($f_termino - $f_inicio) / 3600);

        return $t_trabajo;
        
        /*$f_inicio = intval(strtotime($fecha_inicio));
        $f_termino = intval(strtotime($fecha_termino));
        
        $t = intval($f_termino - $f_inicio) ;
        $t_trabajo = ($t / 3600);
        $f_termino_calc = ($f_inicio + $t);

        if($f_termino_calc != $f_termino){
            $t2 = intval($f_termino - $f_termino_calc);
            $tiempo = ($t + $t2) / 3600; 
            return $tiempo;
        }else{
            return $t_trabajo;
        }
        
        $f_inicio = strtotime($fecha_inicio);
        $f_termino = strtotime($fecha_termino);
        $t_trabajo = (($f_termino - $f_inicio) / 3600);
        $t_min = ($t_trabajo * 60);
        $f_termino_calc = strtotime('+'.$t_min.' minute', $f_inicio);
        
        $HST = new DateTimeZone('America/Santiago');
        $t_i = new DateTime($fecha_inicio, $HST);
        $t_t = new DateTime($fecha_termino, $HST);
        $diff = $t_i->diff($t_t);
        $tiempo_trabajoDT = ($diff->h + ($diff->days*24) + ($diff->i / 60));
        if($t_trabajo != $tiempo_trabajoDT){
            //echo 'DIFERENNCIA! DT= '.$tiempo_trabajoDT.' - STT= '.$t_trabajo. ' ';
        }
        if($f_termino_calc != $f_termino){
            $_dif = (($f_termino - $f_termino_calc) / 3600);
            $t_min2 = ($_dif * 60);
            $tiempo = abs(($t_min2 + $t_min) / 60);
            //echo 'fi = '. date('Y-m-d H:i:s', $f_inicio) .'- f2 ='. date('Y-m-d H:i:s', $f_termino)  .'( v1 = '.$t_trabajo. ' - V2 => '.$tiempo.')';
            return $tiempo;
        }else{
            return $t_trabajo;
        }*/
    }
        
    public function get_fecha_termino($tiempo, $fecha_inicio){
        //echo date('Y-m-d H:i:s', $fecha_inicio). " - ";
        /*$tmpo = intval($tiempo);
        $t_trabajo = ($tmpo / 60);
        //$f_termino = strtotime('+'.($tmpo).' minute', $fecha_inicio); 
        $f_termino = ($fecha_inicio + $tmpo) * 60; 
        
        $diff = $f_termino - $fecha_inicio;
        $hours = ($diff / ( 60 * 60 ));

        if($hours != $t_trabajo){
            $hora = ($t_trabajo - $hours) * 60;
            $f_termino = strtotime($hora.' minute', $f_termino); 
        } */
        $tmpo = intval($tiempo);
        $t_trabajo = ($tmpo / 60);
        $f_termino = strtotime('+'.($tmpo).' minute', $fecha_inicio); 
        return $f_termino;
    }
    
    private function get_hijo_v2($id = null, &$hijos) {
        if ($id == null || $id == '') {
            return;
        }
        $conditions["Planificacion.id <>"] = $id;
        $conditions["Planificacion.correlativo_final"] = $id;
        $conditions["Planificacion.estado"] = array(4, 5, 6);

        $intervenciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.json', 'Planificacion.id', 'Planificacion.fecha', 'Planificacion.hora', 'Planificacion.fecha_termino', 'Planificacion.hora_termino', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion', 'Planificacion.faena_id', 'Planificacion.flota_id', 'Planificacion.unidad_id', 'Planificacion.esn', 'Planificacion.correlativo_final', 'Planificacion.folio', 'Planificacion.sintoma_id', 'Planificacion.backlog_id', 'Planificacion.estado', 'Planificacion.aprobador_id', 'Planificacion.supervisor_responsable', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Turno.nombre', 'Periodo.nombre', 'LugarReparacion.nombre', 'MotivoLlamado.nombre', 'Sintoma.nombre', 'SintomaCategoria.nombre', 'Planificacion.padre', 'Planificacion.os_sap', 'Planificacion.fecha_operacion'),
            'order' => array('Planificacion.fecha' => 'ASC', 'Planificacion.hora' => 'ASC'),
            'conditions' => $conditions,
            'recursive' => 1));
        
        
        foreach($intervenciones as $intervencion){
            if (isset($intervencion["Planificacion"])) {
                $fecha_inicio = ($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
                $fecha_termino = ($intervencion['Planificacion']['fecha_termino'] . ' ' . $intervencion['Planificacion']['hora_termino']);

                $intervencion["Planificacion"]["tiempo_trabajo"] = $this->get_tiempo_trabajo($fecha_inicio, $fecha_termino);
                $intervencion["Planificacion"]["json"] = json_decode($intervencion["Planificacion"]["json"], true);
                $hijos[] = $intervencion;
                //$this->get_hijo($intervencion["Planificacion"]["folio"], $hijos);
            }
        }
    }
    
    private function get_hijo_v2_manual($id = null, &$hijos ,$fecha_ini, $fecha_fin) {
        if ($id == null || $id == '') {
            return;
        }
        $conditions = array();
        
        $conditions["Planificacion.id <>"] = $id;
        $conditions["Planificacion.correlativo_final"] = $id;
        $conditions["Planificacion.estado"] = array(4, 5, 6, 7);
        //$conditions["CONCAT(Planificacion.fecha,'T',Planificacion.hora) BETWEEN ? AND ? "] = array($fecha_ini, $fecha_fin);
        $conditions["CONCAT(Planificacion.fecha,'T',Planificacion.hora) <= ?"] = array($fecha_fin);
        
        $intervenciones_hijo = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.json', 'Planificacion.id', 'Planificacion.fecha', 'Planificacion.hora', 'Planificacion.fecha_termino', 'Planificacion.hora_termino', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion', 'Planificacion.faena_id', 'Planificacion.flota_id', 'Planificacion.unidad_id', 'Planificacion.esn', 'Planificacion.correlativo_final', 'Planificacion.folio', 'Planificacion.sintoma_id', 'Planificacion.backlog_id', 'Planificacion.estado', 'Planificacion.aprobador_id', 'Planificacion.supervisor_responsable', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Turno.nombre', 'Periodo.nombre', 'LugarReparacion.nombre', 'MotivoLlamado.nombre', 'Sintoma.nombre', 'SintomaCategoria.nombre', 'Planificacion.padre', 'Planificacion.os_sap', 'Planificacion.fecha_operacion'),
            'order' => array('Planificacion.fecha' => 'ASC', 'Planificacion.hora' => 'ASC'),
            'conditions' => $conditions,
            'recursive' => 1));
        
        foreach($intervenciones_hijo as $intervencion){
            if (isset($intervencion["Planificacion"])) {
                $fecha_inicio = ($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
                $fecha_termino = ($intervencion['Planificacion']['fecha_termino'] . ' ' . $intervencion['Planificacion']['hora_termino']);

                $intervencion["Planificacion"]["tiempo_trabajo"] = $this->get_tiempo_trabajo($fecha_inicio, $fecha_termino);
                $intervencion["Planificacion"]["json"] = json_decode($intervencion["Planificacion"]["json"], true);
                $hijos[] = $intervencion;
                //$this->get_hijo($intervencion["Planificacion"]["folio"], $hijos);
                
            }
        }
    }
    
    private function get_tecnico_rut($usuario_id) {
        if (is_numeric($usuario_id)) {
            $resultado = $this->Usuario->find('first', array(
                'fields' => array('Usuario.usuario', 'Usuario.id'),
                'conditions' => array('Usuario.id' => $usuario_id),
                'recursive' => -1
            ));

            return @$resultado["Usuario"]["usuario"];
        }
        return '';
    }

    private function generar_delta_1(&$time, $original, &$dataArray, $folio) {
        $deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo', 'DeltaDetalle.observacion', 'DeltaItem.nombre', 'DeltaResponsable.nombre'),
            'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '1'),
            'recursive' => 1));
        foreach ($deltas as $delta) {
            if (is_numeric($delta["DeltaDetalle"]["tiempo"]) && intval($delta["DeltaDetalle"]["tiempo"]) > 0) {
                $tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
                $tiempo_trabajod1 = $tiempo / 60;
                
                $time_terminod1 = $this->get_fecha_termino($tiempo, $time);
               
                $dataLocal = $original;
                $dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
                $dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
                $dataLocal["categoria"] = "Tiempo";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_terminod1);
                $dataLocal["hora_termino"] = date('H:i', $time_terminod1);
                $dataLocal["tiempo"] = $tiempo_trabajod1;
                $dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Llamado-Lugar Físico";
                $dataLocal["pos_subsistema"] = "Delta1";
                $dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];
                $dataArray[] = $dataLocal;
                $time = $time_terminod1;
            }
        }
    }

    private function generar_delta_2(&$time, $original, &$dataArray, $folio) {
        $deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo', 'DeltaDetalle.observacion', 'DeltaItem.nombre', 'DeltaResponsable.nombre'),
            'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '2'),
            'recursive' => 1));
        foreach ($deltas as $delta) {
            if (is_numeric($delta["DeltaDetalle"]["tiempo"]) && intval($delta["DeltaDetalle"]["tiempo"]) > 0) {
                $tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
                $tiempo_trabajod2 = $tiempo / 60;
                //$time_termino = $time + $tiempo * 60;
                $time_terminod2 = $this->get_fecha_termino($tiempo, $time);
                
                $dataLocal = $original;

                $dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
                $dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
                $dataLocal["categoria"] = "Tiempo";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_terminod2);
                $dataLocal["hora_termino"] = date('H:i', $time_terminod2);
                $dataLocal["tiempo"] = $tiempo_trabajod2;
                $dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Lugar Físico-Inicio Interv.";
                $dataLocal["pos_subsistema"] = "Delta2";
                $dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];

                $dataArray[] = $dataLocal;
                $time = $time_terminod2;
            }
        }
    }

    private function generar_delta_3(&$time, $original, &$dataArray, $folio) {
        $deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo', 'DeltaItem.id', 'DeltaDetalle.observacion', 'DeltaItem.nombre', 'DeltaResponsable.nombre', 'DeltaItem.id'),
            'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '3'),
            'recursive' => 1));
        foreach ($deltas as $delta) {
            if (is_numeric($delta["DeltaDetalle"]["tiempo"]) && $delta["DeltaItem"]["id"] != '16' && intval($delta["DeltaDetalle"]["tiempo"]) > 0) {
                $tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
                $tiempo_trabajod3 = $tiempo / 60;
                //$time_termino = $time + $tiempo * 60;
                $time_terminod3 = $this->get_fecha_termino($tiempo, $time);
                
                $dataLocal = $original;
                $dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
                $dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
                $dataLocal["categoria"] = "Tiempo";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_terminod3);
                $dataLocal["hora_termino"] = date('H:i', $time_terminod3);
                $dataLocal["tiempo"] = $tiempo_trabajod3;
                $dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Inicio Interv-Término Interv.";
                $dataLocal["pos_subsistema"] = "Delta3";
                $dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];
                $dataArray[] = $dataLocal;
                $time = $time_terminod3;
            }
        }
    }

    private function generar_delta_4(&$time, $original, &$dataArray, $folio) {
        $deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo', 'DeltaDetalle.observacion', 'DeltaItem.nombre', 'DeltaResponsable.nombre'),
            'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '4'),
            'recursive' => 1));
        foreach ($deltas as $delta) {
            if (is_numeric($delta["DeltaDetalle"]["tiempo"]) && intval($delta["DeltaDetalle"]["tiempo"]) > 0) {
                $tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
                $tiempo_trabajod4 = $tiempo / 60;
                //$time_termino = $time + $tiempo * 60;
                $time_terminod4 = $this->get_fecha_termino($tiempo, $time);
                $dataLocal = $original;

                $dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
                $dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
                $dataLocal["categoria"] = "Tiempo";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_terminod4);
                $dataLocal["hora_termino"] = date('H:i', $time_terminod4);
                $dataLocal["tiempo"] = $tiempo_trabajod4;
                $dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Término Interv-Inicio P.P.";
                $dataLocal["pos_subsistema"] = "Delta4";
                $dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];

                $dataArray[] = $dataLocal;
                $time = $time_terminod4;
            }
        }
    }

    private function generar_delta_5(&$time, $original, &$dataArray, $folio) {
        $deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo', 'DeltaDetalle.observacion', 'DeltaItem.nombre', 'DeltaResponsable.nombre', 'DeltaItem.id'),
            'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '5'),
            'recursive' => 1));
        foreach ($deltas as $delta) {
            if (is_numeric($delta["DeltaDetalle"]["tiempo"]) && $delta["DeltaItem"]["id"] != '32' && intval($delta["DeltaDetalle"]["tiempo"]) > 0) {
                $tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
                $tiempo_trabajod5 = $tiempo / 60;
                //$time_termino = $time + $tiempo * 60;
                $time_terminod5 = $this->get_fecha_termino($tiempo, $time);
                $dataLocal = $original;

                $dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
                $dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
                $dataLocal["categoria"] = "Tiempo";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_terminod5);
                $dataLocal["hora_termino"] = date('H:i', $time_terminod5);
                $dataLocal["tiempo"] = $tiempo_trabajod5;
                $dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Reproceso";
                $dataLocal["pos_subsistema"] = "Delta5";
                $dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];

                $dataArray[] = $dataLocal;
                $time = $time_terminod5;
            }
        }
    }

    private function generar_delta_mp(&$time, $original, &$dataArray, $folio) {
        $deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo', 'DeltaDetalle.observacion', 'DeltaItem.nombre', 'DeltaResponsable.nombre', 'DeltaItem.id'),
            'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '6'),
            'recursive' => 1));
        foreach ($deltas as $delta) {
            if (is_numeric($delta["DeltaDetalle"]["tiempo"]) && intval($delta["DeltaDetalle"]["tiempo"]) > 0) {
                $tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
                $tiempo_trabajomp = $tiempo / 60;
                //$time_termino = $time + $tiempo * 60;
                $time_terminomp = $this->get_fecha_termino($tiempo, $time);
                $dataLocal = $original;

                if ($delta["DeltaItem"]["id"] != '43') {
                    $dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
                } else {
                    $dataLocal["actividad"] = "MP";
                }
                $dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
                if ($delta["DeltaItem"]["id"] != '43') {
                    $dataLocal["categoria"] = "Tiempo";
                } else {
                    $dataLocal["categoria"] = "Intervención";
                }

                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_terminomp);
                $dataLocal["hora_termino"] = date('H:i', $time_terminomp);
                $dataLocal["tiempo"] = $tiempo_trabajomp;
                $dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Inicio Mantención-Término Mantención";
                $dataLocal["pos_subsistema"] = "Delta3";
                $dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];

                $dataArray[] = $dataLocal;
                $time = $time_terminomp;
            }
        }
    }

    private function generar_espera_desconexion(&$time, $json, $original, &$dataArray, $folio) {
        $fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.termino_intervencion	', 'IntervencionFechas.inicio_desconexion'),
            'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_intervencion IS NOT NULL', 'IntervencionFechas.termino_intervencion IS NOT NULL', 'IntervencionFechas.inicio_desconexion IS NOT NULL'),
            'recursive' => -1));
        if (isset($fechas["IntervencionFechas"])) {
            $fecha = strtotime($fechas["IntervencionFechas"]["termino_intervencion"]);
            $fecha_termino = strtotime($fechas["IntervencionFechas"]["inicio_desconexion"]);
            
            if ($fecha_termino > $fecha) {
                $t_i = ($fechas["IntervencionFechas"]["termino_intervencion"]);
                $t_t = ($fechas["IntervencionFechas"]["inicio_desconexion"]);
                $duracion = $this->get_tiempo_trabajo($t_i, $t_t);
                                
                $dataLocal = $original;
                $dataLocal["actividad"] = "T_DCC";
                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Tiempo";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
                $dataLocal["hora_inicio"] = date('H:i', $fecha);
                $dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
                $dataLocal["hora_termino"] = date('H:i', $fecha_termino);
                $dataLocal["tiempo"] = $duracion;
                $dataLocal["sistema"] = "00_Motor completo";
                $dataLocal["subsistema"] = "Cambio de Módulo";
                $dataLocal["elemento"] = "Espera Desconexión";
                $dataArray[] = $dataLocal;
                $time = $fecha_termino;
            }
        }
    }

    private function generar_registro_desconexion(&$time, $json, $original, &$dataArray, $folio) {
        $fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_desconexion', 'IntervencionFechas.termino_desconexion'),
            'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_desconexion IS NOT NULL', 'IntervencionFechas.termino_desconexion IS NOT NULL'),
            'recursive' => -1));
        if (isset($fechas["IntervencionFechas"])) {
            $fecha = strtotime($fechas["IntervencionFechas"]["inicio_desconexion"]);
            $fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_desconexion"]);
            if ($fecha_termino > $fecha) {
                $t_i = ($fechas["IntervencionFechas"]["inicio_desconexion"]);
                $t_t = ($fechas["IntervencionFechas"]["termino_desconexion"]);
                $duracion = $this->get_tiempo_trabajo($t_i, $t_t);
                $dataLocal = $original;

                $dataLocal["actividad"] = "T_DCC";
                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
                $dataLocal["hora_inicio"] = date('H:i', $fecha);
                $dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
                $dataLocal["hora_termino"] = date('H:i', $fecha_termino);
                $dataLocal["tiempo"] = $duracion;
                $dataLocal["sistema"] = "00_Motor completo";
                $dataLocal["subsistema"] = "Cambio de Módulo";
                $dataLocal["elemento"] = "Desconexión";

                $dataArray[] = $dataLocal;
                $time = $fecha_termino;
            }
        }
    }

    private function generar_espera_conexion(&$time, $json, $original, &$dataArray, $folio) {
        $fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.termino_desconexion', 'IntervencionFechas.inicio_conexion'),
            'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_desconexion IS NOT NULL', 'IntervencionFechas.termino_desconexion IS NOT NULL', 'IntervencionFechas.inicio_conexion IS NOT NULL'),
            'recursive' => -1));
        if (isset($fechas["IntervencionFechas"])) {
            $fecha = strtotime($fechas["IntervencionFechas"]["termino_desconexion"]);
            $fecha_termino = strtotime($fechas["IntervencionFechas"]["inicio_conexion"]);
            if ($fecha_termino > $fecha) {
                $t_i = ($fechas["IntervencionFechas"]["termino_desconexion"]);
                $t_t = ($fechas["IntervencionFechas"]["inicio_conexion"]);
                $duracion = $this->get_tiempo_trabajo($t_i, $t_t);
                $dataLocal = $original;

                $dataLocal["actividad"] = "T_DCC";
                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Tiempo";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
                $dataLocal["hora_inicio"] = date('H:i', $fecha);
                $dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
                $dataLocal["hora_termino"] = date('H:i', $fecha_termino);
                $dataLocal["tiempo"] = $duracion;
                $dataLocal["sistema"] = "00_Motor completo";
                $dataLocal["subsistema"] = "Cambio de Módulo";
                $dataLocal["elemento"] = "Espera Conexión";

                $dataArray[] = $dataLocal;
                $time = $fecha_termino;
            }
        }
    }

    private function generar_registro_conexion(&$time, $json, $original, &$dataArray, $folio) {
        $fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_conexion', 'IntervencionFechas.termino_conexion'),
            'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_conexion IS NOT NULL', 'IntervencionFechas.termino_conexion IS NOT NULL'),
            'recursive' => -1));
        if (isset($fechas["IntervencionFechas"])) {
            $fecha = strtotime($fechas["IntervencionFechas"]["inicio_conexion"]);
            $fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_conexion"]);
            if ($fecha_termino > $fecha) {
                $t_i = ($fechas["IntervencionFechas"]["inicio_conexion"]);
                $t_t = ($fechas["IntervencionFechas"]["termino_conexion"]);
                $duracion = $this->get_tiempo_trabajo($t_i, $t_t);
                $dataLocal = $original;

                $dataLocal["actividad"] = "T_DCC";
                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
                $dataLocal["hora_inicio"] = date('H:i', $fecha);
                $dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
                $dataLocal["hora_termino"] = date('H:i', $fecha_termino);
                $dataLocal["tiempo"] = $duracion;
                $dataLocal["sistema"] = "00_Motor completo";
                $dataLocal["subsistema"] = "Cambio de Módulo";
                $dataLocal["elemento"] = "Conexión";

                $dataArray[] = $dataLocal;
                $time = $fecha_termino;
            }
        }
    }

    private function generar_espera_puesta_marcha(&$time, $json, $original, &$dataArray, $folio) {
        $fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_puesta_marcha', 'IntervencionFechas.termino_conexion'),
            'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_puesta_marcha IS NOT NULL', 'IntervencionFechas.termino_conexion IS NOT NULL', 'IntervencionFechas.inicio_conexion IS NOT NULL'),
            'recursive' => -1));
        if (isset($fechas["IntervencionFechas"])) {
            $fecha = strtotime($fechas["IntervencionFechas"]["termino_conexion"]);
            $fecha_termino = strtotime($fechas["IntervencionFechas"]["inicio_puesta_marcha"]);
            if ($fecha_termino > $fecha) {
                $t_i = ($fechas["IntervencionFechas"]["termino_conexion"]);
                $t_t = ($fechas["IntervencionFechas"]["inicio_puesta_marcha"]);
                $duracion = $this->get_tiempo_trabajo($t_i, $t_t);
                $dataLocal = $original;

                $dataLocal["actividad"] = "T_DCC";
                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Tiempo";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
                $dataLocal["hora_inicio"] = date('H:i', $fecha);
                $dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
                $dataLocal["hora_termino"] = date('H:i', $fecha_termino);
                $dataLocal["tiempo"] = $duracion;
                $dataLocal["sistema"] = "00_Motor completo";
                $dataLocal["subsistema"] = "Puesta en Marcha";
                $dataLocal["elemento"] = "Espera Puesta en Marcha";

                $dataArray[] = $dataLocal;
                $time = $fecha_termino;
            }
        }
    }

    private function generar_registro_puesta_marcha(&$time, $json, $original, &$dataArray, $folio) {
        $fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_puesta_marcha', 'IntervencionFechas.termino_puesta_marcha'),
            'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_puesta_marcha IS NOT NULL', 'IntervencionFechas.termino_puesta_marcha IS NOT NULL'),
            'recursive' => -1));
        if (isset($fechas["IntervencionFechas"])) {
            $fecha = strtotime($fechas["IntervencionFechas"]["inicio_puesta_marcha"]);
            $fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_puesta_marcha"]);
            if ($fecha_termino > $fecha) {
                $t_i = ($fechas["IntervencionFechas"]["inicio_puesta_marcha"]);
                $t_t = ($fechas["IntervencionFechas"]["termino_puesta_marcha"]);
                $duracion = $this->get_tiempo_trabajo($t_i, $t_t);
                $dataLocal = $original;

                $dataLocal["actividad"] = "T_DCC";
                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
                $dataLocal["hora_inicio"] = date('H:i', $fecha);
                $dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
                $dataLocal["hora_termino"] = date('H:i', $fecha_termino);
                $dataLocal["tiempo"] = $duracion;
                $dataLocal["sistema"] = "00_Motor completo";
                $dataLocal["subsistema"] = "Puesta en Marcha";
                $dataLocal["elemento"] = "Puesta en Marcha";

                $dataArray[] = $dataLocal;
                $time = $fecha_termino;
            }
        }
    }

    private function generar_registro_prueba_potencia(&$time, $json, $original, &$dataArray, $folio) {
        $fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_prueba_potencia', 'IntervencionFechas.termino_prueba_potencia'),
            'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_prueba_potencia IS NOT NULL', 'IntervencionFechas.termino_prueba_potencia IS NOT NULL'),
            'recursive' => -1));
        if (isset($fechas["IntervencionFechas"])) {
            $fecha = strtotime($fechas["IntervencionFechas"]["inicio_prueba_potencia"]);
            $fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_prueba_potencia"]);
            if ($fecha_termino > $fecha) {
                $t_i = ($fechas["IntervencionFechas"]["inicio_prueba_potencia"]);
                $t_t = ($fechas["IntervencionFechas"]["termino_prueba_potencia"]);
                $duracion = $this->get_tiempo_trabajo($t_i, $t_t);
                $dataLocal = $original;

                $dataLocal["actividad"] = "T_DCC";
                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
                $dataLocal["hora_inicio"] = date('H:i', $fecha);
                $dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
                $dataLocal["hora_termino"] = date('H:i', $fecha_termino);
                $dataLocal["tiempo"] = $duracion;
                $dataLocal["sistema"] = "00_Motor completo";
                $dataLocal["subsistema"] = "Prueba Potencia";
                $dataLocal["elemento"] = "Prueba Potencia";

                $dataArray[] = $dataLocal;
                $time = $fecha_termino;
            }
        }
    }

    private function generar_get_elementos(&$time, $original, &$dataArray, $folio) {
        $elementos = $this->IntervencionElementos->find('all', array('fields' => array('IntervencionElementos.pn_entrante', 'IntervencionElementos.pn_saliente', 'IntervencionElementos.tiempo', 'IntervencionElementos.id_elemento', 'Sistema.nombre', 'Subsistema.nombre', 'Elemento.nombre', 'Diagnostico.nombre', 'Solucion.nombre', 'Posiciones_Elemento.nombre', 'Posiciones_Subsistema.nombre', 'TipoElemento.nombre'),
            'conditions' => array('IntervencionElementos.folio' => $folio, 'tipo_registro' => '0'),
            'recursive' => 1));
        foreach ($elementos as $elemento) {
            if (is_numeric($elemento["IntervencionElementos"]["tiempo"]) && intval($elemento["IntervencionElementos"]["tiempo"]) > 0) {
                $tiempo = intval($elemento["IntervencionElementos"]["tiempo"]);
                $tiempo_trabajoe = $tiempo / 60;
                //$time_termino = $time + $tiempo * 60;
                $time_terminoe = $this->get_fecha_termino($tiempo, $time);
                $dataLocal = $original;

                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_terminoe);
                $dataLocal["hora_termino"] = date('H:i', $time_terminoe);
                $dataLocal["tiempo"] = $tiempo_trabajoe;
                $dataLocal["sistema"] = $elemento["Sistema"]["nombre"];
                $dataLocal["subsistema"] = $elemento["Subsistema"]["nombre"];
                $dataLocal["pos_subsistema"] = $elemento["Posiciones_Subsistema"]["nombre"];
                $dataLocal["id_code"] = $elemento["IntervencionElementos"]["id_elemento"];
                $dataLocal["elemento"] = $elemento["Elemento"]["nombre"];
                $dataLocal["pos_elemento"] = $elemento["Posiciones_Elemento"]["nombre"];
                $dataLocal["diagnostico"] = $elemento["Diagnostico"]["nombre"];
                $dataLocal["solucion"] = $elemento["Solucion"]["nombre"];
                $dataLocal["causa"] = $elemento["TipoElemento"]["nombre"];

                $dataLocal["pn_entrante"] = $elemento["IntervencionElementos"]["pn_entrante"];
                $dataLocal["pn_saliente"] = $elemento["IntervencionElementos"]["pn_saliente"];

                $dataArray[] = $dataLocal;
                $time = $time_terminoe;
            }
        }
    }

    private function generar_registro_fluidos(&$time, $json, $original, &$dataArray, $folio) {
        $ingreso_fluidos = $this->IntervencionFluido->find('all', array('conditions' => array('intervencion_id' => $folio), 'recursive' => 2));
        foreach ($ingreso_fluidos as $fluido) {
            if (floatval($fluido["IntervencionFluido"]["cantidad"]) > 0) {
                $dataLocal = $original;
                $dataLocal["responsable"] = "";
                $dataLocal["categoria"] = "Fluido";
                $dataLocal["tiempo"] = "";
                $dataLocal["sistema"] = "Fluido";
                $dataLocal["subsistema"] = "Fluido";
                $dataLocal["elemento"] = $fluido["Fluido"]["nombre"];
                $dataLocal["pos_elemento"] = $fluido["IntervencionFluido"]["cantidad"];
                $dataLocal["diagnostico"] = $fluido["Fluido"]["FluidoUnidad"]["nombre"];
                $dataLocal["solucion"] = $fluido["TipoIngreso"]["nombre"];
                $dataArray[] = $dataLocal;
            }
        }
    }

    function generar_delta_operacion(&$time, $json, $original, &$dataArray, $folio) {
        print_r("Se entra a generar delta operacion");

        $deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo', 'DeltaDetalle.observacion', 'DeltaItem.nombre', 'DeltaResponsable.nombre', 'DeltaItem.id'),
            'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '8'),
            'recursive' => 1));
        foreach ($deltas as $delta) {
            if (intval($delta["DeltaDetalle"]["tiempo"]) > 0) {

                print_r("Se agrega delta");
                $tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
                $tiempo_trabajodo = $tiempo / 60;
                //$time_termino = $time + $tiempo * 60;
                $time_terminodo = $this->get_fecha_termino($tiempo, $time);
                $dataLocal = $original;

                $dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
                $dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
                $dataLocal["categoria"] = "Final";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_terminodo);
                $dataLocal["hora_termino"] = date('H:i', $time_terminodo);
                $dataLocal["tiempo"] = $tiempo_trabajodo;
                $dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Término Interv.-Inicio Operación";
                $dataLocal["pos_subsistema"] = "DeltaS3";
                $dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];


                $dataArray[] = $dataLocal;
                $time = $time_terminodo;
            }
        }
    }

    function generar_delta_tiempo_adicional_bitacora(&$time, $json, $original, &$dataArray, $folio) {

        $deltas = $this->DeltaDetalle->find('all', array(
            'fields' => array('DeltaDetalle.tiempo', 'DeltaDetalle.observacion', 'DeltaItem.nombre', 'DeltaResponsable.nombre', 'DeltaItem.id'),
            'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '9'),
            'recursive' => 1)
        );
//
        foreach ($deltas as $delta) {
            if (intval($delta["DeltaDetalle"]["tiempo"]) > 0) {
                $tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
                $tiempo_trabajotab = $tiempo / 60;
                //$time_termino = $time + $tiempo * 60;
                $time_terminotab = $this->get_fecha_termino($tiempo, $time);
                $dataLocal = $original;

                $dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
                $dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
                $dataLocal["categoria"] = "Tiempo";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_terminotab);
                $dataLocal["hora_termino"] = date('H:i', $time_terminotab);
                $dataLocal["tiempo"] = $tiempo_trabajotab;
                $dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Detalle tiempo adicional bitácora";
                $dataLocal["pos_subsistema"] = "DeltaS2";
                $dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];

                $dataArray[] = $dataLocal;
                $time = $time_terminotab;
            }
        }
    }

    private function generar_delta_continuacion($time, $json, $original, &$dataArray, $folio) {
        $deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo', 'DeltaDetalle.observacion', 'DeltaItem.nombre', 'DeltaResponsable.nombre', 'DeltaItem.id'),
            'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '7'),
            'recursive' => 1));
        foreach ($deltas as $delta) {
            if (intval($delta["DeltaDetalle"]["tiempo"]) > 0) {
                $tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
                $tiempo_trabajodc = $tiempo / 60;
                //$time_termino = $time + $tiempo * 60;
                $time_terminodc = $this->get_fecha_termino($tiempo, $time);
                $dataLocal = $original;
                $dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
                $dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
                $dataLocal["categoria"] = "Tiempo";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_terminodc);
                $dataLocal["hora_termino"] = date('H:i', $time_terminodc);
                $dataLocal["tiempo"] = $tiempo_trabajodc;
                $dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
                $dataLocal["sistema"] = "Tiempo";
                $dataLocal["subsistema"] = "Término Interv.-Inicio Interv.";
                $dataLocal["pos_subsistema"] = "DeltaS1";
                $dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];

                $dataArray[] = $dataLocal;
                $time = $time_terminodc;
            }
        }
    }

    function generar_get_elementos_reproceso(&$time, $original, &$dataArray, $folio) {
        $elementos = $this->IntervencionElementos->find('all', array('fields' => array('IntervencionElementos.pn_entrante', 'IntervencionElementos.pn_saliente', 'IntervencionElementos.tiempo', 'IntervencionElementos.id_elemento', 'Sistema.nombre', 'Subsistema.nombre', 'Elemento.nombre', 'Diagnostico.nombre', 'Solucion.nombre', 'Posiciones_Elemento.nombre', 'Posiciones_Subsistema.nombre', 'TipoElemento.nombre'),
            'conditions' => array('IntervencionElementos.folio' => $folio, 'tipo_registro' => '1'),
            'recursive' => 1));
        foreach ($elementos as $elemento) {
            if (is_numeric($elemento["IntervencionElementos"]["tiempo"]) && intval($elemento["IntervencionElementos"]["tiempo"]) > 0) {
                $tiempo = intval($elemento["IntervencionElementos"]["tiempo"]);
                $tiempo_trabajoer = $tiempo / 60;
                //$time_termino = $time + $tiempo * 60;
                $time_terminoer = $this->get_fecha_termino($tiempo, $time);
                $dataLocal = $original;

                $dataLocal["responsable"] = "DCC";
                $dataLocal["categoria"] = "Intervención";
                $dataLocal["fecha_inicio"] = date('Y-m-d', $time);
                $dataLocal["hora_inicio"] = date('H:i', $time);
                $dataLocal["fecha_termino"] = date('Y-m-d', $time_terminoer);
                $dataLocal["hora_termino"] = date('H:i', $time_terminoer);
                $dataLocal["tiempo"] = $tiempo_trabajoer;
                $dataLocal["sistema"] = $elemento["Sistema"]["nombre"];
                $dataLocal["subsistema"] = $elemento["Subsistema"]["nombre"];
                $dataLocal["pos_subsistema"] = $elemento["Posiciones_Subsistema"]["nombre"];
                $dataLocal["id_code"] = $elemento["IntervencionElementos"]["id_elemento"];
                $dataLocal["elemento"] = $elemento["Elemento"]["nombre"];
                $dataLocal["pos_elemento"] = $elemento["Posiciones_Elemento"]["nombre"];
                $dataLocal["diagnostico"] = $elemento["Diagnostico"]["nombre"];
                $dataLocal["solucion"] = $elemento["Solucion"]["nombre"];
                $dataLocal["causa"] = $elemento["TipoElemento"]["nombre"];

                $dataLocal["pn_entrante"] = $elemento["IntervencionElementos"]["pn_entrante"];
                $dataLocal["pn_saliente"] = $elemento["IntervencionElementos"]["pn_saliente"];

                $dataArray[] = $dataLocal;
                $time = $time_terminoer;
            }
        }
    }

    private function correlativo_terminado($correlativo) {
        $planificaciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.id', 'Planificacion.padre', 'Planificacion.correlativo_final', 'Planificacion.folio', 'Planificacion.estado'),
            'conditions' => array("Planificacion.correlativo_final" => $correlativo),
            'recursive' => -1
        ));
        $correlativo_terminado = false;

        foreach ($planificaciones as $planificacion) {

            $idPlanificacion = $this->Planificacion->find('first', array('fields' => array('Planificacion.id'), 'conditions' => array('Planificacion.padre' => $planificacion["Planificacion"]["folio"], 'Planificacion.correlativo_final' => $planificacion["Planificacion"]["correlativo_final"]), 'recursive' => -1));

            if ((!isset($idPlanificacion["Planificacion"]) || !isset($idPlanificacion["Planificacion"]["id"])) &&
                    $planificacion["Planificacion"]["estado"] != 2) {
                $correlativo_terminado = true;
            }
        }

        return $correlativo_terminado;
    }

    private function intervencion_terminada($id) {
        $planificaciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.id', 'Planificacion.padre', 'Planificacion.correlativo_final', 'Planificacion.folio', 'Planificacion.estado'),
            'conditions' => array("Planificacion.id" => $id),
            'recursive' => -1
        ));

        $bitacora_terminada = false;


        foreach ($planificaciones as $planificacion) {

            $idPlanificacion = $this->Planificacion->find('first', array('fields' => array('Planificacion.id'), 'conditions' => array('Planificacion.padre' => $planificacion["Planificacion"]["folio"], 'Planificacion.correlativo_final' => $planificacion["Planificacion"]["correlativo_final"]), 'recursive' => -1));

            if ((!isset($idPlanificacion["Planificacion"]) || !isset($idPlanificacion["Planificacion"]["id"])) &&
                    $planificacion["Planificacion"]["estado"] != 2) {

                $bitacora_terminada = true;
                break;
            }
        }

        return $bitacora_terminada;
    }

    private function get_fecha_termino_intervencion_padre($intervencionHijo, $intervenciones) {
        $correlativoPadre = $intervencionHijo["Planificacion"]['padre'];

        $fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.fecha_termino_turno', 'IntervencionFechas.fecha_termino_global'),
            'conditions' => array('IntervencionFechas.folio' => $correlativoPadre),
            'recursive' => -1));
        
        if ($fechas['IntervencionFechas']['fecha_termino_turno'] != ''){
            return $fechas['IntervencionFechas']['fecha_termino_turno'];
        }else{
            return $fechas['IntervencionFechas']['fecha_termino_global'];
        }
        
    }

}
