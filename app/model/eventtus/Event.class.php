<?php
/**
 * Evento Active Record
 * @author  lucas.tomasi
 */
class Event extends TRecord
{
    const TABLENAME = 'public.events';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; 
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name');
        parent::addAttribute('banner');
        parent::addAttribute('contact_name');
        parent::addAttribute('contact_phone');
        parent::addAttribute('logo');
        parent::addAttribute('cor');
        parent::addAttribute('contact_mail');
        parent::addAttribute('description');
        parent::addAttribute('dt_start');
        parent::addAttribute('dt_end');
    }

    public function get_inscriptions()
    {
        try 
        {

            TTransaction::open('eventtus');
            $repository   = new TRepository('Inscription');
            $criteria     = new TCriteria();
            $criteria->add( new TFilter('event_id','=',$this->id) );
            $inscriptions = $repository->load($criteria,FALSE);
            TTransaction::close();
            return $inscriptions;
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            return null;
        }
    }
}