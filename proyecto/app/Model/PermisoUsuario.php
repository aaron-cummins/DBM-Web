<?php
	App::uses('AppModel', 'Model');
	class PermisoUsuario extends AppModel {
		public $name = 'PermisoUsuario';
		public $useTable = 'permisos_usuarios';
		
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