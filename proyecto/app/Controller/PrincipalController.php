<?php

App::uses('ConnectionManager', 'Model');
/*
  CLASES Y METODOS DEPRECADOS EN ACTUALIZACION 2.0
 */

class PrincipalController extends AppController {

    public function index() {
        $this->check_permissions($this);
        // Verificamos si es Supervisor DCC para redireccionar
        $nivel = $this->Session->read('Nivel');

        /* if ($nivel['id'] == 2) {
          $this->redirect('/Principal/supervisordcc');
          } elseif ($nivel['id'] == 3) {
          $this->redirect('/Principal/cliente');
          } elseif ($nivel['id'] == 5) {
          $this->redirect('/Gestion');
          } */

        if ($nivel == 2 || $nivel == 7) {
            $this->redirect('/Principal/supervisordcc');
        } elseif ($nivel == 3) {
            $this->redirect('/Principal/cliente');
        } elseif ($nivel == 5 || $nivel == 6) {
            $this->redirect('/Gestion');
        }

        $this->set('titulo', 'Principal');
        $this->loadModel('Planificacion');

        $faena_id = intval($this->Session->read('faena_id'));

        if ($faena_id != 0) {
            $faena = "Planificacion.faena_id = $faena_id";
        } else {
            $faena = 'Planificacion.faena_id <> 0';
        }

        $intervenciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.tipointervencion', 'Planificacion.fecha'),
            'conditions' => array(
                $faena,
                'UPPER(Planificacion.tipointervencion)' => array('RP'),
                "not" => array("Planificacion.fecha" => null)),
            'recursive' => -1));

        $rp = count($intervenciones);
        $intervenciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.tipointervencion', 'Planificacion.fecha'),
            'conditions' => array(
                $faena,
                'UPPER(Planificacion.tipointervencion)' => array('MP'),
                "not" => array("Planificacion.fecha" => null)),
            'recursive' => -1));
        $mp = count($intervenciones);
        $intervenciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.tipointervencion', 'Planificacion.fecha'),
            'conditions' => array(
                $faena,
                'UPPER(Planificacion.tipointervencion)' => array('OP'),
                "not" => array("Planificacion.fecha" => null)),
            'recursive' => -1));
        $bl = count($intervenciones);
        $intervenciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.tipointervencion', 'Planificacion.fecha'),
            'conditions' => array(
                $faena,
                'UPPER(Planificacion.tipointervencion)' => array('RI'),
                "not" => array("Planificacion.fecha" => null)),
            'recursive' => -1));
        $ri = count($intervenciones);
        $intervenciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.tipointervencion', 'Planificacion.fecha'),
            'conditions' => array(
                $faena,
                'UPPER(Planificacion.tipointervencion)' => array('EX'),
                "not" => array("Planificacion.fecha" => null)),
            'recursive' => -1));
        $ex = count($intervenciones);

        $intervenciones = $this->Planificacion->find('all', array('fields' => array('Planificacion.estado', 'Planificacion.tiempo_trabajo', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion'), 'conditions' => array($faena, "Planificacion.estado IN (4,5,6,7)"),
            'recursive' => -1));
        $this->set('intervenciones_terminadas', $intervenciones);
        //$this->set('intervenciones_terminadas', array());

        $intervenciones = $this->Planificacion->find('all', array('fields' => array('Planificacion.estado', 'Planificacion.tiempo_trabajo', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion'), 'conditions' => array($faena, "Planificacion.estado IN (2)"),
            'recursive' => -1));
        $this->set('intervenciones_ejecucion', $intervenciones);
        //$this->set('intervenciones_ejecucion', array());
        $intervenciones = $this->Planificacion->find('all', array('fields' => array('Planificacion.estado', 'Planificacion.hijo', 'Planificacion.tiempo_trabajo', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion', 'Planificacion.json'), 'conditions' => array($faena, "(Planificacion.hijo <> '' AND Planificacion.hijo IS NOT NULL) AND Planificacion.estado IN (4,5,6,7)"),
            'recursive' => -1));
        $this->set('intervenciones_continuacion', $intervenciones);
        $this->set('intervenciones_continuacion', array());

        $this->set('rp', $rp);
        $this->set('mp', $mp);
        $this->set('bl', $bl);
        $this->set('ri', $ri);
        $this->set('ex', $ex);
        $this->set('total', $ex + $ri + $bl + $mp + $rp);
    }

    public function supervisordcc() {
        $this->check_permissions($this);
        $this->set("titulo", "Dashboard Supervisor DCC");
        $this->loadModel('Planificacion');
        $this->loadModel('Configuracion');
        $faena_id = intval($this->Session->read('faena_id'));
        $fq = " faena_id<>0 AND ";
        if ($faena_id != 0) {
            $faena = "Planificacion.faena_id = $faena_id";
            $fq = " faena_id=$faena_id AND ";
        } else {
            $faena = 'Planificacion.faena_id <> 0';
            $fq = " faena_id<>0 AND ";
        }

        $conf = $this->Configuracion->find('first', array(
            'conditions' => array("id = 1"),
            'recursive' => -1
        ));
        $conf = $conf["Configuracion"];

        /*
          "Pantalla Principal Resumen Supervisor DCC :
          Titulo: "" Dias Pendientes de Revision"" => 2 graficos:


          1.- Grafico de torta separado por categoria según cantidad de días en espera de revisión (Verde( 0 -1 dias), Amarillo (1 - 2 dias), rojo ( > 2 Dias )), estas categorias pueden ser modificadas por el administrador.


          2.- Grafico de barras separado por trabajos terminados y pendientes (son los que generan continuación) y cada barra debe ser segmentada por la cantidad de dias pendiente de revisión segun categoria anterior."
         */

        // Se obtienen todos los trabajos ejecutados y no aprobados.
        // Se debe incluir estado Rechazado Cliente?
        $estados = "(7, 6)";
        $intervenciones = $this->Planificacion->find('all', array('fields' => array('Planificacion.estado', 'Planificacion.tiempo_trabajo', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion', 'Planificacion.fecha_termino', 'Planificacion.faena_id', 'Planificacion.hijo'), 'conditions' => array($faena, "Planificacion.estado IN $estados"), 'recursive' => -1));

        $this->set('intervenciones', $intervenciones);
        $datos = array();
        $datos["verde"] = 0;
        $datos["amarillo"] = 0;
        $datos["rojo"] = 0;
        $fecha_actual = date_create(date("Y-m-d"));

        $datos_2 = array();
        $datos_2["verde"][0] = 0;
        $datos_2["amarillo"][0] = 0;
        $datos_2["rojo"][0] = 0;
        $datos_2["verde"][1] = 0;
        $datos_2["amarillo"][1] = 0;
        $datos_2["rojo"][1] = 0;

        foreach ($intervenciones as $intervencion) {
            $interval = date_diff(date_create($intervencion["Planificacion"]["fecha_termino"]), $fecha_actual);
            switch ($interval->format("%d")) {
                case 0:
                    $datos["verde"]++;
                    if ($intervencion["Planificacion"]["hijo"] != "") {
                        $datos_2["verde"][1]++;
                    } else {
                        $datos_2["verde"][0]++;
                    }
                    break;
                case 1:
                case 2:
                    $datos["amarillo"]++;
                    if ($intervencion["Planificacion"]["hijo"] != "") {
                        $datos_2["amarillo"][1]++;
                    } else {
                        $datos_2["amarillo"][0]++;
                    }
                    break;
                case $conf["g_3_c"]:
                default:
                    $datos["rojo"]++;
                    if ($intervencion["Planificacion"]["hijo"] != "") {
                        $datos_2["rojo"][1]++;
                    } else {
                        $datos_2["rojo"][0]++;
                    }
            }
        }

        $db = ConnectionManager::getDataSource('default');
        $f1 = strtotime("-1 day");
        $f2 = strtotime("-2 day");
        $f3 = strtotime("-3 day");
        $f0 = strtotime("now");
        $f1 = date("Y-m-d", $f1);
        $f0 = date("Y-m-d", $f0);
        $f2 = date("Y-m-d", $f2);
        $f3 = date("Y-m-d", $f3);
        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Días";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Trabajos";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["rows"][0]["c"][0]["v"] = "< 1 dia";
        $q = "select count(*) as count from (select id,estado from planificacion where $fq estado IN (7) and fecha BETWEEN '$f0 00:00:00' AND '$f0 23:59:59') as r;";
        //echo "<!--$q-->";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][0]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][0]["c"][1]["v"] = "0";
        }
        $grafico_data["rows"][1]["c"][0]["v"] = "1 - 2 dias";
        $q = "select count(*) as count from (select id,estado from planificacion where $fq estado IN (7) and fecha BETWEEN '$f2 00:00:00' AND '$f1 23:59:59') as r;";
        echo "<!--$q-->";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][1]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][1]["c"][1]["v"] = "0";
        }
        $grafico_data["rows"][2]["c"][0]["v"] = "> 2 dias";
        $q = "select count(*) as count from (select id,estado from planificacion where $fq estado IN (7) and fecha <= '$f3 23:59:59') as r;";
        //echo "<!--$q-->";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][2]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][2]["c"][1]["v"] = "0";
        }

        $this->set("datos_grafico_1", $grafico_data);

        /*
          [
          ['Genre', 'Fantasy & Sci Fi', 'Romance', 'Mystery/Crime', 'General',
          'Western', 'Literature', { role: 'annotation' } ],
          ['2010', 10, 24, 20, 32, 18, 5, ''],
          ['2020', 16, 22, 23, 30, 16, 9, ''],
          ['2030', 28, 19, 29, 30, 12, 13, '']
          ]
         */
        $this->set("datos_grafico_2", $grafico_data);

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Días";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "> 2 dias";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["cols"][2]["label"] = "1 - 2 dias";
        $grafico_data["cols"][2]["type"] = "number";
        $grafico_data["cols"][3]["label"] = "< 1 dia";
        $grafico_data["cols"][3]["type"] = "number";

        $grafico_data["rows"][0]["c"][0]["v"] = "Terminados";
        $q = "select count(*) from (select id,estado from planificacion where $fq estado IN (7) and (hijo is null or hijo = '') and fecha <= '$f3 23:59:59') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][0]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][0]["c"][1]["v"] = "0";
        }
        $q = "select count(*) from (select id,estado from planificacion where $fq estado IN (7) and (hijo is null or hijo = '') and fecha BETWEEN '$f2 00:00:00' AND '$f1 23:59:59') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][0]["c"][2]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][0]["c"][2]["v"] = "0";
        }
        $q = "select count(*) from (select id,estado from planificacion where $fq  estado IN (7) and (hijo is null or hijo = '') and fecha >= '$f0 00:00:00') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][0]["c"][3]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][0]["c"][3]["v"] = "0";
        }

        $grafico_data["rows"][1]["c"][0]["v"] = "Pendientes";
        $q = "select count(*) from (select id,estado from planificacion where $fq  estado IN (7) and (hijo is not null and hijo <> '') and fecha >= '$f0 00:00:00') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][1]["c"][3]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][1]["c"][3]["v"] = "0";
        }
        $q = "select count(*) from (select id,estado from planificacion where $fq estado IN (7) and (hijo is not null and hijo <> '') and fecha BETWEEN '$f2 00:00:00' AND '$f1 23:59:59') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][1]["c"][2]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][1]["c"][2]["v"] = "0";
        }
        $q = "select count(*) from (select id,estado from planificacion where $fq estado IN (7) and (hijo is not null and hijo <> '') and fecha <= '$f3 23:59:59') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][1]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][1]["c"][1]["v"] = "0";
        }

        $this->set("datos_grafico_6", $grafico_data);


        /*
          var data = google.visualization.arrayToDataTable([
          ['Genre', 'Fantasy & Sci Fi', 'Romance', 'Mystery/Crime', 'General',
          'Western', 'Literature', { role: 'annotation' } ],
          ['2010', 10, 24, 20, 32, 18, 5, ''],
          ['2020', 16, 22, 23, 30, 16, 9, ''],
          ['2030', 28, 19, 29, 30, 12, 13, '']
          ]);

          var options = {
          width: 600,
          height: 400,
          legend: { position: 'top', maxLines: 3 },
          bar: { groupWidth: '75%' },
          isStacked: true,
          };
         */

        $this->set('datos', $datos);

        /*

          "Pantalla Principal Resumen Supervisor DCC :
          Titulo: "" Trabajos pplanificados no realizados"" => 2 graficos:

          1.- Grafico de torta separado por categoria según cantidad de días en espera de realización (Categoria Planificados) (Verde( 0 -1 dias), Amarillo (1 - 2 dias), rojo ( > 2 Dias )), estas categorias pueden ser modificadas por el administrador.


          2.- Grafico de torta separados por tipo principal (RP, MP, BL)."
         */

        $fecha_actual = date("Y-m-d");
        // Se obtienen todos los trabajos programados y no ejecutados.
        $intervenciones = $this->Planificacion->find('all', array('fields' => array('Planificacion.estado', 'Planificacion.id', 'Planificacion.tiempo_trabajo', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion', 'Planificacion.fecha', 'Planificacion.faena_id', 'Planificacion.hijo'), 'conditions' => array($faena, "Planificacion.estado = 2"), 'recursive' => -1));

        $datos = array();
        $datos["verde"] = 0;
        $datos["amarillo"] = 0;
        $datos["rojo"] = 0;
        $datos["RP"] = 0;
        $datos["OP"] = 0;
        $datos["MP"] = 0;
        $datos["RI"] = 0;
        $fecha_actual = date_create($fecha_actual);
        foreach ($intervenciones as $intervencion) {
            if ($intervencion["Planificacion"]["tipointervencion"] == 'BL') {
                $intervencion["Planificacion"]["tipointervencion"] = 'OP';
            }
            $datos[strtoupper($intervencion["Planificacion"]["tipointervencion"])]++;
            $interval = date_diff(date_create($intervencion["Planificacion"]["fecha"]), $fecha_actual);
            switch ($interval->format("%d")) {
                case 0:
                    ///echo $intervencion["Planificacion"]["id"] . " ";
                    $datos["verde"]++;
                    break;
                case 1:
                case 2:
                    $datos["amarillo"]++;
                    break;
                case $conf["g_3_c"]:
                default:
                    if ($interval->format("%d") < 0) {
                        $datos["verde"]++;
                        //echo $intervencion["Planificacion"]["id"] . " ";
                    } else {
                        //echo $interval->format("%d") . " ";
                        $datos["rojo"]++;
                    }
            }
        }

        /*
          {
          "cols": [
          {"id":"","label":"Topping","pattern":"","type":"string"},
          {"id":"","label":"Slices","pattern":"","type":"number"}
          ],
          "rows": [
          {"c":[{"v":"Mushrooms","f":null},{"v":3,"f":null}]},
          {"c":[{"v":"Onions","f":null},{"v":1,"f":null}]},
          {"c":[{"v":"Olives","f":null},{"v":1,"f":null}]},
          {"c":[{"v":"Zucchini","f":null},{"v":1,"f":null}]},
          {"c":[{"v":"Pepperoni","f":null},{"v":2,"f":null}]}
          ]
          }
         */

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Tipo";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Trabajos";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["rows"][0]["c"][0]["v"] = "RP";
        $grafico_data["rows"][0]["c"][1]["v"] = $datos["RP"];
        $grafico_data["rows"][1]["c"][0]["v"] = "OP";
        $grafico_data["rows"][1]["c"][1]["v"] = $datos["OP"];
        $grafico_data["rows"][2]["c"][0]["v"] = "MP";
        $grafico_data["rows"][2]["c"][1]["v"] = $datos["MP"];
        $grafico_data["rows"][3]["c"][0]["v"] = "RI";
        $grafico_data["rows"][3]["c"][1]["v"] = $datos["RI"];
        $this->set("datos_grafico_5", $grafico_data);

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Días";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Trabajos";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["rows"][0]["c"][0]["v"] = "< 1 dia";
        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and  correlativo_final is not null and  estado IN (2) and fecha >= '$f0 00:00:00') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][0]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][0]["c"][1]["v"] = "0";
        }
        $grafico_data["rows"][1]["c"][0]["v"] = "1 - 2 dias";
        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and  correlativo_final is not null and  estado IN (2) and fecha BETWEEN '$f2 00:00:00' AND '$f1 23:59:59') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][1]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][1]["c"][1]["v"] = "0";
        }
        $grafico_data["rows"][2]["c"][0]["v"] = "> 2 dias";
        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and  correlativo_final is not null  and estado IN (2) and fecha <= '$f3 23:59:59') as r;";
        //echo "<!-- $q -->";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][2]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][2]["c"][1]["v"] = "0";
        }

        $this->set("datos_grafico_3", $grafico_data);
    }

    public function cliente() {
        $this->check_permissions($this);
        $this->set("titulo", "Dashboard Cliente");
        $this->loadModel('Planificacion');
        $faena_id = intval($this->Session->read('faena_id'));

        if ($faena_id != 0) {
            $faena = "Planificacion.faena_id = $faena_id";
        } else {
            $faena = 'Planificacion.faena_id <> 0';
        }
        /*
          Perfil Cliente => Item Resumen: Agregar 3 graficos idem Supervisor DCC => 1.- Torta con cantidad de dias para aprobación. 2.- Barras separados por eventos terminados y pendientes. 3.- Torta con tipo de intervención.
         */
        $fq = " faena_id<>0 AND ";
        if ($faena_id != 0) {
            $faena = "Planificacion.faena_id = $faena_id";
            $fq = " faena_id=$faena_id AND ";
        } else {
            $faena = 'Planificacion.faena_id <> 0';
            $fq = " faena_id<>0 AND ";
        }
        $db = ConnectionManager::getDataSource('default');
        $f1 = strtotime("-1 day");
        $f2 = strtotime("-2 day");
        $f3 = strtotime("-3 day");
        $f0 = strtotime("now");
        $f1 = date("Y-m-d", $f1);
        $f0 = date("Y-m-d", $f0);
        $f2 = date("Y-m-d", $f2);
        $f3 = date("Y-m-d", $f3);
        $fecha_actual = date("Y-m-d");
        // Se debe incluir estado Rechazado Cliente?
        $estados = "(4,6)";
        // Se obtienen todos los trabajos ejecutados y no aprobados.
        $tipo_trabajos = "('RP', 'BL', 'MP', 'RI', 'EX')";
        $intervenciones = $this->Planificacion->find('all', array('fields' => array('Planificacion.id', 'Planificacion.estado', 'Planificacion.tiempo_trabajo', 'Planificacion.correlativo', 'Planificacion.tipointervencion', 'Planificacion.tipomantencion', 'Planificacion.fecha_aprobacion', 'Planificacion.faena_id', 'Planificacion.hijo'),
            'conditions' => array($faena, "Planificacion.estado IN $estados and Planificacion.correlativo is not null",
                "UPPER(Planificacion.tipointervencion) IN $tipo_trabajos",
                "not" => array("Planificacion.fecha" => null),
                "not" => array("Planificacion.faena_id" => null),
                "not" => array("Planificacion.flota_id" => null),
                "not" => array("Planificacion.unidad_id" => null)),
            'order' => "Planificacion.id",
            'recursive' => -1));

        $datos = array();
        $datos["verde"] = 0;
        $datos["amarillo"] = 0;
        $datos["rojo"] = 0;
        $datos["RP"] = 0;
        $datos["BL"] = 0;
        $datos["MP"] = 0;

        $datos_2 = array();
        $datos_2["verde"][0] = 0;
        $datos_2["amarillo"][0] = 0;
        $datos_2["rojo"][0] = 0;
        $datos_2["verde"][1] = 0;
        $datos_2["amarillo"][1] = 0;
        $datos_2["rojo"][1] = 0;

        $datos["RP"] = 0;
        $datos["BL"] = 0;
        $datos["MP"] = 0;
        $datos["RI"] = 0;
        $datos["EX"] = 0;

        $datosr["RP"] = 0;
        $datosr["BL"] = 0;
        $datosr["MP"] = 0;
        $datosr["RI"] = 0;
        $datosr["EX"] = 0;
        //$i=1;
        $fecha_actual = date_create($fecha_actual);
        foreach ($intervenciones as $intervencion) {
            /* if(strtoupper($intervencion["Planificacion"]["tipointervencion"])=="EX"){
              echo "$i {$intervencion["Planificacion"]["id"]} {$intervencion["Planificacion"]["tipointervencion"]} {$intervencion["Planificacion"]["fecha_aprobacion"]}<br />";
              $i++;
              } */
            if ($intervencion["Planificacion"]["estado"] == 4) {
                $datos[strtoupper($intervencion["Planificacion"]["tipointervencion"])]++;
            }
            if ($intervencion["Planificacion"]["estado"] == 6) {
                $datosr[strtoupper($intervencion["Planificacion"]["tipointervencion"])]++;
            }
            $interval = date_diff(date_create($intervencion["Planificacion"]["fecha_aprobacion"]), $fecha_actual);
            switch ($interval->format("%d")) {
                case 0:
                    $datos["verde"]++;
                    if ($intervencion["Planificacion"]["hijo"] != "") {
                        $datos_2["verde"][1]++;
                    } else {
                        $datos_2["verde"][0]++;
                    }
                    break;
                case 1:
                case 2:
                    $datos["amarillo"]++;
                    if ($intervencion["Planificacion"]["hijo"] != "") {
                        $datos_2["amarillo"][1]++;
                    } else {
                        $datos_2["amarillo"][0]++;
                    }
                    break;
                case 3:
                default:
                    $datos["rojo"]++;
                    if ($intervencion["Planificacion"]["hijo"] != "") {
                        $datos_2["rojo"][1]++;
                    } else {
                        $datos_2["rojo"][0]++;
                    }
            }
        }

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Días";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Trabajos";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["rows"][0]["c"][0]["v"] = "< 1 dia";
        $grafico_data["rows"][0]["c"][1]["v"] = $datos["verde"];
        $grafico_data["rows"][1]["c"][0]["v"] = "1 - 2 dias";
        $grafico_data["rows"][1]["c"][1]["v"] = $datos["amarillo"];
        $grafico_data["rows"][2]["c"][0]["v"] = "> 2 dias";
        $grafico_data["rows"][2]["c"][1]["v"] = $datos["rojo"];
        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and correlativo is not null and estado IN (4) and fecha_aprobacion >= '$f0 00:00:00') as r;";
        //echo "<!--$q-->";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][0]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][0]["c"][1]["v"] = "0";
        }
        $grafico_data["rows"][1]["c"][0]["v"] = "1 - 2 dias";
        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and correlativo is not null and estado IN (4) and fecha_aprobacion BETWEEN '$f2 00:00:00' AND '$f1 23:59:59') as r;";
        //echo "<!--$q-->";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][1]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][1]["c"][1]["v"] = "0";
        }
        $grafico_data["rows"][2]["c"][0]["v"] = "> 2 dias";
        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and correlativo is not null and estado IN (4) and fecha_aprobacion <= '$f3 23:59:59') as r;";
        echo "<!--$q-->";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][2]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][2]["c"][1]["v"] = "0";
        }
        $this->set("datos_grafico_1", $grafico_data);

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Días";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "> 2 dias";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["cols"][2]["label"] = "1 - 2 dias";
        $grafico_data["cols"][2]["type"] = "number";
        $grafico_data["cols"][3]["label"] = "< 1 dia";
        $grafico_data["cols"][3]["type"] = "number";

        $grafico_data["rows"][0]["c"][0]["v"] = "Terminados";
        /* $grafico_data["rows"][0]["c"][1]["v"] = $datos_2["rojo"][0];
          $grafico_data["rows"][0]["c"][2]["v"] = $datos_2["amarillo"][0];
          $grafico_data["rows"][0]["c"][3]["v"] = $datos_2["verde"][0]; */

        $grafico_data["rows"][1]["c"][0]["v"] = "Pendientes";
        /* $grafico_data["rows"][1]["c"][1]["v"] = $datos_2["rojo"][1];
          $grafico_data["rows"][1]["c"][2]["v"] = $datos_2["amarillo"][1];
          $grafico_data["rows"][1]["c"][3]["v"] = $datos_2["verde"][1]; */

        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and  correlativo is not null and estado IN (4) and (hijo is null or hijo = '') and fecha_aprobacion <= '$f3 23:59:59') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][0]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][0]["c"][1]["v"] = "0";
        }
        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and  correlativo is not null  and estado IN (4) and (hijo is null or hijo = '') and fecha_aprobacion BETWEEN '$f2 00:00:00' AND '$f1 23:59:59') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][0]["c"][2]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][0]["c"][2]["v"] = "0";
        }
        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and  correlativo is not null  and estado IN (4) and (hijo is null or hijo = '') and fecha_aprobacion >= '$f0 00:00:00') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][0]["c"][3]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][0]["c"][3]["v"] = "0";
        }

        $grafico_data["rows"][1]["c"][0]["v"] = "Pendientes";
        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and  correlativo is not null  and estado IN (4) and (hijo is not null and hijo <> '') and fecha_aprobacion >= '$f0 00:00:00') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][1]["c"][3]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][1]["c"][3]["v"] = "0";
        }
        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and  correlativo is not null  and estado IN (4) and (hijo is not null and hijo <> '') and fecha_aprobacion BETWEEN '$f2 00:00:00' AND '$f1 23:59:59') as r;";
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][1]["c"][2]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][1]["c"][2]["v"] = "0";
        }
        $q = "select count(*) from (select id,estado from planificacion where $fq fecha is not null and correlativo is not null and  estado IN (4) and (hijo is not null and hijo <> '') and fecha_aprobacion <= '$f3 23:59:59') as r;";
        //die($q);
        $r = $db->query($q);
        if (isset($r[0][0]["count"])) {
            $grafico_data["rows"][1]["c"][1]["v"] = $r[0][0]["count"];
        } else {
            $grafico_data["rows"][1]["c"][1]["v"] = "0";
        }


        $this->set("datos_grafico_2", $grafico_data);

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Tipo";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Trabajos";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["rows"][0]["c"][0]["v"] = "RP";
        $grafico_data["rows"][0]["c"][1]["v"] = $datos["RP"];
        $grafico_data["rows"][1]["c"][0]["v"] = "BL";
        $grafico_data["rows"][1]["c"][1]["v"] = $datos["BL"];
        $grafico_data["rows"][2]["c"][0]["v"] = "MP";
        $grafico_data["rows"][2]["c"][1]["v"] = $datos["MP"];
        $grafico_data["rows"][3]["c"][0]["v"] = "EX";
        $grafico_data["rows"][3]["c"][1]["v"] = $datos["EX"];
        $grafico_data["rows"][4]["c"][0]["v"] = "RI";
        $grafico_data["rows"][4]["c"][1]["v"] = $datos["RI"];



        $this->set("datos_grafico_4", $grafico_data);

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Tipo";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Trabajos";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["rows"][0]["c"][0]["v"] = "RP";
        $grafico_data["rows"][0]["c"][1]["v"] = $datosr["RP"];
        $grafico_data["rows"][1]["c"][0]["v"] = "BL";
        $grafico_data["rows"][1]["c"][1]["v"] = $datosr["BL"];
        $grafico_data["rows"][2]["c"][0]["v"] = "MP";
        $grafico_data["rows"][2]["c"][1]["v"] = $datosr["MP"];
        $grafico_data["rows"][3]["c"][0]["v"] = "EX";
        $grafico_data["rows"][3]["c"][1]["v"] = $datosr["EX"];
        $grafico_data["rows"][4]["c"][0]["v"] = "RI";
        $grafico_data["rows"][4]["c"][1]["v"] = $datosr["RI"];

        $this->set("datos_grafico_5", $grafico_data);
    }

    public function gestion() {
        $this->check_permissions($this);
        $this->set("titulo", "Dashboard Gestión");
        $this->loadModel('Planificacion');
        $this->loadModel('Unidad');
        $this->loadModel('Sistema');
        $this->loadModel('Subsistema');
        $this->loadModel('Elemento');
        $faena_id = intval($this->Session->read('faena_id'));

        if ($faena_id != 0) {
            $faena = "Planificacion.faena_id = $faena_id";
        } else {
            $faena = 'Planificacion.faena_id <> 0';
        }

        $time = time();
        $time = strtotime(date("Y-m", time()) . "-01");

        if (isset($this->request->data) && count($this->request->data)) {
            //$fecha = time();
            //$fecha = date('Y-m', $date);
            $time = strtotime($this->request->data['fecha'] . "-01");
            //$fecha_termino = $this->request->data['fecha_termino'];
        }

        $mes = date('Y-m', $time);

        $date = strtotime(date('Y-m-d', $time));
        $fecha_termino = strtotime(date('Y-m-d', $time) . ' +30 days');
        $fecha_termino = date('Y-m-d', $fecha_termino);
        $fecha = date('Y-m-d', $date);

        $intervenciones = $this->Planificacion->find('all', array(
            'fields' => array('Unidad.motor_id', 'Planificacion.unidad_id', 'Planificacion.faena_id', 'Planificacion.estado', 'Planificacion.tipointervencion', 'Planificacion.fecha', 'Planificacion.tiempo_trabajo', 'Planificacion.json'),
            'conditions' => array(
                $faena,
                'Planificacion.estado IN (4, 5, 6, 7)',
                "Planificacion.fecha BETWEEN '$fecha' AND '$fecha_termino'",
                //'EXTRACT(MONTH FROM DATE Planificacion.fecha) = ' . intval(date("m")),
                //'UPPER(Planificacion.tipointervencion)' => array('RP'),
                "not" => array("Planificacion.fecha" => null)),
            'order' => 'Planificacion.fecha',
            'recursive' => 1));

        // Disponibilidad Fisica
        /*
          DF = (HN - HRDET) / HN
          HN = #MOTORES * 24 * 30 (#MOTORES = #EQUIPOS)
          HRDET = DURACION TODAS LAS INTERVENCIONES
         */

        if ($faena_id != 0) {
            $faena = "faena_id = $faena_id";
        } else {
            $faena = 'faena_id <> 0';
        }
        $this->loadModel('UnidadDetalle');
        $unidad = $this->UnidadDetalle->find('all', array('fields' => array('id', 'unidad'), 'conditions' => array($faena), 'recursive' => -1));
        // HN debe ser en minutos, agregar * 60 en produccion
        $hn = count($unidad) * 24 * 30 * 60;

        $hrdet = 0;
        $data = array();
        $datac = array();
        $delta = array();
        $deltatotal = array(0, 0, 0, 0);

        foreach ($intervenciones as $intervencion) {
            if (strpos($intervencion["Planificacion"]["tiempo_trabajo"], ':') !== false) {
                $tiempo = split(":", $intervencion["Planificacion"]["tiempo_trabajo"]);
                $tiempo = intval($tiempo[0]) * 60 + intval($tiempo[1]);
            } else {
                $tiempo = 0;
            }

            if (!isset($data[$intervencion["Planificacion"]["fecha"]])) {
                $data[$intervencion["Planificacion"]["fecha"]] = $tiempo;
            } else {
                $data[$intervencion["Planificacion"]["fecha"]] += $tiempo;
            }

            if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "MP") {
                if (!isset($datac[$intervencion["Planificacion"]["fecha"]])) {
                    $datac[$intervencion["Planificacion"]["fecha"]] = $tiempo;
                } else {
                    $datac[$intervencion["Planificacion"]["fecha"]] += $tiempo;
                }
            }

            if ($intervencion["Planificacion"]["json"] != NULL && $intervencion["Planificacion"]["json"] != "") {
                $json = json_decode($intervencion["Planificacion"]["json"], TRUE);
                if (count($json) > 0 && is_array($json)) {
                    foreach ($json as $key => $value) {
                        if ($this->startsWith($key, "d") && $this->endsWith($key, "_r") && intval($value) > 0) {
                            $hora = preg_replace("/_r$/", "_h", $key);
                            $minuto = preg_replace("/_r$/", "_m", $key);
                            if (!isset($delta[$intervencion["Planificacion"]["fecha"]][intval($value)])) {
                                $delta[$intervencion["Planificacion"]["fecha"]][intval($value)] = intval($json[$hora]) * 60 + intval($json[$minuto]);
                            } else {
                                $delta[$intervencion["Planificacion"]["fecha"]][intval($value)] += intval($json[$hora]) * 60 + intval($json[$minuto]);
                            }

                            if (!isset($deltatotal[intval($value)])) {
                                $deltatotal[intval($value)] = intval($json[$hora]) * 60 + intval($json[$minuto]);
                            } else {
                                $deltatotal[intval($value)] += intval($json[$hora]) * 60 + intval($json[$minuto]);
                            }
                        }
                    }
                }

                // en EX inicio intervencion y termino intervencion es tiempo KCH
                if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "EX" && isset($json["i_i_f"]) && isset($json["i_t_f"])) {
                    $inicio = strtotime($json["i_i_f"] . " " . $json["i_i_h"] . ":" . $json["i_i_m"] . " " . $json["i_i_p"]);
                    $termino = strtotime($json["i_t_f"] . " " . $json["i_t_h"] . ":" . $json["i_t_m"] . " " . $json["i_t_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[2] += ($termino - $inicio) / 60;
                    @$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                // Se verifica si hay tiempo de conexión y desconexión y se agrega a DCC
                if (isset($json["con_f"]) && isset($json["cont_f"])) {
                    $inicio = strtotime($json["con_f"] . " " . $json["con_h"] . ":" . $json["con_m"] . " " . $json["con_p"]);
                    $termino = strtotime($json["cont_f"] . " " . $json["cont_h"] . ":" . $json["cont_m"] . " " . $json["cont_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    @$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                if (isset($json["desc_f"]) && isset($json["desct_f"])) {
                    $inicio = strtotime($json["desc_f"] . " " . $json["desc_h"] . ":" . $json["desc_m"] . " " . $json["desc_p"]);
                    $termino = strtotime($json["desct_f"] . " " . $json["desct_h"] . ":" . $json["desct_m"] . " " . $json["desct_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    @$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                if (isset($json["pp_i_f"]) && isset($json["pp_t_f"])) {
                    $inicio = strtotime($json["pp_i_f"] . " " . $json["pp_i_h"] . ":" . $json["pp_i_m"] . " " . $json["pp_i_p"]);
                    $termino = strtotime($json["pp_t_f"] . " " . $json["pp_t_h"] . ":" . $json["pp_t_m"] . " " . $json["pp_t_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    @$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                if (isset($json["pm_i_f"]) && isset($json["pm_t_f"])) {
                    $inicio = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"] . ":" . $json["pm_i_m"] . " " . $json["pm_i_p"]);
                    $termino = strtotime($json["pm_t_f"] . " " . $json["pm_t_h"] . ":" . $json["pm_t_m"] . " " . $json["pm_t_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    @$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                // < 15 minutos es DCC excp. EX (KCH)

                /* $tiempo_total = $intervencion['Planificacion']['tiempo_trabajo'];
                  $tiempo_total = split(":", $tiempo_total);
                  $tiempo_total = $tiempo_total[0] * 60 + $tiempo_total[1];

                  $tiempo_dcc = $tiempo_total - $tiempos[2] - $tiempos[3]; */
            }
        }

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Día";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Disponibilidad Contractual";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["cols"][2]["label"] = "Disponibilidad Física";
        $grafico_data["cols"][2]["type"] = "number";

        // Se definen los ultimos 30 dias con cero por defecto para generar grafico completo.
        for ($i = 0; $i < 30; $i++) {
            $grafico_data["rows"][$i]["c"][0]["v"] = sprintf("%02d", ($i + 1)) . '';
            $grafico_data["rows"][$i]["c"][1]["v"] = 100;
            $grafico_data["rows"][$i]["c"][2]["v"] = 100;
        }

        //print_r($grafico_data);
        //$i = 0;
        foreach ($data as $key => $value) {
            //$data[$key] = round(($hn - $value) / $hn * 100, 1);
            $i = intval(date("d", strtotime($key))) - 1;
            $grafico_data["rows"][$i]["c"][0]["v"] = date("d", strtotime($key)) . '';


            if (isset($datac[$key])) {
                if (!isset($delta[$key][1])) {
                    $delta[$key][1] = 0;
                }
                $grafico_data["rows"][$i]["c"][1]["v"] = round(($hn - /* $delta[$key][1] - */ $datac[$key]) / $hn * 100, 1);
            } else {
                $grafico_data["rows"][$i]["c"][1]["v"] = round(($hn - $value) / $hn * 100, 1);
            }
            $grafico_data["rows"][$i]["c"][2]["v"] = round(($hn - $value) / $hn * 100, 1);
        }
        //print_r($grafico_data);
        $this->set("datos_grafico_1", $grafico_data);

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Responsable";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Indisponibilidad";
        $grafico_data["cols"][1]["type"] = "number";

        $grafico_data["rows"][0]["c"][0]["v"] = "DCC";
        $grafico_data["rows"][0]["c"][1]["v"] = round($deltatotal[1] * 100 / $hn, 2);
        $grafico_data["rows"][1]["c"][0]["v"] = "OEM";
        $grafico_data["rows"][1]["c"][1]["v"] = round($deltatotal[2] * 100 / $hn, 2);
        $grafico_data["rows"][2]["c"][0]["v"] = "MINA";
        $grafico_data["rows"][2]["c"][1]["v"] = round($deltatotal[3] * 100 / $hn, 2);

        $this->set("datos_grafico_2", $grafico_data);

        $this->set("data", $data);
        $this->set("hn", $hn);


        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Trabajos";
        $grafico_data["cols"][0]["type"] = "number";
        $grafico_data["cols"][1]["label"] = "Faenas";
        $grafico_data["cols"][1]["type"] = "number";

        $tiempos = array();
        $cantidad = array();

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
        }

        $i = 0;
        foreach ($cantidad as $key => $value) {
            //$tiempos[$key] = $tiempos[$key] / $value;
            $grafico_data["rows"][$i]["c"][0]["v"] = $value;
            $grafico_data["rows"][$i++]["c"][1]["v"] = round($tiempos[$key] / ($value * 60), 1);
        }

        $this->set("datos_grafico_4", $grafico_data);


        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Trabajos";
        $grafico_data["cols"][0]["type"] = "number";
        $grafico_data["cols"][1]["label"] = "Equipo";
        $grafico_data["cols"][1]["type"] = "number";

        $tiempos = array();
        $cantidad = array();

        foreach ($intervenciones as $intervencion) {
            if (strpos($intervencion["Planificacion"]["tiempo_trabajo"], ':') !== false) {
                $tiempo = split(":", $intervencion["Planificacion"]["tiempo_trabajo"]);
                $tiempo = intval($tiempo[0]) * 60 + intval($tiempo[1]);
            } else {
                $tiempo = 0;
            }
            if (isset($cantidad[$intervencion["Planificacion"]["unidad_id"]])) {
                $cantidad[$intervencion["Planificacion"]["unidad_id"]]++;
            } else {
                $cantidad[$intervencion["Planificacion"]["unidad_id"]] = 1;
            }
            if (isset($tiempos[$intervencion["Planificacion"]["unidad_id"]])) {
                $tiempos[$intervencion["Planificacion"]["unidad_id"]] += $tiempo;
            } else {
                $tiempos[$intervencion["Planificacion"]["unidad_id"]] = $tiempo;
            }
        }

        $i = 0;
        foreach ($cantidad as $key => $value) {
            //$tiempos[$key] = $tiempos[$key] / $value;
            $grafico_data["rows"][$i]["c"][0]["v"] = $value;
            $grafico_data["rows"][$i++]["c"][1]["v"] = round($tiempos[$key] / ($value * 60), 1);
        }

        $this->set("datos_grafico_5", $grafico_data);

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Trabajos";
        $grafico_data["cols"][0]["type"] = "number";
        $grafico_data["cols"][1]["label"] = "Motor";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["cols"][2]["role"] = "annotation";
        $grafico_data["cols"][2]["type"] = "string";

        $tiempos = array();
        $cantidad = array();

        foreach ($intervenciones as $intervencion) {
            if (strpos($intervencion["Planificacion"]["tiempo_trabajo"], ':') !== false) {
                $tiempo = split(":", $intervencion["Planificacion"]["tiempo_trabajo"]);
                $tiempo = intval($tiempo[0]) * 60 + intval($tiempo[1]);
            } else {
                $tiempo = 0;
            }
            if (isset($cantidad[$intervencion["Unidad"]["motor_id"]])) {
                $cantidad[$intervencion["Unidad"]["motor_id"]]++;
            } else {
                $cantidad[$intervencion["Unidad"]["motor_id"]] = 1;
            }
            if (isset($tiempos[$intervencion["Unidad"]["motor_id"]])) {
                $tiempos[$intervencion["Unidad"]["motor_id"]] += $tiempo;
            } else {
                $tiempos[$intervencion["Unidad"]["motor_id"]] = $tiempo;
            }
        }

        $i = 0;
        foreach ($cantidad as $key => $value) {
            //$tiempos[$key] = $tiempos[$key] / $value;
            $grafico_data["rows"][$i]["c"][0]["v"] = $value;
            $grafico_data["rows"][$i]["c"][1]["v"] = round($tiempos[$key] / ($value * 60), 1);
            $grafico_data["rows"][$i++]["c"][2]["v"] = $key . '';
        }

        $this->set("datos_grafico_6", $grafico_data);

        /*
          var elemento = [localStorage.getItem("ele_sistema_id"),
          localStorage.getItem("ele_subsistema_id"),
          localStorage.getItem("ele_sposicion_id"),
          localStorage.getItem("ele_elemento_id"),
          localStorage.getItem("ele_posicion_id"),
          localStorage.getItem("ele_categoria_id"),
          localStorage.getItem("ele_diagnostico_id"),
          localStorage.getItem("ele_solucion_id"),
          localStorage.getItem("ele_tipo_intervencion")];
         */
        // Carga de pareto eventos por sistema
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
                        if (!isset($sistema[$elemento[0]])) {
                            $sistema[$elemento[0]] = 1;
                        } else {
                            $sistema[$elemento[0]]++;
                        }
                        if (!isset($subsistema[$elemento[1]])) {
                            $subsistema[$elemento[1]] = 1;
                        } else {
                            $subsistema[$elemento[1]]++;
                        }
                        if (!isset($elementos[$elemento[3]])) {
                            $elementos[$elemento[3]] = 1;
                        } else {
                            $elementos[$elemento[3]]++;
                        }
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
        $this->set("datos_grafico_7", $grafico_data);

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Subsistema";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Eventos";
        $grafico_data["cols"][1]["type"] = "number";
        $i = 0;
        foreach ($subsistema as $key => $value) {
            //$tiempos[$key] = $tiempos[$key] / $value;
            $subsistema = $this->Subsistema->find('first', array('conditions' => array("id = $key"), 'recursive' => -1));
            $grafico_data["rows"][$i]["c"][0]["v"] = $subsistema["Subsistema"]["nombre"];
            $grafico_data["rows"][$i++]["c"][1]["v"] = $value;
        }
        $this->set("datos_grafico_8", $grafico_data);

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Elemento";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Eventos";
        $grafico_data["cols"][1]["type"] = "number";
        $i = 0;
        foreach ($elementos as $key => $value) {
            if (!is_numeric($key))
                continue;
            //$tiempos[$key] = $tiempos[$key] / $value;
            $elemento = $this->Elemento->find('first', array('conditions' => array("id = $key"), 'recursive' => -1));
            $grafico_data["rows"][$i]["c"][0]["v"] = $elemento["Elemento"]["nombre"];
            $grafico_data["rows"][$i++]["c"][1]["v"] = $value;
        }
        $this->set("datos_grafico_9", $grafico_data);
        // % Detenciones DCC
        //$det_dcc = $deltatotal[1] / $hn;
        //print_r($delta);

        /* Generacion del Filtro */


        $query = "";
        $estado = "0";
        $tipo_intervencion = "";
        $flota_id = "";
        $unidad_id = "";
        $tipo_evento = "";
        /*
          if ($faena_id != 0) {
          $faena = "faena_id = $faena_id";
          } else {
          $faena = 'faena_id <> 0';
          } */

        $this->loadModel('UnidadDetalle');
        $flotas = $this->UnidadDetalle->find('all', array('fields' => array('DISTINCT flota_id', 'flota'), 'order' => 'flota', 'conditions' => array($faena), 'recursive' => -1));

        $this->set('unidad_id', $unidad_id);
        $this->set('flota_id', $flota_id);
        $this->set('flotas', $flotas);
        $this->set('tipo_intervencion', $tipo_intervencion);
        $this->set('fecha', $fecha);
        $this->set('fecha_termino', $fecha_termino);
        $this->set('tipo_evento', $tipo_evento);
        $this->set('mes', $mes);
    }

    public function gestion_ajax_pie_indisponibilidad($dia, $fecha) {
        $this->loadModel('Planificacion');
        $this->loadModel('Unidad');
        $faena_id = intval($this->Session->read('faena_id'));
        $this->layout = null;
        if ($faena_id != 0) {
            $faena = "faena_id = $faena_id";
        } else {
            $faena = 'faena_id <> 0';
        }

        $time = time();
        $time = strtotime($fecha . "-" . $dia);
        $fecha_termino = date('Y-m-d', $time);
        $fecha = date('Y-m-d', $time);

        $intervenciones = $this->Planificacion->find('all', array(
            'fields' => array('Planificacion.unidad_id', 'Planificacion.estado', 'Planificacion.tipointervencion', 'Planificacion.fecha', 'Planificacion.tiempo_trabajo', 'Planificacion.json'),
            'conditions' => array(
                $faena,
                'Planificacion.estado IN (4, 5, 6, 7)',
                "Planificacion.fecha BETWEEN '$fecha' AND '$fecha_termino'",
                //'EXTRACT(MONTH FROM DATE Planificacion.fecha) = ' . intval(date("m")),
                //'UPPER(Planificacion.tipointervencion)' => array('RP'),
                "not" => array("Planificacion.fecha" => null)),
            'order' => 'Planificacion.fecha',
            'recursive' => -1));

        $this->loadModel('UnidadDetalle');
        $unidad = $this->UnidadDetalle->find('all', array('fields' => array('id', 'unidad'), 'conditions' => array($faena), 'recursive' => -1));
        // HN debe ser en minutos, agregar * 60 en produccion
        $hn = count($unidad) * 24 * 30;

        $hrdet = 0;
        $data = array();
        $datac = array();
        $delta = array();
        $deltatotal = array(0, 0, 0, 0);

        foreach ($intervenciones as $intervencion) {

            // Filtramos dias distintos a $dia
            if (intval(date("d", strtotime($intervencion["Planificacion"]["fecha"]))) != intval($dia)) {
                continue;
            }

            if (strpos($intervencion["Planificacion"]["tiempo_trabajo"], ':') !== false) {
                $tiempo = split(":", $intervencion["Planificacion"]["tiempo_trabajo"]);
                $tiempo = intval($tiempo[0]) * 60 + intval($tiempo[1]);
            } else {
                $tiempo = 0;
            }

            if (!isset($data[$intervencion["Planificacion"]["fecha"]])) {
                $data[$intervencion["Planificacion"]["fecha"]] = $tiempo;
            } else {
                $data[$intervencion["Planificacion"]["fecha"]] += $tiempo;
            }

            if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "MP") {
                if (!isset($datac[$intervencion["Planificacion"]["fecha"]])) {
                    $datac[$intervencion["Planificacion"]["fecha"]] = $tiempo;
                } else {
                    $datac[$intervencion["Planificacion"]["fecha"]] += $tiempo;
                }

                if (!isset($delta[$intervencion["Planificacion"]["fecha"]][1])) {
                    $deltatotal[1] = $tiempo;
                } else {
                    $deltatotal[1] += $tiempo;
                }

                continue;
            }

            /* if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "EX") {
              if (!isset($datac[$intervencion["Planificacion"]["fecha"]])) {
              $datac[$intervencion["Planificacion"]["fecha"]] = $tiempo;
              } else {
              $datac[$intervencion["Planificacion"]["fecha"]] += $tiempo;
              }

              if (!isset($delta[$intervencion["Planificacion"]["fecha"]][intval($value)])) {
              $delta[$intervencion["Planificacion"]["fecha"]][1] = $tiempo;
              } else {
              $delta[$intervencion["Planificacion"]["fecha"]][1] += $tiempo;
              }

              continue;
              } */

            if ($intervencion["Planificacion"]["json"] != null) {
                $json = json_decode($intervencion["Planificacion"]["json"], true);
                foreach ($json as $key => $value) {
                    if ($this->startsWith($key, "d") && $this->endsWith($key, "_r") && intval($value) > 0) {
                        $hora = preg_replace("/_r$/", "_h", $key);
                        $minuto = preg_replace("/_r$/", "_m", $key);
                        if (!isset($delta[$intervencion["Planificacion"]["fecha"]][intval($value)])) {
                            $delta[$intervencion["Planificacion"]["fecha"]][intval($value)] = intval($json[$hora]) * 60 + intval($json[$minuto]);
                        } else {
                            $delta[$intervencion["Planificacion"]["fecha"]][intval($value)] += intval($json[$hora]) * 60 + intval($json[$minuto]);
                        }

                        if (!isset($deltatotal[intval($value)])) {
                            $deltatotal[intval($value)] = intval($json[$hora]) * 60 + intval($json[$minuto]);
                        } else {
                            $deltatotal[intval($value)] += intval($json[$hora]) * 60 + intval($json[$minuto]);
                        }
                    }
                }

                // en EX inicio intervencion y termino intervencion es tiempo KCH
                if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "EX") {
                    $inicio = strtotime($json["i_i_f"] . " " . $json["i_i_h"] . ":" . $json["i_i_m"] . " " . $json["i_i_p"]);
                    $termino = strtotime($json["i_t_f"] . " " . $json["i_t_h"] . ":" . $json["i_t_m"] . " " . $json["i_t_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[2] += ($termino - $inicio) / 60;
                    @$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                // Se verifica si hay tiempo de conexión y desconexión y se agrega a DCC
                if (isset($json["con_f"]) && isset($json["cont_f"])) {
                    $inicio = strtotime($json["con_f"] . " " . $json["con_h"] . ":" . $json["con_m"] . " " . $json["con_p"]);
                    $termino = strtotime($json["cont_f"] . " " . $json["cont_h"] . ":" . $json["cont_m"] . " " . $json["cont_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    @$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                if (isset($json["desc_f"]) && isset($json["desct_f"])) {
                    $inicio = strtotime($json["desc_f"] . " " . $json["desc_h"] . ":" . $json["desc_m"] . " " . $json["desc_p"]);
                    $termino = strtotime($json["desct_f"] . " " . $json["desct_h"] . ":" . $json["desct_m"] . " " . $json["desct_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    @$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                if (isset($json["pp_i_f"]) && isset($json["pp_t_f"])) {
                    $inicio = strtotime($json["pp_i_f"] . " " . $json["pp_i_h"] . ":" . $json["pp_i_m"] . " " . $json["pp_i_p"]);
                    $termino = strtotime($json["pp_t_f"] . " " . $json["pp_t_h"] . ":" . $json["pp_t_m"] . " " . $json["pp_t_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    @$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                if (isset($json["pm_i_f"]) && isset($json["pm_t_f"])) {
                    $inicio = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"] . ":" . $json["pm_i_m"] . " " . $json["pm_i_p"]);
                    $termino = strtotime($json["pm_t_f"] . " " . $json["pm_t_h"] . ":" . $json["pm_t_m"] . " " . $json["pm_t_p"]);
                    //$minutos = ($termino - $inicio) / 60;
                    $deltatotal[1] += ($termino - $inicio) / 60;
                    @$delta[$intervencion["Planificacion"]["fecha"]][1] += ($termino - $inicio) / 60;
                }

                // < 15 minutos es DCC excp. EX (KCH)

                /* $tiempo_total = $intervencion['Planificacion']['tiempo_trabajo'];
                  $tiempo_total = split(":", $tiempo_total);
                  $tiempo_total = $tiempo_total[0] * 60 + $tiempo_total[1];

                  $tiempo_dcc = $tiempo_total - $tiempos[2] - $tiempos[3]; */
            }
        }

        $grafico_data = NULL;
        $grafico_data = array();
        $grafico_data["cols"][0]["label"] = "Responsable";
        $grafico_data["cols"][0]["type"] = "string";
        $grafico_data["cols"][1]["label"] = "Indisponibilidad";
        $grafico_data["cols"][1]["type"] = "number";
        $grafico_data["rows"][0]["c"][0]["v"] = "DCC";
        $grafico_data["rows"][0]["c"][1]["v"] = isset($deltatotal[1]) ? round($deltatotal[1] * 100 / $hn, 2) : 0;
        $grafico_data["rows"][1]["c"][0]["v"] = "OEM";
        $grafico_data["rows"][1]["c"][1]["v"] = isset($deltatotal[2]) ? round($deltatotal[2] * 100 / $hn, 2) : 0;
        $grafico_data["rows"][2]["c"][0]["v"] = "MINA";
        $grafico_data["rows"][2]["c"][1]["v"] = isset($deltatotal[3]) ? round($deltatotal[3] * 100 / $hn, 2) : 0;

        if ($deltatotal[3] > 0 || $deltatotal[2] > 0 || $deltatotal[1] > 0) {
            $this->set("datos_grafico", json_encode($grafico_data));
        } else {
            $this->set("datos_grafico", "0");
        }
    }

    function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }

    public function metronic() {
        $this->layout = 'metronic_principal';
    }

}

?>