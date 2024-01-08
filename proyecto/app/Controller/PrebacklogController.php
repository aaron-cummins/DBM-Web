<?php

/**
 * Description of PrebacklogController
 *
 * @author AZUNIGA
 */
App::uses('ConnectionManager', 'Model');
App::uses('File', 'Utility');
App::import('Controller', 'Utilidades');
App::uses('CakeEmail', 'Network/Email');
App::import('Vendor', 'Classes/PHPExcel');
App::import('Controller', 'UtilidadesReporte');

App::uses('Sanitize', 'Utility');

require_once '../../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Ses;

class PrebacklogController extends AppController {

    //put your code here
    public $components = array('AWSSES','Session');
    public $ses;
    
    function index() {
        $this->set('titulo', 'Prebacklog Parámetros');
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Prebacklog');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('PermisoUsuario');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sintoma');
        $this->loadModel('Prebacklog_categoria');

        $this->loadModel('Prebacklog_motivoCierre');
        $this->loadModel('Prebacklog_comentario');


        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("PermisosFaenas"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("PermisosFaenas"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id' => $this->Session->read("PermisosFaenas"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $categoria_sintoma = $this->SintomaCategoria->find('list', array('order' => array("SintomaCategoria.nombre"), 'recursive' => -1));
        $this->set(compact('categoria_sintoma'));

        $sintomas = $this->Sintoma->find('all', array('fields' => array("Sintoma.sintoma_categoria_id", "Sintoma.id", "Sintoma.nombre", "Sintoma.codigo"), 'conditions' => array('Sintoma.e' => '1'), 'order' => array("Sintoma.nombre"), 'recursive' => -1));
        $this->set(compact('sintomas'));

        $motivocierre = $this->Prebacklog_motivoCierre->find('all', array('fields' => array("Prebacklog_motivoCierre.id", "Prebacklog_motivoCierre.motivo"), 'conditions' => array('Prebacklog_motivoCierre.e' => '1', 'Prebacklog_motivoCierre.tipo' => '0'), 'order' => array("Prebacklog_motivoCierre.motivo"), 'recursive' => -1));
        $this->set(compact('motivocierre'));

        $sinrevisar = 0;
        $revisado = 0;
        $realizado = 0;
        $planificado = 0;
        $desactivado = 0;
        $total = 0;
        $cerrado = 0;

        $alertas = $this->Prebacklog->query("select count(*), estado_id , estados.nombre from prebacklog inner join estados on estados.id = estado_id Where tipo='0' group by estado_id, estados.nombre");
        foreach ($alertas as $al) {
            switch ($al[0]['estado_id']) {
                case 2:
                    $planificado = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
                case 7:
                    $sinrevisar = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
                case 3:
                    $revisado = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
                case 11:
                    $realizado = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
                case 9:
                    $desactivado = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
                case 13;
                    $cerrado = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
            }
        }
        $this->set(compact('sinrevisar'));
        $this->set(compact('revisado'));
        $this->set(compact('realizado'));
        $this->set(compact('desactivado'));
        $this->set(compact('planificado'));
        $this->set(compact('total'));
        $this->set(compact('cerrado'));

        if ($this->request->is('post')) {
            if (isset($this->request->data) && count($this->request->data) > 0) {
                $motivo_cierre = $this->request->data["motivo_cierre"];
                $id_prebacklog = $this->request->data["id_prebacklog"];
                $comentario = "Prebacklog cerrado - " . trim($this->request->data["comentario_cierre"]);

                $data = array();
                $comen = array();



                if ($motivo_cierre != '') {
                    $data["motivocierre_id"] = $motivo_cierre;
                    $data["fecha_actualizacion"] = "'" . date("Y-m-d H:i:s") . "'";
                    $data["fecha_cierre"] = "'" . date("Y-m-d H:i:s") . "'";
                    $data["estado_id"] = 9;
                    $data["id"] = $id_prebacklog;

                    $this->Prebacklog->updateAll(
                            array('Prebacklog.motivocierre_id' => $data["motivocierre_id"],
                                'Prebacklog.fecha_actualizacion' => $data["fecha_actualizacion"],
                                'Prebacklog.fecha_cierre' => $data["fecha_cierre"],
                                'Prebacklog.estado_id' => $data["estado_id"]),
                            array('Prebacklog.id' => $data["id"])
                    );
                }

                if ($comentario != '') {
                    $comen['comentario'] = $comentario;
                    $comen['usuario_id'] = $this->getUsuarioID();
                    ;
                    $comen['prebacklog_id'] = $id_prebacklog;
                    $comen['fecha'] = date("Y-m-d H:i:s");

                    $this->Prebacklog_comentario->create();
                    $this->Prebacklog_comentario->save($comen);
                }
                //print_r($data);
                $this->Session->setFlash("Prebacklog folio $id_prebacklog cerrado con éxito", 'guardar_exito');
            }
        }

        $conditions = array();
        $limit = 25;
        $faena_id="";
        $flota_id="";
        $unidad_id="";
        $criticidad_id="";
        $fecha_inicio="";
        $fecha_termino="";
        $estado_id="";
        $folio="";

        $conditions["Prebacklog.faena_id IN"] = $this->Session->read("PermisosFaenas");
        $conditions["Prebacklog.tipo"] = '0';
        if ($this->request->is('get')) {
            if (isset($this->request->query['folio']) && is_numeric($this->request->query['folio'])) {
                $folio = $this->request->query['folio'];
                $conditions["Prebacklog.id"] = $folio;

            }
            if (isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
                $limit = $this->request->query['limit'];
            }
            if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
                $faena_id = $this->request->query['faena_id'];
                $conditions["Prebacklog.faena_id"] = $faena_id;
            }

            if (isset($this->request->query['estado_id']) && is_numeric($this->request->query['estado_id'])) {
                $estado_id = $this->request->query['estado_id'];
                $conditions["Prebacklog.estado_id"] = $estado_id;
            }
            if (isset($this->request->query['criticidad_id']) && is_numeric($this->request->query['criticidad_id'])) {
                $criticidad_id = $this->request->query['criticidad_id'];
                $conditions["Prebacklog.criticidad_id"] = $criticidad_id;
            }
            if (isset($this->request->query['fecha_inicio']) && $this->request->query['fecha_inicio'] != '') {
                $fecha_inicio = $this->request->query['fecha_inicio'];
                $conditions["Prebacklog.fecha_creacion >="] = $fecha_inicio . ' 00:00:00';
            }
            if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != '') {
                $fecha_termino = $this->request->query['fecha_termino'];
                $conditions["Prebacklog.fecha_creacion <="] = $fecha_termino . ' 00:00:00';
            }
            foreach ($this->request->query as $key => $value) {
                $this->set($key, $value);
            }
            if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
                $flota_id = explode("_", $this->request->query['flota_id']);
                $flota_id = $flota_id[1];
                $this->set(compact('flota_id'));
                $conditions["Prebacklog.flota_id"] = $flota_id;
            }
            if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
                $unidad_id = explode("_", $this->request->query['unidad_id']);
                $unidad_id = $unidad_id[2];
                $equipo_id = $unidad_id;
                $this->set(compact('unidad_id'));
                $this->set(compact('equipo_id'));
                $conditions["Prebacklog.unidad_id"] = $unidad_id;
            }
            $query = http_build_query($this->request->query);
            $this->set(compact('query'));

            if (isset($this->request->query["btn-descargar"])) {
                //$this->redirect("/Prebacklog/descarga_parametros?faena_id=$faena_id&flota_id=$flota_id&unidad_id=$unidad_id&componente_id=$componente_id&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin");
                $this->redirect("/Prebacklog/descarga_parametros?faena_id=$faena_id&flota_id=$flota_id&unidad_id=$unidad_id&criticidad_id=$criticidad_id&fecha_inicio=$fecha_inicio&fecha_termino=$fecha_termino&estado_id=$estado_id&folio=$folio");
            }
        }



        $this->paginate = array(
            'fields' => array('Prebacklog.*', 'Usuario.u', 'Criticidad.nombre', 'Estado.nombre_prebacklog', 'Estado.id', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Prebacklog_categoria.nombre', 'Sintoma.nombre', 'SintomaCategoria.nombre'),
            'conditions' => $conditions,
            'limit' => $limit,
            'order' => 'Prebacklog.fecha_creacion DESC',
            'recursive' => 1
        );
        $registros = $this->paginate('Prebacklog');

        //print_r($registros);
        $this->set(compact('registros'));
        $this->set(compact('limit'));
    }

    function aceite() {
        $this->set('titulo', 'Prebacklog Aceite');
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Prebacklog');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('PermisoUsuario');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sintoma');
        $this->loadModel('Prebacklog_categoria');

        $this->loadModel('Prebacklog_motivoCierre');
        $this->loadModel('Prebacklog_comentario');

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("PermisosFaenas"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("PermisosFaenas"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id' => $this->Session->read("PermisosFaenas"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $motivocierre = $this->Prebacklog_motivoCierre->find('all', array('fields' => array("Prebacklog_motivoCierre.id", "Prebacklog_motivoCierre.motivo"), 'conditions' => array('Prebacklog_motivoCierre.e' => '1', 'Prebacklog_motivoCierre.tipo' => '1'), 'order' => array("Prebacklog_motivoCierre.motivo"), 'recursive' => -1));
        $this->set(compact('motivocierre'));

        $sinrevisar = 0;
        $revisado = 0;
        $realizado = 0;
        $planificado = 0;
        $desactivado = 0;
        $total = 0;
        $cerrado = 0;

        $alertas = $this->Prebacklog->query("select count(*), estado_id , estados.nombre from prebacklog inner join estados on estados.id = estado_id Where tipo='1' group by estado_id, estados.nombre");
        foreach ($alertas as $al) {
            switch ($al[0]['estado_id']) {
                case 2:
                    $planificado = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
                case 7:
                    $sinrevisar = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
                case 3:
                    $revisado = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
                case 11:
                    $realizado = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
                case 9:
                    $desactivado = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
                case 13;
                    $cerrado = $al[0]['count'];
                    $total += $al[0]['count'];
                    break;
            }
        }
        $this->set(compact('sinrevisar'));
        $this->set(compact('revisado'));
        $this->set(compact('realizado'));
        $this->set(compact('desactivado'));
        $this->set(compact('planificado'));
        $this->set(compact('total'));
        $this->set(compact('cerrado'));

        if ($this->request->is('post')) {
            if (isset($this->request->data) && count($this->request->data) > 0) {
                $motivo_cierre = $this->request->data["motivo_cierre"];
                $id_prebacklog = $this->request->data["id_prebacklog"];
                $comentario = "Prebacklog cerrado - " . trim($this->request->data["comentario_cierre"]);

                $data = array();
                $comen = array();



                if ($motivo_cierre != '') {
                    $data["motivocierre_id"] = $motivo_cierre;
                    $data["fecha_actualizacion"] = "'" . date("Y-m-d H:i:s") . "'";
                    $data["fecha_cierre"] = "'" . date("Y-m-d H:i:s") . "'";
                    $data["estado_id"] = 9;
                    $data["id"] = $id_prebacklog;

                    $this->Prebacklog->updateAll(
                            array('Prebacklog.motivocierre_id' => $data["motivocierre_id"],
                                'Prebacklog.fecha_actualizacion' => $data["fecha_actualizacion"],
                                'Prebacklog.fecha_cierre' => $data["fecha_cierre"],
                                'Prebacklog.estado_id' => $data["estado_id"]),
                            array('Prebacklog.id' => $data["id"])
                    );
                }

                if ($comentario != '') {
                    $comen['comentario'] = $comentario;
                    $comen['usuario_id'] = $this->getUsuarioID();
                    ;
                    $comen['prebacklog_id'] = $id_prebacklog;
                    $comen['fecha'] = date("Y-m-d H:i:s");

                    $this->Prebacklog_comentario->create();
                    $this->Prebacklog_comentario->save($comen);
                }
                //print_r($data);
                $this->Session->setFlash("Prebacklog folio $id_prebacklog cerrado con éxito", 'guardar_exito');
            }
        }

        $conditions = array();
        $limit = 25;
        $faena_id="";
        $flota_id="";
        $unidad_id="";
        $criticidad_id="";
        $fecha_inicio="";
        $fecha_termino="";
        $estado_id="";
        $folio="";

        $conditions["Prebacklog.faena_id IN"] = $this->Session->read("PermisosFaenas");
        $conditions["Prebacklog.tipo"] = '1';
        if ($this->request->is('get')) {
            if (isset($this->request->query['folio']) && is_numeric($this->request->query['folio'])) {
                $folio = $this->request->query['folio'];
                $conditions["Prebacklog.id"] = $folio;

            }
            if (isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
                $limit = $this->request->query['limit'];
            }
            if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
                $faena_id = $this->request->query['faena_id'];
                $conditions["Prebacklog.faena_id"] = $faena_id;
            }

            if (isset($this->request->query['estado_id']) && is_numeric($this->request->query['estado_id'])) {
                $estado_id = $this->request->query['estado_id'];
                $conditions["Prebacklog.estado_id"] = $estado_id;
            }
            if (isset($this->request->query['criticidad_id']) && is_numeric($this->request->query['criticidad_id'])) {
                $criticidad_id = $this->request->query['criticidad_id'];
                $conditions["Prebacklog.criticidad_id"] = $criticidad_id;
            }
            if (isset($this->request->query['fecha_inicio']) && $this->request->query['fecha_inicio'] != '') {
                $fecha_inicio = $this->request->query['fecha_inicio'];
                $conditions["Prebacklog.fecha_creacion >="] = $fecha_inicio . ' 00:00:00';
            }
            if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != '') {
                $fecha_termino = $this->request->query['fecha_termino'];
                $conditions["Prebacklog.fecha_creacion <="] = $fecha_termino . ' 00:00:00';
            }
            foreach ($this->request->query as $key => $value) {
                $this->set($key, $value);
            }
            if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
                $flota_id = explode("_", $this->request->query['flota_id']);
                $flota_id = $flota_id[1];
                $this->set(compact('flota_id'));
                $conditions["Prebacklog.flota_id"] = $flota_id;
            }
            if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
                $unidad_id = explode("_", $this->request->query['unidad_id']);
                $unidad_id = $unidad_id[2];
                $equipo_id = $unidad_id;
                $this->set(compact('unidad_id'));
                $this->set(compact('equipo_id'));
                $conditions["Prebacklog.unidad_id"] = $unidad_id;
            }
            $query = http_build_query($this->request->query);
            $this->set(compact('query'));

            if (isset($this->request->query["btn-descargar"])) {
                //$this->redirect("/Prebacklog/descarga_parametros?faena_id=$faena_id&flota_id=$flota_id&unidad_id=$unidad_id&componente_id=$componente_id&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin");
                $this->redirect("/Prebacklog/descarga_aceite?faena_id=$faena_id&flota_id=$flota_id&unidad_id=$unidad_id&criticidad_id=$criticidad_id&fecha_inicio=$fecha_inicio&fecha_termino=$fecha_termino&estado_id=$estado_id&folio=$folio");
            }
        }



        $this->paginate = array(
            'fields' => array('Prebacklog.*', 'Usuario.u', 'Criticidad.nombre', 'Estado.nombre', 'Estado.id', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Prebacklog_categoria.nombre'),
            'conditions' => $conditions,
            'limit' => $limit,
            'order' => 'Prebacklog.fecha_creacion DESC',
            'recursive' => 1
        );
        $registros = $this->paginate('Prebacklog');


        $this->set(compact('registros'));
        $this->set(compact('limit'));
    }

    function prebacklog_parametro($id = '') {
        $this->set('titulo', 'Parámetros');
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Prebacklog');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('PermisoUsuario');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sintoma');
        $this->loadModel('Prebacklog_categoria');
        $this->loadModel('Prebacklog_comentario');
        $this->loadModel('Prebacklog_archivo');

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("PermisosFaenas"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("PermisosFaenas"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id' => $this->Session->read("PermisosFaenas"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $categoria_sintoma = $this->SintomaCategoria->find('list', array('order' => array("SintomaCategoria.nombre"), 'recursive' => -1));
        $this->set(compact('categoria_sintoma'));

        $sintomas = $this->Sintoma->find('all', array('fields' => array("Sintoma.sintoma_categoria_id", "Sintoma.id", "Sintoma.nombre", "Sintoma.codigo"), 'conditions' => array('Sintoma.e' => '1'), 'order' => array("Sintoma.nombre"), 'recursive' => -1));
        $this->set(compact('sintomas'));

        $categoria = $this->Prebacklog_categoria->find('all', array('fields' => array("Prebacklog_categoria.id", "Prebacklog_categoria.nombre"), 'conditions' => array('Prebacklog_categoria.e' => '1'), 'order' => array("Prebacklog_categoria.nombre"), 'recursive' => -1));
        $this->set(compact('categoria'));


        if ($id != "" && is_numeric($id)) {
            $prebacklog = $this->Prebacklog->find('first', array("conditions" => array("id" => $id), "fields" => "Prebacklog.*", 'recursive' => -1));
            $comentarios = $this->Prebacklog_comentario->find('all',
                    array('conditions' => array('Prebacklog_comentario.prebacklog_id' => $id),
                        'order' => 'Prebacklog_comentario.fecha DESC', 'recursive' => 1));

            $this->set('comentarios', $comentarios);
            $this->set('faena_id', $prebacklog["Prebacklog"]["faena_id"]);
            $this->set('flota_id', $prebacklog["Prebacklog"]["flota_id"]);
            $this->set('unidad_id', $prebacklog["Prebacklog"]["equipo_id"]);
            $this->set('data', $prebacklog["Prebacklog"]);
        }

        if ($this->request->is('post')) {
            if (isset($this->request->data) && count($this->request->data) > 0) {
                try {
                    $data = $this->request->data;

                    $data["tipo"] = '0';
                    $data["usuario_id"] = $this->getUsuarioID();
                    $data["fecha_creacion"] = date('Y-m-d H:i:s', time());
                    $data["estado_id"] = "7";

                    $uni = explode("_", $data["unidad_id"]);
                    $data["flota_id"] = $uni[1];
                    $data["unidad_id"] = $uni[2];

                    $data['e_falla_mayor'] = $this->request->data['e_falla_mayor'] ? "true" : "false";
                    $data['e_falla_acumulativa'] = $this->request->data['e_falla_acumulativa'] ? "true" : "false";
                    $data['e_falla_electrica'] = $this->request->data['e_falla_electrica'] ? "true" : "false";
                    $data['no_existe_falla'] = $this->request->data['no_existe_falla'] ? "true" : "false";


                    $this->Prebacklog->create();
                    $this->Prebacklog->save($data);
                    $folio = $this->Prebacklog->id;
                    //$faena_id = $this->Prebacklog->faena_id;
                    $faena_id = $uni[0];


                    if (isset($_FILES)) {
                        foreach ($_FILES['archivo']['tmp_name'] as $index => $tmpName) {
                            if (!empty($tmpName)) { // && is_uploaded_file($tmpName)
                                $tipo = $_FILES['archivo']['type'][$index];
                                if (($tipo == "image/gif") ||
                                        ($tipo == "image/png") ||
                                        ($tipo == "image/jpeg") ||
                                        ($tipo == "image/jpg") ||
                                        ($tipo == "application/pdf") ||
                                        ($tipo == 'application/vnd.ms-excel') ||
                                        ($tipo == 'applicación/excel') ||
                                        ($tipo == 'applicación/vnd.msexcel') ||
                                        ($tipo == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') ||
                                        ($tipo == 'text/plain') ||
                                        ($tipo == 'text/csv') ||
                                        ($tipo == 'text/tsv')) {

                                    try {
                                        $name = $folio . '-' . $_FILES['archivo']['name'][$index];
                                        $url = $this->upload($name, $tmpName);
                                    } catch (Exception $e) {
                                        $this->Session->setFlash("error al subir archivo " . $e->getMessage(), 'guardar_error');
                                        $this->redirect("/Prebacklog/vista_aceite/" . $folio);
                                    }

                                    $archivo['nombre'] = $name; //$_FILES['archivo']['name'][$index]; //$data['comentario'];
                                    $archivo['usuario_id'] = $this->getUsuarioID();
                                    $archivo['ruta'] = $url;
                                    $archivo['prebacklog_id'] = $folio;
                                    $archivo['fecha'] = date('Y-m-d H:i:s', time());

                                    $this->Prebacklog_archivo->create();
                                    $this->Prebacklog_archivo->save($archivo);
                                } else {
                                    $this->Session->setFlash('Tipo de archivo no permitido, solo se deben adjuntar PDF, CSV o imagenes.', 'guardar_error');
                                    $this->redirect("/Prebacklog/vista_aceite/" . $folio);
                                    return;
                                }
                            }
                        }
                    }

                    //Limpia el comentario de cualquier tag
                    $data['comentario'] = Sanitize::html($data['comentario']);
                    
                    if (isset($data['comentario']) && $data['comentario'] != "") {
                        $comen['comentario'] = $data['comentario'];
                        $comen['usuario_id'] = $data['usuario_id'];
                        $comen['prebacklog_id'] = $folio;
                        $comen['fecha'] = $data['fecha_creacion'];

                        $this->Prebacklog_comentario->create();
                        $this->Prebacklog_comentario->save($comen);
                    }




                    if ($this->request->data['envia_correo'] == '1') {
                        $this->Mail($faena_id, $folio);

                        $this->Session->setFlash("Prebacklog folio $folio creado con éxito, correo enviado.", 'guardar_exito');
                    } else {
                        $this->Session->setFlash("Prebacklog folio $folio creado con éxito", 'guardar_exito');
                    }

                    $this->redirect("/Prebacklog/");
                } catch (Exception $e) {
                    $this->Session->setFlash("Ocurrió un error al intentar registrar el Prebacklog, por favor intentar nuevamente.", 'guardar_error');
                }
            }
        }
    }

    function prebacklog_aceite($id = '') {
        $this->set('titulo', 'Aceite');
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Prebacklog');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('PermisoUsuario');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sintoma');

        $this->loadModel('Prebacklog_alerta');

        $this->loadModel('Prebacklog_categoria');
        $this->loadModel('Prebacklog_comentario');
        $this->loadModel('Prebacklog_archivo');
        $this->loadModel('Prebacklog_alertaParametro');
        $this->loadModel('Prebacklog_motivoAceite');
        $this->loadModel('Prebacklog_parametros');


        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("PermisosFaenas"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("PermisosFaenas"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id' => $this->Session->read("PermisosFaenas"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        #$categoria_sintoma = $this->SintomaCategoria->find('list', array('order' => array("SintomaCategoria.nombre"), 'recursive' => -1));
        #$this->set(compact('categoria_sintoma'));
        #$sintomas = $this->Sintoma->find('all', array('fields' => array("Sintoma.sintoma_categoria_id", "Sintoma.id", "Sintoma.nombre", "Sintoma.codigo"), 'conditions' => array('Sintoma.e' => '1'), 'order' => array("Sintoma.nombre"), 'recursive' => -1));
        #$this->set(compact('sintomas'));

        $categoria = $this->Prebacklog_categoria->find('all', array('fields' => array("Prebacklog_categoria.id", "Prebacklog_categoria.nombre"), 'conditions' => array('Prebacklog_categoria.e' => '1'), 'order' => array("Prebacklog_categoria.nombre"), 'recursive' => -1));
        $this->set(compact('categoria'));

        $alertas = $this->Prebacklog_alerta->find('all', array('fields' => array("Prebacklog_alerta.id", "Prebacklog_alerta.nombre"), 'conditions' => array('Prebacklog_alerta.e' => '1'), 'order' => array("Prebacklog_alerta.nombre"), 'recursive' => -1));
        $this->set(compact('alertas'));

        $alerta_parametro = $this->Prebacklog_alertaParametro->find('all', array('fields' => array("Prebacklog_alertaParametro.id", "Prebacklog_alertaParametro.nombre", "Prebacklog_alertaParametro.alerta_id"), 'conditions' => array('Prebacklog_alertaParametro.e' => '1'), 'order' => array("Prebacklog_alertaParametro.alerta_id"), 'recursive' => -1));
        $this->set(compact('alerta_parametro'));

        $motivoaceites = $this->Prebacklog_motivoAceite->find('all', array('fields' => array("Prebacklog_motivoAceite.id", "Prebacklog_motivoAceite.motivo"), 'conditions' => array('Prebacklog_motivoAceite.e' => '1'), 'order' => array("Prebacklog_motivoAceite.motivo"), 'recursive' => -1));
        $this->set(compact('motivoaceites'));



        if ($id != "" && is_numeric($id)) {
            $prebacklog = $this->Prebacklog->find('first', array("conditions" => array("id" => $id), "fields" => "Prebacklog.*", 'recursive' => -1));
            $this->loadModel('Prebacklog_comentario');
            $comentarios = $this->Prebacklog_comentario->find('all',
                    array('conditions' => array('Prebacklog_comentario.prebacklog_id' => $id),
                        'order' => 'Prebacklog_comentario.fecha DESC', 'recursive' => 1));

            $parametros = $this->Prebacklog_parametros->find('all',
                    array('fields' => array('Prebacklog_parametros.*', 'Prebacklog_alertaParametro.*'),
                        'conditions' => array('Prebacklog_parametros.prebacklog_id' => $id), 'recursive' => 1));

           
            
            $this->set('comentarios', $comentarios);
            $this->set('parametros', $parametros);
            $this->set('faena_id', $prebacklog["Prebacklog"]["faena_id"]);
            $this->set('flota_id', $prebacklog["Prebacklog"]["flota_id"]);
            $this->set('unidad_id', $prebacklog["Prebacklog"]["equipo_id"]);
            $this->set('data', $prebacklog["Prebacklog"]);
        }

        if ($this->request->is('post')) {
            if (isset($this->request->data) && count($this->request->data) > 0) {
                try {
                    $data = $this->request->data;


                    $data["tipo"] = '1';
                    $data["usuario_id"] = $this->getUsuarioID();
                    $data["fecha_creacion"] = date('Y-m-d H:i:s', time());
                    $data["estado_id"] = "7";

                    $uni = explode("_", $data["unidad_id"]);
                    $data["flota_id"] = $uni[1];
                    $data["unidad_id"] = $uni[2];

                    $data['e_falla_mayor'] = $this->request->data['e_falla_mayor'] ? "true" : "false";
                    $data['e_falla_acumulativa'] = $this->request->data['e_falla_acumulativa'] ? "true" : "false";
                    $data['e_falla_electrica'] = $this->request->data['e_falla_electrica'] ? "true" : "false";
                    $data['no_existe_falla'] = $this->request->data['no_existe_falla'] ? "true" : "false";

                    $this->Prebacklog->create();
                    $this->Prebacklog->save($data);
                    $folio = $this->Prebacklog->id;
                    $faena_id = $uni[0]; //$this->Prebacklog->faena_id;

                    $this->Prebacklog_parametros->deleteAll(array('Prebacklog_parametros.prebacklog_id' => $folio), false);
                    //Recorro todos los tipos de alertas
                    foreach ($data['alerta'] as $key => $value) {
                        $idalerta = $value;
                        foreach ($data['paramAlerta_' . $idalerta] as $key1 => $value1) {

                            if ($data['param_alerta_' . $idalerta . '_' . $value1] > 0) {
                                $preback_param['alerta_parametro_id'] = $value1;
                                $preback_param['valor'] = $data['param_alerta_' . $idalerta . '_' . $value1];
                                $preback_param['motivoaceite_id'] = $data['motivo_aceite_' . $idalerta . '_' . $value1];
                                $preback_param['prebacklog_id'] = $folio;
                                $preback_param['sinvalor'] = isset($data['sinvalor_alerta_' . $idalerta . '_' . $value1]) ? '1' : '0';

                                $this->Prebacklog_parametros->create();
                                $this->Prebacklog_parametros->save($preback_param);
                            }
                        }
                    }

                    if (isset($_FILES)) {
                        foreach ($_FILES['archivo']['tmp_name'] as $index => $tmpName) {
                            if (!empty($tmpName)) { // && is_uploaded_file($tmpName)
                                $tipo = $_FILES['archivo']['type'][$index];
                                if (($tipo == "image/gif") ||
                                        ($tipo == "image/png") ||
                                        ($tipo == "image/jpeg") ||
                                        ($tipo == "image/jpg") ||
                                        ($tipo == "application/pdf") ||
                                        ($tipo == 'application/vnd.ms-excel') ||
                                        ($tipo == 'applicación/excel') ||
                                        ($tipo == 'applicación/vnd.msexcel') ||
                                        ($tipo == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') ||
                                        ($tipo == 'text/plain') ||
                                        ($tipo == 'text/csv') ||
                                        ($tipo == 'text/tsv')) {

                                    try {
                                        $name = $folio . '-' . $_FILES['archivo']['name'][$index];
                                        $url = $this->upload($name, $tmpName);
                                    } catch (Exception $e) {
                                        $this->Session->setFlash("error al subir archivo " . $e->getMessage(), 'guardar_error');
                                        $this->redirect("/Prebacklog/vista_aceite/" . $folio);
                                    }

                                    $archivo['nombre'] = $name; //$_FILES['archivo']['name'][$index]; //$data['comentario'];
                                    $archivo['usuario_id'] = $this->getUsuarioID();
                                    $archivo['ruta'] = $url;
                                    $archivo['prebacklog_id'] = $folio;
                                    $archivo['fecha'] = date('Y-m-d H:i:s', time());

                                    $this->Prebacklog_archivo->create();
                                    $this->Prebacklog_archivo->save($archivo);
                                } else {
                                    $this->Session->setFlash('Tipo de archivo no permitido, solo se deben adjuntar PDF, CSV o imagenes.', 'guardar_error');
                                    $this->redirect("/Prebacklog/vista_aceite/" . $folio);
                                }
                            }
                        }
                    }

                    //Limpia el comentario de cualquier tag
                    $data['comentario'] = Sanitize::html($data['comentario']);
                    
                    if (isset($data['comentario']) && $data['comentario'] != "") {
                        $comen['comentario'] = $data['comentario'];
                        $comen['usuario_id'] = $data['usuario_id'];
                        $comen['prebacklog_id'] = $folio;
                        $comen['fecha'] = $data['fecha_creacion'];

                        $this->Prebacklog_comentario->create();
                        $this->Prebacklog_comentario->save($comen);
                    }



                    if ($this->request->data['envia_correo'] == '1') {
                        $this->Mail($faena_id, $folio);

                        $this->Session->setFlash("Prebacklog folio $folio creado con éxito, correo enviado.", 'guardar_exito');
                    } else {
                        $this->Session->setFlash("Prebacklog folio $folio creado con éxito", 'guardar_exito');
                    }

                    $this->redirect("/Prebacklog/aceite");
                } catch (Exception $e) {
                    $this->Session->setFlash("Ocurrió un error al intentar registrar el Prebacklog, por favor intentar nuevamente.", 'guardar_error');
                }
            }
        }
    }

    function vista_parametros($id = '') {
        $this->set('titulo', 'Parámetros');
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Prebacklog');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('PermisoUsuario');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sintoma');

        $this->loadModel('Prebacklog_alerta');

        $this->loadModel('Prebacklog_categoria');
        $this->loadModel('Prebacklog_comentario');
        $this->loadModel('Prebacklog_archivo');
        $this->loadModel('Prebacklog_alertaParametro');
        $this->loadModel('Prebacklog_motivoAceite');
        $this->loadModel('Prebacklog_parametros');

        $this->loadModel('Backlog');


        if ($this->request->is('post')) {
            if (isset($this->request->data) && count($this->request->data) > 0) {
                try {
                    $data = $this->request->data;
                    if (isset($data['comentario']) && $data['comentario'] != "") {

                        if (isset($_FILES)) {
                            foreach ($_FILES['archivo']['tmp_name'] as $index => $tmpName) {
                                if (!empty($tmpName)) { // && is_uploaded_file($tmpName)
                                    $tipo = $_FILES['archivo']['type'][$index];
                                    if (($tipo == "image/gif") ||
                                            ($tipo == "image/png") ||
                                            ($tipo == "image/jpeg") ||
                                            ($tipo == "image/jpg") ||
                                            ($tipo == "application/pdf") ||
                                            ($tipo == 'application/vnd.ms-excel') ||
                                            ($tipo == 'applicación/excel') ||
                                            ($tipo == 'applicación/vnd.msexcel') ||
                                            ($tipo == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') ||
                                            ($tipo == 'text/plain') ||
                                            ($tipo == 'text/csv') ||
                                            ($tipo == 'text/tsv')) {

                                        try {
                                            $name = $data['id'] . '-' . $_FILES['archivo']['name'][$index];
                                            $url = $this->upload($name, $tmpName);
                                        } catch (Exception $e) {
                                            $this->Session->setFlash("error al subir archivo " . $e->getMessage(), 'guardar_error');
                                            $this->redirect("/Prebacklog/vista_aceite/" . $data['id']);
                                        }

                                        $archivo['nombre'] = $name; //$_FILES['archivo']['name'][$index]; //$data['comentario'];
                                        $archivo['usuario_id'] = $this->getUsuarioID();
                                        $archivo['ruta'] = $url;
                                        $archivo['prebacklog_id'] = $data['id'];
                                        $archivo['fecha'] = date('Y-m-d H:i:s', time());

                                        $this->Prebacklog_archivo->create();
                                        $this->Prebacklog_archivo->save($archivo);
                                    } else {
                                        $this->Session->setFlash('Tipo de archivo no permitido, solo se deben adjuntar PDF, CSV o imagenes.', 'guardar_error');
                                        $this->redirect("/Prebacklog/vista_aceite/" . $data['id']);
                                    }
                                }
                            }
                        }

                        //Limpia el comentario de cualquier tag
                        $data['comentario'] = Sanitize::html($data['comentario']);
                        
                        $comen['comentario'] = $data['comentario'];
                        $comen['usuario_id'] = $this->getUsuarioID();
                        $comen['prebacklog_id'] = $data['id'];
                        $comen['fecha'] = date('Y-m-d H:i:s', time());

                        $this->Prebacklog_comentario->create();
                        $this->Prebacklog_comentario->save($comen);



                        $this->Session->setFlash("Prebacklog folio" . $data['id'] . " modificado con éxito", 'guardar_exito');
                    }



                    $this->redirect("/Prebacklog/vista_parametros/" . $data['id']);
                } catch (Exception $e) {
                    $this->Session->setFlash("Ocurrió un error al intentar registrar el Prebacklog, por favor intentar nuevamente.", 'guardar_error');
                }
            }
        }

        if ($id != "" && is_numeric($id)) {
            $prebacklog = $this->Prebacklog->find('first',
                    array('fields' => array('Prebacklog.*', 'Prebacklog_categoria.nombre', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad',
                            'Estado.nombre_prebacklog', 'Estado.id', 'Criticidad.nombre', 'SintomaCategoria.nombre', 'Sintoma.nombre', 'Prebacklog_motivoCierre.motivo'),
                        "conditions" => array("Prebacklog.id" => $id), 'recursive' => 1));
            $comentarios = $this->Prebacklog_comentario->find('all',
                    array('conditions' => array('Prebacklog_comentario.prebacklog_id' => $id),
                        'order' => 'Prebacklog_comentario.fecha ASC', 'recursive' => 1));

            $arvhivos = $this->Prebacklog_archivo->find('all',
                    array('conditions' => array('Prebacklog_archivo.prebacklog_id' => $id),
                        'order' => 'Prebacklog_archivo.fecha DESC', 'recursive' => 1));

            $backlogs = $this->Backlog->find('all',
                    array('conditions' => array('Backlog.prebacklog_id' => $id),
                        'order' => 'Backlog.fecha_creacion asc', 'recursive' => 1));

            $this->set('comentarios', $comentarios);
            $this->set('arvhivos', $arvhivos);
            $this->set('backlogs', $backlogs);
            //$this->set('parametros', $parametros);
            $this->set('faena_id', $prebacklog["Prebacklog"]["faena_id"]);
            $this->set('flota_id', $prebacklog["Prebacklog"]["flota_id"]);
            $this->set('unidad_id', $prebacklog["Prebacklog"]["unidad_id"]);
            $this->set('data', $prebacklog);
        }
    }

    function vista_aceite($id = '') {
        $this->set('titulo', 'Aceite');
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Prebacklog');
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('PermisoUsuario');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sintoma');

        $this->loadModel('Prebacklog_alerta');

        $this->loadModel('Prebacklog_categoria');
        $this->loadModel('Prebacklog_comentario');
        $this->loadModel('Prebacklog_archivo');
        $this->loadModel('Prebacklog_alertaParametro');
        $this->loadModel('Prebacklog_motivoAceite');
        $this->loadModel('Prebacklog_parametros');

        $this->loadModel('Backlog');

        if ($this->request->is('post')) {
            if (isset($this->request->data) && count($this->request->data) > 0) {
                try {
                    $data = $this->request->data;
                    if (isset($data['comentario']) && $data['comentario'] != "") {
                        if (isset($_FILES)) {
                            foreach ($_FILES['archivo']['tmp_name'] as $index => $tmpName) {
                                if (!empty($tmpName)) { // && is_uploaded_file($tmpName)
                                    $tipo = $_FILES['archivo']['type'][$index];
                                    if (($tipo == "image/gif") ||
                                            ($tipo == "image/png") ||
                                            ($tipo == "image/jpeg") ||
                                            ($tipo == "image/jpg") ||
                                            ($tipo == "application/pdf") ||
                                            ($tipo == 'application/vnd.ms-excel') ||
                                            ($tipo == 'applicación/excel') ||
                                            ($tipo == 'applicación/vnd.msexcel') ||
                                            ($tipo == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') ||
                                            ($tipo == 'text/plain') ||
                                            ($tipo == 'text/csv') ||
                                            ($tipo == 'text/tsv')) {

                                        try {
                                            $name = $data['id'] . '-' . $_FILES['archivo']['name'][$index];
                                            $url = $this->upload($name, $tmpName);
                                        } catch (Exception $e) {
                                            $this->Session->setFlash("error al subir archivo " . $e->getMessage(), 'guardar_error');
                                            $this->redirect("/Prebacklog/vista_aceite/" . $data['id']);
                                        }

                                        $archivo['nombre'] = $name; //$_FILES['archivo']['name'][$index]; //$data['comentario'];
                                        $archivo['usuario_id'] = $this->getUsuarioID();
                                        $archivo['ruta'] = $url;
                                        $archivo['prebacklog_id'] = $data['id'];
                                        $archivo['fecha'] = date('Y-m-d H:i:s', time());

                                        $this->Prebacklog_archivo->create();
                                        $this->Prebacklog_archivo->save($archivo);
                                    } else {
                                        $this->Session->setFlash('Tipo de archivo no permitido, solo se deben adjuntar PDF, CSV o imagenes.', 'guardar_error');
                                        $this->redirect("/Prebacklog/vista_aceite/" . $data['id']);
                                    }
                                }
                            }
                        }
                        //Limpia el comentario de cualquier tag
                        $data['comentario'] = Sanitize::html($data['comentario']);
                        
                        $comen['comentario'] = $data['comentario'];
                        $comen['usuario_id'] = $this->getUsuarioID();
                        $comen['prebacklog_id'] = $data['id'];
                        $comen['fecha'] = date('Y-m-d H:i:s', time());

                        $this->Prebacklog_comentario->create();
                        $this->Prebacklog_comentario->save($comen);

                        $this->Session->setFlash("Prebacklog folio" . $data['id'] . " modificado con éxito", 'guardar_exito');
                    }



                    $this->redirect("/Prebacklog/vista_aceite/" . $data['id']);
                } catch (Exception $e) {
                    $this->Session->setFlash("Ocurrió un error al intentar registrar el Prebacklog, por favor intentar nuevamente.", 'guardar_error');
                }
            }
        }

        if ($id != "" && is_numeric($id)) {
            $prebacklog = $this->Prebacklog->find('first',
                    array('fields' => array('Prebacklog.*', 'Prebacklog_categoria.nombre', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Estado.nombre', 'Estado.id', 'Criticidad.nombre', 'Prebacklog_motivoCierre.motivo'),
                        "conditions" => array("Prebacklog.id" => $id), 'recursive' => 1));
            $comentarios = $this->Prebacklog_comentario->find('all',
                    array('conditions' => array('Prebacklog_comentario.prebacklog_id' => $id),
                        'order' => 'Prebacklog_comentario.fecha ASC', 'recursive' => 1));
            $parametros = $this->Prebacklog_parametros->find('all',
                    array('fields' => array('Prebacklog_parametros.*', 'Prebacklog_alertaParametro.*', 'Prebacklog_motivoAceite.motivo'),
                        'conditions' => array('Prebacklog_parametros.prebacklog_id' => $id),
                        'joins' => array(
                            array(
                                'table' => 'prebacklog_alerta',
                                'alias' => 'preAlert',
                                'type' => 'LEFT',
                                'conditions' => array(
                                    'preAlert.id = "Prebacklog_alertaParametro".alerta_id'
                                )
                            )
                        ),
                        'recursive' => 2));

            $arvhivos = $this->Prebacklog_archivo->find('all',
                    array('conditions' => array('Prebacklog_archivo.prebacklog_id' => $id),
                        'order' => 'Prebacklog_archivo.fecha DESC', 'recursive' => 1));

            $backlogs = $this->Backlog->find('all',
                    array('conditions' => array('Backlog.prebacklog_id' => $id),
                        'order' => 'Backlog.fecha_creacion asc', 'recursive' => 1));
            
            
            
            $this->set('comentarios', $comentarios);
            $this->set('arvhivos', $arvhivos);
            $this->set('backlogs', $backlogs);
            $this->set('parametros', $parametros);
            $this->set('faena_id', $prebacklog["Prebacklog"]["faena_id"]);
            $this->set('flota_id', $prebacklog["Prebacklog"]["flota_id"]);
            $this->set('unidad_id', $prebacklog["Prebacklog"]["unidad_id"]);
            $this->set('data', $prebacklog);
        }
    }

    public function comentarios($id) {
        $this->layout = null;
        $this->loadModel('Prebacklog_comentario');
        $historial = $this->Prebacklog_comentario->find('all',
                array('conditions' => array('Prebacklog_comentario.prebacklog_id' => $id),
                    'order' => 'Prebacklog_comentario.fecha asc', 'recursive' => 1));
        $this->set('comentarios', $historial);
    }

    public function Mail($faena_id, $id) {
        $util = new UtilidadesController();
        $email = new CakeEmail();
        $this->loadModel('Prebacklog');
        $this->loadModel('Prebacklog_envioEmail');
        $this->loadModel('Prebacklog_envioEmailFaena');
        $this->loadModel('Prebacklog_alerta');

        $this->loadModel('Prebacklog_categoria');
        $this->loadModel('Prebacklog_comentario');
        $this->loadModel('Prebacklog_archivo');
        $this->loadModel('Prebacklog_alertaParametro');
        $this->loadModel('Prebacklog_motivoAceite');
        $this->loadModel('Prebacklog_parametros');
        $this->loadModel('Usuario');
        
        $email->config('amazon');
        $email->emailFormat('html');
        
        $destinatarios = array();
        $tipo = "";
        try {

            $prebacklog = $this->Prebacklog->find('first',
                    array('fields' => array('Prebacklog.*', 'Prebacklog_categoria.nombre', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad',
                            'Estado.nombre', 'Estado.id', 'Criticidad.nombre', 'SintomaCategoria.nombre', 'Sintoma.nombre', 'Prebacklog_motivoCierre.motivo'),
                        "conditions" => array("Prebacklog.id" => $id), 'recursive' => 1));

            $comentarios = $this->Prebacklog_comentario->find('all',
                    array('conditions' => array('Prebacklog_comentario.prebacklog_id' => $id),
                        'order' => 'Prebacklog_comentario.fecha ASC', 'recursive' => 1));

            
            
            
            
            $usuarios = $this->Prebacklog_envioEmail->query("Select Prebacklog_envioEmail.email
                        FROM prebacklog_envioemail
                        LEFT JOIN prebacklog_envioemail_faena ON (Prebacklog_envioEmail.id = Prebacklog_envioEmail_Faena.envioemail_id)  
                        Where Prebacklog_envioemail_faena.faena_id = ".$faena_id."  and receptor = '1' and e = '1'");
            
                        
            foreach ($usuarios as $usuario) {
                if (isset($usuario[0]["email"])) {
                    $destinatarios[] = $usuario[0]["email"];
                }
            }

            
            $primer_comentario = '';
            foreach($comentarios as $comentario){
                $primer_comentario = html_entity_decode($comentario['Prebacklog_comentario']['comentario']);
            }
            

            if ($prebacklog["Prebacklog"]['tipo'] == 0) {
                $tipo = "Parámetros";
            } else {
                $tipo = "Aceite";
            }

            #$destinatarios[] = "aaron.zuniga@cummins.cl";
            $html = "<html>";
            $html .= "<body>";
            $html .= "<table width=\"100%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
            $html .= "<tr style=\"background-color: #d02323; color: white;\">";
            $html .= "<td style=\"background-color: #d02323; color: white; text-align: center;\" colspan=\"2\">Se creó un Prebacklog de $tipo </td>";
            $html .= "</tr>";
            $html .= "</table>";
            $html .= '<table border="1" width="100%">';
            $html .= '	<tr>';
            $html .= '		<th>Faena</th>';
            $html .= "           <td nowrap>{$prebacklog["Faena"]['nombre']}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Flota</th>';
            $html .= "		<td nowrap>{$prebacklog["Flota"]['nombre']}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Equipo</th>';
            $html .= "		<td nowrap>{$prebacklog["Unidad"]["unidad"]}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Categoría</th>';
            $html .= "		<td nowrap>" . html_entity_decode($prebacklog['Prebacklog_categoria']['nombre']) . "</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Folio</th>';
            $html .= "		<td nowrap>{$prebacklog['Prebacklog']['id']}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Fecha</th>';
            $html .= "		<td nowrap>" . date("d-m-Y", strtotime($prebacklog['Prebacklog']["fecha_creacion"])) . "</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Criticidad</th>';
            $html .= "		<td nowrap>{$prebacklog['Criticidad']['nombre']}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Comentario</th>';
            $html .= "		<td nowrap>" . $primer_comentario . "</td>";
            $html .= '	</tr>';
            $html .= '	<tr>';
            $html .= '		<th>IR</th>';
            $html .= '		<td nowrap><a href="https://dbm.cummins.cl/" target="_blank">dbm.cummins.cl</a></td>';
            $html .= '   </tr>';
            $html .= '</table>';
            $html .= "</body>";
            $html .= "</html>";

            $asunto = "DBM - Se generó un Prebacklog de " . $tipo; //. $int["Faena"];

            
            
            /* if(MAIL_DEBUG == ""){
              if(is_array($destinatarios) && count($destinatarios) > 0) {

              $this->AWSSES->to = $destinatarios;
              $this->AWSSES->sendRaw(utf8_encode($asunto), utf8_encode($html));
              $this->AWSSES->charset = 'UTF-8';

              }
              } else {
              $destinatarios = array();
              $destinatarios[] = MAIL_DEBUG;
              $this->AWSSES->to = $destinatarios;
              $this->AWSSES->sendRaw(utf8_encode($asunto), utf8_encode($html));
              } */
            
            if (is_array($destinatarios) && count($destinatarios) > 0) {
                $this->sendMail($destinatarios, $asunto, $html);
            }
            
            /*if (MAIL_DEBUG != "") {
                $destinatarios = array();
                $destinatarios[] = MAIL_DEBUG;
                $this->sendMail($destinatarios, $asunto, $html2);
            }*/
            //$this->AWSSES->reset();
            $email->reset();
            //$this->Session->setFlash("Prebacklog folio " . $id . " correo enviado con éxito", 'guardar_exito');
        } catch (Exception $e) {
            //$this->Session->setFlash('No se pudo enviar correo '. $e->getMessage(), 'guardar_error');
            $this->logger($this, $e->getMessage());
        }
        //$this->redirect('/Medicion');
    }

    public function upload($file_name, $temp_name) {
        try {
            $s3 = S3Client::factory(
                            array(
                                'credentials' => array(
                                    'key' => AMAZON_S3_ACCESS_KEY_ID,
                                    'secret' => AMAZON_S3_SECRET_ACCESS_KEY
                                ),
                                'version' => 'latest',
                                'region' => AMAZON_S3_REGION
                            )
            );
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }

        $keyName = basename($file_name);
        $pathInS3 = 'https://s3.' . AMAZON_S3_REGION . '.amazonaws.com/' . AMAZON_S3_BUCKET_NAME . '/' . (AMAZON_S3_BUCKET_DIRECTORY_NAME != '' ? AMAZON_S3_BUCKET_DIRECTORY_NAME . "/" : "") . $file_name;

        try {
            $file = $temp_name;
            $s3->putObject(
                    array(
                        'Bucket' => AMAZON_S3_BUCKET_NAME,
                        'Key' => (AMAZON_S3_BUCKET_DIRECTORY_NAME != '' ? AMAZON_S3_BUCKET_DIRECTORY_NAME . "/" . $keyName : $keyName),
                        'SourceFile' => $file,
                    //'StorageClass' => 'REDUCED_REDUNDANCY'
                    )
            );
        } catch (S3Exception $e) {
            die('Error:' . $e->getMessage());
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }

        return $pathInS3;
    }

    public function descarga($nombre) {
        try {
            $s3 = S3Client::factory(
                            array(
                                'credentials' => array(
                                    'key' => AMAZON_S3_ACCESS_KEY_ID,
                                    'secret' => AMAZON_S3_SECRET_ACCESS_KEY
                                ),
                                'version' => 'latest',
                                'region' => AMAZON_S3_REGION
                            )
            );
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }

        try {

            $url = $s3->getObjectUrl(AMAZON_S3_BUCKET_NAME, $nombre);

            $obj = $s3->getObject(
                    array(
                        'Bucket' => AMAZON_S3_BUCKET_NAME,
                        'Key' => (AMAZON_S3_BUCKET_DIRECTORY_NAME != '' ? AMAZON_S3_BUCKET_DIRECTORY_NAME . "/" . $nombre : $nombre),
                    )
            );

            // Display the object in the browser.
            header("Content-Type: {$obj['ContentType']}");
            echo $obj['Body'];
        } catch (S3Exception $e) {
            die('Error:' . $e->getMessage());
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }

        exit;
    }

    public function eliminarArchivo($nombre, $id, $tipo, $idPrebacklog) {
        $this->loadModel('Prebacklog_archivo');

        try {
            $s3 = S3Client::factory(
                            array(
                                'credentials' => array(
                                    'key' => AMAZON_S3_ACCESS_KEY_ID,
                                    'secret' => AMAZON_S3_SECRET_ACCESS_KEY
                                ),
                                'version' => 'latest',
                                'region' => AMAZON_S3_REGION
                            )
            );
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }

        try {

            $result = $s3->deleteObject([
                'Bucket' => AMAZON_S3_BUCKET_NAME,
                'Key' => (AMAZON_S3_BUCKET_DIRECTORY_NAME != '' ? AMAZON_S3_BUCKET_DIRECTORY_NAME . "/" . $nombre : $nombre),
            ]);

            $this->Prebacklog_archivo->delete($id);

            $this->Session->setFlash("Archivo $nombre Eliminado", 'guardar_exito');

            if ($tipo == 0) {
                $this->redirect("/Prebacklog/vista_parametros/$idPrebacklog");
            } else {
                $this->redirect("/Prebacklog/vista_aceite/$idPrebacklog");
            }
        } catch (S3Exception $e) {
            exit('Error: ' . $e->getAwsErrorMessage() . PHP_EOL);
        }

        exit;
    }

    public function validar() {
        $this->loadModel('Prebacklog');
        $this->loadModel('Prebacklog_comentario');
        $this->loadModel('Backlog');

        $idprebacklog = $_GET['idPrebacklog'];
        $tipo = $_GET['tipo'];
        $opcion = $_GET['option'];
        $comentario = $_GET['comentario'];

        $efm = $_GET['efm'] == 1 ? 'true' : 'false';
        $efa = $_GET['efa' ]== 1 ? 'true' : 'false';
        $efe = $_GET['efe'] == 1 ? 'true' : 'false';
        $nef = $_GET['nef'] == 1 ? 'true' : 'false';

        try {
            //inserta un comentario con la aprobacion de la bitacora
            $comen['comentario'] = $comentario;
            $comen['usuario_id'] = $this->getUsuarioID();
            $comen['prebacklog_id'] = $idprebacklog;
            $comen['fecha'] = date("Y-m-d H:i:s");

            $this->Prebacklog_comentario->create();
            $this->Prebacklog_comentario->save($comen);

            if ($opcion == 1) {
                $this->redirect("/Backlog/web?idPrebacklog=" . $idprebacklog . "&tipo=" . $tipo . "&validationes=ANZR6hkai1S4lV4DoR%C4m1l4YA4r0N-DCC");
            } else {

                //cerrar prebacklog
                $this->Prebacklog->updateAll(array('Prebacklog.estado_id' => 13),array('Prebacklog.id' => $idprebacklog));
                $this->Prebacklog->updateAll(array('Prebacklog.e_falla_mayor' => $efm),array('Prebacklog.id' => $idprebacklog));
                $this->Prebacklog->updateAll(array('Prebacklog.e_falla_acumulativa' => $efa),array('Prebacklog.id' => $idprebacklog));
                $this->Prebacklog->updateAll(array('Prebacklog.e_falla_electrica' => $efe),array('Prebacklog.id' => $idprebacklog));
                $this->Prebacklog->updateAll(array('Prebacklog.no_existe_falla' => $nef),array('Prebacklog.id' => $idprebacklog));

                $this->Session->setFlash("Prebacklog folio $idprebacklog cerrado con éxito", 'guardar_exito');

                if ($tipo == 0) {
                    $this->redirect("/Prebacklog/index");
                } else {
                    $this->redirect("/Prebacklog/aceite");
                }
            }
        } catch (Exception $e) {
            $this->Session->setFlash("Ocurrió un error al intentar registrar el Prebacklog, por favor intentar nuevamente." + $e->getMessage(), 'guardar_error');
        }
    }

    public function eliminar($id, $tipo) {
        $this->loadModel('Prebacklog');
        if ($id != 0 && $id != "") {
            $this->Prebacklog->delete($id);

            $this->Session->setFlash("Prebacklog folio $id Eliminado", 'guardar_exito');

            if ($tipo == 0) {
                $this->redirect("/Prebacklog/index");
            } else {
                $this->redirect("/Prebacklog/aceite");
            }
        }
    }

    public function descarga_aceite() {
        $this->loadModel('Prebacklog');
        $this->loadModel('Prebacklog_parametros');
        $this->loadModel('Prebacklog_comentario');
        $this->loadModel('Backlog');

        $conditions = array();
        $conditions["Prebacklog.faena_id IN"] = $this->Session->read("PermisosFaenas");
        $conditions["Prebacklog.tipo"] = '1';
        if ($this->request->is('get')) {
            if (isset($this->request->query['folio']) && is_numeric($this->request->query['folio'])) {
                $conditions["Prebacklog.id"] = $this->request->query['folio'];
            }

            if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
                $conditions["Prebacklog.faena_id"] = $this->request->query['faena_id'];
            }

            if (isset($this->request->query['estado_id']) && is_numeric($this->request->query['estado_id'])) {
                $conditions["Prebacklog.estado_id"] = $this->request->query['estado_id'];
            }
            if (isset($this->request->query['criticidad_id']) && is_numeric($this->request->query['criticidad_id'])) {
                $conditions["Prebacklog.criticidad_id"] = $this->request->query['criticidad_id'];
            }
            if (isset($this->request->query['fecha_inicio']) && $this->request->query['fecha_inicio'] != '') {
                $fecha_inicio = $this->request->query['fecha_inicio'];
                $conditions["Prebacklog.fecha_creacion >="] = $fecha_inicio;
            }
            if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != '') {
                $fecha_termino = $this->request->query['fecha_termino'];
                $conditions["Prebacklog.fecha_creacion <="] = $fecha_termino;
            }
            foreach ($this->request->query as $key => $value) {
                $this->set($key, $value);
            }
            if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
                $flota_id = $this->request->query['flota_id'];
                $conditions["Prebacklog.flota_id"] = $flota_id;
            }
            if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
                $unidad_id = $this->request->query['unidad_id'];
                $conditions["Prebacklog.unidad_id"] = $unidad_id;
            }
            $query = http_build_query($this->request->query);
            $this->set(compact('query'));
        }



        $registro = $this->Prebacklog->find('all', array(
            'fields' => array('Prebacklog.*', 'Usuario.u', 'Usuario.nombres', 'Usuario.apellidos', 'Criticidad.nombre', 'Estado.nombre', 'Estado.id', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Prebacklog_categoria.nombre'),
            'conditions' => $conditions,
            'order' => 'Prebacklog.fecha_creacion DESC',
            'recursive' => 1
        ));


        $ids = array();
        $aceites = array();
        $registros_id = array();
        $comentarios_id = array();
        $parametros_id = array();
        $backlogs_id = array();

        foreach ($registro as $reg) {
            $ids[] = $reg['Prebacklog']['id'];
            $registros_id[$reg['Prebacklog']['id']] = $reg;
        }

        //Obtengo todos los comentarios correspondientes a la descarga de informacion
        $comentarios = $this->Prebacklog_comentario->find('all',
                array(
                    'fields' => array('Prebacklog_comentario.*', 'Usuario.nombres', 'Usuario.apellidos'),
                    'conditions' => (count($ids) <= 1 ?  array('Prebacklog_comentario.prebacklog_id' => $ids) : array('Prebacklog_comentario.prebacklog_id IN' => $ids)),
                    'order' => array('Prebacklog_comentario.prebacklog_id', 'Prebacklog_comentario.fecha ASC'),
                    'recursive' => 1));

        //Obtengo todos los parametros correspondientes a la descarga de informacion
        $parametros = $this->Prebacklog_parametros->find('all',
                array('fields' => array('Prebacklog_parametros.*', 'Prebacklog_alertaParametro.nombre', 'Prebacklog_motivoAceite.motivo', 'Prebacklog_alerta.nombre'),
                    'conditions' => (count($ids) <= 1 ?  array('Prebacklog_parametros.prebacklog_id' => $ids) : array('Prebacklog_parametros.prebacklog_id IN' => $ids)),
                    'joins' => array(
                        array(
                            'table' => 'prebacklog_alerta',
                            'alias' => 'Prebacklog_alerta',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Prebacklog_alerta.id = "Prebacklog_alertaParametro".alerta_id'
                            )
                        )
                    ),
                    'recursive' => 1));


        //Obtengo todos los backlogs correspondientes a la descarga de informacion
        $backlogs = $this->Backlog->find('all',
                array(
                    'fields' => array('Backlog.prebacklog_id', 'Backlog.id', 'Backlog.intervencion_id'),
                    'conditions' => (count($ids) <= 1 ? array('Backlog.prebacklog_id' => $ids) : array('Backlog.prebacklog_id IN' => $ids)),
                    'order' => 'Backlog.fecha_creacion asc', 'recursive' => 1));


        //!** ORDENO LA INFORMACION **!
        //separo comentarios por id de prebacklog
        foreach ($comentarios as $comen) {
            $comen['Prebacklog_comentario']['comentario'] = html_entity_decode($comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace(";", ":", $comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace("\t", "", $comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace("\r\n", "", $comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace("\n", "", $comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace("\r", "", $comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace('"', '', $comen['Prebacklog_comentario']['comentario']);

            $registros_id[$comen['Prebacklog_comentario']['prebacklog_id']]['comentarios'][$comen['Prebacklog_comentario']['id']] = ($comen);
        }

        //print_r($parametros);

        //separo parametros por id de prebacklog
        foreach ($parametros as $param) {
            $parametros_id['alerta'][$param['Prebacklog_parametros']['prebacklog_id']][] = html_entity_decode($param['Prebacklog_alerta']['nombre']);
            $parametros_id['valor'][$param['Prebacklog_parametros']['prebacklog_id']][] = $param['Prebacklog_parametros']['valor'];
            $parametros_id['parametro'][$param['Prebacklog_parametros']['prebacklog_id']][] = html_entity_decode($param['Prebacklog_alertaParametro']['nombre']);

            $registros_id[$param['Prebacklog_parametros']['prebacklog_id']]['parametros'] = $parametros_id;
        }


        //separo backlogs por id de prebacklog
        foreach ($backlogs as $bkl) {
            $registros_id[$bkl['Backlog']['prebacklog_id']]['backlogs'][$bkl['Backlog']['id']] = $bkl;
        }

        //print_r($registros_id);

        foreach ($ids as $id) {

            $aceite[$id]['id'] = $id;
            $aceite[$id]['faena'] = $registros_id[$id]['Faena']['nombre'];
            $aceite[$id]['flota'] = $registros_id[$id]['Flota']['nombre'];
            $aceite[$id]['unidad'] = $registros_id[$id]['Unidad']['unidad'];
            $aceite[$id]['esn'] = $registros_id[$id]['Prebacklog']['esn'];
            $aceite[$id]['estado'] = $registros_id[$id]['Estado']['nombre'];
            $aceite[$id]['tipo'] = 'Analisis de aceite';


            //Obtengo las distintas alertas
            $unico = array();
            $alerta = '';
            $paramet = '';

            //print_r($registros_id[$id]['parametros']);

            //$unico[$id][] = array_unique($registros_id[$id]['parametros']['alerta'][$id]);
            $unico[$id][] = $registros_id[$id]['parametros']['alerta'][$id];

            //print_r($unico[$id]);

            for($i = 0; $i <= count($registros_id[$id]['parametros']['alerta'][$id]); $i ++){
                //print_r($registros_id[$id]['parametros']['alerta'][$id][$i]. ' & ');
                if($registros_id[$id]['parametros']['alerta'][$id][$i]) {
                    $alerta .= ' & '. $registros_id[$id]['parametros']['alerta'][$id][$i];
                    $paramet .= ' & ' . $registros_id[$id]['parametros']['parametro'][$id][$i];
                }
            }

            /*foreach ($unico as $pm_id) {
                $alerta .= $pm_id . ' & ';
            }*/
            //print_r(substr(trim($alerta), 2));
            $aceite[$id]['alerta'] = substr($alerta, 2);
            $aceite[$id]['parametro'] = substr($paramet, 2);

            $aceite[$id]['criticidad'] = $registros_id[$id]['Criticidad']['nombre'];
            $aceite[$id]['fecha_evento'] = date_format(date_create($registros_id[$id]['Prebacklog']['fecha_creacion']), 'd/m/Y');
            $aceite[$id]['hora_evento'] = date_format(date_create($registros_id[$id]['Prebacklog']['fecha_creacion']), 'h:00:00');
            $aceite[$id]['fecha_creacion'] = date_format(date_create($registros_id[$id]['Prebacklog']['fecha_creacion']), 'd/m/Y');
            $aceite[$id]['mes_creacion'] = date_format(date_create($registros_id[$id]['Prebacklog']['fecha_creacion']), 'm');
            $aceite[$id]['creador'] = $registros_id[$id]['Usuario']['nombres'] . ' ' . $registros_id[$id]['Usuario']['apellidos'];

            //Diferencias de dias para fechas
            $crea = new DateTime($registros_id[$id]['Prebacklog']['fecha_creacion']);
            $hoy = new DateTime(date('Y-m-d H:i:s'));
            $intervalo = $crea->diff($hoy);
            $aceite[$id]['dias_desde_creacion'] = $intervalo->days;
            $aceite[$id]['dias_desde_evento'] = $intervalo->days;


            //comentarios
            $arr_comen = $registros_id[$id]['comentarios'];
            $max_id_coment = max(array_keys($arr_comen));
            $min_id_coment = min(array_keys($arr_comen));

            $aceite[$id]['fecha_ultimo_movimiento'] = date_format(date_create($registros_id[$id]['comentarios'][$max_id_coment]['Prebacklog_comentario']['fecha']), 'd/m/Y');
            $aceite[$id]['hora_ultimo_movimiento'] = date_format(date_create($registros_id[$id]['comentarios'][$max_id_coment]['Prebacklog_comentario']['fecha']), 'h:i:s');
            $aceite[$id]['gestor_ultimo_movimiento'] = $registros_id[$id]['comentarios'][$max_id_coment]['Usuario']['nombres'] . ' ' . $registros_id[$id]['comentarios'][$max_id_coment]['Usuario']['apellidos'];
            //$aceite[$id]['cargo_gestor_ultimo_movimiento'] = '';


            $aceite[$id]['reporte_inicial'] = $registros_id[$id]['comentarios'][$min_id_coment]['Prebacklog_comentario']['comentario'];
            $aceite[$id]['ultimo_reporte'] = $registros_id[$id]['comentarios'][$max_id_coment]['Prebacklog_comentario']['comentario'];

            $historial = '';
            foreach ($registros_id[$id]['comentarios'] as $historia) {
                $historial .= ' - (' . date_format(date_create($historia['Prebacklog_comentario']['fecha']), 'd/m/Y H:i:s') . ') ' . $historia['Prebacklog_comentario']['comentario']; //. PHP_EOL;
            }
            $aceite[$id]['historial'] = $historial;

            ///backlog e intervenciones
            $backlogses = '    ';
            $interv = "    ";
            foreach ($registros_id[$id]['backlogs'] as $bklgs) {
                $backlogses = trim($backlogses);
                $interv = trim($interv);

                $backlogses .= "F: " . $bklgs['Backlog']['id'] . " - ";
                $interv .= ($bklgs['Backlog']['intervencion_id'] != '' ? "C: " . $bklgs['Backlog']['intervencion_id'] . " - " : '');
                ;
            }
            $aceite[$id]['backlog'] = substr($backlogses, 0, -3);
            $aceite[$id]['intervencion'] = substr($interv, 0, -3);
        }


        //EMPIEZA CREACION DE EXCEL
        $dataArray = array();

        $utilReporte = new UtilidadesReporteController();
        $util = new UtilidadesController();

        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
        $objPHPExcel = new PHPExcel();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Cache-Control: max-age=0');
        header('Content-Disposition: attachment;filename="Prebacklog_Aceite-' . date("Y-m-d") . '".xlsx');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . date('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objPHPExcel->getProperties()
                ->setCreator("DBM")
                ->setLastModifiedBy("DBM")
                ->setTitle("Prebacklog_Aceite");
        $objPHPExcel->setActiveSheetIndex(0)->setTitle('Prebacklog_Aceite');

        $i = 1;
        $filaInicial = 2;
        $enc = array();
        foreach ($aceite as $value) {
            $dataTemp = array();
            $linea = i + $filaInicial++;
            $dataTemp[] = $value['id'];
            $dataTemp[] = $value['faena'];
            $dataTemp[] = $value['flota'];
            $dataTemp[] = $value['unidad'];
            $dataTemp[] = $value['esn'];
            $dataTemp[] = $value['estado'];
            $dataTemp[] = $value['tipo'];

            $dataTemp[] = html_entity_decode($value['alerta']);

            $dataTemp[] = html_entity_decode($value['parametro']);

            $dataTemp[] = $value['criticidad'];
            $dataTemp[] = $value['fecha_evento'];
            $dataTemp[] = $value['hora_evento'];
            $dataTemp[] = $value['fecha_creacion'];
            $dataTemp[] = $value['mes_creacion'];
            $dataTemp[] = $value['creador'];

            $dataTemp[] = $value['dias_desde_creacion'];
            $dataTemp[] = $value['dias_desde_evento'];

            $dataTemp[] = $value['fecha_ultimo_movimiento'];
            $dataTemp[] = $value['hora_ultimo_movimiento'];
            $dataTemp[] = $value['gestor_ultimo_movimiento'];
            //$dataTemp[] = $value['cargo_gestor_ultimo_movimiento'];
            $dataTemp[] = ($value['reporte_inicial']);
            $dataTemp[] = ($value['ultimo_reporte']);

            $dataTemp[] = ($value['historial']);
            $dataTemp[] = $value['backlog'];
            $dataTemp[] = $value['intervencion'];

            $dataArray[] = $dataTemp;
        }

        // Encabezados
        $encabezados = array("Id prebacklog", "Faena", "Flota", "Unidad", "ESN", "Estado", "Tipo", "Alerta", "Parámetro", "Criticidad", "Fecha evento", "Hora evento", "Fecha creación", "Mes creacion", "Creador",
            "Dias desde creación", "Dias desde evento", "Fecha último evento", "Hora último evento", "Gestor último evento",
            //"Cargo gestor último evento", 
            "Reporte Inicial","Ultimo Reporte", "Historial", "Id backlog(s)", "Id intervención(es)");

        $count = count($encabezados);
        for ($i = 0; $i < $count; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 1, $encabezados[$i]);
        }
        $colString = PHPExcel_Cell::stringFromColumnIndex($count - 1);
        $utilReporte->cellColor("A1:" . $colString . "1", "1BA39C", "FFFFFF", $objPHPExcel);
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $colString . '1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true); //->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true); //->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true); //->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);

        $style_cell = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                //'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );

        $style_border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),)
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A1:V' . (count($aceite) + 1))->applyFromArray($style_cell);
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $colString . (count($aceite) + 1))->applyFromArray($style_border);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function descarga_parametros() {
        $this->loadModel('Prebacklog');
        $this->loadModel('Prebacklog_parametros');
        $this->loadModel('Prebacklog_comentario');
        $this->loadModel('Prebacklog_motivoCierre');
        $this->loadModel('Backlog');

        $conditions = array();
        //$limit = 25;
        $conditions["Prebacklog.faena_id IN"] = $this->Session->read("PermisosFaenas");
        $conditions["Prebacklog.tipo"] = '0';
        if ($this->request->is('get')) {
            if (isset($this->request->query['folio']) && is_numeric($this->request->query['folio'])) {
                $conditions["Prebacklog.id"] = $this->request->query['folio'];
            }
            /*if (isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
                $limit = $this->request->query['limit'];
            }*/
            if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
                $conditions["Prebacklog.faena_id"] = $this->request->query['faena_id'];
            }

            if (isset($this->request->query['estado_id']) && is_numeric($this->request->query['estado_id'])) {
                $conditions["Prebacklog.estado_id"] = $this->request->query['estado_id'];
            }
            if (isset($this->request->query['criticidad_id']) && is_numeric($this->request->query['criticidad_id'])) {
                $conditions["Prebacklog.criticidad_id"] = $this->request->query['criticidad_id'];
            }
            if (isset($this->request->query['fecha_inicio']) && $this->request->query['fecha_inicio'] != '') {
                $fecha_inicio = $this->request->query['fecha_inicio'];
                $conditions["Prebacklog.fecha_creacion >="] = $fecha_inicio;
            }
            if (isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != '') {
                $fecha_termino = $this->request->query['fecha_termino'];
                $conditions["Prebacklog.fecha_creacion <="] = $fecha_termino;
            }
            foreach ($this->request->query as $key => $value) {
                $this->set($key, $value);
            }
            if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
                $conditions["Prebacklog.flota_id"] = $this->request->query['flota_id'];
            }
            if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
                $conditions["Prebacklog.unidad_id"] = $this->request->query['unidad_id'];
            }
            $query = http_build_query($this->request->query);
            $this->set(compact('query'));
        }



        $registro = $this->Prebacklog->find('all', array(
            'fields' => array('Prebacklog.*', 'Usuario.u', 'Usuario.nombres', 'Usuario.apellidos', 'Criticidad.nombre', 'Estado.nombre', 'Estado.id', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Prebacklog_categoria.nombre', 'Sintoma.nombre', 'SintomaCategoria.nombre', 'Prebacklog_motivoCierre.motivo'),
            'conditions' => $conditions,
            'order' => 'Prebacklog.fecha_creacion DESC',
            'recursive' => 1
        ));


        $ids = array();
        $parametros = array();
        $registros_id = array();
        $comentarios_id = array();
        $parametros_id = array();
        $backlogs_id = array();

        foreach ($registro as $reg) {
            $ids[] = $reg['Prebacklog']['id'];
            $registros_id[$reg['Prebacklog']['id']] = $reg;
        }

        //Obtengo todos los comentarios correspondientes a la descarga de informacion
        $comentarios = $this->Prebacklog_comentario->find('all',
                array(
                    'fields' => array('Prebacklog_comentario.*', 'Usuario.nombres', 'Usuario.apellidos'),
                    'conditions' =>(count($ids) <= 1 ?  array('Prebacklog_comentario.prebacklog_id' => $ids) : array('Prebacklog_comentario.prebacklog_id IN' => $ids)),
                    'order' => array('Prebacklog_comentario.prebacklog_id', 'Prebacklog_comentario.fecha ASC'),
                    'recursive' => 1));

        //Obtengo todos los backlogs correspondientes a la descarga de informacion
        $backlogs = $this->Backlog->find('all',
                array(
                    'fields' => array('Backlog.prebacklog_id', 'Backlog.id', 'Backlog.intervencion_id'),
                    'conditions' => (count($ids) <= 1 ?  array('Backlog.prebacklog_id' => $ids) : array('Backlog.prebacklog_id IN' => $ids)),
                    'order' => 'Backlog.fecha_creacion asc', 'recursive' => 1));


        foreach ($comentarios as $comen) {
            $comen['Prebacklog_comentario']['comentario'] = html_entity_decode($comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace(";", ":", $comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace("\t", "", $comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace("\r\n", "", $comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace("\n", "", $comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace("\r", "", $comen['Prebacklog_comentario']['comentario']);
            $comen['Prebacklog_comentario']['comentario'] = str_replace('"', '', $comen['Prebacklog_comentario']['comentario']);

            $registros_id[$comen['Prebacklog_comentario']['prebacklog_id']]['comentarios'][$comen['Prebacklog_comentario']['id']] = $comen;
        }

        //separo backlogs por id de prebacklog
        foreach ($backlogs as $bkl) {
            $registros_id[$bkl['Backlog']['prebacklog_id']]['backlogs'][$bkl['Backlog']['id']] = $bkl;
        }

        //print_r($registros_id);

        foreach ($ids as $id) {

            $parametros[$id]['id'] = $id;
            $parametros[$id]['faena'] = $registros_id[$id]['Faena']['nombre'];
            $parametros[$id]['flota'] = $registros_id[$id]['Flota']['nombre'];
            $parametros[$id]['unidad'] = $registros_id[$id]['Unidad']['unidad'];
            $parametros[$id]['esn'] = $registros_id[$id]['Prebacklog']['esn'];
            $parametros[$id]['estado'] = $registros_id[$id]['Estado']['id'] == 3 ? 'Sin Planifiar' : $registros_id[$id]['Estado']['nombre'];
            $parametros[$id]['tipo'] = 'Parámetros';
            $parametros[$id]['categoria'] = $registros_id[$id]['Prebacklog_categoria']['nombre'];

            $parametros[$id]['cat_sintoma'] = $registros_id[$id]['SintomaCategoria']['nombre'];
            $parametros[$id]['sintoma'] = $registros_id[$id]['Sintoma']['nombre'];

            $parametros[$id]['criticidad'] = $registros_id[$id]['Criticidad']['nombre'];
            $parametros[$id]['fecha_evento'] = date_format(date_create($registros_id[$id]['Prebacklog']['fecha_creacion']), 'd/m/Y');
            $parametros[$id]['hora_evento'] = date_format(date_create($registros_id[$id]['Prebacklog']['fecha_creacion']), 'h:00:00');
            $parametros[$id]['fecha_creacion'] = date_format(date_create($registros_id[$id]['Prebacklog']['fecha_creacion']), 'd/m/Y');
            $parametros[$id]['mes_creacion'] = date_format(date_create($registros_id[$id]['Prebacklog']['fecha_creacion']), 'm');
            $parametros[$id]['semana_creacion'] = date_format(date_create($registros_id[$id]['Prebacklog']['fecha_creacion']), 'W');
            $parametros[$id]['creador'] = $registros_id[$id]['Usuario']['nombres'] . ' ' . $registros_id[$id]['Usuario']['apellidos'];


            if($registros_id[$id]['Prebacklog']['fecha_cierre'] !== null) {
                $parametros[$id]['fecha_cierre'] = date_format(date_create($registros_id[$id]['Prebacklog']['fecha_cierre']), 'd/m/Y');
                $parametros[$id]['semana_cierre'] = date_format(date_create($registros_id[$id]['Prebacklog']['fecha_cierre']), 'W');
            }else{
                $parametros[$id]['fecha_cierre'] = "";
                $parametros[$id]['semana_cierre'] = "";
            }
            //Diferencias de dias para fechas
            $crea = new DateTime($registros_id[$id]['Prebacklog']['fecha_creacion']);
            $hoy = new DateTime(date('Y-m-d H:i:s'));
            $intervalo = $crea->diff($hoy);
            $parametros[$id]['dias_desde_creacion'] = $intervalo->days;
            $parametros[$id]['dias_desde_evento'] = $intervalo->days;


            //comentarios
            $arr_comen = $registros_id[$id]['comentarios'];
            $max_id_coment = max(array_keys($arr_comen));
            $min_id_coment = min(array_keys($arr_comen));

            $parametros[$id]['fecha_ultimo_movimiento'] = date_format(date_create($registros_id[$id]['comentarios'][$max_id_coment]['Prebacklog_comentario']['fecha']), 'd/m/Y');
            $parametros[$id]['hora_ultimo_movimiento'] = date_format(date_create($registros_id[$id]['comentarios'][$max_id_coment]['Prebacklog_comentario']['fecha']), 'h:i:s');
            $parametros[$id]['gestor_ultimo_movimiento'] = $registros_id[$id]['comentarios'][$max_id_coment]['Usuario']['nombres'] . ' ' . $registros_id[$id]['comentarios'][$max_id_coment]['Usuario']['apellidos'];
            //$parametros[$id]['cargo_gestor_ultimo_movimiento'] = '';


            $parametros[$id]['reporte_inicial'] = $registros_id[$id]['comentarios'][$min_id_coment]['Prebacklog_comentario']['comentario'];
            $parametros[$id]['ultimo_reporte'] = $registros_id[$id]['comentarios'][$max_id_coment]['Prebacklog_comentario']['comentario'];

            $historial = '';
            $fecha = "";
            $diferencia = "";
            foreach ($registros_id[$id]['comentarios'] as $historia) {

                if(stripos($historia['Prebacklog_comentario']['comentario'], "Se creo el backlog" ) !== false){
                    $fecha .= 'FB: ' . date_format(date_create($historia['Prebacklog_comentario']['fecha']), 'd/m/Y') . " - ";
                    $fecha_back = new DateTime($historia['Prebacklog_comentario']['fecha']);
                    $inter = $crea->diff($fecha_back);
                    $diferencia .= 'D: ' . $inter->days . " - ";
                }

                $historial .= ' - (' . date_format(date_create($historia['Prebacklog_comentario']['fecha']), 'd/m/Y H:i:s') . ') ' . $historia['Prebacklog_comentario']['comentario']; //. PHP_EOL;
            }

            $parametros[$id]['historial'] = $historial;
            $parametros[$id]['fecha_bak'] = substr($fecha, 0, -3);
            $parametros[$id]['dias_bak'] = substr($diferencia, 0, -3);

            //backlog e intervenciones
            $backlogses = '    ';
            $interv = "    ";
            foreach ($registros_id[$id]['backlogs'] as $bklgs) {
                $backlogses = trim($backlogses);
                $interv = trim($interv);

                $backlogses .= "F: " . $bklgs['Backlog']['id'] . " - ";
                $interv .= ($bklgs['Backlog']['intervencion_id'] != '' ? "C: " . $bklgs['Backlog']['intervencion_id'] . " - " : '');
                ;
            }
            $parametros[$id]['backlog'] = substr($backlogses, 0, -3);
            $parametros[$id]['intervencion'] = substr($interv, 0, -3);
            $parametros[$id]['motivocierre'] = $registros_id[$id]['Prebacklog_motivoCierre']['motivo'];


            //Resultados de prebacklog, saber si evito falla
            $parametros[$id]['resultado'] = "";
            if($registros_id[$id]['Prebacklog']['e_falla_mayor']) $parametros[$id]['resultado'] = "Evitó falla mayor";
            if($registros_id[$id]['Prebacklog']['e_falla_acumulativa']) $parametros[$id]['resultado'] = "Evitó falla acumulativa";
            if($registros_id[$id]['Prebacklog']['e_falla_electrica']) $parametros[$id]['resultado'] = "Resuelve falla eléctrica";
            if($registros_id[$id]['Prebacklog']['no_existe_falla']) $parametros[$id]['resultado'] = "No existe falla";

        }

        //print_r($parametros);
        //EMPIEZA CREACION DE EXCEL
        $dataArray = array();

        $utilReporte = new UtilidadesReporteController();
        $util = new UtilidadesController();

        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
        $objPHPExcel = new PHPExcel();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Cache-Control: max-age=0');
        header('Content-Disposition: attachment;filename="Prebacklog_Parametros-' . date("Y-m-d") . '".xlsx');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . date('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objPHPExcel->getProperties()
                ->setCreator("DBM")
                ->setLastModifiedBy("DBM")
                ->setTitle("Prebacklog_Parametros");
        $objPHPExcel->setActiveSheetIndex(0)->setTitle('Prebacklog_Parametros');

        $i = 1;
        $filaInicial = 2;
        $enc = array();
        foreach ($parametros as $value) {
            $dataTemp = array();
            $linea = i + $filaInicial++;
            $dataTemp[] = $value['id'];
            $dataTemp[] = $value['faena'];
            $dataTemp[] = $value['flota'];
            $dataTemp[] = $value['unidad'];
            $dataTemp[] = $value['esn'];
            $dataTemp[] = $value['estado'];
            $dataTemp[] = $value['tipo'];
            $dataTemp[] = $value['categoria'];

            $dataTemp[] = $value['cat_sintoma'];
            $dataTemp[] = $value['sintoma'];

            $dataTemp[] = $value['criticidad'];
            $dataTemp[] = $value['fecha_evento'];
            $dataTemp[] = $value['hora_evento'];
            $dataTemp[] = $value['fecha_creacion'];
            $dataTemp[] = $value['mes_creacion'];
            $dataTemp[] = $value['semana_creacion'];
            $dataTemp[] = $value['creador'];

            $dataTemp[] = $value['dias_desde_creacion'];
            $dataTemp[] = $value['dias_desde_evento'];

            $dataTemp[] = $value['fecha_ultimo_movimiento'];
            $dataTemp[] = $value['hora_ultimo_movimiento'];
            $dataTemp[] = $value['gestor_ultimo_movimiento'];
            //$dataTemp[] = $value['cargo_gestor_ultimo_movimiento'];
            $dataTemp[] = $value['reporte_inicial'];
            $dataTemp[] = $value['ultimo_reporte'];

            $dataTemp[] = $value['historial'];
            $dataTemp[] = $value['fecha_bak'];
            $dataTemp[] = $value['dias_bak'];
            $dataTemp[] = $value['backlog'];
            $dataTemp[] = $value['intervencion'];
            $dataTemp[] = $value['motivocierre'];
            $dataTemp[] = $value['fecha_cierre'];
            $dataTemp[] = $value['semana_cierre'];
            $dataTemp[] = $value['resultado'];
            $dataArray[] = $dataTemp;
        }

        // Encabezados
        $encabezados = array("Id prebacklog", "Faena", "Flota", "Unidad", "ESN", "Estado", "Tipo", "Categoría", "Cat. Síntoma", "Síntoma", "Criticidad", "Fecha evento", "Hora evento", "Fecha creación", "Mes creación", "Semana creación", "Creador",
            "Dias desde creación", "Dias desde evento", "Fecha último evento", "Hora último evento", "Gestor último evento",
            //"Cargo gestor último evento", 
            "Reporte Inicial","Ultimo Reporte", "Historial", "Fec. backlog(s)", "Dias desde creación a backlog" ,"Id backlog(s)", "Id intervención(es)", "Motivo de cierre","Fecha cierre", "Semana cierre", "Evito falla");

        $count = count($encabezados);
        for ($i = 0; $i < $count; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 1, $encabezados[$i]);
        }
        $colString = PHPExcel_Cell::stringFromColumnIndex($count - 1);
        $utilReporte->cellColor("A1:" . $colString . "1", "4c87b9", "FFFFFF", $objPHPExcel);
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $colString . '1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true); //->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true); //->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true); //->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);

        $style_cell = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                //'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );

        $style_border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),)
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A1:V' . (count($parametros) + 1))->applyFromArray($style_cell);
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $colString . (count($parametros) + 1))->applyFromArray($style_border);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    function sendMail($to, $subject, $body) {

        $this->ses = new Aws\Ses\SesClient([
            'version' => '2010-12-01',
            'region' => AMAZON_SES_REGION,
            'credentials' => [
                'key' => AMAZON_SES_ACCESS_KEY_ID,
                'secret' => AMAZON_SES_SECRET_ACCESS_KEY
            ]
        ]);

        if (is_string($to)) {
            $to = [$to];
        }

        $destination = array(
            'ToAddresses' => $to
        );
        $message = array(
            'Subject' => array(
                'Data' => $subject
            ),
            'Body' => array()
        );



        if ($body != NULL) {
            $message['Body']['Html'] = array(
                'Data' => $body
            );
        }

        $char_set = 'UTF-8';
        $ok = true;

        try {
            $response = $this->ses->sendEmail([
                'Destination' => $destination,
                'Source' => AMAZON_SES_FROM_EMAIL,
                'Message' => $message
            ]);
        } catch (AwsException $e) {
            $ok = false;
            $this->log('Error sending email from AWS SES: ' . $e->getMessage(), 'error');
            $this->log('Error sending email from AWS SES: ' . $e->getAwsErrorMessage(), 'error');
        }

        return $ok;
    }
}
