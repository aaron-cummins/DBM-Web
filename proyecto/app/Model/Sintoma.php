<?php
	App::uses('AppModel', 'Model');
	class Sintoma extends AppModel {
		public $name = 'Sintoma';
		public $useTable = 'sintoma';
		var $displayField = "nombre";
		
		public $belongsTo = array(
			'Categoria' => array(
				'className' => 'SintomaCategoria',
				'foreignKey' => 'sintoma_categoria_id'
			)
		);
	}
?>