<?php
	App::uses('AppModel', 'Model');
	class Backlog extends AppModel {
		public $name = 'Backlog';
		public $useTable = 'backlog';
		public $belongsTo = array(
			'Usuario' => array(
				'className' => 'Usuario',
				'foreignKey' => 'usuario_id'
			),
			//'Elemento' => array(
			//	'className' => 'IntervencionElementos',
			//	'foreignKey' => 'elemento_id'
			//),
			'Sistema' => array(
				'className' => 'Sistema',
				'foreignKey' => 'sistema_id'
			),
			'Subsistema' => array(
				'className' => 'Subsistema',
				'foreignKey' => 'subsistema_id'
			),
			'Unidad' => array(
				'className' => 'Unidad',
				'foreignKey' => 'equipo_id'
			),
			'Faena' => array(
				'className' => 'Faena',
				'foreignKey' => 'faena_id'
			),
			'Flota' => array(
				'className' => 'Flota',
				'foreignKey' => 'flota_id'
			),
			'Estado' => array(
				'className' => 'Estado',
				'foreignKey' => 'estado_id'
			),
			'Intervencion' => array(
				'className' => 'Planificacion',
				'foreignKey' => 'intervencion_id'
			),
			'LugarCreacion' => array(
				'className' => 'LugarCreacion',
				'foreignKey' => 'creacion_id'
			),
			'Criticidad' => array(
				'className' => 'Criticidad',
				'foreignKey' => 'criticidad_id'
			),
			'ResponsableBacklog' => array(
				'className' => 'ResponsableBacklog',
				'foreignKey' => 'responsable_id'
			),
			'Sintoma' => array(
				'className' => 'Sintoma',
				'foreignKey' => 'sintoma_id'
			),
			'SintomaCategoria' => array(
				'className' => 'SintomaCategoria',
				'foreignKey' => 'categoria_sintoma_id'
			),
            'PlanAccion' => array(
                'className' => 'PlanAccion',
                'foreignKey' => 'plan_accion_id'
            )
		);
	}
?>