<?php
	App::uses('AppModel', 'Model');
	class EstadoMotorInstalacion extends AppModel {
		public $name = 'EstadoMotorInstalacion';
		public $useTable = 'estado_motor_instalacion';
		var $displayField = "nombre"; 
	}
?>