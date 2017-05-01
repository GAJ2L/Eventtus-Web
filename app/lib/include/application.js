/**
 * Adiciona um ano na data de validade
 * no form de apolices
 */
function preencheDataValidade()
{
   var dt_fechamento = $('input[name="dt_fechamento"]').val();
   var arrayData  = dt_fechamento.split("/");
   arrayData[2] = parseInt(arrayData[2]) + 1;
   var dt_validade = arrayData[0]+"/"+arrayData[1]+"/"+arrayData[2];
   $('input[name="dt_validade"]').val( dt_validade );
}

function defineValor( nomeCampo , value )
{
	$("input[name='" +nomeCampo+ "']").val( value );
}

function mostraCampo( nome ) 
{
	$("input[name='" +nome+ "']").closest('tr').show();
}

function escondeCampo( nome ) 
{
	$("input[name='" +nome+ "']").closest('tr').hide();
}

function mostraPDF( id , pdf )
{
	$('#pdf').append('<i class="fa fa-download"></i><a target="_blank" href="apolices/'+id+'.pdf">&nbsp;'+pdf+'</a>');
	$('#pdf').parent('td').show();
}

function definirDatasComEventos( datas )
{
	var datas_array = datas.split(',');
	
	for (var i = datas_array.length - 1; i >= 0; i--) 
	{
		$('#'+datas_array[i]).css( 'background'    , '#222d32' );
		$('#'+datas_array[i]).css( 'color'         , 'rgb(255, 255, 255)'   );
		$('#'+datas_array[i]).css( 'border'        , 'solid 1px #EDEDED' );    
		$('#'+datas_array[i]).css( 'border-radius' , '5px' );    
	};
}