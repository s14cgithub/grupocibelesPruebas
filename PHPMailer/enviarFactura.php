<?php 


require_once('class.phpmailer.php');
require_once('class.smtp.php');	
			
$mail  = new PHPMailer();



//Le decimos al script que utilizaremos SMTP
$mail->IsSMTP();
//Activaremos la autentificación SMTP el cual se utiliza en la mayoría de casos
$mail->SMTPAuth = true;
//Especificamos la seguridad de la conexion, puede ser SSL, TLS o lo dejamos en blanco si no sabemos
//$correo->SMTPSecure = '';
//Especificamos el host del servidor SMTP
$mail->Host = "grupocibeles.es";
//Especficiamos el puerto del servidor SMTP
$mail->Port = 587;
//El usuario del servidor SMTP
$mail->Username   = "facturacion@grupocibeles.es";
//Contraseña del usuario
$mail->Password   = "3M934!@f24#*|89PeM";





//desde donde sale el correo
$mail->SetFrom("facturacion@grupocibeles.es", "Grupo Cibeles");
//correo destinatario
//$mail->AddAddress("jorgecte@gmail.com", "Clarinete Siglo XVIII");
$mail->Subject = "Factura";
$mail->IsHTML(false);	
$mail->CharSet='UTF-8';

$mail->Body = "Estimados señores,\n\nAdjuntamos las facturas correspondientes a los servicios prestados.\n\nUn cordial saludo,\n\nGRUPO CIBELES";

/*//Enviamos el correo

if(!$mail->Send()) {
  echo "Hubo un error: " . $mail->ErrorInfo;
} else {
  echo "Mensaje enviado con exito.";
}*/

?>