<?php

App::uses('ConnectionManager', 'Model');
App::import('Vendor', 'Classes/PHPExcel');
App::import('Controller', 'Utilidades');
App::import('Controller', 'UtilidadesReporte');
App::uses('Sanitize', 'Utility');
/*
  Esta clase define el despliegue de los backlog existentes en la base de datos.
 */

class BacklogController extends AppController {

    public $scaffold = 'admin';

    /*
      Este metodo despliega todos los backlog no realizados para planificarlos, se llamda desde el despliegue de Planificacion a traves de ajax.
     */

    public function select($id) {
        $this->layout = null;
        $this->loadModel('Backlog');
        $backlogs = $this->Backlog->find('all',
            /* array('order' => 'Backlog.criticidad ASC, Backlog.fecha DESC',
              'fields' => array('equipo_id','Backlog.realizado','Backlog.e','Backlog.criticidad','Backlog.fecha','Backlog.comentario','Backlog.id','Sistema_Motor.*'),
              'conditions' => array('equipo_id' => $id, 'Backlog.realizado IS NULL', 'Backlog.e'=>'1'),
              'recursive' => 2));
             */
            array('order' => 'Backlog.criticidad_id ASC, Backlog.fecha_creacion DESC',
                'fields' => array('Unidad.unidad', 'Backlog.criticidad_id', 'Backlog.fecha_creacion', 'Backlog.comentario', 'Backlog.id', 'Sistema.nombre', 'Subsistema.nombre', 'Backlog.responsable_id', 'Backlog.categoria_sintoma_id', 'Backlog.sintoma_id'),
                //'conditions' => array('Backlog.equipo_id' => $id, 'Backlog.realizado IS NULL', 'Backlog.e'=>'1','Backlog.fecha IS NOT NULL'),
                'conditions' => array('Backlog.equipo_id' => $id, 'Backlog.estado_id' => '8', 'Backlog.e' => '1', 'Backlog.fecha_creacion IS NOT NULL'),
                'recursive' => 1));
        $this->set('backlog', $backlogs);
    }

    public function selectUnidadIntervencion($id, $idintervencion) {
        $this->layout = null;
        $this->loadModel('Backlog');
        $backlogs = $this->Backlog->find('all',
            /* array('order' => 'Backlog.criticidad ASC, Backlog.fecha DESC',
              'fields' => array('equipo_id','Backlog.realizado','Backlog.e','Backlog.criticidad','Backlog.fecha','Backlog.comentario','Backlog.id','Sistema_Motor.*'),
              'conditions' => array('equipo_id' => $id, 'Backlog.realizado IS NULL', 'Backlog.e'=>'1'),
              'recursive' => 2));
             */
            array('order' => 'Backlog.criticidad_id ASC, Backlog.fecha_creacion DESC',
                'fields' => array('Unidad.unidad', 'Backlog.criticidad_id', 'Backlog.fecha_creacion', 'Backlog.comentario', 'Backlog.id', 'Sistema.nombre', 'Subsistema.nombre', 'Backlog.responsable_id'),
                //'conditions' => array('Backlog.equipo_id' => $id, 'Backlog.realizado IS NULL', 'Backlog.e'=>'1','Backlog.fecha IS NOT NULL'),
                'conditions' => array('Backlog.equipo_id' => $id, ('Backlog.estado_id = 8 OR Backlog.intervencion_id =' . $idintervencion), 'Backlog.e' => '1', 'Backlog.fecha_creacion IS NOT NULL'),
                'recursive' => 1));
        $this->set('backlog', $backlogs);
    }

