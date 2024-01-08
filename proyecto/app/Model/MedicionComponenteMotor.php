<?php

/**
 * Description of MedicionComponente
 *
 * @author AZUNIGA
 */
App::uses('AppModel', 'Model');
class MedicionComponenteMotor extends AppModel {
    //put your code here
    public $name = 'MedicionComponenteMotor';
    public $useTable = 'medicion_componente_motor';
    var $displayField = "componente_id";
    
    public $belongsTo = array(
			'Motor' => array(
				'className' => 'Motor',
				'foreignKey' => 'motor_id'
			),
                        'MedicionComponente' => array(
				'className' => 'MedicionComponente',
				'foreignKey' => 'componente_id'
			)
        );
}
