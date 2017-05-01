<?php
/**
 * ApoliceReport Report
 * @author  <your name here>
 */
class ApoliceReport extends TPage
{
    protected $form; // form
    protected $notebook;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // create the form
        $this->form = new TQuickForm('form_Apolice_report');
        $this->form->class = 'tform';
        $this->form->style = 'width: 500px';
        $this->form->setFormTitle('Report');
        


        // create the form fields
        $id                             = new TEntry('id');
        $ref_companhia                  = new TEntry('ref_companhia');
        $ref_tipo_apolice               = new TEntry('ref_tipo_apolice');
        $premio                         = new TEntry('premio');
        $comissao                       = new TEntry('comissao');
        $dt_fechamento                  = new TEntry('dt_fechamento');
        $dt_validade                    = new TEntry('dt_validade');
        $ref_cliente                    = new TEntry('ref_cliente');
        $total_parcelas                 = new TEntry('total_parcelas');
        $output_type                    = new TRadioGroup('output_type');


        // add the fields
        $this->form->addQuickField('id', $id,  50);
        $this->form->addQuickField('ref_companhia', $ref_companhia,  50);
        $this->form->addQuickField('ref_tipo_apolice', $ref_tipo_apolice,  50);
        $this->form->addQuickField('premio', $premio,  50);
        $this->form->addQuickField('comissao', $comissao,  50);
        $this->form->addQuickField('dt_fechamento', $dt_fechamento,  50);
        $this->form->addQuickField('dt_validade', $dt_validade,  50);
        $this->form->addQuickField('ref_cliente', $ref_cliente,  50);
        $this->form->addQuickField('total_parcelas', $total_parcelas,  50);
        $this->form->addQuickField('Output', $output_type,  100, new TRequiredValidator );




        
        $output_type->addItems(array('html'=>'HTML', 'pdf'=>'PDF', 'rtf'=>'RTF'));;
        $output_type->setValue('pdf');
        $output_type->setLayout('horizontal');
        
        // add the action button
        $this->form->addQuickAction(_t('Generate'), new TAction(array($this, 'onGenerate')), 'fa:check-circle-o green');
        
        // add the form to the page
        parent::add($this->form);
    }
    
    /**
     * method onGenerate()
     * Executed whenever the user clicks at the generate button
     */
    function onGenerate()
    {
        try
        {
            // open a transaction with database 'eventtus'
            TTransaction::open('eventtus');
            
            // get the form data into an active record
            $formdata = $this->form->getData();
            
            $repository = new TRepository('Apolice');
            $criteria   = new TCriteria;
            
            if ($formdata->id)
            {
                $criteria->add(new TFilter('id', 'like', "%{$formdata->id}%"));
            }
            if ($formdata->ref_companhia)
            {
                $criteria->add(new TFilter('ref_companhia', 'like', "%{$formdata->ref_companhia}%"));
            }
            if ($formdata->ref_tipo_apolice)
            {
                $criteria->add(new TFilter('ref_tipo_apolice', 'like', "%{$formdata->ref_tipo_apolice}%"));
            }
            if ($formdata->premio)
            {
                $criteria->add(new TFilter('premio', 'like', "%{$formdata->premio}%"));
            }
            if ($formdata->comissao)
            {
                $criteria->add(new TFilter('comissao', 'like', "%{$formdata->comissao}%"));
            }
            if ($formdata->dt_fechamento)
            {
                $criteria->add(new TFilter('dt_fechamento', 'like', "%{$formdata->dt_fechamento}%"));
            }
            if ($formdata->dt_validade)
            {
                $criteria->add(new TFilter('dt_validade', 'like', "%{$formdata->dt_validade}%"));
            }
            if ($formdata->ref_cliente)
            {
                $criteria->add(new TFilter('ref_cliente', 'like', "%{$formdata->ref_cliente}%"));
            }
            if ($formdata->total_parcelas)
            {
                $criteria->add(new TFilter('total_parcelas', 'like', "%{$formdata->total_parcelas}%"));
            }

           
            $objects = $repository->load($criteria);
            $format  = $formdata->output_type;
            
            if ($objects)
            {
                $widths = array(50,50,50,50,50,50,50,50,50);
                
                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths);
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $tr = new TTableWriterRTF($widths);
                        break;
                }
                
                // create the document styles
                $tr->addStyle('title', 'Arial', '10', 'B',   '#ffffff', '#6B6B6B');
                $tr->addStyle('datap', 'Arial', '10', '',    '#000000', '#E5E5E5');
                $tr->addStyle('datai', 'Arial', '10', '',    '#000000', '#ffffff');
                $tr->addStyle('header', 'Times', '16', 'B',  '#4A5590', '#C0D3E9');
                $tr->addStyle('footer', 'Times', '12', 'BI', '#4A5590', '#C0D3E9');
                
                // add a header row
                $tr->addRow();
                $tr->addCell('Apolice', 'center', 'header', 9);
                
                // add titles row
                $tr->addRow();
                $tr->addCell('id', 'right', 'title');
                $tr->addCell('ref_companhia', 'right', 'title');
                $tr->addCell('ref_tipo_apolice', 'right', 'title');
                $tr->addCell('premio', 'right', 'title');
                $tr->addCell('comissao', 'right', 'title');
                $tr->addCell('dt_fechamento', 'left', 'title');
                $tr->addCell('dt_validade', 'left', 'title');
                $tr->addCell('ref_cliente', 'right', 'title');
                $tr->addCell('total_parcelas', 'right', 'title');

                
                // controls the background filling
                $colour= FALSE;
                
                // data rows
                foreach ($objects as $object)
                {
                    $style = $colour ? 'datap' : 'datai';
                    $tr->addRow();
                    $tr->addCell($object->id, 'right', $style);
                    $tr->addCell($object->ref_companhia, 'right', $style);
                    $tr->addCell($object->ref_tipo_apolice, 'right', $style);
                    $tr->addCell($object->premio, 'right', $style);
                    $tr->addCell($object->comissao, 'right', $style);
                    $tr->addCell($object->dt_fechamento, 'left', $style);
                    $tr->addCell($object->dt_validade, 'left', $style);
                    $tr->addCell($object->ref_cliente, 'right', $style);
                    $tr->addCell($object->total_parcelas, 'right', $style);

                    
                    $colour = !$colour;
                }
                
                // footer row
                $tr->addRow();
                $tr->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 9);
                // stores the file
                if (!file_exists("app/output/Apolice.{$format}") OR is_writable("app/output/Apolice.{$format}"))
                {
                    $tr->save("app/output/Apolice.{$format}");
                }
                else
                {
                    throw new Exception(_t('Permission denied') . ': ' . "app/output/Apolice.{$format}");
                }
                
                // open the report file
                parent::openFile("app/output/Apolice.{$format}");
                
                // shows the success message
                new TMessage('info', 'Report generated. Please, enable popups in the browser (just in the web).');
            }
            else
            {
                new TMessage('error', 'No records found');
            }
    
            // fill the form with the active record data
            $this->form->setData($formdata);
            
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
