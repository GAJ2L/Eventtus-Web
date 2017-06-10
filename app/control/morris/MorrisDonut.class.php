<?php
/**
 * @author Artur Comunello
 */
class MorrisDonut extends TElement
{
    protected $data;
    protected $colors;
    
    /**
     * Método para construir um gráfico no formato "donut" (rosca)
     * @param String  $name   - Name da div que receberá o gráfico.
     * @param Integer $height - Altura da div que receberá o gráfico.
     * @param Integer $width  - Largura da div que receberá o gráfico.
     * @param Array   $data   - Array contendo chave (Descrição) e valor.
     * @param Array   $colors - Array com cores para os dados, caso o número de cores for
     *                                menor que o número de elementos as cores irão repetir.
     */
    public function __construct($name, $height, $width = null, $data = null, $colors = null)
    {
        parent::__construct('div');
        
        $this->id   = 'morris_donut'. uniqid();
        $this->name = $name;
        $this->data = array();
        $this->formatter = '';
        
        $this->setSize($height, $width);
        
        if($data)
            $this->setData($data);
        if($colors)
            $this->setColors($colors);
    }

    public function setData(array $data)
    {
        foreach ($data as $key => $value)
        {
            $this->data[] = "{label: \"{$key}\", value: {$value} }";
        }
    }

    public function getData()
    {
        return implode(',', $this->data);
    }

    public function setColors($colors)
    {
        $this->colors = $colors;
    }

    public function getColors()
    {
        if($this->colors)
            return ', colors: [' .implode(',', $this->colors) .']';
        else
            return '';
    }

    public function setSize($height, $width = null)
    {
        $style = 'height: '. $height .'px;';
        if($width)
            $style .= 'width :'. $width .'px;';

        $this->style = $style;
    }

    /**
     * função para modificar formato que os dados serão exibidos
     * @param $function [função javascript] deve receber os seguintes parametros:
     *                  [ label ] - chave do array data
     *                  [ value ] - valor do array data
     */
    public function setFormatter( $function )
    {
        if ( $function )
            $this->formatter = 'formatter: '.$function;
    }

    public function getFormatter()
    {
        if( $this->formatter )
            return $this->formatter;
        else
            return '';
    }

    public function show()
    {
        $data   = $this->getData();
        $colors = $this->getColors();

        TScript::create("Morris.Donut({
                            element: {$this->id},
                            resize:  true,
                            data:    [{$data}],
                            {$this->getFormatter()}
                            {$colors}
                        }); ");
        
        parent::show();
    }
}
?>