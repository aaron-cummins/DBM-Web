<?php
	App::uses('AppModel', 'Model');
	class TipoEmision extends AppModel {
		public $name = 'TipoEmision';
		public $useTable = 'tipo_emision';
		var $displayField = "nombre";
	}
?>