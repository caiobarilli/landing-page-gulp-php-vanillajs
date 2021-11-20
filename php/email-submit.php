<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

require_once "save-leads.php";

// Email Submit
if (!empty($_POST["email"])) {

	$mail = new PHPMailer(true);

	$mail->IsSMTP();
	$mail->SMTPDebug = 0;
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';

	// Produção
	// $mail->Host = 'smtp.gmail.com';
	// $mail->Port = 587;
	// $mail->Username = 'dev@gmail.com';
	// $mail->Password = '';    

	// Desenvolvimento
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->Username = 'dev@gmail.com';
	$mail->Password = '';       

	$values = $_POST;

	$mail_body = '<table style="border-collapse: collapse; border: 1px solid;">';

	$mail_body .= '<tr><td style="padding:10px; font-size: 16px;"> Email:' . 
				  '</td>'.'<td style="font-size: 16px; padding: 10px;">'.$email.'</td></tr>';
				  
	$mail_body .= '<tr><td style="padding:10px; font-size: 16px;"> Data:' . 
				  '</td>'.'<td style="font-size: 16px; padding: 10px;">'.$dia.'</td></tr>';
	
	$mail_body .= '<tr><td style="padding:10px; font-size: 16px;"> Hora:' . 
				  '</td>'.'<td style="font-size: 16px; padding: 10px;">'.$hora.'</td></tr>';

	$mail_body .= '</table>';
	
	$subject = "Novo E-mail cadastrado [newsletter]";

	try {

		$mail->CharSet = 'UTF-8';
		$mail->Subject = $subject; 
		$mail->isHTML(true);
		$mail->Body = $mail_body;
		$mail->send();

	} catch (phpmailerException $e) {
		echo $e->errorMessage();
	} catch (Exception $e) {
		echo $e->getMessage();
	}

} else {
	exit('No post object');
}