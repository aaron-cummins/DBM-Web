<?php
	App::uses('AppModel', 'Model');
	class SistemaSubsistema extends AppModel {
		public $name = 'SistemaSubsistema';
		public $useTable = 'sistema_subsistema';
		
		public $belongsTo = array(
			'Sistema' => array(
				'className' => 'Sistema',
				'foreignKey' => 'sistema_id'
			),
			'Subsistema' => array(
				'className' => 'Subsistema',
				'foreignKey' => 'subsistema_id'
			)
		);
		
		public $recursive = 2;
	}
?>