<?php
	App::uses('ConnectionManager', 'Model'); 
	App::uses('CakeEmail', 'Network/Email');
	App::import('Controller', 'Utilidades');

	class DccController extends AppController {
		public function index (){
			$this->layout = null;
			$this->redirect('/Principal/');
			exit;
		}
	}
?>