<?php
	App::uses('AppModel', 'Model');
	class LugarReparacion extends AppModel {
		public $name = 'LugarReparacion';
		public $useTable = 'matriz_lugar_reparacion';
		var $displayField = "nombre";
	}
?>