    public function administrar() {
        $this->loadModel('Backlog');
        $this->loadModel('Planificacion');
        $this->check_permissions($this);

        $backlogs = $this->Backlog->find('all', array(
            'fields' => array('Backlog.id', 'Sistema.nombre', 'Sistema.id'),
            'conditions' => array("Backlog.sist_id IS NULL"),
            'recursive' => 1
        ));
        //$this->set('backlogs',$backlogs);
        foreach ($backlogs as $backlog) {
            $data = array();
            //$data["id"] = $backlog["Backlog"]["id"];
            //$data["sist_id"] = $backlog["Sistema"]["nombre"];
            //$this->Backlog->save($data);
        }

        /* $backlogs=$this->Backlog->find('all', array(
          'fields' => array('Backlog.id', 'Backlog.folio_programador', 'Intervencion.estado', 'Backlog.estado_id'),
          'conditions' => array("Backlog.folio_programador IS NOT NULL"),
          'recursive' => 1
          )); */

        //print_r($backlogs);
        //foreach($backlogs as $backlog){
        //	if($backlog["Intervencion"]["estado"]!=$backlog["Backlog"]["estado_id"]){
        //		$data = array();
        //$data["id"] = $backlog["Backlog"]["id"];
        //$data["estado_id"] = $backlog["Intervencion"]["estado"];
        //$this->Backlog->save($data);
        //print_r($data);
        //	}
        /*
          $planificacion=$this->Planificacion->find('first', array(
          'fields' => array('Planificacion.estado'),
          'conditions' => array("Planificacion.id = {$backlog["Backlog"]["folio_programador"]}"),
          'recursive' => -1
          ));
          print_r($planificacion);
         */
        //}


        $usuario = $this->Session->read('Usuario');
        $nivel = $this->Session->read('Nivel');
        if ($usuario == NULL || ($nivel != 2 && $nivel != 4 && $nivel != 7)) {
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        $fecha = date('Y-m-d', time() - 365 * 24 * 60 * 60);
        $fecha_termino = date('Y-m-d');
        $faena_id = $this->Session->read('faena_id');
        $this->set('titulo', "Backlogs");
        //$this->loadModel('Backlog');

        $this->loadModel('Sistema');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('Flota');
        $this->loadModel('UnidadDetalle');
        $this->loadModel('Sistema_Motor');
        $url = "1";
        $query = "";
        $unidad_id = "";
        $flota_id = "";
        $criticidad = "";
        $folio = "";
        $sistema_id = "";
        $faena_id == '0';
        $equipoid = "";
        $e = "";
        $estado = "";
        $motor_id = "";
        $faenas = $this->Session->read('faenas');
        $estado_id = "0";
        if (isset($this->request->query) && count($this->request->query) > 0) {
            if (isset($this->request->query['fecha']) && $this->request->query['fecha'] != "") {
                $fecha = $this->request->query['fecha'];
                $url .= "&fecha=$fecha";
            }
            if (isset($this->request->query['motor_id']) && $this->request->query['motor_id'] != "") {
                $motor_id = $this->request->query['motor_id'];
            }

            if (isset($this->request->query['estado_id']) && $this->request->query['estado_id'] != "") {
                $estado_id = $this->request->query['estado_id'];
                $url .= "&estado=$estado_id";
                if ($estado_id == "" || $estado_id == "0") {
                    $estado = "";
                } else {
                    $estado = "Backlog.estado_id = $estado_id";
                }
            }

            if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != "") {
                $fecha_termino = $this->request->query['fecha_termino'];
                $url .= "&fecha_termino=$fecha_termino";
            }

            if (isset($this->request->query['criticidad']) && $this->request->query['criticidad'] != "") {
                $query = "Backlog.criticidad = " . intval($this->request->query['criticidad']) . " AND ";
                $criticidad = $this->request->query['criticidad'];
                $url .= "&criticidad=$criticidad";
            }

            if (isset($this->request->query['folio']) && $this->request->query['folio'] != "") {
                $folio = $this->request->query['folio'];
                $query = "(Backlog.id = $folio OR Backlog.folio_programador = $folio OR Backlog.folio_creador = $folio) AND ";
                $url .= "&folio=$folio";
            }

            if (isset($this->request->query['folio_programador']) && $this->request->query['folio_programador'] != "") {
                $query = "Backlog.folio_programador = " . intval($this->request->query['folio_programador']) . " AND ";
                $folio_programador = $this->request->query['folio_programador'];
                $url .= "&folio_programador=$folio_programador";
            }

            if (isset($this->request->query['folio_creador']) && $this->request->query['folio_creador'] != "") {
                $query = "Backlog.folio_creador = " . intval($this->request->query['folio_creador']) . " AND ";
                $folio_creador = $this->request->query['folio_creador'];
                $url .= "&folio_creador=$folio_creador";
            }

            if (isset($this->request->query['flotaid']) && $this->request->query['flotaid'] != "" && $this->request->query['flotaid'] != "0") {
                $query_flota = "Unidad.flota_id = " . intval($this->request->query['flotaid']) . " ";
                $flota_id = $this->request->query['flotaid'];
                $url .= "&flotaid=$flota_id";
            }

            if (isset($this->request->query['sistemaid']) && $this->request->query['sistemaid'] != "" && $this->request->query['sistemaid'] != "0") {
                $query = "Backlog.sist_id = " . intval($this->request->query['sistemaid']) . " AND ";
                $sistema_id = $this->request->query['sistemaid'];
                $url .= "&sistemaid=$sistema_id";
            }
            if (isset($this->request->query['faenaid']) && $this->request->query['faenaid'] != "" && $this->request->query['faenaid'] != "0") {
                $query_faena = "Backlog.faena_id IN (" . intval($this->request->query['faenaid']) . ") ";
                $faena_id = $this->request->query['faenaid'];
                $url .= "&faena_id=$faena_id";
            }
            if (isset($this->request->query['equipoid']) && $this->request->query['equipoid'] != "" && $this->request->query['equipoid'] != "0") {
                $query_equipo = "Backlog.equipo_id = " . intval($this->request->query['equipoid']) . " ";
                $equipoid = $this->request->query['equipoid'];
                $url .= "&equipoid=$equipoid";
            }
            if (isset($this->request->query['e']) && $this->request->query['e'] != "") {
                $query = "Backlog.e = '" . $this->request->query['e'] . "' AND ";
                $e = $this->request->query['e'];
                $url .= "&e=$e";
            }

            if (@$query_equipo != "") {
                $query_flota = "";
            }
        }

        $faenas_id = array();
        if ($faena_id == '0') {
            foreach ($faenas as $key => $value) {
                $faenas_id[] = $value["Faena"]["id"];
            }
        } else {
            $faenas_id[] = $faena_id;
        }
        if (count($faenas_id) > 0) {
            $faenas_id = implode(",", $faenas_id);
        }

        /* if($estado!=""&&$estado!="1"){
          $this->paginate = array(
          'fields' => array('Planificacion.backlog_id','Backlog.id','Backlog.comentario','Backlog.e','Backlog.ts','Faena.nombre','Backlog.fecha','Unidad.unidad','Backlog.criticidad',"Backlog.folio_creador","Backlog.folio_programador",'Backlog.realizado','Backlog.sistema_id','Sistema.nombre','Estado.nombre'),
          'conditions' => array("Backlog.faena_id IN ($faenas_id) AND $query 1 = 1","Backlog.fecha BETWEEN '$fecha' AND '$fecha_termino' AND Planificacion.estado=$estado"),
          'limit' => 25,
          'order' => 'Backlog.fecha DESC,Backlog.criticidad ASC',
          'recursive' => 1
          );
          $resultados = $this->paginate('Planificacion');
          }else{ */
        if ($nivel != 2 || $faena_id == '0') {
            $this->paginate = array(
                'fields' => array('Backlog.id', 'Backlog.comentario', 'Backlog.e', 'Backlog.ts', 'Backlog.fecha', 'Unidad.unidad', 'Backlog.criticidad', 'Backlog.sistema_id', "Backlog.folio_creador", "Backlog.folio_programador", 'Backlog.realizado', 'Sistema.nombre', 'Backlog.estado_id', 'Backlog.responsable_id'),
                'conditions' => array("$query 1 = 1", "Backlog.fecha BETWEEN '$fecha' AND '$fecha_termino'", $estado, @$query_equipo, @$query_faena, @$query_flota),
                'limit' => 25,
                'order' => 'Backlog.fecha DESC,Backlog.criticidad ASC',
                'recursive' => 1
            );
            $resultados = $this->paginate('Backlog');
        } else {
            $this->paginate = array(
                'fields' => array('Backlog.id', 'Backlog.comentario', 'Backlog.e', 'Backlog.ts', 'Backlog.fecha', 'Unidad.unidad', 'Backlog.criticidad', 'Backlog.sistema_id', "Backlog.folio_creador", "Backlog.folio_programador", 'Backlog.realizado', 'Sistema.nombre', 'Backlog.estado_id', 'Backlog.responsable_id'),
                'conditions' => array("$query 1 = 1", "Backlog.fecha BETWEEN '$fecha' AND '$fecha_termino'", $estado, @$query_equipo, @$query_faena, @$query_flota),
                'limit' => 25,
                'order' => 'Backlog.fecha DESC,Backlog.criticidad ASC',
                'recursive' => 1
            );
            $resultados = $this->paginate('Backlog');
        }
        //}
        $this->set('resultado', $resultados);
        $this->set('fecha', $fecha);
        $this->set('fecha_termino', $fecha_termino);
        $this->set('unidad_id', $unidad_id);
        $this->set('flota_id', $flota_id);
        $this->set('sistema_id', $sistema_id);
        //$this->set('flota_id', $flota_id);
        $this->set('faena_id', $faena_id);
        $this->set('e', $e);
        $this->set('criticidad', $criticidad);
        $this->set('equipo_id', $equipoid);
        $this->set('folio', $folio);
        $this->set('url', $url);
        $this->set('motor_id', $motor_id);
        //$this->set('folio_creador', $folio_creador);
        //$this->set('folio_programador', $folio_programador);
        $this->set('estado_id', $estado_id);
        //$this->set('faenas', $this->Faena->find('all', array('order' => 'nombre','conditions'=>array("Faena.e='1'"), 'recursive' => -1)));
        $this->set('sistemas', $this->Sistema_Motor->find('all', array('order' => 'Sistema.nombre', 'conditions' => array("Sistema.e='1'"), 'recursive' => 1)));
        $this->set('flotas', $this->UnidadDetalle->find('all', array('fields' => array('DISTINCT flota_id', 'flota', 'faena_id', 'motor_id'), 'order' => 'flota', 'recursive' => -1)));
        //$this->set('flotas', $flotas);
        //$this->set('flotas', $this->Flota->find('all', array('order' => 'nombre','conditions'=>array("Flota.e='1'"), 'recursive' => -1)));
        $this->set('equipos', $this->Unidad->find('all', array('order' => 'unidad', 'conditions' => array("Unidad.e='1'"), 'recursive' => -1)));
    }

