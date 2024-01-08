<?php

require '/var/www/html/vendor/amazon/sqs/aws-autoloader.php';

class AwsShell extends AppShell {
    public $uses = array('AwsMessage');
	
    public function message() {
		$minuto = intval(date("i"));
		if ($minuto % 2 == 0) {
			exit;	
		}
		$log_file = "/var/www/html/log/cron_message_".date("Y-m-d_A").".log";
		$file = fopen($log_file, "a");
		fwrite($file, date("Y-m-d H:i:s") . ": Inicio de revisión de cola de SQS.\n");

		$sharedConfig = [
			'region'  => AMAZON_SQS_REGION,
			'version' => 'latest',
			'credentials' => [
				'key'    => AMAZON_ACCESS_KEY_ID,
				'secret' => AMAZON_SECRET_ACCESS_KEY
			],
		];

		try {
			$sqs_client = new Aws\Sqs\SqsClient($sharedConfig);
			$result = $sqs_client->getQueueUrl(array('QueueName' => AMAZON_SQS_NAME));
			$queue_url = $result->get('QueueUrl');
			$dataSource = $this->AwsMessage->getDataSource();

			$result = $sqs_client->receiveMessage(array(
				'QueueUrl' => $queue_url,
				'MaxNumberOfMessages' => 10,
				'MessageAttributeNames' => ['Folio', 'Faena','Flota','Unidad','TipoIntervencion','Hijo','Padre','Json','FechaGuardado','Correlativo']
			));
			
			if (!isset($result['Messages']) || $result['Messages'] == null || !is_array($result['Messages'])) {
				//$this->out('No hay mensajes en SQS para registrar');
				//fwrite($file, date("Y-m-d H:i:s") . ": No hay mensajes para procesar.\n");
			}else{
				$this->out('Hay '.count($result['Messages']).' mensajes en SQS para registrar');
				fwrite($file, date("Y-m-d H:i:s") . ": Hay ".(count($result['Messages']))." mensajes para procesar.\n");
				foreach($result['Messages'] as $key => $message) {
					$data = array();
					$data["fecha_guardado"] = date("Y-m-d H:i:s", strtotime($message["MessageAttributes"]["FechaGuardado"]["StringValue"]));
					$data["folio"] = $message["MessageAttributes"]["Folio"]["StringValue"];
					$data["faena_id"] = $message["MessageAttributes"]["Faena"]["StringValue"];
					$data["flota_id"] = $message["MessageAttributes"]["Flota"]["StringValue"];
					$data["unidad_id"] = $message["MessageAttributes"]["Unidad"]["StringValue"];
					$data["tipo_intervencion"] = $message["MessageAttributes"]["TipoIntervencion"]["StringValue"];
					$data["hijo"] = $message["MessageAttributes"]["Hijo"]["StringValue"];
					$data["padre"] = $message["MessageAttributes"]["Padre"]["StringValue"];
					$data["json"] = $message["MessageAttributes"]["Json"]["StringValue"];
					$data["correlativo"] = $message["MessageAttributes"]["Correlativo"]["StringValue"];
					$data["message_id"] = $message["MessageId"];
					$data["procesado"] = "0";
					$dataSource->begin();
					try {
						$this->AwsMessage->create();
						if($this->AwsMessage->save($data)){
							$dataSource->commit();
							fwrite($file, date("Y-m-d H:i:s") . ": ".($message["MessageAttributes"]["Folio"]["StringValue"]).". Registrada correctamente. Commit\n");
							$result_delete = $sqs_client->deleteMessage([
								'QueueUrl' => $queue_url,
								'ReceiptHandle' => $message["ReceiptHandle"]
							]);
							fwrite($file, date("Y-m-d H:i:s") . ": ".($message["MessageAttributes"]["Folio"]["StringValue"]).". Mensaje ".($message["MessageId"])." eliminado de la cola SQS.\n");
						} else {
							$dataSource->rollback();
							fwrite($file, date("Y-m-d H:i:s") . ": ".($message["MessageAttributes"]["Folio"]["StringValue"]).". No se puso registrar. Rollback.\n");
						}
					} catch (Exception $e) {
						$dataSource->rollback();
						fwrite($file, date("Y-m-d H:i:s") . ": Ocurrió una excepción ".($e->getMessage()).".\n");
					}						
				}
			}
		} catch (AwsException $e) {
			fwrite($file, date("Y-m-d H:i:s") . ": Ocurrió una excepción ".($e->getMessage()).".\n");
		}		
		fwrite($file, date("Y-m-d H:i:s") . ": Término de revisión de cola de SQS.\n");
		fclose($file);
	}
}

?>