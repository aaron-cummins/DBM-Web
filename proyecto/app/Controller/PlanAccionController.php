<?php

App::uses('ConnectionManager', 'Model');
App::uses('File', 'Utility');
App::import('Controller', 'Utilidades');
App::uses('CakeEmail', 'Network/Email');
App::import('Vendor', 'Classes/PHPExcel');
App::import('Controller', 'UtilidadesReporte');

App::uses('Sanitize', 'Utility');
class PlanAccionController extends AppController
{

    public function index() {

        $this->set('titulo', 'Planes de Acción');
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('PlanAccion');
        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sistema');
        $this->loadModel('Sintoma');
        $this->loadModel('Motor');

        if ($this->request->is('post')){
            try {

                foreach($this->request->data['registro'] as $key => $value) {
                    if ($value == '0') {
                        if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
                            // Estaba desactivado y se activo
                            $data = array();
                            $data["id"] = $key;
                            $data["e"] = "1";
                            $data["usuario_id"] = $this->getUsuarioID();
                            $data["updated"] = date("Y-m-d H:i:s");
                            $this->PlanAccion->save( $data );
                        }
                    }
                    if ($value == '1') {
                        if (!isset($this->request->data['estado'][$key])) {
                            // Estaba activado y se desactivo
                            $data = array();
                            $data["id"] = $key;
                            $data["e"] = "0";
                            $data["usuario_id"] = $this->getUsuarioID();
                            $data["updated"] = date("Y-m-d H:i:s");
                            $this->PlanAccion->save( $data );
                        }
                    }
                }
                $this->Session->setFlash('Cambio de estado de los registros realizados correctamente.','guardar_exito');
            } catch(Exception $e) {
                $this->Session->setFlash('Ocurrió un error al intentar cambiar el estado de los registros, intente nuevamente.','guardar_error');
            }
            $this->redirect(Router::url($this->referer(), true));
        }


        $categoria_sintoma = $this->SintomaCategoria->find('list', array('order' => array("SintomaCategoria.nombre"), 'recursive' => -1));
        $this->set(compact('categoria_sintoma'));

        $sintomas = $this->Sintoma->find('all', array('fields' => array("Sintoma.sintoma_categoria_id", "Sintoma.id", "Sintoma.nombre", "Sintoma.codigo"), 'conditions' => array('Sintoma.e' => '1'), 'order' => array("Sintoma.nombre"), 'recursive' => -1));
        $this->set(compact('sintomas'));

        $sistemas = $this->Sistema->find('list', array('order' => array("Sistema.nombre"), 'recursive' => -1));
        $this->set(compact('sistemas'));

        $motores = $this->Motor->find('all', array('order' => array("Motor.nombre"), 'conditions' => array('Motor.e' => '1'), 'recursive' => 1));
        $this->set(compact('motores'));

        $conditions = array();
        $limit = 25;
        $folio = '';
        $sintoma = "";
        $cat_sintoma = "";
        $motor = "";
        $sistema = 0;


        if ($this->request->is('get')) {
            if (isset($this->request->query['folio']) && is_numeric($this->request->query['folio'])) {
                $folio = $this->request->query['folio'];
                $conditions["PlanAccion.id"] = $folio;
            }
            if (isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
                $limit = $this->request->query['limit'];
            }
            if (isset($this->request->query['sistema_id']) && $this->request->query['sistema_id'] != '') {
                $sistema = $this->request->query['sistema_id'];
                $conditions["PlanAccion.sistema_id"] = $sistema;
            }

            if (isset($this->request->query['motor_id']) && $this->request->query['motor_id'] != '') {
                $motor = $this->request->query['motor_id'];
                $conditions["PlanAccion.motor_id"] = $motor;
            }

            if (isset($this->request->query['categoria_sintoma_id']) && $this->request->query['categoria_sintoma_id'] != '') {
                $cat_sintoma = $this->request->query['categoria_sintoma_id'];
                $conditions["PlanAccion.categoria_sintoma_id"] = $cat_sintoma;
            }

            if (isset($this->request->query['sintoma_id']) && $this->request->query['sintoma_id'] != '') {
                $sintoma = $this->request->query['sintoma_id'];
                $conditions["PlanAccion.sintoma_id"] = $sintoma;
            }

            foreach ($this->request->query as $key => $value) {
                $this->set($key, $value);
            }
            $query = http_build_query($this->request->query);
            $this->set(compact('query'));
        }

