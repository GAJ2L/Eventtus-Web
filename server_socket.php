<?php
error_reporting("~E_NOTICE ~E_DEPRECATED");
set_time_limit (0);

require_once 'init.php'; 
$configs = parse_ini_file("app/config/eventtus.ini");

$address = $configs['ip_socket'];
$port    = $configs['pt_socket'];
$ws      = $configs['ws_socket'];
// CRIA O SOCKET 
if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
     
    die("Couldn't create socket: [$errorcode] $errormsg \n");
}
 
 // LIGA o SOCKET NO ENDEREÇO
if( !socket_bind($sock, $address , $port) )
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
     
    die("Could not bind socket : [$errorcode] $errormsg \n");
}

// COLOCA PARA ESCUTAR NOVAS CONEXÕES
if(!socket_listen ($sock , 10))
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
     
    die("Could not listen on socket : [$errorcode] $errormsg \n");
}
  
$client_socks = array();
 
while (true) 
{
    $read = array();
    // o server é um socket também
    $read[0] = $sock;
     
    $read_clients = true;
    $i = 0;

    while ($read_clients)
    {
        if($client_socks[$i] != null)
        {
            $read[$i+1] = $client_socks[$i];
        }
    	else
    	{
    	   $read_clients = false;	  
    	}
	    $i++;
    }
     
    if(socket_select($read , $write , $except , null) === false)
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
     
        die("Could not listen on socket : [$errorcode] $errormsg \n");
    }
     
    if (in_array($sock, $read)) 
    {
        for ($i = 0; $i < count($client_socks) + 1 ; $i++)
        {
            if ($client_socks[$i] == null) 
            {
                // Novo cliente conectado
                $client_socks[$i] = socket_accept($sock); 

                if(socket_getpeername($client_socks[$i], $address, $port))
                {
                    echo "Client $address : $port is now connected to us. \n";
                    $header = socket_read($client_socks[$i], 2048);
                    perform_handshaking($header, $client_socks[$i], $host, $port); //Executa handshake para o Websocket
                }

                break;
            }
        }
    }

    // verifica se os clientes estão mandando conteudos
    for ($i = 0; $i < count($client_socks) + 1 ; $i++)
    {
        if (in_array($client_socks[$i] , $read))
        {
            $input   = socket_read($client_socks[$i] , 2048);
            $txt     = substr(trim($input),1);
            $message = json_decode( $txt );
        
            if($txt)
            {
                var_dump($txt."\n");
                // envia msg para todos os clientes
                foreach ($client_socks as $key => $value) 
                {
                    socket_write( $value , mask($txt) );
                }
            }
            
            // caso a mensagem for para gravação em banco
            if( $message )
            {
                try 
                {    
                    // salva no banco
                    TTransaction::open('eventtus');
                    $objMessage = new Message();
                    $objMessage->email       = $message->email;
                    $objMessage->dt_store    = $message->date;
                    $objMessage->content     = $message->content;
                    $objMessage->activity_id = $message->activity;

                    $objMessage->store($objMessage);                        
                    TTransaction::close();
                } 
                catch (Exception $e) 
                {
                    TTransaction::rollback();
                    echo $e->getMessage()."\n";   
                }
                
            }
        }
    }
}

// Coloca no formato para WebSocket
function mask($text)
{
    $b1 = 0x80 | (0x1 & 0x0f);
    $length = strlen($text);
    
    if($length <= 125)
        $header = pack('CC', $b1, $length);
    elseif($length > 125 && $length < 65536)
        $header = pack('CCn', $b1, 126, $length);
    elseif($length >= 65536)
        $header = pack('CCNN', $b1, 127, $length);
    return $header.$text;
}

// Executa o handshaking
function perform_handshaking($receved_header,$client_conn, $host, $port)
{
    $headers = array();
    $lines = preg_split("/\r\n/", $receved_header);
    foreach($lines as $line)
    {
        $line = chop($line);
        if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
        {
            $headers[$matches[1]] = $matches[2];
        }
    }
    $secKey = $headers['Sec-WebSocket-Key'];
    $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
    //hand shaking header
    $upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
    "Upgrade: websocket\r\n" .
    "Connection: Upgrade\r\n" .
    "WebSocket-Origin: $host\r\n" .
    "WebSocket-Location: $ws\r\n".
    "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
    socket_write($client_conn,$upgrade,strlen($upgrade));
}