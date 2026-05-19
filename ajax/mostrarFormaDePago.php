<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarFormaDePago")
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
		'concepto'	
	];

	$filtros = [
			//'cliente' => 'EMPRESA SL'
		];

	$order = [			
			['campo' => 'concepto', 'dir' => 'ASC']
		];

	
	$formasDePago = cargarFormasDePago($conn,$bbddSql, $campos, $filtros, $order);
	
	sqlsrv_close($conn);
	
	if (count($formasDePago)<=0)
	{
		echo json_encode("");	
		echo ("Error2: No hay nada para mostrar: ");	
	}
	else
	{ 
		echo json_encode($formasDePago);
	}




		
	
		
}

?>
