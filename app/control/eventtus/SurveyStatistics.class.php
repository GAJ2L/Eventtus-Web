<?php 
/**
* @author lucas.tomasi
*/
class SurveyStatistics extends TPage
{	
	private $donut;
	private $line;
	private $bar;

	function __construct($param)
	{
		parent::__construct();
		$this->donut = new MorrisDonut('donut',350);			
		// $this->line  = new MorrisLine('line');			
		$this->bar   = new MorrisBar('bar');			

		$answers = $this->getAnswers($param['key']);

		$this->donut->setData($answers);
		// $this->line->setData($answers);
		$this->bar->setData($answers);
		
		parent::add($this->donut);
		// parent::add($this->line);
		parent::add($this->bar);
	}

	private function getAnswers($id)
	{
		try 
		{
			TTransaction::open('eventtus');

			$survey  = new Survey($id);
			$answers =  $survey->getAnswers();

			TTransaction::close();

			return $answers;
		}
		catch (Exception $e) 
		{
			TTransaction::rollback();	
			return [];
		}

	}
}
?>