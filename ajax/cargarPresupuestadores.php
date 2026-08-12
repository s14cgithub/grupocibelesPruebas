<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarPresupuestador")
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
		'nombre',
		'telefono',
		'inicial'
	];

	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();

	$order = [
			//['campo' => 'fecha', 'dir' => 'DESC'],
			['campo' => 'nombre', 'dir' => 'ASC']
		];
	
	$presupuestadores = cargarPresupuestadores($conn,$bbddSql, $campos, $filtros, $order);
	
	sqlsrv_close($conn);
	
	if (count($presupuestadores)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($presupuestadores);
	}
		
}

?>
