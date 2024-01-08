<?php
	App::uses('AppModel', 'Model');
	class Periodo extends AppModel {
		public $name = 'Periodo';
		public $useTable = 'matriz_periodo';
		var $displayField = "nombre";
	}
?>