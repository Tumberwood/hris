<?php

	//Mail Server settings
	$mail->SMTPDebug = 4;                                                   // Enable verbose debug output
	$mail->isSMTP();                                                        // Send using SMTP
	$mail->Host       = 'mail.solusi-indonesia.com';                        // Set the SMTP server to send through
	$mail->SMTPAuth   = true;                                               // Enable SMTP authentication
	$mail->Username   = 'support@solusi-indonesia.com';                     // SMTP username
	$mail->Password   = ';ad~T{GXTzxC';                                     // SMTP password
	$mail->SMTPSecure = 'ssl';                                              // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	$mail->Port       = 465;                                                // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
?>