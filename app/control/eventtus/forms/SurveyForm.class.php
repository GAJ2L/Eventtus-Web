<?php
/**
 * SurveyForm
 * @author  lucas.tomasi
 */
class SurveyForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        $this->form = new TForm('form_Survey');
        $this->form->class = 'tform';
        $this->form->style = 'width: 100%';
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        $row = $table->addRow();
        $row->class = 'tformtitle';
        $row->addCell( new TLabel('Surveys') )->colspan = 2;

        $id           = new THidden('id');
        $description  = new TText('description');
        $activity_id  = new TEntry('activity_id');
        $options      = new TMultiField('options');

        $option = new TDBCombo('option', 'eventtus', 'Option', 'id', 'description');

        $options->addField('option', 'Option', $option, 300, true);

        $table->addRowSet( '', $id );
        $table->addRowSet( 'Description:' , $description );
        $table->addRowSet( 'Activity:' , $activity_id );
        $table->addRowSet( 'Options:' , $options );

        $this->form->setFields(array($activity_id,$id,$description,$options));

        
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
            
            $data = $this->form->getData('Survey');
            
            $survey = new Survey($data->id);
            $survey->description = $data->description;
            $survey->activity_id = $data->activity_id;
            $survey->store();

            $criteria   = new TCriteria;
            $criteria->add(new TFilter('survey_id','=',$survey->id));
            $repository = new TRepository('SurveyOption');
            $repository->delete($criteria);

            if( $data->options )
            {
                foreach( $data->options as $option)
                {
                    $so = new SurveyOption;
                    $so->option_id = $option->option;
                    $so->survey_id = $survey->id;
                    $so->store();
                }
            }

            $this->form->setData($data);

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

                $object = new Survey($key);

                $criteria = new TCriteria;
                $criteria->add(new TFilter('survey_id','=',$object->id));
                $repository = new TRepository('SurveyOption');
                $options = $repository->load($criteria);
                
                if( $options )
                {
                    $ops = [];
                    foreach ($options as $op)
                    {
                        $obj = new stdClass;
                        $obj->option = $op->option_id;
                        $ops[] = $obj;
                    }

                    $object->options = $ops; 
                }
                
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

    function onList()
    {
        AdiantiCoreApplication::gotoPage('SurveyList');
    }
}
