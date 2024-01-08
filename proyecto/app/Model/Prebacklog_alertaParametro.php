<?php
/**
 * Description of Prebacklog_alerta_parametro
 *
 * @author AZUNIGA
 */
App::uses('AppModel', 'Model');
class Prebacklog_alertaParametro extends AppModel {
    //put your code here
    public $name = 'Prebacklog_alertaParametro';
    public $useTable = 'prebacklog_alertaparametro';
    public $belongsTo = array(
         'Criticidad' => array(
            'className' => 'Criticidad',
            'foreignKey' => 'criticidad_id'
        ),
        'Prebacklog_alerta'=>array(
            'className' => 'Prebacklog_alerta',
            'foreignKey' => 'alerta_id'
        )
    );
}
