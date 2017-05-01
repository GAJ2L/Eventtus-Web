<?php 
/**
* @author lucas.tomasi
*/
class EventService
{
	
	public static function getEvent($params)
	{
		TTransaction::open('eventtus');
		
		$repository = new TRepository('Inscription');
		$criteria   = new TCriteria;
		$criteria->add( new TFilter('email', '=', $params['email']) );
		$criteria->add( new TFilter('hash',  '=', $params['hash']) );
		$result = $repository->load($criteria, FALSE);

		if( $result )
		{
			$event = new Event($result[0]->event_id);
			$event = json_decode($event->toJson());
			$event->activities = self::getActivities($event->id);
			
			echo( json_encode( $event ) );	
		}

		TTransaction::close();
	}


	private static function getActivities( $event_id )
	{
		$repository = new TRepository('Activity');
		$criteria   = new TCriteria;
		$criteria->add( new TFilter('event_id', '=', $event_id) );
		
		$objects = $repository->load($criteria, FALSE);
		if( $objects )
		{
			$_objects = [];
			foreach( $objects as $key => $obj ) 
			{
				$obj = json_decode($obj->toJson());
				$obj->attachments = self::getAttachments($obj->id);
				$_objects[] = $obj;
			}
		}

		return $_objects;
	}

	private static function getAttachments( $activity_id )
	{
		$repository = new TRepository('Attachment');
		$criteria   = new TCriteria;
		$criteria->add( new TFilter('activity_id', '=', $activity_id) );
		
		$objects = $repository->load($criteria, FALSE);
		
		if( $objects )
		{
			$_objects = [];
			foreach ($objects as $key => $obj) 
			{
				$_objects[] = json_decode($obj->toJson());

			}
		}

		return $_objects;
	}
}
?>