<?php
/**
 * Description of Prebacklog_parametros
 *
 * @author AZUNIGA
 */
App::uses('AppModel', 'Model');
class Prebacklog_parametros extends AppModel {
    //put your code here
    public $name = 'Prebacklog_parametros';
    public $useTable = 'prebacklog_parametros';
    
    public $belongsTo = array(
        'Prebacklog_alertaParametro' => array(
            'className' => 'Prebacklog_alertaParametro',
            'foreignKey' => 'alerta_parametro_id'
        ),
        'Prebacklog_motivoAceite' => array(
            'className' => 'Prebacklog_motivoAceite',
            'foreignKey' => 'motivoaceite_id'
        )
    );
}
