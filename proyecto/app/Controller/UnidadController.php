<?php

App::uses('ConnectionManager', 'Model');
App::import('Controller', 'Utilidades');
App::uses('CakeEmail', 'Network/Email');
App::uses('Sanitize', 'Utility');
require_once '../../vendor/autoload.php';

class UnidadController extends AppController {

    public $components = array('AWSSES');

    //public $scaffold = 'admin';
    public function select() {
        $faena_id = $this->request->query['faena_id'];
        $flota_id = $this->request->query['flota_id'];
        $this->layout = NULL;
        $this->loadModel('UnidadDetalle');
        if (is_numeric($faena_id) && is_numeric($flota_id)) {
            $unidad = $this->UnidadDetalle->find('all', array('fields' => array('Unidad.id', 'UnidadDetalle.id', 'UnidadDetalle.unidad'), 'order' => 'UnidadDetalle.unidad', 'conditions' => array('UnidadDetalle.flota_id' => $flota_id, 'UnidadDetalle.faena_id' => $faena_id, "Unidad.e='1'"), 'recursive' => 1));
        } else {
            $unidad = array();
        }
        $this->set('unidad', $unidad);
    }

    public function esn($id, $faena_id = 0, $flota_id = 0) {
        $util = new UtilidadesController();
        $this->layout = null;
        $this->loadModel('Unidad');
        $this->loadModel('Esn');
        /* $resultado = $this->Unidad->find('first', array(
          'conditions' => array('Unidad.id' => $id)
          )); */

        //$esn = $this->Esn->find('first', array( 
        //						'fields' => array('Esn.esn'),
        //						'conditions' => array("flota_id=$flota_id AND faena_id=$faena_id AND motor_id=$motor_id AND unidad='$unidad' AND (('$fecha'>=fecha_inicio AND '$fecha'<=fecha_termino) OR ('$fecha'>=fecha_inicio AND fecha_termino IS NULL))"),
        //						'recursive' => -1));
        //return @$esn["Esn"]["esn"];
        if ($flota_id != 0 && $faena_id != 0 && $id != 0) {
            $resultado["Unidad"]["esn"] = @$util->getESN($faena_id, $flota_id, $util->getMotor($id), $util->getUnidad($id), date("Y-m-d"));
            if ($resultado["Unidad"]["esn"] == "") {
                $resultado["Unidad"]["esn"] = "";
            }
        } else {
            $resultado["Unidad"]["esn"] = "";
        }
        $this->set('unidad', $resultado);
    }

    public function get_esn_planificacion($identificador) {
        $this->layout = null;
        $this->loadModel('Unidad');

        //$this->loadModel('Esn');
        $this->loadModel('EstadosMotores');
        $data = explode("_", $identificador);
        /* $resultado = $this->Unidad->find('first', array(
          'conditions' => array('Unidad.id' => $data[2])
          )); */

        $faena_id = $data[0];
        $flota_id = $data[1];
        $equipo = $data[2];
        /* $equipo = $resultado["Unidad"]["unidad"];
          $equipo_id = $resultado["Unidad"]["id"];
          $motor_id = $resultado["Unidad"]["motor_id"];
          $fecha = date("Y-m-d");

          $resultado = $this->Esn->find('first', array(
          'fields' => array('Esn.esn'),
          //'conditions' => array("flota_id=$flota_id AND faena_id=$faena_id AND motor_id=$motor_id AND unidad='$equipo'"),
          'conditions' => array("flota_id=$flota_id AND faena_id=$faena_id AND unidad='$equipo' AND (('$fecha' >= fecha_inicio AND '$fecha' <= fecha_termino) OR ('$fecha' >= fecha_inicio AND fecha_termino IS NULL))"),
          'order' => array("id" => 'desc'),
          'recursive' => -1)); */


        $resultado = $this->EstadosMotores->find('first', array(
            'fields' => array('EstadosMotores.esn_placa'),
            'conditions' => array("EstadosMotores.flota_id=$flota_id AND EstadosMotores.faena_id=$faena_id AND EstadosMotores.unidad_id=$equipo AND EstadosMotores.estado_motor_id is not null"),
            'order' => array("EstadosMotores.fecha_ps" => 'desc'),
            'recursive' => -1));

        if (isset($resultado["EstadosMotores"]["esn_placa"]) && $resultado["EstadosMotores"]["esn_placa"] != null && $resultado["EstadosMotores"]["esn_placa"] != "") {
            print_r($resultado["EstadosMotores"]["esn_placa"]);
        } else {
            print_r("");
        }
        exit;
    }

    public function index() {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);
        $this->loadModel('Faena');
        $this->loadModel('FaenaFlota');
        $this->loadModel('Unidad');
        $this->loadModel('Motor');
        $limit = 10;
        $unidad_id = '';
        $faena_id = '';
        $flota_id = '';
        $motor_id = '';

