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

    public function getOptions()
    {
        $criteria   = new TCriteria;
        $repository = new TRepository('SurveyOption');

        $criteria->add(new TFilter('survey_id','=',$this->id));

        $options = $repository->load($criteria);
   
        $result = [];

        if( $options )
        {
            foreach( $options as $option )
            {
                $result[] = $option;
            }
        }

        return $result;
    }

    public function getAnswers()
    {
        $options = $this->getOptions();

        $result = [];
        
        if( $options )
        {
            foreach( $options as $option )
            {                        
                $criteria   = new TCriteria;
                $repository = new TRepository('SurveyAnswer');

                $criteria->add(new TFilter('survey_id',        '=', $this->id));
                $criteria->add(new TFilter('survey_options_id','=', $option->option_id));

                $objOption = new Option($option->option_id);

                $answers = $repository->load($criteria);
                
                $result[$objOption->description] = count($answers);
            }
        }

        return $result;
    }
}