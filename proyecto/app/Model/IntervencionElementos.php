<?php
	App::uses('AppModel', 'Model');
	class IntervencionElementos extends AppModel {
		public $name = 'IntervencionElementos';
		public $useTable = 'intervencion_elementos';
		
		public $belongsTo = array(
			'Sistema' => array(
				'className' => 'Sistema',
				'foreignKey' => 'sistema_id'
			),
			'Subsistema' => array(
				'className' => 'Subsistema',
				'foreignKey' => 'subsistema_id'
			),
			'Elemento' => array(
				'className' => 'Elemento',
				'foreignKey' => 'elemento_id'
			),
			'Diagnostico' => array(
				'className' => 'Diagnostico',
				'foreignKey' => 'diagnostico_id'
			),
			'Solucion' => array(
				'className' => 'Solucion',
				'foreignKey' => 'solucion_id'
			),
			'Posiciones_Elemento' => array(
				'className' => 'Posiciones_Elemento',
				'foreignKey' => 'elemento_posicion_id'
			),
			'Posiciones_Subsistema' => array(
				'className' => 'Posiciones_Subsistema',
				'foreignKey' => 'subsistema_posicion_id'
			),
			'TipoElemento' => array(
				'className' => 'TipoElemento',
				'foreignKey' => 'tipo_id'
			),
			'PosicionSubsistema' => array(
				'className' => 'Posiciones_Subsistema',
				'foreignKey' => 'subsistema_posicion_id'
			),
			'PosicionElemento' => array(
				'className' => 'Posiciones_Elemento',
				'foreignKey' => 'elemento_posicion_id'
			)
		);
	}
?>