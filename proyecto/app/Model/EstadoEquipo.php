<?php
	App::uses('AppModel', 'Model');
	class EstadoEquipo extends AppModel {
		public $name = 'EstadoEquipo';
		public $useTable = 'estado_equipos';
		var $displayField = "nombre"; 
	}
?>