<?php
	App::uses('AppModel', 'Model');
	class Log extends AppModel {
		public $name = 'Log';
		public $useTable = 'log';
		public $belongsTo = array(
			'Usuario' => array(
				'className' => 'Usuario',
				'foreignKey' => 'usuario_id'
			)
		);
	}
?>