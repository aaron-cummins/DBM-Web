<?php
App::uses('CakeEmail', 'Network/Email');
App::import('Vendor', 'Classes/PHPExcel');

//require '../../vendor/autoload.php';

use Aws\Ses;

class SpaceDiskShell extends AppShell {

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


        $destinatarios= ['aaron.zuniga@cummins.cl','cristian.reyesf@cummins.cl', 'clc9mg00310/dbm.support@globalkomatsu.onmicrosoft.com'];
        $this->sendMail($destinatarios, 'Aviso Espacio en disco DBM', $html);

        $email->reset();

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

