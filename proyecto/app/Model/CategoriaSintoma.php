<?php
	App::uses('AppModel', 'Model');
	class CategoriaSintoma extends AppModel {
		public $name = 'CategoriaSintoma';
		public $useTable = 'sintoma_categoria';
		var $displayField = "nombre";
	}
?>