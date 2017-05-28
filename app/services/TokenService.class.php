<?php 
/**
* @author lucas.tomasi
*/
class TokenService
{
	
	function save($param)
	{
		$result = new stdClass;

		if( isset($param['email']) AND isset($param['token']) )
		{
			try
			{
				TTransaction::open('eventtus');
				$token = new Token();
				$token->email = $param['email'];
				$token->token = $param['token'];
				$token->store();
				TTransaction::close();
			}
			catch( Exception $e )
			{
				TTransaction::rollback();
				$result->status  = 'error';
				$result->message = $e->getMessage();
				echo json_encode($result);
			}
		}
		else
		{
			$result->status  = 'error';
			$result->message = 'faltam parametros';
			echo json_encode($result);
		}
	}
}
 ?>