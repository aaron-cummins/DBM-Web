<?php
	App::uses('AppModel', 'Model');
	class Trabajo extends AppModel {
		public $name = 'Trabajo';
		public $useTable = 'trabajo';
		
		public $hasMany = array(
			'Planificacion' => array(
				'className' => 'Planificacion',
				'foreignKey' => 'trabajoid'
			)
		);
	}
?>