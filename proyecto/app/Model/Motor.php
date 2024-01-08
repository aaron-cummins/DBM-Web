<?php
	App::uses('AppModel', 'Model');
	class Motor extends AppModel {
		public $name = 'Motor';
		public $useTable = 'motor';
		var $displayField = "nombre";
		
		public $belongsTo = array(
			'TipoAdmision' => array(
				'className' => 'TipoAdmision',
				'foreignKey' => 'tipo_admision_id'
			),
			'TipoEmision' => array(
				'className' => 'TipoEmision',
				'foreignKey' => 'tipo_emision_id'
			)
		);
	}
?>