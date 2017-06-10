<?php
/**
 * @author Lucas Tomasi
 */
class MorrisLine extends TElement
{
    protected $data;
    protected $colors;
    protected $labels;
    protected $area;
    
    /**
     * Método para construir um gráfico no formato "line" (linhas ou areas)
     * @param String  $name   - Name da div que receberá o gráfico.
     * @param Integer $height - Altura da div que receberá o gráfico.
     * @param Integer $width  - Largura da div que receberá o gráfico.
     * @param Array   $data   - Array contendo chave (Descrição) e valor.
     * @param Array   $colors - Array com cores para os dados, caso o número de cores for
     *                                menor que o número de elementos as cores irão repetir.
     */
    public function __construct($name)
    {
        parent::__construct('div');
        $this->id   = 'morris_bar'. uniqid();
        $this->name = $name;
        
        $this->data    = array();
        $this->labels  = array();
        $this->grid    = 'false'; 
        $this->area    = false; 
    }

    public function setData(array $data)
    {
        foreach ($data as $key => $object)
        {
            $row = "{y: '{$key}',";

            if( is_array($object) )
            {
                foreach ($object as $chave => $value) 
                {
                    $row .= " {$chave}: {$value},";
                    $this->labels[ $chave ] = "'{$chave}'";
                }
        
                $row =  substr($row, 0,-1).'}';
            }
            else
            {
                $row .= "value: {$object} }";
                $this->labels[''] = '\'value\'';
            }

            $this->data[] = $row;
        }
    }

    public function getData()
    {
        return implode(',', $this->data);
    }

    public function getLabels()
    {
        if($this->labels)
            return '[' .implode(',', $this->labels) .']';
        else
            return '';
    }    

    public function setColors($colors)
    {
        $this->colors = $colors;
    }

    public function getColors()
    {
        if($this->colors)
            return 'barColors: [' .implode(',', $this->colors) .'],';
        else
            return '';
    }

    public function setGrid($grid)
    {
        $this->grid = ($grid)? 'true' : 'false' ;
    }

    public function getGrid()
    {
        return "grid: $this->grid,";
    }

    public function setSize($height, $width = null)
    {
        $style = 'height: '. $height .'px;';
        if($width)
            $style .= 'width :'. $width .'px;';

        $this->style = $style;
    }

    public function setArea( $area = false )
    {
        $this->area = $area;
    }

    public function getType()
    {
        if($this->area)
            return 'Area';
        else
            return 'Line';
    }

    /**
     * função para modificar o popover do grafico
     * @param $function [função javascript] deve receber os seguintes parametros:
     *                  [ index ]   - posição do grafico
     *                  [ options ] - as opções de cada dado do grafico
     *                  [ content ] - html do popover
     *                  [ row ]     - linha do array data
     */
    public function setHoverCallback( $function )
    {
        if ( $function )
            $this->hoverCallback = 'hoverCallback: '.$function.',';
    }

    public function getHoverCallback()
    {
        if ( $this->hoverCallback )
            return $this->hoverCallback;
        else
            return '';
    }


    public function show()
    {
        TScript::create("Morris.{$this->getType()}({
                            element:   {$this->id},
                            data:      [{$this->getData()}],
                            xkey:      'y',
                            ykeys:     {$this->getLabels()},
                            labels:    {$this->getLabels()},
                            resize: true,
                            parseTime: false,
                            hideHover: 'auto',
                            {$this->getHoverCallback()}
                            {$this->getGrid()}
                            {$this->getColors()}
                            }); ");
        
        parent::show();
    }
}

?>