<?php

/**
 * Description of Prebacklog
 *
 * @author AZUNIGA
 */
App::uses('AppModel', 'Model');

class Prebacklog extends AppModel {

    public $name = 'Prebacklog';
    public $useTable = 'prebacklog';
    public $belongsTo = array(
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id'
        ),
        'Unidad' => array(
            'className' => 'Unidad',
            'foreignKey' => 'unidad_id'
        ),
        'Faena' => array(
            'className' => 'Faena',
            'foreignKey' => 'faena_id'
        ),
        'Flota' => array(
            'className' => 'Flota',
            'foreignKey' => 'flota_id'
        ),
        'Estado' => array(
            'className' => 'Estado',
            'foreignKey' => 'estado_id'
        ),
        'Criticidad' => array(
            'className' => 'Criticidad',
            'foreignKey' => 'criticidad_id'
        ),
        'Sintoma' => array(
            'className' => 'Sintoma',
            'foreignKey' => 'sintoma_id'
        ),
        'SintomaCategoria' => array(
            'className' => 'SintomaCategoria',
            'foreignKey' => 'categoria_sintoma_id'
        ),
        'Prebacklog_categoria' => array(
            'className' => 'Prebacklog_categoria',
            'foreignKey' => 'categoria_id'
        ),
        'Prebacklog_motivoCierre' => array(
            'className' => 'Prebacklog_motivoCierre',
            'foreignKey' => 'motivocierre_id'
        )
    );

}
