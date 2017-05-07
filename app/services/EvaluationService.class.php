
<?php 
/**
* @author lucas.tomasi
*/
class EvaluationService
{
	public static function store($params)
	{
		$objResponse = new stdClass;

			file_put_contents("/tmp/error0.txt","jlsjflas");	
		try 
		{
			TTransaction::open('eventtus');
			

			$evaluation = new Evaluation();
			file_put_contents("/tmp/error0.txt","jlsjflassssssssssssssss");	
			$evaluation->email        = $params['email'];
            $evaluation->ref_activity = $params['activity'];
            $evaluation->comment      = $params['comment'];
            $evaluation->stars        = $params['star'];
            $evaluation->dt_store     = $params['dt_store'];
			file_put_contents("/tmp/error1.txt",serialize($evaluation));	
			$evaluation->store();

			$objResponse->status = 'success';
			TTransaction::close();
		}
		catch (Exception $e) 
		{
			file_put_contents("/tmp/error2.txt",$e->getMessage());
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