<?php
/**
 * Option Active Record
 * @author  lucas.tomasi
 */
class Option extends TRecord
{
    const TABLENAME = 'public.options';
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
        parent::addAttribute('value');
    }
}