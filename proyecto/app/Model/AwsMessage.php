<?php
	App::uses('AppModel', 'Model');
	class AwsMessage extends AppModel {
		public $name = 'AwsMessage';
		public $useTable = 'aws_sqs_message';
		
		public $belongsTo = array(
			'Faena' => array(
				'className' => 'Faena',
				'foreignKey' => 'faena_id'
			),
			'Flota' => array(
				'className' => 'Flota',
				'foreignKey' => 'flota_id'
			),
			'Unidad' => array(
				'className' => 'Unidad',
				'foreignKey' => 'unidad_id'
			)
		);
	}
?>