<?php
/**
 * Attachment Active Record
 * @author  lucas.tomasi
 */
class Attachment extends TRecord
{
    const TABLENAME = 'public.attachments';
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
        parent::addAttribute('size');
        parent::addAttribute('local');
        parent::addAttribute('type');
        parent::addAttribute('activity_id');
    }
}