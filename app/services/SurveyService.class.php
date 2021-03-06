<?php

/**
 * @author lucas.tomasi
 */
class SurveyService
{

	function store( $params )
	{
			$objResponse = new stdClass;

			if( isset( $params[ 'email' ] ) )
			{
					try
					{
							TTransaction::open('eventtus');

							$repository = new TRepository('SurveyAnswer');

							$email 		= $params[ 'email' ];
							$answers  = json_decode( $params[ 'answers' ] );

							if ( $answers )
							{
									foreach ( $answers as $answer )
									{
											$criteria = new TCriteria;
											$criteria->add( new TFilter('survey_id', '=', $answer->question ) );
											$criteria->add( new TFilter('email', '=', $email) );

											$repository->delete($criteria);

											$result = new SurveyAnswer();
											$result->survey_options_id = $answer->option;
											$result->email = $email;
											$result->survey_id = $answer->question;

											$result->store();
									}
							}

							$objResponse->status  = 'success';
							$objResponse->message = 'ok';

							TTransaction::close();
					}

					catch ( Exception $e )
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

				$activity = new Activity($params['activity_id']);

				$result = new stdClass;
				$result->id 		 = $activity->id;
				$result->description = $activity->name;
				$result->questions   = [];

				if( $surveys )
				{
					foreach( $surveys as $value )
					{
						$option = new stdClass;
						$option->id          = $value->id;
						$option->description = $value->description;
						$option->options     = [];

						$repository = new TRepository('SurveyOption');
						$criteria   = new TCriteria;
						$criteria->add( new TFilter('survey_id', '=', $value->id) );
						$objOptions = $repository->load($criteria);

						if( $objOptions )
						{
							foreach( $objOptions as $objOption )
							{
								$opt = new Option($objOption->option_id);

								$op  = new stdClass;
								$op->id          = $opt->id;
								$op->description = $opt->description;

								$option->options[] = $op;
							}
						}

						$result->questions[] = $option;

					}

					$objResponse->data = $result;
				}

				$objResponse->status = 'success';

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
