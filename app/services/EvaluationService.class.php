
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
}
?>	