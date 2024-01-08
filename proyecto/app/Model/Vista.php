<?php
	App::uses('AppModel', 'Model');
	class Vista extends AppModel {
		public $name = 'Vista';
		public $useTable = 'vistas';
		var $displayField = "nombre";
	}
?>