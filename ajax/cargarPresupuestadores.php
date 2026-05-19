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
		't1.id',
		't1.nombre',
		't1.telefono',
		't1.inicial'
	];

	$filtros = [
			//'cliente' => 'EMPRESA SL'
		];

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
