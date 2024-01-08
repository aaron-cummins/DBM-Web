<?php
	App::uses('AppModel', 'Model');
	class Turno extends AppModel {
		public $name = 'Turno';
		public $useTable = 'matriz_turno';
		var $displayField = "nombre";
	}
?>