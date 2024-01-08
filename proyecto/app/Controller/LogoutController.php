<?php
App::uses('ConnectionManager', 'Model');

class LogoutController extends AppController {
	public function index() {
		$this->layout = null;
		$this->Session->destroy();
		$this->redirect('/Login');
	}
}
?>