<?php
	App::uses('AppModel', 'Model');
	class UnidadDetalle extends AppModel {
		public $name = 'UnidadDetalle';
		public $useTable = 'unidad_detalle';
		public $belongsTo = array(
			'Unidad' => array(
				'className' => 'Unidad',
				'foreignKey' => 'id'
			)
		);
	}
?>