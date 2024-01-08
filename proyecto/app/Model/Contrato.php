<?php
	App::uses('AppModel', 'Model');
	class Contrato extends AppModel {
		public $name = 'Contrato';
		public $useTable = 'tipo_contrato';
		var $displayField = "nombre"; 
	}
?>