<?php
	App::uses('AppModel', 'Model');
	class Elemento_Diagnostico extends AppModel {
		public $name = 'Elemento_Diagnostico';
		public $useTable = 'elemento_diagnostico';
		
		public $belongsTo = array(
			'Elemento' => array(
				'className' => 'Elemento',
				'foreignKey' => 'elemento_id'
			),
			'Diagnostico' => array(
				'className' => 'Diagnostico',
				'foreignKey' => 'diagnostico_id'
			)
		);
		
		public $recursive = -1;
	}
?>