<?php
	App::uses('AppModel', 'Model');
	class Comentario extends AppModel {
		public $name = 'Comentario';
		public $useTable = 'comentario';
		public $belongsTo = array(
			'Usuario' => array(
				'className' => 'Usuario',
				'foreignKey' => 'usuario_id'
			),
			'Planificacion' => array(
				'className' => 'Planificacion',
				'foreignKey' => 'planificacion_id'
			)
		);
	}
?>