        $this->paginate = array(
            'fields' => array('PlanAccion.*', 'Usuario.u', 'Sistema.nombre', 'Elemento.*', 'Subsistema.*', 'Motor.*', 'TipoAdmision.nombre', 'TipoEmision.nombre', 'Sintoma.nombre', 'SintomaCategoria.nombre'),
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'table' => 'tipo_admision',
                    'alias' => 'TipoAdmision',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'TipoAdmision.id = "Motor".tipo_admision_id'
                    )
                ),
                array(
                    'table' => 'tipo_emision',
                    'alias' => 'TipoEmision',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'TipoEmision.id = "Motor".tipo_emision_id'
                    )
                )
            ),
            'limit' => $limit,
            'order' => 'PlanAccion.id DESC',
            'recursive' => 1
        );

        $registros = $this->paginate('PlanAccion');

        //print_r($registros);
        $this->set(compact('registros'));
        $this->set(compact('limit'));
        $this->set(compact('folio'));
        $this->set(compact('sintoma'));
        $this->set(compact('cat_sintoma'));
        $this->set(compact('motor'));
        $this->set(compact('sistema'));
    }

    public function crear() {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sistema');
        $this->loadModel('Sintoma');
        $this->loadModel('Motor');

        $categoria_sintoma = $this->SintomaCategoria->find('list', array('order' => array("SintomaCategoria.nombre"), 'recursive' => -1));
        $this->set(compact('categoria_sintoma'));

        $sintomas = $this->Sintoma->find('all', array('fields' => array("Sintoma.sintoma_categoria_id", "Sintoma.id", "Sintoma.nombre", "Sintoma.codigo"), 'conditions' => array('Sintoma.e' => '1'), 'order' => array("Sintoma.nombre"), 'recursive' => -1));
        $this->set(compact('sintomas'));

        $sistemas = $this->Sistema->find('list', array('order' => array("Sistema.nombre"), 'recursive' => -1));
        $this->set(compact('sistemas'));

        $motores = $this->Motor->find('all', array('order' => array("Motor.nombre"), 'conditions' => array('Motor.e' => '1'), 'recursive' => 1));
        $this->set(compact('motores'));

        if ($this->request->is('post')) {
            if (isset($this->request->data) && count($this->request->data) > 0) {
                try {
                    $data = $this->request->data;

                    $data['nombre'] = $data['nombre'];
                    $data['descripcion'] = $data['descripcion'];
                    $data['motor_id'] = $data['motor_id_'];
                    $data['usuario_id'] = $this->getUsuarioID();
                    $data['fecha_creacion'] = date("Y-m-d H:i:s");
                    $data['e'] = true;

                    $this->PlanAccion->create();
                    $this->PlanAccion->save($data);

                    $this->Session->setFlash("Plan de acción creado con éxito ", 'guardar_exito');

                    $this->redirect("/PlanAccion");
                } catch (Exception $e) {
                    $this->Session->setFlash("Ocurrió un error al intentar registrar el Plan de acción, por favor intentar nuevamente. " . $e->getMessage(), 'guardar_error');
                }
            }
        }
    }

    public function editar($id){
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        $this->loadModel('SintomaCategoria');
        $this->loadModel('Sistema');
        $this->loadModel('Sintoma');
        $this->loadModel('Motor');

        $categoria_sintoma = $this->SintomaCategoria->find('list', array('order' => array("SintomaCategoria.nombre"), 'recursive' => -1));
        $this->set(compact('categoria_sintoma'));

        $sintomas = $this->Sintoma->find('all', array('fields' => array("Sintoma.sintoma_categoria_id", "Sintoma.id", "Sintoma.nombre", "Sintoma.codigo"), 'conditions' => array('Sintoma.e' => '1'), 'order' => array("Sintoma.nombre"), 'recursive' => -1));
        $this->set(compact('sintomas'));

        $sistemas = $this->Sistema->find('list', array('order' => array("Sistema.nombre"), 'recursive' => -1));
        $this->set(compact('sistemas'));

        $motores = $this->Motor->find('all', array('order' => array("Motor.nombre"), 'conditions' => array('Motor.e' => '1'), 'recursive' => 1));
        $this->set(compact('motores'));

        if ($this->request->is('post')) {
            if (isset($this->request->data) && count($this->request->data) > 0) {
                try {
                    $data = $this->request->data;

                    $data['nombre'] = $data['nombre'];
                    $data['descripcion'] = $data['descripcion'];
                    $data['motor_id'] = $data['motor_id_'];
                    $data['usuario_id'] = $this->getUsuarioID();
                    //$data['fecha_creacion'] = date("Y-m-d H:i:s");
                    $data['e'] = true;

                    $this->PlanAccion->create();
                    $this->PlanAccion->save($data);

                    $this->set('data', $data);

                    $this->Session->setFlash("Plan de acción editado con éxito ", 'guardar_exito');
                    $this->redirect('index');
                } catch (Exception $e) {
                    $this->Session->setFlash("Ocurrió un error al intentar registrar el Plan de acción, por favor intentar nuevamente. " . $e->getMessage(), 'guardar_error');
                }
            }
        }

        if ($id != "" && is_numeric($id)) {
            $planaccion = $this->PlanAccion->find('first', array("conditions" => array("id" => $id), "fields" => "PlanAccion.*", 'recursive' => -1));

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
        }
    }


    public function planaccion_backlog($id){
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);

        if ($id != "" && is_numeric($id)) {
            $planaccion = $this->PlanAccion->find('first', array("conditions" => array("id" => $id), "fields" => "PlanAccion.*", 'recursive' => -1));

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

            $resultados = json_encode($planaccion);
            print($resultados);
            die;
        }
    }
}