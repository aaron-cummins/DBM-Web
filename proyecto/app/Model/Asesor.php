<?php
	App::uses('AppModel', 'Model');
	class Asesor extends AppModel {
		public $name = 'Asesor';
		public $useTable = 'asesor_tecnico';
		public $belongsTo = array(
			'Faena' => array(
				'className' => 'Faena',
				'foreignKey' => 'faena_id'
			)
		);
	}
?>