<?php
App::uses('ConnectionManager', 'Model'); 

class PlanificarController extends AppController {
	public function index() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
	}
}