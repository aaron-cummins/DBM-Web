<?php
	App::uses('AppModel', 'Model');
	class Fluido extends AppModel {
		public $name = 'Fluido';
		public $useTable = 'matriz_fluidos';
		var $displayField = "nombre"; 
		
		public $belongsTo = array(
			'FluidoUnidad' => array(
				'className' => 'FluidoUnidad',
				'foreignKey' => 'unidad_id'
			)
		);
		
	}
?>