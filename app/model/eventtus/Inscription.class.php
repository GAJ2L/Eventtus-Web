<?php
/**
 * Inscription Active Record
 * @author  lucas.tomasi
 */
class Inscription extends TRecord
{
    const TABLENAME = 'public.inscriptions';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; 
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('email');
        parent::addAttribute('hash');
        parent::addAttribute('event_id');
    }
}