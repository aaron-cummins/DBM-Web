<?php
	App::uses('AppModel', 'Model');
	class FaenaFlota extends AppModel {
		public $name = 'FaenaFlota';
		public $useTable = 'faena_flota';
		
		public $belongsTo = array(
			'Faena' => array(
				'className' => 'Faena',
				'foreignKey' => 'faena_id'
			),
			'Flota' => array(
				'className' => 'Flota',
				'foreignKey' => 'flota_id'
			),
			'Motor' => array(
				'className' => 'Motor',
				'foreignKey' => 'motor_id'
			)
		);
	}
?>