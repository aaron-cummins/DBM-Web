<?php
	App::uses('AppModel', 'Model');
	class Usuario extends AppModel {
		public $name = 'Usuario';
		public $useTable = 'usuario';
		var $displayField = "apellidos"; 
	}
?>