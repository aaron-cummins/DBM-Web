<?php
/**
 * Description of Medicion
 *
 * @author AZUNIGA
 */
App::uses('AppModel', 'Model');
class Medicion extends AppModel {
    //put your code here
    public $name = 'Medicion';
    public $useTable = 'medicion';
    public $belongsTo = array(
            'Faena' => array(
                    'className' => 'Faena',
                    'foreignKey' => 'faena_id'
            ),
            'Unidad' => array(
                    'className' => 'Unidad',
                    'foreignKey' => 'unidad_id'
            ),
            'Flota' => array(
                    'className' => 'Flota',
                    'foreignKey' => 'flota_id'
            ),
            'MedicionComponente' => array(
                    'className' => 'MedicionComponente',
                    'foreignKey' => 'componente_id'
            )
        );
}
