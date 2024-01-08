<?php
	App::uses('AppModel', 'Model');
	class TipoContrato extends AppModel {
		public $name = 'TipoContrato';
		public $useTable = 'tipo_contrato';
		var $displayField = "nombre"; 
	}
?>