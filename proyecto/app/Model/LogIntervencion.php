<?php
	App::uses('AppModel', 'Model');
	class LogIntervencion extends AppModel {
		public $name = 'LogIntervencion';
		public $useTable = 'log_intervencion';
		
		public $belongsTo = array(
			'Usuario' => array(
				'className' => 'Usuario',
				'foreignKey' => 'usuario_id'
			)
		);
	}
?>