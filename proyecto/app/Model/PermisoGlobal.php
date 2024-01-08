<?php
	App::uses('AppModel', 'Model');
	class PermisoGlobal extends AppModel {
		public $name = 'PermisoGlobal';
		public $useTable = 'permisos_globales';
		
		public $belongsTo = array(
			'Cargo' => array(
				'className' => 'NivelUsuario',
				'foreignKey' => 'cargo_id'
			)
		);
	}
?>