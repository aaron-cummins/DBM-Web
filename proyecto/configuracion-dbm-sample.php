<?php
	/*
	 * Archivo de configuración DBM
	 */

	/**
	 * Configuración Azure AD
	 * Información obtenida desde repositorio de oauth2-azure
	 * https://github.com/TheNetworg/oauth2-azure
	 */
	$azure_ad_client_id = "";
	$azure_ad_client_secret = "";
	$azure_ad_redirect_url = "";
	
	/*
	 * Configuracion Base de Datos 
	 * Información obtenida desde el servicio de Amazon RDS
	 * https://console.aws.amazon.com/rds/home
	 */
	
	$data_base_host = "";
	$data_base_login = "";
	$data_base_password = "";
	$data_base_data_base = "";
	$data_base_encoding = "utf8";
	$data_base_persistent = false;
	
	/*
	 * Configuracion SMTP 
	 * Información obtenida desde el servicio de Amazon SES
	 * https://console.aws.amazon.com/ses/home
	 */
	
	$mail_host = "";
	$mail_port = "587";
	$mail_username = "";
	$mail_password = "";
	$mail_transport = "Smtp";
	$mail_tls = true;
	$mail_charset = "utf-8";
	$mail_from = ""; // Definición de correo que enviará las alertas
	$mail_debug = ""; // Email utilizado para testear envío de alertas, si se deja vacío no se enviarán correos de prueba
	
	/*
	 * Configuracion SQS 
	 * Información obtenida desde el servicio de Amazon SQS
	 * https://console.aws.amazon.com/sqs/home
	 */
	
	$amazon_sqs_region = "";
	$amazon_sqs_name = ".fifo"; // Nombre de la cola debe terminar en .fifo
	
	/* 
	 * Configuracion Credenciales de Seguridad Amazon
	 * Información obtenida desde el servicio de Amazon IAM
	 * Se deben asignar permisos de lectura y escritura al servicio SQS
	 * https://console.aws.amazon.com/iam/home
	 */
	
	$amazon_access_key_id = "";
	$amazon_secret_access_key = "";

	/*
	Credenciales AWS SES
	*/
	$amazon_ses_access_key_id = "";
	$amazon_ses_secret_access_key = "";
	$amazon_ses_region = "";
	$amazon_ses_from_email = "";
	
	/* Definición de URL pública de DBM */
	$dbm_url = ""; // Se debe ingresar url pública del DBM, con http y sin el / final
	$is_https = true; // Indicar si el sitio corre bajo https o no
	
	/**** ¡ NO modificar hacia abajo ! ****/

	define('AZURE_AD_CLIENT_ID', $azure_ad_client_id);
	define('AZURE_AD_CLIENT_SECRET', $azure_ad_client_secret);
	define('AZURE_AD_REDIRECT_URI', $azure_ad_redirect_url);
	 
	define('GOOGLE_API_CLIENTID', $google_id_cliente);
	define('GOOGLE_API_CLIENT_SECRET', $google_secreto_cliente);
	define('GOOGLE_API_JSON', $google_archivo_json);
	
	define('AMAZON_SQS_REGION', $amazon_sqs_region);
	define('AMAZON_SQS_NAME', $amazon_sqs_name);

	define('AMAZON_ACCESS_KEY_ID', $amazon_access_key_id);
	define('AMAZON_SECRET_ACCESS_KEY', $amazon_secret_access_key);

	define('AMAZON_SES_ACCESS_KEY_ID', $amazon_ses_access_key_id);
	define('AMAZON_SES_SECRET_ACCESS_KEY', $amazon_ses_secret_access_key);
	define('AMAZON_SES_REGION', $amazon_ses_region);
	define('AMAZON_SES_FROM_EMAIL', $amazon_ses_from_email);

	define('DB_HOST', $data_base_host);
	define('DB_USER', $data_base_login);
	define('DB_PASS', $data_base_password);
	define('DB_BASE', $data_base_data_base);
	define('DB_ENCODING', $data_base_encoding);
	define('DB_PERSISTENT', $data_base_persistent);

	define('MAIL_HOST', $mail_host);
	define('MAIL_PORT', $mail_port);
	define('MAIL_USER', $mail_username);
	define('MAIL_PASS', $mail_password);
	define('MAIL_TRANSPORT', $mail_transport);
	define('MAIL_TLS', $mail_tls);
	define('MAIL_CHARSET', $mail_charset);
	define('MAIL_FROM', $mail_from);
	define('MAIL_DEBUG', $mail_debug);
	
	define('DBM_URL', $dbm_url);
	define('IS_HTTPS', $is_https);