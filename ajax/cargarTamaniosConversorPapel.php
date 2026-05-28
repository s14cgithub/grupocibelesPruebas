<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarTamanioConversor")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	


	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = [
		'id',
		'idTamanioInicio',
		'idTamanioFinal',
		'valor'		
	];

	$joins = ['tabla2','tabla3'];

	$filtros = [
			//'cliente' => 'EMPRESA SL'
		];

	$filtrosOperadores = [
			//'cliente' => 'EMPRESA SL'
		];

	$order = [
			['campo' => 'tamanioInicio', 'dir' => 'ASC'],
			['campo' => 'tamanioFinal', 'dir' => 'ASC']
		];
	
	$tamanio = cargarTamaniosConversorPapel($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores, $order);
	
	sqlsrv_close($conn);	
	
	echo json_encode($tamanio);	
		
}

?>
