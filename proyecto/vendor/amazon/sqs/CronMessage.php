<?php
	error_reporting(0);
	ini_set('memory_limit','53687091');
	$minuto = intval(date("i"));
	if ($minuto % 2 == 0) {
		exit;	
	}
	$log_file = "/var/www/html/vendor/amazon/sqs/logs/cron_message_".date("Y-m-d_A").".log";
	$file = fopen($log_file, "a");
	fwrite($file, date("Y-m-d H:i:s") . ": Inicio de revisión de cola de SQS.\n");
	include("/var/www/html/configuracion-dbm.php");
	require 'aws-autoloader.php';	
	$dbconn = pg_connect("host=".DB_HOST." dbname=".DB_BASE." user=".DB_USER." password=".DB_PASS."") or die('No se ha podido conectar: ' . pg_last_error()); 

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

		$result = $sqs_client->receiveMessage(array(
			'QueueUrl' => $queue_url,
			'MaxNumberOfMessages' => 10,
			'MessageAttributeNames' => ['Folio', 'Faena','Flota','Unidad','TipoIntervencion','Hijo','Padre','Json','FechaGuardado','Correlativo']
		));
		
		if (!isset($result['Messages']) || $result['Messages'] == null || !is_array($result['Messages'])) {
			fwrite($file, date("Y-m-d H:i:s") . ": No hay mensajes para procesar.\n");
		}else{
			fwrite($file, date("Y-m-d H:i:s") . ": Hay ".(count($result['Messages']))." mensajes para procesar.\n");
			foreach($result['Messages'] as $key => $message) {
				$fecha_guardado = date("Y-m-d H:i:s", strtotime($message["MessageAttributes"]["FechaGuardado"]["StringValue"]));
				$query = "INSERT INTO aws_sqs_message (folio, faena, flota, unidad, tipo_intervencion, hijo, padre, json, fecha_guardado, correlativo, message_id, procesado) VALUES ('{$message["MessageAttributes"]["Folio"]["StringValue"]}','{$message["MessageAttributes"]["Faena"]["StringValue"]}','{$message["MessageAttributes"]["Flota"]["StringValue"]}','{$message["MessageAttributes"]["Unidad"]["StringValue"]}','{$message["MessageAttributes"]["TipoIntervencion"]["StringValue"]}','{$message["MessageAttributes"]["Hijo"]["StringValue"]}','{$message["MessageAttributes"]["Padre"]["StringValue"]}','{$message["MessageAttributes"]["Json"]["StringValue"]}','$fecha_guardado','{$message["MessageAttributes"]["Correlativo"]["StringValue"]}','{$message["MessageId"]}', '0')";
				$query = str_replace("''", "NULL", $query);
				
				pg_query("BEGIN");
				$result_query = pg_query($query);
				if ($result_query) {
					pg_query("COMMIT");
					fwrite($file, date("Y-m-d H:i:s") . ": ".($message["MessageAttributes"]["Folio"]["StringValue"]).". Registrada correctamente. Commit\n");
					$result_delete = $sqs_client->deleteMessage([
						'QueueUrl' => $queue_url,
						'ReceiptHandle' => $message["ReceiptHandle"]
					]);
					fwrite($file, date("Y-m-d H:i:s") . ": ".($message["MessageAttributes"]["Folio"]["StringValue"]).". Mensaje ".($message["MessageId"])." eliminado de la cola SQS.\n");
				} else {
					pg_query("ROLLBACK");
					fwrite($file, date("Y-m-d H:i:s") . ": ".($message["MessageAttributes"]["Folio"]["StringValue"]).". No se puso registrar. Rollback.\n");
					fwrite($file, date("Y-m-d H:i:s") . ": ".($message["MessageAttributes"]["Folio"]["StringValue"]).". Query con error $query.\n");
				}	
			}
		}
	} catch (AwsException $e) {
		fwrite($file, date("Y-m-d H:i:s") . ": Ocurrió una excepción ".($e->getMessage()).".\n");
	}
	
	pg_close($dbconn);
	fwrite($file, date("Y-m-d H:i:s") . ": Término de revisión de cola de SQS.\n");
	fclose($file);
?>
