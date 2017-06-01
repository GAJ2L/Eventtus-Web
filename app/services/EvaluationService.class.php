
<?php 
/**
* @author lucas.tomasi
*/
class EvaluationService
{
	public static function store($params)
	{
		$objResponse = new stdClass;

		try 
		{
			TTransaction::open('eventtus');

			$evaluation = new Evaluation();
			$evaluation->email        = $params['email'];
            $evaluation->ref_activity = $params['activity'];
            $evaluation->comment      = $params['comment'];
            $evaluation->stars        = $params['star'];
            $evaluation->dt_store     = date('Y-m-d H:i:s' , strtotime($params['dt_store']) );
			$evaluation->store();

			$objResponse->status = 'success';
			TTransaction::close();
		}
		catch (Exception $e) 
		{
			$objResponse->status = 'error';
			$objResponse->message = $e->getMessage();
			TTransaction::rollback();
		}

		finally
		{
			echo json_encode($objResponse);
		}
	}

	public function getMedia($params)
	{
		$objResponse = new stdClass;
		if( isset( $params['activity_id'] ) )
		{
			try
			{
				TTransaction::open('eventtus');
				
				$conn = TTransaction::get();
				
				$sth = $conn->prepare('SELECT count(id) as count, avg(stars) as avg from evaluations WHERE ref_activity = ?'); 
				$sth->execute([$params['activity_id']]);
            	$results = $sth->fetchAll();
				
				$result = new stdClass;
				$result->count = $results[0]['count'];
				$result->avg   = $results[0]['avg'];

				$objResponse->data    = $result;
				$objResponse->status  = 'success';
				
				TTransaction::close();
			}
			catch(Exception $e)
			{
				$objResponse->status  = 'error';
				$objResponse->message = $e->getMessage();
				TTransaction::rollback();
			}

			finally
			{
				echo json_encode( $objResponse );
			}
		}
		else
		{
			$objResponse->status  = 'error';
			$objResponse->message = 'faltam parametros';
			echo json_encode( $objResponse );
		}
	}
}
?>	