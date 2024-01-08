<?php
	App::uses('AppModel', 'Model');
	class Intervencion extends AppModel {
		public $name = 'Intervencion';
		public $useTable = 'intervencion';
		
		public $belongsTo = array(
			'Planificacion' => array(
				'className' => 'Planificacion',
				'foreignKey' => 'planificacion_id'
			)
		);
	}
?>