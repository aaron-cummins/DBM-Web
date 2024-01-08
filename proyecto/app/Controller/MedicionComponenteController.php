<?php

/**
 * Description of MedicionComponenteController
 *
 * @author AZUNIGA
 */

App::uses('ConnectionManager', 'Model'); 
App::uses('File', 'Utility');
App::import('Controller', 'Utilidades');
App::uses('CakeEmail', 'Network/Email');
require_once '../../vendor/autoload.php';

class MedicionComponenteController extends AppController{
    //put your code here
    
    function index($html){
        $this->layout = 'metronic_principal';
        $this->loadModel('MedicionComponente');
        
        $componentes = $this->MedicionComponente->find('all', array('fields'=>array('MedicionComponente.*'), 'order' => 'nombre', 'recursive' => -1));
        $this->set('componentes', $componentes);
        $this->set('titulo', 'Componentes a medir');
        
        if($html == 1){
            
            $html = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-check"></i> ÉXITO!</strong>'.
                        ' Datos guardados.'.
                    '</div>';
            $this->set('alert', $html);
        }
        
    }
    
    
    function grabar($id = 0){
        $this->set('titulo', 'Medición');
        $this->layout = 'metronic_principal';
        $this->loadModel('MedicionComponente');
        $this->loadModel('Motor');
        $this->loadModel('MedicionComponenteMotor');
        
        $motores = $this->Motor->find('all', array('fields'=>array('Motor.id', 'Motor.nombre', 'TipoEmision.nombre'), 'recursive' => 1));
        $this->set('motores', $motores);
        
        if ($this->request->is('post') && isset($this->request->data)) {
            $data = $this->request->data;
            
            $medicion['id'] = $data['id'];
            $medicion['nombre'] = $data['nombre'];
            $medicion['unidad_medida'] = $data['unidad_medida'];
            $medicion['optimo_min'] = str_replace(',','.',$data['optimo_min']);
            $medicion['optimo_max'] = str_replace(',','.',$data['optimo_max']);
            $medicion['optimo_mensaje'] = trim($data['optimo_mensaje']);
            $medicion['optimo_alerta'] = trim($data['optimo_alerta']);
            $medicion['medio_min'] = str_replace(',','.',$data['medio_min']);
            $medicion['medio_max'] = str_replace(',','.',$data['medio_max']);
            $medicion['medio_mensaje'] = trim($data['medio_mensaje']);
            $medicion['medio_alerta'] = trim($data['medio_alerta']);
            $medicion['alto_min'] = str_replace(',','.',$data['alto_min']);
            $medicion['alto_max'] = str_replace(',','.',$data['alto_max']);
            $medicion['alto_mensaje'] = trim($data['alto_mensaje']);
            $medicion['alto_alerta'] = trim($data['alto_alerta']);
            $medicion['e'] = 1;
            $medicion['medicion_inicial'] = $data['medicion_inicial'];
            $medicion['compara_medidas'] = isset($data['compara_medidas']) ? $data['compara_medidas'] : False;
            $medicion['ingresa_medicion'] = isset($data['ingresa_medicion']) ? $data['ingresa_medicion'] : False;
            $medicion['selecciona_fecha'] = isset($data['selecciona_fecha']) ? $data['selecciona_fecha'] : False;
            
            $medicion['especiales'] = htmlentities(trim($data['especiales']), ENT_QUOTES);
            
            
            $permitido = array('png', 'jpg');
            $image_name = $this->request->data["imagen"];
            
            print_r($image_name);
            
            if(isset($this->request->data["file"])) {
                $data = $this->request->data["file"];
                $inputFileName = $data["name"];
                
                try {
                    $ext = pathinfo($inputFileName, PATHINFO_EXTENSION);
                    if (!in_array($ext, $permitido)) {
                        $this->Session->setFlash('Ocurrió un error al intentar cargar la imagen, intente nuevamente.','guardar_error');
                        return;
                    }
                    $newfile = '/app/webroot/images/mediciones/'. $inputFileName ; //$image_name.'.png';
                    
                    if (!move_uploaded_file($inputFileName, $newfile)) {
                        
                        $this->Session->setFlash('Ocurrió un error al intentar cargar la imagen, intente nuevamente.','guardar_error');
                        return;
                    }
                    
                    copy($inputFileName, $image_name);
                } catch(Exception $e) {
                    $this->Session->setFlash('Ocurrió un error al intentar cargar la imagen, intente nuevamente.','guardar_error');
                    return;
                }
            }
            $medicion['imagen'] = $inputFileName; //$image_name;
            
            $this->MedicionComponente->save($medicion);
            
            $mcm = $this->MedicionComponente->Query('SELECT max(id) id from medicion_componente;');
            $this->MedicionComponenteMotor->deleteAll(array('componente_id' => $data['id']), false);
            
            sort($data["especial"]);
            
            //$count = count($data["especial"]);
            // 
            $arr = $data["especial"];    
            foreach($data["especial"] as $a){
                //$a = $arr[$i];
                $especial = Array();
                $especial['motor_id'] = $a['motor'];
                $especial['posiciones'] = $a['posicion'];
                $especial['motivo_cambio'] = $a['motivo_cambio'];
                $especial['opciones_cambio'] = $a['opciones_cambio'];
                
                if(isset($data['id']) || $data['id'] != ""){
                    $especial['componente_id']  = $data['id'];
                }else{
                    $especial['componente_id']  =  $mcm['id'];
                }
                $especial['cambio'] = $a['cambio'];
                //print_r($especial);
                
                $this->MedicionComponenteMotor->create();
                $this->MedicionComponenteMotor->save($especial);
            }
            
            $this->redirect(array("controller" => "MedicionComponente", "action" => "index", 1));
                
        }elseif(isset($id) && $id > 0){
            $med = $this->MedicionComponente->find('first', array(
                    'fields'=> array("MedicionComponente.*"), 
                    'conditions' => array('MedicionComponente.id' => $id)));
            
            $medicion['id'] = $id;
            $medicion['nombre'] = $med['MedicionComponente']['nombre'];
            $medicion['unidad_medida'] = $med['MedicionComponente']['unidad_medida'];
            $medicion['optimo_min'] = str_replace(',','.', $med['MedicionComponente']['optimo_min']);
            $medicion['optimo_max'] = str_replace(',','.', $med['MedicionComponente']['optimo_max']);
            $medicion['optimo_mensaje'] = $med['MedicionComponente']['optimo_mensaje'];
            $medicion['optimo_alerta'] = $med['MedicionComponente']['optimo_alerta'];
            $medicion['medio_min'] = str_replace(',','.', $med['MedicionComponente']['medio_min']);
            $medicion['medio_max'] = str_replace(',','.', $med['MedicionComponente']['medio_max']);
            $medicion['medio_mensaje'] = $med['MedicionComponente']['medio_mensaje'];
            $medicion['medio_alerta'] = $med['MedicionComponente']['medio_alerta'];
            $medicion['alto_min'] = str_replace(',','.', $med['MedicionComponente']['alto_min']);
            $medicion['alto_max'] =str_replace(',','.',  $med['MedicionComponente']['alto_max']);
            $medicion['alto_mensaje'] = $med['MedicionComponente']['alto_mensaje'];
            $medicion['alto_alerta'] = $med['MedicionComponente']['alto_alerta'];
            $medicion['e'] = $med['MedicionComponente']['e'];
            $medicion['medicion_inicial'] = $med['MedicionComponente']['medicion_inicial'];
            $medicion['compara_medidas'] = $med['MedicionComponente']['compara_medidas'];
            $medicion['ingresa_medicion'] = $med['MedicionComponente']['ingresa_medicion'];
            $medicion['selecciona_fecha'] = $med['MedicionComponente']['selecciona_fecha'];
            
            $medicion['especiales'] = html_entity_decode($med['MedicionComponente']['especiales'], ENT_QUOTES);
            //$especiales = $this->MedicionComponenteMotor->find('all', array('conditions' => array('componente_id' => $id),'recursive'=>1));
            
            
            $especiales = $this->MedicionComponenteMotor->query("SELECT medicion_componente_motor.*,  motor.nombre strmotor, tipo_emision.nombre emision
                                        from medicion_componente_motor
                                        inner join motor on motor.id= medicion_componente_motor.motor_id
                                        inner join tipo_emision on tipo_emision.id = motor.tipo_emision_id
                                        Where medicion_componente_motor.componente_id = ".$id);
              
            $this->set('especiales', $especiales);
            $this->set('medicion', $medicion);
        }else{
            $medicion = [];
            $this->set('medicion', $medicion);
        } 
    }
}
