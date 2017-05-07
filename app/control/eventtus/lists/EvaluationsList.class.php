<?php
/**
 * EvaluationsList
 * @author  lucas.tomasi
 */
class EvaluationsList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new TForm('form_search_Evaluation');
        $this->form->class = 'tform';
        
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        $row = $table->addRow();
        $row->class = 'tformtitle';
        $row->addCell( new TLabel('Evaluations') )->colspan = 2;

        $email = new TEntry('email');
        $table->addRowSet("Email",$email);
        $activity = new TEntry('ref_activity');
        $table->addRowSet("Activity",$activity);
        $this->form->setFields(array($email,$activity));

        $this->form->setData( TSession::getValue('Evaluation_filter_data') );
        
        $find_button = TButton::create('find', array($this, 'onSearch'), _t('Find'), 'fa:search');
        $this->form->addField($find_button);
        
        $buttons_box = new THBox;
        $buttons_box->add($find_button);        
        $row = $table->addRow();
        $row->class = 'tformaction';
        $row->addCell($buttons_box)->colspan = 2;

        $this->datagrid = new BootstrapDatagridWrapper( new TDataGrid );
        $this->datagrid->setHeight(320);
        $this->datagrid->width = '100%';
        
        $this->datagrid->addColumn( new TDataGridColumn('id', 'ID', 'left', 100) );
        $this->datagrid->addColumn( new TDataGridColumn('email', 'Email', 'left', 100) );
        $this->datagrid->addColumn( new TDataGridColumn('comment' , 'Comment' , 'center' ,100 ));
        $this->datagrid->addColumn( new TDataGridColumn('stars' , 'Star' , 'center' ,100 ));
        $this->datagrid->addColumn( new TDataGridColumn('dt_store' , 'Date' , 'center' ,100 ));
        $this->datagrid->addColumn( new TDataGridColumn('ref_activity' , 'Activity' , 'center' ,100 ));
        
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
        
        TSession::setValue('EvaluationsList_filter_email_event',   NULL);
        if (isset($data->email) AND ($data->email)) {
            $filter = new TFilter('email', 'ilike', "%$data->email%");
            TSession::setValue('EvaluationsList_filter_email_event',   $filter);
        }

        TSession::setValue('EvaluationsList_filter_activity_event',   NULL);
        if (isset($data->ref_activity) AND ($data->ref_activity)) {
            $filter = new TFilter('ref_activity::text', 'ilike', "%$data->ref_activity%");
            TSession::setValue('EvaluationsList_filter_activity_event',   $filter);
        }

        $this->form->setData($data);
        TSession::setValue('Evaluation_filter_data', $data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
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
            
            $repository = new TRepository('Evaluation');
            $limit = 10;
            $criteria = new TCriteria;
            
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('EvaluationsList_filter_email_event')) {
                $criteria->add(TSession::getValue('EvaluationsList_filter_email_event'));
            }

            if (TSession::getValue('EvaluationsList_filter_activity_event')) {
                $criteria->add(TSession::getValue('EvaluationsList_filter_activity_event'));
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