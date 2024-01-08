<?php
	App::uses('AppModel', 'Model');
	class TipoTecnico extends AppModel {
		public $name = 'TipoTecnico';
		public $useTable = 'tipo_tecnico';
		var $displayField = "nombre";
	}
?>