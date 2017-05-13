<?php
/**
 * Message Active Record
 * @author  lucas.tomasi
 */
class Message extends TRecord
{
    const TABLENAME = 'public.messages';
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
        parent::addAttribute('dt_store');
        parent::addAttribute('content');
        parent::addAttribute('activity_id');
    }
}