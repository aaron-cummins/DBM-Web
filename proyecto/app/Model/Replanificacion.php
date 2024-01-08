<?php
App::uses('AppModel', 'Model');
class Replanificacion extends AppModel{
    public $name = 'Replanificacion';
    public $useTable = 'replanificacion';
    
    public $belongsTo = array(
        'Motivo_replanificacion' => array(
            'className' => 'Motivo_replanificacion',
            'foreignKey' => 'motivo_id'
        ),
        'Planificacion' => array(
            'className' => 'Planificacion',
            'foreignKey' => 'id_intervencion'
        ),
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id'
        )
    );
}
