<?php
	App::uses('AppModel', 'Model');
	class Flota extends AppModel {
		public $name = 'Flota';
		public $useTable = 'flota';
		var $displayField = "nombre"; 
		/*
		public $belongsTo = array(
			'Faena' => array(
				'className' => 'Faena',
				'foreignKey' => 'faena_id'
			)
		);*/
	}
?>