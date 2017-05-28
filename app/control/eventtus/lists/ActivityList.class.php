<?php
/**
 * ActivityList
 * @author  lucas.tomasi
 */
class ActivityList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new TForm('form_search_Activity');
        $this->form->class = 'tform';
        
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        $row = $table->addRow();
        $row->class = 'tformtitle';
        $row->addCell( new TLabel('Activities') )->colspan = 2;

        $name = new TEntry('name');
        $table->addRowSet("Name",$name);
        $this->form->setFields(array($name));

        $this->form->setData( TSession::getValue('Activity_filter_data') );
        
        $find_button = TButton::create('find', array($this, 'onSearch'), _t('Find'), 'fa:search');
        $new_button  = TButton::create('new',  array('ActivityForm', 'onEdit'), _t('New'), 'fa:plus-square green');
        
        $this->form->addField($find_button);
        $this->form->addField($new_button);
        
        $buttons_box = new THBox;
        $buttons_box->add($find_button);
        $buttons_box->add($new_button);
        
        $row = $table->addRow();
        $row->class = 'tformaction';
        $row->addCell($buttons_box)->colspan = 2;

        $this->datagrid = new BootstrapDatagridWrapper( new TDataGrid );
        $this->datagrid->setHeight(320);
        $this->datagrid->width = '100%';
        
        $this->datagrid->addColumn( new TDataGridColumn('id', 'ID', 'left', 100) );
        $this->datagrid->addColumn( new TDataGridColumn('name', 'Name', 'left', 100) );
        $this->datagrid->addColumn( new TDataGridColumn('dt_start' , 'dt_start' , 'center' ,100 ));
        $this->datagrid->addColumn( new TDataGridColumn('dt_end' , 'dt_end' , 'center' ,100 ));
        $this->datagrid->addColumn( new TDataGridColumn('local_name' , 'local_name' , 'center' ,100 ));
        $this->datagrid->addColumn( new TDataGridColumn('local_geolocation' , 'local_geolocation' , 'center' ,100 ));
        $this->datagrid->addColumn( new TDataGridColumn('event_id' , 'event_id' , 'center' ,100 ));
        
        $action1 = new TDataGridAction(array('ActivityForm', 'onEdit'));
        $action1->setLabel(_t('Edit'));
        $action1->setImage('fa:pencil-square-o blue fa-lg');
        $action1->setField('id');
        
        $action2 = new TDataGridAction(array($this, 'onDelete'));
        $action2->setLabel(_t('Delete'));
        $action2->setImage('fa:trash-o red fa-lg');
        $action2->setField('id');
        
        $action3 = new TDataGridAction(array('MessagesList', 'show'));
        $action3->setLabel( 'Messages' );
        $action3->setImage('fa:list blue fa-lg');
        $action3->setField('id');
 
        $action4 = new TDataGridAction(array($this, 'onSendMessage'));
        $action4->setLabel( 'Notifications' );
        $action4->setImage('fa:bell-o green fa-lg');
        $action4->setField('id');
        

        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);
        $this->datagrid->addAction($action3);
        $this->datagrid->addAction($action4);
        
        $this->datagrid->createModel();
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $tabela = new TTable();
        $tabela->style = 'width: 100%';
        $tabela->addRow()->addCell($this->form);
        $tabela->addRow()->addCell($this->datagrid);
        $tabela->addRow()->addCell($this->pageNavigation);
        
        parent::add($tabela);
    }
    
    /**
     * method onSearch()
     * Register the filter in the session when the user performs a search
     */
    function onSearch()
    {
        $data = $this->form->getData();
        
        TSession::setValue('ActivityList_filter_name_event',   NULL);
        if (isset($data->name) AND ($data->name)) {
            $filter = new TFilter('name', 'ilike', "%$data->name%");
            TSession::setValue('ActivityList_filter_name_event',   $filter);
        }

        $this->form->setData($data);
        TSession::setValue('Activity_filter_data', $data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    function onMessages($param)
    {
        TSession::setValue('activity_id_filter_messages',$param['key']);
        $messagesList = new MessagesList();
        $messagesList->show();
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
            
            $repository = new TRepository('Activity');
            $limit = 10;
            $criteria = new TCriteria;
            
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('ActivityList_filter_name_event')) {
                $criteria->add(TSession::getValue('ActivityList_filter_name_event'));
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
 
    function onSendMessage($param)
    {
        AdiantiCoreApplication::loadPage("SendMessageActivity",null,$param);
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
            $object = new Activity($key, FALSE);
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
     * method show()
     * Shows the page
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