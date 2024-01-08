<?php
	App::uses('AppModel', 'Model');
	class LugarCreacion extends AppModel {
		public $name = 'LugarCreacion';
		public $useTable = 'lugar_creacion';
		var $displayField = "nombre";
	}
?>