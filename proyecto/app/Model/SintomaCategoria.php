<?php
	App::uses('AppModel', 'Model');
	class SintomaCategoria extends AppModel {
		public $name = 'SintomaCategoria';
		public $useTable = 'sintoma_categoria';
		var $displayField = "nombre";
		
		public $hasMany = array(
			'Sintomas' => array(
				'className' => 'Sintoma',
				'foreignKey' => 'sintoma_categoria_id'
			)
		);
	}
?>