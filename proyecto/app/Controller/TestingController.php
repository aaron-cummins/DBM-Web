<?php

App::uses('ConnectionManager', 'Model');

class TestingController extends AppController {

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
        if (isset($this->request->data) && count($this->request->data) >= 2 && $this->request->data["faena_id"] != "0" && is_numeric($this->request->data["faena_id"])) {
            $fecha_inicio = $this->request->data["fecha_inicio"];
            $fecha_termino = $this->request->data["fecha_termino"];
            $intervenciones = $this->Planificacion->find('all', array(
                'fields' => array('Planificacion.unidad_id', 'Planificacion.faena_id', 'Planificacion.estado', 'Planificacion.tipointervencion', 'Planificacion.fecha', 'Planificacion.tiempo_trabajo', 'Planificacion.json', 'Planificacion.hora', 'Planificacion.fecha_termino', 'Planificacion.hora_termino'),
                'conditions' => array(
                    "Planificacion.faena_id = {$this->request->data["faena_id"]}",
                    'Planificacion.estado IN (4, 5, 6)',
                    "Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_termino'",
                    "not" => array("Planificacion.fecha" => null)),
                'order' => 'Planificacion.fecha',
                'recursive' => -1));

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
                'fields' => array('Planificacion.unidad_id', 'Planificacion.faena_id', 'Planificacion.estado', 'Planificacion.tipointervencion', 'Planificacion.fecha', 'Planificacion.tiempo_trabajo', 'Planificacion.json', 'Planificacion.hora', 'Planificacion.fecha_termino', 'Planificacion.hora_termino'),
                'conditions' => array(
                    'Planificacion.estado IN (4, 5, 6)',
                    "Planificacion.fecha BETWEEN '$fecha_inicio' AND '$fecha_termino'",
                    "not" => array("Planificacion.fecha" => null)),
                'order' => 'Planificacion.fecha',
                'recursive' => -1));

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

}

?>