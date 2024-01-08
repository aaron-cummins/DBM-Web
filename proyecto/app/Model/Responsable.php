<?php
	App::uses('AppModel', 'Model');
	class Responsable extends AppModel {
		public $name = 'Responsable';
		public $useTable = 'estado_motor_responsable';
		var $displayField = "nombre"; 
	}
?>