<?php
	App::uses('AppModel', 'Model');
	class MotorSistemaSubsistemaElementoDiagnostico extends AppModel {
		public $name = 'MotorSistemaSubsistemaElementoDiagnostico';
		public $useTable = 'motor_sistema_subsistema_elemento_diagnostico';
		
		public $belongsTo = array(
			'Sistema' => array(
				'className' => 'Sistema',
				'foreignKey' => 'sistema_id'
			),
			'Subsistema' => array(
				'className' => 'Subsistema',
				'foreignKey' => 'subsistema_id'
			),
			'Motor' => array(
				'className' => 'Motor',
				'foreignKey' => 'motor_id'
			),
			'Elemento' => array(
				'className' => 'Elemento',
				'foreignKey' => 'elemento_id'
			),
			'Diagnostico' => array(
				'className' => 'Diagnostico',
				'foreignKey' => 'diagnostico_id'
			)
		);
		public $recursive = 1;
	}
?>