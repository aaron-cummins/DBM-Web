<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PrebacklogEnvioEmailController
 *
 * @author AZUNIGA
 */
App::uses('ConnectionManager', 'Model');
App::import('Controller', 'Utilidades');
App::uses('Sanitize', 'Utility');

class PrebacklogEnvioEmailController extends AppController {

    //put your code here

    function index() {
        $this->set('titulo', 'Envio email');
        $this->layout = 'metronic_principal';
        $this->loadModel('Prebacklog_envioEmail');
        $this->loadModel('Prebacklog_envioEmailFaena');
        $this->loadModel('Faena');

        $limit = 10;
        $nombre = '';
        $email = '';
        $faena_id = '';
        $cargo = '';

        if ($this->request->is('post')) {
            try {
                foreach ($this->request->data['registro'] as $key => $value) {
                    if ($value == '0') {
                        if (isset($this->request->data['estado']) && isset($this->request->data['estado'][$key]) && $this->request->data['estado'][$key] == '1') {
                            // Estaba desactivado y se activo
                            $data = array();
                            $data["id"] = $key;
                            $data["e"] = "1";
                            $this->Prebacklog_envioEmail->save($data);
                        }
                    }
                    if ($value == '1') {
                        if (!isset($this->request->data['estado'][$key])) {
                            // Estaba activado y se desactivo
                            $data = array();
                            $data["id"] = $key;
                            $data["e"] = "0";
                            $this->Prebacklog_envioEmail->save($data);
                        }
                    }
                }
                $this->Session->setFlash('Cambio de estado de los registros realizados correctamente.', 'guardar_exito');
            } catch (Exception $e) {
                $this->Session->setFlash('Ocurrió un error al intentar cambiar el estado de los registros, intente nuevamente.', 'guardar_error');
            }
            $this->redirect(Router::url($this->referer(), true));
        }


        $conditions = array();
        if ($this->request->is('get')) {
            if (isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
                $limit = $this->request->query['limit'];
            }
            if (isset($this->request->query['estado']) && is_numeric($this->request->query['estado'])) {
                $conditions["Prebacklog_envioEmail.e"] = $this->request->query['estado'];
            }
            if (isset($this->request->query['data']['nombre']) && $this->request->query['data']['nombre'] != '') {
                $conditions["LOWER(Prebacklog_envioEmail.nombre) LIKE"] = '%' . strtolower($this->request->query['data']['nombre']) . '%';
                $nombre = $this->request->query['data']['nombre'];
            }

            if (isset($this->request->query['data']['email']) && $this->request->query['data']['email'] != '') {
                $conditions["LOWER(Prebacklog_envioEmail.email) LIKE"] = '%' . strtolower($this->request->query['data']['email']) . '%';
                $email = $this->request->query['data']['email'];
            }
            if (isset($this->request->query['data']['faena_id']) && $this->request->query['data']['faena_id'] != '') {
                $conditions["Faena.id"] = $this->request->query['data']['faena_id'];
                $faena_id = $this->request->query['data']['faena_id'];
            }
            if (isset($this->request->query['data']['cargo']) && $this->request->query['data']['cargo'] != '') {
                $conditions["LOWER(Prebacklog_envioEmail.cargo) LIKE"] = '%' . strtolower($this->request->query['data']['cargo']) . '%';
                $cargo = $this->request->query['data']['cargo'];
            }
            foreach ($this->request->query as $key => $value) {
                $this->set($key, $value);
            }
        }

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        
        $faenas_email = array();
        if($faena_id != ''){
            $faenas_email = $this->Prebacklog_envioEmailFaena->find('all', array('fields' => array('Prebacklog_envioEmailFaena.envioemail_id', 'Prebacklog_envioEmailFaena.faena_id','Faena.nombre'), 'conditions' => array('Prebacklog_envioEmailFaena.faena_id' => $faena_id), 'recursive' => 1));
        }else{
            $faenas_email = $this->Prebacklog_envioEmailFaena->find('all', array('fields' => array('Prebacklog_envioEmailFaena.envioemail_id', 'Prebacklog_envioEmailFaena.faena_id','Faena.nombre'), 'recursive' => 1));
        }
        
        $listado = array();
        foreach($faenas_email as $permiso){
            $listado[$permiso['Prebacklog_envioEmailFaena']['envioemail_id']]['Faena'][$permiso["Prebacklog_envioEmailFaena"]["faena_id"]] = $permiso['Faena']['nombre'];
        }
        
        $this->paginate = array(
            'fields' => array('Prebacklog_envioEmail.*'),
            'limit' => $limit, 
            'order' => 'Prebacklog_envioEmail.nombre', 'recursive' => -1, 
            'conditions' => $conditions);
        
        $this->set('registros', $this->paginate('Prebacklog_envioEmail'));
        $this->set('listado', $listado);

        $this->set('limit', $limit);
        $this->set('nombre', $nombre);
        $this->set('faena_id', $faena_id);
        $this->set('cargo', $cargo);
        $this->set('email', $email);
    }

