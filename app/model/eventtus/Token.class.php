<?php
/**
 * Token Active Record
 * @author  lucas.tomasi
 */
class Token extends TRecord
{
    const TABLENAME = 'public.tokens';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial';
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('token');
        parent::addAttribute('email');
    }

    public static function getTokens($email)
    {
        $repository = new TRepository('Token');
        $criteria   = new TCriteria();
        $criteria->add( new TFilter('email','=',$email) );
        $tokens = $repository->load($criteria,FALSE);
        return $tokens;
    }
}