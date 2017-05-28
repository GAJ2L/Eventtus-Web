<?php 
/**
* @author lucas.tomasi
*/
class Notification
{
	private $key;
	private $endpoint;
	private $fields;

	function __construct()
	{
		$config = parse_ini_file('app/config/notification.ini');

		$this->fields    = new stdClass;
		
		$this->key	     = $config['key'];
		$this->endpoint  = $config['endpoint'];

		$this->fields->registration_ids = [];
		$this->fields->notification     = new stdClass;		
		$this->fields->notification->title = "Eventtus";
		$this->fields->notification->icon  = "eventtus";
		$this->fields->notification->sound = "default";
		$this->fields->notification->color = "#2269a7";
	}

	public function setTitle( $title )
	{
		$this->fields->notification->title = $title;
	}

	public function setMessage( $content )
	{
		$this->fields->notification->body = $content;
	}

	public function addToken($token)
	{
		$this->fields->registration_ids[] = $token;
	}

	public function setTokens($tokens)
	{
		$this->fields->registration_ids = $tokens;
	}

	private function  getHeaders()
	{
		return [
			'Authorization: key='.$this->key,
			'Content-Type: application/json'
		];
	}

	public function setData( $data )
	{
		$this->fields->data = $data;
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