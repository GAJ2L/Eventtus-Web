<?php
/**
 * Survey Active Record
 * @author  lucas.tomasi
 */
class Survey extends TRecord
{
    const TABLENAME = 'public.survey';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial';
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('description');
        parent::addAttribute('activity_id');
    }
}