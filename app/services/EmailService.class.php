<?php 
/**
* @author lucas.tomasi
*/
class EmailService
{
	public static function send($param)
	{
		try
		{
			$body     = $param['body'];
			$subject  = $param['subject'];
			$to_mail  = $param['to_mail'];
			$to_name  = $param['to_name'];
			
			$mail = new TMail();
			$mail->addAddress($to_mail,$to_name);
			$mail->setHtmlBody($body);
			$mail->setSubject($subject);
			$mail->setFrom('contato.eventtus@gmail.com','Eventtus');
			$mail->SetSmtpHost('smtp.gmail.com',465);
			$mail->SetSmtpUser('contato.eventtus@gmail.com','GAJ2Leventtus');
			$mail->SetUseSmtp();
			
			$result = new stdClass;
			
			if( $mail->send() )
			{
				$result->status  = 'success';
				$result->message = 'E-mail enviado com sucesso!';
			}
			else
			{
				$result->status  = 'error';
				$result->message = 'Houve um erro ao enviar email!!';
			}

			return json_encode($result);
		}
		catch( Exception $e )
		{
			$result = new stdClass;
			$result->status  = 'error';
			$result->message = 'Houve um erro ao enviar email!!';
			return json_encode($result);
		}
	}
}