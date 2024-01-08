<?php
	App::uses('AppModel', 'Model');
	class Inactividad extends AppModel {
		public $name = 'Inactividad';
		public $useTable = 'bitacora_inactividad';
		public $belongsTo = array(
			'Tecnico' => array(
				'className' => 'Usuario',
				'foreignKey' => 'tecnico_id'
			)
		);
	}
?>