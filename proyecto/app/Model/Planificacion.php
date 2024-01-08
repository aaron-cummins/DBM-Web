<?php
	App::uses('AppModel', 'Model');
	class Planificacion extends AppModel {
		public $name = 'Planificacion';
		public $useTable = 'planificacion';
                public $belongsTo = array(
			'Backlog' => array(
				'className' => 'Backlog',
				'foreignKey' => 'backlog_id'
			),
			'Faena' => array(
				'className' => 'Faena',
				'foreignKey' => 'faena_id'
			),
			'Unidad' => array(
				'className' => 'Unidad',
				'foreignKey' => 'unidad_id'
			),
			'Flota' => array(
				'className' => 'Flota',
				'foreignKey' => 'flota_id'
			),
			'Supervisor' => array(
				'className' => 'Usuario',
				'foreignKey' => 'aprobador_id'
			),
			'Estado' => array(
				'className' => 'Estado',
				'foreignKey' => 'estado'
			),
			'Sintoma' => array(
				'className' => 'Sintoma',
				'foreignKey' => 'sintoma_id'
			),
			'SintomaCategoria' => array(
				'className' => 'SintomaCategoria',
				'foreignKey' => 'categoria_sintoma_id'
			),
			'MotivoLlamado' => array(
				'className' => 'MotivoLlamado',
				'foreignKey' => 'motivo_llamado_id'
			),
			'LugarReparacion' => array(
				'className' => 'LugarReparacion',
				'foreignKey' => 'lugar_reparacion_id'
			),
			'Turno' => array(
				'className' => 'Turno',
				'foreignKey' => 'turno_id'
			),
			'Periodo' => array(
				'className' => 'Periodo',
				'foreignKey' => 'periodo_id'
			),
                        
		);
                
	}
?>