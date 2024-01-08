<?php 

require '../../vendor/autoload.php';

use Aws\Ses;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class AWSSESComponent extends Object {

    public $ses;
    public $emailViewPath = '/Emails';
    public $emailLayouts = 'Emails';
    public $htmlMessage;
    public $from = 'from_email_address';
    public $to;
    public $attachment;

    public function initialize($controller)
    {

    }


    function startup(&$controller)
    {
       $this->controller =& $controller;

       $this->ses = new Aws\Ses\SesClient([
        'version' => '2010-12-01',
        'region' => AMAZON_SES_REGION,
        'credentials' => [
            'key' => AMAZON_SES_ACCESS_KEY_ID,
            'secret' => AMAZON_SES_SECRET_ACCESS_KEY
        ]
        ]);


    }

    public function beforeRender()
    {
    }

    public function shutdown()
    {
    }

    public function beforeRedirect()
    {
    }

    public function send($subject,$body)
    {

        if(is_string($this->to)) {
            $this->to = [$this->to];
        }
        

       $destination = array(
           'ToAddresses' => $this->to
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
        $this->log('Error sending email from AWS SES: ' .$e->getMessage(), 'error');
        $this->log('Error sending email from AWS SES: ' .$e->getAwsErrorMessage(), 'error');
       } 

       return $ok;
    }

    function sendRaw($subject, $body) {
        $sender = AMAZON_SES_FROM_EMAIL;           
        $sendername = 'Alerta DBM';

        // Replace recipient@example.com with a "To" address. If your account 
        // is still in the sandbox, this address must be verified.


        if(is_string($this->to)) {
            $this->to = [$this->to];
        }

        $recipient = $this->to;    


        $subject = $subject;

        // The full path to the file that will be attached to the email. 
        $att = $this->attachment;


        // Create a new PHPMailer object.
        $mail = new PHPMailer;

        // Add components to the email.
        $mail->setFrom($sender, $sendername);

        foreach($recipient as $email)
        {
            $mail->addAddress($email);
        }       
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(true);
        $mail->addAttachment($att);

        // Attempt to assemble the above components into a MIME message.
        if (!$mail->preSend()) {
            echo $mail->ErrorInfo;
        } else {
            // Create a new variable that contains the MIME message.
            $message = $mail->getSentMIMEMessage();
           // echo $mail->getSentMIMEMessage();
        }

        // Try to send the message.
        try {
            $result = $this->ses->sendRawEmail([
                'RawMessage' => [
                    'Data' => $message
                ]
            ]);
            // If the message was sent, show the message ID.
            return TRUE;
        } catch (SesException $error) {
            // If the message was not sent, show a message explaining what went wrong.
            $this->log('Error sending email from AWS SES: ' .$e->getAwsErrorMessage(), 'error');
            return false;
        }

    }

    function reset() {
        $this->htmlMessage = null;
        $this->from = null;
        $this->to = null;
        $this->attachment = null;
    }

}