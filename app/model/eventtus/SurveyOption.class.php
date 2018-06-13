<?php
/**
 * SurveyOption Active Record
 * @author  lucas.tomasi
 */
class SurveyOption extends TRecord
{
    const TABLENAME = 'public.survey_options';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial';
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('option_id');
        parent::addAttribute('survey_id');
    }
}