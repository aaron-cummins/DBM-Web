<?php
	App::uses('AppModel', 'Model');
	class Sistema extends AppModel {
		public $name = 'Sistema';
		public $useTable = 'sistema';
		var $displayField = "nombre"; 
	}
?>