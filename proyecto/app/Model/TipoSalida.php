<?php
	App::uses('AppModel', 'Model');
	class TipoSalida extends AppModel {
		public $name = 'TipoSalida';
		public $useTable = 'tipo_salida';
		var $displayField = "nombre";
	}
?>