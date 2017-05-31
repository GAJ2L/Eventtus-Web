<?php
/**
 * @author  lucas.tomasi
 */
use Adianti\Widget\Wrapper\TQuickForm;

class OptionFormList extends TPage
{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;
    protected $loaded;
    

    function __construct()
    {
        parent::__construct();
        
        $this->form = new TQuickForm('form_Option');
        $this->form->class = 'tform'; 
        $this->form->setFormTitle('Options');
        

        $value       = new TEntry('value');
        $description = new TEntry('description');

        $this->form->addQuickField('Description', $description,  350 );
        $this->form->addQuickField('Value', $value,  350 );

        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'fa:plus-square green');
        
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        $this->datagrid->width = '100%' ;
        
        $this->datagrid->addQuickColumn('Description', 'description', 'left', '90%');
        $this->datagrid->addQuickColumn('Value', 'value', 'left', '90%');

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
            
            $repository = new TRepository('Option');
    
            $limit = 10;
            
            $criteria = new TCriteria;
            
            
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); 
            $criteria->setProperty('limit', $limit);
            
            if (TSession::getValue('Option_filter'))
            {
                
                $criteria->add(TSession::getValue('Option_filter'));
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
            $object = new Option($key, FALSE); 
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
            
            $object = $this->form->getData('Option');
            $this->form->validate(); 
            $object->store(); 
            $this->form->setData($object); 

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
                $object = new Option($key); 
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
