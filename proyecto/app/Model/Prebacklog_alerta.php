<?php
/**
 * Description of Prebacklog_alerta
 *
 * @author AZUNIGA
 */
    App::uses('AppModel', 'Model');
class Prebacklog_alerta extends AppModel {
    //put your code here
    public $name = 'Prebacklog_alerta';
    public $useTable = 'prebacklog_alerta';
    public $belongsTo = array(
         'Criticidad' => array(
            'className' => 'Criticidad',
            'foreignKey' => 'criticidad_id'
        )
    );
}
