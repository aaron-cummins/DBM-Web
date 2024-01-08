<?php
	App::uses('AppModel', 'Model');
	class FluidoTipoIngreso extends AppModel {
		public $name = 'FluidoTipoIngreso';
		public $useTable = 'matriz_fluidos_tipo_ingreso';
		var $displayField = "nombre"; 
	}
?>