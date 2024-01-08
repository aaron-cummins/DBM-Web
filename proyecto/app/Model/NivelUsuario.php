<?php
	App::uses('AppModel', 'Model');
	class NivelUsuario extends AppModel {
		public $name = 'NivelUsuario';
		public $useTable = 'nivelusuario';
		//public $hasMany = 'Usuario';
		var $displayField = "nombre"; 
	}
?>