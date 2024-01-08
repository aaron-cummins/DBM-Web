<?php
	App::uses('ConnectionManager', 'Model'); 
	class ValidarController extends AppController {
		public function index($code="") {
			$this->set('titulo', 'Validar');
			$this->layout=null;
			//localStorage.getItem("faena_id")+";"+localStorage.getItem("flota_id")+";"+localStorage.getItem("equipo_id")+";"+localStorage.getItem("tecnico_principal")+";"+localStorage.getItem("tipo_intervencion")+";"+$(".fecha_inicio").text();
			$this->set("c",$code);
		}
	}
?>