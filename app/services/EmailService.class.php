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
			$result = new stdClass;
			
			if( file_exists('app/config/mail.ini') )
			{
				if( !isset($param['body'])    OR !isset($param['subject'])   OR 
					!isset($param['to_mail']) OR !isset($param['from_mail']) OR 
					!isset($param['to_name']) OR !isset($param['from_name']) )
				{
					$result->status  = 'error';
					$result->message = 'Faltam parametros!!';
				}
				else
				{
					$body      = $param['body'];
					$subject   = $param['subject'];
					$to_mail   = $param['to_mail'];
					$from_mail = $param['from_mail'];
					$to_name   = $param['to_name'];
					$from_name = $param['from_name'];

					$config = parse_ini_file('app/config/mail.ini');

					$mail = new TMail();
					$mail->addAddress($to_mail,$to_name);
					$mail->setHtmlBody($body);
					$mail->setSubject($subject);
					
					$mail->setFrom($config['mail'],$from_name."<{$from_mail}>");

					$mail->SetSmtpHost($config['host'],$config['port']);
					$mail->SetSmtpUser($config['mail'],$config['pass']);
					$mail->SetUseSmtp();
					
					if( $mail->send() )
					{
						$result->status  = 'success';
						$result->message = 'E-mail enviado com sucesso!';
					}
					else
					{
						$result->status  = 'error';
						$result->message = 'Houve um erro ao enviar email!!' . "\n" . $mail->getPHPMailer()->ErrorInfo;
					}
				}
			}
			else
			{
				$result->status  = 'error';
				$result->message = 'Arquivo .ini não encontrado!!';
			}

			echo json_encode($result);
		}
		catch( Exception $e )
		{
			$result = new stdClass;
			$result->status  = 'error';
			$result->message = 'Houve um erro ao enviar email!!';
			echo json_encode($result);
		}
	}
}