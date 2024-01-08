# Proyecto DBM Cummins Chile (Versión Web)

# Características

- CakePHP 2
- Amazon SDK
- Google SKD
- PHPExcel
- Bootstrap
- jQuery

# Requerimientos

- Apache 2
- PHP 5.5.9 o superior

# Servicios de Amazon

El sistema utiliza los siguientes servicios de Amazon AWS:

- [EC2](https://console.aws.amazon.com/ec2/home)
- [RDS](https://console.aws.amazon.com/rds/home)
- [SES](https://console.aws.amazon.com/ses/home)
- [SQS](https://console.aws.amazon.com/sqs/home)
- [SNS](https://console.aws.amazon.com/sns/home)
- [API Gateway](https://console.aws.amazon.com/apigateway/home)
- [Lambda](https://console.aws.amazon.com/lambda/home)

# Archivo de configuración

Para un correcto funcionamiento del sistema se debe renombrar el archivo configuracion-dbm-sample.php a configuracion-dbm.php y completar todas las variables de configuración. Solo modificar variables que se muestran a continuación:

```
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

/* Definición de URL pública de DBM */
$dbm_url = ""; // Se debe ingresar url pública del DBM, con http y sin el / final
```

# Configuración recomendada para Apache

## Librerías recomendadas

- mod_reqtimeout (Opcional)
- mod_qos (Opcional)
- mod_headers (Obligatoria)
- mod_rewrite (Obligatoria)

## Restricción de archivos y directorios

```
<Directory /var/www/html/>
	LimitRequestBody 512000
	LimitXMLRequestBody 512000
	Options -Indexes +FollowSymLinks -Includes -ExecCGI
	AllowOverride All
	Require all granted
</Directory>

<FilesMatch "^\.ht">
	Require all denied
</FilesMatch>

<DirectoryMatch "/\.git">
    Require all denied
</DirectoryMatch>

<DirectoryMatch "/log">
    Require all denied
</DirectoryMatch>

<FilesMatch "\.(json)$">
    Require all denied
</FilesMatch>

<FilesMatch "\.(sql)$">
    Require all denied
</FilesMatch>

<FilesMatch "\.(xml)$">
	Require all denied
</FilesMatch>

<FilesMatch "^(xmlrpc\.php)">
	Require all denied
</FilesMatch>

<FilesMatch "^(wp-config\.php|php\.ini|php5\.ini|install\.php|php\.info|readme\.md|README\.md|readme\.html|bb-config\.php|\.htaccess|\.htpasswd|readme\.txt|timthumb\.php|error_log|error\.log|PHP_errors\.log|\.svn)">
	Require all denied
</FilesMatch>
```

## Opciones del Servidor

```
TraceEnable Off
ServerSignature Off
ServerTokens Prod
FileETag None
Timeout 120
```

## Custom headers

```
<IfModule mod_reqtimeout.c>
	RequestReadTimeout header=20-40,MinRate=500 body=20,MinRate=500
</IfModule>

<IfModule mod_qos.c>
	QS_ClientEntries 10000
	QS_SrvMaxConnPerIP 50
	MaxClients 256
	QS_SrvMaxConnClose 180
	QS_SrvMinDataRate 150 1200
</IfModule>

<IfModule mod_headers.c>
	Header set X-Frame-Options SAMEORIGIN
	Header set X-XSS-Protection "1; mode=block"
	Header set X-Content-Type-Options nosniff
	Header set Content-Security-Policy "default-src 'self'; script-src 'self' *.googleapis.com *.googletagmanager.com *.gstatic.com *.googleusercontent.com; connect-src 'self'; img-src 'self' *.gstatic.com *.googleusercontent.com; style-src 'self';"
	Header unset X-Powered-By
	# Solo si está configurado https
	Header set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
</IfModule>

<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteCond %{THE_REQUEST} !HTTP/1.1$
	RewriteRule .* - [F]
</IfModule>
```

# Cron

Para un correcto funcionamiento de las alertas y generación de reporte base se deben definir las siguientes tareas programadas:

```
* * * * * cd /var/www/html/app/ && Console/cake aws message -app /var/www/html/app/
* * * * * php /var/www/html/vendor/amazon/sqs/CronIntervenciones.php
*/2 * * * * cd /var/www/html/app/ && Console/cake reporte generar_base -app /var/www/html/app/
* * * * * cd /var/www/html/app/ && Console/cake notificacion -app /var/www/html/app/
```

# Links de interés

- [Módulo QOS](http://mod-qos.sourceforge.net/)
- [Módulo Reqtimeout](https://httpd.apache.org/docs/trunk/mod/mod_reqtimeout.html)
- [Documentación Amazon AWS](https://aws.amazon.com/es/documentation/)