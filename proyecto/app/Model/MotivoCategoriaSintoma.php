<?php
	App::uses('AppModel', 'Model');
	class MotivoCategoriaSintoma extends AppModel {
		public $name = 'MotivoCategoriaSintoma';
		public $useTable = 'motivo_categoria_sintoma';
		public $belongsTo = array(
			'Motivo' => array(
				'className' => 'MotivoLlamado',
				'foreignKey' => 'motivo_id'
			),
			'Categoria' => array(
				'className' => 'SintomaCategoria',
				'foreignKey' => 'categoria_id'
			),
			'Sintoma' => array(
				'className' => 'Sintoma',
				'foreignKey' => 'sintoma_id'
			)
		);
	}
?>