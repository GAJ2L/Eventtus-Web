<?php 
/**
* @author lucas.tomasi
*/
class SurveyService
{
	
	function getSurveys($params)
	{
		$objResponse = new stdClass;
		if( isset($params['activity_id']) )
		{
			try
			{
				TTransaction::open('eventtus');
				
				$repository = new TRepository('Survey');
				$criteria   = new TCriteria;
				$criteria->add( new TFilter('activity_id', '=', $params['activity_id']) );
				$surveys = $repository->load($criteria);

				if( $surveys )
				{
					$results = [];

					foreach( $surveys as $value )
					{
						$result = new stdClass;
						$result->id = $value->id;
						$result->description = $value->description;

						$repository = new TRepository('SurveyOption');
						$criteria   = new TCriteria;
						$criteria->add( new TFilter('survey_id', '=', $value->id) );
						$objOptions = $repository->load($criteria);	

						if( $objOptions )
						{
							$options = [];
							foreach( $objOptions as $objOption )
							{
								$opt = new Option($objOption->option_id);
								$options[] = $opt->toJson();
							}
							$result->options = $options;
						}

						$results[] = json_encode($result);

					}

					$objResponse->data    = json_encode($results);
				}

				$objResponse->status  = 'success';
				
				TTransaction::close();
			}
			catch (Exception $e)
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