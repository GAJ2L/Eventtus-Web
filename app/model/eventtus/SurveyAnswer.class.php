<?php
/**
 * SurveyAnswer Active Record
 * @author  lucas.tomasi
 */
class SurveyAnswer extends TRecord
{
    const TABLENAME = 'public.survey_answer';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial';
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('survey_options_id');
        parent::addAttribute('email');
        parent::addAttribute('survey_id');
    }
}