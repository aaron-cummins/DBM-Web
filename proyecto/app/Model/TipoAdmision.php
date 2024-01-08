<?php
	App::uses('AppModel', 'Model');
	class TipoAdmision extends AppModel {
		public $name = 'TipoAdmision';
		public $useTable = 'tipo_admision';
		var $displayField = "nombre";
	}
?>