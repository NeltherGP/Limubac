<?php  
function enviaCorreo($asunto,$destino,$body,$controlador){
		$mailer = $controlador->get('mailer');
		$message = $mailer->createMessage()
        ->setSubject($asunto)
        ->setFrom('sistemalimubac@gmail.com')
		->setBCC('limubac@gmail.com')
        ->setTo($destino)
        ->setBody($body,'text/html')
        
    ;
    $mailer->send($message);	
	}
?>