<?php

App::uses('ConnectionManager', 'Model');

class GestionController extends AppController {

    public function index() {
        $this->check_permissions($this);
        $this->set("titulo", "Dashboard Gestión");
        $this->loadModel('Planificacion');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        //$faena_id =intval($this->Session->read('faena_id'));
        /* if ($faena_id != 0) {
          $faena = "faena_id = $faena_id";
          } else {
          $faena = 'faena_id <> 0';
          } */

        $termino = time();
        $inicio = strtotime(date('Y-m-d H:i:s', $termino) . ' -30 days');
        $fecha_inicio = date("Y-m-d", $inicio);
        $fecha_termino = date("Y-m-d", $termino);
        $this->set("faena_id", "0");
        if (isset($this->request->data) && count($this->request->data) >= 2 && $this->request->data["faena_id"] != "0") {
            $fecha_inicio = $this->request->data["fecha_inicio"];
            $fecha_termino = $this->request->data["fecha_termino"];
            $intervenciones = $this->Planificacion->find('all', array(
                'fields' => array('Unidad.motor_id', 'Planificacion.unidad_id', 'Planificacion.faena_id', 'Planificacion.estado', 'Planificacion.tipointervencion', 'Planificacion.fecha', 'Planificacion.tiempo_trabajo', 'Planificacion.json', 'Planificacion.hora', 'Planificacion.fecha_termino', 'Planificacion.hora_termino'),
                'conditions' => array(
                    "Planificacion.faena_id = {$this->request->data["faena_id"]}",
                    'Planificacion.estado IN (4, 5, 6)',
                    "Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_termino'",
                    "not" => array("Planificacion.fecha" => null)),
                'order' => 'Planificacion.fecha',
                'recursive' => 1));

            // Disponibilidad Fisica
            /*
              DF = (HN - HRDET) / HN
              HN = #MOTORES * 24 * 30 (#MOTORES = #EQUIPOS)
              HRDET = DURACION TODAS LAS INTERVENCIONES
             */
            $unidad = $this->Unidad->find('all', array('fields' => array('id', 'unidad', 'faena_id'), 'conditions' => array("faena_id = {$this->request->data["faena_id"]}"), 'recursive' => -1));

            $HN = count($unidad) * 24; // en Minutos
            $this->set("faena_id", $this->request->data["faena_id"]);
        } else {
            $intervenciones = $this->Planificacion->find('all', array(
                'fields' => array('Unidad.motor_id', 'Planificacion.unidad_id', 'Planificacion.faena_id', 'Planificacion.estado', 'Planificacion.tipointervencion', 'Planificacion.fecha', 'Planificacion.tiempo_trabajo', 'Planificacion.json', 'Planificacion.hora', 'Planificacion.fecha_termino', 'Planificacion.hora_termino'),
                'conditions' => array(
                    'Planificacion.estado IN (4, 5, 6)',
                    "Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_termino'",
                    "not" => array("Planificacion.fecha" => null)),
                'order' => 'Planificacion.fecha',
                'recursive' => 1));

            // Disponibilidad Fisica
            /*
              DF = (HN - HRDET) / HN
              HN = #MOTORES * 24 * 30 (#MOTORES = #EQUIPOS)
              HRDET = DURACION TODAS LAS INTERVENCIONES
             */
            $unidad = $this->Unidad->find('all', array('fields' => array('id', 'unidad'), 'recursive' => -1));
            $HN = count($unidad) * 24; // en Minutos
            //$HN = count($unidad) * 24 * 30 * 60; // en Minutos
        }
        //echo count($unidad) . ' ' ;
        //$HN = count($unidad) * 24 * 30 * 60; // en Minutos
        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Día";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Física";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["cols"][2]["label"] = "Contractual";
        $grafico_data["cols"][2]["type"] = "number";
        $datos = array();
        $contr = array();

        foreach ($intervenciones as $intervencion) {
            if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'BL') {
                continue;
            }
            $time = strtotime($intervencion["Planificacion"]["fecha"] . ' ' . $intervencion["Planificacion"]["hora"]);
            $end = strtotime($intervencion["Planificacion"]["fecha_termino"] . ' ' . $intervencion["Planificacion"]["hora_termino"]);
            $fecha = date("d-m-Y", $time);
            //$tiempo = 0;
            $tiempo = ($end - $time) / (60 * 60);
            /* if (strpos($intervencion["Planificacion"]["tiempo_trabajo"], ':') !== false) {
              $tiempo = split(":", $intervencion["Planificacion"]["tiempo_trabajo"]);
              $tiempo = intval($tiempo[0]) + intval($tiempo[1]) / 60; // En minutos
              } */

            if (!isset($datos[$fecha])) {
                $datos[$fecha] = $tiempo;
            } else {
                $datos[$fecha] += $tiempo;
            }

            if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'RI' || strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'RP') {
                if (!isset($contr[$fecha])) {
                    $contr[$fecha] = $tiempo;
                } else {
                    $contr[$fecha] += $tiempo;
                }
            }
        }

        $i = 0;
        foreach ($datos as $key => $value) {
            //echo "$HN --> $value || ";
            $value = round(($HN - $value) / $HN * 100, 2);
            $grafico_data["rows"][$i]["c"][0]["v"] = $key . '';
            $grafico_data["rows"][$i]["c"][1]["v"] = $value;
            if (isset($contr[$key])) {
                $value = round(($HN - $contr[$key]) / $HN * 100, 2);
                //$value = $contr[$key];
                $grafico_data["rows"][$i]["c"][2]["v"] = $value;
            } else {
                $grafico_data["rows"][$i]["c"][2]["v"] = 100.00;
            }
            $i++;
        }
        //echo $HN;
        $this->set("datos_grafico_1", $grafico_data);
        $this->set("fecha_inicio", $fecha_inicio);
        $this->set("fecha_termino", $fecha_termino);
        $faenas = $this->Faena->find('list', array('fields' => array('id', 'nombre'), 'order' => 'nombre'));
        $this->set('faenas', $faenas);
    }

    public function gestion_ajax_pie_indisponibilidad($fecha, $faena_id) {
        $this->loadModel('Planificacion');
        $this->loadModel('Unidad');
        //$faena_id =intval($this->Session->read('faena_id'));
        $this->layout = null;
        //$time = time();
        $time = strtotime($fecha);
        //$fecha_termino = date('Y-m-d', $time);
        $fecha = date('Y-m-d', $time);
        if ($faena_id != 0) {
            $faena = "faena_id = $faena_id";
        } else {
            $faena = 'faena_id <> 0';
        }
        $intervenciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.unidad_id', 'Planificacion.estado', 'Planificacion.tipointervencion', 'Planificacion.fecha', 'Planificacion.tiempo_trabajo', 'Planificacion.json', 'Planificacion.hora', 'Planificacion.fecha_termino', 'Planificacion.hora_termino'),
            'conditions' => array(
                "$faena",
                'Planificacion.estado IN (4, 5, 6)',
                "Planificacion.fecha = '$fecha'",
                //'EXTRACT(MONTH FROM DATE Planificacion.fecha) = ' . intval(date("m")),
                //'UPPER(Planificacion.tipointervencion)' => array('RP'),
                "not" => array("Planificacion.fecha" => null)),
            //'order' => 'Planificacion.fecha',
            'recursive' => -1));

        $this->loadModel('UnidadDetalle');
        $unidad = $this->UnidadDetalle->find('all', array('fields' => array('id', 'unidad'), 'conditions' => array("$faena"), 'recursive' => -1));
        // HN debe ser en minutos, agregar * 60 en produccion
        $hn = count($unidad) * 24;

        $hrdet = 0;
        $data = array();
        $datac = array();
        $delta = array();
        $deltatotal = array(0, 0, 0, 0);

        foreach ($intervenciones as $intervencion) {
            if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'BL') {
                continue;
            }

            if ($intervencion["Planificacion"]["json"] != null) {
                $json = json_decode($intervencion["Planificacion"]["json"], true);
                foreach ($json as $key => $value) {
                    if (substr($key, 0, 1) == 'd' && substr($key, 0, 2) != 'ds' && substr($key, -2) == '_r' && intval($value) > 0) {
                        $hora = preg_replace("/_r$/", "_h", $key);
                        $minuto = preg_replace("/_r$/", "_m", $key);
                        if (!isset($deltatotal[intval($value)])) {
                            $deltatotal[intval($value)] = intval($json[$hora]) * 60 + intval($json[$minuto]);
                        } else {
                            $deltatotal[intval($value)] += intval($json[$hora]) * 60 + intval($json[$minuto]);
                        }
                    }
                }

                // Se verifica si hay tiempo de conexión y desconexión y se agrega a DCC
                if (isset($json["con_f"]) && isset($json["cont_f"])) {
                    $inicio = strtotime($json["con_f"] . " " . $json["con_h"] . ":" . $json["con_m"] . " " . $json["con_p"]);
                    $termino = strtotime($json["cont_f"] . " " . $json["cont_h"] . ":" . $json["cont_m"] . " " . $json["cont_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    //@$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                if (isset($json["desc_f"]) && isset($json["desct_f"])) {
                    $inicio = strtotime($json["desc_f"] . " " . $json["desc_h"] . ":" . $json["desc_m"] . " " . $json["desc_p"]);
                    $termino = strtotime($json["desct_f"] . " " . $json["desct_h"] . ":" . $json["desct_m"] . " " . $json["desct_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    //@$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                if (isset($json["pp_i_f"]) && isset($json["pp_t_f"])) {
                    $inicio = strtotime($json["pp_i_f"] . " " . $json["pp_i_h"] . ":" . $json["pp_i_m"] . " " . $json["pp_i_p"]);
                    $termino = strtotime($json["pp_t_f"] . " " . $json["pp_t_h"] . ":" . $json["pp_t_m"] . " " . $json["pp_t_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    //@$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                if (isset($json["pm_i_f"]) && isset($json["pm_t_f"])) {
                    $inicio = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"] . ":" . $json["pm_i_m"] . " " . $json["pm_i_p"]);
                    $termino = strtotime($json["pm_t_f"] . " " . $json["pm_t_h"] . ":" . $json["pm_t_m"] . " " . $json["pm_t_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    //@$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) != "EX" && isset($json["llamado_fecha"]) && isset($json["llegada_fecha"])) {
                    $inicio = strtotime($json["llamado_fecha"] . " " . $json["llamado_hora"] . ":" . $json["llamado_min"] . " " . $json["llamado_periodo"]);
                    $termino = strtotime($json["llegada_fecha"] . " " . $json["llegada_hora"] . ":" . $json["llegada_min"] . " " . $json["llegada_periodo"]);
                    if (($termino - $inicio) / 60 == 15) {
                        $deltatotal[1] += ($termino - $inicio) / 60;
                    }
                } elseif (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "EX" && isset($json["llamado_fecha"]) && isset($json["llegada_fecha"])) {
                    $inicio = strtotime($json["llamado_fecha"] . " " . $json["llamado_hora"] . ":" . $json["llamado_min"] . " " . $json["llamado_periodo"]);
                    $termino = strtotime($json["llegada_fecha"] . " " . $json["llegada_hora"] . ":" . $json["llegada_min"] . " " . $json["llegada_periodo"]);
                    if (($termino - $inicio) / 60 == 15) {
                        $deltatotal[3] += ($termino - $inicio) / 60;
                    }
                }

                if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) != "EX" && isset($json["i_i_f"]) && isset($json["llegada_fecha"])) {
                    $inicio = strtotime($json["llegada_fecha"] . " " . $json["llegada_hora"] . ":" . $json["llegada_min"] . " " . $json["llegada_periodo"]);
                    $termino = strtotime($json["i_i_f"] . " " . $json["i_i_h"] . ":" . $json["i_i_m"] . " " . $json["i_i_p"]);
                    if (($termino - $inicio) / 60 == 15) {
                        $deltatotal[1] += ($termino - $inicio) / 60;
                    }
                } elseif (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "EX" && isset($json["i_i_f"]) && isset($json["llegada_fecha"])) {
                    $inicio = strtotime($json["llegada_fecha"] . " " . $json["llegada_hora"] . ":" . $json["llegada_min"] . " " . $json["llegada_periodo"]);
                    $termino = strtotime($json["i_i_f"] . " " . $json["i_i_h"] . ":" . $json["i_i_m"] . " " . $json["i_i_p"]);
                    if (($termino - $inicio) / 60 == 15) {
                        $deltatotal[3] += ($termino - $inicio) / 60;
                    }
                }

                if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "EX" && isset($json["i_i_f"]) && isset($json["i_t_f"])) {
                    $inicio = strtotime($json["i_i_f"] . " " . $json["i_i_h"] . ":" . $json["i_i_m"] . " " . $json["i_i_p"]);
                    $termino = strtotime($json["i_t_f"] . " " . $json["i_t_h"] . ":" . $json["i_t_m"] . " " . $json["i_t_p"]);
                    $deltatotal[3] += ($termino - $inicio) / 60;
                }

                if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "MP" && isset($json["i_i_f"]) && isset($json["i_t_f"])) {
                    $inicio = strtotime($json["i_i_f"] . " " . $json["i_i_h"] . ":" . $json["i_i_m"] . " " . $json["i_i_p"]);
                    $termino = strtotime($json["i_t_f"] . " " . $json["i_t_h"] . ":" . $json["i_t_m"] . " " . $json["i_t_p"]);
                    $deltatotal[1] += ($termino - $inicio) / 60;
                }
            }
        }

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Responsable";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Indisponibilidad";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["rows"][0]["c"][0]["v"] = "DCC";
        $grafico_data["rows"][0]["c"][1]["v"] = isset($deltatotal[1]) ? round(($deltatotal[1] / 60) * 100 / $hn, 2) : 0;
        $grafico_data["rows"][1]["c"][0]["v"] = "OEM";
        $grafico_data["rows"][1]["c"][1]["v"] = isset($deltatotal[2]) ? round(($deltatotal[2] / 60) * 100 / $hn, 2) : 0;
        $grafico_data["rows"][2]["c"][0]["v"] = "MINA";
        $grafico_data["rows"][2]["c"][1]["v"] = isset($deltatotal[3]) ? round(($deltatotal[3] / 60) * 100 / $hn, 2) : 0;

        if ($deltatotal[3] > 0 || $deltatotal[2] > 0 || $deltatotal[1] > 0) {
            $this->set("datos_grafico", json_encode($grafico_data));
        } else {
            $this->set("datos_grafico", "0");
        }
    }

    public function intervenciones_duracion($fecha, $faena_id) {
        $this->loadModel('Planificacion');
        $this->loadModel('Unidad');
        //$faena_id =intval($this->Session->read('faena_id'));
        $this->layout = null;
        //$time = time();
        $time = strtotime($fecha);
        //$fecha_termino = date('Y-m-d', $time);
        $fecha = date('Y-m-d', $time);
        if ($faena_id != "0") {
            $faena = "faena_id = $faena_id";
        } else {
            $faena = 'faena_id <> 0';
        }
        $intervenciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.unidad_id', 'Planificacion.estado', 'Planificacion.tipointervencion', 'Planificacion.fecha', 'Planificacion.tiempo_trabajo', 'Planificacion.json', 'Planificacion.faena_id', 'Faena.nombre'),
            'conditions' => array(
                "Planificacion.$faena",
                'Planificacion.estado IN (4, 5, 6, 7)',
                "Planificacion.fecha = '$fecha'",
                //'EXTRACT(MONTH FROM DATE Planificacion.fecha) = ' . intval(date("m")),
                //'UPPER(Planificacion.tipointervencion)' => array('RP'),
                "not" => array("Planificacion.fecha" => null)),
            'order' => 'Planificacion.fecha',
            'recursive' => 1));

        $this->loadModel('UnidadDetalle');
        $unidad = $this->UnidadDetalle->find('all', array('fields' => array('id', 'unidad'), 'conditions' => array("$faena"), 'recursive' => -1));
        // HN debe ser en minutos, agregar * 60 en produccion
        $hn = count($unidad) * 24 * 30 * 60;

        $hrdet = 0;
        $data = array();
        $datac = array();
        $delta = array();
        $deltatotal = array(0, 0, 0, 0);

        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Trabajos";
        $grafico_data["cols"][0]["type"] = "number";
        $grafico_data["cols"][1]["label"] = "Faenas";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["cols"][2]["type"] = "string";
        $grafico_data["cols"][2]["role"] = "tooltip";

        $tiempos = array();
        $cantidad = array();
        $faenas = array();
        foreach ($intervenciones as $intervencion) {
            if (strpos($intervencion["Planificacion"]["tiempo_trabajo"], ':') !== false) {
                $tiempo = split(":", $intervencion["Planificacion"]["tiempo_trabajo"]);
                $tiempo = intval($tiempo[0]) * 60 + intval($tiempo[1]);
            } else {
                $tiempo = 0;
            }
            if (isset($cantidad[$intervencion["Planificacion"]["faena_id"]])) {
                $cantidad[$intervencion["Planificacion"]["faena_id"]]++;
            } else {
                $cantidad[$intervencion["Planificacion"]["faena_id"]] = 1;
            }
            if (isset($tiempos[$intervencion["Planificacion"]["faena_id"]])) {
                $tiempos[$intervencion["Planificacion"]["faena_id"]] += $tiempo;
            } else {
                $tiempos[$intervencion["Planificacion"]["faena_id"]] = $tiempo;
            }
            $faenas[$intervencion["Planificacion"]["faena_id"]] = $intervencion["Faena"]["nombre"];
        }

        $i = 0;
        foreach ($cantidad as $key => $value) {
            //$tiempos[$key] = $tiempos[$key] / $value;
            $grafico_data["rows"][$i]["c"][0]["v"] = $value;
            $grafico_data["rows"][$i]["c"][1]["v"] = round($tiempos[$key] / ($value * 60), 1);
            $grafico_data["rows"][$i++]["c"][2]["v"] = $faenas[$key] . "\nIntervenciones: $value\nPromedio: " . $grafico_data["rows"][$i - 1]["c"][1]["v"] . "hrs.";
        }

        $this->set("datos_grafico", json_encode($grafico_data));
    }

    public function intervenciones_sistema($fecha, $faena_id) {
        $this->loadModel('Planificacion');
        $this->loadModel('Unidad');
        $this->loadModel('Sistema');
        //$faena_id =intval($this->Session->read('faena_id'));
        $this->layout = null;
        //$time = time();
        $time = strtotime($fecha);
        //$fecha_termino = date('Y-m-d', $time);
        $fecha = date('Y-m-d', $time);
        if ($faena_id != "0") {
            $faena = "faena_id = $faena_id";
        } else {
            $faena = 'faena_id <> 0';
        }
        $intervenciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.unidad_id', 'Planificacion.estado', 'Planificacion.tipointervencion', 'Planificacion.fecha', 'Planificacion.tiempo_trabajo', 'Planificacion.json', 'Planificacion.faena_id'),
            'conditions' => array(
                "$faena",
                'Planificacion.estado IN (4, 5, 6, 7)',
                "Planificacion.fecha = '$fecha'",
                "not" => array("Planificacion.fecha" => null)),
            'order' => 'Planificacion.fecha',
            'recursive' => -1));

        $this->loadModel('UnidadDetalle');
        $unidad = $this->UnidadDetalle->find('all', array('fields' => array('id', 'unidad'), 'conditions' => array("$faena"), 'recursive' => -1));
        // HN debe ser en minutos, agregar * 60 en produccion
        $hn = count($unidad) * 24 * 30 * 60;

        $hrdet = 0;
        $data = array();
        $datac = array();
        $delta = array();
        $deltatotal = array(0, 0, 0, 0);

        $sistema = array();
        $subsistema = array();
        $elementos = array();

        foreach ($intervenciones as $intervencion) {
            if (isset($intervencion["Planificacion"]["json"]) && $intervencion["Planificacion"]["json"] != NULL) {
                $json = json_decode($intervencion["Planificacion"]["json"], true);
                if (isset($json["ele_cantidad"])) {
                    $ele_cantidad = intval($json["ele_cantidad"]);
                    for ($i = 1; $i <= $ele_cantidad; $i++) {
                        $elemento = split(",", $json["elemento_" . $i]);
                        if ($elemento[8] == "1") {
                            if (!isset($sistema[$elemento[0]])) {
                                $sistema[$elemento[0]] = 1;
                            } else {
                                $sistema[$elemento[0]]++;
                            }
                        }
                        //if (!isset($subsistema[$elemento[1]])) {$subsistema[$elemento[1]]=1;}else{$subsistema[$elemento[1]]++;}
                        //if (!isset($elementos[$elemento[3]])) {$elementos[$elemento[3]]=1;}else{$elementos[$elemento[3]]++;}
                    }
                }
            }
        }
        arsort($sistema);
        arsort($subsistema);
        arsort($elementos);
        //print_r($sistema);

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Sistema";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Eventos";
        $grafico_data["cols"][1]["type"] = "number";
        $i = 0;
        foreach ($sistema as $key => $value) {
            //$tiempos[$key] = $tiempos[$key] / $value;
            $sistema = $this->Sistema->find('first', array('conditions' => array("id = $key"), 'recursive' => -1));
            $grafico_data["rows"][$i]["c"][0]["v"] = $sistema["Sistema"]["nombre"];
            $grafico_data["rows"][$i++]["c"][1]["v"] = $value;
        }

        $this->set("datos_grafico", json_encode($grafico_data));
    }

    public function index2() {
        $this->set("titulo", "Dashboard Gestión");
        $this->loadModel('Planificacion');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        //$faena_id =intval($this->Session->read('faena_id'));
        /* if ($faena_id != 0) {
          $faena = "faena_id = $faena_id";
          } else {
          $faena = 'faena_id <> 0';
          } */

        $termino = time();
        $inicio = strtotime(date('Y-m-d H:i:s', $termino) . ' -30 days');
        $fecha_inicio = date("Y-m-d", $inicio);
        $fecha_termino = date("Y-m-d", $termino);
        $this->set("faena_id", "0");
        if (isset($this->request->data) && count($this->request->data) >= 2 && $this->request->data["faena_id"] != "0") {
            $fecha_inicio = $this->request->data["fecha_inicio"];
            $fecha_termino = $this->request->data["fecha_termino"];
            $intervenciones = $this->Planificacion->find('all', array(
                'fields' => array('Unidad.motor_id', 'Planificacion.unidad_id', 'Planificacion.faena_id', 'Planificacion.estado', 'Planificacion.tipointervencion', 'Planificacion.fecha', 'Planificacion.tiempo_trabajo', 'Planificacion.json', 'Planificacion.hora', 'Planificacion.fecha_termino', 'Planificacion.hora_termino'),
                'conditions' => array(
                    "Planificacion.faena_id = {$this->request->data["faena_id"]}",
                    'Planificacion.estado IN (4, 5, 6)',
                    "Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_termino'",
                    "not" => array("Planificacion.fecha" => null)),
                'order' => 'Planificacion.fecha',
                'recursive' => 1));

            // Disponibilidad Fisica
            /*
              DF = (HN - HRDET) / HN
              HN = #MOTORES * 24 * 30 (#MOTORES = #EQUIPOS)
              HRDET = DURACION TODAS LAS INTERVENCIONES
             */
            $unidad = $this->Unidad->find('all', array('fields' => array('id', 'unidad', 'faena_id'), 'conditions' => array("faena_id = {$this->request->data["faena_id"]}"), 'recursive' => -1));

            $HN = count($unidad) * 24; // en Minutos
            $this->set("faena_id", $this->request->data["faena_id"]);
        } else {
            $intervenciones = $this->Planificacion->find('all', array(
                'fields' => array('Unidad.motor_id', 'Planificacion.unidad_id', 'Planificacion.faena_id', 'Planificacion.estado', 'Planificacion.tipointervencion', 'Planificacion.fecha', 'Planificacion.tiempo_trabajo', 'Planificacion.json', 'Planificacion.hora', 'Planificacion.fecha_termino', 'Planificacion.hora_termino'),
                'conditions' => array(
                    'Planificacion.estado IN (4, 5, 6)',
                    "Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_termino'",
                    "not" => array("Planificacion.fecha" => null)),
                'order' => 'Planificacion.fecha',
                'recursive' => 1));

            // Disponibilidad Fisica
            /*
              DF = (HN - HRDET) / HN
              HN = #MOTORES * 24 * 30 (#MOTORES = #EQUIPOS)
              HRDET = DURACION TODAS LAS INTERVENCIONES
             */
            $unidad = $this->Unidad->find('all', array('fields' => array('id', 'unidad'), 'recursive' => -1));
            $HN = count($unidad) * 24; // en Minutos
            //$HN = count($unidad) * 24 * 30 * 60; // en Minutos
        }
        //echo count($unidad) . ' ' ;
        //$HN = count($unidad) * 24 * 30 * 60; // en Minutos
        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Día";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Física";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["cols"][2]["label"] = "Contractual";
        $grafico_data["cols"][2]["type"] = "number";
        $datos = array();
        $contr = array();

        foreach ($intervenciones as $intervencion) {
            if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'BL') {
                continue;
            }
            $time = strtotime($intervencion["Planificacion"]["fecha"] . ' ' . $intervencion["Planificacion"]["hora"]);
            $end = strtotime($intervencion["Planificacion"]["fecha_termino"] . ' ' . $intervencion["Planificacion"]["hora_termino"]);
            $fecha = date("d-m-Y", $time);
            //$tiempo = 0;
            $tiempo = ($end - $time) / (60 * 60);
            /* if (strpos($intervencion["Planificacion"]["tiempo_trabajo"], ':') !== false) {
              $tiempo = split(":", $intervencion["Planificacion"]["tiempo_trabajo"]);
              $tiempo = intval($tiempo[0]) + intval($tiempo[1]) / 60; // En minutos
              } */

            if (!isset($datos[$fecha])) {
                $datos[$fecha] = $tiempo;
            } else {
                $datos[$fecha] += $tiempo;
            }

            if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'RI' || strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'RP') {
                if (!isset($contr[$fecha])) {
                    $contr[$fecha] = $tiempo;
                } else {
                    $contr[$fecha] += $tiempo;
                }
            }
        }

        $i = 0;
        foreach ($datos as $key => $value) {
            //echo "$HN --> $value || ";
            $value = round(($HN - $value) / $HN * 100, 2);
            $grafico_data["rows"][$i]["c"][0]["v"] = $key . '';
            $grafico_data["rows"][$i]["c"][1]["v"] = $value;
            if (isset($contr[$key])) {
                echo "$key,$value,";
                $value = round(($HN - $contr[$key]) / $HN * 100, 2);
                //$value = $contr[$key];
                echo "$value<br />";
                $grafico_data["rows"][$i]["c"][2]["v"] = $value;
            } else {
                echo "$key,$value,100<br/>";
                $grafico_data["rows"][$i]["c"][2]["v"] = 100.00;
            }
            $i++;
        }
        //exit;
        //echo $HN;
        $this->set("datos_grafico_1", $grafico_data);
        $this->set("fecha_inicio", $fecha_inicio);
        $this->set("fecha_termino", $fecha_termino);
        $faenas = $this->Faena->find('list', array('fields' => array('id', 'nombre'), 'order' => 'nombre'));
        $this->set('faenas', $faenas);
    }

}
