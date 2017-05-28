<?php 

/**
* @author lucas.tomasi
*/
class SendMessageActivity extends TWindow
{

	 private $form;
    
    function __construct($param)
    {
        parent::__construct();
        parent::setTitle('Notifications');
        parent::setSize(500, 250);
        
        $this->form = new BootstrapFormWrapper(new TQuickForm);
        $this->form->style = 'width: 90%';
        
        $activity_id = new TEntry('activity_id');
        $title       = new TEntry('title');
        $message     = new TEntry('message');
        
        $activity_id->setEditable(false);
        if( isset($param['id']) )
        	$activity_id->setValue($param['id']);

        $this->form->addQuickField('Activity', $activity_id, 300, new TRequiredValidator);
        $this->form->addQuickField('Title',    $title,       300, new TRequiredValidator);
        $this->form->addQuickField('Message',  $message,     300, new TRequiredValidator);        
        $this->form->addQuickAction('Send Notification', new TAction(array($this, 'onSave')), 'fa:send-o green fa-lg');
        
        parent::add($this->form);
    }
    
    public function onSave($param)
    {
        $data = $this->form->getData();
    	    
        if( $data->activity_id )
        {
        	TTransaction::open('eventtus');
        	
        	$activity = new Activity($data->activity_id);
			$event = $activity->event;
            if( $event->inscriptions )
            {
                $notification = new Notification();
                
                foreach( $event->inscriptions as $inscription )
                {
                    $tokens = Token::getTokens($inscription->email);
                    if(  $tokens )
                    {
                        foreach ($tokens as $token)
                        {
                            $notification->addToken($token->token);
                        }
                    }
                }    

                $notification->setTitle($data->title);
                $notification->setMessage($data->message);
                $notification->send();
            }
	        
	        TTransaction::close();
	        
	        new TMessage('info','Notificações enviadas com sucesso!');
	        
	        parent::closeWindow();
        }
    }
}
 ?>