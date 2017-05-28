<?php
/**
 * Activity Active Record
 * @author  lucas.tomasi
 */
class Activity extends TRecord
{
    const TABLENAME = 'public.activities';
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
        parent::addAttribute('dt_start');
        parent::addAttribute('dt_end');
        parent::addAttribute('local_name');
        parent::addAttribute('local_geolocation');
        parent::addAttribute('event_id');
    }

    public function get_event()
    {
        try 
        {
            TTransaction::open('eventtus');
            $event = new Event($this->event_id);
            TTransaction::close();
            return $event;
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            return null;
        }
    }
}