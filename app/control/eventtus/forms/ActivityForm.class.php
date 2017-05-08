<?php
/**
 * ActivityForm
 * @author  lucas.tomasi
 */
class ActivityForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        $this->form = new TForm('form_Activity');
        $this->form->class = 'tform';
        $this->form->style = 'width: 100%';
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        $row = $table->addRow();
        $row->class = 'tformtitle';
        $row->addCell( new TLabel('Activities') )->colspan = 2;

        $id                = new THidden('id');
        $name              = new TEntry('name');
        $local_name        = new TText('local_name');
        $local_geolocation = new TEntry('local_geolocation');
        $dtStart           = new TEntry('dt_start');
        $dtEnd             = new TEntry('dt_end');
        $eventID           = new TEntry('event_id');
        $attachment        = new TFile('attachment_0');
        $attachment1       = new TFile('attachment_1');
        $attachment2       = new TFile('attachment_2');
        $attachment3       = new TFile('attachment_3');
        $link              = new TEntry('link_0');
        $link1             = new TEntry('link_1');
        $link2             = new TEntry('link_2');
        $link3             = new TEntry('link_3');

        $dtStart->setMask('9999-99-99 99:99:99');
        $dtEnd->setMask('9999-99-99 99:99:99');
        $eventID->setMask('99999999999');
        

        $table->addRowSet( '', $id );
        $table->addRowSet( 'Name:<font color="red">*</font>'         , $name );
        $table->addRowSet( 'Local:<font color="red">*</font>'        , $local_name );
        $table->addRowSet( 'Geo Location:<font color="red">*</font>' , $local_geolocation );
        $table->addRowSet( 'Dt Start:<font color="red">*</font>'     , $dtStart );
        $table->addRowSet( 'Dt End:<font color="red">*</font>'       , $dtEnd );
        $table->addRowSet( 'Event (ID):<font color="red">*</font>'   , $eventID );
        $table->addRowSet( '<hr>'   , '<hr>' );
        $table->addRowSet( 'Attachments 1:'   , $attachment  );
        $table->addRowSet( 'Attachments 2:'   , $attachment1 );
        $table->addRowSet( 'Attachments 3:'   , $attachment2 );
        $table->addRowSet( 'Attachments 4:'   , $attachment3 );
        $table->addRowSet( 'Link 1:'   , $link );
        $table->addRowSet( 'Link 2:'   , $link1 );
        $table->addRowSet( 'Link 3:'   , $link2 );
        $table->addRowSet( 'Link 4:'   , $link3 );

        $this->form->setFields(array($link3,$link2,$link1,$link,$name,$id,$local_geolocation,$dtStart,$dtEnd,$eventID,$local_name,$attachment,$attachment1,$attachment2,$attachment3));

        
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
    function onSave()
    {
        try
        {
            TTransaction::open('eventtus'); 
            
            $object = $this->form->getData('Activity');
            $data   = $this->form->getData();
            $this->form->validate();             
            $object->store();
            $config = parse_ini_file("app/config/eventtus.ini");

            for ($i=0; $i < 4; $i++) 
            {
                $name = $data->{"attachment_$i"};
                if( $name )
                {
                    if( file_exists("tmp/$name") )
                    {
                        $attachment = new Attachment();
                        $attachment->size = $this->getSize( filesize("tmp/$name") );
                        $attachment->type = $this->getType( "tmp/$name" );
                        $attachment->name = $name;
                        $attachment->activity_id = $object->id;
                        $attachment->local = $config['base']. "attachments/{$object->id}<-->{$name}";
                        $attachment->store();
                        copy("tmp/$name", "attachments/{$object->id}<-->{$name}" );
                        unlink("tmp/$name");
                    }
                }

                $link = $data->{"link_$i"};

                if( $link )
                {
                    $attachment = new Attachment();
                    $attachment->size = "";
                    $attachment->type = 2;
                    $attachment->name = "Link $i - ".$object->name;
                    $attachment->activity_id = $object->id;
                    $attachment->local = $link;
                    $attachment->store();
                }    
            }

            $this->form->setData($object);

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
    
    public function getType($file_name)
    {
        $file = new SplFileInfo($file_name);
        $e = strtoupper($file->getExtension());
        
        if( $e == 'JPG' OR $e == 'JPEG' OR $e == 'PNG' OR $e == 'GIF' )
        {
            $extension = 0;
        }
        elseif($e == 'PDF')
        {
            $extension = 1;
        }
        elseif($e == 'PPSX' OR $e == 'PPS' OR $e == 'PPSM' OR $e == 'PPT' OR $e == 'ODP' OR $e == 'ODG' OR $e == 'OTG' OR $e == 'OTP')
        {
            $extension = 3;
        }
        else
        {
            $extension = 4;
        }

        return $extension;
    }

    public function getSize($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;

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

                $object = new Activity($key); 
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
        AdiantiCoreApplication::gotoPage('ActivityList');
    }
}
