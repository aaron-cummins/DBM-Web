<?php
App::uses('ConnectionManager', 'Model'); 
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Utilidades');
App::import('Vendor', 'Classes/fpdf');

class ReplanificacionController extends AppController {
    //put your code here
    
    public function historial($id){
        $this->layout = null;
        $this->loadModel('Replanificacion');
        $historial = $this->Replanificacion->find('all', 
                array('conditions' => array('Replanificacion.id_intervencion' => $id),
                    'order' => 'Replanificacion.id ASC'));
        $this->set('historial', $historial);
    }
    
}
