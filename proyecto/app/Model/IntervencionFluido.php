<?php
	App::uses('AppModel', 'Model');
	class IntervencionFluido extends AppModel {
		public $name = 'IntervencionFluido';
		public $useTable = 'intervencion_fluidos';
		public $sequence = 'intervenciones_fluidos_id_seq';
		
		public $belongsTo = array(
			'Fluido' => array(
				'className' => 'Fluido',
				'foreignKey' => 'fluido_id'
			),
			'TipoIngreso' => array(
				'className' => 'FluidoTipoIngreso',
				'foreignKey' => 'tipo_ingreso_id'
			)
		);
	}
?>