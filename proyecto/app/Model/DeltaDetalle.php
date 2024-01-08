<?php
	App::uses('AppModel', 'Model');
	class DeltaDetalle extends AppModel {
		public $name = 'DeltaDetalle';
		public $useTable = 'delta_detalle';
		public $belongsTo = array(
			'DeltaItem' => array(
				'className' => 'DeltaItem',
				'foreignKey' => 'delta_item_id'
			),
			'DeltaResponsable' => array(
				'className' => 'DeltaResponsable',
				'foreignKey' => 'delta_responsable_id'
			)
		);
	}
?>