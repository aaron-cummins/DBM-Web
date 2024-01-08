<?php
	App::uses('AppModel', 'Model');
	class MotorSistemaSubsistemaPosicion extends AppModel {
		public $name = 'MotorSistemaSubsistemaPosicion';
		public $useTable = 'motor_sistema_subsistema_posicion';
		
		public $belongsTo = array(
			'Sistema' => array(
				'className' => 'Sistema',
				'foreignKey' => 'sistema_id'
			),
			'Subsistema' => array(
				'className' => 'Subsistema',
				'foreignKey' => 'subsistema_id'
			),
			'Motor' => array(
				'className' => 'Motor',
				'foreignKey' => 'motor_id'
			),
			'Posicion' => array(
				'className' => 'Posiciones_Subsistema',
				'foreignKey' => 'posicion_id'
			),
			'Posicion_Subsistema' => array(
				'className' => 'Posiciones_Subsistema',
				'foreignKey' => 'posicion_id'
			)
		);
		public $recursive = 1;
	}
?>