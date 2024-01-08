<?php
	App::uses('AppModel', 'Model');
	class FluidoUnidad extends AppModel {
		public $name = 'FluidoUnidad';
		public $useTable = 'matriz_fluidos_unidad';
		var $displayField = "nombre"; 
	}
?>