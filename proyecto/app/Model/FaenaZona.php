<?php
	App::uses('AppModel', 'Model');
	class FaenaZona extends AppModel {
		public $name = 'FaenaZona';
		public $useTable = 'faena_zona';
		var $displayField = "nombre"; 
	}
?>