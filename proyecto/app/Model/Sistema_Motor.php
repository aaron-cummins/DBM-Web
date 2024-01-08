<?php
	App::uses('AppModel', 'Model');
	class Sistema_Motor extends AppModel {
		public $name = 'Sistema_Motor';
		public $useTable = 'sistema_motor';
		
		public $belongsTo = array(
			'Sistema' => array(
				'className' => 'Sistema',
				'foreignKey' => 'sistema_id'
			),
			'Motor' => array(
				'className' => 'Motor',
				'foreignKey' => 'motor_id'
			)
		);
		
		public $recursive = 1;
	}
?>