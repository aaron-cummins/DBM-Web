<?php
	App::uses('AppModel', 'Model');
	class ResponsableBacklog extends AppModel {
		public $name = 'ResponsableBacklog';
		public $useTable = 'responsable';
		var $displayField = "nombre";
	}
?>