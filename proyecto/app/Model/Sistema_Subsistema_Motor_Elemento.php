<?php
	App::uses('AppModel', 'Model');
	class Sistema_Subsistema_Motor_Elemento extends AppModel {
		public $name = 'Sistema_Subsistema_Motor_Elemento';
		public $useTable = 'sistema_subsistema_motor_elemento';
		
		public $belongsTo = array(
			'Motor' => array(
				'className' => 'Motor',
				'foreignKey' => 'motor_id'
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
			'Posicion' => array(
				'className' => 'Posiciones_Elemento',
				'foreignKey' => 'posicion_id'
			)
		);
		
		public $recursive = 1;
	}
?>