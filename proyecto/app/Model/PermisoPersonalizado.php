<?php
	App::uses('AppModel', 'Model');
	class PermisoPersonalizado extends AppModel {
		public $name = 'PermisoPersonalizado';
		public $useTable = 'permisos_personalizados';
		public $belongsTo = array(
			'Usuario' => array(
				'className' => 'Usuario',
				'foreignKey' => 'usuario_id'
			),
			'Cargo' => array(
				'className' => 'NivelUsuario',
				'foreignKey' => 'cargo_id'
			),
			'Faena' => array(
				'className' => 'Faena',
				'foreignKey' => 'faena_id'
			)
		);
	}
?>