<?php
	App::uses('AppModel', 'Model');
	class EstadoMotor extends AppModel {
		public $name = 'EstadoMotor';
		public $useTable = 'estado_motores';
		var $displayField = "nombre"; 
	}
?>