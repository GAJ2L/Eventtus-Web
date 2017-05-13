<?php
/**
 * MessagesList
 * @author  lucas.tomasi
 */
class MessagesList extends TPage
{
    private $box;
    private $loaded;
    private $sock;
    
    public function __construct()
    {
        parent::__construct();
                        
        $this->box = new TVBox;
        $this->box->style = "widht:100%";
        parent::add($this->box);
    }
    
    public static function ok($param)
    {
        TScript::create('alert(2);');
    }

    public function onReload()
    {
        try
        {
            $activity_id = TSession::getValue('activity_id_filter_messages');
            
            if( $activity_id ) 
            {
                TTransaction::open('eventtus');
            
                $repository = new TRepository('Message');
                $criteria   = new TCriteria();
                $criteria->setProperties(['order'=>'dt_store']);    
                $criteria->add(new TFilter('activity_id','=',$activity_id));
                $objects = $repository->load($criteria, FALSE);
                if ($objects)
                {
                    foreach ($objects as $object)
                    {
                        $this->box->add( $this->addPanel( $object ) );
                    }
                }
                $criteria->resetProperties();
                $this->box->show();
                TTransaction::close();
            }
            
            $this->loaded = true;
        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    public function addPanel($obj)
    {
        $element = new TElement('div');
        $content = new TElement('div');
        
        $message  = new TElement('div');
        $footer   = new TElement('div');

        $element->class = "panel panel-default"; 
        $content->class = "panel-body"; 
        $footer->style  = "float:right;"; 

        $message->add($obj->content);
        $footer->add( $obj->email . ' (' . date( 'd/m/Y H:i:s', strtotime($obj->dt_store) )  . ')' );

        $content->add( $message );
        $content->add( $footer );
        $element->add($content);

        return $element;
    }

    function show()
    {
        $id = $_GET['key'];
        TSession::setValue('activity_id_filter_messages',$id);

        if( !$this->loaded )
        {
            $this->onReload();

            $configs = parse_ini_file("app/config/eventtus.ini");
            $server_socket = $configs['ws_socket'];

            TScript::create("

                    var wsUri = '{$server_socket}';

                    websocket = new WebSocket(wsUri); 

                    websocket.onopen = function(ev) {
                        console.log('connected');
                    }

                    websocket.onclose = function(ev) { 
                        console.log('Disconnected');
                    };
                    
                    websocket.onmessage = function(ev) { 
                        // console.log( ev );
                        if( ev.data ) {
                            var obj = JSON.parse(ev.data);
                            if( obj.activity == {$id} )
                                $('#adianti_div_content').append(\"<div style='widht:100%'><div style=clear:both'><div class='panel panel-default'><div class='panel-body'><div>\"+obj.content+\"</div><div style='float:right;'>\"+obj.email+\" (\"+obj.date+\")</div></div></div></div></div>\");
                        }
                    };
                    
                    websocket.onerror = function(ev) { 
                        console.log('Error '+ ev.data);
                    };
            ");
        }
    }
}