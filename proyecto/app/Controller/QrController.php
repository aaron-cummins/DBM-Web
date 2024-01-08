<?php
App::uses('ConnectionManager', 'Model');
require_once('../Vendor/phpqrcode/qrlib.php');
/*
	Esta clase define la funcionalidad para cambiar de perfil y de faena
*/
class QrController extends AppController {
	public function ck($id = "", $folio = "") {
		$this->layout=null;
		if($id==sha1($folio)){
			$this->loadModel('Planificacion');
			$planificacion = $this->Planificacion->find('first', array(
				'fields' => array('id'),
				'conditions' => array("md5(CAST(id AS varchar)) = '$folio'"),
				'recursive' => -1
			));
			if(isset($planificacion["Planificacion"])){
				header("Location: http://cummins.sisrai.cl/bitacora_web/pdf.php?f=".$planificacion["Planificacion"]["id"]);
				exit;
			}
		}
		die("El código QR escaneado no contiene información asociada al sistema.");
	}

	public function generate() {
		$this->autoRender = false;
		$this->response->type('png');
		return QRcode::png($this->request->query['code'], false, QR_ECLEVEL_H, 4);
	}

	public function generate_internal($payload) {
		$filepath = '/tmp/qr_'.uniqid().'_'.date_timestamp_get(date_create()).'.png';
		QRcode::png($payload, $filepath, QR_ECLEVEL_H, 4);
		return $filepath;
	}
}
?>