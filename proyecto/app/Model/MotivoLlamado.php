<?php
	App::uses('AppModel', 'Model');
	class MotivoLlamado extends AppModel {
		public $name = 'MotivoLlamado';
		public $useTable = 'motivo_llamado';
		var $displayField = "nombre"; 
	}
?>