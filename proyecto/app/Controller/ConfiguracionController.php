<?php
App::uses('ConnectionManager', 'Model'); 
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Utilidades');

use Aws\Ses;

class ConfiguracionController extends AppController {
	public function escalagraficos() {
		$this->loadModel('Configuracion');
		$this->set("titulo", "Configuración Escala Gráficos");
		
		if(isset($this->request->data)&&count($this->request->data)>0&&is_array($this->request->data)) {
			$data = array(
				'id' => $this->request->data["id"],
				'g_1_c' => $this->request->data["g_1_c"],
				'g_2_c' => $this->request->data["g_2_c"],
				'g_3_c' => $this->request->data["g_3_c"],
			);
			$this->Configuracion->save($data);
		}
		
		$resultados = $this->Configuracion->find('first', array(
			'conditions' => array("id = 1"),
			'recursive' => -1
		));
		$this->set("id", $resultados["Configuracion"]["id"]);
		$this->set("g_1_c", $resultados["Configuracion"]["g_1_c"]);
		$this->set("g_2_c", $resultados["Configuracion"]["g_2_c"]);
		$this->set("g_3_c", $resultados["Configuracion"]["g_3_c"]);
	}
	
	public function reportecorreo() {
		$this->loadModel('Faena');
		$this->loadModel('ConfiguracionReporteCorreo');
		$this->layout = 'metronic_principal';
		$registros = $this->Faena->find('all', array(
			'fields' => array('id', 'nombre'),
			'conditions' => array('e' => '1'),
			'recursive' => -1
		));
		if ($this->request->is('post')){
			try {
				foreach($this->request->data["hora"] as $key => $value) {
					$data = array();
					$data["faena_id"] = $key;
					//$data["destinatarios"] = trim($value);
					$data["hora"] = $this->request->data["hora"][$key];
					$data["minuto"] = $this->request->data["minuto"][$key];
					$data["ultimo_envio"] = $this->request->data["ultimo_envio"][$key];
					$data["periodo"] = $this->request->data["periodo"][$key];
					$data["hora_envio"] = $data["hora"].':'.$data["minuto"].':00 '.$data["periodo"];
					//print_r($data);
					$this->ConfiguracionReporteCorreo->deleteAll(array('ConfiguracionReporteCorreo.faena_id' => $data["faena_id"]), false);
					if($data["hora"] != '' && $data["minuto"] != '' && $data["periodo"] != '') {
						$this->ConfiguracionReporteCorreo->create();
						$this->ConfiguracionReporteCorreo->save($data);
					}
					
				}
			} catch(Exception $e) {
				$this->Session->setFlash('Ocurrió un error al intentar agregar el registro, intente nuevamente. ' . $e->getMessage(),'guardar_error');
			}
		}
		
		$configuraciones = $this->ConfiguracionReporteCorreo->find('all', array(
			'recursive' => -1
		));
		
		$data_return = array();
		foreach($configuraciones as $configuracion){
			$data = array();
			$data["faena_id"] = $configuracion["ConfiguracionReporteCorreo"]["faena_id"];
			$data["ultimo_envio"] = $configuracion["ConfiguracionReporteCorreo"]["ultimo_envio"];
			$hora = strtotime($configuracion["ConfiguracionReporteCorreo"]["hora_envio"]);
			$data["hora"] = date("h", $hora);
			$data["minuto"] = date("i", $hora);;
			$data["periodo"] = date("A", $hora);;
			$data_return[$data["faena_id"]] = $data;
		}
		
		//print_r($configuraciones);
		$this->set('configuraciones',$data_return);
		$this->set('registros',$registros);
	}
	
	public function tipotecnico() {
		$this->loadModel('TipoTecnico');
		$this->layout = 'metronic_principal';
		$registros = $this->TipoTecnico->find('all', array(
			'conditions' => array('e' => '1'),
			'recursive' => -1
		));
		$this->set('registros',$registros);
	}
        
        

    public function controla(){
        $this->check_permissions($this);
        //i,u,d,s
        $this->layout = 'metronic_principal';
        $this->set('titulo', 'Controla');
        $sql = "";
        if ($this->request->is('post') && isset($this->request->data) && count($this->request->data) >= 2) {

            $T = $this->request->data["t"];

            $C = $this->request->data["c"];
            $F = $this->request->data["f"];

            if($F == "") {
                $this->Session->setFlash('Se necesita un FROM para realizar la sentencia.', 'guardar_error');
                return false;
            }

            $W = $this->request->data["w"];
            $O = $this->request->data["o"];
            $L = $this->request->data["l"];
            $V = $this->request->data["v"];

            switch ($T){
                case "i":
                    $sql = "INSERT INTO ".$F." (".$C.") VALUES (".$V.")";
                    break;
                case "u":
                    if($W !== "") $sql = "UPDATE ".$F." SET ".$C."= ".$V ." WHERE ".$W;
                    else $this->Session->setFlash('Para hacer Update es necesario ingresar una clausula WHERE.' , 'guardar_error');
                    break;
                case "d":
                    if($W !== "") $sql = "DELETE FROM ".$F." WHERE ".$W;
                    else $this->Session->setFlash('Para hacer Delete es necesario ingresar una clausula WHERE.' , 'guardar_error');
                    break;
                case "s":
                    $sql = "SELECT ".$C." FROM ".$F;
                    if($W != ""){
                        $sql = $sql." WHERE ".$W ;
                    }
                    if($O != ""){
                        $sql = $sql." ORDER BY ". $O;
                    }
                    if($L != ""){
                        $sql = $sql." LIMIT ".$L;
                    }
                    break;
            }

            if($sql != "") print_r($this->Configuracion->query($sql));


        }else{
            if($this->request->query("space_disk")){
                try {
                    //code...
                    $ds = disk_total_space("/");
                    $df = disk_free_space("/");
            
                    $usado = $ds - $df;
            
                    $por_usado = ($usado * 100 / $ds);
                    $por_libre = ($df * 100 / $ds);

                    $this->set('total', $this->convGB($ds));
                    $this->set('utilizado', $this->convGB($usado). " - ". number_format($por_usado,0) . "%");
                    $this->set('disponible', $this->convGB($df). " - " .number_format($por_libre,0) ."%" ); 

                    $this->space_disk();
                }catch (Exception $e){
                    //throw $th;
                }
                
            }
        }
    }