    public function xls() {
        $this->layout = null;
        $this->loadModel('Backlog');
        $this->loadModel('Planificacion');

        $usuario = $this->Session->read('Usuario');
        $nivel = $this->Session->read('Nivel');
        if ($usuario == NULL || ($nivel != 2 && $nivel != 4 && $nivel != 7)) {
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        $fecha = date('Y-m-d', time() - 365 * 24 * 60 * 60);
        $fecha_termino = date('Y-m-d');
        $faena_id = $this->Session->read('faena_id');
        $this->set('titulo', "Backlogs");
        //$this->loadModel('Backlog');

        $this->loadModel('Sistema');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('Flota');
        $this->loadModel('UnidadDetalle');
        $this->loadModel('Sistema_Motor');
        $url = "1";
        $query = "";
        $unidad_id = "";
        $flota_id = "";
        $criticidad = "";
        $folio = "";
        $sistema_id = "";
        $faena_id = '0';
        $equipoid = "";
        $e = "";
        $estado = "";
        $motor_id = "";
        $faenas = $this->Session->read('faenas');
        $estado_id = "0";
        if (isset($this->request->query) && count($this->request->query) > 0) {
            if (isset($this->request->query['fecha']) && $this->request->query['fecha'] != "") {
                $fecha = $this->request->query['fecha'];
                $url .= "&fecha=$fecha";
            }
            if (isset($this->request->query['motor_id']) && $this->request->query['motor_id'] != "") {
                $motor_id = $this->request->query['motor_id'];
            }

            if (isset($this->request->query['estado_id']) && $this->request->query['estado_id'] != "") {
                $estado_id = $this->request->query['estado_id'];
                $url .= "&estado_id=$estado_id";
                if ($estado_id == "" || $estado_id == "0") {
                    $estado = "";
                } else {
                    $estado = "Backlog.estado_id = $estado_id";
                }
            }

            if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != "") {
                $fecha_termino = $this->request->query['fecha_termino'];
                $url .= "&fecha_termino=$fecha_termino";
            }

            if (isset($this->request->query['criticidad']) && $this->request->query['criticidad'] != "") {
                $query = "Backlog.criticidad = " . intval($this->request->query['criticidad']) . " AND ";
                $criticidad = $this->request->query['criticidad'];
                $url .= "&criticidad=$criticidad";
            }

            if (isset($this->request->query['folio']) && $this->request->query['folio'] != "") {
                $folio = $this->request->query['folio'];
                $query = "(Backlog.id = $folio OR Backlog.folio_programador = $folio OR Backlog.folio_creador = $folio) AND ";
                $url .= "&folio=$folio";
            }

            if (isset($this->request->query['folio_programador']) && $this->request->query['folio_programador'] != "") {
                $query = "Backlog.folio_programador = " . intval($this->request->query['folio_programador']) . " AND ";
                $folio_programador = $this->request->query['folio_programador'];
                $url .= "&folio_programador=$folio_programador";
            }

            if (isset($this->request->query['folio_creador']) && $this->request->query['folio_creador'] != "") {
                $query = "Backlog.folio_creador = " . intval($this->request->query['folio_creador']) . " AND ";
                $folio_creador = $this->request->query['folio_creador'];
                $url .= "&folio_creador=$folio_creador";
            }

            if (isset($this->request->query['flotaid']) && $this->request->query['flotaid'] != "" && $this->request->query['flotaid'] != "0") {
                $query_flota = "Unidad.flota_id = " . intval($this->request->query['flotaid']) . " ";
                $flota_id = $this->request->query['flotaid'];
                $url .= "&flotaid=$flota_id";
            }

            if (isset($this->request->query['sistemaid']) && $this->request->query['sistemaid'] != "" && $this->request->query['sistemaid'] != "0") {
                $query = "Backlog.sist_id = " . intval($this->request->query['sistemaid']) . " AND ";
                $sistema_id = $this->request->query['sistemaid'];
                $url .= "&sistemaid=$sistema_id";
            }
            if (isset($this->request->query['faenaid']) && $this->request->query['faenaid'] != "" && $this->request->query['faenaid'] != "0") {
                $query_faena = "Backlog.faena_id IN (" . intval($this->request->query['faenaid']) . ") ";
                $faena_id = $this->request->query['faenaid'];
                $url .= "&faena_id=$faena_id";
            }
            if (isset($this->request->query['equipoid']) && $this->request->query['equipoid'] != "" && $this->request->query['equipoid'] != "0") {
                $query_equipo = "Backlog.equipo_id = " . intval($this->request->query['equipoid']) . " ";
                $equipoid = $this->request->query['equipoid'];
                $url .= "&equipoid=$equipoid";
            }
            if (isset($this->request->query['e']) && $this->request->query['e'] != "") {
                $query = "Backlog.e = '" . $this->request->query['e'] . "' AND ";
                $e = $this->request->query['e'];
                $url .= "&e=$e";
            }

            if (@$query_equipo != "") {
                $query_flota = "";
            }
        }

        $faenas_id = array();
        if ($faena_id == '0') {
            foreach ($faenas as $key => $value) {
                $faenas_id[] = $value["Faena"]["id"];
            }
        } else {
            $faenas_id[] = $faena_id;
        }
        if (count($faenas_id) > 0) {
            $faenas_id = implode(",", $faenas_id);
        }

        /*
          if($nivel!=2||$faena_id=='0'){
          $resultados = $this->Backlog->find('all', array(
          'fields' => array('Backlog.id','Backlog.comentario','Backlog.e','Backlog.ts','Faena.nombre','Backlog.fecha','Unidad.unidad','Backlog.criticidad','Backlog.sistema_id',"Backlog.folio_creador","Backlog.folio_programador",'Backlog.realizado','Sistema.nombre','Estado.nombre','Backlog.estado_id','Flota.nombre'),
          'conditions' => array("Backlog.faena_id IN ($faenas_id) AND $query 1 = 1","Backlog.fecha BETWEEN '$fecha' AND '$fecha_termino'",$estado,@$query_equipo,@$query_faena,@$query_flota),
          //'limit' => 25,
          'order' => 'Backlog.fecha DESC,Backlog.criticidad ASC',
          'recursive' => 1
          ));
          }else{
          $resultados = $this->Backlog->find('all', array(
          'fields' => array('Backlog.id','Backlog.comentario','Backlog.e','Backlog.ts','Faena.nombre','Backlog.fecha','Unidad.unidad','Backlog.criticidad','Backlog.sistema_id',"Backlog.folio_creador","Backlog.folio_programador",'Backlog.realizado','Sistema.nombre','Estado.nombre','Backlog.estado_id','Flota.nombre'),
          'conditions' => array("Backlog.faena_id IN ($faenas_id) AND $query 1 = 1","Backlog.fecha BETWEEN '$fecha' AND '$fecha_termino'",$estado,@$query_equipo,@$query_faena,@$query_flota),
          //'limit' => 25,
          'order' => 'Backlog.fecha DESC,Backlog.criticidad ASC',
          'recursive' => 1
          ));
          } */

        $resultados = $this->Backlog->find('all', array(
            'fields' => array('Backlog.id', 'Backlog.comentario', 'Backlog.e', 'Backlog.ts', 'Faena.nombre', 'Backlog.fecha', 'Unidad.unidad', 'Backlog.criticidad', 'Backlog.sistema_id', "Backlog.folio_creador", "Backlog.folio_programador", 'Backlog.realizado', 'Sistema.nombre', 'Estado.nombre', 'Backlog.estado_id', 'Flota.nombre'),
            'conditions' => array("Backlog.faena_id IN ($faenas_id) AND $query 1 = 1", "Backlog.fecha BETWEEN '$fecha' AND '$fecha_termino'", $estado, @$query_equipo, @$query_faena, @$query_flota),
            //'limit' => 25,
            'order' => 'Backlog.fecha DESC,Backlog.criticidad ASC',
            'recursive' => 1
        ));

        $this->set('resultado', $resultados);
        /* $this->set('fecha', $fecha);
          $this->set('fecha_termino', $fecha_termino);
          $this->set('unidad_id', $unidad_id);
          $this->set('flota_id', $flota_id);
          $this->set('sistema_id', $sistema_id);
          $this->set('faena_id', $faena_id);
          $this->set('e', $e);
          $this->set('criticidad', $criticidad);
          $this->set('equipo_id', $equipoid);
          $this->set('folio', $folio);
          $this->set('motor_id', $motor_id);
          $this->set('estado_id', $estado_id); */
    }

