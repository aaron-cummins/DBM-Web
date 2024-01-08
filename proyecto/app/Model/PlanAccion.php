<?php

/**
 * Description of PlanAccion
 *
 * @author AZUNIGA
 */
App::uses('AppModel', 'Model');

class PlanAccion extends AppModel {

    public $name = 'PlanAccion';
    public $useTable = 'plan_accion';
    public $belongsTo = array(
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id'
        ),
        'Sintoma' => array(
            'className' => 'Sintoma',
            'foreignKey' => 'sintoma_id'
        ),
        'SintomaCategoria' => array(
            'className' => 'SintomaCategoria',
            'foreignKey' => 'categoria_sintoma_id'
        ),
        'Sistema' => array(
				'className' => 'Sistema',
				'foreignKey' => 'sistema_id'
        ),
        'Subsistema' => array(
            'className' => 'Subsistema',
            'foreignKey' => 'subsistema_id'
        ),
        'Elemento' => array(
            'className' => 'Elemento',
            'foreignKey' => 'elemento_id'
        ),
        'Diagnostico' => array(
            'className' => 'Diagnostico',
            'foreignKey' => 'diagnostico_id'
        ),
        'Posiciones_Elemento' => array(
            'className' => 'Posiciones_Elemento',
            'foreignKey' => 'elemento_posicion_id'
        ),
        'Posiciones_Subsistema' => array(
            'className' => 'Posiciones_Subsistema',
            'foreignKey' => 'subsistema_posicion_id'
        ),
        'PosicionSubsistema' => array(
            'className' => 'Posiciones_Subsistema',
            'foreignKey' => 'subsistema_posicion_id'
        ),
        'PosicionElemento' => array(
            'className' => 'Posiciones_Elemento',
            'foreignKey' => 'elemento_posicion_id'
        ),
        'Motor' => array(
            'className' => 'Motor',
            'foreignKey' => 'motor_id'
        ),
    );

}