<?php
	App::uses('AppModel', 'Model');
	class Faena extends AppModel {
		public $name = 'Faena';
		public $useTable = 'faena';
		var $displayField = "nombre"; 
		var $persistModel = true;
		
		public $belongsTo = array(
			'FaenaZona' => array(
				'className' => 'FaenaZona',
				'foreignKey' => 'zona_id'
			),
			'FaenaZonaNumerica' => array(
				'className' => 'FaenaZonaNumerica',
				'foreignKey' => 'zona_numerica_id'
			)
		);
	}
?>