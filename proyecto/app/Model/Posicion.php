<?php
	App::uses('AppModel', 'Model');
	class Posicion extends AppModel {
		public $name = 'Posicion';
		public $useTable = 'subsistema_posicion';
		/*
		public $belongsTo = array(
			'Categoria' => array(
				'className' => 'Sistema',
				'foreignKey' => 'sintoma_categoria_id'
			)
		);*/
	}
?>