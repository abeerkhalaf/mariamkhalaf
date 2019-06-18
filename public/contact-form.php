<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include('./config.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    function checkCaptcha()
    {
        if (isset($_POST['g-recaptcha-response'])) {
            $captcha = $_POST['g-recaptcha-response'];
            $postdata = http_build_query(
                array(
                    'secret' => SECRET_KEY,
                    'response' => $captcha,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                )
            );
            $options = array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata
                )
            );
            $context = stream_context_create($options);
            $result = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context));
            return $result->success;
        } else {
            return false;
        }
    }

    if (checkCaptcha()) {
        $mail = new PHPMailer(true);  // Passing `true` enables exceptions
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $buying_or_selling = $_POST['buying_or_selling'];
        $message = $_POST['message'];
        $message_body = <<<EOT
New contact from: $name
Their email is: $email
Their full message is:
$message
EOT;
        try {
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = EMAIL_HOST;                  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = EMAIL_USERNAME;          // SMTP username
            $mail->Password = EMAIL_PASSWORD;          // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = EMAIL_PORT;                  // TCP port to connect to
            $mail->setFrom($email, $name);
            $mail->addAddress(EMAIL_FORWARD_ADDRESS, EMAIL_FORWARD_NAME);     // Add a recipient
            $mail->isHTML(false);                                  // Set email format to HTML
            $mail->Subject = 'Website Contact Form';
            $mail->Body = $message_body;
            $mail->send();
            echo "Thank you for contacting us, we'll reach out to you shortly!";
        } catch (Exception $e) {
        }
    } else {
        echo "Invalid captcha please try resubmitting the form!";
    }
} else {
    http_response_code(404);
    die();
}