        if ($this->request->is('post')) {
            try {
                foreach ($this->request->data['registro'] as $key => $value) {
                    if ($value == '0') {
                        if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
                            // Estaba desactivado y se activo
                            $data = array();
                            $data["id"] = $key;
                            $data["e"] = "1";
                            $data["updated"] = date("Y-m-d H:i:s");
                            $this->Unidad->save($data);
                        }
                    }
                    if ($value == '1') {
                        if (!isset($this->request->data['estado'][$key])) {
                            // Estaba activado y se desactivo
                            $data = array();
                            $data["id"] = $key;
                            $data["e"] = "0";
                            $data["updated"] = date("Y-m-d H:i:s");
                            $this->Unidad->save($data);
                        }
                    }
                }
            } catch (Exception $e) {
                $this->Session->setFlash('Ocurrió un error al intentar cambiar el estado de los registros, intente nuevamente.', 'guardar_error');
                $this->logger($this, $e->getMessage());
            }
            $this->redirect(Router::url($this->referer(), true));
        }

        $conditions = array();
        if ($this->request->is('get')) {
            if (isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
                $limit = $this->request->query['limit'];
            }
            if (isset($this->request->query['estado']) && is_numeric($this->request->query['estado'])) {
                $conditions["Unidad.e"] = $this->request->query['estado'];
            }
            if (isset($this->request->query['motor_id']) && is_numeric($this->request->query['motor_id'])) {
                $conditions["Unidad.motor_id"] = $this->request->query['motor_id'];
                $motor_id = $this->request->query['motor_id'];
            }
            if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
                $faena_id = $this->request->query['faena_id'];
                $conditions["Unidad.faena_id"] = $faena_id;
            }
            if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
                $flota_id = explode("_", $this->request->query['flota_id']);
                $flota_id = $flota_id[1];
                $conditions["Unidad.flota_id"] = $flota_id;
            }
            if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
                $unidad_id = explode("_", $this->request->query['unidad_id']);
                $unidad_id = $unidad_id[2];
                $conditions["Unidad.id"] = $unidad_id;
            }
            if (isset($this->request->query['unidad']) && $this->request->query['unidad']) {
                $conditions["Unidad.unidad"] = $this->request->query['unidad'];
            }
            foreach ($this->request->query as $key => $value) {
                $this->set($key, $value);
            }
        }

        $conditions["Unidad.faena_id IN"] = $this->Session->read("FaenasFiltro");
        $this->paginate = array('limit' => $limit, 'order' => 'Unidad.unidad', 'recursive' => 2, 'conditions' => $conditions);
        $this->set('registros', $this->paginate('Unidad'));
        $this->set('limit', $limit);

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $motores = $this->Motor->find('all', array('order' => array("Motor.nombre"), 'conditions' => array('Motor.e' => '1'), 'recursive' => 1));
        $this->set(compact('motores'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id' => $this->Session->read("FaenasFiltro")), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $this->set('unidad_id', $unidad_id);
        $this->set('flota_id', $flota_id);
        $this->set('faena_id', $faena_id);
        $this->set('motor_id', $motor_id);

        // Unidades pendientes de aprobación
        $unidades_pendientes = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id' => $this->Session->read("FaenasFiltro"), "Unidad.e" => "2"), 'order' => array("Unidad.unidad"), 'recursive' => -1));

        if (!empty($unidades_pendientes) && count($unidades_pendientes) > 0) {
            //$this->Session->setFlash('Existen unidades pendientes de aprobación, haga <a href="/Unidad?faena_id=&flota_id=&unidad_id=&motor_id=&estado=2&limit=10">click aquí</a> para revisarlas.', 'guardar_warning');
        }

        $this->set(compact('unidades'));
        $this->set('titulo', 'Unidad');
        $this->set('breadcrumb', 'Administración');
    }

    public function pendientes() {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);
        $this->loadModel('Unidad');
        $limit = 10;

        $conditions = array();
        $conditions["Unidad.e"] = 2;
        //print_r($this->Session->read("FaenasFiltro"));
        //$conditions["Unidad.faena_id IN"] = $this->Session->read("FaenasFiltro");
        $this->paginate = array('limit' => $limit, 'order' => 'Unidad.unidad', 'recursive' => 2, 'conditions' => $conditions);
        $this->set('registros', $this->paginate('Unidad'));
        $this->set('titulo', 'Unidades Pendientes');
        $this->set('breadcrumb', 'Administración');
    }

    public function crear() {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);
        $util = new UtilidadesController();
        $this->loadModel('Motor');
        $this->loadModel('Faena');
        $this->loadModel('Usuario');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('PermisoGlobal');
        $this->loadModel('PermisoPersonalizado');
        $this->loadModel('PermisoUsuario');
        $this->loadModel('EstadoEquipo');
        $this->loadModel('EstadoMotor');
        $this->loadModel('TipoContrato');

        /*
          $permisos = $this->PermisoGlobal->find( "all", array( 'conditions' => array( 'PermisoGlobal.correo_id' => '4' ), 'recursive' => -1));
          debug($permisos);

          foreach($permisos as $permiso){
          $permisos_us = $this->PermisoUsuario->find( "all", array( 'conditions' => array( 'PermisoUsuario.cargo_id' => $permiso["PermisoGlobal"]["cargo_id"], 'PermisoUsuario.faena_id' => '57' ), 'recursive' => -1));
          debug($permisos_us);
          }

          $permisos_pe = $this->PermisoPersonalizado->find('all', array(
          'fields' => array('PermisoPersonalizado.id'),
          'conditions' => array("PermisoPersonalizado.faena_id" => '57', 'PermisoPersonalizado.correo_id' => '4', '' => ''),
          'recursive' => -1
          ));
          debug($permisos_pe);

          foreach($permisos_pe as $permiso){

          }

         */

        if ($this->request->is('post')) {
            try {
                $this->request->data["estado_motor_id"] = 1;
                $this->request->data["aprobador_id"] = NULL;
                
                $this->request->data["modelo_equipo"] = Sanitize::html($this->request->data["modelo_equipo"]);
                $this->request->data["nserie"] = Sanitize::html($this->request->data["nserie"]);
                $this->request->data["unidad"] = Sanitize::html($this->request->data["unidad"]);
                
                
                if ($this->Unidad->save($this->request->data)) {
                    // Cuadro Mando => Email => Alerta Creación Unidad (Nivel usuario)	
                    // Envio alerta de creacion de unidad
                    {
                        $faena_id = $this->request->data["faena_id"];
                        $faena = $this->Faena->find("first", array("fields" => array("nombre"), "conditions" => array("id" => $faena_id), "recursive" => -1));
                        $email = new CakeEmail();
                        $email->config('amazon');
                        $email->emailFormat('html');
                        $destinatarios = array();
                        $usuarios = $util->get_users_with_permissions_mail(4, $faena_id);
                        foreach ($usuarios as $usuario_id) {
                            $usuario = $this->Usuario->find('first', array(
                                'fields' => array("Usuario.id", 'Usuario.correo_electronico'),
                                'conditions' => array("Usuario.id" => $usuario_id),
                                'recursive' => -1
                            ));
                            if (isset($usuario["Usuario"]["correo_electronico"])) {
                                $destinatarios[] = $usuario["Usuario"]["correo_electronico"];
                            }
                        }
                        $html = "<table width=\"90%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
                        $html .= "<tr style=\"background-color: red; color: white;\">";
                        $html .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">UNIDAD CREADA</td>";
                        $html .= "</tr>";
                        $html .= "<tr style=\"background-color: white; color: black;\">";
                        $html .= "<td style=\"background-color: white; color: black; text-align: center;\" colspan=\"2\">REVISAR Y APROBAR EN <a href=\"" . DBM_URL . "/Unidad/Pendientes\">" . DBM_URL . "/Unidad/Pendientes</a></td>";
                        $html .= "</tr>";

                        if (MAIL_DEBUG != "") {
                            $html .= "<tr>";
                            $html .= "	<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">Destinatarios: " . (implode(",", $destinatarios)) . "</td>";
                            $html .= "</tr>";
                        }

                        $html .= "</table>";

                        if (MAIL_DEBUG == "") {
                            if (is_array($destinatarios) && count($destinatarios) > 0) {


                                $this->AWSSES->to = $destinatarios;
                                $this->AWSSES->send('Unidad Creada / ' . $faena["Faena"]["nombre"], $html);
                            }
                        } else {
                            $destinatarios = array();
                            $destinatarios[] = MAIL_DEBUG;
                            $this->AWSSES->to = $destinatarios;
                            $this->AWSSES->send('Unidad Creada / ' . $faena["Faena"]["nombre"], $html);
                        }
                        $email->reset();
                    }

                    $this->Session->setFlash('La unidad ha sido ingresada correctamente.', 'guardar_exito');
                    $this->redirect(array('action' => 'index'));
                }
            } catch (Exception $e) {
                $this->Session->setFlash('No se pudo ingresar la unidad, por favor revise la información e intente nuevamente.', 'guardar_error');
                $this->logger($this, $e->getMessage());
            }
        }

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        //$flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id","faena_id","Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        //$this->set(compact('flotas'));

        $flotas = $this->Flota->find('list', array('order' => array("Flota.nombre"), 'conditions' => array('Flota.e' => '1'), 'recursive' => -1));
        $this->set(compact('flotas'));

        $estados_equipos = $this->EstadoEquipo->find('list', array('order' => array("EstadoEquipo.nombre"), 'recursive' => -1));
        $this->set(compact('estados_equipos'));

        $motores = $this->Motor->find('all', array('order' => array("Motor.nombre"), 'conditions' => array('Motor.e' => '1'), 'recursive' => 1));
        $this->set(compact('motores'));

        $tipo_contratos = $this->TipoContrato->find('list', array('order' => array("TipoContrato.nombre"), 'recursive' => -1));
        $this->set(compact('tipo_contratos'));
    }

    public function aprobar() {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Motor');
        $this->loadModel('Faena');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('EstadoEquipo');
        $this->loadModel('EstadoMotor');
        $this->loadModel('TipoContrato');
        $this->loadModel('EstadosMotores');

        $id = $this->request->params['pass'][0];
        $this->Unidad->id = $id;
        if ($this->Unidad->exists()) {
            if ($this->request->is('post') || $this->request->is('put')) {
                try {
                    $this->request->data["aprobador_id"] = $this->Session->read("usuario_id");
                    //$this->request->data["flota_id"] = explode("_", $this->request->data["flota_id"]);
                    //$this->request->data["flota_id"] = $this->request->data["flota_id"][1];
                    // Si no existe, creamos relación
                    try {
                        $this->FaenaFlota->save($this->request->data);
                    } catch (Exception $e) {
                        $this->logger($this, $e->getMessage());
                    }

                    if ($this->Unidad->save($this->request->data)) {
                        //$this->request->data["unidad_id"] = $this->Unidad->id;
                        //$this->EstadosMotores->save( $this->request->data );
                        $this->Session->setFlash('Registro aprobado con éxito', 'guardar_exito');
                        $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Session->setFlash('No se pudo aprobar el registro', 'guardar_error');
                    }
                } catch (Exception $e) {
                    $this->Session->setFlash('Ocurrió un error al intentar aprobar el registro, intente nuevamente.', 'guardar_error');
                    $this->logger($this, $e->getMessage());
                }
            }
            $this->request->data = $this->Unidad->read();
            $this->set('data', $this->request->data);
            $this->set('faena_id', $this->request->data["Unidad"]["faena_id"]);
            $this->set('flota_id', $this->request->data["Unidad"]["flota_id"]);
        }

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $flotas1 = $this->Flota->find('list', array('order' => array("Flota.nombre"), 'conditions' => array('Flota.e' => '1'), 'recursive' => -1));
        $this->set(compact('flotas1'));

        $estados_equipos = $this->EstadoEquipo->find('list', array('order' => array("EstadoEquipo.nombre"), 'recursive' => -1));
        $this->set(compact('estados_equipos'));

        $motores = $this->Motor->find('all', array('order' => array("Motor.nombre"), 'conditions' => array('Motor.e' => '1'), 'recursive' => 1));
        $this->set(compact('motores'));

        $tipo_contratos = $this->TipoContrato->find('list', array('order' => array("TipoContrato.nombre"), 'recursive' => -1));
        $this->set(compact('tipo_contratos'));
    }

    public function editar() {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Motor');
        $this->loadModel('Faena');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('EstadoEquipo');
        $this->loadModel('EstadoMotor');
        $this->loadModel('TipoContrato');

        $id = $this->request->params['pass'][0];
        $this->Unidad->id = $id;
        if ($this->Unidad->exists()) {
            if ($this->request->is('post') || $this->request->is('put')) {
                try {
                    $this->request->data["flota_id"] = explode("_", $this->request->data["flota_id"]);
                    $this->request->data["flota_id"] = $this->request->data["flota_id"][1];
                    if ($this->Unidad->save($this->request->data)) {
                        $this->Session->setFlash('Registro editado con éxito.', 'guardar_exito');
                        $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Session->setFlash('No se pudo guardar el registro.', 'guardar_errot');
                    }
                } catch (Exception $e) {
                    $this->Session->setFlash('Ocurrió un error al intentar edigtar el registro, intente nuevamente.', 'guardar_error');
                    $this->logger($this, $e->getMessage());
                }
            }
            $this->request->data = $this->Unidad->read();
            $this->set('data', $this->request->data);
            $this->set('faena_id', $this->request->data["Unidad"]["faena_id"]);
            $this->set('flota_id', $this->request->data["Unidad"]["flota_id"]);
        }

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $flotas1 = $this->Flota->find('list', array('order' => array("Flota.nombre"), 'conditions' => array('Flota.e' => '1'), 'recursive' => -1));
        $this->set(compact('flotas1'));

        $estados_equipos = $this->EstadoEquipo->find('list', array('order' => array("EstadoEquipo.nombre"), 'recursive' => -1));
        $this->set(compact('estados_equipos'));

        $motores = $this->Motor->find('all', array('order' => array("Motor.nombre"), 'conditions' => array('Motor.e' => '1'), 'recursive' => 1));
        $this->set(compact('motores'));

        $tipo_contratos = $this->TipoContrato->find('list', array('order' => array("TipoContrato.nombre"), 'recursive' => -1));
        $this->set(compact('tipo_contratos'));
    }

    public function agregar() {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('Motor');
        $this->loadModel('Faena');
        $this->loadModel('Flota');
        $this->loadModel('FaenaFlota');
        $this->loadModel('EstadoEquipo');
        $this->loadModel('EstadoMotor');
        $this->loadModel('TipoContrato');
        $this->loadModel('EstadosMotores');

        if ($this->request->is('post') || $this->request->is('put')) {
            try {
                $this->request->data["aprobador_id"] = $this->Session->read("usuario_id");
                $this->request->data["flota_id"] = explode("_", $this->request->data["flota_id"]);
                $this->request->data["flota_id"] = $this->request->data["flota_id"][1];
                $this->Unidad->create();
                if ($this->Unidad->save($this->request->data)) {
                    $this->request->data["unidad_id"] = $this->Unidad->id;
                    $this->EstadosMotores->save($this->request->data);
                    $this->Session->setFlash('Registro agregado con éxito.', 'guardar_exito');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('No se pudo guardar el registro.', 'guardar_error');
                }
            } catch (Exception $e) {
                $this->Session->setFlash('Ocurrió un error al intentar agregar el registro, intente nuevamente.', 'guardar_error');
                $this->logger($this, $e->getMessage());
            }
        }

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $flotas1 = $this->Flota->find('list', array('order' => array("Flota.nombre"), 'conditions' => array('Flota.e' => '1'), 'recursive' => -1));
        $this->set(compact('flotas1'));

        $estados_equipos = $this->EstadoEquipo->find('list', array('order' => array("EstadoEquipo.nombre"), 'recursive' => -1));
        $this->set(compact('estados_equipos'));

        $motores = $this->Motor->find('all', array('order' => array("Motor.nombre"), 'conditions' => array('Motor.e' => '1'), 'recursive' => 1));
        $this->set(compact('motores'));

        $tipo_contratos = $this->TipoContrato->find('list', array('order' => array("TipoContrato.nombre"), 'recursive' => -1));
        $this->set(compact('tipo_contratos'));
    }

    public function complemento() {
        $this->check_permissions($this);
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('FaenaFlota');
        $this->loadModel('EstadoEquipo');
        $this->loadModel('EstadoMotor');
        $this->loadModel('Responsable');
        $this->loadModel('TipoContrato');
        $this->loadModel('TipoSalida');
        $this->loadModel('EstadosMotores');
        $this->layout = 'metronic_principal';
        $faena_id = '';
        $flota_id = '';
        $unidad_id = '';
        $esn = '';

        if ($this->request->is('post')) {
            try {
                $this->EstadosMotores->id = $this->request->data["id"];
                if ($this->EstadosMotores->exists()) {
                    if ($this->request->is('post') || $this->request->is('put')) {
                        if ($this->request->data["fecha_ps"] != null && $this->request->data["fecha_ps"] != '') {
                            $this->request->data["fecha_ps"] = date("Y-m-d", strtotime($this->request->data["fecha_ps"]));
                        }
                        if ($this->request->data["fecha_falla"] != null && $this->request->data["fecha_falla"] != '') {
                            $this->request->data["fecha_falla"] = date("Y-m-d", strtotime($this->request->data["fecha_falla"]));
                        }
                        if ($this->request->data["fecha_llegada_taller"] != null && $this->request->data["fecha_llegada_taller"] != '') {
                            $this->request->data["fecha_llegada_taller"] = date("Y-m-d", strtotime($this->request->data["fecha_llegada_taller"]));
                        }

                        if ($this->EstadosMotores->save($this->request->data)) {
                            $this->Session->setFlash('Datos complementarios registrados correctamente.', 'guardar_exito');
                        } else {
                            $this->Session->setFlash('Ocurrió un error al registrar los datos complementarios, por favor intente nuevamente.', 'guardar_error');
                        }
                    } else {
                        
                    }
                }
            } catch (Exception $e) {
                $this->Session->setFlash('Ocurrió un error al intentar registrar datos complementarios, intente nuevamente.', 'guardar_error');
                $this->logger($this, $e->getMessage());
            }
        }

        $conditions = array();
        $conditions2 = array();
        //if ($this->request->is('get')){
        if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
            $faena_id = $this->request->query['faena_id'];
            $conditions["EstadosMotores.faena_id"] = $faena_id;
        }
        if (isset($this->request->query['esn_placa']) && is_numeric($this->request->query['esn_placa'])) {
            $esn_placa = $this->request->query['esn_placa'];
            $conditions2["EstadosMotores.esn_placa"] = $esn_placa;
        }
        if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
            $flota_id = explode("_", $this->request->query['flota_id']);
            $flota_id = $flota_id[1];
            $conditions["EstadosMotores.flota_id"] = $flota_id;
        }
        if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
            $unidad_id = explode("_", $this->request->query['unidad_id']);
            $unidad_id = $unidad_id[2];
            $conditions["EstadosMotores.unidad_id"] = $unidad_id;
        }
        foreach ($this->request->query as $key => $value) {
            $this->set($key, $value);
        }
        //}

        if (isset($this->request->query['id']) && is_numeric($this->request->query['id'])) {
            $this->EstadosMotores->id = $this->request->query['id'];
            if ($this->EstadosMotores->exists()) {
                $this->request->data = $this->EstadosMotores->read();
                $this->set('data', $this->request->data);
            }
        }

        if ($faena_id != '' && $flota_id != '' && $unidad_id != '') {
            $estado_motor = $this->EstadosMotores->find('all', array('recursive' => 1, 'conditions' => $conditions, 'order' => array('EstadosMotores.fecha_ps', 'EstadosMotores.id')));
            $this->set('estado_motor', $estado_motor);
        }
        if ($esn_placa != '') {
            $estado_motor2 = $this->EstadosMotores->find('all', array('recursive' => 1, 'conditions' => $conditions2, 'order' => array('EstadosMotores.fecha_ps', 'EstadosMotores.id')));
            $this->set('estado_motor2', $estado_motor2);
        }

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $estados_equipos = $this->EstadoEquipo->find('list', array('order' => array("EstadoEquipo.nombre"), 'recursive' => -1));
        $this->set(compact('estados_equipos'));

        $estados_motor = $this->EstadoMotor->find('list', array('order' => array("EstadoMotor.nombre"), 'recursive' => -1));
        $this->set(compact('estados_motor'));

        $tipo_contratos = $this->TipoContrato->find('list', array('order' => array("TipoContrato.nombre"), 'recursive' => -1));
        $this->set(compact('tipo_contratos'));

        $tipo_salidas = $this->TipoSalida->find('list', array('order' => array("TipoSalida.nombre"), 'recursive' => -1));
        $this->set(compact('tipo_salidas'));

        $responsables = $this->Responsable->find('list', array('order' => array("Responsable.nombre"), 'recursive' => -1));
        $this->set(compact('responsables'));

        $this->set('unidad_id', $unidad_id);
        $this->set('flota_id', $flota_id);
        $this->set('faena_id', $faena_id);
        $this->set('esn_placa', $esn_placa);

        $this->set('titulo', 'Datos complementarios');
        $this->set('breadcrumb', 'Unidad');
    }

    public function montajes() {
        $this->check_permissions($this);
        $util = new UtilidadesController();
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('FaenaFlota');
        $this->loadModel('EstadoEquipo');
        $this->loadModel('EstadoMotor');
        $this->loadModel('TipoContrato');
        $this->loadModel('EstadoMotorInstalacion');
        $this->loadModel('EstadosMotores');
        $this->loadModel('Medicion');
        $this->layout = 'metronic_principal';
        $faena_id = '';
        $flota_id = '';
        $unidad_id = '';
        $esn_placa = '';

        if ($this->request->is('post')) {
            try {
                if ($this->request->is('post') || $this->request->is('put')) {
                    $this->EstadosMotores->create();
                    if (isset($this->request->data["tipo_nuevo_contrato_id"])) {
                        $this->request->data["tipo_contrato_id"] = $this->request->data["tipo_nuevo_contrato_id"];
                        unset($this->request->data["tipo_nuevo_contrato_id"]);
                    }
                    if ($this->request->data["fecha_ps"] != null && $this->request->data["fecha_ps"] != '') {
                        $this->request->data["fecha_ps"] = date("Y-m-d", strtotime($this->request->data["fecha_ps"]));
                    }

                    if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
                        $faena_id = $this->request->query['faena_id'];
                        $this->request->data["faena_id"] = $faena_id;
                    }

                    // Registro montaje pendiente
                    $this->request->data["estado"] = '1';
                    $this->request->data["aprobador_montaje_id"] = NULL;
                    $this->request->data["hr_motor_instalacion"] = str_replace(',', '.', $this->request->data["hr_motor_instalacion"]);

                    if ($this->EstadosMotores->save($this->request->data)) {
                        // Envio alerta de montaje
                        {

                            //Graba en Medicion Axial
                            $data = $this->request->data;
                            $medicion['faena_id'] = $data['faena_id'];
                            $medicion['flota_id'] = $data['flota_id'];
                            $medicion['unidad_id'] = $data['unidad_id'];
                            $medicion['componente_id'] = 1;
                            $medicion['medicion'] = str_replace(',', '.', $data['medicion_axial']);
                            $medicion['fecha'] = $data["fecha_ps"]; //date('Y-m-d H:i:s', time());
                            $medicion['ps'] = 'true';
                            $medicion['usuario_id'] = $this->Session->read('usuario_id');
                            $this->Medicion->save($medicion);
                            //****************

                            $faena = $this->Faena->find("first", array("fields" => array("nombre"), "conditions" => array("id" => $faena_id), "recursive" => -1));
                            $email = new CakeEmail();
                            $email->config('amazon');
                            $email->emailFormat('html');
                            $destinatarios = array();
                            $usuarios = $util->get_users_with_permissions_mail(6, $faena_id);
                            foreach ($usuarios as $usuario_id) {
                                $usuario = $this->Usuario->find('first', array(
                                    'fields' => array("Usuario.id", 'Usuario.correo_electronico'),
                                    'conditions' => array("Usuario.id" => $usuario_id),
                                    'recursive' => -1
                                ));
                                if (isset($usuario["Usuario"]["correo_electronico"])) {
                                    $destinatarios[] = $usuario["Usuario"]["correo_electronico"];
                                }
                            }
                            $html = "<table width=\"90%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
                            $html .= "<tr style=\"background-color: red; color: white;\">";
                            $html .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">UNIDAD MONTADA</td>";
                            $html .= "</tr>";
                            $html .= "<tr style=\"background-color: white; color: black;\">";
                            $html .= "<td style=\"background-color: white; color: black; text-align: center;\" colspan=\"2\">REVISAR Y APROBAR EN <a href=\"" . DBM_URL . "/Unidad/Aprobaciones\">" . DBM_URL . "/Unidad/Aprobaciones</a></td>";
                            $html .= "</tr>";
                            if (MAIL_DEBUG != "") {
                                $html .= "<tr>";
                                $html .= "	<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">Destinatarios: " . (implode(",", $destinatarios)) . "</td>";
                                $html .= "</tr>";
                            }
                            $html .= "</table>";
                            if (MAIL_DEBUG == "") {
                                if (is_array($destinatarios) && count($destinatarios) > 0) {

                                    $this->AWSSES->to = $destinatarios;
                                    $this->AWSSES->send('Unidad Montada / ' . $faena["Faena"]["nombre"], $html);
                                }
                            }

                            if (MAIL_DEBUG != "") {

                                $destinatarios = array();
                                $destinatarios[] = MAIL_DEBUG;
                                $this->AWSSES->to = $destinatarios;
                                $this->AWSSES->send('Unidad Montada / ' . $faena["Faena"]["nombre"], $html);
                            }
                        }
                        $this->Session->setFlash('La unidad ha sido montada correctamente.', 'guardar_exito');
                    } else {
                        $this->Session->setFlash('Ocurrió un error al montar la unidad, por favor intente nuevamente.', 'guardar_error');
                    }
                } else {
                    
                }
            } catch (Exception $e) {
                $this->Session->setFlash('Ocurrió un error al intentar montar el registro, intente nuevamente.', 'guardar_error');
                $this->logger($this, $e->getMessage());
            }
        }

        $conditions = array();
        $conditions2 = array();
        //if ($this->request->is('get')){
        if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
            $faena_id = $this->request->query['faena_id'];
            $conditions["EstadosMotores.faena_id"] = $faena_id;
        }
        if (isset($this->request->query['esn_placa']) && is_numeric($this->request->query['esn_placa'])) {
            $esn_placa = $this->request->query['esn_placa'];
            $conditions2["EstadosMotores.esn_placa"] = $esn_placa;
        }
        if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
            $flota_id = explode("_", $this->request->query['flota_id']);
            $flota_id = $flota_id[1];
            $conditions["EstadosMotores.flota_id"] = $flota_id;
        }
        if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
            $unidad_id = explode("_", $this->request->query['unidad_id']);
            $unidad_id = $unidad_id[2];
            $conditions["EstadosMotores.unidad_id"] = $unidad_id;
        }
        foreach ($this->request->query as $key => $value) {
            $this->set($key, $value);
        }
        //}
        //$conditions["EstadosMotores.estado IS"] = NULL;
        //$conditions2["EstadosMotores.estado IS"] = NULL;

        if ($faena_id != '' && $flota_id != '' && $unidad_id != '') {
            $estado_motor = $this->EstadosMotores->find('all', array('recursive' => 1, 'conditions' => $conditions, 'order' => array('EstadosMotores.fecha_ps', 'EstadosMotores.id')));
            $this->set('estado_motor', $estado_motor);
        }

        if ($esn_placa != '') {
            $estado_motor2 = $this->EstadosMotores->find('all', array('recursive' => 1, 'conditions' => $conditions2, 'order' => array('EstadosMotores.fecha_ps', 'EstadosMotores.id')));
            $this->set('estado_motor2', $estado_motor2);
        }

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $estados_equipos = $this->EstadoEquipo->find('list', array('order' => array("EstadoEquipo.nombre")));
        $this->set(compact('estados_equipos'));

        $estados_motor = $this->EstadoMotor->find('list', array('order' => array("EstadoMotor.nombre"), 'conditions' => array('EstadoMotor.e' => '1'), 'recursive' => -1));
        $this->set(compact('estados_motor'));

        $tipo_contratos = $this->TipoContrato->find('list', array('order' => array("TipoContrato.nombre"), 'recursive' => -1));
        $this->set(compact('tipo_contratos'));

        $estado_motor_instalacion = $this->EstadoMotorInstalacion->find('list', array('order' => array("nombre"), 'conditions' => array('e' => '1'), 'recursive' => -1));
        $this->set(compact('estado_motor_instalacion'));

        $this->set('unidad_id', $unidad_id);
        $this->set('flota_id', $flota_id);
        $this->set('faena_id', $faena_id);
        $this->set('esn_placa', $esn_placa);

        $this->set('titulo', 'Montaje');
        $this->set('breadcrumb', 'Unidad');
    }

    public function desmontajes() {
        $this->check_permissions($this);
        $util = new UtilidadesController();
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('FaenaFlota');
        $this->loadModel('EstadoEquipo');
        $this->loadModel('EstadoMotor');
        $this->loadModel('TipoContrato');
        $this->loadModel('TipoSalida');
        $this->loadModel('EstadosMotores');
        $this->layout = 'metronic_principal';
        $faena_id = '';
        $flota_id = '';
        $unidad_id = '';

        if ($this->request->is('post')) {
            try {
                $this->EstadosMotores->id = $this->request->data["id"];
                if ($this->EstadosMotores->exists()) {
                    if ($this->request->is('post') || $this->request->is('put')) {
                        if ($this->request->data["fecha_falla"] != null && $this->request->data["fecha_falla"] != '') {
                            $this->request->data["fecha_falla"] = date("Y-m-d", strtotime($this->request->data["fecha_falla"]));
                        }
                        if ($this->request->data["fecha_ps"] != null && $this->request->data["fecha_ps"] != '') {
                            $this->request->data["fecha_ps"] = date("Y-m-d", strtotime($this->request->data["fecha_ps"]));
                        }
                        if (isset($this->request->data["tipo_nuevo_contrato_id"])) {
                            $this->request->data["tipo_contrato_id"] = $this->request->data["tipo_nuevo_contrato_id"];
                            unset($this->request->data["tipo_nuevo_contrato_id"]);
                        }

                        if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
                            $faena_id = $this->request->query['faena_id'];
                        }

                        // Registro desmontaje pendiente
                        $this->request->data["estado"] = '2';
                        $this->request->data["aprobador_desmontaje_id"] = NULL;
                        $this->request->data["hr_operadas_motor"] = str_replace(',', '.', $this->request->data["hr_operadas_motor"]);
                        
                        $this->request->data['motivo_cambio'] = Sanitize::html($this->request->data['motivo_cambio']);
                        if ($this->EstadosMotores->save($this->request->data)) {
                            $faena = $this->Faena->find("first", array("fields" => array("nombre"), "conditions" => array("id" => $faena_id), "recursive" => -1));
                            $email = new CakeEmail();
                            $email->config('amazon');
                            $email->emailFormat('html');
                            $destinatarios = array();
                            $usuarios = $util->get_users_with_permissions_mail(5, $faena_id);
                            foreach ($usuarios as $usuario_id) {
                                $usuario = $this->Usuario->find('first', array(
                                    'fields' => array("Usuario.id", 'Usuario.correo_electronico'),
                                    'conditions' => array("Usuario.id" => $usuario_id),
                                    'recursive' => -1
                                ));
                                if (isset($usuario["Usuario"]["correo_electronico"])) {
                                    $destinatarios[] = $usuario["Usuario"]["correo_electronico"];
                                }
                            }
                            $html = "<table width=\"90%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
                            $html .= "<tr style=\"background-color: red; color: white;\">";
                            $html .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">UNIDAD DESMONTADA</td>";
                            $html .= "</tr>";
                            $html .= "<tr style=\"background-color: white; color: black;\">";
                            $html .= "<td style=\"background-color: white; color: black; text-align: center;\" colspan=\"2\">REVISAR Y APROBAR EN <a href=\"" . DBM_URL . "/Unidad/Aprobaciones\">" . DBM_URL . "/Unidad/Aprobaciones</a></td>";
                            $html .= "</tr>";
                            if (MAIL_DEBUG != "") {
                                $html .= "<tr>";
                                $html .= "	<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">Destinatarios: " . (implode(",", $destinatarios)) . "</td>";
                                $html .= "</tr>";
                            }
                            $html .= "</table>";
                            if (MAIL_DEBUG == "") {
                                if (is_array($destinatarios) && count($destinatarios) > 0) {

                                    $this->AWSSES->to = $destinatarios;
                                    $this->AWSSES->send('Unidad Desmontada / ' . $faena["Faena"]["nombre"], $html);
                                }
                            }
                            if (MAIL_DEBUG != "") {
                                $destinatarios = array();
                                $destinatarios[] = MAIL_DEBUG;

                                $this->AWSSES->to = $destinatarios;
                                $this->AWSSES->send('Unidad Desmontada / ' . $faena["Faena"]["nombre"], $html);
                            }
                            $this->Session->setFlash('La unidad ha sido desmontada correctamente.', 'guardar_exito');
                        } else {
                            $this->Session->setFlash('Ocurrió un error al desmontar la unidad, por favor intente nuevamente. ', 'guardar_error');
                        }
                    } else {
                        
                    }
                }
            } catch (Exception $e) {
                $this->Session->setFlash('Ocurrió un error al intentar desmontar el registro, intente nuevamente.' . $e->getMessage(), 'guardar_error');
                $this->logger($this, $e->getMessage());
            }
        }

        $conditions = array();
        $conditions2 = array();
        //if ($this->request->is('get')){
        if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
            $faena_id = $this->request->query['faena_id'];
            $conditions["EstadosMotores.faena_id"] = $faena_id;
        }
        if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
            $flota_id = explode("_", $this->request->query['flota_id']);
            $flota_id = $flota_id[1];
            $conditions["EstadosMotores.flota_id"] = $flota_id;
        }
        if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
            $unidad_id = explode("_", $this->request->query['unidad_id']);
            $unidad_id = $unidad_id[2];
            $conditions["EstadosMotores.unidad_id"] = $unidad_id;
        }
        foreach ($this->request->query as $key => $value) {
            $this->set($key, $value);
        }
        //}
        //$conditions["EstadosMotores.estado IS"] = NULL;
        $hr_historico_motor = 0;
        if ($faena_id != '' && $flota_id != '' && $unidad_id != '') {
            $estado_motor = $this->EstadosMotores->find('all', array('recursive' => 1, 'conditions' => $conditions, 'order' => array('EstadosMotores.fecha_ps', 'EstadosMotores.id')));
            $this->set('estado_motor', $estado_motor);

            $registro = $estado_motor[count($estado_motor) - 1];
            $esn_placa = $registro["EstadosMotores"]["esn_placa"];
            $conditions2["EstadosMotores.esn_placa"] = $esn_placa;
            $estado_motor2 = $this->EstadosMotores->find('all', array('fields' => 'SUM(hr_operadas_motor)', 'recursive' => -1, 'conditions' => $conditions2));

            //print_r($estado_motor2);
            $hr_historico_motor = $estado_motor2[0][0]['sum'];
        }

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $estados_equipos = $this->EstadoEquipo->find('list', array('order' => array("EstadoEquipo.nombre"), 'recursive' => -1));
        $this->set(compact('estados_equipos'));

        $estados_motor = $this->EstadoMotor->find('list', array('order' => array("EstadoMotor.nombre"), 'recursive' => -1));
        $this->set(compact('estados_motor'));

        $tipo_contratos = $this->TipoContrato->find('list', array('order' => array("TipoContrato.nombre"), 'recursive' => -1));
        $this->set(compact('tipo_contratos'));

        $tipo_salidas = $this->TipoSalida->find('list', array('order' => array("TipoSalida.nombre"), 'recursive' => -1));
        $this->set(compact('tipo_salidas'));

        $this->set('unidad_id', $unidad_id);
        $this->set('flota_id', $flota_id);
        $this->set('hr_historico_motor', $hr_historico_motor);
        $this->set('faena_id', $faena_id);
        $this->set('estado_motor_id', -1);
        $this->set('estado_equipo_id', -1);
        $this->set('titulo', 'Desmontaje');
        $this->set('breadcrumb', 'Unidad');
    }

    public function aprobaciones() {
        $this->check_permissions($this);
        $this->loadModel('Unidad');
        $this->loadModel('Faena');
        $this->loadModel('FaenaFlota');
        $this->loadModel('EstadoEquipo');
        $this->loadModel('EstadoMotorInstalacion');
        $this->loadModel('EstadoMotor');
        $this->loadModel('TipoSalida');
        $this->loadModel('TipoContrato');
        $this->loadModel('EstadosMotores');
        $this->layout = 'metronic_principal';
        $faena_id = '';
        $flota_id = '';
        $unidad_id = '';
        $esn_placa = '';

        if ($this->request->is('post')) {
            try {
                $this->EstadosMotores->id = $this->request->data["id"];
                if ($this->EstadosMotores->exists()) {
                    if ($this->request->is('post') || $this->request->is('put')) {
                        $this->request->data["estado"] = NULL;
                        if ($this->request->data["fecha_falla"] != null && $this->request->data["fecha_falla"] != '') {
                            $this->request->data["fecha_falla"] = date("Y-m-d", strtotime($this->request->data["fecha_falla"]));
                        }
                        if ($this->request->data["fecha_ps"] != null && $this->request->data["fecha_ps"] != '') {
                            $this->request->data["fecha_ps"] = date("Y-m-d", strtotime($this->request->data["fecha_ps"]));
                        }
                        if ($this->EstadosMotores->save($this->request->data)) {
                            $data = array();
                            $data["id"] = $this->request->data["unidad_id"];
                            $data["estado_equipo_id"] = $this->request->data["estado_equipo_id"];
                            $data["estado_motor_id"] = $this->request->data["estado_motor_id"];
                            $data["tipo_contrato_id"] = $this->request->data["tipo_contrato_id"];
                            if ($this->Unidad->save($data)) {
                                if (isset($this->request->data["aprobador_montaje_id"]) && is_numeric($this->request->data["aprobador_montaje_id"])) {
                                    // Alerta de montaje por correo
                                } elseif (isset($this->request->data["aprobador_desmontaje_id"]) && is_numeric($this->request->data["aprobador_desmontaje_id"])) {
                                    // Alerta de desmontaje por correo
                                }
                                $this->Session->setFlash('Unidad aprobada correctamente.', 'guardar_exito');
                            } else {
                                $this->Session->setFlash('Se aprobó correctamente las modificaciones de la unidad, pero no se pudo actualizar el estado de la unidad.', 'guardar_exito');
                            }
                        } else {
                            $this->Session->setFlash('Ocurrió un error al aprobar la unidad, por favor intente nuevamente.', 'guardar_error');
                        }
                    } else {
                        
                    }
                }
            } catch (Exception $e) {
                $this->Session->setFlash('Ocurrió un error al intentar aprobar el registro, intente nuevamente.', 'guardar_error');
                $this->logger($this, $e->getMessage());
            }
        }

        $conditions = array();
        $conditions2 = array();
        //if ($this->request->is('get')){
        if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
            $faena_id = $this->request->query['faena_id'];
            $conditions["EstadosMotores.faena_id"] = $faena_id;
        }
        if (isset($this->request->query['esn_placa']) && is_numeric($this->request->query['esn_placa'])) {
            $esn_placa = $this->request->query['esn_placa'];
            $conditions2["EstadosMotores.esn_placa"] = $esn_placa;
        }
        if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
            $flota_id = explode("_", $this->request->query['flota_id']);
            $flota_id = $flota_id[1];
            $conditions["EstadosMotores.flota_id"] = $flota_id;
        }
        if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
            $unidad_id = explode("_", $this->request->query['unidad_id']);
            $unidad_id = $unidad_id[2];
            $conditions["EstadosMotores.unidad_id"] = $unidad_id;
        }
        foreach ($this->request->query as $key => $value) {
            $this->set($key, $value);
        }
        //}

        if (isset($this->request->query['id']) && is_numeric($this->request->query['id'])) {
            $this->EstadosMotores->id = $this->request->query['id'];
            if ($this->EstadosMotores->exists()) {
                $this->set('data', $this->EstadosMotores->read());
            }
        }

        if ($faena_id != '' && $flota_id != '' && $unidad_id != '') {
            $estado_motor = $this->EstadosMotores->find('all', array('recursive' => 1, 'conditions' => $conditions, 'order' => array('EstadosMotores.fecha_ps', 'EstadosMotores.id')));
            $this->set('estado_motor', $estado_motor);
        }
        if ($esn_placa != '') {
            $estado_motor2 = $this->EstadosMotores->find('all', array('recursive' => 1, 'conditions' => $conditions2, 'order' => array('EstadosMotores.fecha_ps', 'EstadosMotores.id')));
            $this->set('estado_motor2', $estado_motor2);
        }

        $conditions = array();

        $conditions["OR"] = array(
            array(
                'EstadosMotores.estado' => 1,
                'EstadosMotores.aprobador_montaje_id' => null
            ),
            array(
                'EstadosMotores.estado' => 2,
                'EstadosMotores.aprobador_desmontaje_id' => null
            )
        );

        // estado == 1 and aprobador_montaje_id	is null
        // estado == 2 and aprobador_desmontaje_id is null

        $estado_motor = $this->EstadosMotores->find('all', array('recursive' => 1, 'conditions' => $conditions, 'order' => array('EstadosMotores.fecha_ps')));
        $this->set('pendientes', $estado_motor);

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.e' => '1'), 'order' => array("Faena.nombre" => 'asc'), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('Flota.nombre is not null', 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre" => 'asc'), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.e' => '1'), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $estados_equipos = $this->EstadoEquipo->find('list', array('order' => array("EstadoEquipo.nombre"), 'recursive' => -1));
        $this->set(compact('estados_equipos'));

        $estados_motor = $this->EstadoMotor->find('list', array('order' => array("EstadoMotor.nombre"), 'recursive' => -1));
        $this->set(compact('estados_motor'));

        $tipo_contratos = $this->TipoContrato->find('list', array('order' => array("TipoContrato.nombre"), 'recursive' => -1));
        $this->set(compact('tipo_contratos'));

        $tipo_salidas = $this->TipoSalida->find('list', array('order' => array("TipoSalida.nombre"), 'recursive' => -1));
        $this->set(compact('tipo_salidas'));

        $estado_motor_instalacion = $this->EstadoMotorInstalacion->find('list', array('order' => array("nombre"), 'recursive' => -1));
        $this->set(compact('estado_motor_instalacion'));

        $this->set('unidad_id', $unidad_id);
        $this->set('flota_id', $flota_id);
        $this->set('faena_id', $faena_id);
        $this->set('esn_placa', $esn_placa);

        $this->set('titulo', 'Aprobaciones pendientes');
        $this->set('breadcrumb', 'Unidad');
    }

    public function selectunidad($flota_id) {
        $this->layout = null;
        $this->loadModel('Unidad');

        $f = split("_", $flota_id);
        $faena = $f[0];
        $flota = $f[1];

        $unidades = $this->Unidad->find('all',
                array(
                    'fields' => array("id", "flota_id", "faena_id", "Unidad.unidad", 'Unidad.motor_id'),
                    'conditions' => array('Unidad.faena_id' => $faena, 'Unidad.flota_id' => $flota, 'Unidad.e' => '1'),
                    'order' => 'Unidad.unidad',
                    'recursive' => -1)
        );
        $this->set(compact('unidades'));
    }
}

?>