<?php
	App::uses('AppModel', 'Model');
	class EstadosMotores extends AppModel {
		public $name = 'EstadosMotores';
		public $useTable = 'estado_motor';
		
		public $belongsTo = array(
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
			),
			'Unidad' => array(
				'className' => 'Unidad',
				'foreignKey' => 'unidad_id'
			)
		);
	}
?>