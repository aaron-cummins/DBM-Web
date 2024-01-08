<?php
	App::uses('AppModel', 'Model');
	class Unidad extends AppModel {
		public $name = 'Unidad';
		public $useTable = 'unidad';
		
		public $belongsTo = array(
			'Motor' => array(
				'className' => 'Motor',
				'foreignKey' => 'motor_id'
			),
			'Flota' => array(
				'className' => 'Flota',
				'foreignKey' => 'flota_id'
			),
			'Faena' => array(
				'className' => 'Faena',
				'foreignKey' => 'faena_id'
			),
			'EstadoMotor' => array(
				'className' => 'EstadoMotor',
				'foreignKey' => 'estado_motor_id'
			),
			'EstadoEquipo' => array(
				'className' => 'EstadoEquipo',
				'foreignKey' => 'estado_equipo_id'
			),
			'TipoContrato' => array(
				'className' => 'TipoContrato',
				'foreignKey' => 'tipo_contrato_id'
			)
		);
	}
?>