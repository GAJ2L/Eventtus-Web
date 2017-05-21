<?php 
/**
* @author lucas.tomasi
*/
class Notification
{
	private $key;
	private $endpoint;
	private $fields;

	public static $NOTIFICATION_ALL      = 1;
	public static $NOTIFICATION_USER     = 2;
	public static $NOTIFICATION_EVENT    = 3;
	public static $NOTIFICATION_ACTIVITY = 4;

	public static $EVENT_UPDATE = 1;

	function __construct()
	{
		$config = parse_ini_file('app/config/notification.ini');

		$this->fields    = new stdClass;
		
		$this->key	     = $config['key'];
		$this->endpoint  = $config['endpoint'];

		$this->fields->to  			= $config['to'];
		$this->fields->notification = new stdClass;
		$this->fields->data         = new stdClass;
		
		$this->fields->notification->title = "Eventtus";
	}

	public function setTitle( $title )
	{
		$this->fields->notification->title = $title;
	}

	public function setMessage( $content )
	{
		$this->fields->notification->body = $content;
	}

	private function  getHeaders()
	{
		return [
			'Authorization: key='.$this->key,
			'Content-Type: application/json'
		];
	}

	/**
	 * $type = ALL, EVENT, ACTIVITY, USER
	 *
	 */
	public function setAction( $type , $value = null, $method = null )
	{
		$this->fields->data->type   = $type;
		$this->fields->data->method = $method;
		$this->fields->data->value  = $value;
	}

	public function send()
	{
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL,            $this->endpoint );
		curl_setopt( $ch, CURLOPT_HTTPHEADER,     $this->getHeaders() );
		curl_setopt( $ch, CURLOPT_POST,           true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_POSTFIELDS,     json_encode($this->fields) );

		$result = curl_exec($ch);

		return $result;
	}

}
?>