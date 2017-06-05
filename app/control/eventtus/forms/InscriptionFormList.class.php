<?php
/**
 * @author  lucas.tomasi
 */
use Adianti\Widget\Wrapper\TQuickForm;

class InscriptionFormList extends TPage
{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;
    protected $loaded;
    

    function __construct()
    {
        parent::__construct();
        
        $this->form = new TQuickForm('form_Inscription');
        $this->form->class = 'tform'; 
        $this->form->setFormTitle('Inscriptions');
        

        $email = new TEntry('email');
        $hash  = new TEntry('hash');
        $event = new TEntry('event_id');

        $this->form->addQuickField('Email', $email,  350 );
        $this->form->addQuickField('HASH', $hash,  350 );
        $this->form->addQuickField('Event (ID)', $event,  350 );

        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'fa:plus-square green');
        
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        $this->datagrid->width = '100%' ;
        
        $this->datagrid->addQuickColumn('Email', 'email', 'left', '90%');
        $this->datagrid->addQuickColumn('HASH', 'hash', 'left', '90%');
        $this->datagrid->addQuickColumn('Event', 'event_id', 'left', '90%');

        $this->datagrid->addQuickAction('Delete',  new TDataGridAction(array($this, 'onDelete')), 'id', 'fa:trash-o red fa-lg');
        
        $this->datagrid->createModel();
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $this->datagrid->createModel();

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $table = new TTable();
        $table->style = 'width: 100%';
        $table->addRow()->addCell($this->form);
        $table->addRow()->addCell($this->datagrid);
        $table->addRow()->addCell($this->pageNavigation);

        parent::add($table);
    }

    /**
     * method onReload()
     * Load the datagrid with the database objects
     */
    function onReload($param = NULL)
    {
        try
        {
            TTransaction::open('eventtus');
            
            $repository = new TRepository('Inscription');
    
            $limit = 10;
            
            $criteria = new TCriteria;
            
            
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); 
            $criteria->setProperty('limit', $limit);
            
            if (TSession::getValue('Inscription_filter'))
            {
                
                $criteria->add(TSession::getValue('Inscription_filter'));
            }
            
            
            $objects = $repository->load($criteria, FALSE);
            
            $this->datagrid->clear();
            if ($objects)
            {
                
                foreach ($objects as $object)
                {
                    
                    $this->datagrid->addItem($object);
                }
            }
            
            
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); 
            $this->pageNavigation->setProperties($param); 
            $this->pageNavigation->setLimit($limit); 
            
            
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) 
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); 
            TTransaction::rollback(); 
        }
    }
    
    /**
     * method onDelete()
     * executed whenever the user clicks at the delete button
     * Ask if the user really wants to delete the record
     */
    function onDelete($param)
    {
        
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); 
        
        
        new TQuestion(TAdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * method Delete()
     * Delete a record
     */
    function Delete($param)
    {
        try
        {
            
            $key=$param['key'];
            
            TTransaction::open('eventtus'); 
            $object = new Inscription($key, FALSE); 
            $object->delete(); 
            TTransaction::close(); 
            
            $this->onReload( $param ); 
            new TMessage('info', TAdiantiCoreTranslator::translate('Record deleted')); 
        }
        catch (Exception $e) 
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); 
            TTransaction::rollback(); 
        }
    }
    
    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    function onSave()
    {
        try
        {
            TTransaction::open('eventtus'); 
            
            
            $object = $this->form->getData('Inscription');
            $this->form->validate(); 
            $object->store(); 
            $this->form->setData($object); 
    
            // salva qrcode
            $name = "tmp/{$object->event_id}{$object->email}{$object->hash}.png";
            file_put_contents( $name , file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='.$object->hash) );

            $config = parse_ini_file('app/config/mail.ini');

            // envia email com QRCode
            $mail = new TMail();
            $mail->addAddress($object->email);
            $mail->setHtmlBody($this->getMessage($object->hash));
            $mail->addAttach($name,'QRCode.png');
            $mail->setSubject("Novo evento");
            $mail->setFrom($config['mail'],"Eventtus");
            $mail->SetSmtpHost($config['host'],$config['port']);
            $mail->SetSmtpUser($config['mail'],$config['pass']);
            $mail->SetUseSmtp();
            $mail->send();



            TTransaction::close(); 
            
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved')); 
            $this->onReload(); 
        }
        catch (Exception $e) 
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); 
            TTransaction::rollback(); 
        }
    }
    
    public function getMessage($codigo)
    {
        return "
            <b>Olá</b><br>
            <br>
            Sua inscrição foi efetuada com sucesso.<br>
            <br>
            Para mais informações sobre o evento e suas atividades, você pode acessar o aplicativo <b>Eventtus</b>, na PlayStore<br><br>
            Para adicionar o evento basta você logar com o email cadastrado nessa inscrição e utilizar o QRCode em anexo, ou você pode adicionar o código <b>{$codigo}</b> manualmente.<br>
            <br>
            Bom evento <b>;)</b><br>
            <br>
            Atencionsamente<br>
            Equipe <b>GAJ2L</b><br> 
        ";
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
                $object = new Inscription($key); 
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
    
    /**
     * method show()
     * Shows the page e seu conteÃºdo
     */
    function show()
    {
        
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') )
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}
