<?php
/**
 * Evaluation Active Record
 * @author  lucas.tomasi
 */
class Evaluation extends TRecord
{
    const TABLENAME = 'public.evaluations';
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
        parent::addAttribute('comment');
        parent::addAttribute('ref_activity');
        parent::addAttribute('stars');
    }
}