    function formulario($id = "") {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);
        $this->loadModel('Prebacklog_envioEmail');
        $this->loadModel('Prebacklog_envioEmailFaena');
        $this->loadModel('Faena');

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.e' => '1'), 'order' => array("Faena.nombre" => 'asc'), 'recursive' => -1));
        $this->set(compact('faenas'));

        if($this->request->is('get')){
            $faenas_permiso = array();
            $usuario = array();
            
            if($id != ""){
                $usuario = $this->Prebacklog_envioEmail->find('first', array('conditions'=>array('id='.$id),'recursive'=>-1));
                $faenas_email = $this->Prebacklog_envioEmailFaena->find('all', array('conditions'=>array('envioemail_id='.$id)));
                foreach($faenas_email as $value) {
                     $faenas_permiso[] = $value["Prebacklog_envioEmailFaena"]["faena_id"];
                } 
            }
            $this->set('data', $usuario);
            $this->set('faenas_email', $faenas_permiso);
            
            return;
        }
        
        if ($this->request->is('post')) {
            try {

                if($id == ""){
                    $usuario_check = $this->Prebacklog_envioEmail->find('first', array('fields' => array("Prebacklog_envioEmail.id"),
                        'conditions' => array('Prebacklog_envioEmail.email' => $this->request->data["email"]),
                        'recursive' => -1));
                    if (isset($usuario_check["Prebacklog_envioEmail"]["id"])) {
                        $this->Session->setFlash('El correo electrónico ingresado ya existe en DBM.', 'guardar_error');
                        $this->request->data["Prebacklog_envioEmail"] = $this->request->data;
                        $this->set('data', $this->request->data);
                        return;
                    }
                }else{
                    $data['id'] = $id;
                }
                
                $data['nombre'] = ($this->request->data["nombre"]);
                $data['email'] = $this->request->data["email"];
                $data['cargo'] = ($this->request->data["cargo"]);
                $data['receptor'] = isset($this->request->data['receptor']) ? '1' : '0';
                $data['e'] = isset($this->request->data['e']) ? '1' : '0';
                
                
                if($this->Prebacklog_envioEmail->save($data)){
                    $codigo = $this->Prebacklog_envioEmail->id;
                    
                    $this->Prebacklog_envioEmailFaena->deleteAll(array('Prebacklog_envioEmailFaena.envioemail_id' => $codigo), false);
                    
                    foreach($this->request->data('faenas') as $value) {
                        $data_f = array();
                        $data_f["envioemail_id"] = $codigo;
                        $data_f["faena_id"] = $value;
                        
                        $this->Prebacklog_envioEmailFaena->create();
			$this->Prebacklog_envioEmailFaena->save($data_f);
                    }
                    
                    $this->Session->setFlash('Registro guardado con éxito.', 'guardar_exito');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('No se pudo agregar el registro, intente nuevamente.', 'guardar_error');
                }
                
                
            } catch (Exception $e) {
                $this->Session->setFlash('Ocurrió un error al intentar agregar el registro, intente nuevamente.', 'guardar_error');
            }
        }
    }
    
}
