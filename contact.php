<?php

require 'phpmailer/PHPMailerAutoload.php';

$secrets = require '.secrets';
$mail_settings = $secrets['smtp_server'];

$mail = new PHPMailer;

// $mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = $mail_settings['host'];                 // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = $mail_settings['username'];         // SMTP username
$mail->Password = $mail_settings['password'];         // SMTP password
$mail->SMTPSecure = 'PHPMailer';    // Enable TLS encryption, `ssl` also accepted
$mail->Port = $mail_settings['port'];                 // TCP port to connect to

$mail->isHTML(true);

// message that will be displayed when everything is OK :)
$okMessage = 'Contact form successfully submitted. Thank you, we will get back to you soon!';

// If something goes wrong, we will display this message.
$errorMessage = 'There was an error while submitting the form. Please try again later';

$name = $_POST["name"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$message = $_POST["message"];

$mail->setFrom($mail_settings['fromEmail'], $mail_settings['fromPerson']);
$mail->addAddress($mail_settings['fromEmail']);     // Add a recipient
$mail->addReplyTo($email);

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Cooperative IT Webform';
$mail->Body    = '<p><b>Contact Form</b></p>Name: ' . $name . '</br> phone: ' .$phone . '</br> email: ' . $email . '</br>Message: ' . $message;

if(!$mail->send()) {
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);

} else {
    $responseArray = array('type' => 'success', 'message' => $okMessage);
}

// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);

    header('Content-Type: application/json');

    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['message'];
}
