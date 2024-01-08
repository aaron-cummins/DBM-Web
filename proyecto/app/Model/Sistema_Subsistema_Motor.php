<?php
	App::uses('AppModel', 'Model');
	class Sistema_Subsistema_Motor extends AppModel {
		public $name = 'Sistema_Subsistema_Motor';
		public $useTable = 'sistema_subsistema_motor';
		
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
		);
		
		public $recursive = 1;
	}
?>