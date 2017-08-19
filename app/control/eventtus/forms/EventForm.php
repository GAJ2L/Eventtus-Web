<?php
/**
 * EventForm
 * @author  lucas.tomasi
 */
class EventForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        $this->form = new TForm('form_Event');
        $this->form->class = 'tform';
        $this->form->style = 'width: 100%';
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        $row = $table->addRow();
        $row->class = 'tformtitle';
        $row->addCell( new TLabel('Events') )->colspan = 2;

        $id           = new THidden('id');
        $name         = new TEntry('name');
        $description  = new TText('description');
        $banner       = new TEntry('banner');
        $cor          = new TEntry('cor');
        $logo         = new TEntry('logo');
        $dtStart      = new TEntry('dt_start');
        $dtEnd        = new TEntry('dt_end');
        $contactName  = new TEntry('contact_name');
        $contactPhone = new TEntry('contact_phone');
        $contactMail  = new TEntry('contact_mail');

        $name->setSize(325);
        

        $table->addRowSet( '', $id );
        $table->addRowSet( 'Name:<font color="red">*</font>'        , $name );
        $table->addRowSet( 'Description:<font color="red">*</font>' , $description );
        $table->addRowSet( 'Banner:<font color="red">*</font>'      , $banner );
        $table->addRowSet( 'Cor:<font color="red">*</font>'         , $cor );
        $table->addRowSet( 'Logo:<font color="red">*</font>'        , $logo );
        $table->addRowSet( 'Dt Start:<font color="red">*</font>'    , $dtStart );
        $table->addRowSet( 'Dt End:<font color="red">*</font>'      , $dtEnd );
        $table->addRowSet( '<b>Contact</b>' );    
        $table->addRowSet( '<hr>','<hr>' );
        $table->addRowSet( 'Name:<font color="red">*</font>'     , $contactName );
        $table->addRowSet( 'Phone:<font color="red">*</font>'    , $contactPhone );
        $table->addRowSet( 'E-mail:<font color="red">*</font>'   , $contactMail );

        $this->form->setFields(array($name,$id,$banner,$dtStart,$dtEnd,$contactName,$contactPhone,$contactMail,$description));

        
        $save_button = TButton::create('save', array($this, 'onSave'), _t('Save'), 'fa:floppy-o');
        $new_button  = TButton::create('new' , array($this, 'onEdit'), _t('New'),  'fa:plus-square green');
        $list_button = TButton::create('list', array($this, 'onList'), _t('Back to the listing'),  'fa:table blue');
        $this->form->addField($save_button);
        $this->form->addField($new_button);
        $this->form->addField($list_button);

        $buttons_box = new THBox;
        $buttons_box->add($save_button);
        $buttons_box->add($new_button);
        $buttons_box->add($list_button);

        $row = $table->addRow();
        $row->class = 'tformaction'; // CSS class
        $row->addCell($buttons_box)->colspan = 2;
        
        $table = new TTable();
        $table->style = 'width: 100%';
        $table->addRow()->addCell($this->form);
        parent::add( $table );
    }

    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    function onSave($param)
    {
        try
        {
            TTransaction::open('eventtus'); 
            
            $event = $this->form->getData('Event');
            $edicao = ($event->id)? true : false ;
            $this->form->validate();             
            $event->store(); 
            $this->form->setData($event);

            if( $edicao )
            {
                $this->sendNotifications($event);
            }

            TTransaction::close();    
            
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) 
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); 
            $this->form->setData( $this->form->getData() ); 
            TTransaction::rollback();
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                $key=$param['key'];  

                TTransaction::open('eventtus'); 

                $object = new Event($key); 
                $this->form->setData($object); 

                TTransaction::close(); 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }

    function sendNotifications( $event )
    {
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

            $notification->setMessage("Evento: $event->name foi atualizado, atualize o evento para mais informações!");
            $notification->send();
        }
    }

    function onList()
    {
        AdiantiCoreApplication::gotoPage('EventList');
    }
}
