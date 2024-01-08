<?php
	App::uses('AppModel', 'Model');
	class ElementoPool extends AppModel {
		public $name = 'ElementoPool';
		public $useTable = 'elemento_pool';
		
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
			'Elemento' => array(
				'className' => 'Elemento',
				'foreignKey' => 'elemento_id'
			),
		);
		public $recursive = 1;
	}
?>