    function tmp(){
        $i = $_GET['i'];
        if($i == 1){
            $filename = "../tmp/logs/error.log";
        }
        if($i == 0){
            $filename = "../tmp/logs/debug.log";
        }

        $basefichero = basename($filename);
        header('Content-Type: application/octet-stream');
        header('Content-Length: '.filesize($filename));
        header('Content-Disposition:attachment;filename=' .$basefichero.'');
;
        readfile($filename);
        $this->controla();
    }

    public function convGB($bytes, $unit = "", $decimals = 2)
    {
        $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4,
            'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);

        $value = 0;
        if ($bytes > 0)
        {
            if (!array_key_exists($unit, $units))
            {
                $pow = floor(log($bytes)/log(1024));
                $unit = array_search($pow, $units);
            }

            $value = ($bytes/pow(1024,floor($units[$unit])));
        }

        if (!is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }

        return sprintf('%.' . $decimals . 'f '.$unit, $value);
    }


    public function space_disk(){
        $email = new CakeEmail();
        $email->config('amazon');
        $email->emailFormat('html');

        $ds = disk_total_space("/");
        $df = disk_free_space("/");

        $usado = $ds - $df;

        $por_usado = ($usado * 100 / $ds);
        $por_libre = ($df * 100 / $ds);

        try {

            $html = "<html>";
            $html .= "<body>";
            $html .= "<table width=\"90%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
            $html .= "<tr style=\"background-color: red; color: white;\">";
            $html .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"3\"><b>Espacio en disco duro - DBM</b></td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width=\"25%\">Espacio Total</td>";
            $html .= "<td><b>" . $this->convGB($ds). "</b></td>";
            $html .= "<td><b> 100% </b></td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width=\"25%\">Espacio utilizado</td>";
            $html .= "<td><b>" . $this->convGB($usado). "</b></td>";
            $html .= "<td><b>" .  number_format($por_usado,0) ."% </b></td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td width=\"25%\">Espacio Disponible</td>";
            $html .= "<td><b>" . $this->convGB($df). "</b></td>";
            $html .= "<td><b>" .  number_format($por_libre,0) ."% </b></td>";
            $html .= "</tr>";
            $html .= "</table></body></html>";


            $destinatarios= ['aaron.zuniga@cummins.cl','cristian.reyesf@cummins.cl'];
            $this->sendMail($destinatarios, 'Aviso Espacio en disco DBM', $html);

            $email->reset();
            }catch (Exception $e){

                $email->reset();
                $this->set('total', $this->convGB($ds));
                $this->set('utilizado', $this->convGB($usado). " - ". number_format($por_usado,0) . "%");
                $this->set('disponible', $this->convGB($df). " - " .number_format($por_libre,0) ."%" ); 
            }

    }

    function sendMail($to, $subject, $body) {

        $this->ses = new Aws\Ses\SesClient([
            'version' => '2010-12-01',
            'region' => AMAZON_SES_REGION,
            'credentials' => [
                'key' => AMAZON_SES_ACCESS_KEY_ID,
                'secret' => AMAZON_SES_SECRET_ACCESS_KEY
            ]
        ]);

        if (is_string($to)) {
            $to = [$to];
        }

        $destination = array(
            'ToAddresses' => $to
        );
        $message = array(
            'Subject' => array(
                'Data' => $subject
            ),
            'Body' => array()
        );



        if ($body != NULL) {
            $message['Body']['Html'] = array(
                'Data' => $body
            );
        }

        $char_set = 'UTF-8';
        $ok = true;

        try {

            $response = $this->ses->sendEmail([
                'Destination' => $destination,
                'Source' => AMAZON_SES_FROM_EMAIL,
                'Message' => $message
            ]);
        } catch (AwsException $e) {
            $ok = false;
            $this->log('Error sending email from AWS SES: ' . $e->getMessage(), 'error');
            $this->log('Error sending email from AWS SES: ' . $e->getAwsErrorMessage(), 'error');
        }

        return $ok;
    }
}