    public function e($id, $state) {
        if ($id != "" && is_numeric($id) && ($state == "1" || $state == "0")) {
            $data = array('id' => $id, 'e' => $state);
            $this->loadModel('Backlog');
            $this->Backlog->save($data);
            $this->redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    public function nw() {
        $usuario = $this->Session->read('Usuario');
        $this->loadModel('Backlog');
        $this->loadModel('Sistema');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('Flota');
        $this->loadModel('Sistema_Motor');
        $this->layout = null;

        //print_r($this->request->data);
        //exit;

        $unidad = $this->Unidad->find('first', array('conditions' => array("Unidad.id={$this->request->data['nuid']}"), 'recursive' => -1));
        $sistema_motor = $this->Sistema_Motor->find('first', array(
            'fields' => array('Sistema_Motor.id', 'Sistema_Motor.sistema_id', 'Sistema_Motor.motor_id'),
            'conditions' => array("Sistema_Motor.sistema_id={$this->request->data['sist_id']} AND Sistema_Motor.motor_id={$unidad['Unidad']['motor_id']}"),
            'recursive' => -1
        ));

        if (!isset($sistema_motor["Sistema_Motor"]["id"])) {
            $data = array();
            $data["sistema_id"] = $this->request->data['sist_id'];
            $data["motor_id"] = $unidad['Unidad']['motor_id'];
            $this->Sistema_Motor->create();
            $this->Sistema_Motor->save($data);
            $this->Sistema_Motor->id;
            $sistema_id = $this->Sistema_Motor->id;
        } else {
            $sistema_id = $sistema_motor["Sistema_Motor"]["id"];
        }

        $data = array();
        $data["faena_id"] = $this->request->data['fid'];
        $data["equipo_id"] = $this->request->data['nuid'];
        $data["fecha"] = $this->request->data['fecha'];
        $data["comentario"] = $this->request->data['comentario'];
        $data["criticidad"] = $this->request->data['criticidad'];
        $data["sist_id"] = $this->request->data['sist_id'];
        $data["sistema_id"] = $sistema_id;
        $data["tid"] = $usuario["id"];
        $this->Backlog->create();
        $this->Backlog->save($data);
        $this->redirect($_SERVER["HTTP_REFERER"]);
    }

    public function create() {
        $usuario = $this->Session->read('Usuario');
        $this->loadModel('Backlog');
        if (isset($this->request->data) && count($this->request->data) > 0) {
            $data = array();
            $data["estado_id"] = '8';
            $data["criticidad"] = $this->request->data["criticidad"];
            $data["responsable_id"] = $this->request->data["responsable_id"];
            $data["sistema_id"] = $this->request->data["sistemaid"];
            $data["faena_id"] = @$this->request->data["faenaid"];
            $data["flota_id"] = $this->request->data["flotaid"];
            $data["fecha"] = $this->request->data["fecha"];
            $data["equipo_id"] = $this->request->data["equipoid"];
            $data["comentario"] = $this->request->data["comentario"];

            if (@$this->request->data["faenaid"] == '') {
                if ($this->Session->read('faena_id') != "" && $this->Session->read('faena_id') != "0") {
                    $data["faena_id"] = $this->Session->read('faena_id');
                }
            }

            $this->Backlog->create();
            $this->Backlog->save($data);
            $this->redirect("/Backlog/administrar");
            exit;
        } else {
            $this->loadModel('UnidadDetalle');
            $this->loadModel('Unidad');
            $this->loadModel('Sistema');
            $this->loadModel('Sistema_Motor');
            $this->set('titulo', "Crear Backlog");
            $this->set('flotas', $this->UnidadDetalle->find('all', array('fields' => array('DISTINCT flota_id', 'flota', 'faena_id', 'motor_id'), 'order' => 'flota', 'recursive' => -1)));
            $this->set('equipos', $this->Unidad->find('all', array('order' => 'unidad', 'conditions' => array("Unidad.e='1'"), 'recursive' => -1)));
            $this->set('sistemas', $this->Sistema_Motor->find('all', array('order' => 'Sistema.nombre', 'conditions' => array("Sistema.e='1' AND Sistema_Motor.e='1'"), 'recursive' => 1)));
            $this->set('flota_id', ""); //$flota_id);
            $this->set('faena_id', ""); //$faena_id);
            $this->set('equipo_id', ""); //$equipo_id);
            $this->set('sistema_id', ""); //$sistema_id);
        }
    }

    public function specto($id = '') {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Backlog');
        $this->loadModel('Planificacion');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('PermisoUsuario');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sistema');
        $this->loadModel('Sintoma');

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad", 'Unidad.motor_id'), 'conditions' => array('Unidad.faena_id' => $this->Session->read("FaenasFiltro")), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $categoria_sintoma = $this->SintomaCategoria->find('list', array('order' => array("SintomaCategoria.nombre"), 'recursive' => -1));
        $this->set(compact('categoria_sintoma'));

        $sintomas = $this->Sintoma->find('all', array('fields' => array("Sintoma.sintoma_categoria_id", "Sintoma.id", "Sintoma.nombre", "Sintoma.codigo"), 'conditions' => array('Sintoma.e' => '1'), 'order' => array("Sintoma.nombre"), 'recursive' => -1));
        $this->set(compact('sintomas'));

        $sistemas = $this->Sistema->find('list', array('order' => array("Sistema.nombre"), 'recursive' => -1));
        $this->set(compact('sistemas'));

        if ($id != "" && is_numeric($id)) {
            $backlog = $this->Backlog->find('first', array("conditions" => array("id" => $id), "fields" => "Backlog.*", 'recursive' => -1));
            $this->set('faena_id', $backlog["Backlog"]["faena_id"]);
            $this->set('flota_id', $backlog["Backlog"]["flota_id"]);
            $this->set('unidad_id', $backlog["Backlog"]["equipo_id"]);
            $tiempo_estimado = $backlog["Backlog"]["tiempo_estimado"];
            $backlog["Backlog"]["tiempo_estimado_hora"] = floor($tiempo_estimado / 60);
            $backlog["Backlog"]["tiempo_estimado_minuto"] = ($tiempo_estimado % 60);
            $this->set('data', $backlog["Backlog"]);
        }

        if ($this->request->is('post')) {
            if (isset($this->request->data) && count($this->request->data) > 0) {
                try {
                    $data = $this->request->data;
                    if (isset($data["id"]) && $data["id"] != "" && is_numeric($data["id"])) {
                        $data["usuario_id"] = $this->getUsuarioID();
                        $data["unidad_id"] = explode("_", $data["unidad_id"]);
                        $data["flota_id"] = $data["unidad_id"][1];
                        $data["equipo_id"] = $data["unidad_id"][2];
                        if (isset($this->request->data["tiempo_estimado_hora"]) && is_numeric($this->request->data["tiempo_estimado_hora"])) {
                            if (isset($this->request->data["tiempo_estimado_minuto"]) && is_numeric($this->request->data["tiempo_estimado_minuto"])) {
                                $data["tiempo_estimado"] = intval($this->request->data["tiempo_estimado_hora"]) * 60 + intval($this->request->data["tiempo_estimado_minuto"]);
                            }
                        }
                        $data['sistema_id'] = $this->request->data["sistema_ids"];
                        $this->Backlog->save($data);
                        $folio = $this->Backlog->id;
                        $this->Session->setFlash("Backlog folio $folio modificado con éxito", 'guardar_exito');
                    } else {
                        $data["usuario_id"] = $this->getUsuarioID();
                        $data["fecha_creacion"] = date("Y-m-d");
                        $data["estado_id"] = "8";
                        $data["creacion_id"] = "2";
                        $data["unidad_id"] = explode("_", $data["unidad_id"]);
                        $data["flota_id"] = $data["unidad_id"][1];
                        $data["equipo_id"] = $data["unidad_id"][2];
                        if (isset($this->request->data["tiempo_estimado_hora"]) && is_numeric($this->request->data["tiempo_estimado_hora"])) {
                            if (isset($this->request->data["tiempo_estimado_minuto"]) && is_numeric($this->request->data["tiempo_estimado_minuto"])) {
                                $data["tiempo_estimado"] = intval($this->request->data["tiempo_estimado_hora"]) * 60 + intval($this->request->data["tiempo_estimado_minuto"]);
                            }
                        }
                        $this->Backlog->create();
                        $this->Backlog->save($data);
                        $folio = $this->Backlog->id;
                        $this->Session->setFlash("Backlog folio $folio creado con éxito", 'guardar_exito');
                    }
                    $this->redirect("/Backlog/");
                } catch (Exception $e) {
                    $this->Session->setFlash("Ocurrió un error al intentar registrar el Backlog, por favor intentar nuevamente.", 'guardar_error');
                }
            }
        }
    }

    public function web($id = "") {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Backlog');
        $this->loadModel('Planificacion');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('IntervencionElementos');
        $this->loadModel('PermisoUsuario');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sistema');
        $this->loadModel('Sintoma');
        $this->loadModel('LugarCreacion');
        $this->loadModel('Prebacklog');
        $this->loadModel('Prebacklog_comentario');
        $this->loadModel('PlanAccion');

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad", 'Unidad.motor_id'), 'conditions' => array('Unidad.faena_id' => $this->Session->read("FaenasFiltro")), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $categoria_sintoma = $this->SintomaCategoria->find('list', array('order' => array("SintomaCategoria.nombre"), 'recursive' => -1));
        $this->set(compact('categoria_sintoma'));

        $sintomas = $this->Sintoma->find('all', array('fields' => array("Sintoma.sintoma_categoria_id", "Sintoma.id", "Sintoma.nombre", "Sintoma.codigo"), 'conditions' => array('Sintoma.e' => '1'), 'order' => array("Sintoma.nombre"), 'recursive' => -1));
        $this->set(compact('sintomas'));

        $sistemas = $this->Sistema->find('list', array('order' => array("Sistema.nombre"), 'recursive' => -1));
        $this->set(compact('sistemas'));

        $lugarcreacion = $this->LugarCreacion->find('list', array('fields' => array("LugarCreacion.id", "LugarCreacion.nombre")));
        $this->set(compact('lugarcreacion'));

        //$planaccion = $this->PlanAccion->find('list', array('fields' => array("PlanAccion.id", "PlanAccion.nombre")));
        //$this->set(compact('planaccion'));

        if ($this->request->is('post')) {
            if (isset($this->request->data) && count($this->request->data) > 0) {
                try {
                    $data = $this->request->data;
                    if (isset($data["id"]) && $data["id"] != "" && is_numeric($data["id"])) {
                        $data["usuario_id"] = $this->getUsuarioID();
                        $data["unidad_id"] = explode("_", $data["unidad_ids"][0]);
                        $data["flota_id"] = $data["unidad_id"][1];
                        $data["equipo_id"] = $data["unidad_id"][2];
                        $data["creacion_id"] = $data["creacion_id"];
                        if (isset($this->request->data["tiempo_estimado_hora"]) && is_numeric($this->request->data["tiempo_estimado_hora"])) {
                            if (isset($this->request->data["tiempo_estimado_minuto"]) && is_numeric($this->request->data["tiempo_estimado_minuto"])) {
                                $data["tiempo_estimado"] = intval($this->request->data["tiempo_estimado_hora"]) * 60 + intval($this->request->data["tiempo_estimado_minuto"]);
                            }
                        }

                        $data['sintoma_id'] = (isset($data['sintoma_id']) || $data['sintoma_id'] == "" ? $data['sintoma_ids'] : $data['sintoma_id']);
                        $data['categoria_sintoma_id'] = $data['categoria_sintoma_id'];

                        $elemento_id = $data["elemento_id"];
                        unset($data["elemento_id"]);

                        //Limpia el comentario de cualquier tag
                        $data['comentario'] = Sanitize::html($data['comentario']);
                        $data["plan_accion_id"] = $data["plan_accion_id"];

                        $this->Backlog->save($data);

                        $this->Session->setFlash("Backlog folio {$data["id"]} modificado con éxito", 'guardar_exito');
                        $data["id"] = $data["intervencion_elemento_id"];
                        $data["elemento_id"] = $elemento_id;


                        $this->IntervencionElementos->save($data);
                    } else {

                        $data["unidad_id"] = explode("_", $data["unidad_ids"][0]);
                        $data["flota_id"] = $data["unidad_id"][1];
                        $data["equipo_id"] = $data["unidad_id"][2];
                        $data["creacion_id"] = $data["creacion_id"];
                        $data["tipo_registro"] = "2";
                        $data["usuario_id"] = $this->getUsuarioID();
                        $data["fecha_creacion"] = date("Y-m-d");
                        $data["estado_id"] = "8";
                        $data["creacion_id"] = $data["creacion_id"];
                        $data["plan_accion_id"] = $data["plan_accion_id"];

                        $data['sintoma_id'] = ($data['sintoma_id'] == "" ? $data['sintoma_ids'] : $data['sintoma_id']);
                        $data['categoria_sintoma_id'] = $data['categoria_sintoma_id'];

                        if (isset($this->request->data["tiempo_estimado_hora"]) && is_numeric($this->request->data["tiempo_estimado_hora"])) {
                            if (isset($this->request->data["tiempo_estimado_minuto"]) && is_numeric($this->request->data["tiempo_estimado_minuto"])) {
                                $data["tiempo_estimado"] = intval($this->request->data["tiempo_estimado_hora"]) * 60 + intval($this->request->data["tiempo_estimado_minuto"]);
                            }
                        }

                        $folio = "";
                        $fcount = 0;

                        //Limpia el comentario de cualquier tag
                        $data['comentario'] = Sanitize::html($data['comentario']);

                        //foreach($data['unidad_ids'] as $key => $value){
                        foreach ($this->request->data('unidad_ids') as $value) {
                            //$data["unidad_id"] = explode("_", $key);
                            $unidad_id = explode("_", $value);
                            $data["flota_id"] = $unidad_id[1];
                            $data["equipo_id"] = $unidad_id[2];

                            $data["folio"] = "OP" . date("YmdHi") . $fcount;
                            $data["prebacklog_id"] = $data["idprebacklog"];



                            $this->IntervencionElementos->create();
                            $this->IntervencionElementos->save($data);
                            $data["elemento_id"] = $this->IntervencionElementos->id;
                            $this->Backlog->create();
                            $this->Backlog->save($data);
                            $folio .= $this->Backlog->id . ', ';

                            $fcount += 1;
                        }

                        if ($data["idprebacklog"] != "" || $data["idprebacklog"] != 0) {

                            //Cambia estado de prebacklog a planificado
                            $this->Prebacklog->updateAll(
                                array('Prebacklog.estado_id' => 3),
                                array('Prebacklog.id' => $data["idprebacklog"]));

                            //inserta un comentario con la creacion del backlog
                            $comen['comentario'] = 'Se creo el backlog F-' . $folio . ' -> Comentario: ' . $data["comentario"];
                            $comen['usuario_id'] = $this->getUsuarioID();
                            $comen['prebacklog_id'] = $data["idprebacklog"];
                            $comen['fecha'] = date("Y-m-d H:i:s");

                            $this->Prebacklog_comentario->create();
                            $this->Prebacklog_comentario->save($comen);
                        }

                        //print_r($this->request->data('unidad_ids'));
                        $this->Session->setFlash("Backlog folio $folio creado con éxito ", 'guardar_exito');
                    }
                    $this->redirect("/Backlog/");
                } catch (Exception $e) {
                    $this->Session->setFlash("Ocurrió un error al intentar registrar el Backlog, por favor intentar nuevamente. " . $e->getMessage(), 'guardar_error');
                }
            }
        }else {
            if (isset($_GET['idPrebacklog']) && $_GET['idPrebacklog'] != "" && is_numeric($_GET['idPrebacklog'])) {
                $idpre = $_GET['idPrebacklog'];

                $prebacklog = $this->Prebacklog->find('first', array(
                    "fields" => array('Prebacklog.*', 'Faena.*', 'Flota.*', 'Unidad.*', 'Sintoma.*', 'SintomaCategoria.*'),
                    "conditions" => array("Prebacklog.id" => $idpre), 'recursive' => 1));

                //$backlog = $this->Backlog->find('first', array("conditions" => array("id" => $id), "fields" => "Backlog.*", 'recursive' => -1));
                $this->set('faena_id', $prebacklog["Prebacklog"]["faena_id"]);
                $this->set('flota_id', $prebacklog["Prebacklog"]["flota_id"]);
                $this->set('unidad_id', $prebacklog["Prebacklog"]["unidad_id"]);

                $unidades_seleccionadas[] = $prebacklog["Prebacklog"]["unidad_id"];
                $this->set('unidad_ids', $unidades_seleccionadas);
                if($prebacklog["Prebacklog"]["tipo"] == 0){
                    $this->set('creacion_id', 7);
                }else{
                    $this->set('creacion_id', 5);
                }

                $this->set('faena', $prebacklog["Faena"]["nombre"]);
                $this->set('flota', $prebacklog["Flota"]["nombre"]);
                $this->set('unidad', $prebacklog["Unidad"]["unidad"]);
                $this->set('sintoma', $prebacklog["Sintoma"]["nombre"]);
                $this->set('sintomaID', $prebacklog["Sintoma"]["id"]);
                $this->set('cat_sintoma', $prebacklog["SintomaCategoria"]["nombre"]);
                $this->set('cat_sintomaID', $prebacklog["SintomaCategoria"]["id"]);

                $prebacklog["Prebacklog"]['id'] = "";
                $prebacklog["Prebacklog"]['creacion_id'] = 5;
                $prebacklog["Prebacklog"]["tiempo_estimado"] = 0;
                $prebacklog["Prebacklog"]['sintoma_id'] = $prebacklog["Sintoma"]['id'];


                $tiempo_estimado = $prebacklog["Prebacklog"]["tiempo_estimado"];
                $prebacklog["Prebacklog"]["tiempo_estimado_hora"] = floor($tiempo_estimado / 60);
                $prebacklog["Prebacklog"]["tiempo_estimado_minuto"] = ($tiempo_estimado % 60);
                if (isset($prebacklog["Prebacklog"]["elemento_id"]) && $prebacklog["Prebacklog"]["elemento_id"] != NULL && is_numeric($prebacklog["Prebacklog"]["elemento_id"])) {
                    $elemento_id = $prebacklog["Prebacklog"]["elemento_id"];
                    $elemento = $this->IntervencionElementos->find('first', array("fields" => array("Sistema.id", "Subsistema.id", "Elemento.id", "Diagnostico.id", "PosicionSubsistema.id", "PosicionElemento.id", "IntervencionElementos.pn_saliente", "IntervencionElementos.id_elemento", "IntervencionElementos.id"), "conditions" => array("IntervencionElementos.id" => $elemento_id), 'recursive' => 1));
                    $this->set('elemento', $elemento);
                }

                $this->set('data', $prebacklog["Prebacklog"]);
            }

            if (isset($_GET['idPlanaccion']) && $_GET["idPlanaccion"] != "" && is_numeric($_GET['idPlanaccion'])) {
                $id_planaccion = $_GET['idPlanaccion'];
                $id_creacion = $_GET['creacion'];
                $unidades_url = explode(",", $_GET['unidades']);

                $planaccion = $this->PlanAccion->find('first', array("conditions" => array("id" => $id_planaccion), "fields" => "PlanAccion.*", 'recursive' => -1));

                $planaccion['PlanAccion']['id'] = $_GET['id'];
                $planaccion['PlanAccion']['folio'] = $_GET['folio'];

                $planaccion["PlanAccion"]["criticidad_id"] = $_GET['criticidad'];
                $planaccion["PlanAccion"]["responsable_id"] = $_GET['responsable'];
                $planaccion["PlanAccion"]["tiempo_estimado_hora"] = $_GET['t_h'];
                $planaccion["PlanAccion"]["tiempo_estimado_minuto"] = $_GET['t_m'];

                $planaccion["PlanAccion"]["comentario"] = $planaccion["PlanAccion"]['descripcion'];

                $dt = explode("_", $unidades_url[0]);
                $this->set('faena_id', $dt[0]);
                $this->set('flota_id', $dt[1]);
                $this->set('unidad_id', $dt[2]);

                $unidades_seleccionadas = array();
                foreach ($unidades_url as $u){
                    $dt1 = explode("_", $u);
                    $unidades_seleccionadas[] = $dt1[2];
                }
                $this->set('unidad_ids', $unidades_seleccionadas);

                $this->set('creacion_id', $id_creacion);

                $elemento['Sistema']['id'] = $planaccion['PlanAccion']['sistema_id'];
                $elemento['Subsistema']['id'] = $planaccion['PlanAccion']['subsistema_id'];
                $elemento['Elemento']['id'] = $planaccion['PlanAccion']['elemento_id'];
                $elemento['Diagnostico']['id'] = $planaccion['PlanAccion']['diagnostico_id'];
                $elemento['PosicionSubsistema']['id'] = $planaccion['PlanAccion']['subsistema_posicion_id'];
                $elemento['PosicionElemento']['id'] = $planaccion['PlanAccion']['elemento_posicion_id'];
                $elemento['IntervencionElementos']['pn_saliente'] = $planaccion['PlanAccion']['pn_saliente'];
                $elemento['IntervencionElementos']['id_elemento'] = $planaccion['PlanAccion']['id_elemento'];
                //$elemento['IntervencionElementos']['id'] = $planaccion['PlanAccion']['subsistema_id'];

                $this->set('elemento', $elemento);

                $this->set('data', $planaccion["PlanAccion"]);

                $planaccion_select = $this->PlanAccion->find('list', array('fields' => array("PlanAccion.id", "PlanAccion.nombre")));
                $this->set('planaccion', $planaccion_select);

                $this->set("plan_accion_id", $id_planaccion);
            }

            if ($id != "" && is_numeric($id)) {
                $backlog = $this->Backlog->find('first', array("conditions" => array("id" => $id), "fields" => "Backlog.*", 'recursive' => -1));
                $this->set('faena_id', $backlog["Backlog"]["faena_id"]);
                $this->set('flota_id', $backlog["Backlog"]["flota_id"]);
                $this->set('unidad_id', $backlog["Backlog"]["equipo_id"]);
                $this->set('plan_accion_id', $backlog["Backlog"]["plan_accion_id"]);

                $unidad_ids[] = $backlog["Backlog"]["equipo_id"];
                $this->set('unidad_ids', $unidad_ids);
                $this->set('creacion_id', $backlog["Backlog"]["creacion_id"]);

                if (!isset($backlog["Backlog"]["tiempo_estimado"]) || !is_numeric($backlog["Backlog"]["tiempo_estimado"])) {
                    $backlog["Backlog"]["tiempo_estimado"] = 0;
                }

                $tiempo_estimado = $backlog["Backlog"]["tiempo_estimado"];
                $backlog["Backlog"]["tiempo_estimado_hora"] = floor($tiempo_estimado / 60);
                $backlog["Backlog"]["tiempo_estimado_minuto"] = ($tiempo_estimado % 60);
                if (isset($backlog["Backlog"]["elemento_id"]) && $backlog["Backlog"]["elemento_id"] != NULL && is_numeric($backlog["Backlog"]["elemento_id"])) {
                    $elemento_id = $backlog["Backlog"]["elemento_id"];
                    $elemento = $this->IntervencionElementos->find('first', array("fields" => array("Sistema.id", "Subsistema.id", "Elemento.id", "Diagnostico.id", "PosicionSubsistema.id", "PosicionElemento.id", "IntervencionElementos.pn_saliente", "IntervencionElementos.id_elemento", "IntervencionElementos.id"), "conditions" => array("IntervencionElementos.id" => $elemento_id), 'recursive' => 1));
                    $this->set('elemento', $elemento);
                }

                $this->set('data', $backlog["Backlog"]);
            }
        }
    }

    public function index() {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Backlog');
        $this->loadModel('Planificacion');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('PermisoUsuario');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sistema');
        $this->loadModel('Sintoma');
        $this->loadModel('LugarCreacion');
        $this->loadModel('Prebacklog');
        $this->loadModel('Prebacklog_comentario');
        $this->loadModel('PlanAccion');

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id' => $this->Session->read("FaenasFiltro"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $categoria_sintoma = $this->SintomaCategoria->find('list', array('order' => array("SintomaCategoria.nombre"), 'recursive' => -1));
        $this->set(compact('categoria_sintoma'));

        $sintomas = $this->Sintoma->find('all', array('fields' => array("Sintoma.sintoma_categoria_id", "Sintoma.id", "Sintoma.nombre", "Sintoma.codigo"), 'conditions' => array('Sintoma.e' => '1'), 'order' => array("Sintoma.nombre"), 'recursive' => -1));
        $this->set(compact('sintomas'));

        $sistemas = $this->Sistema->find('list', array('order' => array("Sistema.nombre"), 'recursive' => -1));
        $this->set(compact('sistemas'));

        $lugarcreacion = $this->LugarCreacion->find('list', array('fields' => array("LugarCreacion.id", "LugarCreacion.nombre")));
        $this->set(compact('lugarcreacion'));

        $planaccion = $this->PlanAccion->find('all', array('fields' => array("PlanAccion.id", "PlanAccion.nombre"), 'conditions' => array('PlanAccion.e' => '1')));
        $this->set(compact('planaccion'));

        if ($this->request->is('post')) {
            if (isset($this->request->data) && count($this->request->data) > 0) {
                $motivo_desactivado = trim($this->request->data["motivo_desactivado"]);
                $correlativo = trim($this->request->data["correlativo"]);
                foreach ($this->request->data["backlog"] as $key => $value) {
                    $data = array();
                    $data["id"] = $value;

                    $data_prebacklog = array();

                    //obtengo datos de backlog
                    $bklog = $this->Backlog->find('first', array(
                        'fields' => array('Backlog.id', 'Backlog.equipo_id', 'Backlog.faena_id', 'Backlog.flota_id', 'Backlog.prebacklog_id'),
                        'conditions' => array("Backlog.id" => $data["id"]),
                        'recursive' => -1
                    ));

                    if ($motivo_desactivado != '') {
                        $data["motivo_desactivado"] = $motivo_desactivado;
                        $data["fecha_desactivado"] = date("Y-m-d H:i:s");
                        $data["estado_id"] = "10";

                        //Datos de prebacklog
                        $data_prebacklog['estado_id'] = "7";
                        $data_prebacklog['comentario'] = "Backlog F-" . $data["id"] . " eliminado: " . $motivo_desactivado;
                    } elseif ($correlativo != '' && is_numeric($correlativo)) {
                        $registro = $this->Planificacion->find('first', array(
                            'fields' => array('Planificacion.id', 'Planificacion.unidad_id', 'Planificacion.faena_id', 'Planificacion.flota_id', 'Planificacion.tipointervencion'),
                            'conditions' => array("Planificacion.correlativo_final" => $correlativo),
                            'recursive' => -1
                        ));



                        if (isset($registro["Planificacion"]) && isset($registro["Planificacion"]["id"])) {

                            if ($bklog['Backlog']['equipo_id'] == $registro["Planificacion"]["unidad_id"] && $bklog['Backlog']['faena_id'] == $registro["Planificacion"]["faena_id"] && $bklog['Backlog']['flota_id'] == $registro["Planificacion"]["flota_id"] && $registro["Planificacion"]["tipointervencion"] != 'MP') {
                                $data["intervencion_id"] = $registro["Planificacion"]["id"];
                                $data["realizado"] = "S";
                                $data["estado_id"] = "11";

                                //Datos de prebacklog
                                $data_prebacklog['estado_id'] = "11";
                                $data_prebacklog['comentario'] = 'Trabajo realizado, intervención terminada N°' . $correlativo;
                            } else {
                                $this->Session->setFlash('El correlativo ingresado no puede ser MP y debe pertenecer a la faena y equipo seleccionado.', 'guardar_error');
                            }
                        } else {
                            $this->Session->setFlash('El correlativo ingresado no existe.', 'guardar_error');
                        }
                    }
                    //print_r($data);
                    $this->Backlog->save($data);

                    //si tiene prebacklog lo modifico a estado realizado
                    if ($bklog["Backlog"]['prebacklog_id'] != "" || $bklog["Backlog"]['prebacklog_id'] != 0) {

                        //Cambia estado de prebacklog a planificado
                        $this->Prebacklog->updateAll(
                            array('Prebacklog.estado_id' => $data_prebacklog['estado_id']),
                            array('Prebacklog.id' => $bklog["Backlog"]['prebacklog_id']));

                        //inserta un comentario con la aprobacion de la bitacora
                        $comen['comentario'] = $data_prebacklog['comentario'];
                        $comen['usuario_id'] = $this->getUsuarioID();
                        $comen['prebacklog_id'] = $bklog["Backlog"]['prebacklog_id'];
                        $comen['fecha'] = date("Y-m-d H:i:s");

                        $this->Prebacklog_comentario->create();
                        $this->Prebacklog_comentario->save($comen);
                    }
                }
            }
        }

        $conditions = array();
        $limit = 25;
        $fecha_inicio = date("Y-m-d",strtotime("-365 days"));
        $fecha_termino = date("Y-m-d");
        $plan_accion = 0;

        $conditions["Backlog.faena_id IN"] = $this->Session->read("FaenasFiltro");
        if ($this->request->is('get')) {
            if (isset($this->request->query['folio']) && is_numeric($this->request->query['folio'])) {
                $conditions["Backlog.id"] = $this->request->query['folio'];
            }
            if (isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
                $limit = $this->request->query['limit'];
            }
            if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
                $conditions["Backlog.faena_id"] = $this->request->query['faena_id'];
            }

            if (isset($this->request->query['correlativo_']) && is_numeric($this->request->query['correlativo_'])) {
                $conditions["CAST(Intervencion.correlativo_final AS text) LIKE"] = "%" . $this->request->query['correlativo_'] . "%";
            }
            if (isset($this->request->query['estado_id']) && $this->request->query['estado_id'] != "") {
                $estados = explode("_", $this->request->query['estado_id']);
                if(count($estados) <= 1){
                    $conditions["Backlog.estado_id"] = $this->request->query['estado_id'];
                }else{
                    $conditions["Backlog.estado_id IN"] = array(8,2);
                }
            }
            if (isset($this->request->query['criticidad_id']) && is_numeric($this->request->query['criticidad_id'])) {
                $conditions["Backlog.criticidad_id"] = $this->request->query['criticidad_id'];
            }
            if (isset($this->request->query['responsable_id']) && $this->request->query['responsable_id'] != '') {
                $conditions["Backlog.responsable_id"] = $this->request->query['responsable_id'];
            }
            if (isset($this->request->query['creacion_id']) && $this->request->query['creacion_id'] != '') {
                $conditions["Backlog.creacion_id"] = $this->request->query['creacion_id'];
            }
            if (isset($this->request->query['sistema_id']) && $this->request->query['sistema_id'] != '') {
                $conditions["Backlog.sistema_id"] = $this->request->query['sistema_id'];
            }
            if (isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] != '') {
                $conditions["Intervencion.tipointervencion"] = $this->request->query['tipointervencion'];
            }
            if (isset($this->request->query['fecha_inicio']) && $this->request->query['fecha_inicio'] != '') {
                $fecha_inicio = $this->request->query['fecha_inicio'];
                $conditions["Backlog.fecha_creacion >="] = $fecha_inicio . ' 00:00:00';
            }
            if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != '') {
                $fecha_termino = $this->request->query['fecha_termino'];
                $conditions["Backlog.fecha_creacion <="] = $fecha_termino . ' 00:00:00';
            }
            foreach ($this->request->query as $key => $value) {
                $this->set($key, $value);
            }
            if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
                $flota_id = explode("_", $this->request->query['flota_id']);
                $flota_id = $flota_id[1];
                $this->set(compact('flota_id'));
                $conditions["Backlog.flota_id"] = $flota_id;
            }
            if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
                $unidad_id = explode("_", $this->request->query['unidad_id']);
                $unidad_id = $unidad_id[2];
                $equipo_id = $unidad_id;
                $this->set(compact('unidad_id'));
                $this->set(compact('equipo_id'));
                $conditions["Backlog.equipo_id"] = $unidad_id;
            }

            if (isset($this->request->query['plan_accion']) && $this->request->query['plan_accion'] != '') {
                $plan_accion = $this->request->query['plan_accion'];
                $conditions["PlanAccion.id"] = $plan_accion;
            }

            $query = http_build_query($this->request->query);
            $this->set(compact('query'));
        }

        $this->paginate = array(
            'fields' => array('Backlog.*', 'Intervencion.tipointervencion', 'Intervencion.correlativo_final', 'Intervencion.fecha', 'Usuario.u', 'LugarCreacion.nombre', 'Criticidad.nombre', 'ResponsableBacklog.nombre', 'Sistema.nombre', 'Estado.nombre', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'PlanAccion.id', 'PlanAccion.nombre'),
            'conditions' => $conditions,
            'limit' => $limit,
            'order' => 'Backlog.fecha_creacion DESC',
            'recursive' => 1
        );

        $registros = $this->paginate('Backlog');
        foreach ($registros as $key => $registro) {
            if (isset($registro["Intervencion"]["correlativo_final"]) && is_numeric($registro["Backlog"]["correlativo_final"])) {
                $intervenciones = $this->Planificacion->find('all', array('fields' => array('Planificacion.observacion', 'Planificacion.json', 'Planificacion.fecha', 'Planificacion.hora', 'Planificacion.id'),
                    'conditions' => array('correlativo_final' => $registro["Intervencion"]["correlativo_final"]),
                    'order' => 'Planificacion.id ASC',
                    'recursive' => -1));
                $comentarios = array();
                //print_r($intervenciones);
                foreach ($intervenciones as $key2 => $intervencion) {
                    if (isset($intervencion["Planificacion"]["observacion"])) {
                        $comentarios[] = $intervencion["Planificacion"]["observacion"];
                    }
                }
                $registros[$key]["Comentarios"] = $comentarios;
            }
        }
        //print_r($registros);
        $this->set("fecha_inicio", $fecha_inicio);
        $this->set("fecha_termino", $fecha_termino);
        $this->set("plan_accion", $plan_accion);
        $this->set(compact('registros'));
        $this->set(compact('limit'));
    }


    public function descargar($tipo = "") {
        $this->layout = null;
        $this->check_permissions($this);
        $this->loadModel('Backlog');
        $this->loadModel('IntervencionElementos');
        if ($tipo == "Excel") {
            $conditions = array();
            //$conditions["Backlog.faena_id IN"] = $this->Session->read("FaenasFiltro");
            if ($this->request->is('get')) {
                if (isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
                    $limit = $this->request->query['limit'];
                }
                if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
                    $conditions["Backlog.faena_id"] = $this->request->query['faena_id'];
                }
                if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
                    $flota_id = explode("_", $this->request->query['flota_id']);
                    $flota_id = $flota_id[1];
                    $conditions["Backlog.flota_id"] = $flota_id;
                }
                if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
                    $unidad_id = explode("_", $this->request->query['unidad_id']);
                    $unidad_id = $unidad_id[2];
                    $conditions["Backlog.equipo_id"] = $unidad_id;
                }
                if (isset($this->request->query['correlativo_']) && is_numeric($this->request->query['correlativo_'])) {
                    $conditions["CAST(Intervencion.correlativo_final AS text) LIKE"] = "%" . $this->request->query['correlativo_'] . "%";
                }
                if (isset($this->request->query['estado_id']) && $this->request->query['estado_id'] != "") {
                    $estados = explode("_", $this->request->query['estado_id']);
                    if(count($estados) <= 1){
                        $conditions["Backlog.estado_id"] = $this->request->query['estado_id'];
                    }else{
                        $conditions["Backlog.estado_id IN"] = array(8,2);
                    }
                }
                if (isset($this->request->query['responsable_id']) && $this->request->query['responsable_id'] != '') {
                    $conditions["Backlog.responsable_id"] = $this->request->query['responsable_id'];
                }
                if (isset($this->request->query['creacion_id']) && $this->request->query['creacion_id'] != '') {
                    $conditions["Backlog.creacion_id"] = $this->request->query['creacion_id'];
                }
                if (isset($this->request->query['sistema_id']) && $this->request->query['sistema_id'] != '') {
                    $conditions["Backlog.sistema_id"] = $this->request->query['sistema_id'];
                }
                if (isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] != '') {
                    $conditions["Intervencion.tipointervencion"] = $this->request->query['tipointervencion'];
                }
                if (isset($this->request->query['fecha_inicio']) && $this->request->query['fecha_inicio'] != '') {
                    $fecha_inicio = $this->request->query['fecha_inicio'];
                    $conditions["Backlog.fecha_creacion >="] = $fecha_inicio . ' 00:00:00';
                }
                if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != '') {
                    $fecha_termino = $this->request->query['fecha_termino'];
                    $conditions["Backlog.fecha_creacion <="] = $fecha_termino . ' 00:00:00';
                }
                if (isset($this->request->query['plan_accion']) && $this->request->query['plan_accion'] != '') {
                    $plan_accion = $this->request->query['plan_accion'];
                    $conditions["PlanAccion.id"] = $plan_accion;
                }

                foreach ($this->request->query as $key => $value) {
                    $this->set($key, $value);
                }
            }

            $backlogs = $this->Backlog->find('all', array('fields' => array('Backlog.*', 'Intervencion.tipointervencion', 'Intervencion.correlativo_final', 'Intervencion.fecha', 'Usuario.u', 'LugarCreacion.nombre', 'Criticidad.nombre', 'ResponsableBacklog.nombre', 'Sistema.nombre', 'Estado.nombre', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Sistema.nombre', 'Subsistema.nombre', 'Sintoma.nombre', 'SintomaCategoria.nombre', 'Estado.nombre', 'Backlog.elemento_id', 'PlanAccion.id', 'PlanAccion.nombre'),
                    'conditions' => $conditions,
                    'order' => 'Backlog.fecha_creacion DESC',
                    'recursive' => 1)
            );
            $dataArray = array();
            foreach ($backlogs as $value) {
                $date = strtotime($value["Backlog"]["fecha_creacion"]);
                $date = date("d-m-Y", $date);
                $dataTemp = array();
                $conditions = array();
                $dataTemp[] = $value["Faena"]["nombre"];
                $dataTemp[] = $value["Flota"]["nombre"];
                $dataTemp[] = $value["Unidad"]["unidad"];
                $dataTemp[] = $date;
                $dataTemp[] = $value["LugarCreacion"]["nombre"];
                $dataTemp[] = $value["Usuario"]["u"];
                $dataTemp[] = $value["Criticidad"]["nombre"];
                $dataTemp[] = $value["ResponsableBacklog"]["nombre"];
                $dataTemp[] = $value["SintomaCategoria"]["nombre"];
                $dataTemp[] = $value["Sintoma"]["nombre"];
                $dataTemp[] = $value["Sistema"]["nombre"];
                $conditions["IntervencionElementos.id"] = $value["Backlog"]["elemento_id"];
                if ($value["Backlog"]["elemento_id"] != null && is_numeric($value["Backlog"]["elemento_id"])) {
                    $elemento = $this->IntervencionElementos->find('first', array(
                        'fields' => array(
                            'Sistema.nombre',
                            'Subsistema.nombre',
                            'PosicionSubsistema.nombre',
                            'Elemento.nombre',
                            'PosicionElemento.nombre',
                            'Diagnostico.nombre',
                            'IntervencionElementos.pn_saliente',
                            'IntervencionElementos.id_elemento'),
                        'conditions' => $conditions,
                        'recursive' => 1));
                } else {
                    $elemento = '';
                }
                if (isset($elemento) && isset($elemento["Sistema"]["nombre"])) {
                    $dataTemp[] = $elemento["Subsistema"]["nombre"];
                    $dataTemp[] = $elemento["PosicionSubsistema"]["nombre"];
                    $dataTemp[] = $elemento["IntervencionElementos"]["id_elemento"];
                    $dataTemp[] = $elemento["Elemento"]["nombre"];
                    $dataTemp[] = $elemento["PosicionElemento"]["nombre"];
                    $dataTemp[] = $elemento["Diagnostico"]["nombre"];
                    $dataTemp[] = $elemento["IntervencionElementos"]["pn_saliente"];
                } else {
                    $dataTemp[] = "";
                    $dataTemp[] = "";
                    $dataTemp[] = "";
                    $dataTemp[] = "";
                    $dataTemp[] = "";
                    $dataTemp[] = "";
                    $dataTemp[] = "";
                }
                $dataTemp[] = html_entity_decode($value["Backlog"]["comentario"]);
                $dataTemp[] = $value["Estado"]["nombre"];
                $dataTemp[] = $value["Intervencion"]["tipointervencion"];
                $dataTemp[] = $value["Intervencion"]["correlativo_final"];
                $dataTemp[] = $value["Backlog"]["id"];
                $h = $value["Backlog"]["tiempo_estimado"] / 60;
                //$m = $value["Backlog"]["tiempo_estimado"]%60;
                $dataTemp[] = is_null($h) ? '0' : $h; //$value["Backlog"]["tiempo_estimado"];
                $dataTemp[] = $value["Intervencion"]["fecha"];

                $dataTemp[] = $value["Backlog"]["motivo_desactivado"];
                $dataTemp[] = $value["Backlog"]["fecha_desactivado"] == null ? '' : date("d-m-Y H:i:s", strtotime($value["Backlog"]["fecha_desactivado"]));

                $dataTemp[] = $value['PlanAccion']['id'];
                $dataTemp[] = $value['PlanAccion']['nombre'];

                $dataArray[] = $dataTemp;
            }
            $utilReporte = new UtilidadesReporteController();
            $util = new UtilidadesController();
            PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
            $objPHPExcel = new PHPExcel();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Cache-Control: max-age=0');
            header('Content-Disposition: attachment;filename="Backlogs-' . date("Y-m-d") . '".xlsx');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . date('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $objPHPExcel->getProperties()
                ->setCreator("DBM")
                ->setLastModifiedBy("DBM")
                ->setTitle("Backlogs");
            $objPHPExcel->setActiveSheetIndex(0)->setTitle('Backlogs');

            $style_centrado = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ),
            );

            // Encabezados
            $encabezados = array("Faena", "Flota", "Equipo", "Fecha creación", "Lugar creación", "Creador", "Criticidad", "Responsable", "Categoría Síntoma", "Síntoma", "Sistema", "Subsistema", "Pos. subsistema", "ID elemento", "Elemento", "Pos. elemento", "Diagnostico", "Part number", "Comentarios", "Estado", "Tipo", "Correlativo", "Folio", "Tiempo estimado (Horas)", "fecha inicio intervencion", "Motivo desactivado", "Fecha desactivado", "ID P. Acción", 'Plan de acción');
            $count = count($encabezados);
            for ($i = 0; $i < $count; $i++) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 1, $encabezados[$i]);
                $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(12);
            }
            // Carga de Datos desde Array
            //$i = 1;
            $utilReporte->cellColor("A1:AC1", "FF0000", "FFFFFF", $objPHPExcel);
            $objPHPExcel->getActiveSheet()->getStyle('A1:AC1')->getFont()->setBold(true);
            $total = count($backlogs) + 1;
            $objPHPExcel->getActiveSheet()->getStyle('A1:AC' . $total)->applyFromArray($style_centrado);
            $objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2', true);
            //$objPHPExcel->getActiveSheet()->getStyle('A1:AR1')->getFont()->setBold(true);
            //for ($i=1;$i<=$total;$i++){
            //$objPHPExcel->getActiveSheet()->getStyle("Q1:Q".$total)->getNumberFormat()->setFormatCode('#,##0.00');
            //}
            //$objPHPExcel->getActiveSheet()->getColumnDimension('A:AA')->setWidth(20);
            //$objPHPExcel->getActiveSheet()->getCo
            //$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
            //$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(13);
            //$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(11);
            //$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(70);
            //$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
            //$objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }
    }

    public function motorimagen($motor_id, $sistema_id, $subsistema_id) {
        $this->layout = null;
        $this->loadModel('Motor');
        $this->loadModel('Sistema');
        $this->loadModel('Subsistema');

        $resultado = $this->Motor->find('first', array(
            'conditions' => array("Motor.id" => $motor_id),
            'recursive' => 1));

        if (isset($resultado["Motor"])) {
            $motor = $resultado["Motor"]["nombre"] . ' ' . $resultado["TipoEmision"]["nombre"];
        }

        $resultado = $this->Sistema->find('first', array(
            'conditions' => array("id" => $sistema_id),
            'recursive' => -1));

        if (isset($resultado["Sistema"])) {
            $sistema = $resultado["Sistema"]["nombre"];
            $sistema = explode("_", $sistema);
            $sistema = $sistema[1];
        }

        $resultado = $this->Subsistema->find('first', array(
            'conditions' => array("id" => $subsistema_id),
            'recursive' => -1));

        if (isset($resultado["Subsistema"])) {
            $subsistema = $resultado["Subsistema"]["nombre"];
        }

        $image_source = $motor . ' ' . $sistema . ' ' . $subsistema;
        $image_source = str_replace(" ", "_", $image_source);
        $image_source = strtoupper($image_source);
        echo str_replace(array("Á", "É", "Í", "Ó", "Ú", "Ñ"), array("A", "E", "I", "O", "U", "N"), $image_source);
        exit;
    }

    public function prebacklog($id) {

        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Backlog');
        $this->loadModel('Prebacklog');

        $prebacklog = $this->Prebacklog->find('first', array(
            "conditions" => array("id" => $id), 'recursive' => -1));




        //$backlog = $this->Backlog->find('first', array("conditions" => array("id" => $id), "fields" => "Backlog.*", 'recursive' => -1));
        $this->set('faena_id', $prebacklog["Prebacklog"]["faena_id"]);
        $this->set('flota_id', $prebacklog["Prebacklog"]["flota_id"]);
        $this->set('unidad_id', $prebacklog["Prebacklog"]["equipo_id"]);
        $this->set('creacion_id', 5);

        $prebacklog["Prebacklog"]["tiempo_estimado"] = 0;


        $tiempo_estimado = $prebacklog["Prebacklog"]["tiempo_estimado"];
        $prebacklog["Prebacklog"]["tiempo_estimado_hora"] = floor($tiempo_estimado / 60);
        $prebacklog["Prebacklog"]["tiempo_estimado_minuto"] = ($tiempo_estimado % 60);
        if (isset($prebacklog["Prebacklog"]["elemento_id"]) && $prebacklog["Prebacklog"]["elemento_id"] != NULL && is_numeric($prebacklog["Prebacklog"]["elemento_id"])) {
            $elemento_id = $prebacklog["Prebacklog"]["elemento_id"];
            $elemento = $this->IntervencionElementos->find('first', array("fields" => array("Sistema.id", "Subsistema.id", "Elemento.id", "Diagnostico.id", "PosicionSubsistema.id", "PosicionElemento.id", "IntervencionElementos.pn_saliente", "IntervencionElementos.id_elemento", "IntervencionElementos.id"), "conditions" => array("IntervencionElementos.id" => $elemento_id), 'recursive' => 1));
            $this->set('elemento', $elemento);
        }

        $this->set('data', $prebacklog["Prebacklog"]);
    }

}

?>