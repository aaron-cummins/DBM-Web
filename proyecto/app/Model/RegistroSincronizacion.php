<?php
	App::uses('AppModel', 'Model');
	class RegistroSincronizacion extends AppModel {
		public $name = 'RegistroSincronizacion';
		public $useTable = 'registro_sincronizacion';
		/*
		public $hasMany = array(
			'Planificacion' => array(
				'className' => 'Planificacion',
				'foreignKey' => 'planificacion_id'
			)
		);*/
	}
?>