<?php

/**
 * Description of PrebacklogController
 *
 * @author AZUNIGA
 */
App::uses('ConnectionManager', 'Model');
App::import('Controller', 'Utilidades');
App::uses('Sanitize', 'Utility');

class PrebacklogAlertaParametroController extends AppController {

    function index() {
        $this->set('titulo', 'Alerta Parámetros');
        $this->layout = 'metronic_principal';
        $this->loadModel('Prebacklog_alertaParametro');
        $this->loadModel('Prebacklog_alerta');

        $alertas = $this->Prebacklog_alerta->find('list', array('fields' => array('Prebacklog_alerta.id', 'Prebacklog_alerta.nombre')  ,'order' => array("Prebacklog_alerta.nombre"), 'recursive' => -1));
        $this->set(compact('alertas'));
        
        if ($this->request->is('post')) {
            try {
                foreach ($this->request->data['registro'] as $key => $value) {
                    if ($value == '0') {
                        if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
                            // Estaba desactivado y se activo
                            $data = array();
                            $data["id"] = $key;
                            $data["e"] = "1";
                            $data['fecha_actualizacion'] =  date('Y-m-d H:i:s', time());
                            $this->Prebacklog_alertaParametro->save($data);   
                        }
                    }
                    if ($value == '1') {
                        if (!isset($this->request->data['estado'][$key])) {
                            // Estaba activado y se desactivo
                            $data = array();
                            $data["id"] = $key;
                            $data["e"] = "0";
                            $data['fecha_actualizacion'] =  date('Y-m-d H:i:s', time());
                            $this->Prebacklog_alertaParametro->save($data);
                        }
                    }
                    $this->Session->setFlash('Cambio de estado de los registros realizados correctamente.', 'guardar_exito');
                }
            } catch (Exception $e) {
                $this->Session->setFlash('Ocurrió un error al intentar cambiar el estado de los registros, intente nuevamente.', 'guardar_error');
            }
            $this->redirect(Router::url($this->referer(), true));
        }


        $conditions = array();
        $limit = 25;
        if ($this->request->is('get')) {
            
            if (isset($this->request->query['alerta_id']) && is_numeric($this->request->query['alerta_id'])) {
                $conditions["Prebacklog_alertaParametro.alerta_id"] = $this->request->query['alerta_id'];
            }
            
            if (isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
                $limit = $this->request->query['limit'];
            }
            if (isset($this->request->query['criticidad_id']) && is_numeric($this->request->query['criticidad_id'])) {
                $conditions["Prebacklog_alertaParametro.criticidad_id"] = $this->request->query['criticidad_id'];
            }

            $query = http_build_query($this->request->query);
            $this->set(compact('query'));
        }



        $this->paginate = array(
            'fields' => array('Prebacklog_alertaParametro.*', 'Criticidad.nombre', 'Prebacklog_alerta.nombre'),
            'conditions' => $conditions,
            'limit' => $limit,
            'order' => 'Prebacklog_alertaParametro.id',
            'recursive' => 1
        );
        $registros = $this->paginate('Prebacklog_alertaParametro');

        $this->set(compact('registros'));
        $this->set(compact('limit'));
    }

    function alerta_parametro($id = '') {
        $this->set('titulo', 'Parámetros');
        $this->layout = 'metronic_principal';
        $this->loadModel('Prebacklog_alertaParametro');
        $this->loadModel('Prebacklog_alerta');

        $alertas = $this->Prebacklog_alerta->find('list', array('fields' => array('Prebacklog_alerta.id', 'Prebacklog_alerta.nombre')  ,'order' => array("Prebacklog_alerta.nombre"), 'recursive' => -1));
        $this->set(compact('alertas'));

        if ($this->request->is('post')) {
            $data = $this->request->data;
            
            //Limpia el comentario de cualquier tag
            $data['nombre'] = Sanitize::html($data['nombre']);            
            $data['unidad_medida'] = substr(Sanitize::html($data['unidad_medida']), 0, 49);
            $data['e'] = isset($data['e']) ? '1' : '0';
            $data['fecha_creacion'] = $id != "" ? $data['fecha_creacion'] :  date('Y-m-d H:i:s', time());
            $data['fecha_actualizacion'] =  date('Y-m-d H:i:s', time());
            
            $this->Prebacklog_alertaParametro->save($data);
            $this->Session->setFlash('Registro guardado correctamente.', 'guardar_exito');
            $this->redirect('index');
        }
        
        if ($id != "" && is_numeric($id)) {
            $PrebacklogAlerta = $this->Prebacklog_alertaParametro->find('first', array("conditions" => array("id" => $id), "fields" => "Prebacklog_alertaParametro.*", 'recursive' => -1));
            $this->set('data', $PrebacklogAlerta);
        }       
        
    }

}
